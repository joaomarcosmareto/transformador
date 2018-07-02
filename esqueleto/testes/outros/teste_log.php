<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#">
    
    <head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" >
    </head>

    <body>
<?php
include_once "../core/Logger.php";
include_once '../core/dominio/ConexaoBD.php';
try{
	$con = ConexaoBD::getInstance("localhost", "root", '', "workout");
	Logger::salvar($con, "COD01");
}catch(Exception $e){
	Logger::salvar(null, "COD01");
}
?>
    </body>
</html>