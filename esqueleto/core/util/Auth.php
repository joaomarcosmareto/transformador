<?php

namespace core\util;

use core\AppConfig;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;

// use core\DAO\ACADEMIADAO;
// use core\DAO\FUNCIONARIODAO;
// use core\DAO\USUARIODAO;
// use core\util\Logger;
// use core\util\UtilFunctions;
// use Redis;

use core\dominio\USUARIO;

use core\util\exceptions\PermissaoNegadaException;
use core\util\exceptions\TokenExpiradoException;
use core\util\exceptions\TokenInvalidoException;

class Auth
{
    static public $INVALID_HASH = 2;
    static public $EXPIRED = 3;
    static public $INVALID_TOKEN = 4;
    static public $ERROR = 5;

    static public $TIME_ADD_TO_EXPIRATION = 100;

    // static public function checkPermission($permissoes, $func)
    // {
    //     if($func == null) return true;

    //     $has_permission = in_array($func, $permissoes);

    //     if($has_permission)
    //         Logger::setFluxo("USUARIO_COM_PERMISSAO", null);
    //     else
    //         Logger::setFluxo("USUARIO_SEM_PERMISSAO", null);

    //     return $has_permission;
    // }

    public static function generateToken($usuario, $negocio_id = null)
    {
        $bytes = random_bytes(32);
        $token_id = base64_encode(bin2hex($bytes).(string)$usuario['_id']);
        $signer = new Sha256();

        $token = (new Builder())
            // ->setIssuer(Appconfig::$SERVER_NAME) // Configures the issuer (iss claim)
            // ->setAudience(Appconfig::$AUDIENCE_NAME) // Configures the audience (aud claim)
            ->setId($token_id, false) // Configures the id (jti claim), replicating as a header item
            // ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
            // ->setNotBefore(time()) // Configures the time that the token can be used (nbf claim)
            ->setExpiration(time() + Auth::$TIME_ADD_TO_EXPIRATION) // Configures the expiration time of the token (exp claim)
            // o claim info abaixo, sãos os dados do usuario_id logado e qual negocio ele está manipulando no momento.
            ->set('_id', (string)$usuario['_id'])
            ->set('d', (string)$usuario['_id'])
            // ->set('negocio_id', (string)$negocio_id)
            ->sign($signer, AppConfig::$SECRET_KEY) // creates a signature using "testing" as key
            ->getToken(); // Retrieves the generated token
        return $token;
    }

    /**
     * Esta função recebe uma string jwt encodada e encriptada e devolve um objeto jwt parseado.
     * Se $check for true, também faz a verificação de validade do Token
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function getToken(string $jwt_string = null, bool $check = true){
        if($jwt_string == null || !isset($jwt_string[7]))
            return null;

        $jwt_string = substr($jwt_string, 7);

        $signer = new Sha256();
        $token = (new Parser())->parse($jwt_string);
        $token->getHeaders();
        $token->getClaims();

        // se o token ainda contem a assinatura do servidor, vulgo não foi alterado
        if($token->verify($signer, Appconfig::$SECRET_KEY))
        {
            if($check)
                Auth::check($token);
            return $token;
        }
        return null;
    }

    //retorna o claim info do token caso seja considerado autenticado
    public static function check($token = null, $jwt_string = null)
    {
        // constrói o token a partir do que veio no cabeçalho http
        if($token == null)
            $token = Auth::getToken($jwt_string);

        if($token !== null)
        {
            if($token->getClaim("jti") === "")
                throw new TokenInvalidoException();

            $dataExpires = new \DateTime();
            $dataExpires->setTimezone(new \DateTimeZone("America/Sao_Paulo"));
            $dataExpires->setTimeStamp($token->getClaim("exp"));

            $dataHoraAtual = new \DateTime();
            $dataHoraAtual->setTimezone(new \DateTimeZone("America/Sao_Paulo"));

            if($dataHoraAtual->format('Y-m-d H:i:s') > $dataExpires->format('Y-m-d H:i:s'))
                throw new TokenExpiradoException();

            // return ["usuario_id"=>$token->getClaim("_id"), "academia_id"=>$token->getClaim("academia_id")];
            return true;
        }
        throw new TokenInvalidoException();
    }//check

    // static public function getInfo($token = null){
    //     if($token == null)
    //         $token = Auth::getToken();

    //     if($token !== null)
    //         return ["usuario_id"=>$token->getClaim("_id"), "academia_id"=>$token->getClaim("academia_id")];
    //     return null;
    // }



    // static public function getPermissionFromToken($token)
    // {
    //     $redis = Cache::getInstance();
    //     $permissoes = $redis->get($token);
    //     if($permissoes !== false && $permissoes !== null)
    //         return explode(";", $permissoes);
    //     return null;
    // }

    // static function initService(&$con, &$retorno, &$usuario, $info){
    //     if(UtilFunctions::contemErro($retorno))
    //     {
    //         return false;
    //     }
    //     if(empty($con))
    //         $con = ConexaoBD::getInstance();

    //     if($con)
    //     {
    //         if($info !== null)
    //         {
    //             $usuario = new USUARIO(UtilFunctions::getMongoID($info["usuario_id"]));
    //             return true;
    //         }
    //     }
    //     else
    //     {
    //         Logger::salvar(null, "SEM_CONEXAO_BANCO", null, "");
    //         $retorno["erro"][] = i18n::write("FALHA")." E1. ".i18n::write("EQUIPE_AVISADA")." ".i18n::write("TENTE_NOVAMENTE");
    //     }
    //     return false;
    // }

    // //id é o id do item
    // static public function seItemPertenceAoNegocio($id, $item, $academia_id, $usuarioLogado, $con, $log_nome, $idPodeSerNULL = false)
    // {
    //     $retorno = array();

    //     if( (!$idPodeSerNULL && $id == null)
    //         || ($id != null && ($item == null || ($item != null && ((string)$item->getAcademia_id()) != ((string)$academia_id)) ) )
    //         )
    //     {
    //         $retorno[] = i18n::write("ITEM_NAO_EXISTE_OU_NAO_PERTENCE_A_ACADEMIA");
    //         Logger::setFluxo("ITEM_NAO_EXISTE_OU_NAO_PERTENCE_A_ACADEMIA", $usuarioLogado->getEmail());
    //     }

    //     return $retorno;
    // }

    // retorna uma validade de 3h (padrão) + 1-30min em formato string
    static public function getNewValidadeToken($hoursBase = 3)
    {
        $validade = new \DateTime();
        $validade->setTimezone(new \DateTimeZone("America/Sao_Paulo"));
        $validade->modify("+".$hoursBase." hour");//máximo de tempo na academia

        return $validade->format('Y-m-d H:i:s');
    }

    // recebe DateTime $validade
    static public function isExpired($validade)
    {
        $dataHoraAtual = new \DateTime();
        $dataHoraAtual->setTimezone(new \DateTimeZone("America/Sao_Paulo"));

        return $dataHoraAtual->format('Y-m-d H:i:s') > $validade->format('Y-m-d H:i:s');
    }

}// Classe