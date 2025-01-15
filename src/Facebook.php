<?php

namespace Eaglewatch\DomainSearch;

use Exception;
use Eaglewatch\DomainSearch\Abstracts\HttpRequest;


class Facebook extends HttpRequest
{

    public function __construct() {}

    private function getAccessToken()
    {
        $appId = config('facebook.app_id');
        $appSecret = config('facebook.app_secret');
        if (!$appId || !$appSecret) {
            throw new Exception("Facebook App ID and App Secret are required");
        }
        $this->setApiUrl(config('facebook.auth_url'));
        $this->setRequestOptions();
        $response = $this->setHttpResponse("?client_id={$appId}&client_secret={$appSecret}&grant_type=client_credentials", 'GET', [])->getResponse();
        if (!$response['access_token']) {
            throw new Exception("Access token not found in Facebook response");
        }
        return $response['access_token'];
    }

    public function search(string $domain): array
    {
        $accessToken = $this->getAccessToken();
        $this->setApiUrl(config('facebook.domain_url'));
        $this->setRequestOptions();
        return $this->setHttpResponse("?fields=domains&access_token={$accessToken}&query=*.{$domain}", 'GET', [])->getResponse();
    }
}
