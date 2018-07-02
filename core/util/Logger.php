<?php

namespace core\util;

use core\dominio\Log;
use core\util\Mongo;
use core\AppConfig;
use Psr\Http\Message\ServerRequestInterface;

use MongoDB\BSON\ObjectID;

class Logger {

    private static $monolog;
    private static $log;

    private function __construct() { }

    public static function getInstance(array $settings, ServerRequestInterface $request)
    {
        if (!isset(self::$monolog))
        {
            self::$monolog = new \Monolog\Logger($settings['name']);
            self::$monolog->pushProcessor(new \Monolog\Processor\UidProcessor());
            self::$monolog->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        }
        self::init($request);
    }

    public static function init(ServerRequestInterface $request){
        $inicio = UtilFunctions::getCurrentTimestamp();

        self::$log = [
            '_id' => new ObjectID(),
            'metodo' => $request->getMethod(),
            'url' => $request->getUri()->getPath(),
            'duracao' => $inicio,

            'data' => UtilFunctions::obterDataAtualBanco(),
            'ip' => $_SERVER['REMOTE_ADDR']
        ];

        if($request->isGet())
            self::$log['param'] = $request->getQueryParams();
        else if($request->isPost())
            self::$log['param'] = $request->getParams();
    }

    public static function info(string $mensagem)
    {
        self::$monolog->info($mensagem);
    }

    public static function setFluxo($fluxo = null, $usuario = null)
    {
        if(self::$log !== null){
            if($usuario != null)
                self::$log['usuario'] = $usuario;
            if($fluxo != null)
                self::$log['fluxo'][] = $fluxo;
        }
    }

    public static function addQuery($query)
    {
        if(self::$log !== null){
            self::$log["queries"][] = $query;
        }
    }

    public static function finaliza(){
        $fim = UtilFunctions::getCurrentTimestamp();
        if(self::$log !== null){
            // self::$log->doc["queries"][] = $query;
            self::$log['duracao'] = ($fim - self::$log['duracao']);
            self::$log['memoria'] = memory_get_peak_usage(true);

            if(AppConfig::$LogarRetorno)
                self::$log['retorno'] = $retorno;

            Logger::salvar();
        }
    }

    private static function salvar()
    {
        Mongo::salvar("log", self::$log);
        return true;
    }

}