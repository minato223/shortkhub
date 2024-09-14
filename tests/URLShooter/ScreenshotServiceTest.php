<?php

namespace App\Http\Controllers;

use App\URLShooter\Domain\Entity\ScreenshotQuery;
use PHPUnit\Framework\TestCase;
use App\URLShooter\Domain\Service\ScreenshotService;
use App\URLShooter\Infrastructure\Client\ScreenshotApiClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ScreenshotServiceTest extends TestCase
{
    public const BASE_URL = 'http://localhost';
    protected function setUp(): void {}

    public function testGenerate()
    {
        $data = [
            'id' => 1,
            'name' => 'test',
            'path' => '/path/to/file',
            'createdAt' => '2023-01-01T00:00:00+00:00',
        ];
        $mockResponse = new MockResponse(json_encode($data));
        $mockHttpClient = new MockHttpClient($mockResponse);
        $screenshotApiClient = new ScreenshotApiClient($mockHttpClient, self::BASE_URL);
        $service = new ScreenshotService($screenshotApiClient);
        $query = new ScreenshotQuery('http://example.com');
        $result = $service->generate($query);
        $this->assertEquals($data, $result);
    }

    public function testGenerateWithErrorResponse()
    {
        $mockResponse = new MockResponse('', ['http_code' => 500]);
        $mockHttpClient = new MockHttpClient($mockResponse);
        $screenshotApiClient = new ScreenshotApiClient($mockHttpClient, self::BASE_URL);
        $service = new ScreenshotService($screenshotApiClient);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Request failed with status code 500');


        $query = new ScreenshotQuery('http://example.com');
        $result = $service->generate($query);    }

    public function testGenerateWithEmptyResponse()
    {
        $mockResponse = new MockResponse(json_encode([]));
        $mockHttpClient = new MockHttpClient($mockResponse);
        $screenshotApiClient = new ScreenshotApiClient($mockHttpClient, self::BASE_URL);
        $service = new ScreenshotService($screenshotApiClient);


        $query = new ScreenshotQuery('http://example.com');
        $result = $service->generate($query);
        $this->assertEquals([], $result);
    }

    public function testGetAll()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'test',
                'path' => '/path/to/file',
                'createdAt' => '2023-01-01T00:00:00+00:00',
            ],
            [
                'id' => 2,
                'name' => 'test2',
                'path' => '/path/to/file2',
                'createdAt' => '2023-01-02T00:00:00+00:00',
            ],
        ];
        $mockResponse = new MockResponse(json_encode($data));
        $mockHttpClient = new MockHttpClient($mockResponse);
        $screenshotApiClient = new ScreenshotApiClient($mockHttpClient, self::BASE_URL);
        $service = new ScreenshotService($screenshotApiClient);
        $result = $service->getAll();
        $this->assertEquals($data, $result);
    }

    public function testGetAllWithErrorResponse()
    {
        $mockResponse = new MockResponse('', ['http_code' => 500]);
        $mockHttpClient = new MockHttpClient($mockResponse);
        $screenshotApiClient = new ScreenshotApiClient($mockHttpClient, self::BASE_URL);
        $service = new ScreenshotService($screenshotApiClient);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Request failed with status code 500');

        $service->getAll();
    }

    public function testGetAllWithEmptyResponse()
    {
        $mockResponse = new MockResponse(json_encode([]));
        $mockHttpClient = new MockHttpClient($mockResponse);
        $screenshotApiClient = new ScreenshotApiClient($mockHttpClient, self::BASE_URL);
        $service = new ScreenshotService($screenshotApiClient);

        $result = $service->getAll();

        $this->assertEquals([], $result);
    }
}
