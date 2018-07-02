<?php
namespace core\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\dominio\functions\NegocioFunctions;
use core\util\{Firewall};

class CategoriaController {

    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function salvar(Request $request, Response $response, $args)
    {
        $esperado = [
            "id" => ["tipo" => "id", "obrigatorio" => false, "ifNotSet" => null],
            "negocio_id" => ["tipo" => "id", "obrigatorio" => true, "ifNotSet" => null],
            "nome" => ["tipo" => "string", "obrigatorio" => true, "ifNotSet" => null],
            "ativo" => ["tipo" => "boolean", "obrigatorio" => true, "ifNotSet" => null],
        ];
        $params = Firewall::sanitize($request, $esperado);

        NegocioFunctions::validaInputCadastroCategoria($params);

        $usuario = $request->getAttribute('usuario');

        //TODO: verficar se tem algum com mesmo nome
        //NegocioFunctions::validaRegrasNegocioSalvarCategoria($params);

        NegocioFunctions::updateNegocioCategoria($params, $usuario['_id']);

        //TODO: tirar esse response com JSON
        $return = $response->withJson('', 200);
        return $return;
    }

}