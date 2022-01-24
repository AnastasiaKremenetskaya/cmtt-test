<?php

namespace Tests\Feature\Ad;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class InvalidPostDataTest extends TestCase
{
    private $http;

    public function setUp(): void
    {
        $this->http = new Client(['base_uri' => 'http://nginx']);
    }

    public function tearDown(): void
    {
        $this->http = null;
    }

    public function testPostWrongPriceData()
    {
        $ad = [
            'text' => 'Advertisement1',
            'price' => -2,
            'limit' => 1000,
            'banner' => 'https://linktoimage.png',
        ];

        $response = $this->http->request('POST', '/ads', [
            'form_params' => $ad
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=utf-8", $contentType);

        $responseActual = $response->getBody()->getContents();
        $responseExpected = json_encode([
                                            'message' => [
                                                "The Price minimum is 0"
                                            ],
                                            'code' => 400,
                                            'data' => [],
                                        ]);

        $this->assertJsonStringEqualsJsonString($responseExpected, $responseActual);
    }

    public function testPostNoTextData()
    {
        $ad = [
            'text' => null,
            'price' => 1000,
            'limit' => 1000,
            'banner' => 'https://linktoimage.png',
        ];

        $response = $this->http->request('POST', '/ads', [
            'form_params' => $ad
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=utf-8", $contentType);

        $responseActual = $response->getBody()->getContents();
        $responseExpected = json_encode([
                                            'message' => [
                                                "The Text is required"
                                            ],
                                            'code' => 400,
                                            'data' => [],
                                        ]);

        $this->assertJsonStringEqualsJsonString($responseExpected, $responseActual);
    }

    public function testPostWrongLimitData()
    {
        $ad = [
            'text' => 'Ad',
            'price' => 1000,
            'limit' => null,
            'banner' => 'https://linktoimage.png',
        ];

        $response = $this->http->request('POST', '/ads', [
            'form_params' => $ad
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=utf-8", $contentType);

        $responseActual = $response->getBody()->getContents();
        $responseExpected = json_encode([
                                            'message' => [
                                                "The Limit is required"
                                            ],
                                            'code' => 400,
                                            'data' => [],
                                        ]);

        $this->assertJsonStringEqualsJsonString($responseExpected, $responseActual);
    }

    public function testPostWrongBannerData()
    {
        $ad = [
            'text' => 'Ad',
            'price' => 1000,
            'limit' => 10,
            'banner' => 1000,
        ];

        $response = $this->http->request('POST', '/ads', [
            'form_params' => $ad
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=utf-8", $contentType);

        $responseActual = $response->getBody()->getContents();
        $responseExpected = json_encode([
                                            'message' => [
                                                "Invalid Banner link"
                                            ],
                                            'code' => 400,
                                            'data' => [],
                                        ]);

        $this->assertJsonStringEqualsJsonString($responseExpected, $responseActual);
    }
}
