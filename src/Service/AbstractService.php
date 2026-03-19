<?php

namespace Twix\Service;

use Twix\TwixClient;
use GuzzleHttp\Psr7\Response;

abstract class AbstractService
{
    protected TwixClient $client;

    public function __construct(TwixClient $client)
    {
        $this->client = $client;
    }

    protected function request(string $method, string $uri, array $options = []): array
    {
        return $this->client->request($method, $uri, $options);
    }
}
