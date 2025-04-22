<?php

namespace App\Services;

use App\Models\BaseDummyModel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class DummyService
{
    protected Client $client;
    protected string $baseUrl = 'https://dummyjson.com';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws GuzzleException
     */
    public function getEntities(BaseDummyModel $model, string $query = ""): array
    {
        $response = $this->client->get($this->baseUrl . $model->getEndpoint() . $query);
        $body = $response->getBody()->getContents();
        return json_decode($body, true);
    }
}
