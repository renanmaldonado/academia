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
		$sql2 = "SELECT pa.valor_cobrado, pa.valor_professor, a.valor_mensal, pa.cod_professor
			 FROM professor_aula pa, aula a 
			 WHERE pa.cod_aula = a.cod_aula 
			 AND pa.cod_aula = '$var1'
				 AND pa.cod_professor = '".$_POST['cod_professor']."'";
		$query2 = mysql_query($sql2);
		$row2 = mysql_fetch_assoc($query2);
		
		$valor = "'".$row2['valor_cobrado']."'";
		$valor_prof = $row2['valor_professor'];
		$prof = $row2['cod_professor'];
		
		
		mysql_query("INSERT INTO aluno_dia_aula (cod_aluno_pacote_aula, dia, hr_inicio, hr_fim, informacao, remarcado) VALUES ('".$_POST['cod_aluno_pacote_aula']."', '".vaiparaobanco($_POST['dia2'])."', '".$_POST['hr_inicio']."', '".$_POST['hr_fim']."', '".$_POST['informacao']."', '".$_POST['cod_aluno_dia_aula']."')") or die(mysql_error());
		$ultimo_prof = mysql_insert_id();
		
		mysql_query("UPDATE pagto_aluno SET valor = '$valor' WHERE cod_aluno_dia_aula = '".$_REQUEST['cod_aluno_dia_aula']."'");

		mysql_query("DELETE FROM aluno_pacote_professor WHERE cod_aluno_dia_aula = '".$_REQUEST['cod_aluno_dia_aula']."'");
		
		mysql_query("INSERT INTO aluno_pacote_professor (cod_pacote, cod_aluno_dia_aula, cod_professor) VALUES ('$var', '$ultimo_prof', '".$_REQUEST['cod_professor']."')");
		$ultimo = mysql_insert_id();
		
		mysql_query("INSERT INTO pagto_professor (cod_aluno_pacote_professor, cod_professor, valor, dt_gerado, dt_vencimento) VALUES ('$ultimo', '$prof', '$valor_prof', DATE(NOW()), '". vaiparaobanco($_REQUEST['dia2']) ."')") or die(mysql_error());

		
			
	}
	else
	{
	
  
  
  	$sql1 = "SELECT *, DAY(dt_inicio)AS dia FROM aluno_pacote WHERE cod_pacote = '$var'";
	$query1 = mysql_query($sql1) or die(mysql_error());
	$row1 = mysql_fetch_assoc($query1);
	
	$sql2 = "SELECT pa.valor_cobrado, pa.valor_professor, a.valor_mensal, pa.cod_professor
			 FROM professor_aula pa, aula a 
			 WHERE pa.cod_aula = a.cod_aula 
			 AND pa.cod_aula = '$var1'
			 AND pa.cod_professor = '".$_POST['cod_professor']."'";
	$query2 = mysql_query($sql2);
	$row2 = mysql_fetch_assoc($query2);
	
	  if(isset($_POST['dia2']))
	  {
			$insertSQL = sprintf("INSERT INTO aluno_pacote_aula (cod_pacote, cod_aula) VALUES (%s, %s)",
                       GetSQLValueString($_POST['cod_pacote'], "int"),
                       GetSQLValueString($_POST['cod_aula'], "int"));

			mysql_select_db($database_conecta, $conecta);
			$Result1 = mysql_query($insertSQL, $conecta) or die(mysql_error());
			$ultimo = mysql_insert_id();
			
			mysql_query("INSERT INTO aluno_dia_aula (cod_aluno_pacote_aula, dia, hr_inicio, hr_fim) VALUES ('$ultimo', '".vaiparaobanco($_POST['dia2'])."', '".$_POST['hr_inicio']."', '".$_POST['hr_fim']."') ") or die(mysql_error());
	  }
	  else
	  {
		  		
				//inicio for
				if(count($_POST['dia']) > 0){
				foreach($_POST['dia'] as $dia){
					if($_POST['tipo'] == 1)
					{
						$dia = $dia;
						$hr_inicio = $_POST['hr_inicio'];
						$hr_fim = $_POST['hr_fim'];
					}
					else
					{
						$ex_dia = explode('|',$dia);
						$dia = $ex_dia[0];
						$hr_inicio = $ex_dia[1];
						$hr_fim = $ex_dia[2];
						$cod_aula_dia = $ex_dia[3];
					}
					
					$i = 0;
					for($hoje = $row1['dt_inicio']; $hoje <= $row1['dt_fim']; $hoje = date('Y-m-d', strtotime('+1 day', strtotime($hoje))) )
					{
						if(date('w', strtotime($dia)) == DiasemanaW($hoje))
						{
							if($i == 0)
							{
								$insertSQL = sprintf("INSERT INTO aluno_pacote_aula (cod_pacote, cod_aula) VALUES (%s, %s)",
										   GetSQLValueString($_POST['cod_pacote'], "int"),
										   GetSQLValueString($_POST['cod_aula'], "int"));
					
								mysql_select_db($database_conecta, $conecta);
								$Result1 = mysql_query($insertSQL, $conecta) or die(mysql_error());
								$ultimo[$cod_aula_dia] = mysql_insert_id();
							}

		
							
							$vencimento = explode('-', $hoje);
							$vencimento = $vencimento[0]."-".$vencimento[1]."-".Verifica_dia($row1['dia_pagto']);
							
							mysql_query("INSERT INTO aluno_dia_aula (cod_aluno_pacote_aula, dia, hr_inicio, hr_fim ) VALUES ('".$ultimo[$cod_aula_dia]."', '". $hoje ."', '$hr_inicio', '$hr_fim') ") or die(mysql_error());
							$id_aula = mysql_insert_id();
							if(isset($cod_aula_dia)){
								mysql_query("UPDATE aluno_pacote_aula SET cod_aula_dia = '$cod_aula_dia' WHERE cod_aluno_pacote_aula = '".$ultimo[$cod_aula_dia]."'") or die(mysql_error());
							}
							if($_POST['tipo'] == 1)
							{
								$valor = "'".$row2['valor_cobrado']."'";
								$valor_prof = $row2['valor_professor'];
								$prof = $row2['cod_professor'];
								mysql_query("INSERT INTO aluno_pacote_professor (cod_professor, cod_aluno_dia_aula, cod_pacote) VALUES ('$prof', '$id_aula', '$var')") or die(mysql_error());
								$ultimo_prof = mysql_insert_id();
								mysql_query("INSERT INTO pagto_professor (cod_aluno_pacote_professor, cod_professor, valor, dt_gerado, dt_vencimento) VALUES ('$ultimo_prof', '$prof', '$valor_prof', DATE(NOW()), '". $hoje ."')") or die(mysql_error());
							}
							else
							{
								$valor = "(SELECT SUM(valor_cobrado) FROM professor_aula WHERE cod_aula = '".$_POST['cod_aula']."' AND cod_aula_dia = '$cod_aula_dia')";
							}
							
							if($row2['valor_mensal'] == 'N')
							{
								mysql_query("INSERT INTO pagto_aluno (cod_aluno_dia_aula, cod_aluno_pacote_aula, cod_pacote, cod_aluno, valor, dt_vencimento, cod_aula) VALUES ('$id_aula', '".$ultimo[$cod_aula_dia]."', '$var', '".$row1['cod_aluno']."', $valor, '$vencimento', '$var1')") or die (mysql_error());
								$valor_prof = $row2['valor_professor'];
								$prof = $row2['cod_professor'];
								mysql_query("INSERT INTO aluno_pacote_professor (cod_professor, cod_aluno_dia_aula, cod_pacote) VALUES ('$prof', '$id_aula', '$var')") or die(mysql_error());
								$ultimo_prof = mysql_insert_id();
								mysql_query("INSERT INTO pagto_professor (cod_aluno_pacote_professor, cod_professor, valor, dt_gerado, dt_vencimento) VALUES ('$ultimo_prof', '$prof', '$valor_prof', DATE(NOW()), '". $hoje ."')") or die(mysql_error());

							}
							else
							{
								if($_POST['tipo'] == 1)
								{								
									$sql3 = "SELECT pa.cod_professor, pa.valor_cobrado, pa.valor_professor, a.valor_mensal 
											 FROM professor_aula pa, aula a 
											 WHERE pa.cod_aula = a.cod_aula 
											 AND pa.cod_aula = '$var1'
											 AND pa.cod_aula_dia IS NULL";
								}
								else
								{
									$sql3 = "SELECT pa.cod_professor, pa.valor_cobrado, pa.valor_professor, a.valor_mensal 
											 FROM professor_aula pa, aula a 
											 WHERE pa.cod_aula = a.cod_aula 
											 AND pa.cod_aula = '$var1'
											 AND pa.cod_aula_dia = '$cod_aula_dia'";
								}
								$query3 = mysql_query($sql3) or die(mysql_query());
								$row3 = mysql_fetch_assoc($query3);
								
								if($a < $_POST['pacote'])
								{
								
									do{
										$valor = "'". ($row3['valor_cobrado'] + $valor) ."'";
										$valor_prof2 = $row3['valor_professor'];
										$prof = $row3['cod_professor'];
										mysql_query("INSERT INTO aluno_pacote_professor (cod_professor, cod_aluno_dia_aula, cod_pacote) VALUES ('$prof', '$id_aula', '$var')") or die(mysql_error());
										$ultimo_prof2 = mysql_insert_id();
										mysql_query("INSERT INTO pagto_professor (cod_aluno_pacote_professor, cod_professor, valor, dt_gerado, dt_vencimento) VALUES ('$ultimo_prof2', '$prof', '$valor_prof2', DATE(NOW()), '". $hoje ."')") or die(mysql_error());
									}while($row3 = mysql_fetch_assoc($query3));
								
								
									if($a > 0)
									{
										$vencimento = date('Y-m-d', strtotime('+1 Month', strtotime($vencimento)));	
									}
									mysql_query("INSERT INTO pagto_aluno (cod_aluno_pacote_aula, cod_pacote, cod_aluno, valor, dt_vencimento, cod_aula) VALUES ('".$ultimo[$cod_aula_dia]."', '$var', '".$row1['cod_aluno']."', $valor, '$vencimento', '$var1')") or die (mysql_error());
									$a++;
								}

							}
							$i++;
						}
					}
				};
				};

	  }
	}

  $insertGoTo = "cad_aula_pacote2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}



mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT a.nome_aluno, ap.pacote FROM aluno a, aluno_pacote ap WHERE ap.cod_aluno = a.cod_aluno AND ap.cod_pacote = '$var'";
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
            <form method="post" name="form1"  id="form1" action="<?php echo $editFormAction; ?>">
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
                  <td colspan="2" align="" class="load" nowrap>&nbsp;</td>
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
              <input name="pacote" type="hidden" id="pacote" value="<?php echo $row_ver['pacote']; ?>">
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
	$.validator.addMethod("time24", function(value, element) {
		if (!/^\d{2}:\d{2}$/.test(value)) return false;
		var parts = value.split(':');
		if (parts[0] > 23 || parts[1] > 59) return false;
		return true;
	}, "Invalid time format.");
	
	$("#form1").validate({
		rules: {
			hr_inicio: {
				time24: true	
			},
			hr_fim:{
				time24: true
			}
		},
		messages: {
			hr_inicio: {
				time24: "Digite uma hora válida."	
			},
			hr_fim:{
				time24: "Digite uma hora válida."	
			}
		}
	});

	$(".load").html('<div align="center"><img src="images/ajax_loading.gif" /></div>').load('verifica_professores.php?cod_aula=' + $("#cod_aula").val() + '&cod_pacote=<?php echo $var; ?>&cod_aluno_dia_aula=<?php echo $_GET['cod_aluno_dia_aula'] ?>');
	
	$("#cod_aula").change(function(e){
		var verifica_aula = $(this).val();
		
		$(".load").html('<div align="center"><img src="images/ajax_loading.gif" /></div>').load('verifica_professores.php?cod_aula=' + verifica_aula + '&cod_pacote=<?php echo $var; ?>&cod_aluno_dia_aula=<?php echo $_GET['cod_aluno_dia_aula'] ?>');
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
