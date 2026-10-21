<?php

declare(strict_types=1);

namespace Tests;

use App\Migrations\CreateRequestLimitsTable;
use App\Migrations\CreateVideoSummariesTable;
use Tempest\Database\Config\SQLiteConfig;
use Tempest\Database\Migrations\CreateMigrationsTable;

abstract class DatabaseTestCase extends IntegrationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Never touch the real database file
        $this->container->config(new SQLiteConfig(path: ':memory:'));

        $this->migrate(CreateMigrationsTable::class, CreateRequestLimitsTable::class, CreateVideoSummariesTable::class);
    }
}
