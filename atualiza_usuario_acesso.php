<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php 
//Validação do módulo
$fvar2 = 1;
require_once('verifica.php'); 
$var1 = $_GET['cod_usuario'];
$var2 = $_GET['cod_modulo'];

//Validação da permissão
if($alteracao == "N")
{
	echo $mensg = "<script>alert('Você não tem permissão para alteração neste módulo')</script>";
	echo $mensg = "<script>window.close();</script>";
	echo $mensg = "<script>history.back();</script>";
	exit;
}
?>
<?php
mysql_select_db($database_conecta, $conecta);
$query_atualiza = "SELECT * FROM modulos_usuario WHERE cod_usuario = '$var1' AND cod_modulo = '$var2'";
$atualiza = mysql_query($query_atualiza, $conecta) or die(mysql_error());
$row_atualiza = mysql_fetch_assoc($atualiza);
$totalRows_atualiza = mysql_num_rows($atualiza);
?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE modulos_usuario SET cadastro=%s, alteracao=%s, exclusao=%s, visualizacao=%s, exportacao=%s WHERE cod_modulo=%s AND cod_usuario=%s",
                       GetSQLValueString(isset($_POST['cadastro']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString(isset($_POST['alteracao']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString(isset($_POST['exclusao']) ? "true" : "", "defined","'S'","'N'"),
					   GetSQLValueString(isset($_POST['visualizacao']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString(isset($_POST['exportacao']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString($_POST['cod_modulo'], "int"),
                       GetSQLValueString($_POST['cod_usuario'], "int"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($updateSQL, $conecta) or die(mysql_error());

  $updateGoTo = "usuario_modulos.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
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
<form method="POST" name="form1" action="<?php echo $editFormAction; ?>" onSubmit="return Validaform();">
    <table width="551" border="0" align="center" cellpadding="0" cellspacing="0" class="fundoform">
    <!--DWLayoutTable-->
    <tr valign="middle"> 
      <td height="34" colspan="3"><div align="center" class="titulo">CADASTRO DE ACESSOS </div></td>
    </tr>
    <tr> 
      <td width="18" height="200">&nbsp;</td>
      <td width="516" valign="top">

  <table align="center" class="detalhe">
    <tr valign="middle">
      <td width="85" height="22" align="right" nowrap>Cadastro:</td>
      <td width="294"><input <?php if (!(strcmp($row_atualiza['cadastro'],"S"))) {echo "checked";} ?> type="checkbox" name="cadastro" value="" ></td>
    </tr>
    <tr valign="middle">
      <td height="29" align="right" nowrap>Altera&ccedil;&atilde;o:</td>
      <td><input <?php if (!(strcmp($row_atualiza['alteracao'],"S"))) {echo "checked";} ?> type="checkbox" name="alteracao" value="" ></td>
    </tr>
    <tr valign="middle">
      <td height="28" align="right" nowrap>Exclus&atilde;o:</td>
      <td><input <?php if (!(strcmp($row_atualiza['exclusao'],"S"))) {echo "checked";} ?> type="checkbox" name="exclusao" value="" ></td>
    </tr>
    <tr valign="middle">
      <td height="26" align="right" nowrap>Visualiza&ccedil;&atilde;o:</td>
      <td><input <?php if (!(strcmp($row_atualiza['visualizacao'],"S"))) {echo "checked";} ?> type="checkbox" name="visualizacao" value="" ></td>
    </tr>
    <tr valign="middle">
      <td height="26" align="right" nowrap>Exporta&ccedil;&atilde;o:</td>
      <td><input <?php if (!(strcmp($row_atualiza['exportacao'],"S"))) {echo "checked";} ?> type="checkbox" name="exportacao" value="" ></td>
    </tr>
    <tr valign="middle">
      <td align="right" nowrap>&nbsp;</td>
      <td>      <input type="hidden" name="MM_insert" value="form1">
        <input type="hidden" name="dt_alteracao" value="<?php echo date("Y-m-d H:i:s"); ?>" size="32">
        <input type="hidden" name="cod_usuario" value="<?php echo $_GET['cod_usuario']; ?>" size="32">
        <input type="hidden" name="cod_alteracao" value="<?php echo $_SESSION['id']; ?>" size="32">
        <input name="cod_modulo" type="hidden" id="cod_modulo" value="<?php echo $row_atualiza['cod_modulo']; ?>"></td>
    </tr>
  </table>
<div align="center"> 
          <BR>
          <input name="submit" type="submit" value="Salvar" class="b-salvar">
          <input type="button" name="btnClear" class="b-cancelar voltar" value="Cancelar">
          <input type="hidden" name="MM_update" value="form1">
          <br>
          <br>
</div></td>
      <td width="15">&nbsp;</td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<script src="js/jquery.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/scripts.js"></script>

</body>
</html>
<?php
mysql_free_result($atualiza);
?>
