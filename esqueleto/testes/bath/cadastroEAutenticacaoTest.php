<?php

use core\AppConfig;
use core\util\RequestPHPTest;

use core\DAO\USUARIODAO;
use core\util\ConexaoBD;

//PARAMETROS GERAIS E INICIALIZAÇÃO
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$url = AppConfig::getTestBaseURL()."/core/servicos/empresa/registerUsuarioAcademia.php";

$test = array();


/*

1 algum campo em branco
2 algum campo enorme (+255)
3 nome ou sobrenome com numero
4 sexo diferente de M ou F
5 senha curta
6 senha longa
7 senha sem numero
8 email inválido (sem @)
9 email enorme (+255)
10 dataNascimento inválida
11 cadastro de usuário com dados válidos
12 Confimar cadastro feito no teste anterior
13 trocar senha de conta confirmada no teste anterior
14 realizar a troca da senha solicitada no teste anterior

*/

//TESTE 1     
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Algum campo em branco";
$test["url"] = $url;
$test["retornoEsperado"] = '{"dadosInvalidos":"'.RequestPHPTest::$NO_COMPARE.'", "sucesso":false}';
$test["postFields"] = array(
                        'nome' => 'Teste',
                        'sobrenome' => 'Teste',
                        'sexo' => 'M',
                        'dataNascimento' => '17/02/1988',
                        'senha' => '123abC00',
                        'email' => 'a@'.rand().rand().'.com',
                    );
RequestPHPTest::somePostFieldBlank($url, $test, $result, array('dataNascimento'), RequestPHPTest::$NO_COMPARE);


//TESTE 2
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~        

$test["nome"] = "Algum campo enorme (+255)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"dadosInvalidos":"'.RequestPHPTest::$NO_COMPARE.'", "sucesso":false}';
$test["postFields"] = array(
                        'nome' => 'Teste',
                        'sobrenome' => 'Teste',
                        'sexo' => 'M',
                        'dataNascimento' => '17/02/1988',
                        'senha' => '123abC00',
                        'email' => 'a@'.rand().rand().'.com',
                    );
RequestPHPTest::somePostFieldBig($url, $test, $result, array(), RequestPHPTest::$NO_COMPARE);


//TESTE 3
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~        

//TODO:
//$test["nome"] = "Nome com somente números";
//$test["url"] = $url;
//$test["retornoEsperado"] = '{"dadosInvalidos":"'.RequestPHPTest::$NO_COMPARE.'", "sucesso":false}';
//$test["postFields"] = array(
//                        'nome' => '123123',
//                        'sobrenome' => 'Teste',
//                        'sexo' => 'M',
//                        'dataNascimento' => '17/02/1988',
//                        'senha' => '123abC00',
//                        'email' => 'a@'.rand().rand().'.com',
//                    );
//$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);
//
//$test["nome"] = "Sobrenome com somente números";
//$test["url"] = $url;
//$test["retornoEsperado"] = '{"dadosInvalidos":"'.RequestPHPTest::$NO_COMPARE.'", "sucesso":false}';
//$test["postFields"] = array(
//                        'nome' => 'Teste',
//                        'sobrenome' => '123123',
//                        'sexo' => 'M',
//                        'dataNascimento' => '17/02/1988',
//                        'senha' => '123abC00',
//                        'email' => 'a@'.rand().rand().'.com',
//                    );
//$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 4
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~        

$test["nome"] = "Sexo diferente de M ou F";
$test["url"] = $url;
$test["retornoEsperado"] = '{"dadosInvalidos":"'.RequestPHPTest::$NO_COMPARE.'", "sucesso":false}';
$test["postFields"] = array(
                        'nome' => 'Teste',
                        'sobrenome' => 'Teste',
                        'sexo' => 'H',
                        'dataNascimento' => '17/02/1988',
                        'senha' => '123abC00',
                        'email' => 'a@'.rand().rand().'.com',
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 5
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~        

$test["nome"] = "Senha curta";
$test["url"] = $url;
$test["retornoEsperado"] = '{"dadosInvalidos":"'.RequestPHPTest::$NO_COMPARE.'", "sucesso":false}';
$test["postFields"] = array(
                        'nome' => 'Teste',
                        'sobrenome' => 'Teste',
                        'sexo' => 'M',
                        'dataNascimento' => '17/02/1988',
                        'senha' => '123abC',
                        'email' => 'a@'.rand().rand().'.com',
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 6
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~        

$test["nome"] = "Senha longa";
$test["url"] = $url;
$test["retornoEsperado"] = '{"dadosInvalidos":"'.RequestPHPTest::$NO_COMPARE.'", "sucesso":false}';
$test["postFields"] = array(
                        'nome' => 'Teste',
                        'sobrenome' => 'Teste',
                        'sexo' => 'M',
                        'dataNascimento' => '17/02/1988',
                        'senha' => 'upllmIVnjyvGklxtxKLGafkApgPyRhogUzznwfDSqIHZWtMXHfKUTVcVRlErYmrrcMAsXcaCmysuNNjwSAPWdbFyloQpwgIeRaHxcFtLHexBJOgfcGJdnTDSiGxthTUqSBtXgnHibcmVTCxlKtPyZfBFLuSBZwOjOwFnpmXJOECMwVXkfNEStiBZzNnNNPOxQDenmheYIaujpFSfMOnbjcEapDvzfpEhjIiosUoUvtLNwKGtbMCgdsEKOcxdUnmu213',
                        'email' => 'a@'.rand().rand().'.com',
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 7
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~        

$test["nome"] = "Senha sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"dadosInvalidos":"'.RequestPHPTest::$NO_COMPARE.'", "sucesso":false}';
$test["postFields"] = array(
                        'nome' => 'Teste',
                        'sobrenome' => 'Teste',
                        'sexo' => 'M',
                        'dataNascimento' => '17/02/1988',
                        'senha' => 'AAABBBccc',
                        'email' => 'a@'.rand().rand().'.com',
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 8
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~        

$test["nome"] = "Email inválido (sem @)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"dadosInvalidos":"'.RequestPHPTest::$NO_COMPARE.'", "sucesso":false}';
$test["postFields"] = array(
                        'nome' => 'Teste',
                        'sobrenome' => 'Teste',
                        'sexo' => 'M',
                        'dataNascimento' => '17/02/1988',
                        'senha' => '123abC00',
                        'email' => 'a'.rand().rand().'.com',
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 9
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~        
$test["nome"] = "Email enorme (+ 255)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"dadosInvalidos":"'.RequestPHPTest::$NO_COMPARE.'", "sucesso":false}';
$test["postFields"] = array(
                        'nome' => 'Teste',
                        'sobrenome' => 'Teste',
                        'sexo' => 'M',
                        'dataNascimento' => '17/02/1988',
                        'senha' => '123abC00',
                        'email' => 'upllmIVnjyvGklxtxKLGafkApgPyRhogUzznwfDSqIHZWtMXHfKUTVcVRlErYmrrcMAsXcaCmysuNNjwSAPWdbFyloQpwgIeRaHxcFtLHexBJOgfcGJdnTDSiGxthTUqSBtXgnHibcmVTCxlKtPyZfBFLuSBZwOjOwFnpmXJOECMwVXkfNEStiBZzNnNNPOxQDenmheYIaujpFSfMOnbjcEapDvzfpEhjIiosUoUvtLNwKGtbMCgdsEKOcxdUnmu213@'.rand().rand().'.com',
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 10
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~  
$test["nome"] = "DataNascimento inválida > 7/02/2000";
$test["url"] = $url;
$test["retornoEsperado"] = '{"dadosInvalidos":"'.RequestPHPTest::$NO_COMPARE.'", "sucesso":false}';
$test["postFields"] = array(
                        'nome' => 'Teste',
                        'sobrenome' => 'Teste',
                        'sexo' => 'M',
                        'dataNascimento' => '7/02/2000',
                        'senha' => '123abC00',
                        'email' => 'a@'.rand().rand().'.com',
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


$test["nome"] = "DataNascimento inválida > 01/01/2090";
$test["url"] = $url;
$test["retornoEsperado"] = '{"dadosInvalidos":"'.RequestPHPTest::$NO_COMPARE.'", "sucesso":false}';
$test["postFields"] = array(
                        'nome' => 'Teste',
                        'sobrenome' => 'Teste',
                        'sexo' => 'M',
                        'dataNascimento' => '07/02/2090',
                        'senha' => '123abC00',
                        'email' => 'a@'.rand().rand().'.com',
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


$test["nome"] = "DataNascimento inválida > asdasda";
$test["url"] = $url;
$test["retornoEsperado"] = '{"dadosInvalidos":"'.RequestPHPTest::$NO_COMPARE.'", "sucesso":false}';
$test["postFields"] = array(
                        'nome' => 'Teste',
                        'sobrenome' => 'Teste',
                        'sexo' => 'M',
                        'dataNascimento' => 'asdasda',
                        'senha' => '123abC00',
                        'email' => 'a@'.rand().rand().'.com',
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 11
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
$email_test11 = 'a@'.rand().rand().'.com';
$test["nome"] = "Cadastro de usuário com dados válidos";
$test["url"] = $url;
$test["retornoEsperado"] = '{"msg":"<ul><li>Foi enviado para seu e-mail o código de ativação solicitado abaixo.<\/li><\/ul>","sucesso":true}';
$test["postFields"] = array(
                        'nome' => 'Teste',
                        'sobrenome' => 'Teste',
                        'sexo' => 'M',
                        'dataNascimento' => '17/02/1988',
                        'senha' => '123abC00',
                        'email' => $email_test11,
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 12
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
$url2 = AppConfig::getTestBaseURL()."/core/servicos/autenticacao/confirmarCadastro.php";

$con = ConexaoBD::getInstance();
$USUARIODAO = new USUARIODAO($con);

$user_test = $USUARIODAO->retornaTodos(["email"], [$email_test11], ["="], array("limit"=>1));

$cod = !empty($user_test) ? $user_test[0]->getAccToken() : null;

$test["nome"] = "Confimar cadastro feito no teste anterior";
$test["url"] = $url2;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'codigo' => $cod,
                        'email' => $email_test11,
                    );
$result[] = RequestPHPTest::basicTest($url2, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 13
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
$url3 = AppConfig::getTestBaseURL()."/core/servicos/autenticacao/recuperarSenha.php";

$con = ConexaoBD::getInstance();
$USUARIODAO = new USUARIODAO($con);

$user_test = $USUARIODAO->retornaTodos(["email"], [$email_test11], ["="], array("limit"=>1));

$cod = !empty($user_test) ? $user_test[0]->getAccToken() : null;

$test["nome"] = "trocar senha de conta confirmada no teste anterior";
$test["url"] = $url3;
$test["retornoEsperado"] = '{"msg":"<ul><li>Foi enviado para seu e-mail o código solicitado para a troca de senha.<\/li><\/ul>","sucesso":true}';
$test["postFields"] = array(
                        'email' => $email_test11,
                    );
$result[] = RequestPHPTest::basicTest($url3, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 14
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
$url4 = AppConfig::getTestBaseURL()."/core/servicos/autenticacao/trocarSenha.php";
$user_test = $USUARIODAO->retornaTodos(["email"], [$email_test11], ["="], array("limit"=>1));

$cod = !empty($user_test) ? $user_test[0]->getPwdToken() : null;

$test["nome"] = "realizar a troca da senha solicitada no teste anterior";
$test["url"] = $url4;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'codigo' => $cod,
                        'email' => $email_test11,
                        'novasenha' => '456abC00',
                        'confirmarsenha' => '456abC00',
                    );
$result[] = RequestPHPTest::basicTest($url4, $test, RequestPHPTest::$NO_COMPARE);