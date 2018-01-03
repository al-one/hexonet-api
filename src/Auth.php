<?php

namespace Alone\Hexonet;

class Auth
{

    protected $params = [];

    public function __construct($s_login,$s_pw = null,$s_entity = null,$s_user = null)
    {
        $opt = array_merge(compact('s_login','s_pw','s_entity','s_user'),is_array($s_login) ? $s_login : []);
        $this->params = $opt;
    }

    public function getParams()
    {
        return $this->params ?: [];
    }

}
