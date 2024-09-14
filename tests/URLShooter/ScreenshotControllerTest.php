<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScreenshotControllerTest extends WebTestCase
{
    public function testGenerateScreenshot(): void
    {
        $client = static::createClient();
        
        // Simuler une requête POST avec un payload JSON
        $client->request(
            'POST', 
            '/screenshot/generate', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['url' => 'http://example.com'])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        
        $responseContent = $client->getResponse()->getContent();
        $data = json_decode($responseContent, true);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
    }

    public function testGenerateScreenshotWithInvalidPayload(): void
    {
        $client = static::createClient();
        
        // Simuler une requête POST avec un payload invalide
        $client->request(
            'POST', 
            '/screenshot/generate', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['invalid_field' => 'invalid_value'])
        );

        $this->assertResponseStatusCodeSame(400); // On attend un statut 400 pour une requête incorrecte
    }
}
