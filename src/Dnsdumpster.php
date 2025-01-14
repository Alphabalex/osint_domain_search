<?php

namespace Eaglewatch\DomainSearch;

use Eaglewatch\DomainSearch\Abstracts\HttpRequest;


class Dnsdumpster extends HttpRequest
{

    public function __construct()
    {
        $this->setApiUrl(config('dnsdumpster.api_url'));
        $this->setApiKey(config('dnsdumpster.api_key'));
        $this->additionalHeader = ['X-API-Key' => $this->apiKey];
    }

    public function search(string $domain): array
    {
        $this->setRequestOptions();
        return $this->setHttpResponse("/domain/{$domain}", 'GET', [])->getResponse();
    }
}
