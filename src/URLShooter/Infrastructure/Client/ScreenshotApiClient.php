<?php

namespace App\URLShooter\Infrastructure\Client;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ScreenshotApiClient implements ScreenshotApiClientInterface
{
    const API_ENDPOINT = '/api/screenshots';
    public function __construct(
        private HttpClientInterface $httpClient,
        #[Autowire('%env(URL_DOMAIN)%')]
        private string $baseUrl
        )
    {
    }

    public function get(): array
    {
        return $this->api(self::API_ENDPOINT, [], Request::METHOD_GET);
    }

    public function post(array $data): array
    {
        return $this->api(self::API_ENDPOINT, $data, Request::METHOD_POST, [
            'Content-Type' => 'application/json',
        ]);
    }

    private function api(string $endpoint, array $data = [], string $method = 'POST', array $headers = []): array
    {
        $response = $this->httpClient->request($method, sprintf('%s%s', $this->baseUrl, $endpoint), [
            'json' => $data,
            'headers' => $headers,
        ]);
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        }
        throw new \Exception('Request failed with status code ' . $response->getStatusCode() . ': ' . $response->getContent());
        // throw new \Exception('Request failed with status code ' . $response->getStatusCode());
    }
}