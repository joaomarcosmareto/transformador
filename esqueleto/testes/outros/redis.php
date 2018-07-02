<?php
//Connecting to Redis server on localhost
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->auth("LIb62102n");


echo "Connection to server sucessfully";
echo "<br/>Server is running: ".$redis->ping();
   
$redis->set("counter", 0);
echo "<br/>C: ".$redis->get("counter");
$redis->incr("counter");
echo "<br/>C: ".$redis->get("counter");
$redis->incr("counter");
echo "<br/>C: ".$redis->get("counter");

//intervalo de 1s, maximo de 1 requisição
$deltaT1 = 1;//no appconfig botar valores bem altos
$limite1 = 1;

//intervalo de 60s, maximo de 30 requisição
$deltaT2 = 60;
$deltaT2 = 30;

$t = time();//tempo em s
$IP = $_SERVER['REMOTE_ADDR'];
echo '<br>1:'.requestLimitControl($redis, $IP, $deltaT1, $limite1);

//erros a cada x min
$email = "ciro@ifes.edu.br";
$deltaLogin = 900;
$limiteLogin = 5;
//echo '<br>2:'.requestLimitControl($redis, "BAD_".$email, $deltaLogin, $limiteLogin);



function requestLimitControl($redis, $keyBase, $deltaTempo, $limitContador)
{
    $t = time();//tempo em s
    
    $tRedis = $redis->get($keyBase."_T");    
    if(empty($tRedis))
    {
        $redis->setex($keyBase."_T", $deltaTempo, $t);
        $redis->setex($keyBase."_C", $deltaTempo, 1);
    }
    else
    {
        if($t > $tRedis+$deltaTempo)
        {
            $redis->setex($keyBase."_T", $deltaTempo, $t);
            $redis->setex($keyBase."_C", $deltaTempo, 1);
        }
        else
        {
            $redis->incr($keyBase."_C");
            if($redis->get($keyBase."_C") > $limitContador)
            {
                header('HTTP/ 429 Too Many Requests', false, 429);
                exit();
//                return false;                
            }
        }
    }
//    $redis->close();
    return true;
}


/*

IP_T 00:00:00:00
IP_C 0

IP_T 00:00:00:01
IP_C 1
  

..

IP_T 00:00:01:00
IP_C 50

quando chegar no limite do T + delta zerar o tempo e o contador

se o tempo for menor que T+delta e tiver passado o limite do truput (C) entao começar a negar

o delta e limite pode ser configurado por serviço, login e cadastro tem q ser mais limitado


*/