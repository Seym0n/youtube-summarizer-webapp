<?php

namespace App;

use DateTime;
use Tempest\Database\IsDatabaseModel;
use Tempest\Validation\Rules\Length;

final class VideoSummary
{
    use IsDatabaseModel;

    public function __construct(
        #[Length(min: 1, max: 500)]
        public readonly string $video_id = '',
        #[Length(min: 1, max: 2000)]
        public readonly string $url = '',
        #[Length(min: 1, max: 50)]
        public readonly string $summary_type = '',
        #[Length(min: 1, max: 20)]
        public readonly string $language = '',
        public readonly string $summary_content = '',
        public readonly ?DateTime $created_at = null,
    ) {
    }
}
