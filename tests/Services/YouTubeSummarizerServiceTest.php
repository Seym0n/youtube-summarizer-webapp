<?php

declare(strict_types=1);

namespace Tests\Services;

use App\Services\YouTubeSummarizerService;
use App\VideoSummary;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Tests\DatabaseTestCase;

use function Tempest\Database\query;

/**
 * @internal
 */
final class YouTubeSummarizerServiceTest extends DatabaseTestCase
{
    private array $history = [];

    private function service(Response ...$responses): YouTubeSummarizerService
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));

        return new YouTubeSummarizerService(new Client(['handler' => $stack]), 'test-key');
    }

    public function test_fetches_and_caches_summary(): void
    {
        $service = $this->service(new Response(200, [], json_encode(['summary' => 'Great video'])));

        $result = $service->getSummary('https://www.youtube.com/watch?v=abc123&t=5', 'bulletpoints', 'en');

        $this->assertSame(['success' => true, 'summary' => 'Great video', 'cached' => false], $result);
        $this->assertCount(1, $this->history);

        $request = $this->history[0]['request'];
        $this->assertSame('test-key', $request->getHeaderLine('x-rapidapi-key'));
        $this->assertStringContainsString('type=bullets', $request->getUri()->getQuery());

        $this->assertSame(1, query(VideoSummary::class)->count()->execute());
    }

    public function test_returns_cached_summary_without_http_call(): void
    {
        $url = 'https://youtu.be/xyz789';
        $this->service(new Response(200, [], json_encode(['summary' => 'Cached text'])))
            ->getSummary($url, 'quick', 'en');

        $service = $this->service();
        $result = $service->getSummary($url, 'quick', 'en');

        $this->assertSame(['success' => true, 'summary' => 'Cached text', 'cached' => true], $result);
        $this->assertCount(0, $this->history);
    }

    public function test_invalid_api_response_fails(): void
    {
        $service = $this->service(new Response(200, [], json_encode(['nope' => true])));

        $result = $service->getSummary('https://youtu.be/abc', 'quick', 'en');

        $this->assertFalse($result['success']);
        $this->assertSame('Invalid API response', $result['error']);
    }

    public function test_api_error_is_reported(): void
    {
        $service = $this->service(new Response(500, [], 'boom'));

        $result = $service->getSummary('https://youtu.be/abc', 'quick', 'en');

        $this->assertFalse($result['success']);
        $this->assertStringStartsWith('API request failed:', $result['error']);
    }

    public function test_invalid_url_throws(): void
    {
        $this->expectExceptionMessage('Invalid YouTube URL format');

        $this->service()->getSummary('https://example.com/video', 'quick', 'en');
    }
}
