<?php

namespace App\Service;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticService
{
    private $client;

    public function __construct(string $host)
    {
        $this->client = ClientBuilder::create()
            ->setHosts([$host])
            ->build();
    }

    public function search(array $params): array
    {
        $response = $this->client->search($params);

        return $response->asArray();
    }

    public function index(string $index, string $id, array $document): void
    {
        $this->client->index([
            'index' => $index,
            'id'    => $id,
            'body'  => $document
        ]);
    }
}
