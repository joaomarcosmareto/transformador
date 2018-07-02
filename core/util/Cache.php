<?php

namespace core\util;

use Redis;
use core\AppConfig;

class Cache {

    private static $redis;

    private function __construct() { }

    public static function getInstance($host = null, $porta = null, $senha = null): Redis
    {
        if (!isset(self::$redis))
        {
            self::$redis = new Redis();
            self::$redis->connect($host, $porta);
            self::$redis->auth($senha);
        }
        return self::$redis;
    }

    public static function close()
    {
        if (isset(self::$redis))
        {
            self::$redis->close();
            self::$redis = null;
        }
    }

    //se ja tiver a chave sobrescreve o valor
    public static function set($key, $value, $timeout = 0)
    {
        self::$redis->set($key, $value, $timeout);
    }

    //quando não tem a chave retorna NULL
    public static function get($key)
    {
        self::$redis->get($key);
    }

    public static function existe($key)
    {
//        return xcache_isset((string)$key);
        return null;
    }

    //quando não tem, não da erro
    public static function delete($key)
    {
        self::$redis->delete($key);
    }

}
