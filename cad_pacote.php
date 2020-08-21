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
$var = $_GET['cod_aluno'];

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
  $insertSQL = sprintf("INSERT INTO aluno_pacote (cod_aluno, pacote, dia_pagto, desconto, justifica_desconto, acrescimo, justifica_acrescimo, dt_inicio, dt_fim, status, dt_cadastro) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, DATE(NOW()))",
                       GetSQLValueString($_POST['cod_aluno'], "int"),
                       GetSQLValueString($_POST['plano'], "int"),
                       GetSQLValueString($_POST['dia_pagto'], "int"),
                       GetSQLValueString($_POST['desconto'], "double"),
                       GetSQLValueString($_POST['justifica_desconto'], "text"),
                       GetSQLValueString($_POST['acrescimo'], "double"),
                       GetSQLValueString($_POST['justifica_acrescimo'], "text"),
                       GetSQLValueString(vaiparaobanco($_POST['dt_inicio']), "date"),
                       GetSQLValueString(vaiparaobanco($_POST['dt_fim']), "date"),
                       GetSQLValueString(isset($_POST['status']) ? "true" : "", "defined","'S'","'N'"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($insertSQL, $conecta) or die(mysql_error());
  $ultimo = mysql_insert_id();
  
  $valor = "'".valorbanco($_POST['acrescimo'])."'";
  $valor1 = "'-" . valorbanco($_POST['desconto'])."'";
  
  	$venc = $_POST['dia_pagto'];
	$venc = date("Y-m-$venc");
	$venc = date('l', strtotime($venc));
	$venc = date('Y-m-d', strtotime("Next $venc"));

  	mysql_query("INSERT INTO pagto_aluno (cod_atr, cod_pacote, cod_aluno, valor, dt_vencimento) VALUES (2, '$ultimo', '$var', $valor, '$venc')") or die (mysql_error());
  	mysql_query("INSERT INTO pagto_aluno (cod_atr, cod_pacote, cod_aluno, valor, dt_vencimento) VALUES (3, '$ultimo', '$var', $valor1, '$venc')") or die (mysql_error());
  
	$fechar = 'true';
}

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT a.nome_aluno FROM aluno a WHERE a.cod_aluno = '".$_REQUEST['cod_aluno']."'";
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
          <td colspan="3"><div align="center" class="titulo">CADASTRAR PACOTE DO ALUNO - <?php echo $row_ver['nome_aluno']; ?></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td valign="top"><form method="POST" name="form1" id="form1" action="<?php echo $editFormAction; ?>">
            <table width="100%" align="center" class="detalhe">
              <tr valign="baseline">
                <td nowrap align="right">Pacote:</td>
                <td><select name="plano" id="plano">
                  <option value="0">Avulso</option>
                  <option value="12">Anual</option>
                  <option value="6">Semestral</option>
                  <option value="3">Trimestral</option>
                  <option value="2">Bimestral</option>
                  <option value="1" selected>Mensal</option>
                </select></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Data do pacote:</td>
                <td><label for="dt_inicio"></label>
                  <input name="dt_inicio" type="text" class="data dt1" id="dt_inicio" size="10" maxlength="10" value="<?php echo date('d/m/Y'); ?>">
&agrave;
<input name="dt_fim" type="text" class="data dt2" id="dt_fim" size="10" maxlength="10" value=""><span id="dt_fim2"></span></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Dia do pagamento:</td>
                <td><input name="dia_pagto" type="text" class="numero" id="dia_pagto" value="01" size="2" maxlength="2">
                <p class="Verdana10cinzanormal">
                Se o dia do pagamento for menor que o dia da data de in&iacute;cio do pacote o pagamento ser&aacute; gerado para o pr&oacute;ximo m&ecirc;s.</td>
              	</p>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Acr&eacute;scimo:</td>
                <td><input type="text" name="acrescimo" class="moeda" value="" size="8"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right" valign="top">Justificar acr&eacute;scimo:</td>
                <td><textarea name="justifica_acrescimo" cols="50" rows="5"></textarea></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Desconto:</td>
                <td><input type="text" name="desconto" class="moeda" size="8"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right" valign="top">Justificar desconto:</td>
                <td><textarea name="justifica_desconto" cols="50" rows="5"></textarea></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">Status:</td>
                <td><input name="status" type="checkbox" value="" checked="CHECKED"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">&nbsp;</td>
                <td><input type="submit" class="b-salvar" value="Salvar">
                <input name="button" type="button" class="b-cancelar fechar" id="button" value="Cancelar"></td>
              </tr>
            </table>
            <input type="hidden" name="cod_pacote" value="<?php echo $row_ver['cod_pacote']; ?>">
            <input name="cod_aluno" type="hidden" id="cod_aluno" value="<?php echo $var; ?>">
            <input type="hidden" name="MM_insert" value="form1">
          </form></td>
          <td>&nbsp;</td>
        </tr>
      </table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<!-- Javascript -->
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
<script>
$(document).ready(function(e){
	
	<?php
	if($fechar == 'true')
	{
		echo "Url('cad_aula_pacote2.php?cod_pacote=$ultimo');";	
	}
	?>
	
	$("#form1").validate({
		rules: {
			dt_marcada: {
				required: true,
				dateBR: true
			},
			hr_inicio: {
				required: true,
				timerbr: true
			},
			dia_pagto: {
				required: true
			}
		},
		messages: {
			dt_marcada: {
				required: "Selecione uma data."
			},
			hr_inicio: {
				required: "Digite uma hora válida."
			},
			dia_pagto: {
				required: "Digite o dia do pagamento. O mesmo deve ser menor que 28."
			}
		}
	});

    //somar dias
	var minhaData = moment(
    "<?php echo date('d/m/Y'); ?>", "D/M/YYYY"
	).add(
		'month', 1
	).format('DD/MM/YYYY');
	
	$('#dt_fim').val(minhaData);
	$("#dt_fim").attr("type", 'hidden');
	$("#dt_fim2").html(minhaData);
	
	$("#form1").submit
	(function(){
		var valor = $("#dia_pagto").val();
		
		if(valor > 28)
		{
			$("#dia_pagto").val('').focus();
			alert('O dia do pagamento deve ser menor 28.');
			return false;	
		}
	});
	
	$("#plano, #dt_inicio").change(function(){
		
		var dia = $("#dt_inicio").val();
		
		var plano = $("#plano").val();
				
		var minhaData = moment(
		dia, "D/M/YYYY"
		).add(
			'month', plano
		).format('DD/MM/YYYY');
		
		$('#dt_fim').val(minhaData);
		
		if(plano > 0)
		{
			$('#dt_fim').removeAttr("disabled");
			$("#dt_fim").attr("type", 'hidden');
			$("#dt_fim2").html(minhaData);
		}
		else
		{
			$('#dt_fim').removeAttr("disabled");
			$("#dt_fim").attr("type", 'text').val(minhaData);
			$("#dt_fim2").html('');
		}

		
		
			
	});
	//fim somar
	
});

</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($ver);
?>
