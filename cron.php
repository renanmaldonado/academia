<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php 
unset($_SESSION['sql']); 

//Filtrar por nome
$var = $_REQUEST['cod_msg'];
if($var == '')
{		
$sql_lista = "(SELECT *
			  FROM emails_enviados
			  WHERE agendamento <= DATE(NOW())
			  AND todo_dia = 'S')
			  union
			  (SELECT *
			  FROM emails_enviados
			  WHERE agendamento = DATE(NOW())
			  AND todo_dia = 'N')";
}
else
{
$sql_lista = "SELECT *
			  FROM emails_enviados
			  WHERE cod_msg = '$var'";
}
$_SESSION['sql']= $sql_lista;

mysql_select_db($database_conecta, $conecta);
$query_lista = $sql_lista;
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);
?>
<?php
require 'plugin/PHPMailer-master/PHPMailerAutoload.php';

if($totalRows_lista > 0){
	do{
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	//Set who the message is to be sent from
	$mail->setFrom('vilamada@pilatesvilamada.com.br', 'Pilates Vila Madá');
	//Set who the message is to be sent to
	
	if($row_lista['para'] == '')
	{
		mysql_select_db($database_conecta, $conecta);
		$query_contato = "SELECT * FROM aluno WHERE status = 'S' AND cod_aluno IN (7,8)";
		$contato = mysql_query($query_contato, $conecta) or die(mysql_error());
		$row_contato = mysql_fetch_assoc($contato);
		$totalRows_contato = mysql_num_rows($contato);
		
		do{
			$mail->addAddress($row_contato['email'], $row_contato['nome_aluno']);	
		}while($row_contato = mysql_fetch_assoc($contato));
	}
	else
	{
		$ex = explode("; ", $row_lista['para']);
		$c = count($ex);
		for($i = 0; $i < $c; $i++)
		{
			$mail->addAddress($ex[$i]);
		}
	}
	//Set the subject line
	$mail->Subject = $row_lista['titulo'];
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->Body = $row_lista['msg'];
	$mail->IsHTML(true); 
	
	
	//send the message, check for errors
	if (!$mail->send()) {
		echo "Erro ao tentar enviar e-mail: " . $mail->ErrorInfo;
	} else {
		echo "E-mail enviado com sucesso.";
		mysql_query("UPDATE emails_enviados SET dt_enviado=NOW() WHERE cod_msg = '".$row_lista['cod_msg']."'");
	}
	}while($row_lista = mysql_fetch_assoc($lista));
}
