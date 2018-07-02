<?php

use core\AppConfig;
use core\util\RequestPHPTest;

use core\DAO\USUARIODAO;
use core\DAO\CONVITEDAO;
use core\util\ConexaoBD;
use core\util\Auth;

//PARAMETROS GERAIS E INICIALIZAÇÃO
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test = array();

/*

- salvar aluno existente
- load aluno
- listar alunos
- obter nova matricula
- salvar novo aluno
- enviar convite aluno
 * aceitar convite aluno
- desvincular aluno
- salvar aluno existente adicionando foto

*/

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

//$dataInicio = date("d/m/Y");
//$dataFim = "01/01/2100";

//TESTE 1
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarAluno.php';

$test["nome"] = "Caso de sucesso salvar aluno";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'aluno_id' => '56b3ca0d51226205048b45a6',
                        'nome' => 'Teste',
                        'sobrenome' => "Não Usar",
                        'ativo' => 1,
                        'matricula' => '3',
                        'email' => '5@5.com',
                        'dataNascimento' => '14/01/1988',
                        'celular' => '27992553493',
                        'telefone' => '2733362734',
                        'sexo' => 'M',
                        'cep' => '29150270',
                        'rua' => 'Rua Manoel Joaquim dos Santos',
                        'numero' => '56',
                        'bairro' => 'Itacibá',
                        'cidade' => 'Cariacica',
                        'estado' => 'ES',
                        'semFicha' => 0,
                        'removerFoto' => false,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 2
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/aluno/listarAlunos.php';

$test["nome"] = "Caso de sucesso listar alunos";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"itens":[{"matricula":"3","nomeCompleto":"Teste Não Usar","ativo":true,"id":"56b3ca0d51226205048b45a6","estado":4},{"matricula":"1","nomeCompleto":"Ciro Xavier Maretto","ativo":true,"id":"56b3ca0d51226205048b45a4","estado":4},{"matricula":"2","nomeCompleto":"João Marcos Mareto Calado","ativo":true,"id":"56b3ca0d51226205048b45a5","estado":2}],"total":3}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'ativo' => '',
                        'nome' => '',
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

$url = AppConfig::getTestBaseURL().'/core/servicos/aluno/loadAluno.php';

$test["nome"] = "Caso de sucesso load aluno";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"aluno":{"id":"56b3ca0d51226205048b45a6","nomeCompleto":"Teste Não Usar","ativo":"1","matricula":"3","email":"5@5.com","observacoes":null,"dataMatricula":null,"dataDesmatricula":null,"usuario_id":"56b3ca0d51226205048b459e","foto1":null,"nome":"Teste","sobrenome":"Não Usar","sexo":"M","semFicha":false,"dataNascimento":"14\/01\/1988","idade":29,"telefone":"2733362734","celular":"27992553493","cep":"29150270","rua":"Rua Manoel Joaquim dos Santos","numero":"56","bairro":"Itacibá","cidade":"Cariacica","estado":"ES","vinculo":4,"matriculas":[]},"usuario":{"foto1":null,"nome":"Teste","sobrenome":"Não Usar","sexo":"M","dataNascimento":"21\/02\/1988","idade":28,"telefone":null,"celular":null,"cep":null,"rua":null,"numero":null,"bairro":null,"cidade":null,"estado":null}}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'aluno_id' => '56b3ca0d51226205048b45a6',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 4
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/obterNovaMatricula.php';

$test["nome"] = "obter nova matrícula";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"matricula":4}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 5
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarAluno.php';

$test["nome"] = "Caso de sucesso novo aluno";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'nome' => 'Teste',
                        'sobrenome' => "teste",
                        'ativo' => 1,
                        'matricula' => 4,
                        'email' => 'ciro_xm@yahoo.com.br',
                        'dataNascimento' => '17/02/1988',
                        'celular' => '27992283448',
                        'telefone' => '',
                        'sexo' => 'M',
                        'cep' => '29150410',
                        'rua' => 'Rodovia Governador José Henrique Sette',
                        'numero' => '5',
                        'bairro' => 'Itacibá',
                        'cidade' => 'Cariacica',
                        'estado' => 'ES',
                        'semFicha' => 0,
                        'removerFoto' => false,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 6
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/convite/criarConvite.php';

$test["nome"] = "enviar convite a aluno";
$test["url"] = $url;
$test["retornoEsperado"] = '{"msg":"<ul><li>Um convite foi enviado para o aluno.<br \/><br \/> Agora falta só ele aceitar o convite.<\/li><\/ul>","sucesso":true}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'aluno_id' => '56b3ca0d51226205048b45a5',
                        'email' => 'joaomarcosmareto@gmail.com',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 7
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/aluno/desvincularAluno.php';

$test["nome"] = "desvincular aluno";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'aluno_id' => '56b3ca0d51226205048b45a4',
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 8
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarAluno.php';

$test["nome"] = "Caso de sucesso salvar aluno existente adicionando foto";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'aluno_id' => '56b3ca0d51226205048b45a6',
                        'nome' => 'Teste',
                        'sobrenome' => "Não Usar",
                        'ativo' => 1,
                        'matricula' => '3',
                        'email' => '5@5.com',
                        'dataNascimento' => '14/01/1988',
                        'celular' => '27992553493',
                        'telefone' => '2733362734',
                        'sexo' => 'M',
                        'cep' => '29150270',
                        'rua' => 'Rua Manoel Joaquim dos Santos',
                        'numero' => '56',
                        'bairro' => 'Itacibá',
                        'cidade' => 'Cariacica',
                        'estado' => 'ES',
                        'semFicha' => 0,
                        'removerFoto' => false,
                        'file'=>curl_file_create(AppConfig::getDirBase().'testes/imgs/jpg.jpg', 'jpg', 'file'),
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 9
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$CONVITEDAO = new CONVITEDAO($con);
$convites = $CONVITEDAO->retornaTodos();
$convite = empty($convites) ? '' : (string)$convites[0]->getId();

$USUARIODAO = new USUARIODAO($con);
$user_test = $USUARIODAO->retornaTodos(["email"], ['joaomarcosmareto@gmail.com'], ["="], array("limit"=>1))[0];
$user_test->setValidadeToken(Auth::getNewValidadeToken());
$user_test->setPrivateToken(Auth::getNewToken());
$user_test->setPublicToken(Auth::getNewToken());
$user_test->setRefreshToken(Auth::getNewToken());
$USUARIODAO->salvar($user_test);

$public_token = $user_test->getPublicToken();
$private_token = $user_test->getPrivateToken();

$url = AppConfig::getTestBaseURL().'/core/servicos/convite/aceitarConvite.php';

$test["nome"] = "aceitar convite aluno (esse teste depende do sucesso do teste 6)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"countNav":8,"nome":"João Marcos","sobrenome":"Mareto Calado","email":"joaomarcosmareto@gmail.com","sexo":"M","nascimento":"14\/01\/1988","idade":29,"foto90x79":null,"foto36x36":null,"telefone":null,"celular":null,"facebook":null,"cep":null,"rua":null,"numero":null,"bairro":null,"cidade":null,"estado":null,"academias":[{"aluno_id":"56b3ca0d51226205048b45a5","matricula":"2","id":"56b3ca0d51226205048b45a1","nome":"O2 Respirando Qualidade de Vida","descricao":"Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto","foto1":null,"foto2":null,"foto3":null,"foto4":null,"horarioFuncionamento":"De 2ª a 6ª de 6h às 22h. Sábado de 6h às 12h.","logo":null,"rua":"Manoel Joaquim dos Santos","numero":"20","complemento":"Em cima da Padaria Monza","bairro":"Itacibá","cidade":"Cariacica","estado":"ES","telefone1":null,"telefone2":"2799999999","email":null,"siteLink":"https:\/\/www.google.com.br\/maps\/@-20.3226672,-40.3764407,3a,75y,44.27h,102.17t\/data=!3m4!1e1!3m2!1sp4F25fVq7HhOVdB2_OSUSA!2e0","socialLink":"https:\/\/www.facebook.com\/respirando.devida","latitude":"-20.3226672","longitude":"-40.3764407"}],"auth":{"privateToken":"*?*","publicToken":"*?*","refreshToken":"*?*"},"sucesso":true}';
$test["postFields"] = array(
                        'convite_id' => $convite,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);