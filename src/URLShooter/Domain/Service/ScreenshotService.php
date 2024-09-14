<?php

namespace App\URLShooter\Domain\Service;

use App\URLShooter\Domain\Entity\ScreenshotQuery;
use App\URLShooter\Infrastructure\Client\ScreenshotApiClientInterface;

class ScreenshotService implements ScreenshotServiceInterface
{
    public function __construct(
        private ScreenshotApiClientInterface $screenshotApiClient
    ) {
    }
    public function generate(ScreenshotQuery $query): array
    {
        return $this->screenshotApiClient->post(['url' => $query->url]);
    }

    public function getAll(): array
    {
        return $this->screenshotApiClient->get();
    }
}