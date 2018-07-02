<?php
namespace core\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\AppConfig;
use core\dominio\functions\UsuarioFunctions;
use core\dominio\functions\NegocioFunctions;
use core\util\auth\Auth;
use core\util\{i18n, Cache, Firewall, Logger, Mongo, MailManager, ManipuladorArquivos, Retorno, UtilFunctions};

class RefreshAuth {

    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function refresh(Request $request, Response $response, $args)
    {
        if (!$request->hasHeader('Authorization')) {
            throw new \Exception("Authentication needed", 403);
        }

        $jwt_string = trim($request->getHeader('Authorization')[0]);

        $token = Auth::getToken($jwt_string, false);

        $newToken = null;

        $usuario = ['_id' => UtilFunctions::getMongoID($token->getClaim("_id"))];
        $_id = UtilFunctions::getMongoID(null);

        $newToken = Auth::generateToken($usuario, $_id);

        // $permissoes = UsuarioFunctions::getUserPermission($usuario);
        // $permissoes = implode(";", $permissoes);

        $permissoes = "1;2;3;4;5;6";

        Cache::set($newToken, $permissoes, Auth::$TIME_ADD_TO_EXPIRATION);

        Retorno::$data["sucesso"] = true;

        $response = $response->withHeader('Authorization', 'Bearer '. $newToken);
        $return = $response->withJson(UtilFunctions::RETORNO(Retorno::$data), 200);
        Logger::setFluxo("LOGIN_SUCESSO", $usuario['_id']);

        return $return;
    }
}
