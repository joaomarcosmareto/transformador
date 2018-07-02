<?php

use core\AppConfig;
use core\util\RequestPHPTest;

use core\DAO\USUARIODAO;
use core\util\ConexaoBD;
use core\util\Auth;

//PARAMETROS GERAIS E INICIALIZAÇÃO
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$url = AppConfig::getTestBaseURL()."/core/servicos/aluno/setTreinoFeito.php";

$test = array();


/*

1 Caso de sucesso com alteração de senha
2 Caso de sucesso sem alteração de senha

*/

//echo 'Estes testes requerem banco populado no popula banco e que o usuário 1@1.com já tenha logado</br>';

//TESTE 1
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$con = ConexaoBD::getInstance();
$USUARIODAO = new USUARIODAO($con);

$user_test = $USUARIODAO->retornaTodos(["email"], ['5@5.com'], ["="], array("limit"=>1))[0];
$user_test->setValidadeToken(Auth::getNewValidadeToken());
$USUARIODAO->salvar($user_test);

$timestamp_test = '123';
$public_token = $user_test->getPublicToken();
$private_token = $user_test->getPrivateToken();

$test["nome"] = "Caso de sucesso";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'treinos' => '[{"data":"2016-02-20","h":"09:14:32","t":0}]',
                        'ficha_id' => '56b3cae5512262d5288b473d',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


$test["nome"] = "Data mal formada";
$test["url"] = $url;
$test["retornoEsperado"] = '';
$test["postFields"] = array(
                        'treinos' => '[{"data":"6-02-20 09:14:32","t":0}]',
                        'ficha_id' => '56b3cae5512262d5288b473d',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

$test["nome"] = "Faltando parametro";
$test["url"] = $url;
$test["retornoEsperado"] = '';
$test["postFields"] = array(
                        'treinos' => '[{"data":"2016-02-20 09:14:32","t":0}]',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

$test["nome"] = "Faltando parametro";
$test["url"] = $url;
$test["retornoEsperado"] = '';
$test["postFields"] = array(
                        'treinos' => '[{"data":"2016-02-20 09:14:32","t":0}]',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);
