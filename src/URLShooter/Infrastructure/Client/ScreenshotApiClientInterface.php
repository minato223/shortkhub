<?php

namespace App\URLShooter\Infrastructure\Client;

interface ScreenshotApiClientInterface
{
    public function get(): array;
    public function post(array $data): array;
}