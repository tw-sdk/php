<?php

namespace Twix;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Twix\Exception\TwixApiException;
use Twix\Exception\TwixAuthException;
use Twix\Exception\TwixValidationException;
use Twix\Service\ChatService;
use Twix\Service\OrderService;
use Twix\Service\PaymentService;
use Twix\Service\ProcessorService;
use Twix\Service\TickerService;
use Twix\Service\WalletService;
use Twix\Service\WebhookService;

class TwixClient
{
    private string $apiToken;
    private string $baseUrl;
    private GuzzleClient $httpClient;

    public function __construct(
        string $apiToken,
        string $base_url
    ) {
        $this->apiToken = $apiToken;
        // Убеждаемся, что base_url заканчивается слешем
        $this->baseUrl = rtrim($base_url, '/') . '/';

        $this->httpClient = new GuzzleClient([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiToken,
            ],
        ]);
    }

    public function request(string $method, string $uri, array $options = []): array
    {
        try {
            $normalizedUri = ltrim($uri, '/');
            $response = $this->httpClient->request($method, $normalizedUri, $options);

            $body = $response->getBody()->getContents();
            $decoded = json_decode($body, true);

            $data = is_array($decoded) ? $decoded : ['message' => $body];
            $data['code'] = $response->getStatusCode();

            return $data;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = $response->getBody()->getContents();

                $decoded = json_decode($body, true);
                $data = is_array($decoded) ? $decoded : ['message' => $body];
                $data['code'] = $response->getStatusCode();

                return $data;
            }

            throw $e;

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function chats(): ChatService
    {
        return new ChatService($this);
    }

    public function orders(): OrderService
    {
        return new OrderService($this);
    }

    public function payments(): PaymentService
    {
        return new PaymentService($this);
    }
    public function ticker(): TickerService
    {
        return new TickerService($this);
    }

    public function processor(): ProcessorService
    {
        return new ProcessorService($this);
    }

    public function wallet(): WalletService
    {
        return new WalletService($this);
    }

    public function webhook(): WebHookService
    {
        return new WebHookService($this);
    }
}
