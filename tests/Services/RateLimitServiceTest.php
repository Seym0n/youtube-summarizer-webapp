<?php

declare(strict_types=1);

namespace Tests\Services;

use App\Services\RateLimitService;
use Tests\DatabaseTestCase;

/**
 * @internal
 */
final class RateLimitServiceTest extends DatabaseTestCase
{
    public function test_first_request_is_allowed(): void
    {
        $service = new RateLimitService();

        $this->assertTrue($service->isAllowed('1.1.1.1'));
        $this->assertSame(10, $service->getRemainingRequests('1.1.1.1'));
    }

    public function test_recording_requests_decrements_remaining(): void
    {
        $service = new RateLimitService();

        $service->recordRequest('1.1.1.1');
        $service->recordRequest('1.1.1.1');

        $this->assertSame(8, $service->getRemainingRequests('1.1.1.1'));
    }

    public function test_blocked_after_daily_limit(): void
    {
        $service = new RateLimitService();

        for ($i = 0; $i < 10; $i++) {
            $service->recordRequest('1.1.1.1');
        }

        $this->assertFalse($service->isAllowed('1.1.1.1'));
        $this->assertSame(0, $service->getRemainingRequests('1.1.1.1'));
    }

    public function test_limits_are_tracked_per_ip(): void
    {
        $service = new RateLimitService();

        for ($i = 0; $i < 10; $i++) {
            $service->recordRequest('1.1.1.1');
        }

        $this->assertTrue($service->isAllowed('2.2.2.2'));
    }
}
