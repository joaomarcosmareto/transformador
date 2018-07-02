<?php

namespace core\util;

use core\AppConfig;
use core\util\Auth;
use core\util\Cache;
use core\util\Logger;
use MongoDB\BSON\ObjectID;

class Firewall {

    /**
    * 1º checa o intervalo de request.
    * 2º checa se o token está válido.
    * 3º checa se o usuário possui permissão.
    * 4º checa os minParams.
    * ver a chamada do arquivo desvincularAluno.php
    *
    * @param  array  $options ["minParans" => ["academia_id"], "token"=>true, "func"=> null]
    * @param  array $retorno passado por referência
    *
    * @return array $info
    */
    static function check($options = null, &$retorno){
        try{
            $requestLimit = $options["requestLimit"] ?? ["interval" => AppConfig::$RequestTimeLimit, "limit" => AppConfig::$RequestCountLimit];
            Firewall::requestLimitControl($requestLimit["interval"], $requestLimit["limit"]);

            $checkToken = $options["token"] ?? false;
            $token = null;
            $permissoes = null;
            $info = null;
            if($checkToken)
            {
                $token = Auth::getToken();
                Auth::check($token);
                $info = Auth::getInfo($token);
                $permissoes = Auth::getPermissionFromToken($token);
            }

            $func = $options["func"] ?? null;
            if($func !== null)
            {
                if(!Auth::checkPermission($permissoes, $func))
                {
                    throw new PermissaoNegadaException();
                }
            }

            $minParans = $options["minParams"] ?? [];
            Firewall::requestMinParans($minParans);

            return $info;

        }catch(BadRequestLimitException $e){
            $retorno["sucesso"] = false;
            $retorno["request_error"] = true;
            Logger::setFluxo("TOO_MANY_REQUESTS");
        }catch(TokenExpiradoException $e){
            $retorno["sucesso"] = false;
            $retorno["auth"]["expired"] = true;
            $retorno["erro"][] = i18n::write("TOKEN_EXPIRED");
            Logger::setFluxo("TOKEN_EXPIRED");
        }catch(TokenInvalidoException $e){
            $retorno["sucesso"] = false;
            $retorno["erro"][] = i18n::write("FALHA_AUTENTICACAO");
            Logger::setFluxo("FALHA_AUTENTICACAO");
        }catch(PermissaoNegadaException $e){
            $retorno["sucesso"] = false;
            $retorno["erro"][] = i18n::write("USUARIO_SEM_PERMISSAO");
            Logger::setFluxo("USUARIO_SEM_PERMISSAO");
        }catch(BadRequestMinParamException $e){
            $retorno["sucesso"] = false;
            $retorno["request_error"] = true;
            Logger::setFluxo("PARAMETROS_INVALIDOS");
        }
        finally{
            return $info;
        }

    }

    public static function checkBadLogin($deltaTempo, $limitContador)
    {
        if($deltaTempo === -1) return true;

        $keyBase = 'BAD_'.$_SERVER['REMOTE_ADDR'];

        $redis = Cache::getInstance();

        $t = time();//tempo em s

        $tRedis = $redis->get($keyBase."_T");
        if(!empty($tRedis) && !($t > $tRedis+$deltaTempo) && $redis->get($keyBase."_C") >= $limitContador)
        {
            return false;
        }
        return true;
    }

    public static function incrBadLogin($deltaTempo)
    {
        if($deltaTempo === -1) return true;

        $keyBase = 'BAD_'.$_SERVER['REMOTE_ADDR'];

        $redis = Cache::getInstance();

        $t = time();//tempo em s

        $tRedis = $redis->get($keyBase."_T");
        if(empty($tRedis))
        {
            $redis->setex($keyBase."_T", $deltaTempo, $t);
            $redis->setex($keyBase."_C", $deltaTempo, 1);
        }
        else
        {
            if($t > $tRedis+$deltaTempo)
            {
                $redis->setex($keyBase."_T", $deltaTempo, $t);
                $redis->setex($keyBase."_C", $deltaTempo, 1);
            }
            else
            {
                $redis->incr($keyBase."_C");
            }
        }
        $redis->close();
    }

    public static function sanitize($request, $esperado = null, $args = null) {
        $params = [];
        if($esperado !== null)
        {
            $params = $request->getParams();
            foreach ($esperado as $k=>$v)
            {
                if($v["obrigatorio"] && !isset($params[$k]))
                {
                    throw new \Exception("Error Missing Param", 400);
                }
                $params[$k] = FireWall::getValue($params, $k, $v);
            }
        }
        else if($args !== null)
        {
            $params = [];
            foreach ($args as $min)
            {
                $test = $request->getAttribute($min);
                if($test == null)
                {
                    throw new \Exception("Error Missing Param", 400);
                }
                $params[$min] = trim($test);
            }
        }
        return $params;
    }

    public static function getValue($params, $key, $config)
    {
        if(!isset($params[$key]) || $params[$key] === '')
            return $config["ifNotSet"] ?? null;

        $p = trim($params[$key]);

        $tipoRetorno = $config["tipo"];

        if($tipoRetorno != null){
            if($tipoRetorno === "string")
                return $p;
            else if($tipoRetorno === "int")
                return (int)$p;
            else if($tipoRetorno === "float")
                return (float)$p;
            else if($tipoRetorno === "double")
                return (double)$p;
            else if($tipoRetorno === "boolean")
                return $p === "1" || $p === "true" ? true : false;
        }

        if(AppConfig::$DB_TYPE === "Mongo" && $tipoRetorno === "id")
        {
            try {
                if($p === "null" || $p === null)
                    return null;
                return new ObjectID($p);
            } catch (InvalidArgumentException $exc) {
                return null;
            }
        }

        return $p;
    }

}
