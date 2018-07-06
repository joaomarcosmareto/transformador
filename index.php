<?php

ini_set('max_execution_time', 3);

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
};

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
$pathFile = 'esqueleto/assets/js/controllers/ModeloController.js';
//$lines = file ();

//$GLOBALS['R'] = $modelo["caracter_replace"];

//var_dump(renderLinha($lines[3], $modelo["entidades"][0]));

//var_dump($modelo["entidades"]);
//echo "<br><br><br><br>";
processarArquivo($pathFile, $modelo);
//echo "xxx";

function processarArquivo($pathFile, $modelo){
    $lines = file($pathFile);
    //var_dump($lines);
    $countLines = count($lines);
    $finalFile = "";
    $idnex2 = 0;
    for ($index = 0;$index < $countLines;) {
//        $index2++;
        $index += renderLinha($lines, $index, $modelo, $finalFile);
//        echo "<br/>";
//        echo $r."   ".$lines[$index]."              ".$index;
//        echo "<br/>";
//        if($index2 >= $countLines)
//            break;
        
//        $line = $lines[$index];
//        if(seNovoTrecho($line)){
//            processarTrecho($lines, $indexLine, $modelo, $finalFile);
//            echo "<br/><br/>";
//            var_dump($line);
//            echo "<br/><br/>";
//        }else{
//            $elements = [];
//            preg_match_all("/.*§(.*)§.*/U", $line, $elements);
//
//            foreach ($elements[1] as $toReplace) {
//                $finalFile .= str_replace('§'.$toReplace.'§', $modelo[$toReplace], $line);
//            }
//        }        
    }
    
    var_dump($finalFile);
};


function renderLinha($lines, $indexLine, $modelo, &$finalFile){
//    var_dump($modelo);
    //var_dump($line);
    
    $line = $lines[$indexLine];
    
//    if($indexLine === 8)
//        echo "XXXXXXXXXXXX".$line;
    
    if(seNovoTrecho($line)){
//        echo "<br/><br/>";
//        var_dump($line);
//        echo "<br/><br/>";
//        if($indexLine === 8)
//            echo "111111111".$line;
        return processarTrecho($lines, $indexLine, $modelo, $finalFile);
    }else{
        if(strstr($line, "§File: ")){
            return 1;
            //TODO:
        }
        if(strstr($line, "§§")){
            return 1;
            //TODO:
        }
        $elements = [];
        preg_match_all("/.*§(.+)§.*/U", $line, $elements);

        foreach ($elements[1] as $toReplace) {
            $finalFile .= str_replace('§'.$toReplace.'§', $modelo[$toReplace], $line)."<br/>";
        }
        if(empty($elements[1])){
            $finalFile .= $line."<br>";
        }
//        if($indexLine === 8)
//            echo "ZZZZZZZZ".$line;
        return 1;
    }
};

function processarTrecho($lines, $indexLine, $modelo, &$finalFile){
    
    $l = $lines[$indexLine];
        
    if(strstr($l, "§For:")){//TODO: substituir pro preg_macth
        
        $entidadeFor = explode(" ", trim($l))[1];
        //echo "BB".$entidadeFor."BB";
        $entidadeFor = trim(str_replace("§", "", $entidadeFor));
        //echo "AAAA".$entidadeFor."AAAA";
        //TODO excecao se line_num+1 > count($lines), pq poderia tentar acessar index q n existe
        
        if($indexLine === 8){
//            echo "ZZZZZZZZ".$l."ZZZZZZZ";
//            var_dump($modelo);
        }
        
        $finalLineTrecho = getFinalLineTrecho($lines, $indexLine);

        foreach ($modelo[$entidadeFor] as $indexItem => $item) {
            for ($i = $indexLine+1; $i < $finalLineTrecho;) {
                $i += renderLinha($lines, $i, $item, $finalFile);
            }            
        }
        
        return $finalLineTrecho-$indexLine;
    }
    return 1;
};


function getFinalLineTrecho($lines, $index = 0){
    $countLines = count($lines);

    $lvl = 0;
    for (;$index < $countLines;$index++) {
        $l = $lines[$index];
        if(strstr($l, '§§')){
            $lvl--;
            if($lvl === 0){
                return $index;
            }
        }
        //TODO: se der $lvl negativo aqui deve ser lançado uma exceção de arquivo errado
        if(seNovoTrecho($l)){
            $lvl++;
        }
    }
    //TODO: se nÃ£o achar nada o arquivo template ta errado, gerar exception
};

function seNovoTrecho($l){
    return strstr($l, "§For:") || strstr($l, "§If:");
};


//$modelo = json_decode(file_get_contents('php://input'), true);
//$modelo = $modelo["modelo"];
//
//recurse_copy("./esqueleto", "./output/");

