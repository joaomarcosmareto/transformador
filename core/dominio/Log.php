<?php
/**
* Classe DOMINIO criada pelo gerador usando o template [PHP] Classes DOMINIO
* @author [PHP] Classes do Dominio para o mongoDB
*/
namespace core\dominio;

use MongoDB\BSON\ObjectID;

class Log extends Dominio{

	public $doc;

//----------------------------------------------------------------------------------------------
	public function __construct($_id = null, $codigo = null, $mensagem = null, $usuario = null, $ip = null, $data = null, $duracao = null, $memoria = null, $metodo = null, $param = null, $queries = null, $url = null, $fluxo = null, $retorno = null)
	{
        if($_id == null)
			$_id = new ObjectID();
	    $this->doc = array(
			"_id" => $_id,
			"codigo" => $codigo,
			"mensagem" => $mensagem,
			"usuario" => $usuario,
			"ip" => $ip,
			"data" => $data,
			"duracao" => $duracao,
			"memoria" => $memoria,
			"metodo" => $metodo,
			"param" => $param,
			"queries" => $queries,
			"url" => $url,
			"fluxo" => $fluxo,
			"retorno" => $retorno

	    );
	}
	public function getCodigo(){
		return $this->doc["codigo"] ?? null;
	}
	public function getMensagem(){
		return $this->doc["mensagem"] ?? null;
	}
	public function getUsuario(){
		return $this->doc["usuario"] ?? null;
	}
	public function getIp(){
		return $this->doc["ip"] ?? null;
	}
	public function getData(){
		return $this->doc["data"] ?? null;
	}
	public function getDuracao(){
		return $this->doc["duracao"] ?? null;
	}
	public function getMemoria(){
		return $this->doc["memoria"] ?? null;
	}
	public function getMetodo(){
		return $this->doc["metodo"] ?? null;
	}
	public function getParam(){
		return $this->doc["param"] ?? null;
	}
	public function getQueries(){
		return $this->doc["queries"] ?? null;
	}
	public function getUrl(){
		return $this->doc["url"] ?? null;
	}
	public function getFluxo(){
		return $this->doc["fluxo"] ?? null;
	}
	public function getRetorno(){
		return $this->doc["retorno"] ?? null;
	}

	public function setCodigo($Codigo){
		$this->doc["codigo"] = $Codigo;
	}
	public function setMensagem($Mensagem){
		$this->doc["mensagem"] = $Mensagem;
	}
	public function setUsuario($Usuario){
		$this->doc["usuario"] = $Usuario;
	}
	public function setIp($Ip){
		$this->doc["ip"] = $Ip;
	}
	public function setData($Data){
		$this->doc["data"] = $Data;
	}
	public function setDuracao($Duracao){
		$this->doc["duracao"] = $Duracao;
	}
	public function setMemoria($Memoria){
		$this->doc["memoria"] = $Memoria;
	}
	public function setParam($Param){
		$this->doc["param"] = $Param;
	}
	public function setMetodo($Metodo){
		$this->doc["metodo"] = $Metodo;
	}
	public function setQueries($Queries){
		$this->doc["queries"] = $Queries;
	}
	public function setUrl($Url){
		$this->doc["url"] = $Url;
	}
	public function setFluxo($Fluxo){
		$this->doc["fluxo"] = $Fluxo;
	}
	public function setRetorno($Retorno){
		$this->doc["retorno"] = $Retorno;
	}


}