<?php

namespace App\URLShooter\Application;

use App\URLShooter\Domain\Entity\ScreenshotQuery;
use App\URLShooter\Domain\Service\ScreenshotServiceInterface;

class GenerateScreenshotUseCase
{
    public function __construct(
        private ScreenshotServiceInterface $screenshotService
    ) {}

    public function execute(ScreenshotQuery $query): array
    {
        return $this->screenshotService->generate($query);
    }
}
