<?php

declare(strict_types=1);

namespace App;

use App\Models\VideoSummary;
use App\Services\RateLimitService;
use App\Services\YouTubeSummarizerService;
use GuzzleHttp\Client;
use Tempest\Cache\Cache;
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\View\View;

use function Tempest\request;
use function Tempest\response;
use function Tempest\Database\query;
use function Tempest\view;

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
    public function summarize(): mixed
    {
        $requestData = request()->body();
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        
        // Validate required fields
        if (empty($requestData['url']) || empty($requestData['type']) || empty($requestData['language'])) {
            return response()->json([
                'success' => false,
                'error' => 'Missing required fields'
            ]);
        }

        // Initialize rate limiting service
        $rateLimitService = new RateLimitService();
        
        // Check rate limit
        if (!$rateLimitService->isAllowed($ipAddress)) {
            return response()->json([
                'success' => false,
                'error' => 'Daily request limit exceeded (10 requests per day)',
                'remaining' => 0
            ]);
        }

        // Initialize YouTube summarizer service
        $httpClient = new Client();
        $rapidApiKey = $_ENV['RAPIDAPI_KEY'] ?? '';
        
        if (empty($rapidApiKey)) {
            return response()->json([
                'success' => false,
                'error' => 'API configuration error'
            ]);
        }

        $summarizerService = new YouTubeSummarizerService($httpClient, $rapidApiKey);

        try {
            $result = $summarizerService->getSummary(
                $requestData['url'],
                $requestData['type'],
                $requestData['language']
            );

            if ($result['success']) {
                // Record the request
                $rateLimitService->recordRequest($ipAddress);
                
                // Update statistics cache
                $this->incrementVideosCount();
                
                return response()->json([
                    'success' => true,
                    'summary' => $result['summary'],
                    'cached' => $result['cached'],
                    'remaining' => $rateLimitService->getRemainingRequests($ipAddress) - 1
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An unexpected error occurred'
            ]);
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