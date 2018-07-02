<?php


//require '../../vendor/autoload.php';
require '../../autoload.php';

use core\util\AppMail;

$a = AppMail::PHPMailerFactory();

$mail = new PHPMailer;

$mail->SMTPDebug = 3;                               // Enable verbose debug output

$Host = 'smtp.gmail.com';
$Username = 'workoutmobilemail@gmail.com';
$Password = '123abC@@';

//        $Username = 'maretoinformatica@gmail.com';
//        $Password = '44eHdiWylTg6SK';
//        
$Port = "587";

$mail->CharSet = 'UTF-8';
$mail->IsSMTP(); 
$mail->Host = $Host; 
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
$mail->SMTPAuth = true; 
$mail->SMTPSecure = 'tls';
$mail->Port = $Port; 
$mail->Username = $Username; 
$mail->Password = $Password; 

$mail->SetFrom('workoutmobilemail@gmail.com', "Easy Treino");
$mail->addReplyTo('workoutmobilemail@gmail.com', "Easy Treino");

$mail->addAddress("ciro_xm@yahoo.com.br");
$mail->msgHTML("ASDASDAS");

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}