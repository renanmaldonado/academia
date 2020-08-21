<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php
$email = $_REQUEST['email'];
$login = $_REQUEST['login'];
$cod = $_REQUEST['cod'];

if($email <> ''){
	$valor = "email = '$email'";
}

if($login <> '')
{
	$valor = "login = '$login'";	
}

if($cod <> '')
{
	$atualiza = "AND cod_usuario <> '$cod'";
}

mysql_select_db($database_conecta, $conecta);
if($cod == '')
{
	$query_visualiza = "SELECT * FROM usuario WHERE $valor $atualiza";
}
else
{
	$query_visualiza = "SELECT * FROM usuario WHERE $valor AND cod_usuario <> '$cod'";
}
$visualiza = mysql_query($query_visualiza, $conecta) or die(mysql_error());
$row_visualiza = mysql_fetch_assoc($visualiza);
$totalRows_visualiza = mysql_num_rows($visualiza);

if($totalRows_visualiza  == 0)
{
	echo 'true';
}
else
{
	echo 'false';
}
