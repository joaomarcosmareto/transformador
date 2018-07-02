<?php
include_once '../core/dominio/ACADEMIA.php';
include_once '../core/dominio/ACADEMIADAO.php';
include_once '../core/dominio/ALUNO.php';
include_once '../core/dominio/ALUNODAO.php';
include_once '../core/dominio/USUARIO.php';
include_once '../core/dominio/USUARIODAO.php';
include_once '../core/dominio/AVALIACAO.php';
include_once '../core/dominio/AVALIACAODAO.php';
include_once '../core/dominio/FICHA.php';
include_once '../core/dominio/FICHADAO.php';
include_once '../core/dominio/PROFESSOR.php';
include_once '../core/dominio/PROFESSORDAO.php';
include_once '../core/dominio/EXERCICIO.php';
include_once '../core/dominio/EXERCICIODAO.php';
include_once '../core/dominio/EXERCICIO_ACADEMIA.php';
include_once '../core/dominio/EXERCICIO_ACADEMIADAO.php';
include_once '../core/dominio/EXERCICIO_FICHA.php';
include_once '../core/dominio/EXERCICIO_FICHADAO.php';
include_once '../core/Bcrypt.php';
include_once '../core/dominio/ConexaoBD.php';

//--------------------------------------------------
//$id = '1542d385fec495';
$id = null;
$nome = "nome";
$sobrenome = "sobrenome";
$email = "email";
$senha = "senha";
$sexo = 'F';
$dataNascimento = '2014-10-01';
$foto = null;
$USUARIO = new USUARIO($id, $nome, $sobrenome, $email, Bcrypt::hash($senha), $sexo, $dataNascimento, $foto);

$con = ConexaoBD::getInstance("localhost", "root", '', "workout");

$USUARIODAO = new USUARIODAO($con);
$USUARIODAO->salvar($USUARIO);

$USUARIO2 = $USUARIODAO->retornaPorId("15430431ade916");
if($USUARIO2)
	$USUARIO2->imprimir();

//$USUARIO2 = $USUARIODAO->deletar($USUARIODAO->retornaPorId("1542d51aa0e731"));

echo count($USUARIODAO->retornaTodos(["nome"],["nome"],["="], " order by id"));


?>
