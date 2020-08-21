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
  $updateSQL = sprintf("UPDATE usuario SET nome=%s, telefone=%s, email=%s, login=%s WHERE cod_usuario=%s",
                       GetSQLValueString($_POST['nome'], "text"),
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.numeric.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="js/jquery.maskMoney.js"></script>
<script src='plugin/fullcalendar/lib/moment.min.js'></script>
<script src='plugin/fullcalendar/fullcalendar.min.js'></script>
<script src='plugin/fullcalendar/lang/pt-br.js' charset="iso-8859-1"></script>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1" >
    <table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="fundoform">
    <!--DWLayoutTable-->
    <tr valign="middle"> 
      <td height="34" colspan="3"><div align="center" class="titulo">CADASTRAR USU&Aacute;RIO</div></td>
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
      <td><input name="nome" type="text" id="nome" value="<?php echo $row_ver['nome']; ?>" size="60" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap>Telefone:</td>
      <td><input name="telefone" type="text" class="telefone" id="telefone" value="<?php echo $row_ver['telefone']; ?>" size="15" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap>E-mail:</td>
      <td><input name="email" type="text" value="<?php echo $row_ver['email']; ?>" size="60" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap>Login:</td>
      <td><input name="login" type="text" value="<?php echo $row_ver['login']; ?>" size="32" ></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" name="salvar" id="salvar" value="Salvar" class="b-salvar">
        <input name="cancelar" type="button" class="b-cancelar voltar" id="cancelar" value="Cancelar"></td>
    </tr>
  </table></td>
      <td width="15">&nbsp;</td>
    </tr>
  </table>
    <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp;</p>
<script>
$(function(){
	
	$("#form1").validate({
		rules: {
			nome: {
				required: true
			},
			login: {
				required: true,
				remote: "verifica_usuario.php?cod=" + $('#cod_usuario').val() + ""
			},
			email: {
				email: true
			}
		},
		messages: {
			nome: {
				required: "Digite o nome do usuário."	
			},
			login: {
				required: "Digite um login.",
				remote: "Esse login já existe."
			},
			email: {
				email: "Digite um e-mail válido!"
			}
		}
	});
	
});
</script>
<script src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($ver);
?>
