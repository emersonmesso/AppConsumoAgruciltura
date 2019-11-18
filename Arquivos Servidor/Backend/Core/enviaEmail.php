<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require "../Config/Controller.php";
use PHPMailer\PHPMailer\PHPMailer;

require '../Config/PHPMailer/src/Exception.php';
require '../Config/PHPMailer/src/PHPMailer.php';
require '../Config/PHPMailer/src/SMTP.php';

$mysql = new Controller();
$mail = new PHPMailer(true);
// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
try{
$mail->IsSMTP(); // Define que a mensagem será SMTP
$mail->Host = "ds1.hospedam.com"; // Endereço do servidor SMTP
//$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
//$mail->Username = 'seumail@dominio.net'; // Usuário do servidor SMTP
//$mail->Password = 'senha'; // Senha do servidor SMTP



// Define o remetente
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->From = "contato@padraotorrent.com"; // Seu e-mail
$mail->FromName = "Sistema de Água"; // Seu nome
// Define os destinatário(s)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->AddAddress($mysql->user['email'], $mysql->user['nome']);


// Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
$mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)



// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->Subject  = "Relatório Aplicativo Água"; // Assunto da mensagem
$mail->Body = "O arquivo do relatório do seu sensor está disponível em anexo a este E-mail";
$mail->AltBody = "Este é o corpo da mensagem de teste, em Texto Plano! \r\n :)";
// Define os anexos (opcional)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->AddAttachment($_POST['link'], "anexo.pdf");  // Insere um anexo
// Envia o e-mail
$enviado = $mail->Send();
// Limpa os destinatários e os anexos
$mail->ClearAllRecipients();
$mail->ClearAttachments();
// Exibe uma mensagem de resultado
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}