<?php

use core\AppConfig;
use core\util\RequestPHPTest;

use core\DAO\USUARIODAO;
use core\util\ConexaoBD;
use core\util\Auth;

//PARAMETROS GERAIS E INICIALIZAÇÃO
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test = array();

/*

1 salvar comunicado
2 load comunicado
3 listar comunicado
4 salvar comunicado com data errada

*/

//echo 'Estes testes requerem banco populado no popula banco e que o usuário 1@1.com já tenha logado</br>';

//TESTE 1     
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$con = ConexaoBD::getInstance();
$USUARIODAO = new USUARIODAO($con);

$user_test = $USUARIODAO->retornaTodos(["email"], ['rodrigo@email.com.br'], ["="], array("limit"=>1))[0];
$user_test->setValidadeToken(Auth::getNewValidadeToken());
$USUARIODAO->salvar($user_test);

$timestamp_test = '123';
$public_token = $user_test->getPublicToken();
$private_token = $user_test->getPrivateToken();

$dataInicio = date("d/m/Y");
$dataFim = "01/01/2100";
$dataInicio2 = date("Y/m/d");
$dataFim2 = "2100/01/01";

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/comunicado/salvarComunicado.php';

$test["nome"] = "Caso de sucesso salvar comunicado";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'dataInicio' => $dataInicio,
                        'dataFim' => $dataFim,
                        'titulo' => "Teste",
                        'descricao' => 'Lá lá lá
asdasdasd

asdasd',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 2
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/comunicado/loadComunicado.php';

$test["nome"] = "Caso de sucesso load comunicado";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"titulo":"Teste","descricao":"Lá lá lá\r\nasdasdasd\r\n\r\nasdasd","dataInicio":"2016\/02\/21","dataFim":"'.  addslashes($dataFim2).'"}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'comunicado_id' => '56c9a93451226203246681a8',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 3
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/comunicado/listarComunicado.php';

$test["nome"] = "Caso de sucesso listar comunicado";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"itens":[{"id":"56c9a93451226203246681a8","titulo":"Teste","descricao":"Lá lá lá\r\nasdasdasd\r\n\r\nasdasd","dataInicio":"21\/02\/2016","dataFim":"'.  addslashes($dataFim).'"},{"id":"*?*","titulo":"Teste","descricao":"Lá lá lá\r\nasdasdasd\r\n\r\nasdasd","dataInicio":"'.  addslashes($dataInicio).'","dataFim":"'.  addslashes($dataFim).'"}],"total":2}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'dataInicio' => '',
                        'dataFim' => '',
                        'titulo' => '',
                        'numRegistros' => 20,
                        'paginaAtual' => 1,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 3
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/comunicado/salvarComunicado.php';

$test["nome"] = "Caso de falha salvar comunicado data errada";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Data inválida: Data início<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'dataInicio' => '0aa1/01/2016',
                        'dataFim' => $dataFim,
                        'titulo' => "Teste",
                        'descricao' => 'Lá lá lá
asdasdasd

asdasd',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);
