<?php

namespace Eaglewatch\DomainSearch;

use Eaglewatch\DomainSearch\Abstracts\HttpRequest;

class WayBack extends HttpRequest
{
    private $options = array();
    public function __construct(array $options = [])
    {
        $this->options = array_merge(getConfig('wayback'), $options);
    }

    public function search(string $domain): array
    {
        $url = $this->options['api_url'] . "?url=*." . urlencode($domain) . "/*&output=json&collapse=urlkey";
        return $this->getFileContent($url);
    }

    public function available(string $domain): array
    {
        $url = $this->options['availability_url'] . "?url=" . urlencode($domain);
        return $this->getFileContent($url);
    }
}
