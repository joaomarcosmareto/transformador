<?php

namespace core\dominio;

class Mail extends Dominio{

	public function __construct($_id = null, $to = null, $subject = null, $msg = null, $from_endereco = null, $from_nome = null, $replyto_endereco = null, $replyto_nome = null)
	{
        parent::__construct($_id);

        $this->doc = array_merge(
            $this->doc,
            [
                "to"        		=> $to,
			    "subject"   		=> $subject,
				"msg"       		=> $msg,
				"from_endereco"     => $from_endereco,
				"from_nome"         => $from_nome,
				"replyto_endereco"	=> $replyto_endereco,
				"replyto_nome"	    => $replyto_nome
            ]
	    );
	}
	public function getTo(){
		return $this->doc["to"] ?? null;
	}
	public function getSubject(){
		return $this->doc["subject"] ?? null;
	}
	public function getMsg(){
		return $this->doc["msg"] ?? null;
	}
	public function getFromEndereco(){
		return $this->doc["from_endereco"] ?? null;
	}
	public function getFromNome(){
		return $this->doc["from_nome"] ?? null;
	}
	public function getReplyToEndereco(){
		return $this->doc["replyto_endereco"] ?? null;
	}
	public function getReplyToNome(){
		return $this->doc["replyto_nome"] ?? null;
	}

	public function setTo($To){
		$this->doc["to"] = $To;
	}
	public function setSubject($Subject){
		$this->doc["subject"] = $Subject;
	}
	public function setMsg($Msg){
		$this->doc["msg"] = $Msg;
	}
	public function setFromEndereco($From_Endereco){
		$this->doc["from_endereco"] = $From_Endereco;
	}
	public function setFromNome($From_Nome){
		$this->doc["from_nome"] = $From_Nome;
	}
	public function setReplyToEndereco($ReplyTo_Endereco){
		$this->doc["replyto_endereco"] = $ReplyTo_Endereco;
	}
	public function setReplyToNome($ReplyTo_Nome){
		$this->doc["replyto_nome"] = $ReplyTo_Nome;
	}


}