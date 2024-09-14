<?php

namespace App\URLShooter\Domain\Entity;

class ScreenshotQuery
{
    public function __construct(
        public string $url
    ) {}
}