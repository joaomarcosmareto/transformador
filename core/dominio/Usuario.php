<?php

namespace core\dominio;

class Usuario extends Dominio{

    public function __construct(
        $_id = null, $nome = null, $sobrenome = null, $email = null, $senha = null, $sexo = null, $dataNascimento = null,
        $foto = null, $telefone = null, $celular = null,
        $rua = null, $numero = null, $complemento = null, $cep = null, $bairro = null, $cidade = null, $estado = null,
        $pwdToken = null, $validadePwdToken = null, $accToken = null, $validadeAccToken = null)
    {
        parent::__construct($_id);

        $this->doc = array_merge(
            $this->doc,
            [
                "nome" => $nome,
                "sobrenome" => $sobrenome,
                "email" => $email,
                "senha" => $senha,
                "sexo" => $sexo,
                "dataNascimento" => $dataNascimento,
                "foto" => $foto,
                "telefone" => $telefone,
                "celular" => $celular,
                "rua" => $rua,
                "numero" => $numero,
                "complemento" => $complemento,
                "cep" => $cep,
                "bairro" => $bairro,
                "cidade" => $cidade,
                "estado" => $estado,
                "pwdToken" => $pwdToken,
                "validadePwdToken" => $validadePwdToken,
                "accToken" => $accToken,
                "validadeAccToken" => $validadeAccToken,
            ]
        );
    }

    public function getNome(){
        return $this->doc["nome"] ?? null;
    }
    public function getSobrenome(){
        return $this->doc["sobrenome"] ?? null;
    }
    public function getEmail(){
        return $this->doc["email"] ?? null;
    }
    public function getSenha(){
        return $this->doc["senha"] ?? null;
    }
    public function getSexo(){
        return $this->doc["sexo"] ?? null;
    }
    public function getDataNascimento(){
        return $this->doc["dataNascimento"] ?? null;
    }
    public function getFoto(){
        return $this->doc["foto"] ?? null;
    }
    public function getTelefone(){
        return $this->doc["telefone"] ?? null;
    }
    public function getCelular(){
        return $this->doc["celular"] ?? null;
    }
    public function getRua(){
        return $this->doc["rua"] ?? null;
    }
    public function getNumero(){
        return $this->doc["numero"] ?? null;
    }
    public function getComplemento(){
        return $this->doc["complemento"] ?? null;
    }
    public function getCep(){
        return $this->doc["cep"] ?? null;
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
    public function getPwdToken(){
        return $this->doc["pwdToken"] ?? null;
    }
    public function getValidadePwdToken(){
        return $this->doc["validadePwdToken"] ?? null;
    }
    public function getAccToken(){
        return $this->doc["accToken"] ?? null;
    }
    public function getValidadeAccToken(){
        return $this->doc["validadeAccToken"] ?? null;
    }

    public function setNome($Nome){
        $this->doc["nome"] = $Nome;
    }
    public function setSobrenome($Sobrenome){
        $this->doc["sobrenome"] = $Sobrenome;
    }
    public function setEmail($Email){
        $this->doc["email"] = $Email;
    }
    public function setSenha($Senha){
        $this->doc["senha"] = $Senha;
    }
    public function setSexo($Sexo){
        $this->doc["sexo"] = $Sexo;
    }
    public function setDataNascimento($DataNascimento){
        $this->doc["dataNascimento"] = $DataNascimento;
    }
    public function setFoto($Foto){
        $this->doc["foto"] = $Foto;
    }
    public function setTelefone($Telefone){
        $this->doc["telefone"] = $Telefone;
    }
    public function setCelular($Celular){
        $this->doc["celular"] = $Celular;
    }
    public function setRua($Rua){
        $this->doc["rua"] = $Rua;
    }
    public function setNumero($Numero){
        $this->doc["numero"] = $Numero;
    }
    public function setComplemento($Complemento){
        $this->doc["complemento"] = $Complemento;
    }
    public function setCep($Cep){
        $this->doc["cep"] = $Cep;
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
    public function setPwdToken($PwdToken){
        $this->doc["pwdToken"] = $PwdToken;
    }
    public function setValidadePwdToken($ValidadePwdToken){
        $this->doc["validadePwdToken"] = $ValidadePwdToken;
    }
    public function setAccToken($AccToken){
        $this->doc["accToken"] = $AccToken;
    }
    public function setValidadeAccToken($ValidadeAccToken){
        $this->doc["validadeAccToken"] = $ValidadeAccToken;
    }

}