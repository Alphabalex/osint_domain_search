<?php
namespace Eaglewatch\DomainSearch\Abstracts;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Client\Response;
use GuzzleHttp\Promise\PromiseInterface;


abstract class HttpRequest
{
    protected string $baseUrl, $apiKey;
    protected $client;
    protected $response;
    protected array $additionalHeader = [];

    public function __construct()
    {
        //$this->setApiUrl();
    }

    protected function setApiUrl($apiBaseUrl): void
    {
        $this->baseUrl = $apiBaseUrl;
    }

    protected function setApiKey($key): void
    {
        $this->apiKey = $key;
    }

    protected function setRequestOptions()
    {
        $headers = [
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            ...$this->additionalHeader
        ];
        $this->client = new Client(
            [
                'base_uri' => $this->baseUrl,
                'headers' => $headers
            ]
        );
    }

    protected function setHttpResponse($relativeUrl, $method, $body = [])
    {
        if (is_null($method)) {
            throw new Exception("Request method must be specified");
        }

        $this->response = $this->client->{strtolower($method)}(
            $this->baseUrl . $relativeUrl,
            ["body" => json_encode($body)]
        );
        return $this;
    }

    protected function getResponse()
    {
        $data = json_decode($this->response->getBody(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Failed to decode JSON: " . json_last_error_msg());
        }
        return $data;
    }
}
