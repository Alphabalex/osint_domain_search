<?php

namespace Eaglewatch\DomainSearch;

class CrtSearch
{

    public function __construct() {}

    public function search(string $domain): array
    {
        $url = config('crt.url') . "?q=" . urlencode($domain) . "&output=json";

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

        // Transform data if necessary
        $results = array_map(function ($item) {
            return [
                'issuerId' => $item['issuer_ca_id'] ?? '',
                'issuer' => $item['issuer_name'] ?? '',
                'commonName' => $item['common_name'] ?? '',
                'name' => $item['name_value'] ?? '',
                'id' => $item['id'] ?? '',
                'loggedAt' => $item['entry_timestamp'] ?? '',
                'notBefore' => $item['not_before'] ?? '',
                'notAfter' => $item['not_after'] ?? '',
                'serialNumber' => $item['serial_number'] ?? '',
            ];
        }, $data);

        return $results;
    }
}
