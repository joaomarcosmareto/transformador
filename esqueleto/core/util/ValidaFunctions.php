<?php

namespace core\util;

class ValidaFunctions
{
    static public function inputDefault($param, $campo = null, $obrigatorio = true, $maxSize = 255, $minSize = 0)
    {
        if(empty($campo))
            throw new \Exception("Faltou passar o campo a ser validado: ".$campo);

        $retorno = array();

        if(empty($param) && $obrigatorio)
        {
            $retorno[] = i18n::write("CAMPO_NAO_PODE_SER_VAZIO").$campo;
        }
        else if(empty($param) && !$obrigatorio)
        {
            return $retorno;
        }
        else if($param !== null && strlen(trim($param)) > $maxSize)
        {
            $retorno[] = i18n::write("NUMERO_MAX_CARACTER_EXCEDIDO").$campo;
        }
        else if($param !== null && strlen(trim($param)) <= $minSize)
        {
            $retorno[] = i18n::write("NUMERO_MINIMO_CARACTERES_NAO_ATENDIDO").$campo;
        }

        return $retorno;
    }

    static public function inputEmailDefault($param, $campo = null, $obrigatorio = true)
    {
        if(empty($campo))
            throw new \Exception("Faltou passar o campo a ser validado.");

        $retorno = array();

        if(empty($param) && $obrigatorio)
        {
            $retorno[] = i18n::write("CAMPO_NAO_PODE_SER_VAZIO").$campo;
        }
        else if($param !== null && filter_var($param, FILTER_VALIDATE_EMAIL) == false)
        {
            $retorno[] = i18n::write("EMAIL_INVALIDO");
        }

        /*
        //checa se o dominio é válido
        if (!checkdnsrr($domain, 'MX')) {
         domain is not valid
        }
        */

        return $retorno;
    }

    static public function inputInArrayDefault($param, $campo = null, $obrigatorio = true, $array = "SEXO")
    {
        if(empty($campo))
            throw new \Exception("Faltou passar o campo a ser validado.");

        $retorno = array();

        if($param !== "0" && $param !== 0 && empty($param) && $obrigatorio)
        {
            $retorno[] = i18n::write("CAMPO_NAO_PODE_SER_VAZIO").$campo;
        }
        else if($param !== null)
        {
            if($array === "SEXO")
            {
                if( !in_array($param, array("M", "F")) )
                    $retorno[] = i18n::write("CAMPO_INVALIDO").$campo;
            }
            else if($array === "ESTADO")
            {
                $estados = array( "AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES",
                                    "GO", "MA", "MT", "MS", "MG", "PA", "PB", "PR",
                                    "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC",
                                    "SE", "SP", "TO");
                if(!$obrigatorio)
                    $estados[] = "";

                if( !in_array($param, $estados) )
                    $retorno[] = i18n::write("CAMPO_INVALIDO").$campo;
            }
            else if(is_array($array))
            {
                if( !in_array($param, $array) )
                    $retorno[] = i18n::write("CAMPO_INVALIDO").$campo;
            }
        }

        return $retorno;
    }

    static public function intInputDefault($param, $campo = null, $obrigatorio = true, $apenasPositivo = false)
    {
        if(empty($campo))
            throw new \Exception("Faltou passar o campo a ser validado.");

        $retorno = array();

        if($param === null && $obrigatorio)
        {
            $retorno[] = i18n::write("CAMPO_NAO_PODE_SER_VAZIO").$campo;
        }
        else if($param !== null)
        {
            if(filter_var($param, FILTER_VALIDATE_INT) === false)
                $retorno[] = i18n::write ("CAMPO_INVALIDO").$campo;
            if(count($retorno) == 0 && $apenasPositivo)
            {
                if($param < 0)
                    $retorno[] = i18n::write("CAMPO_NAO_PODE_TER_VALOR_NEGATIVO").$campo;
            }
        }

        return $retorno;
    }

    static public function numericInputDefault($param, $campo = null, $obrigatorio = true, $apenasPositivo = false)
    {
        if(empty($campo))
            throw new \Exception("Faltou passar o campo a ser validado.");

        $retorno = array();

        if($param === null && $obrigatorio)
        {
            $retorno[] = i18n::write("CAMPO_NAO_PODE_SER_VAZIO").$campo;
        }
        else if($param !== null)
        {
            if(filter_var($param, FILTER_VALIDATE_INT) === false)
            {
                // se não for inteiro, tenta validar como float
                $param = str_replace(",",".", $param);
                if(filter_var($param, FILTER_VALIDATE_FLOAT) === false)
                    $retorno[] = i18n::write ("CAMPO_INVALIDO").$campo;
            }
            if(count($retorno) == 0 && $apenasPositivo)
            {
                if($param < 0)
                    $retorno[] = i18n::write("CAMPO_NAO_PODE_TER_VALOR_NEGATIVO").$campo;
            }
        }

        return $retorno;
    }

    static public function booleanInputDefault($param, $campo = null, $obrigatorio = true)
    {
        if(empty($campo))
            throw new \Exception("Faltou passar o campo a ser validado.");

        $retorno = array();

        if($param === null && $obrigatorio)
        {
            $retorno[] = i18n::write("CAMPO_NAO_PODE_SER_VAZIO").$campo;
        }
        else if($param !== null)
        {
            if( ($param || !$param) == false)
                $retorno[] = i18n::write ("CAMPO_INVALIDO").$campo;
        }

        return $retorno;
    }

    static public function inputPasswordDefault($param, $campo = null, $obrigatorio = true, $confirmaSenha = null)
    {
        if(empty($campo))
            throw new \Exception("Faltou passar o campo a ser validado.");

        $retorno = array();

        if(empty($param) && $obrigatorio)
        {
            $retorno[] = i18n::write("CAMPO_NAO_PODE_SER_VAZIO").$campo;
        }
        else if($param !== null)
        {
            if( strlen($param) < 8 )
                $retorno[] = i18n::write("SENHA_CURTA");
            else if( strlen($param) > 26 )
                $retorno[] = i18n::write("SENHA_LONGA");

            if( !preg_match("/[0-9]/", $param) )
                $retorno[] = i18n::write("SENHA_SEM_NUMEROS");

            if( !preg_match("/[a-z]/i", $param) )
                $retorno[] = i18n::write("SENHA_SEM_LETRAS");

            if($confirmaSenha !== null && $param !== $confirmaSenha)
                $retorno[] = i18n::write("SENHAS_NAO_CONFEREM");
        }
        return $retorno;
    }

    //TODO: fazer outras valiações como tamanho do arquivo e tipo
//    static public function imgInputDefault($param, $campo = null, $podeSerNULL = false, $largura = 320, $altura = 240)
//    {
//        if(empty($campo))
//            $campo = i18n::write("DADOS_INVALIDOS");
//
//        $aux = array();
//
//        if($param != null)
//        {
//            if( (isset($param["tmp_name"]) && ThumbnailGenerator::convertImageToThumbnailBase64($param["tmp_name"], $largura, $altura) != false) == false)
//                $aux[] = $campo;
//        }
//        else if($podeSerNULL)
//            return;
//
//        $retorno = array_merge($aux, $retorno);
//    }

    static public function imgInputDefault($param, $campo = null, $obrigatorio = true, $type = null, $maxBytes = 5242880)
    {
        if(empty($campo))
            throw new \Exception("Faltou passar o campo a ser validado.");

        if($type === null)
            $type = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);

        $retorno = array();

        if($param === null && $obrigatorio)
        {
            $retorno[] = i18n::write("CAMPO_NAO_PODE_SER_VAZIO").$campo;
        }
        else if($param !== null)
        {
            if( isset($param["tmp_name"]) && !empty($param["tmp_name"]))
            {
                list($source_image_width, $source_image_height, $source_image_type) = getimagesize($param["tmp_name"]);

                $bytes = @filesize($param["tmp_name"]);

                if( !in_array($source_image_type, $type) )
                {
                    $retorno[] = i18n::write("FORMATO_IMAGEM_INVALIDO").$campo;
                }
                else if($bytes !== false && $bytes > $maxBytes)
                {
                    $retorno[] = i18n::write("ARQUIVO_IMAGEM_MAIOR_PERMITIDO").$campo;
                }
            }
            else
                $retorno[] = i18n::write("IMAGEM_NAO_ENVIADA").$campo;
        }

        return $retorno;
    }

    static public function inputData($param, $campo = null, $obrigatorio = true, $maiorQueAtual = true, $format = 'd-m-Y', $menorQueAtual = false)
    {
        if(empty($campo))
            throw new \Exception("Faltou passar o campo a ser validado.");

        $retorno = array();

        if(empty($param) && $obrigatorio)
        {
            $retorno[] = i18n::write("CAMPO_NAO_PODE_SER_VAZIO").$campo;
        }
        else if($param != null)
        {
            try{
                $d = \DateTime::createFromFormat($format, $param);

                if ( $d == false || ($d && $d->format($format) != $param))
                {
                    $retorno[] = i18n::write("DATA_INVALIDA").$campo;
                }
                else if($maiorQueAtual)
                {
                    if($d->format('Y-m-d') < date('Y-m-d')){
                        $retorno[] = i18n::write("DATA_INICIO_DEVE_SER_MAIOR_QUE_HOJE");
                    }
                }
                if(empty($retorno) && $menorQueAtual){
                    if($d->format('Y-m-d') > date('Y-m-d')){
                        $retorno[] = i18n::write("DATA_ANTERIOR_HOJE");
                    }
                }
            }
            catch(Exception $e){
                $retorno[] = i18n::write("ERRO_NA_DATA");
            }
        }

        return $retorno;
    }

    static public function inputDataInicioFim($data1, $data2, $campo1 = null, $campo2 = null, $obrigatorio = true, $format = 'd-m-Y', $dataInicioMaiorQueHoje = true)
    {
        if(empty($campo1) || empty($campo2) )
            throw new \Exception("Faltou passar o campo a ser validado.");

        $retorno = array();

        if($obrigatorio && (empty($data1) || empty($data2)) )
        {
            $retorno[] = i18n::write("CAMPO_NAO_PODE_SER_VAZIO").$campo;
        }
        else if($data1 !== null && $data2 !== null)
        {
            try{
                $d1 = \DateTime::createFromFormat($format, $data1);
                $d2 = \DateTime::createFromFormat($format, $data2);

                if ( $d1 == false || $d2 == false
                     || ($d1 && $d1->format($format) != $data1)
                     || ($d2 && $d2->format($format) != $data2)
                    )
                {
                    if ( $d1 == false || ($d1 && $d1->format($format) != $data1) )
                    {
                       $retorno[] = i18n::write("DATA_INVALIDA").$campo1;
                    }
                    if ( $d2 == false || ($d2 && $d2->format($format) != $data2) )
                    {
                       $retorno[] = i18n::write("DATA_INVALIDA").$campo2;
                    }
                }
                else if($d1->format('Y-m-d') > $d2->format('Y-m-d')){
                    $retorno[] = i18n::write("DATA_FIM_DEVE_SER_MAIOR_QUE_DATA_INICIO");
                }

                if(empty($retorno) && $dataInicioMaiorQueHoje && $d1->format('Y-m-d') < date("Y-m-d")){
                    $retorno[] = i18n::write("DATA_INICIO_DEVE_SER_MAIOR_QUE_HOJE");
                }
            }
            catch(Exception $e){
                $retorno[] = i18n::write("ERRO_NA_DATA");
            }
        }

        return $retorno;
    }

}
