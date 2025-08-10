<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;

final class VideoSummary
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly string $video_id = '',
        public readonly string $url = '',
        public readonly string $summary_type = '',
        public readonly string $language = '',
        public readonly string $summary_content = '',
        public readonly ?DateTime $created_at = null,
    ) {
    }
}