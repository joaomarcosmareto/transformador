<?php

namespace core\dominio\functions;

use core\util\{i18n, UtilFunctions, ValidaFunctions, Retorno, Logger};
// use core\AppConfig;
use MongoDB\BSON\ObjectID;
use core\util\Mongo;

use core\dominio\Negocio;

class ConteudoFunctions {

    //Se não desejar validar um campo setar como NULL
    public static function validaInputCadastro(array $params)
    {
        $retorno = array();
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params["titulo"], i18n::write('TITULO')));

        if(!empty($retorno)){
            throw new \Exception(json_encode($retorno), 400);
        }

    }

    public static function update($params, $usuario_id) {

        $document = array();
        $document["_id"] = empty($params["id"]) ? new ObjectID() : $params["id"];
        $document["negocio_id"] = $params["negocio"];
        $document["titulo"] = $params["titulo"];
        $document["publicado"] = $params["publicado"];
        $document["categoria_id"] = $params["categoria"];
        $document["modelo"] = $params["modelo"];
        $document["paginas"] = $params["paginas"];
        $document["conteudo"] = $params["conteudo"];
        $document["usuarioAlteracao"] = $usuario_id;
        $document["dataAlteracao"] = UtilFunctions::obterDataAtualBanco();
        //TODO: colocar ou não o atributo removido

        $criteria = array("_id"=>$document["_id"]);
        $set =  array();

        if(empty($params["id"]))
        {
            $document["dataCriacao"] = UtilFunctions::obterDataAtualBanco();
            $set = $document;
        }
        else
        {
            $set = [
                    '$set' =>
                        [
                            'titulo'=>$document["titulo"],
                            'publicado'=>$document["publicado"],
                            'categoria_id'=>$document["categoria_id"],
                            'modelo'=>$document["modelo"],
                            'paginas'=>$document["paginas"],
                            'conteudo'=>$document["conteudo"],
                            'usuarioAlteracao'=>$document["usuarioAlteracao"],
                            'dataAlteracao'=>$document["dataAlteracao"],
                        ]
                    ];
        }

        Mongo::atualizar("conteudo", $criteria, $set, ['upsert' => true, "multiple" => false]);

        Logger::setFluxo("SALVAR_CATEGORIA_SUCESSO");
    }

}