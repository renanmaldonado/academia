<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
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
?>
<?php
$var = $_GET['cod_msg'];
mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT *
			  FROM emails_enviados
			  WHERE cod_msg = '$var'";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<noscript>
  <meta http-equiv="Refresh" content="1; url=javascript.php">
</noscript>
<title>INTRANET</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="cache-control"   content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<link href="plugin/datatables/media/css/main.css" rel="stylesheet" type="text/css">
<link href="css/intranet.css" rel="stylesheet" type="text/css">
<script src="js/jquery.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.maskedinput.js"></script>
<script src="js/scripts.js"></script>
<script>
$(function(){
	
	$("#form1").validate({
		rules: {
			nome_aluno: {
				required: true
			},
			email: {
				email: true
			}
		},
		messages: {
			nome_aluno: {
				required: "Digite o nome do aluno."	
			},
			email: {
				email: "Digite um e-mail válido!"
			}
		}
	});
	
});
</script>

</head>
<body>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1" >
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="fundoform">
    <!--DWLayoutTable-->
    <tr valign="middle">
      <td height="34" colspan="3"><div align="center" class="titulo"><?php echo $row_ver['titulo']; ?></div></td>
    </tr>
    <tr>
      <td height="14">&nbsp;</td>
      <td valign="top">Para: <?php echo ($row_ver['para'] == '')? "Todos" : $row_ver['para']; ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="18" height="200">&nbsp;</td>
      <td width="516" valign="top"><table width="100%" align="center" class="detalhe">
        <tr valign="baseline">
          <td width="22%"nowrap><?php echo $row_ver['msg']; ?></td>
          </tr>
      </table></td>
      <td width="15">&nbsp;</td>
    </tr>
    <tr>
      <td height="17">&nbsp;</td>
      <td align="center" valign="top"><input name="button" type="button" onClick="javascript:window.close()" class="b-fechar fechar" id="button" value="Sair"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input name="cod_aluno" type="hidden" id="cod_aluno" value="<?php echo $row_ver['cod_aluno']; ?>">
  <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($ver);
?>
