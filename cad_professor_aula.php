<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php require_once("funcoes/formata_moeda.php"); ?>
<?php 
//Validação do módulo
$fvar2 = 4;
require_once('verifica.php'); 
$_SESSION['cod'] = '';
$var = $_REQUEST['cod_professor'];

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO professor_aula (cod_professor, cod_aula, valor_professor, valor_cobrado) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['cod_professor'], "int"),
                       GetSQLValueString($_POST['cod_aula'], "int"),
                       GetSQLValueString(limpaparaobanco($_POST['valor_professor']), "double"),
                       GetSQLValueString(limpaparaobanco($_POST['valor_cobrado']), "double"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($insertSQL, $conecta) or die(mysql_error());

  $insertGoTo = "lista_professor_aulas.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_conecta, $conecta);
$query_lista = "SELECT * FROM aula WHERE cod_aula NOT IN (SELECT cod_aula FROM professor_aula WHERE cod_professor = '$var' AND cod_aula_dia IS NULL) ORDER BY desc_aula ASC";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

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
<form method="post" name="form1" id="form1" action="<?php echo $editFormAction; ?>">
  <table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="fundoform">
    <!--DWLayoutTable-->
    <tr valign="middle">
      <td colspan="3"><div align="center" class="titulo">CADASTRAR DE PROFESSOR</div></td>
    </tr>
    <tr>
      <td width="18">&nbsp;</td>
      <td width="516" valign="top">
      <table width="100%" align="center" class="detalhe">
    <tr valign="middle">
      <td width="29%" align="right" nowrap>Modalidade:</td>
      <td width="71%"><select name="cod_aula">
        <?php 
do {  
?>
        <option value="<?php echo $row_lista['cod_aula']?>" ><?php echo $row_lista['desc_aula']?></option>
        <?php
} while ($row_lista = mysql_fetch_assoc($lista));
?>
      </select></td>
    <tr>
    <tr valign="middle">
      <td nowrap align="right">Valor do professor:</td>
      <td><input name="valor_professor" type="text" class="moeda" id="valor_professor" value="" size="10"></td>
    </tr>
    <tr valign="middle">
      <td nowrap align="right">Valor cobrado:</td>
      <td><input name="valor_cobrado" type="text" class="moeda" id="valor_cobrado" value="" size="10"></td>
    </tr>
    <tr valign="middle">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" class="b-salvar" value="Salvar">
        <input name="button" type="button" class="b-cancelar voltar" id="button" value="Cancelar"></td>
    </tr>
  </table>
      <input type="hidden" name="cod_professor" value="<?php echo $var; ?>">
      <input type="hidden" name="MM_insert" value="form1"></td>
      <td width="15">&nbsp;</td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<script src="js/jquery.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.maskedinput.js"></script>
<script src="js/jquery.maskMoney.js"></script>
<script>
$(function(){
	
	$("#form1").validate({
		rules: {
			valor_professor: {
				required: true
			},
			valor_cobrado: {
				required: true
			}
		},
		messages: {
			valor_professor: {
				required: "Digite o valor de custo do professor."	
			},
			valor_cobrado: {
				required: "Digite o valor cobrado da aula."
			}
		}
	});
	
});
</script>
<script src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($lista);
?>
