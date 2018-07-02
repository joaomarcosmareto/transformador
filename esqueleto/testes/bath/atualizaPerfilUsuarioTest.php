<?php

use core\AppConfig;
use core\util\RequestPHPTest;

use core\DAO\USUARIODAO;
use core\util\ConexaoBD;
use core\util\Auth;

//PARAMETROS GERAIS E INICIALIZAÇÃO
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$url = AppConfig::getTestBaseURL()."/core/servicos/empresa/salvarPerfil.php";

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

$test["nome"] = "Caso de sucesso com alteração de senha";
$test["url"] = $url;
$test["retornoEsperado"] = '{"foto90x79":null,"foto36x36":null,"sucesso":true}';
$test["postFields"] = array(
                        'nome' => 'Teste E',
                        'sobrenome' => 'Teste E',
                        'sexo' => 'F',
                        'aniversario' => '02/02/1988',
                        'novasenha' => '123abC00',
                        'confirmsenha' => '123abC00',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 2
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Caso de sucesso sem alteração de senha";
$test["url"] = $url;
$test["retornoEsperado"] = '{"foto90x79":null,"foto36x36":null,"sucesso":true}';
$test["postFields"] = array(
                        'nome' => 'Teste E',
                        'sobrenome' => 'Teste E',
                        'sexo' => 'F',
                        'aniversario' => '02/02/1988',
                        'novasenha' => '',
                        'confirmsenha' => '',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


$test["nome"] = "Caso de sucesso com foto JPEG";
$test["url"] = $url;
$test["retornoEsperado"] = '{"foto90x79":"*ex*?*#https://(.*).jpeg#","foto36x36":"*ex*?*#https://(.*).jpeg#","sucesso":true}';
$test["postFields"] = array(
                        'nome' => 'Teste E',
                        'sobrenome' => 'Teste E',
                        'sexo' => 'F',
                        'aniversario' => '02/02/1988',
                        'novasenha' => '',
                        'confirmsenha' => '',
                        'file'=>curl_file_create(AppConfig::getDirBase().'testes/imgs/jpg.jpg', 'jpg', 'file'),
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

$test["nome"] = "Caso de sucesso com foto GIF";
$test["url"] = $url;
$test["retornoEsperado"] = '{"foto90x79":"*ex*?*#https://(.*).gif#","foto36x36":"*ex*?*#https://(.*).gif#","sucesso":true}';
$test["postFields"] = array(
                        'nome' => 'Teste E',
                        'sobrenome' => 'Teste E',
                        'sexo' => 'F',
                        'aniversario' => '02/02/1988',
                        'novasenha' => '',
                        'confirmsenha' => '',
                        'file'=>curl_file_create(AppConfig::getDirBase().'testes/imgs/gif.gif', 'gif', 'file'),
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);



$test["nome"] = "Caso de sucesso com foto PNG";
$test["url"] = $url;
$test["retornoEsperado"] = '{"foto90x79":"*ex*?*#https://(.*).png#","foto36x36":"*ex*?*#https://(.*).png#","sucesso":true}';
$test["postFields"] = array(
                        'nome' => 'Teste E',
                        'sobrenome' => 'Teste E',
                        'sexo' => 'F',
                        'aniversario' => '02/02/1988',
                        'novasenha' => '',
                        'confirmsenha' => '',
                        'file'=>curl_file_create(AppConfig::getDirBase().'testes/imgs/png.png', 'png', 'file'),
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


