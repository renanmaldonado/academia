<?php
$fvar1 = $_SESSION['id'];

mysql_select_db($database_conecta, $conecta);
$query_ver_modulo = "SELECT * FROM modulos_usuario WHERE cod_usuario = '$fvar1' AND cod_modulo = '$fvar2'";
$ver_modulo = mysql_query($query_ver_modulo, $conecta) or die(mysql_error());
$row_ver_modulo = mysql_fetch_assoc($ver_modulo);
$totalRows_ver_modulo = mysql_num_rows($ver_modulo);

$cadastro = $row_ver_modulo['cadastro'];
$alteracao = $row_ver_modulo['alteracao'];
$visualizacao = $row_ver_modulo['visualizacao'];
$exclusao = $row_ver_modulo['exclusao'];
$exportacao = $row_ver_modulo['exportacao'];

if($totalRows_ver_modulo == 0)
{
	echo $mensg = "<script>alert('Você não tem acesso a este módulo')</script>";
	echo $mensg = "<script>window.close();</script>";
	echo $mensg = "<script>history.back();</script>";
	exit;
}
?>
