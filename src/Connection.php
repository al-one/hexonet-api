<?php

namespace Alone\Hexonet;

use GuzzleHttp;

class Connection
{

    protected $auth;

    protected $base_url = 'https://api.ispapi.net/api/call.cgi';

    public function __construct(Auth $auth,$base_url = null)
    {
        $this->auth = $auth;
        isset($base_url) && $this->base_url = $base_url;
    }

    public function call($cmd,$params = [])
    {
        $http = new GuzzleHttp\Client();
        $req = $http->get($this->base_url.'?'.http_build_query(array_merge($this->auth->getParams(),$params,['command' => $cmd])),[
            'headers' => [
                'User-Agent' => 'Alone-Hexonet-API-Client',
            ],
            'allow_redirects' => false,
        ]);
        return new Response($req);
    }

}
