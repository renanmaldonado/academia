<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php 
//Validação do módulo
$fvar2 = 1;
require_once('verifica.php'); 

//Validação da permissão
if($alteracao == "N")
{
	echo $mensg = "<script>alert('Você não tem permissão para atualizar neste módulo')</script>";
	echo $mensg = "<script>window.close();</script>";
	echo $mensg = "<script>history.back();</script>";
	exit;
}
?>
<?php
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
  $updateSQL = sprintf("UPDATE usuario SET nome=%s, cep=%s, endereco=%s, num_endereco=%s, bairro=%s, cidade=%s, estado=%s, telefone=%s, email=%s, login=%s WHERE cod_usuario=%s",
                       GetSQLValueString($_POST['nome'], "text"),
                       GetSQLValueString($_POST['cep'], "text"),
                       GetSQLValueString($_POST['endereco'], "text"),
                       GetSQLValueString($_POST['num_endereco'], "int"),
                       GetSQLValueString($_POST['bairro'], "text"),
                       GetSQLValueString($_POST['cidade'], "text"),
                       GetSQLValueString($_POST['estado'], "text"),
                       GetSQLValueString($_POST['telefone'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['login'], "text"),
                       GetSQLValueString($_POST['cod_usuario'], "int"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($updateSQL, $conecta) or die(mysql_error());

  $updateGoTo = "lista_usuario.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT * FROM usuario WHERE cod_usuario = '".$_GET['cod_usuario']."'";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

$_SESSION['cod'] = $row_ver['cod_usuario'];
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

</head>
<body>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1" >
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="fundoform">
    <!--DWLayoutTable-->
    <tr valign="middle"> 
      <td height="34" colspan="3"><div align="center" class="titulo">VISUALIZAR USU&Aacute;RIO</div></td>
    </tr>
    <tr> 
      <td width="18" height="200">&nbsp;</td>
      <td width="516" valign="top"><table width="100%" align="center" class="detalhe">
    <tr valign="baseline">
      <td width="96" align="right" nowrap>&nbsp;</td>
      <td width="365"><input type="hidden" name="cod_usuario" id="cod_usuario" value="<?php echo $row_ver['cod_usuario']; ?>" size="32">
        <input type="hidden" name="cod_autor" value="<?php echo $_SESSION['id']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap>Nome completo:</td>
      <td><?php echo $row_ver['nome']; ?></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap>Telefone:</td>
      <td><?php echo $row_ver['telefone']; ?></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap>E-mail:</td>
      <td><?php echo $row_ver['email']; ?></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap>Login:</td>
      <td><?php echo $row_ver['login']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input name="cancelar" type="button" class="b-fechar fechar" id="cancelar" value="Fechar"></td>
    </tr>
  </table></td>
      <td width="15">&nbsp;</td>
    </tr>
  </table>
    <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp;</p>
<script src="js/jquery.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.maskedinput.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($ver);
?>
