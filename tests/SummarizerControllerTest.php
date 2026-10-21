<?php

declare(strict_types=1);

namespace Tests;

use App\Services\RateLimitService;
use App\Services\YouTubeSummarizerService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * @internal
 */
final class SummarizerControllerTest extends DatabaseTestCase
{
    private const BODY = [
        'url' => 'https://www.youtube.com/watch?v=abc123',
        'type' => 'quick',
        'language' => 'en',
    ];

    private function fakeApi(string $apiKey, Response ...$responses): void
    {
        $client = new Client(['handler' => HandlerStack::create(new MockHandler($responses))]);

        $this->container->singleton(
            YouTubeSummarizerService::class,
            new YouTubeSummarizerService($client, $apiKey),
        );
    }

    public function test_returns_summary(): void
    {
        $this->fakeApi('key', new Response(200, [], json_encode(['summary' => 'A summary'])));

        $response = $this->http->post('/api/summarize', self::BODY);

        $response->assertOk();
        $this->assertTrue($response->response->body['success']);
        $this->assertSame('A summary', $response->response->body['summary']);
        $this->assertFalse($response->response->body['cached']);
    }

    public function test_fails_when_api_key_is_missing(): void
    {
        $this->fakeApi('');

        $this->http
            ->post('/api/summarize', self::BODY)
            ->assertStatus(\Tempest\Http\Status::INTERNAL_SERVER_ERROR);
    }

    public function test_upstream_failure_returns_bad_request(): void
    {
        $this->fakeApi('key', new Response(200, [], json_encode(['nope' => true])));

        $this->http
            ->post('/api/summarize', self::BODY)
            ->assertStatus(\Tempest\Http\Status::BAD_REQUEST);
    }

    public function test_rate_limited_after_daily_limit(): void
    {
        $this->fakeApi('key');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $limits = new RateLimitService();

        for ($i = 0; $i < 10; $i++) {
            $limits->recordRequest($ip);
        }

        $this->http
            ->post('/api/summarize', self::BODY)
            ->assertStatus(\Tempest\Http\Status::TOO_MANY_REQUESTS);
    }
}
