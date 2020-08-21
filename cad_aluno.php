<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  
  $dt = ($_POST['dt_nascimento'] == '')? "" : vaiparaobanco($_POST['dt_nascimento']);
  $insertSQL = sprintf("INSERT INTO aluno (nome_aluno, telefone, tel_comercial, tel_residencial, como_conheceu, outros, email, dt_nascimento, endereco, numero, bairro, profissao) VALUES (%s, %s, %s, %s, %s, %s, %s, '$dt', %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nome_aluno'], "text"),
                       GetSQLValueString($_POST['telefone'], "text"),
					   GetSQLValueString($_POST['tel_comercial'], "text"),
					   GetSQLValueString($_POST['tel_residencial'], "text"),
					   GetSQLValueString($_POST['como_conheceu'], "text"),
					   GetSQLValueString($_POST['outros'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['endereco'], "text"),
                       GetSQLValueString($_POST['numero'], "text"),
                       GetSQLValueString($_POST['bairro'], "text"),
                       GetSQLValueString($_POST['profissao'], "text"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($insertSQL, $conecta) or die(mysql_error());
  $ultimo_id = mysql_insert_id();
	
	$insert = mysql_query("INSERT INTO aluno_pacote_id (cod_aluno, dt_cadastro, dia_pagto) VALUES ('$ultimo_id', DATE(NOW()), '".$_POST['dia_pagto']."')", $conecta);
	
	
  $insertGoTo = "cad_aluno.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  
}
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
<form method="post" name="form1" id="form1" action="">
  <table width="100%" align="center" class="fundoform">
    <!--DWLayoutTable-->
    <tr valign="middle">
      <td height="34" colspan="3"><div align="center" class="titulo">CADASTRAR ALUNO</div></td>
    </tr>
    <tr>
      <td width="1" height="200">&nbsp;</td>
      <td width="1271" valign="top"><table width="100%" align="center" class="detalhe">
        <tr valign="baseline">
          <td width="22%" align="right" valign="middle" nowrap>Nome do aluno:</td>
          <td width="78%" valign="middle"><input type="text" name="nome_aluno" id="nome_aluno" value="" size="60"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Data de nascimento:</td>
          <td valign="middle"><input name="dt_nascimento" type="text" class="data" id="dt_nascimento" size="10" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Endere&ccedil;o:</td>
          <td valign="middle"><label for="endereco"></label>
            <input type="text" name="endereco" id="endereco" />
            N&ordm;
            <label for="numero"></label>
            <input name="numero" type="text" class="numero" id="numero" size="5" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Bairro:</td>
          <td valign="middle"><label for="bairro"></label>
            <input type="text" name="bairro" id="bairro" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Profiss&atilde;o:</td>
          <td valign="middle"><label for="profissao"></label>
            <input type="text" name="profissao" id="profissao" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Telefone:</td>
          <td valign="middle"><input type="text" name="telefone" id="telefone" class="telefone" value="" size="15">
          Comercial:
            <input type="text" name="tel_comercial" id="tel_comercial" class="telefone" value="" size="15"> 
            Residencial:
            <input type="text" name="tel_residencial" id="tel_residencial" class="telefone" value="" size="15"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Como conheceu?</td>
          <td valign="middle">
          <select name="como_conheceu" id="como_conheceu">
          	<option value="">Selecione</option>
            <option value="Ex-aluno"> Ex-aluno</option>
            <option value="Facebook"> Facebook</option>
            <option value="Indicação"> Indicação</option>
            <option value="Internet"> Internet</option>
            <option value="Panfletos"> Panfletos</option>
            <option value="Passagem"> Passagem</option>
          </select>
            <input type="text" name="outros" id="outros"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>E-mail:</td>
          <td valign="middle"><input name="email" id="email" type="text" value="" size="60"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Dia do pagamento:</td>
          <td valign="middle"><input name="dia_pagto" type="text" class="numero" id="dia_pagto" value="01" size="2" maxlength="2"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td valign="middle"><input type="submit" class="b-salvar" value="Salvar">
            <input name="cancelar" type="button" class="b-cancelar fechar" id="cancelar" value="Cancelar"></td>
        </tr>
      </table></td>
      <td width="1">&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function() {
	
	

	jQuery.validator.addMethod("dateBR", function (value, element) {
	//contando chars
	if (value.length != 10) return (this.optional(element) || false);
	// verificando data
	var data = value;
	var dia = data.substr(0, 2);
	var barra1 = data.substr(2, 1);
	var mes = data.substr(3, 2);
	var barra2 = data.substr(5, 1);
	var ano = data.substr(6, 4);
	if (data.length != 10 || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia > 31 || mes > 12) return (this.optional(element) || false);
	if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia == 31) return (this.optional(element) || false);
	if (mes == 2 && (dia > 29 || (dia == 29 && ano % 4 != 0))) return (this.optional(element) || false);
	if (ano < 1900) return (this.optional(element) || false);
	return (this.optional(element) || true);
	}, "Informe uma data válida"); // Mensagem padrão 
	
	jQuery('.telefone').mask("(99) 9999-9999?9").ready(function(event) {
        var target, phone, element;
        target = (event.currentTarget) ? event.currentTarget : event.srcElement;
        phone = target.value.replace(/\D/g, '');
        element = $(target);
        element.unmask();
        if(phone.length > 10) {
            element.mask("(99) 99999-999?9");
        } else {
            element.mask("(99) 9999-9999?9");
        }
    });
	
			
	jQuery("#form1").validate({
		rules: {
			nome_aluno: {
				required: true
			},
			dt_vencimento: {
				dateBR: true
			},
			email: {
				email: true,
				remote: "verifica_email.php?"
			}
		},
		messages: {
			nome_aluno: {
				required: "Digite o nome do aluno."	
			},
			email: {
				email: "Digite um e-mail válido!",
				remote: "O e-mail já existe"
			}
		}
	});
	
});
</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>