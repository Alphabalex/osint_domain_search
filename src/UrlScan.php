<?php

namespace Eaglewatch\DomainSearch;

use Eaglewatch\DomainSearch\Abstracts\HttpRequest;

class UrlScan extends HttpRequest
{

    public function __construct() {}

    public function search(string $domain): array
    {
        $url = getConfig('urlscan.url') . "?q=domain:" . urlencode($domain);

        return $this->getFileContent($url);
    }
}
