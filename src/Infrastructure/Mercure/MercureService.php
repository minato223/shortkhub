<?php

namespace App\Infrastructure\Mercure;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

class MercureService
{

    public function __construct(
        readonly private SerializerInterface $serializerInterface,
        readonly private HubInterface $hub
    ) {}

    public function sendMessage(string $message = "Hello", string $topic = "/", bool $isPrivate = false)
    {
        $data = json_encode(['message' => $message, 'isPrivate' => $isPrivate, 'topic' => $topic]);
        $update = new Update($topic, $data, $isPrivate);
        $this->hub->publish($update);
    }

    public function sendCustomMessage(string $message = "Hello", string $topic = "/", bool $isPrivate = false)
    {
        $httpClient = HttpClient::create();
        $data = [
            'topic' => $topic,
            'data' => $message,
        ];
        $response = $httpClient->request('POST', 'http://localhost:8088/.well-known/mercure', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdfX0.a8cjcSRUAcHdnGNMKifA4BK5epRXxQI0UBp2XpNrBdw',
            ],
            'body' => $data,
        ]);
        $statusCode = $response->getStatusCode();
        $content = $response->getContent(false);
    }
}
