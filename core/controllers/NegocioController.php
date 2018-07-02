<?php
namespace core\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\AppConfig;
use core\dominio\functions\UsuarioFunctions;
use core\dominio\functions\NegocioFunctions;
use core\util\auth\Auth;
use core\util\{i18n, Cache, Firewall, Logger, Mongo, MailManager, ManipuladorArquivos, Retorno, UtilFunctions};
use MongoDB\BSON\ObjectID;

class NegocioController {

    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function listar(Request $request, Response $response, $args)
    {
        $esperado = [
            "paginaAtual" => ["tipo" => "int", "obrigatorio" => true],
            "numRegistros" => ["tipo" => "int", "obrigatorio" => true],
            //TODO: filtros
            //"ativo" => ["tipo" => "boolean", "obrigatorio" => true, "ifNotSet" => null],
        ];
        $params = Firewall::sanitize($request, $esperado);

        $usuario = $request->getAttribute('usuario');

        $inicio = ($params["paginaAtual"] -1) * $params["numRegistros"];

        Retorno::$data["itens"] = array();
        Retorno::$data["total"] = 0;

        $append = array(
            "skip" => $inicio,
            "limit" => $params["numRegistros"],
            "sort" => array("nome" => -1),
            "projection" => [
                //"id" => 1,
                "nome" => 1,
                "abreviacao" => 1
            ],
        );

        $campos         = ['colaboradores._id', 'removido'];
        $filtros        = [$usuario['_id'],  ['$ne' => true]];
        $comparadores   = ["=", "="];

        $auxMongo = Mongo::retornaTodos("negocio", $campos, $filtros, $comparadores, $append);

        $count = count($auxMongo);
        for ($i = 0; $i < $count; $i++) {
            $auxMongo[$i]["_id"] = (string)$auxMongo[$i]["_id"];
        }

        Retorno::$data["itens"] = $auxMongo;
        Retorno::$data["total"] = Mongo::retornaContagem("conteudo", $campos, $filtros, $comparadores);

        Logger::setFluxo("LISTAGEM_NEGOCIO_ADMIN_SUCESSO");

        $return = $response->withJson(UtilFunctions::RETORNO(Retorno::$data), 200);
        return $return;
    }

    public function salvar(Request $request, Response $response, $args)
    {
        $esperado = [
            "id" => ["tipo" => "id", "obrigatorio" => false],
            "nome" => ["tipo" => "string", "obrigatorio" => true],
            "abreviacao" => ["tipo" => "string", "obrigatorio" => false],
            "denominacao" => ["tipo" => "string", "obrigatorio" => true],

            "cep" => ["tipo" => "string", "obrigatorio" => false],
            "rua" => ["tipo" => "string", "obrigatorio" => false],
            "numero" => ["tipo" => "string", "obrigatorio" => false],
            "bairro" => ["tipo" => "string", "obrigatorio" => false],
            "cidade" => ["tipo" => "string", "obrigatorio" => false],
            "estado" => ["tipo" => "string", "obrigatorio" => false],

            "telefone" => ["tipo" => "string", "obrigatorio" => false],
            "celular" => ["tipo" => "string", "obrigatorio" => false],
            "email" => ["tipo" => "string", "obrigatorio" => false],
            "links" => ["tipo" => "string", "obrigatorio" => false],

            "removerLogo" => ["tipo" => "boolean", "obrigatorio" => false],
            "removerCapa" => ["tipo" => "boolean", "obrigatorio" => false],
        ];
        $params = Firewall::sanitize($request, $esperado);

        NegocioFunctions::validaInput($params);

        $usuario = $request->getAttribute('usuario');

        // se não está editando
        // if(!isset($params["id"]) || $params["id"] == null){

            // verifica se existe igreja do mesmo usuário com mesmo nome e denominação no banco.
            // $negocio_banco = Mongo::retornaUm("negocio", "Negocio",
            //     ["usuario_id", "nome", 'denominacao'],
            //     [$usuario->getId(), $params["nome"], $params["denominacao"]], ["=", "=", "="]);

            // // se existir, é porque nao precisa criar uma nova.
            // if($negocio_banco !== null)
            // {
            //     Retorno::$data["erro"][] = i18n::write('NEGOCIO_JA_EXISTE');
            // }
            // else{
            //     $negocio = NegocioFunctions::createFromCadastro($usuario->getId(), $params);
            // }
        // }
        //se estiver editando
        // else{
            //retorna o negócio do banco
            // $negocio = Mongo::retornaUm("negocio", "Negocio", ["_id"],[$params["id"]], ["="], ["limit"=>1]);
            // $negocio = NegocioFunctions::salvarNegocio($params, $usuario->getId());
        // }

        NegocioFunctions::validaRegras($params);
        $append = ['limit' => 1 ,'projection' => ['_id' => 1, 'logo' => 1, 'imagemCapa' => 1]];
        $negocio = Mongo::retornaUm("negocio", ["_id"],[$params["id"]], ["="], $append);
        $ma = new ManipuladorArquivos($request);

        if($params["removerLogo"])
        {
            $ma->removerArquivo(AppConfig::getImgPath().$negocio['logo']);
            $params['logo'] = null;
        }
        if($params["removerCapa"])
        {
            $ma->removerArquivo(AppConfig::getImgPath().$negocio['imagemCapa']);
            $params['imagemCapa'] = null;
        }

        if(!empty($ma->files["logo"]))
        {
            $fileData = (string)date("Y-m");
            $destination = "n/$fileData/";

            $fqdn = $ma->saveImage("logo", (string)$params['id'], $destination, 320, 240);

            if(!empty($fqdn))
            {
                if(!empty($negocio['logo']))
                    $ma->removerArquivo(AppConfig::getImgPath().$negocio['logo']);

                $params['logo'] = $fqdn;
            }
        }

        if(!empty($ma->files["imagemCapa"]))
        {
            $fileData = (string)date("Y-m");
            $destination = "n/$fileData/";

            $fqdn = $ma->saveImage("imagemCapa", $negocio['_id'], $destination, 320, 240);

            if(!empty($fqdn))
            {
                if(!empty($negocio['imagemCapa']))
                    $ma->removerArquivo(AppConfig::getImgPath().$negocio['imagemCapa']);

                $params['imagemCapa'] = $fqdn;
            }
        }

        NegocioFunctions::salvar($params, $usuario['_id']);

        $return = $response->withJson(UtilFunctions::RETORNO(Retorno::$data), 200);
        return $return;
    }

    public function selecionar(Request $request, Response $response, $args)
    {
        $esperado = [
            "id" => ["tipo" => "id", "obrigatorio" => false]
        ];
        $params = Firewall::sanitize($request, $esperado);

        //TODO: não gerou exceção por mandar parametro errado, ex: _id no lugar de id

        $usuario = $request->getAttribute('usuario');
        // var_dump($usuario);

        $append = array(
                    "projection" => array(
                        "nome" => 1,
                        "abreviacao" => 1,
                        "denominacao" => 1,
                        "logo" => 1,
                        "imagemCapa" => 1,
                        "cep" => 1,
                        "rua" => 1,
                        "numero" => 1,
                        "bairro" => 1,
                        "cidade" => 1,
                        "estado" => 1,
                        "telefone" => 1,
                        "celular" => 1,
                        "email" => 1,
                        "links" => 1,
                        "categorias" => 1,
                        "menus" => 1,
                        "colaboradores" => 1,
                    )
        );

        $negocio =    Mongo::retornaUm("negocio",
                            ['colaboradores._id', '_id'],
                            [$usuario['_id'], $params['id']], ["=", "="], $append);
        //var_dump($negocio);
        if($negocio !== null)
        {
            $negocio["id"] = (string)$negocio["_id"];
            unset($negocio["_id"]);
            unset($negocio["usuarioAlteracao"]);

            $auxColaboradores = [];
            for ($i = 0; $i < count($negocio["colaboradores"]); $i++) {
                $aux = ["id" => (string)$negocio["colaboradores"][$i]["_id"], "papel" => (string)$negocio["colaboradores"][$i]["papel"]];
                $auxColaboradores[] = $aux;
            }
            $negocio["colaboradores"] =  $auxColaboradores;

            $auxCategorias = [];
            $count_categorias = isset($negocio["categorias"]) ? count($negocio["categorias"]) : 0;
            for ($i = 0; $i < $count_categorias; $i++) {
                $aux = [
                            "id" => (string)$negocio["categorias"][$i]["_id"],
                            "nome" => (string)$negocio["categorias"][$i]["nome"],
                            "ativo" => (bool)$negocio["categorias"][$i]["ativo"] ? '1' : '0',
                        ];
                $auxCategorias[] = $aux;
            }
            $negocio["categorias"] =  $auxCategorias;

            $auxMenus = [];
            $count_menus = isset($negocio["menus"]) ? count($negocio["menus"]) : 0;
            for ($i = 0; $i < $count_menus; $i++) {

                $aux = [];

                if($negocio["menus"][$i]["conteudo_id"] !== null)
                {
                    $conteudo = Mongo::retornaUm("conteudo", ['_id'], [$negocio["menus"][$i]["conteudo_id"]], ["=", "="]);
                    if($conteudo !== null)
                    {
                        $aux["conteudo"]['id'] = (string)$negocio["menus"][$i]["conteudo_id"];
                        $aux["conteudo"]['titulo'] = $conteudo['titulo'];
                    }
                }
                $aux["id"] = (string)$negocio["menus"][$i]["_id"];
                $aux["titulo"] = $negocio["menus"][$i]["titulo"];
                $aux["icone"] = $negocio["menus"][$i]["icone"];
                $aux["tipo"] = (string)$negocio["menus"][$i]["tipo"];
                $aux["biblia"] = $negocio["menus"][$i]["biblia"];
                $aux["categoria"] = (string)$negocio["menus"][$i]["categoria_id"];
                $aux["link"] = $negocio["menus"][$i]["link"];
                $auxMenus[] = $aux;
            }
            $negocio["menus"] =  $auxMenus;

            Retorno::$data["data"] = $negocio;

            return $response->withJson(UtilFunctions::RETORNO(Retorno::$data), 200);
        }
        throw new \Exception(json_encode([i18n::write("NEGOCIO_INEXISENTE_OU_USUARIO_SEM_PERMISSAO")]), 400);
    }

    public function desativar(Request $request, Response $response, $args)
    {
        $esperado = [
            "negocio_id" => ["tipo" => "id", "obrigatorio" => false]
        ];
        $params = Firewall::sanitize($request, $esperado);

        $usuario = $request->getAttribute('usuario');

        $criteria = [
            "_id" => $params['negocio_id'],
            "removido" => false,
            "colaboradores" => ['$elemMatch' => ["_id" => $usuario_id, "papel" => 'admin' ]]
        ];

        $set = [
            '$set' => [
                'removido' => true,
                'dataAlteracao' => UtilFunctions::obterDataAtualBanco(),
                'usuarioAlteracao' => $usuario['_id']
            ]
        ];

        Mongo::atualizar("negocio", $criteria, $set);
        Logger::setFluxo("NEGOCIO_REMOVIDO", $usuario->getEmail());

        return $response->withStatus(200);
    }

}