<?php
namespace core\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\dominio\functions\ConteudoFunctions;
use core\util\{Firewall, Mongo, Retorno, UtilFunctions, ManipuladorArquivos, RoxyUtil};
use core\AppConfig;

class ConteudoController {

    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function listar(Request $request, Response $response, $args)
    {
        $esperado = [
            "negocio" => ["tipo" => "id", "obrigatorio" => true],
            "paginaAtual" => ["tipo" => "int", "obrigatorio" => true],
            "numRegistros" => ["tipo" => "int", "obrigatorio" => true],

            "filtro_titulo" => ["tipo" => "string", "obrigatorio" => false],
            "filtro_categoria" => ["tipo" => "id", "obrigatorio" => false],
            //TODO: filtros
            //"ativo" => ["tipo" => "boolean", "obrigatorio" => true, "ifNotSet" => null],
        ];
        $params = Firewall::sanitize($request, $esperado);

        $inicio = ($params["paginaAtual"] -1) * $params["numRegistros"];

        Retorno::$data["itens"] = array();
        Retorno::$data["total"] = 0;

        $campos = array();
        $filtros = array();
        $comparadores = array();

        $append = array(
            "skip" => $inicio,
            "limit" => $params["numRegistros"],
            "sort" => array("dataAlteracao" => -1),
            "projection" => [
                "titulo" => 1
            ],
        );

        // caso esteja filtrando conteúdo pela tela de menu, tira o número de registro máximo
        if($params['numRegistros'] === 0)
            unset($append['limit']);

        // caso esteja filtrando conteúdo pela tela de menu, verifica o filtro pelo título
        if(isset($params['filtro_titulo']) && $params['filtro_titulo'] !== null && trim($params['filtro_titulo']) !== "")
        {
            $campos[] = "titulo";
            $filtros[] = $params['filtro_titulo'];
            $comparadores[] = "=";
        }
        // caso esteja filtrando conteúdo pela tela de menu, verifica o filtro pela categoria
        if(isset($params['filtro_categoria']) && $params['filtro_categoria'] !== null)
        {
            $campos[] = "categoria_id";
            $filtros[] = $params['filtro_categoria'];
            $comparadores[] = "=";
        }

        $campos[] = "negocio_id";
        $filtros[] = $params["negocio"];
        $comparadores[] = "=";

        $auxMongo = Mongo::retornaTodos("conteudo", $campos, $filtros, $comparadores, $append);

        $count = count($auxMongo);
        for ($i = 0; $i < $count; $i++) {
            $auxMongo[$i]["id"] = (string)$auxMongo[$i]["_id"];
            unset( $auxMongo[$i]["_id"]);
        }

        Retorno::$data["itens"] = $auxMongo;
        Retorno::$data["total"] = Mongo::retornaContagem("conteudo", $campos, $filtros, $comparadores);

        $return = $response->withJson(UtilFunctions::RETORNO(Retorno::$data), 200);
        return $return;
    }

    public function selecionar(Request $request, Response $response, $args)
    {
        $esperado = [
            "id" => ["tipo" => "id", "obrigatorio" => false, "ifNotSet" => null],
            "negocio" => ["tipo" => "id", "obrigatorio" => true, "ifNotSet" => null],
        ];
        $params = Firewall::sanitize($request, $esperado);

        $campos = array();
        $filtros = array();
        $comparadores = array();

        $append = array(
//            "projection" => [
//                "titulo" => 1
//            ],
        );

        $campos[] = "_id";
        $filtros[] = $params["id"];
        $comparadores[] = "=";

        $campos[] = "negocio_id";
        $filtros[] = $params["negocio"];
        $comparadores[] = "=";

        $auxMongo = Mongo::retornaUm("conteudo", $campos, $filtros, $comparadores, $append);

        $auxMongo["id"] = (string)$auxMongo["_id"];
        unset($auxMongo["_id"]);
        $auxMongo["categoria"] = (string)$auxMongo["categoria_id"];
        $auxMongo["publicado"] = $auxMongo["publicado"] ? "1" : "0";
        $auxMongo["paginas"] = !empty($auxMongo["paginas"]) ? $auxMongo["paginas"] : [];
        unset($auxMongo["categoria_id"]);
        unset($auxMongo["negocio_id"]);
        unset($auxMongo["usuarioAlteracao"]);
        unset($auxMongo["dataAlteracao"]);
        unset($auxMongo["dataCriacao"]);
        unset($auxMongo["removido"]);

        $return = $response->withJson($auxMongo, 200);
        return $return;
    }

    public function salvar(Request $request, Response $response, $args)
    {
        $esperado = [
            "id" => ["tipo" => "id"],
            "negocio" => ["tipo" => "id", "obrigatorio" => true],
            "titulo" => ["tipo" => "string", "obrigatorio" => true],
            "publicado" => ["tipo" => "boolean", "obrigatorio" => true],
            "categoria" => ["tipo" => "id", "obrigatorio" => true],
            "modelo" => ["tipo" => "string", "obrigatorio" => true],
            "paginas" => ["tipo" => "string"],
            "conteudo" => ["tipo" => "string"],
        ];
        $params = Firewall::sanitize($request, $esperado);

        ConteudoFunctions::validaInputCadastro($params);

        $usuario = $request->getAttribute('usuario');

        //TODO: verficar se tem permissão nesse negocio
        //NegocioFunctions::validaRegrasNegocioSalvarCategoria($params);

        ConteudoFunctions::update($params, $usuario['_id']);

        //TODO: tirar esse response com JSON
        $return = $response->withJson('', 200);
        return $return;
    }
    
    public function dirtree(Request $request, Response $response, $args)
    {        
        $esperado = [
            "negocio" => ["tipo" => "id", "obrigatorio" => true],
        ];
        $params = Firewall::sanitize($request, $esperado);
        
//    0	{…}
//    p	/browser/Uploads
//    f	2
//    d	3
//    1	{…}
//    p	/browser/Uploads/Documents
//    f	0
//    d	0
//    2	{…}
//    p	/browser/Uploads/dsadsa
//    f	0
//    d	0
//    3	{…}
//    p	/browser/Uploads/Images
//    f	2
//    d	0
            
        $p =RoxyUtil::getFilesPath($params["negocio"]);
        $pNumbers = RoxyUtil::getFilesNumber($p);
        $returnData = [
            [
                'p' => '/Arquivos',
                'f' => $pNumbers["files"],
                'd' => $pNumbers["dirs"],
            ],
        ];
        
        array_push($returnData, ...RoxyUtil::GetDirs($p, $params["negocio"]));
                
        $return = $response->withJson($returnData, 200);
        return $return;        
    }
    
    public function thumb(Request $request, Response $response, $args)
    {        
        //TODO:
    }    
    
    public function deletedir(Request $request, Response $response, $args)
    {        
        $esperado = [
            "negocio" => ["tipo" => "id", "obrigatorio" => true],
            "d" => ["tipo" => "string", "obrigatorio" => false],
        ];
        $params = Firewall::sanitize($request, $esperado);
        
        $params["d"] = urldecode($params["d"]);
        $params["d"] = RoxyUtil::removePrefixPath($params["d"]);

        $path = AppConfig::getDirBase()."/imgs/c/".$params["negocio"].DIRECTORY_SEPARATOR.$params["d"];
        
        $return = [];
        
        if(empty($params["d"])){
            $return = $response->withJson(["res"=>"error", "msg"=>"E_CannotDeleteRoot"], 200);
        }elseif(is_dir($path)){
            if(count(glob($path."/*")))
                $return = $response->withJson(["res"=>"error", "msg"=>"E_DeleteNonEmpty"], 200);
            elseif(rmdir($path))
                $return = $response->withJson(["res"=>"ok", "msg"=>""], 200);
            else
                $return = $response->withJson(["res"=>"error", "msg"=>"E_CannotDeleteDir"], 200);
        }
        else
            $return = $response->withJson(["res"=>"error", "msg"=>"E_DeleteDirInvalidPath"], 200);
                
        return $return;
    }
    
    
    public function deletefile(Request $request, Response $response, $args)
    {        
        $esperado = [
            "negocio" => ["tipo" => "id", "obrigatorio" => true],
            "d" => ["tipo" => "string", "obrigatorio" => true],
            "f" => ["tipo" => "string", "obrigatorio" => true],
        ];
        $params = Firewall::sanitize($request, $esperado);
        
        $params["d"] = urldecode($params["d"]);
        $params["d"] = RoxyUtil::removePrefixPath($params["d"]);
        $path = AppConfig::getDirBase()."/imgs/c/".$params["negocio"].DIRECTORY_SEPARATOR.$params["d"];

        $f = $path."/".str_replace(AppConfig::getImgURL()."c/".$params["negocio"], "", $params["f"]);
        
        $return = [];
        
        if(is_file($f)) {
            if(unlink($f))
                $return = $response->withJson(["res"=>"ok", "msg"=>""], 200);
            else
                $return = $response->withJson(["res"=>"error", "msg"=>"E_DeletеFile"], 200);
        } else
            $return = $response->withJson(["res"=>"error", "msg"=>"E_DeleteFileInvalidPath"], 200);
                
        return $return;
    }
        
    public function createdir(Request $request, Response $response, $args)
    {        
        $esperado = [
            "negocio" => ["tipo" => "id", "obrigatorio" => true],
            "d" => ["tipo" => "string", "obrigatorio" => true],
            "n" => ["tipo" => "string", "obrigatorio" => true],
        ];
        
//        d	/Arquivos
//        n	jhkj
        
        $params = Firewall::sanitize($request, $esperado);
        
        $params["d"] = urldecode($params["d"]);
        $params["d"] = RoxyUtil::removePrefixPath($params["d"]);
        
        $path = AppConfig::getDirBase()."imgs/c/".$params["negocio"].DIRECTORY_SEPARATOR.$params["d"];
        
        if(is_dir($path)){
          if(mkdir($path.'/'.$params["n"] , 0755)){
//            res	ok
//            msg	
            $return = $response->withJson(["res"=>"ok", "msg"=>""], 200);
          }else
            $return = $response->withJson(["res"=>"ok", "msg"=>""], 400);
        }
        else
          $return = $response->withJson("", 400);
        
        return $return;
    }
    
    public function browser(Request $request, Response $response, $args)
    {        
        $esperado = [
            "negocio" => ["tipo" => "id", "obrigatorio" => true],
            "d" => ["tipo" => "string", "obrigatorio" => false],
        ];
        $params = Firewall::sanitize($request, $esperado);
        
        $params["d"] = urldecode($params["d"]);
        $params["d"] = RoxyUtil::removePrefixPath($params["d"]);
        $params["d"] = trim($params["d"]);
        
        $path = AppConfig::getImgPath().'c'.DIRECTORY_SEPARATOR.((string)$params["negocio"]).DIRECTORY_SEPARATOR.$params["d"];
        $pathURL = AppConfig::getImgURL().'c'.DIRECTORY_SEPARATOR.((string)$params["negocio"]).DIRECTORY_SEPARATOR.$params["d"];

        $type = (empty($_POST['type'])?'':strtolower($_POST['type']));
        if($type != 'image' && $type != 'flash')
            $type = '';
        
        $dir = @scandir($path);
        if($dir === false){
            $dir = array();
            $d = opendir($path);
            if($d){
                while(($f = readdir($d)) !== false){
                    $dir[] = $f;
                }
                closedir($d);
            }
        }
        
        natcasesort($dir);

        $roxyReturn = [];
        
        foreach ($dir as $f){
            $fullPath = $path.'/'.$f;
            if(!is_file($fullPath) || ($type == 'image' && !RoxyUtil::IsImage($f)))
                continue;
            $size = filesize($fullPath);
            $time = filemtime($fullPath);
            $w = 0;
            $h = 0;
            if(RoxyUtil::IsImage($f)){
                $tmp = @getimagesize($fullPath);
                if($tmp){
                    $w = $tmp[0];
                    $h = $tmp[1];
                }
            }
            $roxyReturn[] = [
                "p"=>$pathURL.DIRECTORY_SEPARATOR.$f,
                "s"=>$size,
                "t"=>$time,
                "w"=>$w,
                "h"=>$h
              ];
        }            
        
        $return = $response->withJson($roxyReturn, 200);
        return $return;
    }
    
    public function upload(Request $request, Response $response, $args)
    {
        $esperado = [
            "negocio" => ["tipo" => "id", "obrigatorio" => true],
        ];
        $params = Firewall::sanitize($request, $esperado);

        $ma = new ManipuladorArquivos($request);
        //TODO: max size
        $result = $ma->saveImageClientName('upload', 'c'.DIRECTORY_SEPARATOR.$params["negocio"].DIRECTORY_SEPARATOR, 1200, 1200);
        
        $auxReturn = [];
        if(!empty($result)){
            $auxString = explode(DIRECTORY_SEPARATOR, $result);
            $auxReturn = [
                "uploaded" => 1,
                "fileName" => $auxString[count($auxString)-1],
                "url" => AppConfig::getImgURL().$result
            ];
            $return = $response->withJson($auxReturn, 200);
        }else{
            $auxReturn = [
                "uploaded" => 0,
                "error" => "Ops,.. ocorreu um erro ao fazer o upload da imagem.",
            ];
            $return = $response->withJson($auxReturn, 400);
        }
        return $return;
        //TODO:
//        {
//            "uploaded": 1,
//            "fileName": "foo(2).jpg",
//            "url": "/files/foo(2).jpg",
//            "error": {
//                "message": "A file with the same name already exists. The uploaded file was renamed to \"foo(2).jpg\"."
//            }
//        }
        
    }
    

}