<?php

namespace Eaglewatch\DomainSearch;

use Eaglewatch\DomainSearch\Abstracts\HttpRequest;

class WebCheckAnalyzer extends HttpRequest
{
    private $options = array();
    public function __construct(array $options = [])
    {
        $this->options = array_merge(config('webcheck'), $options);
    }

    /**
     * Analyze a URL using Web-Check API
     *
     * @param string $url The URL to analyze
     * @return array Analysis results
     * @throws \Exception
     */
    public function analyze(string $url): array
    {
        $url = $this->options['api_url'] . "?url=" . urlencode($url);
        return $this->getFileContent($url);
    }
}
