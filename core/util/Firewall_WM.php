<?php

namespace core\util;

use core\util\exceptions\BadRequestLimitException;
use core\util\exceptions\BadRequestMinParamException;
use core\util\exceptions\PermissaoNegadaException;
use core\util\exceptions\TokenExpiradoException;
use core\util\exceptions\TokenInvalidoException;

use core\AppConfig;
use core\util\Auth;
use core\util\Cache;
use core\util\Logger;

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

    //Define quantas requisições podem ser feitas por tempo.
    //Por exemplo: $deltaTempo = 1 (1 segundo), $limitContador = 1.
    //Dessa forma o server so vai responder a uma requisição por segundo
    //Se for $deltaTempo -1 então pode ser infinito
    static function requestLimitControl($deltaTempo, $limitContador)
    {
        if($deltaTempo === -1) return true;

        $keyBase = $_SERVER['REMOTE_ADDR'];

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
                if($redis->get($keyBase."_C") > $limitContador)
                {
                    $redis->close();
                    header('HTTP/ 429 Too Many Requests', false, 429);
                    throw new BadRequestLimitException();
                }
            }
        }
    }

    public static function requestMinParans($paransArray) {
        foreach ($paransArray as $param)
        {
            if(!isset($_REQUEST[$param]))
            {
                header('HTTP/ 400 Bad Request', false, 400);
                throw new BadRequestLimitException();
            }
        }
    }

}
