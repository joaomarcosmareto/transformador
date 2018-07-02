<?php

namespace core\dominio;

class Negocio extends Dominio{

    public function __construct(
        $_id = null, $usuario_id = null, $nome = null, $abreviacao = null, $denominacao = null,
        $logo = null, $imagemCapa = null,
        $cep = null, $rua = null, $numero = null, $bairro = null, $cidade = null, $estado = null,
        $telefone = null, $celular = null, $email = null,
        $links = null
    )
    {
        parent::__construct($_id);

        $this->doc = array_merge(
            $this->doc,
            [
                "nome" => $nome,
                "abreviacao" => $abreviacao,
                "denominacao" => $denominacao,
                "logo" => $logo,
                "imagemCapa" => $imagemCapa,
                "cep" => $cep,
                "rua" => $rua,
                "numero" => $numero,
                "bairro" => $bairro,
                "cidade" => $cidade,
                "estado" => $estado,
                "telefone" => $telefone,
                "celular" => $celular,
                "email" => $email,
                "links" => $links
            ]
        );
    }

    public function getNome(){
        return $this->doc["nome"] ?? null;
    }
    public function getAbreviacao(){
        return $this->doc["abreviacao"] ?? null;
    }
    public function getDenominacao(){
        return $this->doc["denominacao"] ?? null;
    }
    public function getLogo(){
        return $this->doc["logo"] ?? null;
    }
    public function getImagemCapa(){
        return $this->doc["imagemCapa"] ?? null;
    }
    public function getCep(){
        return $this->doc["cep"] ?? null;
    }
    public function getRua(){
        return $this->doc["rua"] ?? null;
    }
    public function getNumero(){
        return $this->doc["numero"] ?? null;
    }
    public function getBairro(){
        return $this->doc["bairro"] ?? null;
    }
    public function getCidade(){
        return $this->doc["cidade"] ?? null;
    }
    public function getEstado(){
        return $this->doc["estado"] ?? null;
    }
    public function getTelefone(){
        return $this->doc["telefone"] ?? null;
    }
    public function getCelular(){
        return $this->doc["celular"] ?? null;
    }
    public function getEmail(){
        return $this->doc["email"] ?? null;
    }
    public function getLinks(){
        return $this->doc["links"] ?? null;
    }

    public function setNome($Nome){
        $this->doc["nome"] = $Nome;
    }
    public function setAbreviacao($Abreviacao){
        $this->doc["abreviacao"] = $Abreviacao;
    }
    public function setDenominacao($Denominacao){
        $this->doc["denominacao"] = $Denominacao;
    }
    public function setLogo($Logo){
        $this->doc["logo"] = $Logo;
    }
    public function setImagemCapa($ImagemCapa){
        $this->doc["imagemCapa"] = $ImagemCapa;
    }
    public function setCep($Cep){
        $this->doc["cep"] = $Cep;
    }
    public function setRua($Rua){
        $this->doc["rua"] = $Rua;
    }
    public function setNumero($Numero){
        $this->doc["numero"] = $Numero;
    }
    public function setBairro($Bairro){
        $this->doc["bairro"] = $Bairro;
    }
    public function setCidade($Cidade){
        $this->doc["cidade"] = $Cidade;
    }
    public function setEstado($Estado){
        $this->doc["estado"] = $Estado;
    }
    public function setTelefone($Telefone){
        $this->doc["telefone"] = $Telefone;
    }
    public function setCelular($Celular){
        $this->doc["celular"] = $Celular;
    }
    public function setEmail($Email){
        $this->doc["email"] = $Email;
    }
    public function setLinks($Links){
        $this->doc["links"] = $Links;
    }
    public function setColaboradores($colaboradores){
        $this->doc["colaboradores"] = $colaboradores;
    }
    

}