<?php

declare(strict_types=1);

namespace App;

use Tempest\Http\Request;
use Tempest\Http\IsRequest;

final class SummarizeRequest implements Request
{
    use IsRequest;

    public string $url;
    public string $type;
    public string $language;
}