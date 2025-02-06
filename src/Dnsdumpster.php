<?php

namespace Eaglewatch\DomainSearch;

use Eaglewatch\DomainSearch\Abstracts\HttpRequest;


class Dnsdumpster extends HttpRequest
{
    private $options = array();
    public function __construct(string $api_key, array $options = [])
    {
        $this->options = array_merge(config('dnsdumpster'), $options);
        $this->setApiUrl($this->options['api_url']);
        $this->setHeaders(['X-API-Key' => $api_key]);
    }

    public function search(string $domain): array
    {
        return $this->sendHttpRequest("/domain/{$domain}", 'GET', []);
    }
}
