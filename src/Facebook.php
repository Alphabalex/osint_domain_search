<?php

namespace Eaglewatch\DomainSearch;

use Exception;
use Eaglewatch\DomainSearch\Abstracts\HttpRequest;


class Facebook extends HttpRequest
{

    private $appId, $appSecret;
    private $options = array();

    public function __construct(string $appId, string $appSecret, array $options = [])
    {

        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->options = array_merge(config('facebook'), $options);
    }

    private function getAccessToken()
    {
        if (!$this->appId || !$this->appSecret) {
            throw new Exception("Facebook App ID and App Secret are required");
        }
        $this->setApiUrl($this->options['auth_url']);
        $response = $this->sendHttpRequest("?client_id={$this->appId}&client_secret={$this->appSecret}&grant_type=client_credentials", 'GET', []);
        if (!$response['access_token']) {
            throw new Exception("Access token not found in Facebook response");
        }
        return $response['access_token'];
    }

    public function search(string $domain): array
    {
        $accessToken = $this->getAccessToken();
        $this->setApiUrl($this->options['domain_url']);
        return $this->sendHttpRequest("?fields=domains&access_token={$accessToken}&query=*.{$domain}", 'GET', []);
    }
}
