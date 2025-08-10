<?php

declare(strict_types=1);

namespace App\Migrations;

use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final class CreateRequestLimitsTable implements DatabaseMigration
{
    public string $name = '2025-01-10_create_request_limits_table';

    public function up(): QueryStatement|null
    {
        return new CreateTableStatement('request_limits')
            ->primary()
            ->text('ip_address')
            ->integer('request_count')
            ->date('last_request_date')
            ->unique('ip_address');
    }

    public function down(): QueryStatement|null
    {
        return null; // For simplicity, we'll not implement rollback
    }
}