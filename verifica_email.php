<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php 
//Validação do módulo

$atual = ($_GET['atual'] == '')? "": " AND email <> '".$_GET['atual']."'";
$email = $_GET['email'];

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT * FROM aluno WHERE email = '$email' $atual";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

if($totalRows_ver > 0)
{
	echo 'false';
}	
else
{
	echo 'true';
}
?>