<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php 
//Validação do módulo
$fvar2 = 7;
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
$var = $_REQUEST['cod_professor'];
$var1 = $_REQUEST['cod_pacote'];


mysql_select_db($database_conecta, $conecta);
$query_ver1 = "SELECT pa.cod_pagto_professor, pa.dt_vencimento, pa.dt_pagto
			  FROM pagto_professor pa
			  WHERE pa.cod_professor = '$var'
			  AND pa.quitado = 'N'
			  AND MONTH(pa.dt_vencimento) = '".$_REQUEST['mes']."'
			  AND YEAR(pa.dt_vencimento) = '".$_REQUEST['ano']."'
			  ORDER BY pa.dt_vencimento DESC";
			  
$query_ver = "SELECT pa.cod_pagto_professor,SUM(pa.valor) AS valor, pa.dt_vencimento, pa.dt_pagto
			  FROM pagto_professor pa
			  WHERE pa.cod_professor = '$var'
			  AND pa.quitado = 'N'
			  AND MONTH(pa.dt_vencimento) = '".$_REQUEST['mes']."'
			  AND YEAR(pa.dt_vencimento) = '".$_REQUEST['ano']."'
			  ORDER BY pa.dt_vencimento DESC";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows(mysql_query($query_ver1));

mysql_select_db($database_conecta, $conecta);
$query_ver1 = "SELECT DISTINCT(concat(MONTH(pa.dt_vencimento), ' ', Year(pa.dt_vencimento)))AS venc, MONTH(pa.dt_vencimento)AS mes, Year(pa.dt_vencimento) AS ano
			  FROM pagto_professor pa
			  WHERE pa.cod_professor = '$var'
			  AND pa.quitado = 'N'
			  AND MONTH(pa.dt_vencimento) <= DATE(NOW())
			  AND YEAR(pa.dt_vencimento) <= DATE(NOW())
			  ORDER BY dt_vencimento ASC";
$ver1 = mysql_query($query_ver1, $conecta) or die(mysql_error());
$row_ver1 = mysql_fetch_assoc($ver1);
$totalRows_ver1 = mysql_num_rows($ver1);


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
	
  $updateSQL = sprintf("UPDATE pagto_professor SET quitado='S', dt_pagto=%s WHERE cod_professor=%s AND MONTH(dt_vencimento)=%s AND YEAR(dt_vencimento)=%s",
                       GetSQLValueString(vaiparaobanco($_REQUEST['dt_pagto']), "date"),
					   GetSQLValueString($var, "int"),
					   GetSQLValueString($_REQUEST['mes'], "int"),
					   GetSQLValueString($_REQUEST['ano'], "int"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($updateSQL, $conecta) or die(mysql_error());


		mysql_select_db($database_conecta, $conecta);
		$sql_insert = "INSERT INTO pagto_professor 
					   (cod_atr, cod_professor, valor, dt_vencimento, quitado, dt_gerado) 
					   VALUES 
					   ('1', '$var', '".$_POST['valor_total']."', '".vaiparaobanco($_POST['dt_vencimento'])."', 'N', DATE(NOW())) ";
		$query_insert = mysql_query($sql_insert, $conecta) or die (mysql_error());

		$sql_insert = "INSERT INTO pagto_professor 
					   (cod_atr, cod_professor, valor, dt_vencimento, dt_pagto, quitado, dt_gerado) 
					   VALUES 
					   ('4', '$var', '-".(valorbanco($_POST['valor_pago']))."', '".vaiparaobanco($_POST['dt_vencimento'])."', '".vaiparaobanco($_POST['dt_pagto'])."',  'N', DATE(NOW())) ";
		$query_insert = mysql_query($sql_insert, $conecta) or die (mysql_error());

		$updateGoTo = "atualiza_pagamento_professor.php?cod_professor=$var";

  		header("Location: $updateGoTo");
  
}
if($totalRows_ver1 == 0){
	echo "<script>alert('Não existem pagamentos pendente!'); window.close();</script>";
}

if((($row_ver['valor'] <= 0) || ($row_ver['valor'] == '')) && (($_REQUEST['mes'] <> '') && ($_REQUEST['ano'] <> '')))
{
	echo "<script>alert('O pagamento está quitado!'); window.close();</script>";
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
          <tr valign="baseline" class="referencia">
            <td colspan="2" align="center" nowrap>Referencia do vencimento:
              <select name="venc" id="venc">
                <?php 
			  do{
				  if(DescData($row_ver1['mes']) . ' ' . $row_ver1['ano'] <> '0 0')
				  {
			  ?>
                <option value="mes=<?php echo ($row_ver1['mes']); ?>&ano=<?php echo $row_ver1['ano']; ?>"><?php echo DescData($row_ver1['mes']); ?> <?php echo $row_ver1['ano']; ?></option>
              <?php 
				  }
			  }while($row_ver1 = mysql_fetch_assoc($ver1));
			  ?>
            </select>
            <input name="pagar" type="button" class="b-liberar" id="pagar" value="Pagar"></td>
          </tr>
          <tr valign="baseline" class="atualiza">
            <td align="right" nowrap>Saldo devedor:</td>
            <td>R$ <?php echo moeda_br($row_ver['valor']); ?><input name="valor_total" type="hidden" id="valor_total" value="<?php echo $row_ver['valor']; ?>"></td>
          </tr>
          <tr valign="baseline" class="atualiza">
            <td width="14%" align="right" nowrap>Valor pago:</td>
            <td width="86%"><input name="valor_pago" type="text" class="moeda" id="valor_pago" value="<?php echo moeda_br($row_ver['valor']); ?>" size="32" ></td>
          </tr>
          <tr valign="baseline" class="vencimento">
            <td nowrap align="right">Pr&oacute;ximo vencimento:</td>
            <td><input name="dt_vencimento" type="text" class="data dt1" id="dt_vencimento" value="" size="32"></td>
          </tr>
          <tr valign="baseline" class="atualiza">
            <td nowrap align="right">Data do pagamento:</td>
            <td><input name="dt_pagto" type="text" class="data dt1" id="dt_pagto" value="<?php echo date('d/m/Y'); ?>" size="32"></td>
          </tr>
          <tr valign="baseline" class="atualiza">
            <td nowrap align="right">&nbsp;</td>
            <td><input type="submit" class="b-salvar" value="Salvar">
            <input name="button" type="button" class="b-cancelar fechar" id="button" value="Cancelar"></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input name="cod_professor" type="hidden" id="cod_professor" value="<?php echo $var; ?>">
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
	
	$(".vencimento").hide();
	
	<?php
	if(($_REQUEST['mes'] == '') && ($_REQUEST['ano'] == ''))
	{
		echo '$(".atualiza").hide();';
	}
	else
	{
		echo '$(".atualiza").show();';
		echo '$(".referencia").hide();';
	}
	?>
	
	$("#dt_vencimento").val('');
	
	function MoedaBr(valor){
		
		var val = valor.replace('.','');
		var val1 = val.replace(',','.');
		
		return val1;
	}
	
	function MoedaBr2(valor){
		
		var val = valor.replace('.',',');
		
		return val;
	}
	
	$("#pagar").click(function(){
		var venc = $("#venc").val();
		
		<?php
		$server = $_SERVER['SERVER_NAME'];
		$endereco = $_SERVER ['REQUEST_URI'];
		?>
		
		window.location.href='atualiza_pagamento_professor.php?cod_professor=<?php echo $var; ?>&' + venc;
	});
	
	$("#valor_pago").change(function(e){
		
		var valor = MoedaBr($(this).val());
		var valor1 = $("#valor_total").val();
		//alert(valor1 + " > " + valor);
		
		if(valor1 > valor)
		{
			$(".vencimento").show();
			$("#dt_vencimento").val('<?php echo date('d/m/Y'); ?>');
		}
		else
		{
			$(".vencimento").hide();
			$("#dt_vencimento").val('<?php echo date('d/m/Y'); ?>');
		}
		
	});

	$("#form1").submit(function(){
			
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
