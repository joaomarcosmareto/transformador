<?php

namespace core\util;

use MongoDB\Driver\Exception\InvalidArgumentException;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use Redis;

use core\AppConfig;
use core\util\MailManager;
use core\util\Cache;

class UtilFunctions
{
    static public function RETORNO_COM_EMAIL_ASSINCRONO($retorno, $enviar_email = false)
    {
        if(empty($retorno["dadosInvalidos"]) && empty($retorno["erro"]) && $enviar_email)
        {
            ob_end_clean();
            header("Connection: close\r\n");
            header("Content-Encoding: none\r\n");
            ignore_user_abort(true); // optional
            ob_start();

            UtilFunctions::RETORNO($retorno);

            $size = ob_get_length();
            header("Content-Length: $size");
            ob_end_flush();     // Strange behaviour, will not work
            flush();            // Unless both are called !
            ob_end_clean();

            MailManager::send();
        }
        else
        {
            UtilFunctions::RETORNO($retorno);
        }
    }

    static public function RETORNO($retorno)
    {
        if(empty($retorno["dadosInvalidos"]))
            unset ($retorno["dadosInvalidos"]);
        else
        {
            $rdi = "<ul>";
            $count_1 = count($retorno["dadosInvalidos"]);

            for($i = 0; $i < $count_1; $i++){
                $rdi = $rdi . "<li>" . $retorno["dadosInvalidos"][$i] . "</li>";
            }
            $rdi = $rdi . "</ul>";
            $retorno["dadosInvalidos"] = $rdi;
        }
        if(empty($retorno["erro"]))
            unset ($retorno["erro"]);
        else
        {
            $re = "<ul>";
            $count_1 = count($retorno["erro"]);

            for($i = 0; $i < $count_1; $i++){
                $re = $re . "<li>" . $retorno["erro"][$i] . "</li>";
            }
            $re = $re . "</ul>";
            $retorno["erro"] = $re;
        }
        if(empty($retorno["msg"]))
            unset ($retorno["msg"]);
        else
        {
            $re = "<ul>";
            $count_1 = count($retorno["msg"]);

            for($i = 0; $i < $count_1; $i++){
                $re = $re . "<li>" . $retorno["msg"][$i] . "</li>";
            }
            $re = $re . "</ul>";
            $retorno["msg"] = $re;
        }
        return $retorno;
    }

    static public function contemErro($retorno)
    {
        if(isset($retorno["request_error"]) && $retorno["request_error"] == true)
        {
            return true;
        }
        if(isset($retorno["erro"]) && !empty($retorno["erro"]))
        {
            return true;
        }
        return false;
    }

    static public function POST($param, $returnIfNotSet = null, $tipoRetorno = null, $returnIfEmpty = null)
    {
        if(isset($_POST[$param]) == false)
            return $returnIfNotSet;

        if($_POST[$param] === '')
            return $returnIfEmpty;

        $p = trim($_POST[$param]);

        if($tipoRetorno != null){
            if($tipoRetorno === "String")
                return $p;
            else if($tipoRetorno === "Int")
                return (int)$p;
            else if($tipoRetorno === "Float")
                return (float)$p;
            else if($tipoRetorno === "Double")
                return (double)$p;
            else if($tipoRetorno === "Boolean")
                return $p === "1" || $p === "true" ? true : false;
        }

        if(AppConfig::$DB_TYPE === "Mongo" && strstr($param, "_id") !== false || strstr($param, "id"))
        {
            try {
                $id = new ObjectID($p);
                return $id;
            } catch (InvalidArgumentException $exc) {
                return null;
            }
        }

        return $p;
    }

    static public function existePOST($param)
    {
        return isset($_POST[$param]);
    }

    static public function FILES($param, $returnIfNotSet = null)
    {
        if(isset($_FILES[$param]) == false)
            return $returnIfNotSet;

        return $_FILES[$param];
    }

    static public function fileImgToBase64($file)
    {
        $retorno = null;

        if( !empty($file) )
        {
            list($source_image_width, $source_image_height, $source_image_type) = getimagesize($file);

            if(isset($source_image_type) && empty($source_image_type) == false)
            {
                $imgbinary = fread(fopen($file, "r"), filesize($file));
                $retorno = 'data:image/' . $source_image_type . ';base64,' . base64_encode($imgbinary);
            }
        }
        return $retorno;
    }

    static function getMongoID($id){
        return $id === null ? new ObjectID() : new ObjectID($id);
    }

    static function converteData($data, $origemDateFormat = "d/m/Y", $resultDateFormat = "Y-m-d")
    {
        if(empty($data))
            return null;

        $data = \DateTime::createFromFormat($origemDateFormat, $data);
        if($data)
            return $data->format($resultDateFormat);

        return null;
    }

    static function converteDataFromBanco($data, $origemDateFormat = "Y-m-d", $resultDateFormat = "d/m/Y")
    {
        if(empty($data))
            return null;

        $data = \DateTime::createFromFormat($origemDateFormat, $data);
        if($data)
            return $data->format($resultDateFormat);
        return null;
    }

    static function converteDataFromBancoMongo($data, $resultDateFormat = "d/m/Y")
    {
        if(empty($data))
            return null;

        if($data){
            return $data->toDateTime()->format($resultDateFormat);
        }
        return null;
    }

    static function converteDataToMongo($data, $origemDateFormat = "d/m/Y H:i:s")
    {
        if(empty($data))
            return null;

        if(strpos($data, ":") === false){
            $data .= " 00:00:00";
        }

        $data = \DateTime::createFromFormat($origemDateFormat, $data);

        if($data)
            return new UTCDateTime((strtotime($data->format("Y-m-d H:i:s"))));

        return null;
    }

    static function converteDataFromBancoParaTimeStamp($data)
    {
        if(empty($data))
            return null;

        return strtotime($data) !== -1 ? strtotime($data) : null;
    }

    static function obterDataAtualBanco()
    {
        $data = new \DateTime();
        $data->setTimezone(new \DateTimeZone("America/Sao_Paulo"));
        return $data->format("Y-m-d H:i:s");
    }

    static function stringToNumeric($string, $precisao = 2)
    {
        if($string)
            return round((float) str_replace(",",".", $string), $precisao);
        return null;
    }

    static function randomCode($size) {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $size; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
     }

    static function contemAlgo($param)
    {
        if(gettype($param) === "string")
            return $param !== null && $param !== "null" && $param !== "NULL" && $param !== "unknown type" && trim($param) !== "";

        return $param !== null && $param != "NULL" && $param !== "unknown type";
    }

    //$dataVencimentoAtual: Y-m-d
    //$dataDiaInicial: Y-m-d
    //$quantMeses: > 1 (int)
    public static function retornaProxVencimentoMensal($dataVencimentoAtual, $dataDiaInicial, $quantMes = 1) {

        list($anoAtual, $mesAtual, $diaAtual) = explode("-", $dataVencimentoAtual);
        list($anoInicio, $mesInicio, $diaInicio) = explode("-", $dataDiaInicial);

        $proxVencimentoDia = (int)$diaInicio;
        $proxVencimentoMes = $mesAtual == 12 ? 1 : $mesAtual+1;
        $proxVencimentoAno = $mesAtual == 12 ? $anoAtual+1 : $anoAtual;

        $auxCount = 0;
        while(!checkdate($proxVencimentoMes, $proxVencimentoDia, $proxVencimentoAno) && !($auxCount >= 3)){
            $proxVencimentoDia--;
            $auxCount++;
        }

        $proxVencimento = $proxVencimentoAno."-".$proxVencimentoMes."-".$proxVencimentoDia;
        if ($quantMes === 1) {
            return $proxVencimento;
        }
        else {
            return UtilFunctions::retornaProxVencimentoMensal($proxVencimento, $dataDiaInicial, --$quantMes);
        }
    }

    public static function retornaProxVencimento($dataVencimentoAtual, $dataDiaInicial, $intervalo) {

        $dataProxPagamento = \DateTime::createFromFormat("Y-m-d", $dataVencimentoAtual);

        if($intervalo === "diaria")
            $dataProxPagamento->add(\DateInterval::createFromDateString("+1 day"));
        else if($intervalo === "semanal")
            $dataProxPagamento->add(\DateInterval::createFromDateString("+7 day"));
        else if($intervalo === "mensal")
            $dataProxPagamento = \DateTime::createFromFormat("Y-m-d", UtilFunctions::retornaProxVencimentoMensal($dataVencimentoAtual, $dataDiaInicial));
        else if($intervalo === "bimestral")
            $dataProxPagamento = \DateTime::createFromFormat("Y-m-d", UtilFunctions::retornaProxVencimentoMensal($dataVencimentoAtual, $dataDiaInicial, 2));
        else if($intervalo === "trimestral")
            $dataProxPagamento = \DateTime::createFromFormat("Y-m-d", UtilFunctions::retornaProxVencimentoMensal($dataVencimentoAtual, $dataDiaInicial, 3));
        else if($intervalo === "semestral")
            $dataProxPagamento = \DateTime::createFromFormat("Y-m-d", UtilFunctions::retornaProxVencimentoMensal($dataVencimentoAtual, $dataDiaInicial, 6));
        else if($intervalo === "anual")
            $dataProxPagamento = \DateTime::createFromFormat("Y-m-d", UtilFunctions::retornaProxVencimentoMensal($dataVencimentoAtual, $dataDiaInicial, 12));

        return $dataProxPagamento->format("Y-m-d");
    }

    public static function getCurrentTimestamp(){
        $microtime = microtime();
        $comps = explode(' ', $microtime);
        return sprintf('%d%03d', $comps[1], $comps[0] * 1000);
    }

    function ajustaArrayMongoPraJson($array) {
        foreach ( $array as $k => $value ) {
            if($value instanceof ObjectID){
                $array[$k] = 'ObjectId("'.(string)$array[$k].'")';
            }
            else if ( is_array($value) ){
                $array[$k] = UtilFunctions::ajustaArrayMongoPraJson($value);
            }
        }
        return $array;
    }

    public function parametrosPaginacaoCorretos($paginaAtual, $numRegistros){
        return ( $paginaAtual === null || (is_numeric($paginaAtual) && $paginaAtual > 0) ) &&
                ( $numRegistros === null || (is_numeric($numRegistros) && $numRegistros) );
    }

    public static function abrevianome($nomecompleto){
        $nome = explode(" ", $nomecompleto);
        $result = $nome[0]." ";
        for	($i=1; $i<count($nome); $i++) {
            $nomedomeio = $nome[$i];
            if (($nomedomeio == "de") || ($nomedomeio == "da") || ($nomedomeio == "e") || ($nomedomeio == "dos") || ($nomedomeio == "das") || ($nomedomeio == "di")){
                //$result .= $nomedomeio." ";
                continue;
            } else {
                $reducao = substr($nomedomeio, 0, 1);
                $result .= $reducao.". ";
            }
        }

        return $result;
    }
}