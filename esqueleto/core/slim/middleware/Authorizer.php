<?php
namespace core\slim\middleware;

use core\util\auth\{Auth};
use core\util\{i18n, Logger, Retorno, UtilFunctions};
use core\util\exceptions\{TokenExpiradoException};

class Authorizer{

    // private $redis_settings;
    // private $logger_settings;

    public function __construct()
    {

    }

    public function __invoke($request, $response, $next)
    {
        if (!$request->hasHeader('Authorization')) {
            throw new \Exception("Authentication needed", 403);
        }

        $jwt_string = trim($request->getHeader('Authorization')[0]);

        try{
            $token = Auth::getToken($jwt_string);
        }
        catch(TokenExpiradoException $e){
            Retorno::$data["auth"]["expired"] = true;
            Retorno::$data["erro"][] = i18n::write("TOKEN_EXPIRED");
            Logger::setFluxo("TOKEN_EXPIRED");

            $response = $response->withJson(UtilFunctions::RETORNO(Retorno::$data), 400);
            return $response;
        }

        if($token === null){
            throw new \Exception("Authentication needed", 403);
        }

        $usuario = ['_id' => UtilFunctions::getMongoID($token->getClaim("_id"))];
        $request = $request->withAttribute('usuario', $usuario);

        $response = $next($request, $response);
        return $response;
    }
}