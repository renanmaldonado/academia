<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php //require_once("funcoes/verifica_cpf.php"); ?>
<?php 
//Validação do módulo
$fvar2 = 10;
require_once('verifica.php'); 
$_SESSION['cod'] = '';

//Validação da permissão
if($cadastro == "N")
{
	echo $mensg = "<script>alert('Você não tem permissão para cadastro neste módulo')</script>";
	echo $mensg = "<script>window.close();</script>";
	echo $mensg = "<script>history.back();</script>";
	exit;
}

mysql_select_db($database_conecta, $conecta);
$query_lista = "SELECT * FROM aluno WHERE status = 'S'";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);
?>
<?php
if($_REQUEST['cod'] == 1)
{
	do{
	?>
		<input name="para[]" type="checkbox" value="<?php echo $row_lista['email']; ?>"> <?php echo $row_lista['nome_aluno']; ?>
	<?php 
	}while($row_lista = mysql_fetch_assoc($lista));
}
else
{
?>
	Todos
<?php
}
?>