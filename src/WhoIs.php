<?php

namespace Eaglewatch\DomainSearch;

use Eaglewatch\DomainSearch\Abstracts\HttpRequest;


class WhoIs extends HttpRequest
{
    private $options = array();
    private $apiKey;
    public function __construct(string $api_key, array $options = [])
    {
        $this->apiKey = $api_key;
        $this->options = array_merge(getConfig('whois'), $options);
    }

    public function search(string $domain, array $params = []): array
    {
        $this->setApiUrl($this->options['whois_url']);

        $defaultParams = [
            'apiKey' => $this->apiKey,
            'domainName' => $domain,
            'outputFormat' => 'JSON',
            '_hardRefresh' => 1,
            'ip' => 1
        ];

        $allowedParams = [
            'outputFormat',
            'rdap',
            'preferFresh',
            'da',
            'ip',
            'ipWhois',
            'checkProxyData',
            'thinWhois',
            'ignoreRawTexts',
            'callback',
            'registryRawText',
            'registrarRawText',
            'multiIdIana',
            '_parse',
            '_hardRefresh'
        ];

        $filteredParams = array_filter(
            $params,
            fn($key) => in_array($key, $allowedParams),
            ARRAY_FILTER_USE_KEY
        );

        $queryParams = array_merge($defaultParams, $filteredParams);
        $queryString = http_build_query($queryParams);

        return $this->sendHttpRequest("?{$queryString}", 'GET', []);
    }

    public function history(string $domain, array $params = []): array
    {
        $this->setApiUrl($this->options['whois_history_url']);

        $defaultParams = [
            'apiKey' => $this->apiKey,
            'domainName' => $domain,
            'outputFormat' => 'JSON',
            'mode' => 'purchase'
        ];

        $allowedParams = [
            'mode',
            'outputFormat',
            'skipLiveWhois',
            'sinceDate',
            'createdDateFrom',
            'createdDateTo',
            'updatedDateFrom',
            'updatedDateTo',
            'expiredDateFrom',
            'expiredDateTo'
        ];

        $filteredParams = array_filter(
            $params,
            fn($key) => in_array($key, $allowedParams),
            ARRAY_FILTER_USE_KEY
        );

        // Validate date format for date parameters
        $dateParams = [
            'sinceDate',
            'createdDateFrom',
            'createdDateTo',
            'updatedDateFrom',
            'updatedDateTo',
            'expiredDateFrom',
            'expiredDateTo'
        ];

        foreach ($dateParams as $dateParam) {
            if (isset($filteredParams[$dateParam])) {
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $filteredParams[$dateParam])) {
                    throw new \InvalidArgumentException(
                        "Invalid date format for {$dateParam}. Use YYYY-MM-DD format."
                    );
                }
            }
        }

        $payload = array_merge($defaultParams, $filteredParams);

        return $this->sendHttpRequest("", 'POST', $payload);
    }

    public function reverseSearch(array $includeTerms, array $excludeTerms = [], array $params = []): array
    {
        if (empty($includeTerms)) {
            throw new \InvalidArgumentException('At least one include term is required');
        }

        if (count($includeTerms) > 4 || count($excludeTerms) > 4) {
            throw new \InvalidArgumentException('Maximum 4 terms allowed for include/exclude');
        }

        $this->setApiUrl($this->options['reverse_whois_url']);

        $defaultParams = [
            'apiKey' => $this->apiKey,
            'searchType' => 'history',
            'mode' => 'purchase',
            'punycode' => true,
            'responseFormat' => 'JSON',
            'basicSearchTerms' => [
                'include' => $includeTerms,
                'exclude' => $excludeTerms
            ]
        ];

        $allowedParams = [
            'searchType',
            'mode',
            'punycode',
            'includeAuditDates',
            'responseFormat',
            'createdDateFrom',
            'createdDateTo',
            'updatedDateFrom',
            'updatedDateTo',
            'expiredDateFrom',
            'expiredDateTo',
            'searchAfter',
            'advancedSearchTerms'
        ];

        $filteredParams = array_filter(
            $params,
            fn($key) => in_array($key, $allowedParams),
            ARRAY_FILTER_USE_KEY
        );

        // Validate date parameters
        $dateParams = [
            'createdDateFrom',
            'createdDateTo',
            'updatedDateFrom',
            'updatedDateTo',
            'expiredDateFrom',
            'expiredDateTo'
        ];

        foreach ($dateParams as $dateParam) {
            if (isset($filteredParams[$dateParam])) {
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $filteredParams[$dateParam])) {
                    throw new \InvalidArgumentException(
                        "Invalid date format for {$dateParam}. Use YYYY-MM-DD format."
                    );
                }
            }
        }

        $payload = array_merge($defaultParams, $filteredParams);

        return $this->sendHttpRequest('', 'POST', $payload);
    }

    public function screenshot(string $url, array $params = [])
    {
        if (empty($url)) {
            throw new \InvalidArgumentException('URL is required');
        }

        $this->setApiUrl($this->options['whois_screenshot_url']);

        $defaultParams = [
            'apiKey' => $this->apiKey,
            'url' => $url,
            'imageOutputFormat' => 'base64',
            'errorsOutputFormat' => 'JSON',
            'type' => 'jpg',
            'width' => 800,
            'height' => 600,
            'mode' => 'fast',
            'credits' => 'SA',
        ];

        $allowedParams = [
            'credits',
            'imageOutputFormat',
            'errorsOutputFormat',
            'type',
            'quality',
            'width',
            'height',
            'thumbWidth',
            'mode',
            'scroll',
            'scrollPosition',
            'fullPage',
            'noJs',
            'delay',
            'timeout',
            'scale',
            'retina',
            'ua',
            'cookies',
            'mobile',
            'touchScreen',
            'landscape',
            'failOnHostnameChange'
        ];

        $filteredParams = array_filter(
            $params,
            fn($key) => in_array($key, $allowedParams),
            ARRAY_FILTER_USE_KEY
        );

        // Validate numeric parameters
        $numericValidations = [
            'quality' => ['min' => 40, 'max' => 99],
            'width' => ['min' => 100, 'max' => 3000],
            'height' => ['min' => 100, 'max' => 3000],
            'delay' => ['min' => 0, 'max' => 10000],
            'timeout' => ['min' => 1000, 'max' => 30000],
            'scale' => ['min' => 0.5, 'max' => 4.0]
        ];

        foreach ($numericValidations as $param => $limits) {
            if (isset($filteredParams[$param])) {
                $value = $filteredParams[$param];
                if ($value < $limits['min'] || $value > $limits['max']) {
                    throw new \InvalidArgumentException(
                        "{$param} must be between {$limits['min']} and {$limits['max']}"
                    );
                }
            }
        }

        // Validate thumbWidth if present
        if (isset($filteredParams['thumbWidth'])) {
            $width = $filteredParams['width'] ?? $defaultParams['width'];
            if ($filteredParams['thumbWidth'] < 50 || $filteredParams['thumbWidth'] > $width) {
                throw new \InvalidArgumentException(
                    "thumbWidth must be between 50 and {$width}"
                );
            }
        }

        $queryParams = array_merge($defaultParams, $filteredParams);
        $queryString = http_build_query($queryParams);

        return $this->sendHttpRequest("?{$queryString}", 'GET', [], 'raw');
    }

    public function subdomains(string $domain, array $params = []): array
    {
        if (empty($domain)) {
            throw new \InvalidArgumentException('Domain name is required');
        }

        $this->setApiUrl($this->options['whois_subdomains_url']);

        $defaultParams = [
            'apiKey' => $this->apiKey,
            'domainName' => $domain,
            'outputFormat' => 'JSON'
        ];

        $allowedParams = [
            'outputFormat'
        ];

        $filteredParams = array_filter(
            $params,
            fn($key) => in_array($key, $allowedParams),
            ARRAY_FILTER_USE_KEY
        );

        $queryParams = array_merge($defaultParams, $filteredParams);
        $queryString = http_build_query($queryParams);

        return $this->sendHttpRequest("?{$queryString}", 'GET', []);
    }

    public function threatIntelligence(string $ioc, array $params = []): array
    {
        if (empty($ioc)) {
            throw new \InvalidArgumentException('IOC search term is required');
        }

        $this->setApiUrl($this->options['threat_intelligence_url']);

        $defaultParams = [
            'apiKey' => $this->apiKey,
            'ioc' => $ioc,
            'outputFormat' => 'JSON',
            'size' => 100
        ];

        $allowedParams = [
            'outputFormat',
            'size'
        ];

        $filteredParams = array_filter(
            $params,
            fn($key) => in_array($key, $allowedParams),
            ARRAY_FILTER_USE_KEY
        );

        // Validate size parameter
        if (isset($filteredParams['size'])) {
            $size = (int)$filteredParams['size'];
            if ($size < 1 || $size > 10000) {
                throw new \InvalidArgumentException('Size must be between 1 and 10000');
            }
        }

        $queryParams = array_merge($defaultParams, $filteredParams);
        $queryString = http_build_query($queryParams);

        return $this->sendHttpRequest("?{$queryString}", 'GET', []);
    }

    public function categorize(string $url, array $params = []): array
    {
        if (empty($url)) {
            throw new \InvalidArgumentException('URL is required');
        }

        $this->setApiUrl($this->options['website_categorization_url']);

        $defaultParams = [
            'apiKey' => $this->apiKey,
            'url' => $url,
            'outputFormat' => 'JSON',
            'minConfidence' => 0.55
        ];

        $allowedParams = [
            'outputFormat',
            'minConfidence'
        ];

        $filteredParams = array_filter(
            $params,
            fn($key) => in_array($key, $allowedParams),
            ARRAY_FILTER_USE_KEY
        );

        // Validate minConfidence if present
        if (isset($filteredParams['minConfidence'])) {
            $confidence = (float)$filteredParams['minConfidence'];
            if ($confidence < 0.00 || $confidence > 1.00) {
                throw new \InvalidArgumentException('minConfidence must be between 0.00 and 1.00');
            }
        }

        $queryParams = array_merge($defaultParams, $filteredParams);
        $queryString = http_build_query($queryParams);

        return $this->sendHttpRequest("?{$queryString}", 'GET', []);
    }

    public function reputation(string $domainOrIp, array $params = []): array
    {
        if (empty($domainOrIp)) {
            throw new \InvalidArgumentException('Domain name or IP address is required');
        }

        $this->setApiUrl($this->options['domain_reputation_url']);

        $defaultParams = [
            'apiKey' => $this->apiKey,
            'domainName' => $domainOrIp,
            'outputFormat' => 'JSON',
            'mode' => 'fast'
        ];

        $allowedParams = [
            'outputFormat',
            'mode'
        ];

        $filteredParams = array_filter(
            $params,
            fn($key) => in_array($key, $allowedParams),
            ARRAY_FILTER_USE_KEY
        );

        // Validate mode parameter
        if (isset($filteredParams['mode']) && !in_array($filteredParams['mode'], ['fast', 'full'])) {
            throw new \InvalidArgumentException('Mode must be either "fast" or "full"');
        }

        $queryParams = array_merge($defaultParams, $filteredParams);
        $queryString = http_build_query($queryParams);

        return $this->sendHttpRequest("?{$queryString}", 'GET', []);
    }

    public function geolocation($ip, array $params = []): array
    {
        $this->setApiUrl($this->options['ip_geolocation_url']);

        $defaultParams = [
            'apiKey' => $this->apiKey,
            'ipAddress' => $ip,
            'outputFormat' => 'JSON',
            'reverseIp' => 1
        ];

        $allowedParams = [
            'ipAddress',
            'domain',
            'email',
            'reverseIp',
            'outputFormat'
        ];

        $filteredParams = array_filter(
            $params,
            fn($key) => in_array($key, $allowedParams),
            ARRAY_FILTER_USE_KEY
        );

        // Validate reverseIp parameter if present
        if (isset($filteredParams['reverseIp']) && !in_array($filteredParams['reverseIp'], [0, 1])) {
            throw new \InvalidArgumentException('reverseIp must be either 0 or 1');
        }

        // Validate that at least one search parameter is provided
        if (!empty($filteredParams)) {
            $searchParams = array_intersect(['ipAddress', 'domain', 'email'], array_keys($filteredParams));
            if (count($searchParams) > 1) {
                throw new \InvalidArgumentException('Only one search parameter (ipAddress, domain, or email) can be specified');
            }
        }

        $queryParams = array_merge($defaultParams, $filteredParams);
        $queryString = http_build_query($queryParams);

        return $this->sendHttpRequest("?{$queryString}", 'GET', []);
    }
}
