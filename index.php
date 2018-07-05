<?php

function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

$modelo = '{
    "nome": "Teste",
    "caracter_replace": "§",
    "tags_replace": [
        {
            "nome": "sistema",
            "valor": "teste"
        },
        {
            "nome": "Sistema",
            "valor": "Teste"
        }
    ],
    "entidades":
    [
        {
            "nome": "NCM",
            "campos": [
                {"nome": "_id", "tipo_dado": "id", "tipo_campo": "text"},
                {"nome": "descricao", "tipo_dado": "string", "tipo_campo": "text"},
                {"nome": "ii", "tipo_dado": "float", "tipo_campo": "text"}
            ]
        },
        {
            "nome": "Produto",
            "campos": [
                {"nome": "_id", "tipo_dado": "id", "tipo_campo": "text"},
                {"nome": "nome", "tipo_dado": "string", "tipo_campo": "text"},
                {"nome": "ncm", "tipo_dado": "id", "tipo_campo": "select", "relacao": {"entidade": "NCM", "campo": "_id"}}
            ]
        }
    ]
}';

//var_dump(json_decode($modelo, true));

$modelo = json_decode($modelo, true);
$lines = file ('esqueleto/assets/js/controllers/ModeloController.js');

$GLOBALS['R'] = $modelo["caracter_replace"];

var_dump(renderLinha($lines[3], $modelo["entidades"][0]));

//$retorno = "";
//foreach ($lines as $line_num => $line) {
////    echo "Linha #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br>\n";
//    $lineTrim = trim($line);
//    if(($line_num === 1 && $lineTrim === $R."REPLACE"))
//    {
//        break;
//    }
//    
//    if(seNovoTrecho($lineTrim))
//        processarTrecho($lines, $line_num, getFinalLineTrecho($lines, $line_num));
//    else
//        $result += $line;
//}
//
//$lineTrim = trim($line);
//if(($line_num === 1 && $lineTrim === $R."REPLACE"))
//{
//    return;
//}

function processarTrecho($lines, $line_num, $final_trecho_num){
    
    $lineTrim = $lines[$line_num];
    
    if(strstr($lineTrim, $R."For:")){
        $ifor = explode(" ", $lineTrim)[1];
        //echo $ifor;
        getConteudo($lines, $line_num);
        
        foreach ($modelo[$ifor] as $i => $item) {
            renderLinha($line, $ifor, $item);
        }
    }
}

function renderLinha($line, $modelo){
//    var_dump($modelo);
    //var_dump($line);
    //para cada $ $ da linha, fazer:
    $elements = [];
    preg_match_all("/.*§(.*)§.*/U", $line, $elements);
    //var_dump($elements);
    
    foreach ($elements[1] as $toReplace) {

        $line = str_replace('§'.$toReplace.'§', $modelo[$toReplace], $line);
        
        //TODO: parte com ponto
//        $toReplaceArr = explode($toReplace, ".");        
//        
//        if(count($toReplaceArr) === 1){
//            str_replace($R.$toReplace.$R, $modelo[$nome], $line);
//        }elseif(count($toReplaceArr) === 2){
//            str_replace($R.$toReplaceArr[0].$toReplaceArr[1].$R, $modelo[$toReplaceArr[0]][$toReplaceArr[1]], $line);
//        }   
    }
    
    return $line;
}

function xxx($lines, $index = 0){
    $countLines = count($lines);
    $lvl = 1;
    for (;$index < $countLines;$index++) {
        //var_dump(strstr($lineTrim, $R."For:"));
        if(seNovoTrecho($lineTrim)){
            $ifor = explode(" ", $lineTrim)[1];//TODO ver se tem mais de um
            echo $ifor;
            getConteudo($lines, $line_num);
        }
    }
}

function getFinalLineTrecho($lines, $index = 0){
    $countLines = count($lines);
    $lvl = 1;
    for (;$index < $countLines;$index++) {
        if(strstr($lineTrim, $R.$R)){
            $lvl--;
            if($lvl === 1){
                return $index;
            }
        }
        if(seNovoTrecho($lineTrim)){
            $lvl++;
        }
    }
    //TODO: se não achar nada o arquivo template ta errado, gerar exception
}

function seNovoTrecho($l){
    return strstr($l, $R."For:") || strstr($l, $R."If:");
}


//$modelo = json_decode(file_get_contents('php://input'), true);
//$modelo = $modelo["modelo"];
//
//recurse_copy("./esqueleto", "./output/");

