<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php 
//Validação do módulo
$fvar2 = 5;
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE aula SET desc_aula=%s, max_aluno=%s, grupo=%s, valor_mensal=%s, valor_padrao=%s WHERE cod_aula=%s",
                       GetSQLValueString($_POST['desc_aula'], "text"),
                       GetSQLValueString($_POST['max_aluno'], "int"),
                       GetSQLValueString(isset($_POST['grupo']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString(isset($_POST['valor_mensal']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString(valorbanco($_POST['valor_padrao']), "double"),
                       GetSQLValueString($_POST['cod_aula'], "int"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($updateSQL, $conecta) or die(mysql_error());

  $updateGoTo = "lista_aulas.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_ver = "-1";
if (isset($_GET['cod_aula'])) {
  $colname_ver = $_GET['cod_aula'];
}
mysql_select_db($database_conecta, $conecta);
$query_ver = sprintf("SELECT a.* FROM aula a WHERE a.cod_aula = %s", GetSQLValueString($colname_ver, "int"));
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
</head>
<body>
<form method="post" name="form2" id="form2" action="<?php echo $editFormAction; ?>">
  <table width="700" align="center" class="fundoform">
    <!--DWLayoutTable-->
    <tr valign="middle">
      <td height="34" colspan="3"><div align="center" class="titulo">ATUALIZAR MODALIDADE</div></td>
    </tr>
    <tr>
      <td width="27" height="200">&nbsp;</td>
      <td width="1232" valign="top">
  <table width="100%" align="center" class="detalhe">
    <tr valign="baseline">
      <td nowrap align="right">Modalidade:</td>
      <td><input name="desc_aula" type="text" id="desc_aula" value="<?php echo $row_ver['desc_aula']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Máximo de aluno:</td>
      <td><input name="max_aluno" type="text" class="numero" id="max_aluno" value="<?php echo $row_ver['max_aluno']; ?>" size="5"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Grupo:</td>
      <td><input name="grupo" type="checkbox" id="grupo" value="S" <?php if (!(strcmp($row_ver['grupo'],"S"))) {echo "checked=\"checked\"";} ?>></td>
    </tr>
    <tr valign="baseline" class="grupo">
      <td align="right" valign="middle" nowrap>Valor cobrado mensalmente?</td>
      <td align="left" valign="middle"><input type="checkbox" name="valor_mensal" id="valor_mensal" <?php if (!(strcmp($row_ver['valor_mensal'],"S"))) {echo "checked=\"checked\"";} ?>></td>
    </tr>
    <tr valign="baseline" class="grupo">
      <td align="right" valign="middle" nowrap>Valor padr&atilde;o:</td>
      <td align="left" valign="middle"><input name="valor_padrao" type="text" class="moeda" value="<?php echo moeda_br($row_ver['valor_padrao']); ?>"></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" class="b-salvar" value="Salvar">
      
        <?php if(($alteracao == "S") && ($row_ver['grupo'] == 'S') ){ ?>
        <input name="button2" type="button" class="b-novo" id="button2" onClick="Url('cad_aula_dia.php?cod_aula=<?php echo $row_ver['cod_aula']; ?>')" value="Dias da aula">
        <?php } ?>
        <input name="button" type="button" class="b-voltar" id="button" value="Voltar"></td>
    </tr>
  </table>
  <input type="hidden" name="cod_aula" value="<?php echo $row_ver['cod_aula']; ?>">
  <input type="hidden" name="MM_update" value="form2">
  <input type="hidden" name="cod_aula" value="<?php echo $row_ver['cod_aula']; ?>">
</td>
      <td width="28">&nbsp;</td>
    </tr>
  </table>
</form>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="js/jquery.maskMoney.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.numeric.js"></script>
<script>
$(document).ready(function(){
	
	$(".grupo").hide();
	
	var input = $( "#grupo:checked" ).val();
		
		if(input == 'S')
		{
			$(".grupo").show();
		}
		else
		{
			$(".grupo").hide();
		}
		
	$( "#grupo" ).on( "click", function() {
		var input = $( "#grupo:checked" ).val();
		
		if(input == 'S')
		{
			$(".grupo").show();
		}
		else
		{
			$(".grupo").hide();
		}
	});
	
	$("#form2").validate({
		rules: {
			desc_aula: {
				required: true
			},
			max_aluno: {
				required: true,
				number: true
			}		},
		messages: {
			desc_aula: {
				required: "Digite o nome da Modalidade."	
			},
			max_aluno: {
				required: "Digite o número máximo de aluno.",
				number: "O valor precisa ser numérico."
			}
		}
	});
	
});
</script>
<script src="js/scripts.js"></script></body>
</html>
<?php
mysql_free_result($ver);
?>
