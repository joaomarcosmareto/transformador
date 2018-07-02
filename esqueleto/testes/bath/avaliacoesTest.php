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

1 listar avaliações
2 load avaliaçao
3 salvar avaliaçao existente
4 salvar nova avaliaçao
5 salvar avaliaçao sem data

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

$url = AppConfig::getTestBaseURL().'/core/servicos/avaliacao/listarAvaliacao.php';


//TESTE 1
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Listar avaliações";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"itens":[{"nomeCompleto":"Rodrigo Carecão Barbudo","data":"04\/02\/2016","peso":65,"imc":23.31,"rcq":88,"gorduraAtual":24.6,"id":"56b3cca351226204048b4757"}],"total":1}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'aluno_id' => '56b3ca0d51226205048b45a4',
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

$url = AppConfig::getTestBaseURL().'/core/servicos/avaliacao/loadAvaliacao.php';

$test["nome"] = "Load avaliacao";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"avaliacao":{"data":"2016\/02\/04","professor_id":"56b3ca0d51226205048b45ab","anamnese":{"objetivo":"ss","pratica":"ss","medicamento":"ss","cirurgia":"ss","doenca":"ss","observacao":"ss"},"parq":{"um":"0","dois":"1","tres":"0","quatro":"0","cinco":"1","seis":"0","sete":"0"},"medidas":{"peso":65,"imc":23.31,"imcRisco":["(Normal)","text-primary"],"altura":1.67,"marinha":19.1,"pescoco":40,"ombro":50,"toraxRelaxado":80,"toraxInspirado":60,"cintura":88,"rcq":88,"rcqRisco":["(Risco Muito Alto)","text-danger"],"ca":"1","caRisco":["(Faixa Ideal)","text-primary"],"abdomem":1,"quadril":1,"bracoRelaxadoDireito":1,"bracoRelaxadoEsquerdo":1,"bracoContraidoDireito":1,"bracoContraidoEsquerdo":1,"antebracoDireito":1,"antebracoEsquerdo":1,"coxaDireita":1,"coxaEsquerda":1,"panturrilhaDireita":1,"panturrilhaEsquerda":1},"composicao":{"protocolo":3,"subescapular":25,"tricipital":30,"supraIliaca":40,"abdominal":28,"gorduraAtual":24.6,"massaGorda":15.99,"massaMagra":49.01,"bicipital":null,"axilarMedia":null,"peitoral":null,"coxa":null,"panturrilha":null},"foto1":null,"foto2":null,"foto3":null,"anotacoes":"lalalalala"}}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'avaliacao_id' => '56b3cca351226204048b4757',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 3
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/avaliacao/salvarAvaliacao.php';

$test["nome"] = "Salvar avaliacao existente";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'avaliacao' => '{"_id":"56b3cca351226204048b4757","academia_id":"56b3ca0d51226205048b45a1","aluno_id":"56b3ca0d51226205048b45a4","anotacoes":"lalalalala","data":"04/02/2016","anamnese":{"objetivo":"ss","pratica":"ss","medicamento":"ss","cirurgia":"ss","doenca":"ss","observacao":"ss"},"parq":{"um":"0","dois":"1","tres":"0","quatro":"0","cinco":"1","seis":"0","sete":"0"},"medidas":{"peso":"65","imc":"23,31","imcRisco":["(Normal)","text-primary"],"altura":"1,67","marinha":"19,1","pescoco":"40","ombro":"50","toraxRelaxado":"80","toraxInspirado":"60","cintura":"88","rcq":"88","rcqRisco":["(Risco Muito Alto)","text-danger"],"ca":"1","caRisco":["(Faixa Ideal)","text-primary"],"abdomem":"1","quadril":"1","bracoRelaxadoDireito":"1","bracoRelaxadoEsquerdo":"1","bracoContraidoDireito":"1","bracoContraidoEsquerdo":"1","antebracoDireito":"1","antebracoEsquerdo":"1","coxaDireita":"1","coxaEsquerda":"1","panturrilhaDireita":"1","panturrilhaEsquerda":"1"},"composicao":{"protocolo":"3","subescapular":"25","tricipital":"30","supraIliaca":"40","abdominal":"28","gorduraAtual":"24,6 %","massaGorda":"15,99 Kg","massaMagra":"49,01 Kg","bicipital":null,"axilarMedia":null,"peitoral":null,"coxa":null,"panturrilha":null}}',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 4
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Salvar nova avaliacao";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'avaliacao' => '{"academia_id":"56b3ca0d51226205048b45a1","aluno_id":"56b3ca0d51226205048b45a6","anotacoes":"asdasdasd","data":"06/03/2016","anamnese":{},"parq":{"um":"0","dois":"0","tres":"0","quatro":"0","cinco":"0","seis":"0","sete":"0"},"medidas":{"peso":"65","imc":"23,88","imcRisco":["(Normal)","text-primary"],"altura":"1.65","marinha":"","rcqRisco":"","caRisco":""},"composicao":{}}',                        
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 5
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Salvar nova avaliacao sem data";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Data inválida: <\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'avaliacao' => '{"academia_id":"56b3ca0d51226205048b45a1","aluno_id":"56b3ca0d51226205048b45a6","anotacoes":"asdasdasd","data":"","anamnese":{},"parq":{"um":"0","dois":"0","tres":"0","quatro":"0","cinco":"0","seis":"0","sete":"0"},"medidas":{"peso":"65","imc":"23,88","imcRisco":["(Normal)","text-primary"],"altura":"1.65","marinha":"","rcqRisco":"","caRisco":""},"composicao":{}}',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);