<?php 
require("phpmailer/class.phpmailer.php");

//Inicia a classe PHPMailer 
$mail = new PHPMailer();
		
//Define os dados do servidor e tipo de conexão 
$mail->IsSMTP(); // Define que a mensagem será SMTP
$mail->Host = "smtp.ucob2.org.br"; // Endereço do servidor SMTP
$mail->SMTPAuth = true; // Autenticação
$mail->SMTPSecure = "tls";
$mail->Username = 'noreply@ucob2.org.br'; // Usuário do servidor SMTP
$mail->Password = 'B9cB5fS74n'; // Senha da caixa postal utilizada
		
//Define os dados técnicos da Mensagem 
$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)

$mail->From = 'noreply@ucob2.org.br' 
?>