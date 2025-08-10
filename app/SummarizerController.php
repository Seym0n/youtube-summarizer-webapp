<?php

declare(strict_types=1);

namespace App;

use App\VideoSummary;
use App\Services\RateLimitService;
use App\Services\YouTubeSummarizerService;
use GuzzleHttp\Client;
use Tempest\Cache\Cache;
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\View\View;

use function Tempest\Database\query;
use function Tempest\view;
use Tempest\Http\Responses\Json;
use Tempest\Http\Responses\Ok;
use Tempest\Http\Status;

final readonly class SummarizerController
{
    public function __construct(
        private Cache $cache,
    ) {
    }

    #[Get('/')]
    public function home(): View
    {
        $totalVideos = $this->getTotalVideosCount();
        
        return view('home.view.php', [
            'totalVideos' => $totalVideos
        ]);
    }

    #[Post('/api/summarize')]
    public function summarize(SummarizeRequest $request): Json
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        // Initialize rate limiting service
        $rateLimitService = new RateLimitService();
        
        // Check rate limit
        if (!$rateLimitService->isAllowed($ipAddress)) {
            return new Json([
                'success' => false,
                'error' => 'Daily request limit exceeded (10 requests per day)',
                'remaining' => 0
            ], Status::TOO_MANY_REQUESTS);
        }

        // Initialize YouTube summarizer service
        $httpClient = new Client();
        $rapidApiKey = $_ENV['RAPIDAPI_KEY'] ?? '';
        
        if (empty($rapidApiKey)) {
            return new Json([
                'success' => false,
                'error' => 'API configuration error'
            ], Status::INTERNAL_SERVER_ERROR);
        }

        $summarizerService = new YouTubeSummarizerService($httpClient, $rapidApiKey);

        try {
            $result = $summarizerService->getSummary(
                $request->url,
                $request->type,
                $request->language
            );

            if ($result['success']) {
                // Record the request
                $rateLimitService->recordRequest($ipAddress);
                
                // Update statistics cache
                $this->incrementVideosCount();
                
                return new Json([
                    'success' => true,
                    'summary' => $result['summary'],
                    'cached' => $result['cached'],
                    'remaining' => $rateLimitService->getRemainingRequests($ipAddress) - 1
                ]);
            } else {
                return new Json([
                    'success' => false,
                    'error' => $result['error']
                ], Status::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            dd($e);
            return new Json([
                'success' => false,
                'error' => 'An unexpected error occurred'
            ], Status::INTERNAL_SERVER_ERROR);
        }
    }

    private function getTotalVideosCount(): int
    {
        return $this->fetchTotalVideosCount() ?? 0;
    }

    private function fetchTotalVideosCount(): int
    {
        $result = query(VideoSummary::class)
            ->count()
            ->execute();
            
        return $result ?? 0;
    }

    private function incrementVideosCount(): void
    {
        $currentCount = $this->getTotalVideosCount();
        $this->cache->put('total_videos_count', $currentCount + 1);
    }
}