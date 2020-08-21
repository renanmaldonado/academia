<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php require_once("funcoes/formata_moeda.php"); ?>
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
  $updateSQL = sprintf("UPDATE aluno_pacote SET dia_pagto=%s, acrescimo=%s, desconto=%s, justifica_acrescimo=%s, justifica_desconto=%s, status=%s WHERE cod_pacote=%s",
                       GetSQLValueString($_POST['dia_pagto'], "int"),
                       GetSQLValueString(valorbanco($_POST['acrescimo']), "double"),
                       GetSQLValueString(valorbanco($_POST['desconto']), "double"),
                       GetSQLValueString($_POST['justifica_acrescimo'], "text"),
                       GetSQLValueString($_POST['justifica_desconto'], "text"),
                       GetSQLValueString(isset($_POST['status']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString($_POST['cod_pacote'], "int"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($updateSQL, $conecta) or die(mysql_error());
  
  $valor = "'".valorbanco($_POST['acrescimo'])."'";
  $valor1 = "'-" . valorbanco($_POST['desconto'])."'";
  
  if($valor <> '0.00')
  {
	  $sql = "UPDATE pagto_aluno SET valor = $valor WHERE cod_pacote = '".$_POST['cod_pacote']."' AND cod_atr = 2";
  }
  	mysql_query($sql) or die (mysql_error());
	
  if($valor1 <> '0.00')
  {
	  $sql = "UPDATE pagto_aluno SET valor = $valor1 WHERE cod_pacote = '".$_POST['cod_pacote']."' AND cod_atr = 3";
  }
  	mysql_query($sql) or die (mysql_error());

  $updateGoTo = "atualiza_pacote.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  /*echo "<script>alert('As alterações foram salvas com sucesso!'); window.location.href='$updateGoTo'</script>";*/
  //header(sprintf("Location: %s", $updateGoTo));
}

$colname_ver = "-1";
if (isset($_GET['cod_pacote'])) {
  $colname_ver = $_GET['cod_pacote'];
}
mysql_select_db($database_conecta, $conecta);
$query_ver = sprintf("SELECT ap.*, a.nome_aluno FROM aluno_pacote ap, aluno a WHERE ap.cod_aluno = a.cod_aluno AND ap.cod_pacote = %s", GetSQLValueString($colname_ver, "int"));
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<link href="plugin/datatables/media/css/main.css" rel="stylesheet" type="text/css">
<link href="css/intranet.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%" align="center" class="fundoform">
        <!--DWLayoutTable-->
        <tr valign="middle">
          <td colspan="3"><div align="center" class="titulo">ATUALIZAR PACOTE DO ALUNO - <?php echo $row_ver['nome_aluno']; ?></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td valign="top"><form method="POST" name="form1" action="<?php echo $editFormAction; ?>">
            <table width="100%" align="center" class="detalhe">
              <tr valign="baseline">
                <td nowrap align="right">Data do cadastro:</td>
                <td><?php echo voltadobanco($row_ver['dt_cadastro']); ?></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Dia do pagamento:</td>
                <td><input name="dia_pagto" type="text" class="numero" id="dia_pagto" value="<?php echo Verifica_dia($row_ver['dia_pagto']); ?>" size="2" maxlength="2"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Acr&eacute;scimo:</td>
                <td><input type="text" name="acrescimo" class="moeda" value="<?php echo moeda_br($row_ver['acrescimo']); ?>" size="8"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right" valign="top">Justificar acr&eacute;scimo:</td>
                <td><textarea name="justifica_acrescimo" cols="50" rows="5"><?php echo $row_ver['justifica_acrescimo']; ?></textarea></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Desconto:</td>
                <td><input type="text" name="desconto" class="moeda" value="<?php echo moeda_br($row_ver['desconto']); ?>" size="8"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right" valign="top">Justificar desconto:</td>
                <td><textarea name="justifica_desconto" cols="50" rows="5"><?php echo $row_ver['justifica_desconto']; ?></textarea></td>
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
            <input type="hidden" name="cod_pacote" value="<?php echo $row_ver['cod_pacote']; ?>">
            <input name="cod_aluno" type="hidden" id="cod_aluno" value="<?php echo $row_ver['cod_aluno']; ?>">
<input type="hidden" name="MM_update" value="form1">
          </form></td>
          <td>&nbsp;</td>
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
?>
