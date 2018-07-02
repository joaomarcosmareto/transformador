<?php

use core\AppConfig;
use core\util\RequestPHPTest;

use core\DAO\USUARIODAO;
use core\util\ConexaoBD;
use core\util\Auth;

//PARAMETROS GERAIS E INICIALIZAÇÃO
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test = array();

// primeiro plano é usado para remoção de plano ( teste 16 )
// segundo plano é ativo e é usado para criação de matrícula ( teste 33 )
// terceiro plano é inativo
// quarto plano é removido
// quinto plano é para teste de matrícula encerrada e rematrícula ( teste 35 )
// sexto plano é para teste de encerramento de matrícula
/*

1. Salvar plano com tudo vazio - RETORNAR ERRO
2. Salvar plano com tudo preenchido e sem nome - RETORNAR ERRO
3. Salvar plano com tudo preenchido e sem valor - RETORNAR ERRO
4. Salvar plano com tudo preenchido e sem periodicidade da taxa do serviço (mensalidade) - RETORNAR ERRO
5. Salvar plano com valor de matrícula menor que 0 - RETORNAR ERRO
6. Salvar plano com valor de mensalidade menor que 0 - RETORNAR ERRO
7. Salvar plano com valor de matricula e mensalidade menores que 0 - RETORNAR ERRO
8. Salvar plano com valor de tolerancia de acesso menores que 0 - RETORNAR ERRO
9. Salvar plano sem atividade - RETORNAR SUCESSO
10. Salvar plano com 1 atividade - RETORNAR SUCESSO
11. Salvar plano com mais de 1 atividade - RETORNAR SUCESSO
12. Salvar plano com 1 atividade inexistente - RETORNAR ERRO
13. Salvar plano com mais de 1 atividade inexistente - RETORNAR ERRO

14. Listagem de planos ativos - RETORNAR SUCESSO
15. Listagem de planos ativos de outra academia - RETORNAR ERRO

16. Remover plano ativo da academia - RETORNAR SUCESSO
17. Remover plano ativo de outra academia - RETORNAR ERRO

Encerramento de Matrícula
1. sem nenhum id
2. só id mas vazio
3. só academia_id mas vazio
4. só aluno_id mas vazio
5. só id e academia_id mas vazio
6. só id e aluno_id mas vazio
7. só academia_id e aluno_id mas vazio
8. todos mas vazio

9. só id preenchido, mas inexistente
10. só academia_id preenchido mas inexistente
11. só aluno_id preenchido mas inexistente
12. só id e academia_id preenchido, mas inexistente
13. só id e aluno_id preenchido mas inexistente
14. só academia_id e aluno_id preenchido mas inexistente
15. todos preenchidos mas inexistentes

16. só id e academia_id preenchido mas que nao casam
17. só id e aluno_id preenchido mas que nao casam
18. só academia_id e aluno_id preenchido mas que nao casam
19. todos preenchido mas que nao casam

20. desmatricular com sucesso
21. desmatricular a matricula já desmatriculada

Salvar Recebimento
1. sem nenhum id
2. só academia_id mas vazio
3. só aluno_id mas vazio
4. só academia_id e aluno_id mas vazio

5. só academia_id preenchido mas inexistente
6. só aluno_id preenchido mas inexistente
7. só academia_id e aluno_id preenchido mas inexistente

8. só academia_id e aluno_id preenchido mas que nao casam

9. incluir campo referente diferente de 1 a 4
10. incluir campo referente igual a 1 ou 2 sem matricula_id
11. incluir campo forma diferente de 1 a 3

12. incluir matricula_id sem ter campo referente
13. incluir matricula_id com campo referente diferente de 1 ou 2
14. incluir matricula_id com campo referente igual a 1 ou 2

15. tentar pagar sem valor na request
16. tentar pagar com valor vazio
17. tentar alterar desconto sem ser gerente

18. botar valor negativo
19. botar desconto negativo
20. botar desconto maior que valor

Listar Recebimento
1. sem nenhum id
2. só academia_id mas vazio
3. só aluno_id mas vazio
4. só academia_id e aluno_id mas vazio

5. só academia_id preenchido mas inexistente
6. só aluno_id preenchido mas inexistente
7. só academia_id e aluno_id preenchido mas inexistente

8. só academia_id e aluno_id preenchido mas que nao casam

9. listar recebimento sem pagina atual e sem numero de registros na request
10. listar recebimento com pagina atual vazio
11. listar recebimento com numero de registros vazio
12. listar recebimento com pagina atual e numero de registros vazio
13. listar recebimento com pagina atual igual a 0
14. listar recebimento com numero de registros igual a 0

15. listar recebimento
16. listar recebimento de outra academia


Load Recebimento
1. sem nenhum id
2. só id mas vazio
3. só academia_id mas vazio
4. só aluno_id mas vazio
5. só id e academia_id mas vazio
6. só id e aluno_id mas vazio
7. só academia_id e aluno_id mas vazio
8. todos mas vazio

9. só id preenchido, mas inexistente
10. só academia_id preenchido mas inexistente
11. só aluno_id preenchido mas inexistente
12. só id e academia_id preenchido, mas inexistente
13. só id e aluno_id preenchido mas inexistente
14. só academia_id e aluno_id preenchido mas inexistente
15. todos preenchidos mas inexistentes

16. só id e academia_id preenchido mas que nao casam
17. só id e aluno_id preenchido mas que nao casam
18. só academia_id e aluno_id preenchido mas que nao casam
19. todos preenchido mas que nao casam

20. load recebimento de outra academia
21. load recebimento

*/

//echo 'Estes testes requerem banco populado no popula banco e que o usuário 1@1.com já tenha logado</br>';

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$con = ConexaoBD::getInstance();
$USUARIODAO = new USUARIODAO($con);

$user_test = $USUARIODAO->retornaTodos(["email"], ['rodrigo@email.com.br'], ["="], array("limit"=>1))[0];
$user_test->setValidadeToken(Auth::getNewValidadeToken());
$USUARIODAO->salvar($user_test);

$timestamp_test = '123';
$public_token = $user_test->getPublicToken();
$private_token = $user_test->getPrivateToken();

//TESTE 1 salvar plano com tudo vazio - RETORNAR ERRO
$plano = '{"nome":"","ativo":"","valor":"","valorMatricula":"","intervalo":"","atividades":"","tolerancia":"","toleranciaAcesso":""}';

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Salvar plano com tudo vazio";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: Nome do plano<\/li><li>Campo inválido: Valor de matrícula do plano<\/li><li>Campo inválido: Valor do plano<\/li><li>Campo inválido: intervalo para pagamentos do plano<\/li><li>Campo inválido: Tolerância em dias de inadimplência do plano.<\/li><li>Campo inválido: Tolerância em dias de inadimplência do plano em que o aluno terá acesso ao negócio.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => $plano,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 2 salvar plano com tudo preenchido e sem nome - RETORNAR ERRO
$plano = '{"nome":"","ativo":"true","valor":"100","valorMatricula":"30","intervalo":"mensal","atividades":"","tolerancia":"5","toleranciaAcesso":"5"}';

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Salvar plano com tudo preenchido e sem nome";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: Nome do plano<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => $plano,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 3 salvar plano com tudo preenchido e sem valor - RETORNAR ERRO
$plano = '{"nome":"plano planodo planador","ativo":"true","valor":"","valorMatricula":"30","intervalo":"mensal","atividades":"","tolerancia":"5","toleranciaAcesso":"5"}';

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Salvar plano com tudo preenchido e sem valor";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo inválido: Valor do plano<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => $plano,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 4 salvar plano com tudo preenchido e sem periodicidade da taxa do serviço (mensalidade) - RETORNAR ERRO
$plano = '{"nome":"plano planodo planador","ativo":"true","valor":"100","valorMatricula":"30","intervalo":"","atividades":"","tolerancia":"5","toleranciaAcesso":"5"}';

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Salvar plano com tudo preenchido e sem periodicidade da taxa do serviço (mensalidade)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo inválido: intervalo para pagamentos do plano<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => $plano,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 5 salvar plano com valor de matrícula menor que 0 - RETORNAR ERRO
$plano = '{"nome":"plano planodo planador","ativo":"true","valor":"100","valorMatricula":"-30","intervalo":"mensal","atividades":"","tolerancia":"5","toleranciaAcesso":"5"}';

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Salvar plano com valor de matrícula menor que 0";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ter valor negativo: Valor de matrícula do plano<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => $plano,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 6 salvar plano com valor de mensalidade menor que 0 - RETORNAR ERRO
$plano = '{"nome":"plano planodo planador","ativo":"true","valor":"-100","valorMatricula":"30","intervalo":"mensal","atividades":"","tolerancia":"5","toleranciaAcesso":"5"}';

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Salvar plano com valor de mensalidade menor que 0";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ter valor negativo: Valor do plano<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => $plano,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 7 salvar plano com valor de matricula e mensalidade menores que 0 - RETORNAR ERRO
$plano = '{"nome":"plano planodo planador","ativo":"true","valor":"-100","valorMatricula":"-30","intervalo":"mensal","atividades":"","tolerancia":"5","toleranciaAcesso":"5"}';

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Salvar plano com valor de matricula e mensalidade menores que 0";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ter valor negativo: Valor de matrícula do plano<\/li><li>Campo não pode ter valor negativo: Valor do plano<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => $plano,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 8 salvar plano com valor de tolerancia de acesso menores que 0 - RETORNAR ERRO
$plano = '{"nome":"plano planodo planador","ativo":"true","valor":"100","valorMatricula":"30","intervalo":"mensal","atividades":"","tolerancia":"-5","toleranciaAcesso":"-5"}';

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Salvar plano com valor de tolerancia de acesso menores que 0";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ter valor negativo: Tolerância em dias de inadimplência do plano.<\/li><li>Campo não pode ter valor negativo: Tolerância em dias de inadimplência do plano em que o aluno terá acesso ao negócio.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => $plano,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 9 salvar plano sem atividade - RETORNAR SUCESSO
$plano = '{"nome":"plano planodo planador","ativo":"true","valor":"100","valorMatricula":"30","intervalo":"mensal","atividades":"","tolerancia":"5","toleranciaAcesso":"5"}';

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Salvar plano sem atividade";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"id":"*?*"}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => $plano,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 10 salvar plano com 1 atividade - RETORNAR SUCESSO
$plano = '{"nome":"plano planodo planador","ativo":"true","valor":"100","valorMatricula":"30","intervalo":"mensal","atividades":["Musculação"],"tolerancia":"5","toleranciaAcesso":"5"}';

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Salvar plano com 1 atividade";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"id":"*?*"}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => $plano,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 11 salvar plano com mais de 1 atividade - RETORNAR SUCESSO
$plano = '{"nome":"plano planodo planador","ativo":"true","valor":"100","valorMatricula":"30","intervalo":"mensal","atividades":["Musculação","Jump"],"tolerancia":"5","toleranciaAcesso":"5"}';

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Salvar plano com mais de 1 atividade";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"id":"*?*"}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => $plano,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 12 salvar plano com 1 atividade inexistente - RETORNAR ERRO
$plano = '{"nome":"plano planodo planador","ativo":"true","valor":"100","valorMatricula":"30","intervalo":"mensal","atividades":["Capoeira"],"tolerancia":"5","toleranciaAcesso":"5"}';

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Salvar plano com 1 atividade inexistente";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>As atividades do plano devem ser previamente cadastradas no negócio<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => $plano,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 13 salvar plano com mais de 1 atividade inexistente - RETORNAR ERRO
$plano = '{"nome":"plano planodo planador","ativo":"true","valor":"100","valorMatricula":"30","intervalo":"mensal","atividades":["Kung-Fu","Capoeira"],"tolerancia":"5","toleranciaAcesso":"5"}';

$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Salvar plano com mais de 1 atividade inexistente";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>As atividades do plano devem ser previamente cadastradas no negócio<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => $plano,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 14 Listagem de planos não removidos - RETORNAR SUCESSO
$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/listarPlano.php';

$test["nome"] = "Listagem de planos não removidos";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true,"itens":[{"id":"57869e86f12b7b20346cfaa0","nome":"Todas Modalidades","ativo":true,"valorMatricula":50,"valor":120,"intervalo":"mensal","atividades":["Musculação","Jump","Zumba","Jiu Jitsu"],"tolerancia":7,"toleranciaAcesso":5},{"id":"5787efc0f12b7b048a7d3806","nome":"Sem Luta","ativo":true,"valorMatricula":50,"valor":100,"intervalo":"mensal","atividades":["Musculação","Jump","Zumba"],"tolerancia":5,"toleranciaAcesso":5},{"id":"5788d470f12b7b04770bf2f7","nome":"Somente Ginástica","ativo":false,"valorMatricula":25,"valor":75,"intervalo":"mensal","atividades":["Jump","Zumba"],"tolerancia":5,"toleranciaAcesso":5},{"id":"578cc9b7f12b7b15e409a346","nome":"Plano Monstro","ativo":true,"valorMatricula":99.99,"valor":100,"intervalo":"mensal","atividades":["Musculação","Jump","Zumba","Jiu Jitsu"],"tolerancia":5,"toleranciaAcesso":5},{"id":"578e1a2df12b7b73097af214","nome":"Plano para encerramento de matrícula","ativo":true,"valorMatricula":99.99,"valor":100,"intervalo":"mensal","atividades":["Musculação","Jump","Zumba","Jiu Jitsu"],"tolerancia":5,"toleranciaAcesso":5},{"id":"*?*","nome":"plano planodo planador","ativo":"true","valorMatricula":30,"valor":100,"intervalo":"mensal","atividades":"","tolerancia":"5","toleranciaAcesso":"5"},{"id":"*?*","nome":"plano planodo planador","ativo":"true","valorMatricula":30,"valor":100,"intervalo":"mensal","atividades":["Musculação"],"tolerancia":"5","toleranciaAcesso":"5"},{"id":"*?*","nome":"plano planodo planador","ativo":"true","valorMatricula":30,"valor":100,"intervalo":"mensal","atividades":["Musculação","Jump"],"tolerancia":"5","toleranciaAcesso":"5"}],"total":8}';

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

//TESTE 15 Listagem de planos ativos de outra academia - RETORNAR ERRO
$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/listarPlano.php';

$test["nome"] = "Listagem de planos ativos de outra academia";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Usuário sem permissão para essa funcionalidade.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d5122620504810005',
                        'paginaAtual' => 1,
                        'numRegistros' => 20,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 16 Remover plano ativo da academia - RETORNAR SUCESSO
$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Remover plano ativo da academia";
$test["url"] = $url;
$test["retornoEsperado"] = '{"sucesso":true}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d51226205048b45a1',
                        'plano' => "57869e86f12b7b20346cfaa0",
                        'removido' => 1,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 17 Remover plano ativo de outra academia - RETORNAR ERRO
$url = AppConfig::getTestBaseURL().'/core/servicos/empresa/meuNegocio/salvarPlano.php';

$test["nome"] = "Remover plano ativo de outra academia";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Usuário sem permissão para essa funcionalidade.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'academia_id' => '56b3ca0d5122620504810005',
                        'plano' => "57869e86f12b7b20346cfaa0",
                        'removido' => 1,
                        //Auth
                        'publicToken' => $public_token,
                        'timestamp' => $timestamp_test,
                        'hash' => sha1($timestamp_test.$private_token),
                    );
$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

// Salvar Matrícula
    //TESTE 18 Salvar matrícula com tudo vazio - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula com tudo vazio";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: Data início<\/li><li>Campo não pode ser vazio: Desconto da matrícula<\/li><li>Campo não pode ser vazio: Desconto<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "",
                            'plano_id' => "",
                            'academia_id' => "",
                            'aluno_id' => "",
                            'descontoMatricula' => "",
                            'desconto' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 19 Salvar matrícula com campos obrigatórios preenchidos sem plano_id, academia_id e aluno_id - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula com campos obrigatórios preenchidos sem plano_id, academia_id e aluno_id";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Usuário sem permissão para essa funcionalidade.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "",
                            'academia_id' => "",
                            'aluno_id' => "",
                            'descontoMatricula' => "0.0",
                            'desconto' => "0.0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 20 Salvar matrícula com campos obrigatórios preenchidos sem plano_id e aluno_id - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula com campos obrigatórios preenchidos sem plano_id e aluno_id";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Parametros da requisição inválidos.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "",
                            'descontoMatricula' => "0.0",
                            'desconto' => "0.0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 21 Salvar matrícula com campos obrigatórios preenchidos sem aluno_id - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula com campos obrigatórios preenchidos sem aluno_id";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Parametros da requisição inválidos.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "5787efc0f12b7b048a7d3806",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "",
                            'descontoMatricula' => "0.0",
                            'desconto' => "0.0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 22 Salvar matrícula sem data de início - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula sem data de início";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: Data início<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "",
                            'plano_id' => "5787efc0f12b7b048a7d3806",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "0.0",
                            'desconto' => "0.0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 23 Salvar matrícula sem desconto de matricula - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula sem desconto de matricula";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: Desconto da matrícula<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "5787efc0f12b7b048a7d3806",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "",
                            'desconto' => "0.0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 24 Salvar matrícula sem desconto - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula sem desconto";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: Desconto<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "5787efc0f12b7b048a7d3806",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "0.0",
                            'desconto' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 25 Salvar matrícula com desconto de matricula menor que 0 - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula com desconto de matricula menor que 0";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ter valor negativo: Desconto da matrícula<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "5787efc0f12b7b048a7d3806",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "-1",
                            'desconto' => "0.0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 26 Salvar matrícula com desconto menor que 0 - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula com desconto menor que 0";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ter valor negativo: Desconto<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "5787efc0f12b7b048a7d3806",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "0.0",
                            'desconto' => "-1",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 27 Salvar matrícula com desconto de matricula maior que valor de matricula - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula com desconto de matricula maior que valor de matricula";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Valor para pagamento não pode ser negativo.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "5787efc0f12b7b048a7d3806",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "51.0",
                            'desconto' => "0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 28 Salvar matrícula com desconto maior que valor de mensalidade - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula com desconto maior que valor de mensalidade";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Valor para pagamento não pode ser negativo.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "5787efc0f12b7b048a7d3806",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "0.0",
                            'desconto' => "101",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 29 Salvar matrícula em plano inativo - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula em plano inativo";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Parametros da requisição inválidos.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "5788d470f12b7b04770bf2f7",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "0.0",
                            'desconto' => "0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 30 Salvar matrícula em plano removido - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula em plano removido";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Parametros da requisição inválidos.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "5788d92af12b7b047537e246",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "0.0",
                            'desconto' => "0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 31 Salvar matrícula em plano correto mas com outro academia_id que usuario tem direito - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula em plano correto mas com outro academia_id que usuario tem direito";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Parametros da requisição inválidos.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "5787efc0f12b7b048a7d3806",
                            'academia_id' => "56b3ca0d51226205048b45a2",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "0.0",
                            'desconto' => "0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 32 Salvar matrícula em plano correto mas com outro academia_id que usuario não tem direito - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matrícula em plano correto mas com outro academia_id que usuario não tem direito";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Usuário sem permissão para essa funcionalidade.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "5787efc0f12b7b048a7d3806",
                            'academia_id' => "56b3ca0d51226205048b45a3",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "0.0",
                            'desconto' => "0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 33 Salvar matricula em plano correto com tudo correto - RETORNAR SUCESSO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matricula em plano correto com tudo correto";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"sucesso":true,"dataInicio":"2016\/07\/14","proxPagamento":"14\/07\/2016","id":"*?*"}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "5787efc0f12b7b048a7d3806",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "0.0",
                            'desconto' => "0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 34 Salvar matricula em plano correto com tudo correto em plano ja matriculado - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matricula em plano correto com tudo correto em plano ja matriculado";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Não é possível matricular aluno duas vezes no mesmo plano.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'dataInicio' => "14/07/2016",
                            'plano_id' => "5787efc0f12b7b048a7d3806",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "0.0",
                            'desconto' => "0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 35 Salvar matricula em plano correto com tudo correto em plano ja desmatriculado - RETORNAR SUCESSO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matricula em plano correto com tudo correto em plano ja matriculado e desmatriculado";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"sucesso":true,"dataInicio":"2016\/07\/18","proxPagamento":"18\/07\/2016","id":"*?*"}';
    $test["postFields"] = array(
                            'dataInicio' => "18/07/2016",
                            'plano_id' => "578cc9b7f12b7b15e409a346",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "0.0",
                            'desconto' => "0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

// Encerrar Matrícula
    //TESTE 36 Encerrar matrícula sem nenhum dos id na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula sem nenhum dos id na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 37 Encerrar matrícula só com id e ainda por cima vazio na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula só com id e ainda por cima vazio na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            '_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 38 Encerrar matrícula só com academia_id e ainda por cima vazio na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula só com academia_id e ainda por cima vazio na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 39 Encerrar matrícula só com aluno_id e ainda por cima vazio na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula só com aluno_id e ainda por cima vazio na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'aluno_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 40 Encerrar matrícula só com id e academia_id e ainda por cima vazios na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula só com id e academia_id e ainda por cima vazios na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            '_id' => "",
                            'academia_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 41 Encerrar matrícula só com id e aluno_id e ainda por cima vazios na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula só com id e aluno_id e ainda por cima vazios na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            '_id' => "",
                            'aluno_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 42 Encerrar matrícula só com academia_id e aluno_id e ainda por cima vazios na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula só com academia_id e aluno_id e ainda por cima vazios na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "",
                            'aluno_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 43 Encerrar matrícula com todos os ids vazios na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula com todos os ids vazios na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Usuário sem permissão para essa funcionalidade.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            '_id' => "",
                            'academia_id' => "",
                            'aluno_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 44 Encerrar matrícula com apenas _id porem preenchido na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula com apenas _id porem preenchido inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            '_id' => "asdsadsadsdsad",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 45 Encerrar matrícula com apenas academia_id porem preenchido na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula com apenas academia_id porem preenchido inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 46 Encerrar matrícula com apenas aluno_id porem preenchido inexistente na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula com apenas aluno_id porem preenchido inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'aluno_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 47 Encerrar matrícula com apenas id e academia_id porem preenchido inexistente na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula com apenas id e academia_id porem preenchidos inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            '_id' => "asdasdasdasdsadasdsadsa",
                            'academia_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 48 Encerrar matrícula com apenas id e aluno_id porem preenchido inexistente na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula com apenas id e aluno_id porem preenchidos inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            '_id' => "asdasdasdasdsadasdsadsa",
                            'aluno_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 49 Encerrar matrícula com apenas academia_id e aluno_id porem preenchido inexistente na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula com apenas academia_id e aluno_id porem preenchidos inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "asdasdasdasdsadasdsadsa",
                            'aluno_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 50 Encerrar matrícula com todos preenchidos inexistente na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula com todos preenchidos inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Usuário sem permissão para essa funcionalidade.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            '_id' => "asdasdasdasdsadasdsadsa",
                            'academia_id' => "asdasdasdasdsadasdsadsa",
                            'aluno_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 51 Encerrar matrícula com apenas id e academia_id preenchidos mas que não casam - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula com apenas id e academia_id preenchidos mas que não casam";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            '_id' => "asdasdasdasdsadasdsadsa",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 52 Encerrar matrícula com apenas id e aluno_id preenchidos mas que não casam - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula com apenas id e aluno_id preenchidos mas que não casam";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            '_id' => "asdasdasdasdsadasdsadsa",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 53 Encerrar matrícula com apenas academia_id e aluno_id preenchidos mas que não casam - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula com apenas academia_id e aluno_id preenchidos mas que não casam";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45aa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 54 Encerrar matrícula com todos preenchidos mas que não casam - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula com todos preenchidos mas que não casam";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Falha E2. A nossa equipe foi avisada do problema. Tente novamente mais tarde.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            '_id' => "56b3ca0d51226205048b45a1",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45aa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 55 Encerrar matrícula com tudo certo - RETORNAR SUCESSO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula com tudo certo";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"sucesso":true,"ativo":"0","motivoInativo":"0","dataEncerramento":"*?*"}';
    $test["postFields"] = array(
                            '_id' => "578e1b23f12b7b1a6b142469",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 56 Encerrar matrícula já desmatriculada - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula já desmatriculada";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Falha E4. A nossa equipe foi avisada do problema. Tente novamente mais tarde.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            '_id' => "578e1b23f12b7b1a6b142469",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


    //TESTE 57 Encerrar matrícula passando id de outra academia - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/encerrarMatricula.php';

    $test["nome"] = "Encerrar matrícula passando id de outra academia";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Usuário sem permissão para essa funcionalidade.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            '_id' => "578e1b23f12b7b1a6b142469",
                            'academia_id' => "56b3ca0d51226205048b45a3",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 57.1 Salvar matricula já encerrada - RETORNAR SUCESSO
    $url = AppConfig::getTestBaseURL().'/core/servicos/aluno/salvarMatricula.php';

    $test["nome"] = "Salvar matricula já encerrada";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"sucesso":true,"dataInicio":"2016\/07\/18","dataEncerramento":"2016\/07\/18","usuarioAlteracao":"*?*","dataAlteracao":"*?*"}';
    $test["postFields"] = array(
                            '_id' => '578cce74f12b7b0475338c1b',
                            'dataInicio' => "18/07/2016",
                            'plano_id' => "578cc9b7f12b7b15e409a346",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'descontoMatricula' => "0.0",
                            'desconto' => "0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

// Listar Recebimento
    $url = AppConfig::getTestBaseURL().'/core/servicos/empresa/recebimento/listarRecebimento.php';
    //TESTE 1 Listar recebimento sem nenhum dos id na request - RETORNAR ERRO
    $test["nome"] = "Listar recebimento sem nenhum dos id na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 2 Listar recebimento só com academia_id e ainda por cima vazio na request - RETORNAR ERRO
    $test["nome"] = "Listar recebimento só com academia_id e ainda por cima vazio na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 3 Listar recebimento só com aluno_id e ainda por cima vazio na request - RETORNAR ERRO
    $test["nome"] = "Listar recebimento só com aluno_id e ainda por cima vazio na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'aluno_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 4 Listar recebimento só com academia_id e aluno_id e ainda por cima vazios na request - RETORNAR ERRO
    $test["nome"] = "Listar recebimento só com academia_id e aluno_id e ainda por cima vazios na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "",
                            'aluno_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 5 Listar recebimento com apenas academia_id porem preenchido na request - RETORNAR ERRO
    $test["nome"] = "Listar recebimento com apenas academia_id porem preenchido inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 6 Listar recebimento com apenas aluno_id porem preenchido inexistente na request - RETORNAR ERRO
    $test["nome"] = "Listar recebimento com apenas aluno_id porem preenchido inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'aluno_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 7 Listar recebimento com apenas academia_id e aluno_id porem preenchido inexistente na request - RETORNAR ERRO
    $test["nome"] = "Listar recebimento com apenas academia_id e aluno_id porem preenchidos inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "asdasdasdasdsadasdsadsa",
                            'aluno_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 8 Listar recebimento com apenas academia_id e aluno_id preenchidos mas que não casam - RETORNAR ERRO
    $test["nome"] = "Listar recebimento com apenas academia_id e aluno_id preenchidos mas que não casam";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45aa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 9 Listar recebimento sem página atual e sem número de registros na request - RETORNAR ERRO
    $test["nome"] = "Listar recebimento sem página atual e sem número de registros na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 10 Listar recebimento com apenas página atual e ainda por cima vazio na request - RETORNAR ERRO
    $test["nome"] = "Listar recebimento com apenas página atual e ainda por cima vazio na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'paginaAtual' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 11 Listar recebimento com apenas número de registros e ainda por cima vazio na request - RETORNAR ERRO
    $test["nome"] = "Listar recebimento com apenas número de registros e ainda por cima vazio na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'numRegistros' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 12 Listar recebimento com página atual e número de registros vazio - RETORNAR ERRO
    $test["nome"] = "Listar recebimento com página atual e número de registros vazio";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"sucesso":true,"itens":[{"id":"578e1b23f12b7b1a6b14246b","data":"19\/07\/2016","total":100,"referente":"1","matricula":"578e1b23f12b7b1a6b142469","forma":"1"},{"id":"578e1b23f12b7b1a6b14246a","data":"19\/07\/2016","total":99.99,"referente":"2","matricula":"578e1b23f12b7b1a6b142469","forma":"1"},{"id":"578cce74f12b7b0475338c1d","data":"18\/07\/2016","total":100,"referente":"1","matricula":"578cce74f12b7b0475338c1b","forma":"1"},{"id":"578cce74f12b7b0475338c1c","data":"18\/07\/2016","total":99.99,"referente":"2","matricula":"578cce74f12b7b0475338c1b","forma":"1"}],"total":4}';
    $test["postFields"] = array(
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'paginaAtual' => "",
                            'numRegistros' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 13 Listar recebimento com página atual igual a 0 - RETORNAR ERRO
    $test["nome"] = "Listar recebimento com página atual igual a 0";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Parametros de paginação inválidos.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'paginaAtual' => "0",
                            'numRegistros' => "20",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 14 Listar recebimento com número de registros igual a 0 - RETORNAR ERRO
    $test["nome"] = "Listar recebimento com número de registros igual a 0";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Parametros de paginação inválidos.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'paginaAtual' => "1",
                            'numRegistros' => "0",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 15 Listar recebimento com sucesso - RETORNAR SUCESSO
    $test["nome"] = "Listar recebimento com sucesso";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"sucesso":true,"itens":[{"id":"578e1b23f12b7b1a6b14246b","data":"19\/07\/2016","total":100,"referente":"1","matricula":"578e1b23f12b7b1a6b142469","forma":"1"},{"id":"578e1b23f12b7b1a6b14246a","data":"19\/07\/2016","total":99.99,"referente":"2","matricula":"578e1b23f12b7b1a6b142469","forma":"1"},{"id":"578cce74f12b7b0475338c1d","data":"18\/07\/2016","total":100,"referente":"1","matricula":"578cce74f12b7b0475338c1b","forma":"1"},{"id":"578cce74f12b7b0475338c1c","data":"18\/07\/2016","total":99.99,"referente":"2","matricula":"578cce74f12b7b0475338c1b","forma":"1"}],"total":4}';
    $test["postFields"] = array(
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'paginaAtual' => "1",
                            'numRegistros' => "20",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 16 Listar recebimento de outra academia - RETORNAR ERRO
    $test["nome"] = "Listar recebimento de outra academia";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Usuário sem permissão para essa funcionalidade.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'academia_id' => "56b3ca0d51226205048b45a3",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            'paginaAtual' => "1",
                            'numRegistros' => "20",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

// Load Recebimento
    //TESTE 1 Load recebimento sem nenhum dos id na request - RETORNAR ERRO
    $url = AppConfig::getTestBaseURL().'/core/servicos/empresa/recebimento/loadRecebimento.php';

    $test["nome"] = "Load recebimento sem nenhum dos id na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 2 Load Recebimento só com id e ainda por cima vazio na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento só com id e ainda por cima vazio na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 3 Load recebimento só com academia_id e ainda por cima vazio na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento só com academia_id e ainda por cima vazio na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 4 Load recebimento só com aluno_id e ainda por cima vazio na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento só com aluno_id e ainda por cima vazio na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'aluno_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 5 Load recebimento só com id e academia_id e ainda por cima vazios na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento só com id e academia_id e ainda por cima vazios na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'id' => "",
                            'academia_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 6 Load recebimento só com id e aluno_id e ainda por cima vazios na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento só com id e aluno_id e ainda por cima vazios na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'id' => "",
                            'aluno_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 7 Load recebimento só com academia_id e aluno_id e ainda por cima vazios na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento só com academia_id e aluno_id e ainda por cima vazios na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "",
                            'aluno_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 8 Load recebimento com todos os ids vazios na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento com todos os ids vazios na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Usuário sem permissão para essa funcionalidade.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'id' => "",
                            'academia_id' => "",
                            'aluno_id' => "",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 9 Load recebimento com apenas _id porem preenchido na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento com apenas _id porem preenchido inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'id' => "asdsadsadsdsad",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 10 Load recebimento com apenas academia_id porem preenchido na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento com apenas academia_id porem preenchido inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 11 Load recebimento com apenas aluno_id porem preenchido inexistente na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento com apenas aluno_id porem preenchido inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'aluno_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 12 Load recebimento com apenas id e academia_id porem preenchido inexistente na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento com apenas id e academia_id porem preenchidos inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'id' => "asdasdasdasdsadasdsadsa",
                            'academia_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 13 Load recebimento com apenas id e aluno_id porem preenchido inexistente na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento com apenas id e aluno_id porem preenchidos inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'id' => "asdasdasdasdsadasdsadsa",
                            'aluno_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 14 Load recebimento com apenas academia_id e aluno_id porem preenchido inexistente na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento com apenas academia_id e aluno_id porem preenchidos inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "asdasdasdasdsadasdsadsa",
                            'aluno_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 15 Load recebimento com todos preenchidos inexistente na request - RETORNAR ERRO
    $test["nome"] = "Load recebimento com todos preenchidos inexistente na request";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Usuário sem permissão para essa funcionalidade.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'id' => "asdasdasdasdsadasdsadsa",
                            'academia_id' => "asdasdasdasdsadasdsadsa",
                            'aluno_id' => "asdasdasdasdsadasdsadsa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 16 Load recebimento com apenas id e academia_id preenchidos mas que não casam - RETORNAR ERRO
    $test["nome"] = "Load recebimento com apenas id e academia_id preenchidos mas que não casam";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'id' => "asdasdasdasdsadasdsadsa",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 17 Load recebimento com apenas id e aluno_id preenchidos mas que não casam - RETORNAR ERRO
    $test["nome"] = "Load recebimento com apenas id e aluno_id preenchidos mas que não casam";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'id' => "asdasdasdasdsadasdsadsa",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 18 Load recebimento com apenas academia_id e aluno_id preenchidos mas que não casam - RETORNAR ERRO
    $test["nome"] = "Load recebimento com apenas academia_id e aluno_id preenchidos mas que não casam";
    $test["url"] = $url;
    $test["retornoEsperado"] = '';
    $test["postFields"] = array(
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45aa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 19 Load recebimento com todos preenchidos mas que não casam - RETORNAR ERRO
    $test["nome"] = "Load recebimento com todos preenchidos mas que não casam";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"sucesso":false}';
    $test["postFields"] = array(
                            'id' => "56b3ca0d51226205048b45a1",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45aa",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 20 Load recebimento com tudo certo - RETORNAR SUCESSO
    $test["nome"] = "Load recebimento com tudo certo";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"sucesso":true,"pagamento":{"data":"2016\/07\/18","referente":"2","matricula_id":"578cce74f12b7b0475338c1b","plano_id":"578cc9b7f12b7b15e409a346","forma":"1","valor":99.99,"desconto":0,"observacoes":null}}';
    $test["postFields"] = array(
                            'id' => "578cce74f12b7b0475338c1c",
                            'academia_id' => "56b3ca0d51226205048b45a1",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

    //TESTE 21 Load recebimento de outra academia - RETORNAR SUCESSO
    $test["nome"] = "Load recebimento de outra academia";
    $test["url"] = $url;
    $test["retornoEsperado"] = '{"erro":"<ul><li>Usuário sem permissão para essa funcionalidade.<\/li><\/ul>","sucesso":false}';
    $test["postFields"] = array(
                            'id' => "578cce74f12b7b0475338c1c",
                            'academia_id' => "56b3ca0d51226205048b45a3",
                            'aluno_id' => "56b3ca0d51226205048b45a5",
                            //Auth
                            'publicToken' => $public_token,
                            'timestamp' => $timestamp_test,
                            'hash' => sha1($timestamp_test.$private_token),
                        );
    $result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);