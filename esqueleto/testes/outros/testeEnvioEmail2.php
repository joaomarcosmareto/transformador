<?php

require '../../vendor/autoload.php';

//use PHPMailer;

$mail = new PHPMailer();
        
$Host = 'localhost';
$Username = 'aeouser@localhost';
$Password = 'workout2';

$Port = "25";

$mail->CharSet = 'UTF-8';
$mail->IsSMTP(); 
$mail->Host = $Host; 
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';
$mail->SMTPAuth = false; 
//$mail->SMTPSecure = 'tls';
$mail->SMTPSecure = 'tls';
$mail->Port = $Port; 
$mail->Username = $Username; 
$mail->Password = $Password; 
$mail->isMail();

$mail->SetFrom('noreply@easytreino.com', "Easy Treino");
$mail->addReplyTo('noreply@easytreino.com', "Easy Treino");

$mail->AddAddress("ciro_xm@yahoo.com.br");
//$mail->AddAddress("ciro.xm@gmail.com");
//$mail->AddAddress("joaomarcosmareto@gmail.com");
$mail->Subject = "Email de teste do easytreino 3";
$mail->MsgHTML(nl2br("Olá João, Segue este e-mail de teste. Com algumas palabras. Att, Easy treino"));

$mail->Send();

//echo $mail->ErrorInfo();