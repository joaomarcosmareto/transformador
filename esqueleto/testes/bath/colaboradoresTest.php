<?php

use core\AppConfig;
use core\util\RequestPHPTest;

use core\DAO\USUARIODAO;
use core\DAO\CONVITEDAO;
use core\util\ConexaoBD;
use core\util\Auth;

//PARAMETROS GERAIS E INICIALIZAÇÃO
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test = array();

/*

- listar colaborador
- add colaborador
- add colaborador q n tem conta
- add colaborador ja existente
- add colaborador com email invalido
- salvar colaborador

*/

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$con = ConexaoBD::getInstance();
$USUARIODAO = new USUARIODAO($con);

$user_test = $USUARIODAO->retornaTodos(["email"], ['rodrigo@email.com.br'], ["="], array("limit"=>1))[0];
$user_test->setValidadeToken(Auth::getNewValidadeToken());
$USUARIODAO->salvar($user_test);

$timestamp_test = '123';
$public_token = $user_test->getPublicToken();
$private_token = $user_test->getPrivateToken();

//TESTE 0
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/colaborador/listarColaborador.php';

$test["nome"] = "listar colaboradores";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"itens":[{"id":"56b3ca0d51226205048b45ac","nomeCompleto":"Liomar Bongiovani","ativo":true,"cargo":"Professor"},{"id":"56b3ca0d51226205048b45ab","nomeCompleto":"Rodrigo Carecão Barbudo","ativo":true,"cargo":"Gerente"}],"total":2}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'ativo' => '',
                        'cargo' => '',
                        'paginaAtual' => 1,
                        'numRegistros' => 20,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 1
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/colaborador/adicionarColaborador.php';

$test["nome"] = "Add colaborador";
$test["url"] = $url;
$test["retornoEsperado"] = '{"msg":"<ul><li>O colaborador foi adicionado ao seu negócio. Selecione na lista abaixo para definir as permissões e ativar.<\/li><\/ul>","sucesso":true}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'email' => 'ciro.xm@gmail.com',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 2
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Add colaborador que não tem conta";
$test["url"] = $url;
$test["retornoEsperado"] = '{"msg":"<ul><li>O usuário foi convidado a criar uma conta no sistema. Assim que ele tiver feito isso, adicione-o novamente.<\/li><\/ul>","sucesso":true}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'email' => 'ciro.xm2@gmail.com',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 3
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Add colaborador já existente";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>O usuário informado já é um colaborador ativo.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'email' => 'rodrigo@email.com.br',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 4
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Add colaborador com email inválido";
$test["url"] = $url;
$test["retornoEsperado"] = '{"dadosInvalidos":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'email' => 'ciro.xm2',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 5
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/colaborador/salvarColaborador.php';

$test["nome"] = "Salvar colaborador";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'id' => '56b3ca0d51226205048b45ac',
                        'ativo' => 1,
                        'funcao' => 1,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 6
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Salvar colaborador faltando definir o papel.";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>É preciso definir a situação (Ativo ou Inativo) e o perfil de permissões (Atendente, Professor ou Gerente).<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'id' => '56b3ca0d51226205048b45ac',
                        'ativo' => 1,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);