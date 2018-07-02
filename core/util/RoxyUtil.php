<?php
namespace core\util;

use core\AppConfig;
///use core\util\UtilFunctions;

class RoxyUtil {
    
    static function IsImage($fileName){
        $ret = false;
        $ext = strtolower(self::GetExtension($fileName));
        if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'jpe' || $ext == 'png' || $ext == 'gif' || $ext == 'ico')
          $ret = true;
        return $ret;
    }
    
    static function GetExtension($filename) {
        $ext = '';

        if(mb_strrpos($filename, '.') !== false)
          $ext = mb_substr($filename, mb_strrpos($filename, '.') + 1);

        return strtolower($ext);
    }
    
    static function listDirectory($path){
        $ret = @scandir($path);
        if($ret === false){
          $ret = array();
          $d = opendir($path);
          if($d){
            while(($f = readdir($d)) !== false){
              $ret[] = $f;
            }
            closedir($d);
          }
        }
        return $ret;
    }
      
    static function getFilesNumber($path){
        $files = 0;
        $dirs = 0;
        $tmp = RoxyUtil::listDirectory($path);
        foreach ($tmp as $ff){
          if($ff == '.' || $ff == '..')
            continue;
          elseif(is_file($path.'/'.$ff))
            $files++;
          elseif(is_dir($path.'/'.$ff))
            $dirs++;
        }

        return array('files'=>$files, 'dirs'=>$dirs);
    }
    
    static function GetDirs($path, $negocio_id){

        $ret = $sort = array();
        $files = RoxyUtil::listDirectory($path);
        foreach ($files as $f){
            $fullPath = $path.'/'.$f;
            if(!is_dir($fullPath) || $f == '.' || $f == '..')
                continue;
            $tmp = RoxyUtil::getFilesNumber($fullPath);
            $ret[$fullPath] = array('path'=>$fullPath,'files'=>$tmp['files'],'dirs'=>$tmp['dirs']);
            $sort[$fullPath] = $f;
        }
        natcasesort($sort);

        $retorno = [];
        foreach ($sort as $k => $v) {
            $tmp = $ret[$k];
            $retorno[] = [
                "p"=> RoxyUtil::ajustaPath($tmp['path'], $negocio_id),
                "f"=>$tmp['files'],
                "d"=>$tmp['dirs']
            ];
            $f += $tmp['files'];
            $d += $tmp['dirs'];
            
            array_push($retorno, ...RoxyUtil::GetDirs($tmp['path'], $negocio_id));
        }
        return $retorno;
    }
    
    static function removePrefixPath($p){
        return str_replace("/Arquivos", "", $p);
    }
    
    static function ajustaPath($path, $negocio_id){
        return "/Arquivos/".str_replace(AppConfig::getDirBase()."imgs/c/".$negocio_id."/", "", $path);        
    }
        
    function getFilesPath($negocio_id){
        $ret = (isset($_SESSION[SESSION_PATH_KEY]) && $_SESSION[SESSION_PATH_KEY] != ''?$_SESSION[SESSION_PATH_KEY]:AppConfig::getDirBase()."imgs/c/".$negocio_id);
        //var_dump($ret);
        if(!$ret){
            $ret = RoxyUtil::FixPath(BASE_PATH.'/Arquivos');
            $tmp = $_SERVER['DOCUMENT_ROOT'];
            //var_dump($temp);
            if(mb_substr($tmp, -1) == '/' || mb_substr($tmp, -1) == '\\')
                $tmp = mb_substr($tmp, 0, -1);
            $ret = str_replace(RoxyUtil::FixPath($tmp), '', $ret);
        }
        //var_dump($ret);
        return $ret;
    }
    
    static function FixPath($path){
        $path = mb_ereg_replace('[\\\/]+', '/', $path);
        return $path;
    }
}
