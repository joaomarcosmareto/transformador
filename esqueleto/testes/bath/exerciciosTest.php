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

1 listar exercícios
2 load exercício
3 salvar exercício existente
4 salvar novo exercicio
5 salvar exercício com imagem
6 salvar exercício sem nome
7 salvar exercício removendo imagem

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

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/exercicio/listarExercicio.php';


//TESTE 1
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Listar exercícios";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"itens":[{"id":"56b3ca0d51226205048b46da","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Abdominal com Flexão de Quadril","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46db","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Abdominal em Banco Inclinado","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46dc","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Abdominal em Polia Alta","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46e0","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Abdominal em Roda","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46e1","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Abdominal em Roda em Pé","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46ef","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Abdução em Máquina","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46f0","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Abdução em Polia Baixa","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46f1","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Abdução no Chão","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46ec","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Adução em Máquina","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46ed","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Adução em Polia Baixa","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46f6","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Afundo","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46f7","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Afundo com Barra","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46f2","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Agachamento com Barra","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46f3","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Agachamento Frontal","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46f4","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Agachamento Hack em Máquina","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b469a","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Bicicleta","agrupamentos":[],"agrupamentos_nome":[],"aerobico":true,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46fa","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Bom Dia","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46c1","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Bom Dia","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b46fb","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Bom Dia a Pernas Retas","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0},{"id":"56b3ca0d51226205048b4695","academia_id":"56b3ca0d51226205048b45a1","invisivel":false,"podeInvisivel":true,"nome":"Crucifixo com Halteres","agrupamentos":[],"agrupamentos_nome":[],"aerobico":false,"aparelho":null,"dificuldade":0}],"total":112}';
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

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/exercicio/loadExercicio.php';

$test["nome"] = "Load exercicio";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"nome":"Abdominal com Flexão de Quadril","descricao":"Neste exercício, deverá mover o tronco e as pernas ao mesmo tempo, fazendo com que se aproximem um do outro e posteriormente se afastem. É um exercício exigente e que trabalha bastante bem o reto do abdómem e oblíquos externos.","aparelho":null,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/75.jpg","aerobico":false,"dificuldade":0,"academia_id":"56b3ca0d51226205048b45a1","agrupamentos":[""]}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'id' => '56b3ca0d51226205048b46da',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 3
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/exercicio/salvarExercicio.php';

$test["nome"] = "Salvar exercicio existente";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"id":"*?*"}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'id' => '56b3ca0d51226205048b46da',
                        'nome' => 'Abdominal com Flexão de Quadril',
                        'descricao' => 'Neste exercício, deverá mover o tronco e as pernas ao mesmo tempo, fazendo com que se aproximem um do outro e posteriormente se afastem. É um exercício exigente e que trabalha bastante bem o reto do abdómem e oblíquos externos.',
                        'agrupamento' => 9,
                        'aerobico' => 0,
                        'dificuldade' => 0,
                        'removerGif' => 0,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 4
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Salvar exercicio novo sem imagem";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"id":"*?*"}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'nome' => 'Abdominal com Flexão de Quadril TESTE 1',
                        'descricao' => 'Neste exercício, deverá mover o tronco e as pernas ao mesmo tempo, fazendo com que se aproximem um do outro e posteriormente se afastem. É um exercício exigente e que trabalha bastante bem o reto do abdómem e oblíquos externos.',
                        'agrupamento' => 9,
                        'aerobico' => 0,
                        'dificuldade' => 1,
                        'aparelho' => 1,
                        'removerGif' => 0,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 5
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Salvar exercicio novo com gif";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"id":"*?*"}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'nome' => 'Abdominal com Flexão de Quadril TESTE 2',
                        'descricao' => 'Neste exercício, deverá mover o tronco e as pernas ao mesmo tempo, fazendo com que se aproximem um do outro e posteriormente se afastem. É um exercício exigente e que trabalha bastante bem o reto do abdómem e oblíquos externos.',
                        'agrupamento' => 9,
                        'aerobico' => 0,
                        'dificuldade' => 1,
                        'aparelho' => 2,
                        'removerGif' => 0,
                        'file'=>curl_file_create(AppConfig::getDirBase().'testes/imgs/gif.gif', 'gif', 'file'),
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 6
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Salvar exercicio sem nome";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: Nome<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'descricao' => 'Neste exercício, deverá mover o tronco e as pernas ao mesmo tempo, fazendo com que se aproximem um do outro e posteriormente se afastem. É um exercício exigente e que trabalha bastante bem o reto do abdómem e oblíquos externos.',
                        'agrupamento' => 9,
                        'aerobico' => 0,
                        'dificuldade' => 1,
                        'aparelho' => 1,
                        'removerGif' => 0,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 6
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Salvar exercicio removendo a imagem";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"id":"*?*"}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'id' => '56b3ca0d51226205048b46da',
                        'nome' => 'Abdominal com Flexão de Quadril',
                        'descricao' => 'Neste exercício, deverá mover o tronco e as pernas ao mesmo tempo, fazendo com que se aproximem um do outro e posteriormente se afastem. É um exercício exigente e que trabalha bastante bem o reto do abdómem e oblíquos externos.',
                        'agrupamento' => 9,
                        'aerobico' => 0,
                        'dificuldade' => 0,
                        'removerGif' => 1,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);