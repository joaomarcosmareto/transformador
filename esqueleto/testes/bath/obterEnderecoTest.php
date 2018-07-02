<?php

use core\AppConfig;
use core\util\RequestPHPTest;

use core\DAO\USUARIODAO;
use core\util\ConexaoBD;
use core\util\Auth;

//PARAMETROS GERAIS E INICIALIZAÇÃO
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

// 1. Obter endereço existente - RETORNAR SUCESSO
// 2. Obter endereço inexistente - RETORNAR ERRO

$test = array();

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$con = ConexaoBD::getInstance();
$USUARIODAO = new USUARIODAO($con);

$user_test = $USUARIODAO->retornaTodos(["email"], ['rodrigo@email.com.br'], ["="], array("limit"=>1))[0];
$user_test->setValidadeToken(Auth::getNewValidadeToken());
$USUARIODAO->salvar($user_test);

$timestamp_test = '123';
$public_token = $user_test->getPublicToken();
$private_token = $user_test->getPrivateToken();

$url = AppConfig::getTestBaseURL().'/core/servicos/LoadEndereco.php';

//TESTE 1 obter endereço existente - RETORNAR SUCESSO
$test["nome"] = "Obter endereço existente";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"item":{"bairro":"Itacibá","cidade":"Cariacica","estado":"ES","logradouro":"Rodovia Governador José Henrique Sette"}}';
$test["postFields"] = array(
                        'cep' => '29150410',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 2 Obter endereço inexistente - RETORNAR ERRO
$test["nome"] = "Obter endereço inexistente";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>CEP não encontrado ou não foi possível obter dados deste endereço no momento. Preencha os outros campos do endereço manualmente.<\/li><\/ul>","sucesso":false,"item":[]}';
$test["postFields"] = array(
                        'cep' => '99999999',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);