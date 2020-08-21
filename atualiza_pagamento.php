<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php 
//Validação do módulo
$fvar2 = 8;
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
$var = $_REQUEST['cod_fatura'];


mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT valor - valor_pago as valor, cod_pacote
			  FROM faturamento
			  WHERE cod_fatura = '$var'";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

$var1 = $row_ver['cod_pacote'];

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
	
	$valor_pago = valorbanco($_POST['valor_pago']);
	
	
	mysql_query("INSERT INTO historico_pagto_faturamento (valor, dt_pago, cod_fatura, informacao) VALUES ('$valor_pago', '".vaiparaobanco($_REQUEST['dt_pagto'])."', '".$_POST['cod_fatura']."', '".$_POST['informacao']."')");
	
	if($_POST['valor_total'] == valorbanco($_POST['valor_pago']))
	{
		$status = "S";	
		$dt = ", dt_previsao = NULL";
	}
	elseif($_POST['valor_total'] > valorbanco($_POST['valor_pago'])){
		$status = "P";
		$dt = ", dt_previsao = '".vaiparaobanco($_POST['dt_previsao'])."'";
	}
	else
	{
		$status = "N";
		$dt = ", dt_previsao = '".vaiparaobanco($_POST['dt_previsao'])."'";
	}
	
	$updateSQL = sprintf("UPDATE faturamento SET pagto_status='$status', valor_pago = valor_pago + '$valor_pago', dt_pago=%s $dt WHERE cod_fatura=%s",
						   GetSQLValueString(vaiparaobanco($_REQUEST['dt_pagto']), "date"),
						   GetSQLValueString($_POST['cod_fatura'], "int"));
	
	mysql_select_db($database_conecta, $conecta);
	$Result1 = mysql_query($updateSQL, $conecta) or die(mysql_error());
	$ultimo = mysql_insert_id();

	if($status == 'S'){
		$updateGoTo = "recibo.php?cod=$var1&cod_fatura=".$_POST['cod_fatura'];
	}
	else
	{
		$updateGoTo = "atualiza_pagamento.php?cod_fatura=".$_POST['cod_fatura'];
	}
	header("Location: $updateGoTo");
}


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
  <table width="100%" class="fundoform">
  <tr>
    <td>&nbsp;</td>
    <td class="titulo" align="center">GERENCIAR PAGAMENTO</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><form method="post" name="form1" id="form1" action="<?php echo $editFormAction; ?>">
      <table width="100%" align="center" class="detalhe">
          <tr valign="baseline">
            <td align="right" nowrap>Saldo devedor:</td>
            <td>R$ <?php echo moeda_br($row_ver['valor']); ?><input name="valor_total" type="hidden" id="valor_total" value="<?php echo $row_ver['valor']; ?>"></td>
          </tr>
          <tr valign="baseline" class="atualiza">
            <td width="14%" align="right" nowrap>Valor pago:</td>
            <td width="86%"><input name="valor_pago" type="text" class="moeda" id="valor_pago" value="<?php echo moeda_br($row_ver['valor']); ?>" size="32" ></td>
          </tr>
          <tr valign="baseline" class="atualiza">
            <td nowrap align="right" valign="top">Informação:</td>
            <td><textarea name="informacao" cols="50" rows="5"><?php echo $row_ver['informacao']; ?></textarea></td>
          </tr>
          <tr valign="baseline" class="atualiza">
            <td nowrap align="right" valign="middle">Data previs&atilde;o:</td>
            <td><input name="dt_previsao" type="text" class="data dt1" id="dt_previsao" value="<?php echo date('d/m/Y'); ?>" size="10"> 
            Data para pagamento futuro do restante.</td>
          </tr>
          <tr valign="baseline" class="atualiza">
            <td nowrap align="right">Data do pagamento:</td>
            <td><input name="dt_pagto" type="text" class="data dt1" id="dt_pagto" value="<?php echo date('d/m/Y'); ?>" size="10">
            <input name="rest" id="rest" type="hidden"  value="" size="32"></td>
          </tr>
          <tr valign="baseline" class="atualiza">
            <td nowrap align="right">&nbsp;</td>
            <td><input type="submit" class="b-salvar" value="Salvar">
            <input name="button" type="button" class="b-cancelar fechar" id="button" value="Cancelar"></td>
          </tr>
        </table>
        <input type="hidden" name="cod_fatura" id="cod_fatura" value="<?php echo $_REQUEST['cod_fatura']; ?>">
        <input type="hidden" name="MM_update" value="form1">
        <input name="cod_aluno" type="hidden" id="cod_aluno" value="<?php echo $row_ver['cod_aluno']; ?>">
    </form>
    <p>&nbsp;</p></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<script>
$(document).ready(function(e){
	
	
	function MoedaBr(valor){
		
		var val = valor.replace('.','');
		var val1 = val.replace(',','.');
		
		return val1;
	}
	
	function MoedaBr2(valor){
		
		var val = valor.replace('.',',');
		
		return val;
	}
	
	
	$("#valor_pago").change(function(e){
		
		var valor = MoedaBr($(this).val());
		var valor1 = $("#valor_total").val();
		//alert(valor + " < " + valor1);
		var val_dev = valor1 - valor;
		
		if(valor < valor1)
		{
			$("#rest").val(val_dev);
			$("#val_dev").html("Selecione uma data de vencimento para o valor restante R$ " + val_dev + " da parcela.");
			
		}
		else
		{
			$("#val_dev").html('');
		}
		
	});
	
	$("#form1").validate({
		rules: {
			valor_pago: {
				remote: 'verifica_valor.php?valor_total=' + $('#valor_total').val()
			},
			dt_vemcimento: {
				require: true
			},
			dt_pagto: {
				require: true
			}
		},
		messages: {
			valor_pago: {
				remote: "O valor precisa ser menor ou igual ao saldo devedor."	
			},
			dt_vemcimento: {
				require: "Selecione uma data do vencimento."
			},
			dt_pagto: {
				require: "Selecione a data do pagamento."
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
