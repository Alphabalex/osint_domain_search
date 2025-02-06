<?php

namespace Eaglewatch\DomainSearch;

use Eaglewatch\DomainSearch\Abstracts\HttpRequest;

class HackerTarget extends HttpRequest
{

    public function __construct() {}

    public function search(string $domain): array
    {
        $url = getConfig('hackertarget.url') . "?q=" . urlencode($domain) ."&output=json";
        return $this->getFileContent($url);
    }
}
