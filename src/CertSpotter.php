<?php

namespace Eaglewatch\DomainSearch;

use Eaglewatch\DomainSearch\Abstracts\HttpRequest;

class CertSpotter extends HttpRequest
{

    public function __construct() {}

    public function search(string $domain): array
    {
        $url = getConfig('certspotter.url') . "?domain=" . urlencode($domain) . "&include_subdomains=true&match_wildcards=true&expand=dns_names&expand=issuer&expand=issuer.caa_domains&expand=pubkey_der&expand=pubkey&expand=revocation&expand=problem_reporting&expand=cert_der&expand=issuer.website&expand=issuer.operator&expand=issuer.pubkey_der&expand=issuer.name_der";
        return $this->getFileContent($url);
    }
}
