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

1 listar fichas
2 load ficha
3 salvar ficha existente
4 salvar nova ficha
5 salvar ficha sem exercicio

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

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/ficha/listarFicha.php';


//TESTE 1
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Listar fichas";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"itens":[{"nomeCompleto":"Rodrigo Carecão Barbudo","treinos":"A, B","dataInicio":"05\/02\/2016","dataVencimento":"05\/03\/2016","id":"56b3cae5512262d5288b473d"},{"nomeCompleto":"Rodrigo Carecão Barbudo","treinos":"A, B, C, D","dataInicio":"04\/02\/2016","dataVencimento":"04\/03\/2016","id":"56b3cc23512262dd288b4581"}],"total":2}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'aluno_id' => '56b3ca0d51226205048b45a6',
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

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/ficha/loadFicha.php';

$test["nome"] = "Load ficha";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"ficha":{"id":"56b3cae5512262d5288b473d","dataInicio":"2016\/02\/05","dataFim":"2016\/03\/05","anotacoes":"lalalalala","exercicios":[[{"_id":"56b3cb7951226207048b4581","exercicio_id":"56b3ca0d51226205048b4697","nome":"Crucifixo Declinado com Halteres","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/10.jpg","series":3,"repeticoes":10,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4582","exercicio_id":"56b3ca0d51226205048b469b","nome":"Crucifixo em Máquina","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/13.jpg","series":3,"descanso":30,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4583","exercicio_id":"56b3ca0d51226205048b4699","nome":"Crucifixo em pé em Polia Baixa","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/12.jpg","series":4,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4584","exercicio_id":"56b3ca0d51226205048b469e","nome":"Rosca Concentrada","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/16.jpg","series":4,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4585","exercicio_id":"56b3ca0d51226205048b46a0","nome":"Rosca Scoot com Halter","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/18.jpg","series":4,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4586","exercicio_id":"56b3ca0d51226205048b469c","nome":"Rosca com Barra","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/14.jpg","series":4,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4587","exercicio_id":"56b3ca0d51226205048b46e0","nome":"Abdominal em Roda","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/81.jpg","series":4,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]}],[{"_id":"56b3cb7951226207048b4588","exercicio_id":"56b3ca0d51226205048b46b7","nome":"Remada com Barra","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/41.jpg","series":1,"repeticoes":10,"carga":55,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4589","exercicio_id":"56b3ca0d51226205048b46ba","nome":"Remada em Polia Baixa","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/44.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b458a","exercicio_id":"56b3ca0d51226205048b46b8","nome":"Remada com Barra em Supinação","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/42.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b458b","exercicio_id":"56b3ca0d51226205048b46ad","nome":"Puxada de Tríceps com Corda","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/31.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b458c","exercicio_id":"56b3ca0d51226205048b46ab","nome":"Puxada de Tríceps","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/29.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b458d","exercicio_id":"56b3ca0d51226205048b46ac","nome":"Puxada de Tríceps em Supinação","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/30.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b458e","exercicio_id":"56b3ca0d51226205048b46da","nome":"Abdominal com Flexão de Quadril","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/75.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b458f","exercicio_id":"56b3ca0d51226205048b46df","nome":"Prancha Lateral","aerobico":true,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/80.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]}]]}}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'ficha_id' => '56b3cae5512262d5288b473d',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 3
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/ficha/salvarFicha.php';

$test["nome"] = "Salvar ficha existente";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'ficha' => '{"_id":"56b3cae5512262d5288b473d","academia_id":"56b3ca0d51226205048b45a1","exercicios":[[{"_id":"56dc1c4d50e7df321250df93","exercicio_id":"56b3ca0d51226205048b4697","nome":"Crucifixo Declinado com Halteres","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/10.jpg","series":3,"repeticoes":10,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56dc1c4d50e7df321250df94","exercicio_id":"56b3ca0d51226205048b469b","nome":"Crucifixo em Máquina","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/13.jpg","series":3,"descanso":30,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56dc1c4d50e7df321250df95","exercicio_id":"56b3ca0d51226205048b4699","nome":"Crucifixo em pé em Polia Baixa","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/12.jpg","series":4,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56dc1c4d50e7df321250df96","exercicio_id":"56b3ca0d51226205048b469e","nome":"Rosca Concentrada","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/16.jpg","series":4,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56dc1c4d50e7df321250df97","exercicio_id":"56b3ca0d51226205048b46a0","nome":"Rosca Scoot com Halter","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/18.jpg","series":4,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56dc1c4d50e7df321250df98","exercicio_id":"56b3ca0d51226205048b469c","nome":"Rosca com Barra","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/14.jpg","series":4,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56dc1c4d50e7df321250df99","exercicio_id":"56b3ca0d51226205048b46e0","nome":"Abdominal em Roda","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/81.jpg","series":4,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]}],[{"_id":"56dc1c4d50e7df321250df9a","exercicio_id":"56b3ca0d51226205048b46b7","nome":"Remada com Barra","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/41.jpg","series":1,"repeticoes":10,"carga":55,"descanso":60,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56dc1c4d50e7df321250df9b","exercicio_id":"56b3ca0d51226205048b46ba","nome":"Remada em Polia Baixa","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/44.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56dc1c4d50e7df321250df9c","exercicio_id":"56b3ca0d51226205048b46b8","nome":"Remada com Barra em Supinação","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/42.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56dc1c4d50e7df321250df9d","exercicio_id":"56b3ca0d51226205048b46ad","nome":"Puxada de Tríceps com Corda","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/31.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56dc1c4d50e7df321250df9e","exercicio_id":"56b3ca0d51226205048b46ab","nome":"Puxada de Tríceps","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/29.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56dc1c4d50e7df321250df9f","exercicio_id":"56b3ca0d51226205048b46ac","nome":"Puxada de Tríceps em Supinação","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/30.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56dc1c4d50e7df321250dfa0","exercicio_id":"56b3ca0d51226205048b46da","nome":"Abdominal com Flexão de Quadril","aerobico":false,"gif":"https://localhost:8088/wm/imgs/e/e/75.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56dc1c4d50e7df321250dfa1","exercicio_id":"56b3ca0d51226205048b46df","nome":"Prancha Lateral","aerobico":true,"gif":"https://localhost:8088/wm/imgs/e/e/80.jpg","und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]}]],"anotacoes":"lalalalala","aluno_id":"56b3ca0d51226205048b45a6","dataInicio":"05/02/2016","dataFim":"05/03/2016"}',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 4
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Salvar nova ficha";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'ficha' => '{"academia_id":"56b3ca0d51226205048b45a1","exercicios":[[{"exercicio_id":"56b3ca0d51226205048b4695","nome":"Crucifixo com Halteres","aerobico":false,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","series":"1","repeticoes":"1","carga":"1","descanso":"1"}],[{"exercicio_id":"56b3ca0d51226205048b469b","nome":"Crucifixo em Máquina","aerobico":false,"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","series":"2","repeticoes":"2","carga":"2","descanso":"2"}]],"anotacoes":"asdasdasdasd","aluno_id":"56b3ca0d51226205048b45a6","dataInicio":"06/03/2016","dataFim":"06/04/2016"}',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 5
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


$test["nome"] = "Salvar nova ficha sem exercicios";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>A ficha deve possuir ao menos um exercício.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'ficha' => '{"academia_id":"56b3ca0d51226205048b45a1","exercicios":[[]],"anotacoes":"asdasdasdasd","aluno_id":"56b3ca0d51226205048b45a6","dataInicio":"06/03/2016","dataFim":"06/04/2016"}',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

