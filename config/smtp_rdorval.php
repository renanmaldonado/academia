<?php 
require("phpmailer/class.phpmailer.php");

//Inicia a classe PHPMailer 
$mail = new PHPMailer();
		
//Define os dados do servidor e tipo de conex�o 
$mail->IsSMTP(); // Define que a mensagem ser� SMTP
$mail->Host = "smtp.rdorval.com"; // Endere�o do servidor SMTP
$mail->SMTPAuth = true; // Autentica��o
$mail->Username = 'suporte@rdorval.com'; // Usu�rio do servidor SMTP
$mail->Password = 'dorval5850'; // Senha da caixa postal utilizada
		
//Define os dados t�cnicos da Mensagem 
$mail->IsHTML(true); // Define que o e-mail ser� enviado como HTML
$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)

$mail->From = 'suporte@rdorval.com' 
?>