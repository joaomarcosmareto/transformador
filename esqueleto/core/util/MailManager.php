<?php

namespace core\util;

use PHPMailer;
use core\AppConfig;
use core\dominio\Mail;
use core\util\{i18n, Mongo};
use MongoDB\BSON\ObjectID;

class MailManager {

    public static function createMailCriacaoConta(array $usuario)
    {
        MailManager::create(
            $usuario['email'],
            i18n::write("MAIL_ASSUNTO_CRIACAO_CONTA"),
            i18n::writeParams("MAIL_CORPO_CRIACAO_CONTA", null, [
                $usuario['nome'],
                AppConfig::$SERVER_NAME . "/#!/modlogin/signuppagesuccess/" . $usuario['accToken'],
                AppConfig::$SERVER_NAME . "/#!/modlogin/signuppagesuccess/" . $usuario['accToken'],
            ]),
            i18n::write("MAIL_FROM_CADASTRO_ENDERECO"),
            i18n::write("MAIL_FROM_CADASTRO_NOME"),
            i18n::write("MAIL_REPLYTO_CADASTRO_ENDERECO"),
            i18n::write("MAIL_REPLYTO_CADASTRO_NOME")
        );
    }

    public static function createMailConfirmacaoCadastro(array $usuario)
    {
        MailManager::create(
            $usuario['email'],
            i18n::write("MAIL_ASSUNTO_CONFIRMACAO_CADASTRO"),
            i18n::writeParams("MAIL_CORPO_CONFIRMACAO_CADASTRO", null, [$usuario['nome']]),
            i18n::write("MAIL_FROM_CADASTRO_ENDERECO"),
            i18n::write("MAIL_FROM_CADASTRO_NOME"),
            i18n::write("MAIL_REPLYTO_CADASTRO_ENDERECO"),
            i18n::write("MAIL_REPLYTO_CADASTRO_NOME")
        );
    }

    public static function createMailRecuperarSenha(array $usuario)
    {
        MailManager::create(
            $usuario['email'],
            i18n::write("MAIL_ASSUNTO_RECUPERAR_SENHA"),
            i18n::writeParams("MAIL_CORPO_RECUPERAR_SENHA", null, [
                $usuario['nome'],
                AppConfig::$SERVER_NAME . "/#!/modlogin/trocarsenha/" . $usuario['pwdToken'],
                AppConfig::$SERVER_NAME . "/#!/modlogin/trocarsenha/" . $usuario['pwdToken'],
            ]),
            i18n::write("MAIL_FROM_CADASTRO_ENDERECO"),
            i18n::write("MAIL_FROM_CADASTRO_NOME"),
            i18n::write("MAIL_REPLYTO_CADASTRO_ENDERECO"),
            i18n::write("MAIL_REPLYTO_CADASTRO_NOME")
        );
    }

    public static function createMailAlterarSenha(array $usuario)
    {
        MailManager::create(
            $usuario['email'],
            i18n::write("MAIL_ASSUNTO_ALTERACAO_SENHA"),
            i18n::writeParams("MAIL_CORPO_ALTERACAO_SENHA", null, [$usuario['nome'],]),
            i18n::write("MAIL_FROM_CADASTRO_ENDERECO"),
            i18n::write("MAIL_FROM_CADASTRO_NOME"),
            i18n::write("MAIL_REPLYTO_CADASTRO_ENDERECO"),
            i18n::write("MAIL_REPLYTO_CADASTRO_NOME")
        );
    }

    public static function create(string $to, string $subject, string $msg, string $from_endereco, string $from_nome, string $replyto_endereco, string $replyto_nome)
    {
        $mail = [
            '_id' => new ObjectID(),
            'to' => $to,
            'subject' => $subject,
            'msg' => $msg,
            'fromEndereco' => $from_endereco,
            'fromNome' => $from_nome,
            'replyToEndereco' => $replyto_endereco,
            'replyToNome' => $replyto_nome,

            'dataCriacao' => UtilFunctions::obterDataAtualBanco(),
            'dataAlteracao' => UtilFunctions::obterDataAtualBanco(),
            'usuarioAlteracao' => null,
            'removido' => false,
        ];
        Mongo::salvar("mail", $mail);
        MailManager::send();
    }

    // HÁ UM ERRO NESTA FUNÇÃO QUE ELA PODE NÃO ENVIAR UM EMAIL CASO TENHA UM NOVO EMAIL E ELA STEJA EXECUTANDO.
    // NESTE CASO, O NOVO EMAIL, SO SERÁ ENVIADO, QUANDO UM TERCEIRO EMAIL TIVER QUE SER ENVIADO.
    public static function send()
    {
        if(AppConfig::$SENDING_MAIL_RUNNING == false)
        {
            AppConfig::$SENDING_MAIL_RUNNING = true;

            $mails = Mongo::retornaTodos("mail");

            foreach ($mails as $mail)
            {
                $phpMailer = MailManager::PHPMailerFactory();

                $phpMailer->AddAddress($mail['to']);
                $phpMailer->Subject = $mail['subject'];
                $phpMailer->MsgHTML(nl2br($mail['msg']));
                $phpMailer->SetFrom($mail['fromEndereco'], $mail['fromNome']);
                $phpMailer->addReplyTo($mail['replyToEndereco'], $mail['replyToNome']);

                if(isset($mail["filename"]) && isset($mail["contentFile"]))
                {
                    $phpMailer->AddStringAttachment($mail["contentFile"]->getData(), $mail["filename"]);
                }

                if(AppConfig::$ENVIAR_EMAILS === true)
                {
                    if($phpMailer->Send() == false)
                    {
                        Logger::info($mail['to']."|.|".$mail['subject']."|.|".$mail['msg']."|.|".$phpMailer->ErrorInfo);
                    }
                    else
                    {
                        Mongo::deletar("mail", $mail['_id']);
                    }
                }
                else
                {
                    Mongo::deletar("mail", $mail['_id']);
                }
            }

            AppConfig::$SENDING_MAIL_RUNNING == false;
        }
    }

    public static function PHPMailerFactory()
    {
        if(AppConfig::$AMBIENTE === "DEV")
        {
            $mail = new PHPMailer();
            $mail->CharSet = 'UTF-8';
            $mail->IsSMTP(true);
            $mail->Host = AppConfig::$EMAIL_HOST;
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Port = AppConfig::$EMAIL_PORT;
            $mail->Username = AppConfig::$EMAIL_USERNAME;
            $mail->Password = AppConfig::$EMAIL_PASSWORD;
            return $mail;
        }
        if(AppConfig::$AMBIENTE === "PROD")
        {
            $mail = new PHPMailer();
            $mail->Host = AppConfig::$EMAIL_HOST;
            $mail->CharSet = 'UTF-8';
            $mail->IsSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = 'tls';
            $mail->Port = AppConfig::$EMAIL_PORT;
            $mail->Username = AppConfig::$EMAIL_USERNAME;
            $mail->Password = AppConfig::$EMAIL_PASSWORD;
            $mail->isMail();
            return $mail;
        }
    }

}