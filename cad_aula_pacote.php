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
$var = $_REQUEST['cod_pacote'];
$var1 = $_REQUEST['cod_aula'];

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
	
	if(isset($_REQUEST['cod_aluno_dia_aula']) && ($_REQUEST['cod_aluno_dia_aula'] <> ''))
	{
		mysql_query("INSERT INTO aluno_dia_aula (cod_aluno_pacote_aula, dia, hr_inicio, hr_fim, informacao, remarcado) VALUES ('".$_POST['cod_aluno_pacote_aula']."', '".vaiparaobanco($_POST['dia2'])."', '".$_POST['hr_inicio']."', '".$_POST['hr_fim']."', '".$_POST['informacao']."', '".$_POST['cod_aluno_dia_aula']."')") or die(mysql_error());
	}
	else
	{
	
  $insertSQL = sprintf("INSERT INTO aluno_pacote_aula (cod_pacote, cod_aula) VALUES (%s, %s)",
                       GetSQLValueString($_POST['cod_pacote'], "int"),
                       GetSQLValueString($_POST['cod_aula'], "int"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($insertSQL, $conecta) or die(mysql_error());
  $ultimo = mysql_insert_id();
  
  	$sql1 = "SELECT *, DAY(dt_inicio)AS dia FROM aluno_pacote WHERE cod_pacote = '$var'";
	$query1 = mysql_query($sql1) or die(mysql_error());
	$row1 = mysql_fetch_assoc($query1);
	
	$sql2 = "SELECT pa.valor_cobrado, a.valor_mensal FROM professor_aula pa, aula a WHERE pa.cod_aula = a.cod_aula AND pa.cod_aula = '$var1'";
	$query2 = mysql_query($sql2);
	$row2 = mysql_fetch_assoc($query2);
  
	
	  if(isset($_POST['dia2']))
	  {
			mysql_query("INSERT INTO aluno_dia_aula (cod_aluno_pacote_aula, dia, hr_inicio, hr_fim) VALUES ('$ultimo', '".vaiparaobanco($_POST['dia2'])."', '".$_POST['hr_inicio']."', '".$_POST['hr_fim']."') ");
	  }
	  else
	  {
		  		
			$i = 0;
			for($hoje = $row1['dt_inicio']; $hoje <= $row1['dt_fim']; $hoje = date('Y-m-d', strtotime('+1 day', strtotime($hoje))) )
			{
				foreach($_POST['dia'] as $dia){
					if(date('w', strtotime($dia)) == DiasemanaW($hoje))
					{
						mysql_query("INSERT INTO aluno_dia_aula (cod_aluno_pacote_aula, dia, hr_inicio, hr_fim) VALUES ('$ultimo', '". $hoje ."', '".$_POST['hr_inicio']."', '".$_POST['hr_fim']."') ");
						
						if($row2['valor_mensal'] == 'N')
						{
							mysql_query("INSERT INTO pagto_aluno (cod_aluno_pacote_aula, cod_pacote, cod_aluno, valor) VALUES ('$ultimo', '$var', '".$row1['cod_aluno']."', '".$row2['valor_cobrado']."')") or die (mysql_error());
						}
						elseif($row1['dia'] == date('d', strtotime($hoje)))
						{
							mysql_query("INSERT INTO pagto_aluno (cod_aluno_pacote_aula, cod_pacote, cod_aluno, valor) VALUES ('$ultimo', '$var', '".$row1['cod_aluno']."', '".$row2['valor_cobrado']."')") or die (mysql_error());
						}
					}
				}
				
				$i++;
			}
	  }
	  
	}

  $insertGoTo = "cad_aula_pacote.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}



mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT a.nome_aluno FROM aluno a, aluno_pacote ap WHERE ap.cod_aluno = a.cod_aluno AND ap.cod_pacote = '$var'";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

mysql_select_db($database_conecta, $conecta);
$query_modalidade = "SELECT * FROM aula ORDER BY desc_aula ASC";
$modalidade = mysql_query($query_modalidade, $conecta) or die(mysql_error());
$row_modalidade = mysql_fetch_assoc($modalidade);
$totalRows_modalidade = mysql_num_rows($modalidade);

mysql_select_db($database_conecta, $conecta);
$query_dia = "SELECT * FROM dia_semana ORDER BY cod_dia ASC";
$dia = mysql_query($query_dia, $conecta) or die(mysql_error());
$row_dia = mysql_fetch_assoc($dia);
$totalRows_dia = mysql_num_rows($dia);

if(isset($_GET['cod_aluno_dia_aula'])  && ($_REQUEST['cod_aluno_dia_aula'] <> ''))
{
	mysql_select_db($database_conecta, $conecta);
	$query_remarcado = "SELECT aa.*, ap.* FROM aluno_dia_aula aa, aluno_pacote_aula ap WHERE aa.cod_aluno_pacote_aula = ap.cod_aluno_pacote_aula AND aa.cod_aluno_dia_aula = '".$_GET['cod_aluno_dia_aula']."'";
	$remarcado = mysql_query($query_remarcado, $conecta) or die(mysql_error());
	$row_remarcado = mysql_fetch_assoc($remarcado);
	$totalRows_remarcado = mysql_num_rows($remarcado);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<noscript>
  <meta http-equiv="Refresh" content="1; url=javascript.php">
</noscript>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="cache-control"   content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<title>INTRANET</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<link href="plugin/datatables/media/css/main.css" rel="stylesheet" type="text/css">
<link href="css/intranet.css" rel="stylesheet" type="text/css">


</head>
<body>
<table width="100%" align="center" class="fundoform">
        <!--DWLayoutTable-->
        <tr valign="middle">
          <td colspan="3"><div align="center" class="titulo">CADASTRAR AULAS DO PACOTE DO ALUNO- <?php echo $row_ver['nome_aluno']; ?></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td valign="top">&nbsp;
            <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
              <table width="100%" align="center" class="detalhe">
                <tr valign="baseline">
                  <td width="18%" align="right" nowrap>Aula:</td>
                  <td width="82%"><select name="cod_aula" id="cod_aula">
                    <?php
do {  
?>
                    <option value="<?php echo $row_modalidade['cod_aula']?>"<?php if (!(strcmp($row_modalidade['cod_aula'], $row_remarcado['cod_aula']))) {echo "selected=\"selected\"";} ?>><?php echo $row_modalidade['desc_aula']?></option>
                    <?php
} while ($row_modalidade = mysql_fetch_assoc($modalidade));
  $rows = mysql_num_rows($modalidade);
  if($rows > 0) {
      mysql_data_seek($modalidade, 0);
	  $row_modalidade = mysql_fetch_assoc($modalidade);
  }
?>
                  </select></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right">Selecionar mais de um dia?</td>
                  <td><input name="multi" type="checkbox" id="multi" value="S"><span class="msg"></span></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right" valign="top">Dia:</td>
                  <td><select name="dia[]" id="dia">
                    <?php
$dt = date('Y-m-d');
do {  

if($row_dia['cod_dia'] == 10)
{
	$dt = 10;	
}
elseif(date('w') == $row_dia['cod_dia'])
{
	$dt = date('Y-m-d');
}
elseif(date('w') > $row_dia['cod_dia'])
{
	$dt = date('Y-m-d', strtotime('+6 Day', strtotime($dt)));
}
else
{
	$dt = date('Y-m-d', strtotime('+1 Day', strtotime($dt)));
}
?>
                    <option value="<?php echo $dt; ?>" <?php echo ($dt == date('Y-m-d'))? 'selected="selected"':""; ?>><?php echo $row_dia['desc_dia']; ?></option>
                    <?php
} while ($row_dia = mysql_fetch_assoc($dia));
  $rows = mysql_num_rows($dia);
  if($rows > 0) {
      mysql_data_seek($dia, 0);
	  $row_dia = mysql_fetch_assoc($dia);
  }
?>
                  </select><span class="dia_avulso"></span></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right">Hora in&iacute;cial:</td>
                  <td><input name="hr_inicio" type="text" class="hora" value="" size="5" maxlength="5">
Hora final:
<input name="hr_fim" type="text" class="hora" value="" size="5" maxlength="5"></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right">&nbsp;</td>
                  <td><input type="submit" class="b-salvar" value="Salvar">
                  <input name="button" type="submit" class="b-cancelar cancelar" id="button" value="Cancelar"></td>
                </tr>
              </table>
              <input type="hidden" name="cod_pacote" value="<?php echo $_GET['cod_pacote']; ?>">
              <input type="hidden" name="MM_insert" value="form1">
              <input name="cod_aluno_dia_aula" type="hidden" id="cod_aluno_dia_aula" value="<?php echo $_REQUEST['cod_aluno_dia_aula']; ?>">
              <input name="cod_aluno_pacote_aula" type="hidden" id="cod_aluno_pacote_aula" value="<?php echo $row_remarcado['cod_aluno_pacote_aula']; ?>">
            </form>
          <p>&nbsp;</p></td>
          <td>&nbsp;</td>
        </tr>
      </table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<!-- Javascript -->
<script type="text/javascript" src="js/jquery.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="js/jquery.maskMoney.js"></script>
<script src='plugin/fullcalendar/lib/moment.min.js'></script>
<script src='plugin/fullcalendar/fullcalendar.min.js'></script>
<script src='plugin/fullcalendar/lang/pt-br.js' charset="iso-8859-1"></script>
<script>
$(function(){
	
	<?php 
	if(isset($_GET['cod_aluno_dia_aula']))
	{
	?>
	$("#dia option").remove();
	$("#dia").append('<option value="10">Dia específico</option>').attr("disabled", true);;
	$(".dia_avulso").html(' <input name="dia2" value="<?php echo date('d/m/Y'); ?>" class="dt1 data" size="10" maxlength="10" /> <br><br> <strong>Observação</strong><br><br><textarea name="informacao" id="informacao" cols="45" rows="5"></textarea>');	
	<?php	
	}
	?>
	
	$("#dia").change(function(e){
		var dia = $(this).val();
		if(dia == 10){
			$(".dia_avulso").html(' Selecione um dia: <input name="dia2" value="<?php echo date('d/m/Y'); ?>" class="data" size="10" maxlength="10" />');	
		}
		else
		{
			$(".dia_avulso").html('');
		}	
	});
	
	$("#multi").click(function(){
		var checado = $(this).is(':checked');	
		
		if(checado == true){
			$("#dia").attr({
				multiple: 'multiple',
				size: 8
			});
			$(".msg").html(" Precione a tecla \"Ctrl\" e selecione os dias da semana que desejar.");
			$("#dia option[value='10']").remove();
		}
		else
		{
			$("#dia").removeAttr('multiple');
			$("#dia").removeAttr('size');
			$(".msg").html('');
			$("#dia").append('<option value="10">Dia específico</option>');
		}
	});
	
})
</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($ver);

mysql_free_result($modalidade);

mysql_free_result($dia);
?>
