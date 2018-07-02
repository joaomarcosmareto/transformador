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

1 listar observacoes
2 load observacao
3 salvar observacao existente
4 salvar nova observacao
5 salvar observacao sem nome
6 salvar observacao sem tipo

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

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/exercicio/listarObservacao.php';


//TESTE 1
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Listar observações";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"itens":[{"id":"56b3ca0e51226205048b4728","tag":"Alça polia baixa","tipo":2,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b4729","tag":"Barra com estribo pondal","tipo":2,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b472d","tag":"Barra H","tipo":2,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b472e","tag":"Barra p\/ pulley curva","tipo":2,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b472f","tag":"Barra p\/ pulley reta ponta curva","tipo":2,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b4730","tag":"Barra reta","tipo":2,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b4731","tag":"Barra W","tipo":2,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b4732","tag":"Corda","tipo":2,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b4733","tag":"Corda unilateral","tipo":2,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b471a","tag":"Drop-set","tipo":1,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b471b","tag":"Exaustão","tipo":1,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b471c","tag":"FST 7","tipo":1,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b471d","tag":"Isometria","tipo":1,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b471e","tag":"Negativa acentuada","tipo":1,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b471f","tag":"Negativa com sobrecarga","tipo":1,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b4720","tag":"Negativa total","tipo":1,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b4721","tag":"Pirâmide","tipo":1,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b4722","tag":"Pirâmide decrescente","tipo":1,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b4723","tag":"Progressão de carga","tipo":1,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true},{"id":"56b3ca0e51226205048b4724","tag":"Progressão de repetição","tipo":1,"academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true}],"total":28}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'paginaAtual' => 1,
                        'numRegistros' => 20,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 2
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/exercicio/loadObservacao.php';

$test["nome"] = "Load observacao";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"nome":"Alça polia baixa","tipo":2,"descricao":null,"academia_id":"56b3ca0d51226205048b45a1"}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'id' => '56b3ca0e51226205048b4728',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 3
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/exercicio/salvarObservacao.php';

$test["nome"] = "Salvar observacao existente";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"id":"56b3ca0e51226205048b4728"}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'id' => '56b3ca0e51226205048b4728',
                        'nome' => 'Alça polia baixa TESTE',
                        'descricao' => 'asdasdas',
                        'tipo' => 2,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 4
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Salvar nova observacao";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"id":"*?*"}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'nome' => 'Alça polia baixa TESTE 2',
                        'descricao' => 'asdasdas',
                        'tipo' => 2,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 5
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Salvar nova observacao sem nome";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: Nome<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'descricao' => 'asdasdas',
                        'tipo' => 2,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 6
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Salvar nova observacao sem tipo";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"id":"*?*"}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'descricao' => 'asdasdas',
                        'nome' => 'Alça polia baixa TESTE 3',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);