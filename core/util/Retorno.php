<?php

namespace core\util;

use core\dominio\Log;
use core\util\Mongo;
use core\AppConfig;
use Psr\Http\Message\ServerRequestInterface;

class Retorno {

    public static $data;

    private function __construct() { }

    public static function init()
    {
        //TODO: ver o que realmente precisa ficar aqui
        self::$data = ["dadosInvalidos" => [], "erro" => [], "msg" => []];
    }
}