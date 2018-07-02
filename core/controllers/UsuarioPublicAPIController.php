<?php
namespace core\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\AppConfig;
use core\dominio\functions\UsuarioFunctions;
use core\util\auth\Auth;
use core\util\{i18n, Cache, Firewall, Logger, Mongo, MailManager, Retorno, UtilFunctions};
use MongoDB\BSON\ObjectID;

class UsuarioPublicAPIController {

    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function cadastrar(Request $request, Response $response, $args)
    {
        $esperado = [
            "nome" => ["tipo" => "string", "obrigatorio" => true],
            "sobrenome" => ["tipo" => "string", "obrigatorio" => true],
            "email" => ["tipo" => "string", "obrigatorio" => true],
            "senha" => ["tipo" => "string", "obrigatorio" => true],
            "sexo" => ["tipo" => "string", "obrigatorio" => true],
            "dataNascimento" => ["tipo" => "string", "obrigatorio" => true]
        ];
        $params = Firewall::sanitize($request, $esperado);

        UsuarioFunctions::validaInputRegister($params);

        $usuario_banco = Mongo::retornaUm("usuario", ["email"], [$params["email"]], ["="], ["limit"=>1]);

        $usuarioExisteTokenExpirado = $usuario_banco !== null && $usuario_banco['accToken'] !== null && Auth::isExpired(new \DateTime($usuario_banco['validadeAccToken']));
        // TODO: CRIAR UM CRONJOB PARA REMOVER OS USUÁRIOS QUE SE REGISTRARAM MAS NÃO VALIDARAM O CADASTRO
        if($usuario_banco == null || $usuarioExisteTokenExpirado)
        {
            if($usuarioExisteTokenExpirado)
            {
                Mongo::deletar("usuario", $usuario_banco['_id']);
            }

            $usuario = UsuarioFunctions::fromRegister($params);

            Mongo::salvar("usuario", $usuario);

            MailManager::createMailCriacaoConta($usuario);

            Retorno::$data["sucesso"] = true;
            Retorno::$data["msg"][] = i18n::write("EMAIL_LINK_CADASTRO");

            Logger::setFluxo("REGISTER_USUARIO_ADMIN_SUCESSO");

            $return = $response->withJson(UtilFunctions::RETORNO(Retorno::$data), 200);
            return $return;
        }
        throw new \Exception(json_encode([i18n::write("EMAIL_JA_CADASTRADO")]), 400);
    }

    public function confirmarCadastro(Request $request, Response $response, $args)
    {
        $esperado = [
            "codigo" => ["tipo" => "string", "obrigatorio" => true],
            "email" => ["tipo" => "string", "obrigatorio" => true]
        ];
        $params = Firewall::sanitize($request, $esperado);

        UsuarioFunctions::validaInputConfirmaRegistro($params);

        $usuario = Mongo::retornaUm("usuario", ["accToken", "email"], [$params["codigo"], $params["email"]], ["=", "="], ["limit"=>1]);

        if($usuario !== null)
        {
            if(!Auth::isExpired(new \DateTime($usuario['validadeAccToken'])))
            {
                $usuario['accToken'] = null;
                $usuario['validadeAccToken'] = null;

                Mongo::salvar("usuario", $usuario);

                MailManager::createMailConfirmacaoCadastro($usuario);

                Retorno::$data["sucesso"] = true;

                Logger::setFluxo("CONFIRM_USUARIO_ADMIN_SUCESSO");

                $return = $response->withJson(UtilFunctions::RETORNO(Retorno::$data), 200);
                return $return;
            }
            throw new \Exception(json_encode([i18n::write("ACCTOKEN_EXPIRED")]), 400);
        }
        throw new \Exception(json_encode([i18n::write("ACCTOKEN_NOT_FOUND")]), 400);
    }

    public function recuperarSenha(Request $request, Response $response, $args)
    {
        $esperado = ["email" => ["tipo" => "string", "obrigatorio" => true]];
        $params = Firewall::sanitize($request, $esperado);

        UsuarioFunctions::validaInputRecuperarSenha($params);

        $usuario = Mongo::retornaUm("usuario", ["email"], [$params["email"]], ["="], ["limit"=>1]);

        if($usuario !== null)
        {
            if($usuario['accToken'] == null)
            {
                $usuario['pwdToken'] = (UtilFunctions::randomCode(64));
                $usuario['validadePwdToken'] = (Auth::getNewValidadeToken(1));

                Mongo::salvar("usuario", $usuario);

                MailManager::createMailRecuperarSenha($usuario);

                Retorno::$data["sucesso"] = true;
                Logger::setFluxo("RECUPERAR_SENHA_SUCESSO");

                $return = $response->withJson(UtilFunctions::RETORNO(Retorno::$data), 200);
                return $return;
            }
            throw new \Exception(json_encode([i18n::write("USUARIO_NAO_CONFIRMADO")]), 400);
        }
        throw new \Exception(json_encode([i18n::writeParams("USUARIO_NAO_ENCONTRADO_COM_EMAIL", null, [$usuario['email']])]), 400);
    }

    public function alterarSenha(Request $request, Response $response, $args)
    {
        $esperado = [
            "codigo" => ["tipo" => "string", "obrigatorio" => true],
            "email" => ["tipo" => "string", "obrigatorio" => true],
            "novasenha" => ["tipo" => "string", "obrigatorio" => true],
            "confirmsenha" => ["tipo" => "string", "obrigatorio" => true]
        ];
        $params = Firewall::sanitize($request, $esperado);

        UsuarioFunctions::validaInputAlterarSenha($params);

        $usuario = Mongo::retornaUm("usuario", ["email", "pwdToken"], [$params["email"], $params["codigo"]], ["=", "="], ["limit"=>1]);

        if($usuario !== null)
        {
            if($usuario['accToken'] == null)
            {
                if(!Auth::isExpired(new \DateTime($usuario['validadePwdToken'])))
                {
                    $usuario['pwdToken'] = null;
                    $usuario['validadePwdToken'] = null;
                    $usuario['senha'] = sodium_crypto_pwhash_str(
                        $params["novasenha"],
                        SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
                        SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
                    );

                    Mongo::salvar("usuario", $usuario);

                    // TODO: REMOVER OS TOKENS ATIVOS DO USUÁRIO

                    MailManager::createMailAlterarSenha($usuario);

                    Retorno::$data["sucesso"] = true;
                    Logger::setFluxo("ALTERAR_SENHA_SUCESSO");

                    $return = $response->withJson(UtilFunctions::RETORNO(Retorno::$data), 200);
                    return $return;
                }
                throw new \Exception(json_encode([i18n::write("PWDTOKEN_EXPIRED")]), 400);
            }
            throw new \Exception(json_encode([i18n::write("USUARIO_NAO_CONFIRMADO")]), 400);
        }
        throw new \Exception(json_encode([i18n::writeParams("USUARIO_NAO_ENCONTRADO_COM_EMAIL", null, [$usuario['email']])]), 400);
    }

    public function login(Request $request, Response $response, $args)
    {
        $esperado = [
            "email" => ["tipo" => "string", "obrigatorio" => true],
            "senha" => ["tipo" => "string", "obrigatorio" => true]
        ];
        $params = Firewall::sanitize($request, $esperado);

        UsuarioFunctions::validaInputLogin($params);

        $usuario = Mongo::retornaUm("usuario", ["email", "accToken"], [$params["email"], null], ["=","="], ["limit"=>1]);

        if($usuario !== null)
        {
            if(sodium_crypto_pwhash_str_verify($usuario['senha'], $params["senha"]))
            {
                $retorno = UsuarioFunctions::retornaDadosLoginAppWeb($usuario);
                if(isset($retorno["negocios"]))
                {
                    // REVISAR O PROCESSO DE LOGIN:
                    // NO GENERATE TOKEN, EU CRIO UM UID E RETORNO NO TOKEN PARA O USUARIO.
                    // A CADA REQUEST, EU VERIFICO SE O TOKEN ESTÁ VENCIDO;
                    // SE ESTIVER VENCIDO, EU VEJO SE O UID ENVIADO PELO USUARIO É VÁLIDO, DAÍ EU FAÇO O REFRESH TOKEN.
                    // SE NÃO FOR UM UID VÁLIDO, MANDA LOGAR NOVAMENTE.

                    $permissoes = null;
                    $negocio_id = null;

                    if(count($retorno["negocios"]) == 1){
                        $negocio_id = $retorno["negocios"][0]["id"];
                        $permissoes = UsuarioFunctions::getUserPermission($usuario);
                        $permissoes = implode(";", $permissoes);
                    }
                    Retorno::$data["data"] = $retorno;
                    Retorno::$data["sucesso"] = true;
                    $token = Auth::generateToken($usuario, new ObjectID());

                    Cache::set($token, $permissoes, Auth::$TIME_ADD_TO_EXPIRATION);

                    $response = $response->withHeader('authorization', 'Bearer '. $token);

                    Logger::setFluxo("LOGIN_SUCESSO", $usuario['email']);
                }
                $return = $response->withJson(UtilFunctions::RETORNO(Retorno::$data), 200);
                return $return;
            }
            Firewall::incrBadLogin($this->container->get('settings')['badLoginRate']["timeLimit"]);
            throw new \Exception(json_encode([i18n::write("LOGIN_SENHA_INCORRETOS")]), 400);
        }
        throw new \Exception(json_encode([i18n::write("USUARIO_NAO_CONFIRMADO_OU_INEXISTENTE")]), 400);
    }

    public function logout(Request $request, Response $response, $args)
    {
        // $usuario_id = $request
        $usuario = Mongo::retornaUm("usuario", ["email", "pwdToken"], [$params["email"], $params["codigo"]], ["=", "="], ["limit"=>1]);

        if($usuario !== null)
        {
            // $usuario->doc["devices"] = ["uid"=>]
            Mongo::salvar("usuario", $usuario);
            Cache::delete();
        }

        $return = $response->withJson(Retorno::$data, 200);
        return $return;
    }
}