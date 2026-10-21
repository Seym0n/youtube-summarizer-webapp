<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use Tempest\Container\Container;
use Tempest\Container\Initializer;

final class YouTubeSummarizerInitializer implements Initializer
{
    public function initialize(Container $container): YouTubeSummarizerService
    {
        return new YouTubeSummarizerService(new Client(), $_ENV['RAPIDAPI_KEY'] ?? '');
    }
}
