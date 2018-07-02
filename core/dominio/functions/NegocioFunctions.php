<?php

namespace core\dominio\functions;

use core\util\{i18n, UtilFunctions, ValidaFunctions, Retorno, Logger};
// use core\AppConfig;
use MongoDB\BSON\ObjectID;
use core\util\Mongo;

use core\dominio\Negocio;

class NegocioFunctions {

    //Se não desejar validar um campo setar como NULL
    public static function validaInput(array $params)
    {
        $retorno = array();
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["nome"], i18n::write('NOME')));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["abreviacao"], i18n::write('ABREVIACAO'), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputInArrayDefault($params["denominacao"], i18n::write("DENOMINACAO"), false, array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13)));

        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["cep"], i18n::write("CEP"), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["rua"], i18n::write("RUA"), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["numero"], i18n::write("NUMERO"), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["bairro"], i18n::write("BAIRRO"), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["cidade"], i18n::write("CIDADE"), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["estado"], i18n::write("ESTADO"), false, 2));

        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["telefone"], i18n::write("TELEFONE"), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["celular"], i18n::write("CELULAR"), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputEmailDefault($params["email"], i18n::write('EMAIL'), false));
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["links"], i18n::write("NEGOCIO_ATIVIDADE"), false));

        if(!empty($retorno)){
            throw new \Exception(json_encode($retorno), 400);
        }
    }

    public static function validaRegras(array $params)
    {

    }

    public static function salvar(array $params, ObjectID $usuario_id)
    {
        $criteria = ["_id" => empty($params["id"]) ? new ObjectID() : $params["id"]];

        if(empty($params["id"]))
        {
            $set = [
                "_id" => $criteria["_id"],
                'nome' => $params["nome"],
                'abreviacao' => $params["abreviacao"],
                'denominacao' => $params["denominacao"],

                'cep' => $params["cep"],
                'rua' => $params["rua"],
                'numero' => $params["numero"],
                'bairro' => $params["bairro"],
                'cidade' => $params["cidade"],
                'estado' => $params["estado"],

                'telefone' => $params["telefone"],
                'celular' => $params["celular"],
                'email' => $params["email"],
                'links' => $params["links"],

                'colaboradores' => [["_id" => $usuario_id, "papel" => "admin"]],

                'dataAlteracao' => UtilFunctions::obterDataAtualBanco(),
                'usuarioAlteracao' => $usuario_id,
                //'removido' => false,
                "dataCriacao" => UtilFunctions::obterDataAtualBanco()
            ];
        }
        else
        {
            $criteria["colaboradores"] = ['$elemMatch' => ["_id" => $usuario_id, "papel" => 'admin']];
            $set = [
                '$set' => [
                    'nome' => $params['nome'],
                    'abreviacao' => $params['abreviacao'],
                    'denominacao' => $params['denominacao'],

                    'cep' => $params['cep'],
                    'rua' => $params['rua'],
                    'numero' => $params['numero'],
                    'bairro' => $params['bairro'],
                    'cidade' => $params['cidade'],
                    'estado' => $params['estado'],

                    'telefone' => $params['telefone'],
                    'celular' => $params['celular'],
                    'email' => $params['email'],
                    'links' => $params['links'],

                    'dataAlteracao' => UtilFunctions::obterDataAtualBanco(),
                    'usuarioAlteracao' => $usuario_id,
                    //'removido' => $params['removido']
                ]
            ];
        }

        Mongo::atualizar("negocio", $criteria, $set, ['upsert' => true, "multiple" => false]);

        Logger::setFluxo("SALVAR_NEGOCIO_SUCESSO");
    }

    public static function validaInputCadastroCategoria($params) {

        $retorno = array();
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault("nome", i18n::write('NOME')));

        if(!empty($retorno)){
            throw new \Exception(json_encode($retorno), 400);
        }

    }

    public static function updateNegocioCategoria($params, $usuario_id) {

        $categoria = array();
        $categoria["_id"] = empty($params["id"]) ? new ObjectID() : $params["id"];
        $categoria["nome"] = $params["nome"];
        $categoria["ativo"] = $params["ativo"];
        $categoria["usuarioAlteracao"] = $usuario_id;
        $categoria["dataAlteracao"] = UtilFunctions::obterDataAtualBanco();

        $criteria = array();
        $set =  array();

        if(empty($params["id"]))
        {
            $categoria["dataCriacao"] = UtilFunctions::obterDataAtualBanco();

            Retorno::$data["id"] = (string)$categoria["_id"];

            $criteria = [
                            "_id"=>$params["negocio_id"],
                            "colaboradores"=>['$elemMatch' => ["_id"=>$usuario_id, "papel"=>'admin' ]],
                        ];
            $set = array('$push' => array("categorias" => $categoria));
        }
        else
        {
            $criteria = [
                            "_id"=>$params["negocio_id"],
                            "categorias._id" => $params["id"],
                            "colaboradores"=>['$elemMatch' => ["_id"=>$usuario_id, "papel"=>'admin' ]],
                        ];
            $set = array('$set' => array("categorias.$" => $categoria));
        }

        Mongo::atualizar("negocio", $criteria, $set);

        Logger::setFluxo("SALVAR_CATEGORIA_SUCESSO");
    }

    public static function validaRegrasNegocioMenu($params, $usuario_id) {

    }

    public static function updateNegocioMenu($params, $usuario_id) {
        $document = array();
        $document["_id"] = empty($params["id"]) ? new ObjectID() : $params["id"];
        $document["titulo"] = $params["titulo"];
        $document["icone"] = $params["icone"];
        $document["tipo"] = $params["tipo"];
        $document["biblia"] = $params["biblia"];
        $document["categoria_id"] = $params["categoria_id"];
        $document["link"] = $params["link"];
        $document["conteudo_id"] = $params["conteudo_id"];
        $document["usuarioAlteracao"] = $usuario_id;
        $document["dataAlteracao"] = UtilFunctions::obterDataAtualBanco();

        $criteria = array();
        $set =  array();

        if(empty($params["id"]))
        {
            $document["dataCriacao"] = UtilFunctions::obterDataAtualBanco();
            $criteria = [
                "_id" => $params["negocio_id"],
                "colaboradores" => ['$elemMatch' => ["_id"=>$usuario_id, "papel" => 'admin']],
            ];
            $set = array('$push' => array("menus" => $document));
        }
        else
        {
            $criteria = [
                "_id" => $params["negocio_id"],
                "menus._id" => $params["id"],
                "colaboradores" => ['$elemMatch' => ["_id"=>$usuario_id, "papel" => 'admin']],
            ];
            $set = array('$set' => array("menus.$" => $document));
        }

        Mongo::atualizar("negocio", $criteria, $set);

        Logger::setFluxo("SALVAR_CATEGORIA_SUCESSO");
    }

}