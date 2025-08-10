<?php

declare(strict_types=1);

namespace App\Migrations;

use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final class CreateVideoSummariesTable implements DatabaseMigration
{
    public string $name = '2025-01-10_create_video_summaries_table';

    public function up(): QueryStatement|null
    {
        return new CreateTableStatement('video_summaries')
            ->primary()
            ->text('video_id')
            ->text('url')
            ->text('summary_type')
            ->text('language')
            ->text('summary_content')
            ->datetime('created_at')
            ->index('video_id')
            ->index('summary_type')
            ->index('language');
    }

    public function down(): QueryStatement|null
    {
        return null; // For simplicity, we'll not implement rollback
    }
}