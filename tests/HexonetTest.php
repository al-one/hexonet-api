<?php

namespace Tests;

use Alone\Hexonet;

class HexonetTest extends TestCase
{

    protected $user = 'domain2256';
    protected $pass = 'an56.net';

    protected $auth;

    public function __construct()
    {
        parent::__construct();
        $this->auth = new Hexonet\Auth($this->user,$this->pass);
    }

    public function testApiResponse()
    {
        $api = new Hexonet\Connection($this->auth);
        $ret = $api->call('QueryEnvironmentList');
        $ini = $ret->getHashLower();
        //throw new \Exception($ret->getRaw().PHP_EOL.json_encode(compact('ini')));
        $this->assertEquals($ini['code'],200);
    }

    public function testCheckDomains()
    {
        $api = new Hexonet\Connection($this->auth);
        $ret = $api->call('CheckDomains',[
            'domain1' => 'alo.pw',
            'domain2' => 'anl.pw',
            'domain3' => 'hexonet-api.pw',
        ]);
        $ini = $ret->getHashLower();
        //throw new \Exception($ret->getRaw().PHP_EOL.json_encode(compact('ini')));
        $this->assertEquals(count($ini['property']['domaincheck']),3);
    }

}