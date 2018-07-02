<?php

use core\AppConfig;
use core\util\RequestPHPTest;

//PARAMETROS GERAIS E INICIALIZAÇÃO
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$url = AppConfig::getTestBaseURL()."/core/servicos/autenticacao/loginAluno.php";

$test = array();


/*
1. Login em branco e senha em branco
2. Login inválido e senha em branco
3. Login existente e senha em branco
4. Login inexistente e senha em branco
5. Login enorme (+255) e senha em branco
6. Login com espaço no final e senha em branco

7. Login em branco e senha curta sem letra e sem número
8. Login em branco e senha curta sem letra
9. Login em branco e senha curta sem número
10. Login em branco e senha curta
11. Login em branco e senha sem letra e sem número
12. Login em branco e senha sem letra
13. Login em branco e senha sem número
14. Login em branco e senha válida (formato válido)
15. Login em branco e senha enorme (+255)
16. Login em branco e senha com espaço no final (formato válido)

17. Login inválido e senha curta sem letra e sem número
18. Login inválido e senha curta sem letra
19. Login inválido e senha curta sem número
20. Login inválido e senha curta
21. Login inválido e senha sem letra e sem número
22. Login inválido e senha sem letra
23. Login inválido e senha sem número
24. Login inválido e senha válida (formato válido)
25. Login inválido e senha enorme (+255)
26. Login inválido e senha com espaço no final (formato válido)

27. Login existente e senha curta sem letra e sem número
28. Login existente e senha curta sem letra
29. Login existente e senha curta sem número
30. Login existente e senha curta
31. Login existente e senha sem letra e sem número
32. Login existente e senha sem letra
33. Login existente e senha sem número
34. Login existente e senha válida (formato válido) mas não correta pra logar
35. Login existente e senha enorme (+255)
36. Login existente e senha com espaço no final (formato válido)

37. Login inexistente e senha curta sem letra e sem número
38. Login inexistente e senha curta sem letra
39. Login inexistente e senha curta sem número
40. Login inexistente e senha curta
41. Login inexistente e senha sem letra e sem número
42. Login inexistente e senha sem letra
43. Login inexistente e senha sem número
44. Login inexistente e senha válida (formato válido)
45. Login inexistente e senha enorme (+255)
46. Login inexistente e senha com espaço no final (formato válido)

47. Login enorme (+255) e senha curta sem letra e sem número
48. Login enorme (+255) e senha curta sem letra
49. Login enorme (+255) e senha curta sem número
50. Login enorme (+255) e senha curta
51. Login enorme (+255) e senha sem letra e sem número
52. Login enorme (+255) e senha sem letra
53. Login enorme (+255) e senha sem número
54. Login enorme (+255) e senha válida (formato válido)
55. Login enorme (+255) e senha enorme (+255)
56. Login enorme (+255) e senha com espaço no final (formato válido)

57. Login com espaço no final e senha curta sem letra e sem número
58. Login com espaço no final e senha curta sem letra
59. Login com espaço no final e senha curta sem número
60. Login com espaço no final e senha curta
61. Login com espaço no final e senha sem letra e sem número
62. Login com espaço no final e senha sem letra
63. Login com espaço no final e senha sem número
64. Login com espaço no final e senha válida (formato válido)
65. Login com espaço no final e senha enorme (+255)
66. Login com espaço no final e senha com espaço no final (formato válido)

67. Credenciais corretas mas que precisa de validação de conta
68. Credenciais corretas mas não é aluno
69. Credenciais corretas (login de fato)
*/

//TESTE 1
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login em branco e senha em branco";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: E-mail<\/li><li>Campo não pode ser vazio: Senha<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '',
                        'senha' => '',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 2
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inválido e senha em branco";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><li>Campo não pode ser vazio: Senha<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '1asd1aasd.com',
                        'senha' => '',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 3
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login existente e senha em branco";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: Senha<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com',
                        'senha' => '',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 4
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inexistente e senha em branco";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: Senha<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto2@gmail.com',
                        'senha' => '',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 5
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login enorme (+255) e senha em branco";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><li>Campo não pode ser vazio: Senha<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'upllmIVnjyvGklxtxKLGafkApgPyRhogUzznwfDSqIHZWtMXHfKUTVcVRlErYmrrcMAsXcaCmysuNNjwSAPWdbFyloQpwgIeRaHxcFtLHexBJOgfcGJdnTDSiGxthTUqSBtXgnHibcmVTCxlKtPyZfBFLuSBZwOjOwFnpmXJOECMwVXkfNEStiBZzNnNNPOxQDenmheYIaujpFSfMOnbjcEapDvzfpEhjIiosUoUvtLNwKGtbMCgdsEKOcxdUnmu@gmail.com',
                        'senha' => '',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 6
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login com espaço no final e senha em branco";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: Senha<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com ',
                        'senha' => '',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 7
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login em branco e senha curta sem letra e sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: E-mail<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '',
                        'senha' => '!!!!!!!',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 8
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login em branco e senha curta sem letra";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: E-mail<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '',
                        'senha' => '1234567',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 9
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login em branco e senha curta sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: E-mail<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '',
                        'senha' => 'aaaaaaa',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 10
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login em branco e senha curta";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: E-mail<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '',
                        'senha' => '123456a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 11
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login em branco e senha sem letra e sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: E-mail<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '',
                        'senha' => '!!!!!!!!',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 12
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login em branco e senha sem letra";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: E-mail<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '',
                        'senha' => '12345678',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 13
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login em branco e senha sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: E-mail<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '',
                        'senha' => 'aaaaaaaa',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 14
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login em branco e senha válida (formato válido)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: E-mail<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '',
                        'senha' => '1234567a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 15
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login em branco e senha enorme (+255)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: E-mail<\/li><li>Número máximo de caracteres excedido: Senha<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '',
                        'senha' => '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 16
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login em branco e senha com espaço no final (formato válido)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Campo não pode ser vazio: E-mail<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '',
                        'senha' => '1234567a ',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 17
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inválido e senha curta sem letra e sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '1asd1aasd.com',
                        'senha' => '!!!!!!!',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 18
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inválido e senha curta sem letra";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '1asd1aasd.com',
                        'senha' => '1234567',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 19
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inválido e senha curta sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '1asd1aasd.com',
                        'senha' => 'aaaaaaa',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 20
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inválido e senha curta";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '1asd1aasd.com',
                        'senha' => '123456a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 21
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inválido e senha sem letra e sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '1asd1aasd.com',
                        'senha' => '!!!!!!!!',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 22
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inválido e senha sem letra";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '1asd1aasd.com',
                        'senha' => '12345678',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 23
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inválido e senha sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '1asd1aasd.com',
                        'senha' => 'aaaaaaaa',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 24
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inválido e senha válida (formato válido)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '1asd1aasd.com',
                        'senha' => '1234567a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 25
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inválido e senha enorme (+255)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><li>Número máximo de caracteres excedido: Senha<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '1asd1aasd.com',
                        'senha' => '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 26
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inválido e senha com espaço no final (formato válido)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => '1asd1aasd.com',
                        'senha' => '1234567a ',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 27
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login existente e senha curta sem letra e sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com',
                        'senha' => '!!!!!!!',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 28
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login existente e senha curta sem letra";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com',
                        'senha' => '1234567',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 29
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login existente e senha curta sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com',
                        'senha' => 'aaaaaaa',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 30
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login existente e senha curta";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com',
                        'senha' => '123456a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 31
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login existente e senha sem letra e sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com',
                        'senha' => '!!!!!!!!',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 32
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login existente e senha sem letra";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com',
                        'senha' => '12345678',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 33
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login existente e senha sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com',
                        'senha' => 'aaaaaaaa',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 34
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login existente e senha válida (formato válido) mas não correta pra logar";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com',
                        'senha' => '1234567b',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 35
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login existente e senha enorme (+255)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Número máximo de caracteres excedido: Senha<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com',
                        'senha' => '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 36
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login existente e senha com espaço no final (formato válido)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com',
                        'senha' => '1234567b ',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 37
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inexistente e senha curta sem letra e sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto2@gmail.com',
                        'senha' => '!!!!!!!',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 38
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inexistente e senha curta sem letra";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto2@gmail.com',
                        'senha' => '1234567',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 39
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inexistente e senha curta sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto2@gmail.com',
                        'senha' => 'aaaaaaa',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 40
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inexistente e senha curta";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto2@gmail.com',
                        'senha' => '123456a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 41
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inexistente e senha sem letra e sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto2@gmail.com',
                        'senha' => '!!!!!!!!',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 42
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inexistente e senha sem letra";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto2@gmail.com',
                        'senha' => '12345678',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 43
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inexistente e senha sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto2@gmail.com',
                        'senha' => 'aaaaaaaa',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 44
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inexistente e senha válida (formato válido) mas não correta pra logar";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto2@gmail.com',
                        'senha' => '1234567b',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 45
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inexistente e senha enorme (+255)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Número máximo de caracteres excedido: Senha<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto2@gmail.com',
                        'senha' => '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 46
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login inexistente e senha com espaço no final (formato válido)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto2@gmail.com',
                        'senha' => '1234567b ',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 47
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login enorme (+255) e senha curta sem letra e sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'upllmIVnjyvGklxtxKLGafkApgPyRhogUzznwfDSqIHZWtMXHfKUTVcVRlErYmrrcMAsXcaCmysuNNjwSAPWdbFyloQpwgIeRaHxcFtLHexBJOgfcGJdnTDSiGxthTUqSBtXgnHibcmVTCxlKtPyZfBFLuSBZwOjOwFnpmXJOECMwVXkfNEStiBZzNnNNPOxQDenmheYIaujpFSfMOnbjcEapDvzfpEhjIiosUoUvtLNwKGtbMCgdsEKOcxdUnmu@gmail.com',
                        'senha' => '!!!!!!!',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 48
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login enorme (+255) e senha curta sem letra";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'upllmIVnjyvGklxtxKLGafkApgPyRhogUzznwfDSqIHZWtMXHfKUTVcVRlErYmrrcMAsXcaCmysuNNjwSAPWdbFyloQpwgIeRaHxcFtLHexBJOgfcGJdnTDSiGxthTUqSBtXgnHibcmVTCxlKtPyZfBFLuSBZwOjOwFnpmXJOECMwVXkfNEStiBZzNnNNPOxQDenmheYIaujpFSfMOnbjcEapDvzfpEhjIiosUoUvtLNwKGtbMCgdsEKOcxdUnmu@gmail.com',
                        'senha' => '1234567',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 49
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login enorme (+255) e senha curta sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'upllmIVnjyvGklxtxKLGafkApgPyRhogUzznwfDSqIHZWtMXHfKUTVcVRlErYmrrcMAsXcaCmysuNNjwSAPWdbFyloQpwgIeRaHxcFtLHexBJOgfcGJdnTDSiGxthTUqSBtXgnHibcmVTCxlKtPyZfBFLuSBZwOjOwFnpmXJOECMwVXkfNEStiBZzNnNNPOxQDenmheYIaujpFSfMOnbjcEapDvzfpEhjIiosUoUvtLNwKGtbMCgdsEKOcxdUnmu@gmail.com',
                        'senha' => 'aaaaaaa',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 50
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login enorme (+255) e senha curta";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'upllmIVnjyvGklxtxKLGafkApgPyRhogUzznwfDSqIHZWtMXHfKUTVcVRlErYmrrcMAsXcaCmysuNNjwSAPWdbFyloQpwgIeRaHxcFtLHexBJOgfcGJdnTDSiGxthTUqSBtXgnHibcmVTCxlKtPyZfBFLuSBZwOjOwFnpmXJOECMwVXkfNEStiBZzNnNNPOxQDenmheYIaujpFSfMOnbjcEapDvzfpEhjIiosUoUvtLNwKGtbMCgdsEKOcxdUnmu@gmail.com',
                        'senha' => '123456a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 51
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login enorme (+255) e senha sem letra e sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'upllmIVnjyvGklxtxKLGafkApgPyRhogUzznwfDSqIHZWtMXHfKUTVcVRlErYmrrcMAsXcaCmysuNNjwSAPWdbFyloQpwgIeRaHxcFtLHexBJOgfcGJdnTDSiGxthTUqSBtXgnHibcmVTCxlKtPyZfBFLuSBZwOjOwFnpmXJOECMwVXkfNEStiBZzNnNNPOxQDenmheYIaujpFSfMOnbjcEapDvzfpEhjIiosUoUvtLNwKGtbMCgdsEKOcxdUnmu@gmail.com',
                        'senha' => '!!!!!!!!',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 52
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login enorme (+255) e senha sem letra";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'upllmIVnjyvGklxtxKLGafkApgPyRhogUzznwfDSqIHZWtMXHfKUTVcVRlErYmrrcMAsXcaCmysuNNjwSAPWdbFyloQpwgIeRaHxcFtLHexBJOgfcGJdnTDSiGxthTUqSBtXgnHibcmVTCxlKtPyZfBFLuSBZwOjOwFnpmXJOECMwVXkfNEStiBZzNnNNPOxQDenmheYIaujpFSfMOnbjcEapDvzfpEhjIiosUoUvtLNwKGtbMCgdsEKOcxdUnmu@gmail.com',
                        'senha' => '12345678',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 53
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login enorme (+255) e senha sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'upllmIVnjyvGklxtxKLGafkApgPyRhogUzznwfDSqIHZWtMXHfKUTVcVRlErYmrrcMAsXcaCmysuNNjwSAPWdbFyloQpwgIeRaHxcFtLHexBJOgfcGJdnTDSiGxthTUqSBtXgnHibcmVTCxlKtPyZfBFLuSBZwOjOwFnpmXJOECMwVXkfNEStiBZzNnNNPOxQDenmheYIaujpFSfMOnbjcEapDvzfpEhjIiosUoUvtLNwKGtbMCgdsEKOcxdUnmu@gmail.com',
                        'senha' => 'aaaaaaaa',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 54
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login enorme (+255) e senha válida (formato válido) mas não correta pra logar";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'upllmIVnjyvGklxtxKLGafkApgPyRhogUzznwfDSqIHZWtMXHfKUTVcVRlErYmrrcMAsXcaCmysuNNjwSAPWdbFyloQpwgIeRaHxcFtLHexBJOgfcGJdnTDSiGxthTUqSBtXgnHibcmVTCxlKtPyZfBFLuSBZwOjOwFnpmXJOECMwVXkfNEStiBZzNnNNPOxQDenmheYIaujpFSfMOnbjcEapDvzfpEhjIiosUoUvtLNwKGtbMCgdsEKOcxdUnmu@gmail.com',
                        'senha' => '1234567b',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 55
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login enorme (+255) e senha enorme (+255)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><li>Número máximo de caracteres excedido: Senha<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'upllmIVnjyvGklxtxKLGafkApgPyRhogUzznwfDSqIHZWtMXHfKUTVcVRlErYmrrcMAsXcaCmysuNNjwSAPWdbFyloQpwgIeRaHxcFtLHexBJOgfcGJdnTDSiGxthTUqSBtXgnHibcmVTCxlKtPyZfBFLuSBZwOjOwFnpmXJOECMwVXkfNEStiBZzNnNNPOxQDenmheYIaujpFSfMOnbjcEapDvzfpEhjIiosUoUvtLNwKGtbMCgdsEKOcxdUnmu@gmail.com',
                        'senha' => '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 56
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login enorme (+255) e senha com espaço no final (formato válido)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>E-mail inválido.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'upllmIVnjyvGklxtxKLGafkApgPyRhogUzznwfDSqIHZWtMXHfKUTVcVRlErYmrrcMAsXcaCmysuNNjwSAPWdbFyloQpwgIeRaHxcFtLHexBJOgfcGJdnTDSiGxthTUqSBtXgnHibcmVTCxlKtPyZfBFLuSBZwOjOwFnpmXJOECMwVXkfNEStiBZzNnNNPOxQDenmheYIaujpFSfMOnbjcEapDvzfpEhjIiosUoUvtLNwKGtbMCgdsEKOcxdUnmu@gmail.com',
                        'senha' => '1234567b ',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);

//TESTE 57
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login com espaço no final e senha curta sem letra e sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com ',
                        'senha' => '!!!!!!!',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 58
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login com espaço no final e senha curta sem letra";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com ',
                        'senha' => '1234567',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 59
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login com espaço no final e senha curta sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com ',
                        'senha' => 'aaaaaaa',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 60
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login com espaço no final e senha curta";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com ',
                        'senha' => '123456a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 61
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login com espaço no final e senha sem letra e sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com ',
                        'senha' => '!!!!!!!!',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 62
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login com espaço no final e senha sem letra";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com ',
                        'senha' => '12345678',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 63
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login com espaço no final e senha sem número";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com ',
                        'senha' => 'aaaaaaaa',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 64
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login com espaço no final e senha válida (formato válido) mas não correta pra logar";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com ',
                        'senha' => '1234567b',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 65
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login com espaço no final e senha enorme (+255)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Número máximo de caracteres excedido: Senha<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com ',
                        'senha' => '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 66
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login com espaço no final e senha com espaço no final (formato válido)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Login e senha incorretos.<\/li><\/ul>","sucesso":false}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com ',
                        'senha' => '1234567b ',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 67
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Credenciais corretas mas que precisa de validação de conta";
$test["url"] = $url;
$test["retornoEsperado"] = '{"msg":"<ul><li>A sua conta ainda necessita de validação. Verifique se recebeu o e-mail com o código de ativação.<\/li><\/ul>","sucesso":true,"v":1}';
$test["postFields"] = array(
                        'login' => 'joaquimjose@email.com.br',
                        'senha' => '1234567a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 68
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Credenciais corretas mas não é aluno";
$test["url"] = $url;
$test["retornoEsperado"] = '{"countNav":8,"nome":"João Marcos","sobrenome":"Mareto Calado","email":"joaomarcosmareto@gmail.com","sexo":"M","nascimento":"14\/01\/1988","idade":29,"foto90x79":null,"foto36x36":null,"telefone":null,"celular":null,"facebook":null,"cep":null,"rua":null,"numero":null,"bairro":null,"cidade":null,"estado":null,"auth":{"privateToken":"*?*","publicToken":"*?*","refreshToken":"*?*"},"sucesso":true}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com ',
                        'senha' => '1234567a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 69
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Credenciais corretas de aluno (login de fato)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"countNav":8,"nome":"Teste","sobrenome":"Não Usar","email":"5@5.com","sexo":"M","nascimento":"21\/02\/1988","idade":29,"foto90x79":null,"foto36x36":null,"telefone":null,"celular":null,"facebook":null,"cep":null,"rua":null,"numero":null,"bairro":null,"cidade":null,"estado":null,"academias":[{"aluno_id":"56b3ca0d51226205048b45a6","matricula":"3","id":"56b3ca0d51226205048b45a1","nome":"O2 Respirando Qualidade de Vida","descricao":"Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto","foto1":null,"foto2":null,"foto3":null,"foto4":null,"horarioFuncionamento":"De 2ª a 6ª de 6h às 22h. Sábado de 6h às 12h.","logo":null,"rua":"Manoel Joaquim dos Santos","numero":"20","complemento":"Em cima da Padaria Monza","bairro":"Itacibá","cidade":"Cariacica","estado":"ES","telefone1":null,"telefone2":"2799999999","email":null,"siteLink":"https:\/\/www.google.com.br\/maps\/@-20.3226672,-40.3764407,3a,75y,44.27h,102.17t\/data=!3m4!1e1!3m2!1sp4F25fVq7HhOVdB2_OSUSA!2e0","socialLink":"https:\/\/www.facebook.com\/respirando.devida","latitude":"-20.3226672","longitude":"-40.3764407"},{"aluno_id":"56b3ca0d51226205048b45aa","matricula":"4","id":"56b3ca0d51226205048b45a2","nome":"Plena Forma","descricao":"Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto Texto ","foto1":null,"foto2":null,"foto3":null,"foto4":null,"horarioFuncionamento":null,"logo":null,"rua":"Rua da Casa de Ronald","numero":"99","complemento":null,"bairro":"Tucum","cidade":"Cariacica","estado":"ES","telefone1":"(27)3336-3333","telefone2":"(27)99999-9999","email":null,"siteLink":"http:\/\/plenaformaacademia.blogspot.com.br\/","socialLink":null,"latitude":null,"longitude":null}],"fichas":[{"id":"56b3cae5512262d5288b473d","dataInicio":"05\/02\/2016","dataFim":"05\/03\/2016","professor":{"nome":"Rodrigo","sobrenome":"Carecão Barbudo"},"academia":{"nome":"O2 Respirando Qualidade de Vida"},"exercicios":[[{"_id":"56b3cb7951226207048b4581","exercicio_id":"56b3ca0d51226205048b4697","nome":"Crucifixo Declinado com Halteres","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/10.jpg","totalSeries":3,"repeticoes":"10","descanso":"60","series":[{"repeticoes":["10"],"carga":[""],"descanso":"60"},{"repeticoes":["10"],"carga":[""],"descanso":"60"},{"repeticoes":["10"],"carga":[""],"descanso":"60"}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4582","exercicio_id":"56b3ca0d51226205048b469b","nome":"Crucifixo em Máquina","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/13.jpg","totalSeries":3,"descanso":"30","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":"30"},{"repeticoes":[" ? "],"carga":[""],"descanso":"30"},{"repeticoes":[" ? "],"carga":[""],"descanso":"30"}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4583","exercicio_id":"56b3ca0d51226205048b4699","nome":"Crucifixo em pé em Polia Baixa","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/12.jpg","totalSeries":4,"descanso":"60","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4584","exercicio_id":"56b3ca0d51226205048b469e","nome":"Rosca Concentrada","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/16.jpg","totalSeries":4,"descanso":"60","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4585","exercicio_id":"56b3ca0d51226205048b46a0","nome":"Rosca Scoot com Halter","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/18.jpg","totalSeries":4,"descanso":"60","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4586","exercicio_id":"56b3ca0d51226205048b469c","nome":"Rosca com Barra","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/14.jpg","totalSeries":4,"descanso":"60","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4587","exercicio_id":"56b3ca0d51226205048b46e0","nome":"Abdominal em Roda","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/81.jpg","totalSeries":4,"descanso":"60","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"},{"repeticoes":[" ? "],"carga":[""],"descanso":"60"}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]}],[{"_id":"56b3cb7951226207048b4588","exercicio_id":"56b3ca0d51226205048b46b7","nome":"Remada com Barra","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/41.jpg","totalSeries":1,"repeticoes":"10","carga":"55","descanso":"60","series":[{"repeticoes":["10"],"carga":[55],"descanso":"60"}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b4589","exercicio_id":"56b3ca0d51226205048b46ba","nome":"Remada em Polia Baixa","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/44.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b458a","exercicio_id":"56b3ca0d51226205048b46b8","nome":"Remada com Barra em Supinação","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/42.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b458b","exercicio_id":"56b3ca0d51226205048b46ad","nome":"Puxada de Tríceps com Corda","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/31.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b458c","exercicio_id":"56b3ca0d51226205048b46ab","nome":"Puxada de Tríceps","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/29.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b458d","exercicio_id":"56b3ca0d51226205048b46ac","nome":"Puxada de Tríceps em Supinação","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/30.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b458e","exercicio_id":"56b3ca0d51226205048b46da","nome":"Abdominal com Flexão de Quadril","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/75.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cb7951226207048b458f","exercicio_id":"56b3ca0d51226205048b46df","nome":"Prancha Lateral","aerobico":true,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/80.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]}]],"letrasTreinos":"A B "},{"id":"56b3cc23512262dd288b4581","dataInicio":"04\/02\/2016","dataFim":"04\/03\/2016","professor":{"nome":"Rodrigo","sobrenome":"Carecão Barbudo"},"academia":{"nome":"O2 Respirando Qualidade de Vida"},"exercicios":[[{"_id":"56b3cc23512262dd288b4582","exercicio_id":"56b3ca0d51226205048b46b1","nome":"Rosca Martelo","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/35.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":1,"observacoes":[{"id":"56b3ca0e51226205048b4729","tag":"Barra com estribo pondal","descricao":null}]},{"_id":"56b3cc23512262dd288b4583","exercicio_id":"56b3ca0d51226205048b46b0","nome":"Rosca com Barra em Pronação","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/34.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":1,"observacoes":[{"id":"56b3ca0e51226205048b471c","tag":"FST 7","descricao":"Este método consiste em realizar 7 séries de 8 a 12 repetições com apenas 30 segundos de descanso entre as séries. É aconselhado nos descansos alongar o músculo trabalhado."}]},{"_id":"56b3cc23512262dd288b4584","exercicio_id":"56b3ca0d51226205048b46ee","nome":"Levantamento Terra Sumô","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/95.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":1,"observacoes":[{"id":"56b3ca0e51226205048b4732","tag":"Corda","descricao":null}]},{"_id":"56b3cc23512262dd288b4585","exercicio_id":"56b3ca0d51226205048b46a1","nome":"Rosca Scoot em Máquina","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/19.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":1,"observacoes":[]},{"_id":"56b3cc23512262dd288b4586","exercicio_id":"56b3ca0d51226205048b46a3","nome":"Rosca em Polia Baixa","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/21.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cc23512262dd288b4587","exercicio_id":"56b3ca0d51226205048b46dd","nome":"Rotação do Tronco com Bastão","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/78.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cc23512262dd288b4588","exercicio_id":"56b3ca0d51226205048b46d7","nome":"Flexão de Quadril em Banco","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/72.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cc23512262dd288b4589","exercicio_id":"56b3ca0d51226205048b46dc","nome":"Abdominal em Polia Alta","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/77.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cc23512262dd288b458a","exercicio_id":"56b3ca0d51226205048b46d8","nome":"Flexão de Quadril em Banco Inclinado","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/73.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]}],[{"_id":"56b3cc23512262dd288b458b","exercicio_id":"56b3ca0d51226205048b46ea","nome":"Panturrilha em Máquina","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/91.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":1,"observacoes":[]},{"_id":"56b3cc23512262dd288b458c","exercicio_id":"56b3ca0d51226205048b46eb","nome":"Panturrilha Sentado","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/92.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":1,"observacoes":[]},{"_id":"56b3cc23512262dd288b458d","exercicio_id":"56b3ca0d51226205048b46e8","nome":"Panturrilha em Pé","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/89.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":2,"observacoes":[]},{"_id":"56b3cc23512262dd288b458e","exercicio_id":"56b3ca0d51226205048b469e","nome":"Rosca Concentrada","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/16.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":2,"observacoes":[]},{"_id":"56b3cc23512262dd288b458f","exercicio_id":"56b3ca0d51226205048b469c","nome":"Rosca com Barra","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/14.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cc23512262dd288b4590","exercicio_id":"56b3ca0d51226205048b46a4","nome":"Rosca Bíceps em Polia Alta","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/22.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cc23512262dd288b4591","exercicio_id":"56b3ca0d51226205048b46e7","nome":"Extensão do Quadril com Pés Apoiados no Banco","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/88.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cc23512262dd288b4592","exercicio_id":"56b3ca0d51226205048b46e2","nome":"Step Up","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/83.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]}],[{"_id":"56b3cc23512262dd288b4593","exercicio_id":"56b3ca0d51226205048b4694","nome":"Supino Declinado com Halteres","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/7.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":1,"observacoes":[]},{"_id":"56b3cc23512262dd288b4594","exercicio_id":"56b3ca0d51226205048b4693","nome":"Supino Inclinado com Halteres","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/6.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":1,"observacoes":[]},{"_id":"56b3cc23512262dd288b4595","exercicio_id":"56b3ca0d51226205048b4699","nome":"Crucifixo em pé em Polia Baixa","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/12.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":1,"observacoes":[]},{"_id":"56b3cc23512262dd288b4596","exercicio_id":"56b3ca0d51226205048b468f","nome":"Supino Inclinado com Barra","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/2.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":2,"observacoes":[]},{"_id":"56b3cc23512262dd288b4597","exercicio_id":"56b3ca0d51226205048b46a8","nome":"Extensão de Tríceps Deitado com Barra","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/26.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":2,"observacoes":[]},{"_id":"56b3cc23512262dd288b4598","exercicio_id":"56b3ca0d51226205048b46a7","nome":"Supino em Pega Junta","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/25.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]},{"_id":"56b3cc23512262dd288b4599","exercicio_id":"56b3ca0d51226205048b46af","nome":"Kickback com Polia","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/33.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","observacoes":[]}],[{"_id":"56b3cc23512262dd288b459a","exercicio_id":"56b3ca0d51226205048b46ca","nome":"Esteira","aerobico":true,"gif":null,"series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":1,"observacoes":[]},{"_id":"56b3cc23512262dd288b459b","exercicio_id":"56b3ca0d51226205048b46cc","nome":"Elevação Frontal com Barra","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/61.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":1,"observacoes":[]},{"_id":"56b3cc23512262dd288b459c","exercicio_id":"56b3ca0d51226205048b46d3","nome":"Voos com Halteres com Cabeça Apoiada","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/68.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":2,"observacoes":[]},{"_id":"56b3cc23512262dd288b459d","exercicio_id":"56b3ca0d51226205048b46fb","nome":"Bom Dia a Pernas Retas","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/109.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":2,"observacoes":[]},{"_id":"56b3cc23512262dd288b459e","exercicio_id":"56b3ca0d51226205048b46f3","nome":"Agachamento Frontal","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/100.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":3,"observacoes":[]},{"_id":"56b3cc23512262dd288b459f","exercicio_id":"56b3ca0d51226205048b46f5","nome":"Leg Press","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/102.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":3,"observacoes":[]},{"_id":"56b3cc23512262dd288b45a0","exercicio_id":"56b3ca0d51226205048b46e0","nome":"Abdominal em Roda","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/81.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":4,"observacoes":[]},{"_id":"56b3cc23512262dd288b45a1","exercicio_id":"56b3ca0d51226205048b46e1","nome":"Abdominal em Roda em Pé","aerobico":false,"gif":"http:\/\/localhost:8088\/wm\/imgs\/e\/e\/82.jpg","series":[{"repeticoes":[" ? "],"carga":[""],"descanso":null}],"und_carga":"kg","und_descanso":"s","und_tempo":"m","und_dist":"km","set":4,"observacoes":[]}]],"letrasTreinos":"A B C D "}],"auth":{"privateToken":"*?*","publicToken":"*?*","refreshToken":"*?*"},"sucesso":true}';       
$test["postFields"] = array(
                        'login' => '5@5.com',
                        'senha' => '1234567a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);