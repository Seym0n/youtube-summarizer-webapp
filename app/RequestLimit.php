<?php

namespace App;

use DateTime;
use Tempest\Database\IsDatabaseModel;

final class RequestLimit
{
    use IsDatabaseModel;

    public function __construct(
        public readonly string $ip_address = '',
        public readonly int $request_count = 0,
        public readonly ?DateTime $last_request_date = null,
    ) {
    }
}
