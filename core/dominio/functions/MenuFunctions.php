<?php

namespace core\dominio\functions;

use core\util\auth\Auth;
use core\util\{i18n, UtilFunctions, ValidaFunctions};
// use core\AppConfig;
use MongoDB\BSON\ObjectID;

use core\dominio\Menu;

class MenuFunctions {

    //Se não desejar validar um campo setar como NULL
    public static function validaInputCadastroMenu(array $params)
    {
        $retorno = array();
        $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params['titulo'], i18n::write('TITULO_MENU')));
        $retorno = array_merge($retorno, ValidaFunctions::inputInArrayDefault($params['icone'], i18n::write('ICONE_MENU'), false, array('fa-users', 'fa-videocam')));
        $retorno = array_merge($retorno, ValidaFunctions::inputInArrayDefault($params['tipo'], i18n::write('TIPO_MENU'), false, array(1, 2, 3, 4, 5)));

        $tipo = $params['tipo'];

        if(($tipo === 1 || $tipo === 2) && $params['categoria'] === "")
        {
            $retorno = array_merge($retorno, ["Por favor selecione a categoria do conteúdo."]);
        }

        if($tipo === 4 && $params['conteudo'] === "")
        {
            $retorno = array_merge($retorno, ["Por favor selecione o conteúdo."]);
        }

        if($tipo === 4 && $params['link'] === "")
        {
            $retorno = array_merge($retorno, ValidaFunctions::inputDefault($params['link'], i18n::write('LINK_MENU')));
        }

        if($tipo === 5 && $params['biblia'] === "")
        {
            $retorno = array_merge($retorno, ValidaFunctions::inputInArrayDefault($params['biblia'], i18n::write('BIBLIA_MENU'), false, array(1, 2, 3, 4, 5)));
        }

        if(!empty($retorno)){
            throw new \Exception(json_encode($retorno), 400);
        }
    }

    public static function validaRegrasNegocioMenu($params, $usuario_id)
    {

    }

}