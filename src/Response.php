<?php

namespace Alone\Hexonet;

use Psr\Http\Message\ResponseInterface;

class Response
{

    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getHash()
    {
        $res = $this->getIni() ?: [];
        return $res['RESPONSE'];
    }

    public function getHashLower()
    {
        $res = $this->getIni(true) ?: [];
        return $res['response'];
    }

    public function getIni($lower = false)
    {
        return self::parseIni($this->getRaw(),$lower);
    }

    public function getRaw()
    {
        return $this->response->getBody();
    }

    public function __toString()
    {
        return (string)$this->getRaw();
    }

    public static function parseIni($str,$lower = false)
    {
        $ret = false;
        $lns = explode("\n",$str);
        $section = false;
        foreach($lns as $row)
        {
            $row = trim($row);
            if(!$row || $row[0] == '#' || $row[0] == ';') continue;
            if(preg_match('/^\[(.*?)\]$/',$row,$mat))
            {
                $section = $mat[1];
                continue;
            }
            elseif(preg_match('/^(.+?)\s*=\s*(.*)$/',$row,$mat))
            {
                $key = rtrim($mat[1]);
                $val = ltrim($mat[2]);
                $kls = [$key];
                if(preg_match('/^".*"$|^\'.*\'$/',$val))
                {
                    $val = mb_substr($val,1,mb_strlen($val) - 2);
                }
                if(preg_match_all('/\[(.*?)\]/',$key,$mls))
                {
                    $kls = $mls[1] ?: [];
                    array_unshift($kls,strstr($key,'[',true));
                }
                if($section)
                {
                    array_unshift($kls,$section);
                }
                $kmp = [];
                $len = count($kls);
                for($i = $len - 1;$i >= 0;$i--)
                {
                    $key = $kls[$i];
                    $kmp[$key] = $i == $len - 1 ? $val : $kmp;
                    $kmp = array_intersect_key($kmp,array_flip((array)$key));
                    if($lower)
                    {
                        $kmp = array_change_key_case($kmp,CASE_LOWER);
                    }
                }
                $ret = self::arrayMergeRecursiveDistinct($ret ?: [],$kmp);
            }
        }
        return $ret;
    }

    public static function arrayMergeRecursiveDistinct(array $array1,array $array2)
    {
        $merged = $array1;
        foreach($array2 as $key => $value)
        {
            if(is_array($value) && isset($merged[$key]) && is_array($merged[$key]))
            {
                $merged[$key] = self::arrayMergeRecursiveDistinct($merged[$key],$value);
            }
            else
            {
                $merged[$key] = $value;
            }
        }
        return $merged;
    }

}
