<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php 
//Valida��o do m�dulo
$fvar2 = 2;
require_once('verifica.php'); 
$var1 = $_GET['cod_aluno_pacote_config'];
$var2 = $_GET['url'];

//Valida��o da permiss�o
if($exclusao == "N")
{
	echo $mensg = "<script>alert('Voc� n�o tem permiss�o para excluir neste m�dulo')</script>";
	echo $mensg = "<script>window.close();</script>";
	echo $mensg = "<script>history.back();</script>";
	exit;
}

  $deleteSQL = "DELETE FROM aluno_pacote_config WHERE cod_aluno_pacote_config = '$var1'";
  
  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($deleteSQL, $conecta) or die(mysql_error());

  if(isset($var2) && ($var2 != ''))
  {
  	$deleteGoTo = $var2;
  }
  else
  {
	$deleteGoTo = "lista_aluno_aula_config.php";  
  }
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
?>
