<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php require_once("funcoes/formata_moeda.php"); ?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE aluno_pacote SET desconto_aula=%s, acrescimo_aula=%s, hr_inicio=%s, status=%s WHERE cod_aluno_pacote=%s",
                       GetSQLValueString(valorbanco($_POST['desconto_aula']), "double"),
                       GetSQLValueString(valorbanco($_POST['acrescimo_aula']), "double"),
                       GetSQLValueString($_POST['hr_inicio'], "date"),
                       GetSQLValueString(isset($_POST['status']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString($_POST['cod_aluno_pacote'], "int"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($updateSQL, $conecta) or die(mysql_error());

  $updateGoTo = "atualiza_aula_aluno.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  echo "<script>alert('As alterações foram salvas com sucesso!'); window.location.href='$updateGoTo'</script>";
  //header(sprintf("Location: %s", $updateGoTo));
}


$colname_ver = "-1";
if (isset($_GET['cod_aluno_pacote'])) {
  $colname_ver = $_GET['cod_aluno_pacote'];
}
mysql_select_db($database_conecta, $conecta);
$query_ver = sprintf("SELECT * FROM aluno_pacote WHERE cod_aluno_pacote = %s", GetSQLValueString($colname_ver, "int"));
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

mysql_select_db($database_conecta, $conecta);
$query_professor = "SELECT p.* FROM professor p, professor_aula pa WHERE p.cod_professor = pa.cod_professor AND pa.cod_aula = '".$row_ver['cod_aula']."'";
$professor = mysql_query($query_professor, $conecta) or die(mysql_error());
$row_professor = mysql_fetch_assoc($professor);
$totalRows_professor = mysql_num_rows($professor);

mysql_select_db($database_conecta, $conecta);
$query_dias = "SELECT * FROM dia_semana ORDER BY cod_dia ASC";
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<link href="plugin/datatables/media/css/main.css" rel="stylesheet" type="text/css">
<link href="css/intranet.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%" align="center" class="fundoform">
        <!--DWLayoutTable-->
        <tr valign="middle">
          <td colspan="3"><div align="center" class="titulo">ATUALIZAR AULA</div></td>
        </tr>
        <tr>
          <td width="21">&nbsp;</td>
          <td width="1228" valign="top">
            <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
            <table width="100%" align="center" class="detalhe">
                <tr valign="baseline">
                  <td nowrap align="right">Desconto:</td>
                  <td><input name="desconto_aula" type="text" class="moeda" id="desconto_aula" value="<?php echo moeda_br($row_ver['desconto_aula'], ENT_COMPAT, 'iso-8859-1'); ?>" size="8"></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right">Acréscimo:</td>
                  <td><input name="acrescimo_aula" type="text" class="moeda" id="acrescimo_aula" value="<?php echo moeda_br($row_ver['acrescimo_aula'], ENT_COMPAT, 'iso-8859-1'); ?>" size="8"></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right">Hora:</td>
                  <td><input name="hr_inicio" type="text" class="hora" id="hr_inicio" value="<?php echo htmlentities($row_ver['hr_inicio'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" maxlength="5"></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right">Status:</td>
                  <td><input type="checkbox" name="status" value=""  <?php if (!(strcmp($row_ver['status'],"S"))) {echo "checked=\"checked\"";} ?>></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right">&nbsp;</td>
                  <td><input type="submit" class="b-salvar" value="Salvar">
                  <input name="button" type="button" class="b-cancelar fechar" id="button" value="Cancelar"></td>
                </tr>
              </table>
              <input type="hidden" name="cod_aluno_pacote" value="<?php echo $row_ver['cod_aluno_pacote']; ?>">
              <input type="hidden" name="MM_update" value="form1">
              <input type="hidden" name="cod_aluno_pacote" value="<?php echo $row_ver['cod_aluno_pacote']; ?>">
            </form></td>
          <td width="24">&nbsp;</td>
        </tr>
      </table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<script type="text/javascript" src="js/jquery.js"></script>
 <script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="js/jquery.maskMoney.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.numeric.js"></script>
<script>
$(document).ready(function(){
	
	$("#form1").validate({
		rules: {
			dt_marcada: {
				required: true,
				dateBR: true
			},
			hr_inicio: {
				required: true,
				timerbr: true
			}
		},
		messages: {
			dt_marcada: {
				required: "Selecione uma data."
			},
			hr_inicio: {
				required: "Digite uma hora válida."
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

mysql_free_result($professor);

mysql_free_result($dias);
?>
