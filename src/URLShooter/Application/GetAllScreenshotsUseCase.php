<?php

namespace App\URLShooter\Application;

use App\URLShooter\Domain\Service\ScreenshotServiceInterface;

class GetAllScreenshotsUseCase
{
    public function __construct(
        private ScreenshotServiceInterface $screenshotService
    ) {}

    public function execute(): array
    {
        return $this->screenshotService->getAll();
    }
}
