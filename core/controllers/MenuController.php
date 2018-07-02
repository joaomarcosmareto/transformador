<?php
namespace core\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\AppConfig;
use core\dominio\functions\{MenuFunctions, NegocioFunctions};
use core\util\auth\Auth;
use core\util\{i18n, Cache, Firewall, Logger, Mongo, ManipuladorArquivos, Retorno, UtilFunctions};
use MongoDB\BSON\ObjectID;

class MenuController {

    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function salvar(Request $request, Response $response, $args)
    {
        $esperado = [
            "id" => ["tipo" => "id", "obrigatorio" => false],
            "negocio_id" => ["tipo" => "id", "obrigatorio" => true],
            "titulo" => ["tipo" => "string", "obrigatorio" => true],
            "icone" => ["tipo" => "string", "obrigatorio" => false],
            "tipo" => ["tipo" => "int", "obrigatorio" => true],

            "biblia" => ["tipo" => "int", "obrigatorio" => false],
            "categoria_id" => ["tipo" => "id", "obrigatorio" => false],
            "conteudo_id" => ["tipo" => "id", "obrigatorio" => false],
            "link" => ["tipo" => "string", "obrigatorio" => false],
        ];
        $params = Firewall::sanitize($request, $esperado);

        MenuFunctions::validaInputCadastroMenu($params);

        $usuario = $request->getAttribute('usuario');

        MenuFunctions::validaRegrasNegocioMenu($params, $usuario['_id']);
        NegocioFunctions::updateNegocioMenu($params, $usuario['_id']);

        Logger::setFluxo("CONFIRM_MENU_ADMIN_SUCESSO");
        return $response->withStatus(200);
    }

    public function remover(Request $request, Response $response, $args)
    {
        $esperado = [
            "negocio_id" => ["tipo" => "id", "obrigatorio" => false, "ifNotSet" => null]
        ];
        $params = Firewall::sanitize($request, $esperado);

        $usuario = $request->getAttribute('usuario');

        $negocio_banco =    Mongo::retornaUm("negocio", "Negocio",
                            ['usuario_id', 'negocio_id'],
                            [$usuario['_id'], $params['negocio_id']], ["=", "="]);

        if($negocio_banco !== null)
        {
            $criteria = array();
            $criteria["negocio_id"]     = $negocio_banco->getId();
            $criteria["usuario_id"]     = $usuario['_id'];
            $criteria["ativo"]          = true;

            $set = array('$set' => array('ativo' => false, 'dataAlteracao' => UtilFunctions::obterDataAtualBanco(), 'usuarioAlteracao' => $usuario['_id']));

            if(Mongo::atualizar("negocio", $criteria, $set))
            {
                Logger::setFluxo("NEGOCIO_REMOVIDO", $usuario->getEmail());
                $retorno["sucesso"] = true;
            }
            else
            {
                $retorno["erro"][] = i18n::write("FALHA")." E2. ".i18n::write("EQUIPE_AVISADA")." ".i18n::write("TENTE_NOVAMENTE");
                Logger::setFluxo("NEGOCIO_REMOVIDO", $usuario->getEmail());
                $retorno["sucesso"] = false;
            }
        }
        else
        {
            Retorno::$data["erro"][] = i18n::write('NEGOCIO_NAO_PERTENCE_USUARIO');
            Retorno::$data["sucesso"] = false;
            Logger::setFluxo("NEGOCIO_NAO_PERTENCE_USUARIO", $usuario->getEmail());
        }
        return $response->withJson(UtilFunctions::RETORNO(Retorno::$data), 200);
    }

}