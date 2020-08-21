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
$var = $_GET['cod_aluno'];

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
  $updateSQL = sprintf("UPDATE aluno SET nome_aluno=%s, telefone=%s, tel_comercial=%s, tel_residencial=%s, como_conheceu=%s, outros=%s, email=%s, status=%s, dt_nascimento=%s, endereco=%s, numero=%s, bairro=%s, profissao=%s WHERE cod_aluno=%s",
                       GetSQLValueString($_POST['nome_aluno'], "text"),
                       GetSQLValueString($_POST['telefone'], "text"),
					   GetSQLValueString($_POST['tel_comercial'], "text"),
					   GetSQLValueString($_POST['rel_residencial'], "text"),
					   GetSQLValueString($_POST['como_conheceu'], "text"),
					   GetSQLValueString($_POST['outros'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString(isset($_POST['status']) ? "true" : "", "defined","'S'","'N'"),
					   GetSQLValueString(vaiparaobanco($_POST['dt_nascimento']), "date"),
                       GetSQLValueString($_POST['endereco'], "text"),
                       GetSQLValueString($_POST['numero'], "text"),
                       GetSQLValueString($_POST['bairro'], "text"),
                       GetSQLValueString($_POST['profissao'], "text"),
					   GetSQLValueString($_POST['cod_aluno'], "int"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($updateSQL, $conecta) or die(mysql_error());

  $updateGoTo = "visualiza_aluno.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT * FROM aluno WHERE cod_aluno = '$var'";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);
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
  <table width="100%" class="fundoform">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><table width="100%" align="center" class="detalhe">
            <tr valign="baseline">
              <td align="right" valign="middle" nowrap>Nome do aluno:</td>
              <td valign="middle"><input type="text" name="nome_aluno" id="nome_aluno" value="<?php echo $row_ver['nome_aluno']; ?>" size="60"></td>
            </tr>
            <tr valign="baseline">
              <td align="right" valign="middle" nowrap>Data de nascimento:</td>
              <td valign="middle"><input name="dt_nascimento" type="text" class="data" id="dt_nascimento" size="10" value="<?php echo voltadobanco($row_ver['dt_nascimento']); ?>" /></td>
            </tr>
            <tr valign="baseline">
              <td align="right" valign="middle" nowrap>Endere&ccedil;o:</td>
              <td valign="middle"><label for="endereco"></label>
                <input type="text" name="endereco" id="endereco" value="<?php echo $row_ver['endereco']; ?>" />
                N&ordm;
                <label for="numero"></label>
                <input name="numero" type="text" class="numero" id="numero" size="5" value="<?php echo $row_ver['numero']; ?>" /></td>
            </tr>
            <tr valign="baseline">
              <td align="right" valign="middle" nowrap>Bairro:</td>
              <td valign="middle"><label for="bairro"></label>
                <input type="text" name="bairro" id="bairro" value="<?php echo $row_ver['bairro']; ?>" /></td>
            </tr>
            <tr valign="baseline">
              <td align="right" valign="middle" nowrap>Profiss&atilde;o:</td>
              <td valign="middle"><label for="profissao"></label>
                <input type="text" name="profissao" id="profissao" value="<?php echo $row_ver['profissao']; ?>" /></td>
            </tr>
            <tr valign="baseline">
              <td align="right" valign="middle" nowrap="nowrap">Telefone:</td>
              <td valign="middle"><input type="text" name="telefone" id="telefone" class="telefone" value="<?php echo $row_ver['telefone']; ?>" size="15" />
                Comercial:
                <input type="text" name="tel_comercial" id="tel_comercial" class="telefone" value="<?php echo $row_ver['tel_comercial']; ?>" size="15" />
                Residencial:
                <input type="text" name="tel_residencial" id="tel_residencial" class="telefone" value="<?php echo $row_ver['tel_residencial']; ?>" size="15" /></td>
            </tr>
            <tr valign="baseline">
              <td align="right" valign="middle" nowrap="nowrap">Como conheceu?</td>
              <td valign="middle">
              <select name="como_conheceu" id="como_conheceu">
                <option value="">Selecione</option>
                <option value="Ex-aluno" <?php echo ($row_ver['como_conheceu'] == 'Ex-aluno')? 'selected="selected"':'' ?>> Ex-aluno</option>
                <option value="Facebook" <?php echo ($row_ver['como_conheceu'] == 'Facebook')? 'selected="selected"':'' ?>> Facebook</option>
                <option value="Indicação" <?php echo ($row_ver['como_conheceu'] == 'Indicação')? 'selected="selected"':'' ?>> Indicação</option>
                <option value="Internet" <?php echo ($row_ver['como_conheceu'] == 'Internet')? 'selected="selected"':'' ?>> Internet</option>
                <option value="Panfletos" <?php echo ($row_ver['como_conheceu'] == 'Panfletos')? 'selected="selected"':'' ?>> Panfletos</option>
                <option value="Passagem" <?php echo ($row_ver['como_conheceu'] == 'Passagem')? 'selected="selected"':'' ?>> Passagem</option>
              </select>
                <input type="text" name="outros" id="outros" value="<?php echo $row_ver['outros']; ?>" /></td>
            </tr>
            <tr valign="baseline">
              <td align="right" valign="middle" nowrap>E-mail:</td>
              <td valign="middle"><input name="email" id="email" type="text" value="<?php echo $row_ver['email']; ?>" size="60"></td>
            </tr>
            <tr valign="baseline">
              <td align="right" valign="middle" nowrap>Ativo:</td>
              <td valign="middle"><input <?php if (!(strcmp($row_ver['status'],"S"))) {echo "checked=\"checked\"";} ?> name="status" type="checkbox" id="status" value="S"></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">&nbsp;</td>
              <td valign="middle"><input type="submit" class="b-salvar" value="Salvar">
                <input name="cancelar" type="button" class="b-cancelar voltar" id="cancelar" value="Cancelar"></td>
            </tr>
          </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
  <input name="cod_aluno" type="hidden" id="cod_aluno" value="<?php echo $row_ver['cod_aluno']; ?>">
  <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<script>
$(document).ready(function(){
	
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
	
	$("#form1").validate({
		rules: {
			nome_aluno: {
				required: true
			},
			dt_nascimento:{
				dateBR: true
			},
			email: {
				email: true,
				remote: "verifica_email.php?atual=<?php echo $row_ver['email']; ?>"
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
<?php
mysql_free_result($ver);
?>
