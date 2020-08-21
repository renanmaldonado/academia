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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO modulos_usuario (cod_modulo, cod_usuario, cadastro, alteracao, exclusao, visualizacao, exportacao, cod_cadastro, dt_cadastro) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['cod_modulo'], "int"),
                       GetSQLValueString($_POST['cod_usuario'], "int"),
                       GetSQLValueString(isset($_POST['cadastro']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString(isset($_POST['alteracao']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString(isset($_POST['exclusao']) ? "true" : "", "defined","'S'","'N'"),
					   GetSQLValueString(isset($_POST['visualizacao']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString(isset($_POST['exportacao']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString($_POST['cod_cadastro'], "int"),
                       GetSQLValueString($_POST['dt_cadastro'], "date"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($insertSQL, $conecta) or die(mysql_error());

  $insertGoTo = "usuario_modulos.php?cod_usuario=$var1";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_conecta, $conecta);
$query_modulos = "SELECT m.* FROM modulos m
				  WHERE m.cod_modulo NOT IN(SELECT mu.cod_modulo FROM modulos_usuario mu WHERE mu.cod_modulo = m.cod_modulo AND mu.cod_usuario = '$var1')
				  ORDER BY m.nome_modulo ASC";
$modulos = mysql_query($query_modulos, $conecta) or die(mysql_error());
$row_modulos = mysql_fetch_assoc($modulos);
$totalRows_modulos = mysql_num_rows($modulos);
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
<form method="post" name="form1" id="form1" action="<?php echo $editFormAction; ?>" onSubmit="return Validaform();">
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
      <td width="85" align="right" nowrap>M&oacute;dulo:</td>
      <td width="294"><select name="cod_modulo" id="cod_modulo">
        <option value="">Selecione o módulo...</option>
        <?php
do {  
?>
        <option value="<?php echo $row_modulos['cod_modulo']?>"><?php echo $row_modulos['nome_modulo']?></option>
        <?php
} while ($row_modulos = mysql_fetch_assoc($modulos));
  $rows = mysql_num_rows($modulos);
  if($rows > 0) {
      mysql_data_seek($modulos, 0);
	  $row_modulos = mysql_fetch_assoc($modulos);
  }
?>
      </select></td>
    </tr>
    <tr valign="middle">
      <td height="22" align="right" nowrap>Cadastro:</td>
      <td><input name="cadastro" type="checkbox" value="" checked="CHECKED" ></td>
    </tr>
    <tr valign="middle">
      <td height="29" align="right" nowrap>Altera&ccedil;&atilde;o:</td>
      <td><input name="alteracao" type="checkbox" value="" checked="CHECKED" ></td>
    </tr>
    <tr valign="middle">
      <td height="28" align="right" nowrap>Exclus&atilde;o:</td>
      <td><input name="exclusao" type="checkbox" value="" checked="CHECKED" ></td>
    </tr>
    <tr valign="middle">
      <td height="26" align="right" nowrap>Visuzaliza&ccedil;ao:</td>
      <td><input name="visualizacao" type="checkbox" value="" checked="CHECKED" ></td>
    </tr>
    <tr valign="middle">
      <td height="26" align="right" nowrap>Exporta&ccedil;&atilde;o:</td>
      <td><input name="exportacao" type="checkbox" value="" checked="CHECKED" ></td>
    </tr>
    <tr valign="middle">
      <td align="right" nowrap>&nbsp;</td>
      <td>      <input type="hidden" name="MM_insert" value="form1">
        <input type="hidden" name="dt_cadastro" value="<?php echo date("Y-m-d H:i:s"); ?>" size="32">
        <input type="hidden" name="dt_alteracao" value="<?php echo date("Y-m-d H:i:s"); ?>" size="32">
        <input type="hidden" name="cod_usuario" value="<?php echo $_GET['cod_usuario']; ?>" size="32">
        <input type="hidden" name="cod_cadastro" value="<?php echo $_SESSION['id']; ?>" size="32">
        <input type="hidden" name="cod_alteracao" value="<?php echo $_SESSION['id']; ?>" size="32"></td>
    </tr>
  </table>
<div align="center"> 
          <BR>
          <input name="submit" type="submit" value="Salvar" class="b-salvar">
          <input type="button" name="btnClear" class="b-cancelar voltar" value="Cancelar">
          <input type="hidden" name="MM_insert" value="form1">
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
<script>
$("#form1").validate({
	rules: {
		cod_modulo: {
			required: true	
		}
	},
	messages: {
		cod_modulo: {
			required: "Selecione um módulo."	
		}
	}
});
</script>
<script src="js/scripts.js"></script>

</body>
</html>
<?php
mysql_free_result($modulos);
?>

