<?php

namespace Eaglewatch\DomainSearch;

use Eaglewatch\DomainSearch\Abstracts\HttpRequest;

class VirusTotal extends HttpRequest
{

    public function __construct() {}

    public function search(string $domain): array
    {
        $url = config('virustotal.v2_url') . "?domain=" . urlencode($domain) . "&apikey=" . config('virustotal.api_key');
        return $this->getFileContent($url);
    }
}
