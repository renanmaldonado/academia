<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php 
//Validação do módulo
$fvar2 = 2;
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
$var = $_GET['cod_aluno'];

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE aluno SET nome_aluno=%s, telefone=%s, email=%s, status=%s WHERE cod_aluno=%s",
                       GetSQLValueString($_POST['nome_aluno'], "text"),
                       GetSQLValueString($_POST['telefone'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString(isset($_POST['status']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString($_POST['cod_aluno'], "int"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($updateSQL, $conecta) or die(mysql_error());

  $updateGoTo = "lista_aluno.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT * FROM aluno WHERE cod_aluno = '$var'";
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
      <td height="34" colspan="3"><div align="center" class="titulo">ATUALIZAR ALUNO</div></td>
    </tr>
    <tr>
      <td width="18" height="200">&nbsp;</td>
      <td width="516" valign="top"><table width="100%" align="center" class="detalhe">
        <tr valign="baseline">
          <td width="22%" align="right" valign="middle" nowrap>Nome do aluno:</td>
          <td width="78%" valign="middle"><?php echo $row_ver['nome_aluno']; ?></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Data de nascimento:</td>
          <td valign="middle"><?php echo $row_ver['dt_vencimento']; ?></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Endere&ccedil;o:</td>
          <td valign="middle"><?php echo $row_ver['endereco']; ?> N&ordm; <?php echo $row_ver['numero']; ?></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Bairro:</td>
          <td valign="middle"><?php echo $row_ver['bairro']; ?></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Profiss&atilde;o:</td>
          <td valign="middle"><?php echo $row_ver['profissao']; ?></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Telefone:</td>
          <td valign="middle"><?php echo $row_ver['telefone']; ?></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Telefone comercial:</td>
          <td valign="middle"><?php echo $row_ver['tel_comercial']; ?></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Telefone residencial:</td>
          <td valign="middle"><?php echo $row_ver['tel_residencial']; ?></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Como conheceu?</td>
          <td valign="middle"><?php echo $row_ver['como_conheceu']; ?> Outros: <?php echo $row_ver['outros']; ?></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>E-mail:</td>
          <td valign="middle"><?php echo $row_ver['email']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td valign="middle"><input type="button" class="b-cadastro" value="Alterar dados" onClick="Url('atualiza_aluno.php?cod_aluno=<?php echo $row_ver['cod_aluno']; ?>')"></td>
        </tr>
      </table></td>
      <td width="15">&nbsp;</td>
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
