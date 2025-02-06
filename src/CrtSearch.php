<?php

namespace Eaglewatch\DomainSearch;

use Eaglewatch\DomainSearch\Abstracts\HttpRequest;

class CrtSearch extends HttpRequest
{

    public function __construct() {}

    public function search(string $domain): array
    {
        $url = getConfig('crt.url') . "?q=" . urlencode($domain) . "&output=json";

        $data = $this->getFileContent($url);

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
