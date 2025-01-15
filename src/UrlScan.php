<?php

namespace Eaglewatch\DomainSearch;

class UrlScan
{

    public function __construct() {}

    public function search(string $domain): array
    {
        $url = config('urlscan.url') . "?q=" . urlencode($domain);

        // Fetch the JSON data from the API endpoint
        $jsonData = @file_get_contents($url);
        if ($jsonData === FALSE) {
            throw new \Exception("Unable to fetch data from $url");
        }

        // Decode the JSON response
        $data = json_decode($jsonData, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Failed to decode JSON: " . json_last_error_msg());
        }

        return $data;
    }
}
