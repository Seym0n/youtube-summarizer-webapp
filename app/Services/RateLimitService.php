<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\RequestLimit;
use DateTime;

use function Tempest\Database\query;

final class RateLimitService
{
    private const DAILY_LIMIT = 10;

    public function isAllowed(string $ipAddress): bool
    {
        $limit = $this->getRequestLimit($ipAddress);
        
        if ($limit === null) {
            return true; // First request from this IP
        }

        $today = new DateTime();
        $lastRequestDate = $limit->last_request_date ?? new DateTime();
        
        // Reset count if it's a new day
        if ($lastRequestDate->format('Y-m-d') !== $today->format('Y-m-d')) {
            return true;
        }

        return $limit->request_count < self::DAILY_LIMIT;
    }

    public function recordRequest(string $ipAddress): void
    {
        $limit = $this->getRequestLimit($ipAddress);
        $today = new DateTime();
        
        if ($limit === null) {
            // First request from this IP
            query(RequestLimit::class)
                ->insert(
                    ip_address: $ipAddress,
                    request_count: 1,
                    last_request_date: $today
                )
                ->execute();
        } else {
            $lastRequestDate = $limit->last_request_date ?? new DateTime();
            
            if ($lastRequestDate->format('Y-m-d') !== $today->format('Y-m-d')) {
                // New day, reset count
                query(RequestLimit::class)
                    ->update()
                    ->set('request_count = 1')
                    ->set('last_request_date = ?', $today)
                    ->where('ip_address = ?', $ipAddress)
                    ->execute();
            } else {
                // Same day, increment count
                query(RequestLimit::class)
                    ->update()
                    ->set('request_count = request_count + 1')
                    ->set('last_request_date = ?', $today)
                    ->where('ip_address = ?', $ipAddress)
                    ->execute();
            }
        }
    }

    public function getRemainingRequests(string $ipAddress): int
    {
        if (!$this->isAllowed($ipAddress)) {
            return 0;
        }

        $limit = $this->getRequestLimit($ipAddress);
        
        if ($limit === null) {
            return self::DAILY_LIMIT;
        }

        $today = new DateTime();
        $lastRequestDate = $limit->last_request_date ?? new DateTime();
        
        // Reset count if it's a new day
        if ($lastRequestDate->format('Y-m-d') !== $today->format('Y-m-d')) {
            return self::DAILY_LIMIT;
        }

        return max(0, self::DAILY_LIMIT - $limit->request_count);
    }

    private function getRequestLimit(string $ipAddress): ?RequestLimit
    {
        return query(RequestLimit::class)
            ->select()
            ->where('ip_address = ?', $ipAddress)
            ->first();
    }
}