<?php

declare(strict_types=1);

namespace App;

use App\VideoSummary;
use App\Services\RateLimitService;
use App\Services\YouTubeSummarizerService;
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
        private RateLimitService $rateLimitService,
        private YouTubeSummarizerService $summarizerService,
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

        // Check rate limit
        if (!$this->rateLimitService->isAllowed($ipAddress)) {
            return (new Json([
                'success' => false,
                'error' => 'Daily request limit exceeded (10 requests per day)',
                'remaining' => 0
            ]))->setStatus(Status::TOO_MANY_REQUESTS);
        }

        if (!$this->summarizerService->isConfigured()) {
            return (new Json([
                'success' => false,
                'error' => 'API configuration error'
            ]))->setStatus(Status::INTERNAL_SERVER_ERROR);
        }

        try {
            $result = $this->summarizerService->getSummary(
                $request->url,
                $request->type,
                $request->language
            );

            if ($result['success']) {
                // Record the request
                $this->rateLimitService->recordRequest($ipAddress);
                
                // Update statistics cache
                $this->incrementVideosCount();
                
                return new Json([
                    'success' => true,
                    'summary' => $result['summary'],
                    'cached' => $result['cached'],
                    'remaining' => $this->rateLimitService->getRemainingRequests($ipAddress) - 1
                ]);
            } else {
                return (new Json([
                    'success' => false,
                    'error' => $result['error']
                ]))->setStatus(Status::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return (new Json([
                'success' => false,
                'error' => 'An unexpected error occurred'
            ]))->setStatus(Status::INTERNAL_SERVER_ERROR);
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