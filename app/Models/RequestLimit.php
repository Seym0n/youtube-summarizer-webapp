<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;

final class RequestLimit
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly string $ip_address = '',
        public readonly int $request_count = 0,
        public readonly ?DateTime $last_request_date = null,
    ) {
    }
}