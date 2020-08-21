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

$var = $_GET['cod_turma'];

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
  $insertSQL = sprintf("INSERT INTO aula (desc_aula, max_aluno, grupo, valor_padrao, valor_mensal) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['desc_aula'], "text"),
                       GetSQLValueString($_POST['max_aluno'], "int"),
                       GetSQLValueString(isset($_POST['grupo']) ? "true" : "", "defined","'S'","'N'"),
					   GetSQLValueString(valorbanco($_POST['valor_padrao']), "int"),
					   GetSQLValueString((isset($_POST['valor_mensal']) && isset($_POST['grupo']))  ? "true" : "", "defined","'S'","'N'"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($insertSQL, $conecta) or die(mysql_error());
  $ultimo_id = mysql_insert_id();
  


  $insertGoTo = "lista_aulas.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_conecta, $conecta);
$query_dias = "SELECT ds.* FROM dia_semana ds ORDER BY ds.cod_dia ASC";
$dias = mysql_query($query_dias, $conecta) or die(mysql_error());
$row_dias = mysql_fetch_assoc($dias);
$totalRows_dias = mysql_num_rows($dias);
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
<script src="js/jquery.numeric.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.maskedinput.js"></script>
<script src="js/jquery.maskMoney.js"></script>
<script>
$(function(){
	$(".grupo").hide();
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
	
	$("#form1").validate({
		rules: {
			desc_aula: {
				required: true
			},
			max_aluno: {
				required: true
			}
		},
		messages: {
			desc_aula: {
				required: "Digite o nome da aula."	
			},
			max_aluno: {
				required: "Digite o número máximo de alunos na aula."
			}
		}
	});
	
});
</script>
<script src="js/scripts.js"></script>

</head>
<body>
<form method="post" name="form1" id="form1" action="<?php echo $editFormAction; ?>">
  <table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="fundoform">
    <!--DWLayoutTable-->
    <tr valign="middle">
      <td height="34" colspan="3"><div align="center" class="titulo">CADASTRAR AULA</div></td>
    </tr>
    <tr>
      <td width="18" height="200">&nbsp;</td>
      <td width="516" valign="top"><table width="100%" align="center" class="detalhe">
        <tr valign="baseline">
          <td width="27%" align="right" valign="middle" nowrap>Aula:</td>
          <td width="73%" align="left" valign="middle"><input name="desc_aula" type="text" id="desc_aula" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>M&aacute;ximo de aluno:</td>
          <td align="left" valign="middle"><input name="max_aluno" type="text" id="max_aluno" class="numero" value="" size="3"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Grupo:</td>
          <td align="left" valign="middle"><input type="checkbox" name="grupo" id="grupo" value="S" ></td>
        </tr>
        <tr valign="baseline" class="grupo">
          <td align="right" valign="middle" nowrap>Valor cobrado mensalmente?</td>
          <td align="left" valign="middle"><input type="checkbox" name="valor_mensal" id="valor_mensal"></td>
        </tr>
        <tr valign="baseline" class="grupo">
          <td align="right" valign="middle" nowrap>Valor padr&atilde;o:</td>
          <td align="left" valign="middle"><input type="text" name="valor_padrao" class="moeda"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap><input type="hidden" name="MM_insert" value="form1"></td>
          <td align="left" valign="middle"><input type="submit" class="b-salvar" value="Salvar">
            <input name="cancelar" type="button" class="b-cancelar voltar" id="cancelar" value="Cancelar"></td>
        </tr>
      </table></td>
      <td width="15">&nbsp;</td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
