<?php

namespace testes;

set_time_limit(0);

require "../vendor/autoload.php";

use core\AppConfig;

$bathDirectory = dir(AppConfig::getDirBase().DIRECTORY_SEPARATOR."testes".DIRECTORY_SEPARATOR."bath".DIRECTORY_SEPARATOR);
$retorno = [];
$retorno["sucesso"] = true;
$countAux = 0;
$totalSuccess = 0;
$totalFail = 0;
while($file = $bathDirectory -> read())
{
    if($file != '.' && $file != '..')
    {
        $result = array();

        if(!isset($result['zerarBanco']) || $result['zerarBanco'] == true)
        {
            exec('mongoimport --db '.AppConfig::$MONGO_BASE.' --collection academia --file mongoBase/academia.json --drop');
            exec('mongoimport --db '.AppConfig::$MONGO_BASE.' --collection aluno --file mongoBase/aluno.json --drop');
            exec('mongoimport --db '.AppConfig::$MONGO_BASE.' --collection avaliacao --file mongoBase/avaliacao.json --drop');
            exec('mongoimport --db '.AppConfig::$MONGO_BASE.' --collection convite --file mongoBase/convite.json --drop');
            exec('mongoimport --db '.AppConfig::$MONGO_BASE.' --collection exercicio --file mongoBase/exercicio.json --drop');
            exec('mongoimport --db '.AppConfig::$MONGO_BASE.' --collection ficha --file mongoBase/ficha.json --drop');
            exec('mongoimport --db '.AppConfig::$MONGO_BASE.' --collection funcionario --file mongoBase/funcionario.json --drop');
            exec('mongoimport --db '.AppConfig::$MONGO_BASE.' --collection log --file mongoBase/log.json --drop');
            exec('mongoimport --db '.AppConfig::$MONGO_BASE.' --collection observacao --file mongoBase/observacao.json --drop');
            exec('mongoimport --db '.AppConfig::$MONGO_BASE.' --collection usuario --file mongoBase/usuario.json --drop');
            exec('mongoimport --db '.AppConfig::$MONGO_BASE.' --collection mails --file mongoBase/mails.json --drop');
            exec('mongoimport --db '.AppConfig::$MONGO_BASE.' --collection comunicado --file mongoBase/comunicado.json --drop');
        }

        include $bathDirectory->path.$file;
        

        
        $servicoTotalSuccess = 0;
        $servicoTotalFail = 0;
        foreach ($result as $t) {
            if($t["result"] === true)
            {
                $totalSuccess++;
                $servicoTotalSuccess++;
            }
            else
            {
                $totalFail++;
                $servicoTotalFail++;
            }
        }        
        $retorno["servicos"][$countAux] = array("nome" => $file, "testes" => $result, "totalSuccess" => $servicoTotalSuccess, "totalFail" => $servicoTotalFail);
        $countAux++;
    }
}
$retorno["totalSuccess"] = $totalSuccess;
$retorno["totalFail"] = $totalFail;

$bathDirectory -> close();

echo json_encode($retorno, JSON_UNESCAPED_UNICODE);