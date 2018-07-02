<?php

use core\AppConfig;
use core\util\RequestPHPTest;

//PARAMETROS GERAIS E INICIALIZAÇÃO
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$url = AppConfig::getTestBaseURL()."/core/servicos/autenticacao/loginConsulta.php";

$test = array();

//TESTE 1
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$test["nome"] = "Login correto";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"negocio":{"id":"56b3ca0d51226205048b45a1","nome":"O2 Respirando Qualidade de Vida","logo":null},"auth":{ "privateToken":"'.RequestPHPTest::$NO_COMPARE.'","publicToken":"'.RequestPHPTest::$NO_COMPARE.'","refreshToken":"'.RequestPHPTest::$NO_COMPARE.'"}}';
$test["postFields"] = array(
                        'login' => 'consultaO2',
                        'senha' => '1234567a',
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);