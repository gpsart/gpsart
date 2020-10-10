<?php

declare(strict_types=1);

namespace App\Api\Service;

use Waddle\Activity;
use Waddle\Parsers\GPXParser;

class ActivityFileParser
{
    /** @var GPXParser */
    private $parser;

    public function __construct()
    {
        $this->parser = new GPXParser();
    }

    public function parse(string $filename): Activity
    {
        return $this->parser->parse($filename);
    }
}