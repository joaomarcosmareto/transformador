<?php

require "../../autoload.php";

use core\DAO\USUARIODAO;
use core\util\ConexaoBD;

$publicToken = "21994547a31ea765814.36886591";
$timestamp = "123";

$con = ConexaoBD::getInstance("localhost", "root", '', "workout");

$USUARIODAO = new USUARIODAO($con); 
$usuarios = $USUARIODAO->retornaTodos(["publicToken"], [$publicToken], ["="], " limit 1");

echo sha1($timestamp.$usuarios[0]->getPrivateToken());
