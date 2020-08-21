<?php
require 'plugin/PHPMailer-master/class.phpmailer.php';


$mail = new PHPMailer();
$mail->IsMail();

foreach($_POST['para'] as $para){
	$mail->AddAddress("$para");
}
$mail->Subject = "Test 1";
$mail->Body = "Test 1 of PHPMailer.";

if(!$mail->Send())
{
   echo "Error sending: " . $mail->ErrorInfo;;
}
else
{
   echo "Letter sent";
}