<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function send_email($name, $address, $subject, $message)
{
    require_once("phpmailer/class.phpmailer.php");

    $mail = new PHPMailer();

    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = "mail.rhsuite.com.br";
    $mail->Port = 587;
    $mail->Username = "sistema@rhsuite.com.br";
    $mail->Password = "C0qmisZyn@HO";

    $mail->SetFrom('sistema@rhsuite.com.br', 'RhSuite');

    $mail->Subject = utf8_decode($subject);
    $mail->MsgHTML(utf8_decode($message));

    $mail->AddAddress($address, utf8_decode($name));

    if ($mail->Send()) {
        return 1;
    } else {
        return 0;
    }
}

function send_email_faleConosco($nome, $remetente, $assunto, $mensagem)
{
    require_once("phpmailer/class.phpmailer.php");

    $mail = new PHPMailer();
    $mail->IsSMTP(); //Definimos que usaremos o protocolo SMTP para envio.
    $mail->SMTPAuth = true; //Habilitamos a autenticação do SMTP. (true ou false)
    $mail->Host = "mail.peoplenetcorp.com.br"; //Podemos usar o servidor do gMail para enviar.
    $mail->Port = 587; //Estabelecemos a porta utilizada pelo servidor do gMail.
    $mail->Username = "sistema@peoplenetcorp.com.br"; //Usuário do email
    $mail->Password = "sistema@314"; //Senha do email
    $mail->SetFrom($remetente, utf8_decode($nome));

    // Cópia
    $mail->AddCC('suporte@peoplenetcorp.com.br', 'RhSuite - Suporte');
    $mail->AddCC('financeiro@peoplenetcorp.com.br', 'RhSuite - Financeiro');

    $mail->Subject = utf8_decode($assunto);
    $mail->MsgHTML(utf8_decode($mensagem));

    $mail->AddAddress('contato@peoplenetcorp.com.br', 'RhSuite - Contato');

    if ($mail->Send()) {
        return 1;
    } else {
        return 0;
    }
}
