<?php

namespace Eaglewatch\DomainSearch;

use Eaglewatch\DomainSearch\Abstracts\HttpRequest;

class VirusTotal extends HttpRequest
{

    private $api_key;
    private $options = array();
    public function __construct(string $apiKey, array $options = [])
    {
        $this->api_key = $apiKey;
        $this->options = array_merge(config('virustotal'), $options);
    }

    public function search(string $domain): array
    {
        $url = $this->options['api_url'] . "?domain=" . urlencode($domain) . "&apikey=" . $this->api_key;
        return $this->getFileContent($url);
    }
}
