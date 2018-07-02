<?php

namespace core\dominio;

use MongoDB\BSON\ObjectID;

class Dominio {

	public $collection;
	public $doc;

//----------------------------------------------------------------------------------------------
	public function __construct($_id = null)
	{
		if($_id == null)
			$_id = new ObjectID();
	    $this->doc = [
			"_id" => $_id,
			"dataCriacao" => null,
			"dataAlteracao" => null,
			"usuarioAlteracao" => null,
			"removido" => null
        ];
	}

	public function getCollectionName(){
		return $this->$collection;
	}
	public function getId(){
		return $this->doc["_id"];
	}
	public function getDataCriacao(){
		return $this->doc["dataCriacao"] ?? null;
	}
	public function getDataAlteracao(){
		return $this->doc["dataAlteracao"] ?? null;
	}
	public function getUsuarioAlteracao(){
		return $this->doc["usuarioAlteracao"] ?? null;
	}
	public function getRemovido(){
		return $this->doc["removido"] ?? null;
	}

	public function setCollectionName($col_name){
		$this->$collection = $col_name;
	}

	public function setId($id){
		$this->doc["_id"] = $id;
	}
	public function setDataCriacao($DataCriacao){
		$this->doc["dataCriacao"] = $DataCriacao;
	}
	public function setDataAlteracao($DataAlteracao){
		$this->doc["dataAlteracao"] = $DataAlteracao;
	}
	public function setUsuarioAlteracao($UsuarioAlteracao){
		$this->doc["usuarioAlteracao"] = $UsuarioAlteracao;
	}
	public function setRemovido($Removido){
		$this->doc["removido"] = $Removido;
	}


}