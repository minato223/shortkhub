<?php

namespace App\URLShooter\Domain\Service;

use App\URLShooter\Domain\Entity\ScreenshotQuery;

interface ScreenshotServiceInterface
{
    public function generate(ScreenshotQuery $query): array;
    public function getAll(): array;
}