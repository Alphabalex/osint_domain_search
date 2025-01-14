<?php

namespace Eaglewatch\DomainSearch;

class WebCheckAnalyzer
{

    public function __construct() {}

    /**
     * Analyze a URL using Web-Check API
     *
     * @param string $url The URL to analyze
     * @return array Analysis results
     * @throws \Exception
     */
    public function analyze(string $url): array
    {
        $url = config('webcheck.api_url') . "?url=" . urlencode($url);

        // Fetch the JSON data from the API endpoint
        $response = @file_get_contents($url);
        if ($response === FALSE) {
            throw new \Exception("Unable to fetch data from $url");
        }


        // Decode the JSON response
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Failed to decode JSON: " . json_last_error_msg());
        }

        return $data;
    }
}
