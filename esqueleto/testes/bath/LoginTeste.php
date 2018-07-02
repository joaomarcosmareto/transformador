<?php

use core\AppConfig;
use core\util\RequestPHPTest;

//PARAMETROS GERAIS E INICIALIZAÇÃO
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$url = AppConfig::getTestBaseURL()."/core/servicos/autenticacao/login.php";

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
68. Credenciais corretas mas não é usuário dono de academia
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

$test["nome"] = "Credenciais corretas mas não é usuário dono de academia";
$test["url"] = $url;
$test["retornoEsperado"] = '{"erro":"<ul><li>Apenas os proprietários e colaboradores de negócios fitness podem logar por esse link. Se você é aluno utilize o aplicativo.<\/li><\/ul>"}';
$test["postFields"] = array(
                        'login' => 'joaomarcosmareto@gmail.com ',
                        'senha' => '1234567a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 69
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Credenciais corretas (login de fato)";
$test["url"] = $url;
$test["retornoEsperado"] = '{"nome":"Rodrigo","foto36x36":null,"academias":[{"id":"56b3ca0d51226205048b45a1","nome":"O2 Respirando Qualidade de Vida","logo":null,"bairro":"Itacibá","telefone1":null,"papelPrint":"A5","fonteGrande":false,"ocultarAnotacoes":false,"undCarga":"kg","undDescanso":"s","undTempo":"m","undDist":"km","papel":"Gerente"},{"id":"56b3ca0d51226205048b45a2","nome":"Plena Forma","logo":null,"bairro":"Tucum","telefone1":"(27)3336-3333","papelPrint":"A5","fonteGrande":false,"ocultarAnotacoes":false,"undCarga":"kg","undDescanso":"s","undTempo":"m","undDist":"km","papel":"Professor"}],"auth":{"privateToken":"*?*","publicToken":"*?*","refreshToken":"*?*"},"sucesso":true}';
$test["postFields"] = array(
                        'login' => 'rodrigo@email.com.br',
                        'senha' => '1234567a',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);


//TESTE 70
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$test["nome"] = "Login sem enviar parametros corretos (faltando POST)";
$test["url"] = $url;
$test["retornoEsperado"] = '';
$test["postFields"] = array(
                        'login' => 'rodrigo@email.com.br',
                    );

$result[] = RequestPHPTest::basicTest($url, $test, RequestPHPTest::$NO_COMPARE);