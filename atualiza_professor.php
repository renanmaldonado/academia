<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php 
//Validação do módulo
$fvar2 = 4;
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
  $updateSQL = sprintf("UPDATE professor SET email=%s, nome_professor=%s, telefone=%s, status=%s WHERE cod_professor=%s",
                       GetSQLValueString($_POST['email'], "text"),
					   GetSQLValueString($_POST['nome_professor'], "text"),
                       GetSQLValueString($_POST['telefone'], "text"),
                       GetSQLValueString(isset($_POST['status']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString($_POST['cod_professor'], "int"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($updateSQL, $conecta) or die(mysql_error());

  $updateGoTo = "lista_professor.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_ver = "-1";
if (isset($_GET['cod_professor'])) {
  $colname_ver = $_GET['cod_professor'];
}
mysql_select_db($database_conecta, $conecta);
$query_ver = sprintf("SELECT * FROM professor WHERE cod_professor = %s", GetSQLValueString($colname_ver, "int"));
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
<form method="POST" name="form1" id="form1" action="<?php echo $editFormAction; ?>">
  <table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="fundoform">
    <!--DWLayoutTable-->
    <tr valign="middle">
      <td colspan="3"><div align="center" class="titulo">CADASTRAR DE PROFESSOR</div></td>
    </tr>
    <tr>
      <td width="18">&nbsp;</td>
      <td width="516" valign="top"><table width="100%" align="center" class="detalhe">
        <tr valign="baseline">
          <td width="22%" align="right" valign="middle" nowrap>Nome do professor:</td>
          <td width="78%" valign="middle"><input type="text" name="nome_professor" id="nome_professor" value="<?php echo $row_ver['nome_professor']; ?>" size="60"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Telefone:</td>
          <td valign="middle"><input type="text" name="telefone" id="telefone" class="telefone" value="<?php echo $row_ver['telefone']; ?>" size="15"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>E-mail:</td>
          <td valign="middle"><input name="email" type="text" id="email" size="60" value="<?php echo $row_ver['email']; ?>"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Status:</td>
          <td valign="middle"><input name="status" type="checkbox" id="status" value="S" <?php if (!(strcmp($row_ver['status'],"S"))) {echo "checked=\"checked\"";} ?>></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right"><input name="cod_professor" type="hidden" id="cod_professor" value="<?php echo $row_ver['cod_professor']; ?>"></td>
          <td valign="middle"><input type="submit" class="b-salvar" value="Salvar">
            <input name="cancelar" type="button" class="voltar b-cancelar" id="cancelar" value="Cancelar"></td>
        </tr>
      </table></td>
      <td width="15">&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<script>
$(function(event){
	$("#form1").validate({
		rules: {
			nome_professor: {
				required: true
			},
			email: {
				email: true
			}
		},
		messages: {
			nome_professor: {
				required: "Digite o nome do professor."	
			},
			email: {
				email: "Digite um e-mail válido!"
			}
		}
	});
	
});
</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($ver);
?>
