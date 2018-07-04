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

$R = $modelo["caracter_replace"];

$retorno = "";
foreach ($lines as $line_num => $line) {
//    echo "Linha #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br>\n";
    $lineTrim = trim($line);
    if(($line_num === 1 && $lineTrim === $R."REPLACE"))
    {
        break;
    }
    
    //var_dump(strstr($lineTrim, $R."For:"));
    if(strstr($lineTrim, $R."For:")){
        $ifor = explode(" ", $lineTrim)[1];//TODO ver se tem mais de um
        echo $ifor;
    }        
}

//function getConteudo


//$modelo = json_decode(file_get_contents('php://input'), true);
//$modelo = $modelo["modelo"];
//
//recurse_copy("./esqueleto", "./output/");

