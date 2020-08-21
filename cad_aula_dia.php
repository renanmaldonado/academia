<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php 
//Validação do módulo
$fvar2 = 5;
require_once('verifica.php'); 
$_SESSION['cod'] = '';

$var = $_REQUEST['cod_aula'];

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
  
  mysql_query("DELETE FROM aula_dia WHERE cod_aula = '$var'") or die(mysql_error());
  
  if(isset($_POST['dia']))
  {

	  $i = 0;
	  foreach($_POST['dia'] as $dia)
	  { 
		  if($dia <> '')
		  {
		  	mysql_query("INSERT INTO aula_dia (cod_aula, cod_dia, hr_inicio, hr_fim)VALUES('$var','$dia','".$_POST['hr_inicio'][$i].":00','".$_POST['hr_fim'][$i].":00')") or die(mysql_error());
		  }
		  $i++;
	  }
  }

  $insertGoTo = "cad_aula_dia.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_conecta, $conecta);
/*$query_dias =  "SELECT DISTINCT(pa.cod_aula), ds.*, ad.*, (SELECT COUNT(ac.cod_professor) FROM professor_aula ac WHERE ac.cod_aula_dia = ad.cod_aula_dia)AS qtde_prof
				FROM dia_semana ds, aula_dia ad, professor_aula pa
				WHERE ds.cod_dia = ad.dia 
				AND ad.cod_aula_dia = pa.cod_aula_dia
				AND ad.cod_aula = '$var' 
				ORDER BY ds.cod_dia ASC";
				*/
$query_dias =  "SELECT ds.*, ad.*, (SELECT COUNT(ac.cod_professor) FROM professor_aula ac WHERE ac.cod_aula_dia = ad.cod_aula_dia)AS qtde_prof
				FROM dia_semana ds, aula_dia ad
				WHERE ds.cod_dia = ad.cod_dia 
				AND ad.cod_aula = '$var' 
				ORDER BY ds.cod_dia ASC";				
$dias = mysql_query($query_dias, $conecta) or die(mysql_error());
$row_dias = mysql_fetch_assoc($dias);
$totalRows_dias = mysql_num_rows($dias);

mysql_select_db($database_conecta, $conecta);
$query_dia = "SELECT * FROM dia_semana ORDER BY cod_dia ASC";
$dia = mysql_query($query_dia, $conecta) or die(mysql_error());
$row_dia = mysql_fetch_assoc($dia);
$totalRows_dia = mysql_num_rows($dia);

mysql_select_db($database_conecta, $conecta);
$query_dia_semana = "SELECT * FROM dia_semana ORDER BY cod_dia ASC";
$dia_semana = mysql_query($query_dia_semana, $conecta) or die(mysql_error());
$row_dia_semana = mysql_fetch_assoc($dia_semana);
$totalRows_dia_semana = mysql_num_rows($dia_semana);

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT * FROM aula WHERE cod_aula = '$var'";
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
<link href="plugin/datatables/media/css/main.css" rel="stylesheet" type="text/css">
<link href="css/intranet.css" rel="stylesheet" type="text/css">

</head>
<body>
<form method="post" name="form1" id="form1" action="<?php echo $editFormAction; ?>">
  <table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="fundoform">
    <!--DWLayoutTable-->
    <tr valign="middle">
      <td height="34" colspan="3"><div align="center" class="titulo">DIA DA AULA - <?php echo $row_ver['desc_aula']; ?></div></td>
    </tr>
    <tr>
      <td width="18" height="200">&nbsp;</td>
      <td width="516" valign="top"><table width="100%" align="center" class="detalhe">
        <tr valign="baseline">
          <td width="27%" align="right" valign="middle" nowrap>Dia da semana:</td>
          <td width="73%" align="left" valign="middle"><label for="cod_dia"></label>
            <select name="dia[0]" id="cod_dia">
              <option value="">Selecione um dia</option>
              <?php
do {  
?>
              <option value="<?php echo $row_dia_semana['cod_dia']?>"><?php echo $row_dia_semana['desc_dia']?></option>
              <?php
} while ($row_dia_semana = mysql_fetch_assoc($dia_semana));
  $rows = mysql_num_rows($dia_semana);
  if($rows > 0) {
      mysql_data_seek($dia_semana, 0);
	  $row_dia_semana = mysql_fetch_assoc($dia_semana);
  }
?>
            </select></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Hora:</td>
          <td align="left" valign="middle"><input name="hr_inicio[0]" type="text" class="hora" size="5" maxlength="5">
&agrave;
<input name="hr_fim[0]" type="text"  class="hora"  size="5"maxlength="5"></td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="right" valign="middle" nowrap><hr></td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="right" valign="middle" nowrap>
          <?php 
		  if($totalRows_dias > 0)
		  {
		  ?>
          <table width="100%">
            <tr class="Verdana12cinzanegrito">
              <td width="24%">DIA DA SEMANA</td>
              <td width="66%">HORA IN&Iacute;CIAL / HORA FINAL</td>
              <td width="10%">Professores</td>
            </tr>
			<?php
            $i = 1;
            do{
            ?>
            <?php 
			if($i % 2 == 0)
			{
				$class = "gradeY";
			}
			else
			{
				$class = "gradeX";
				$hover = "";
			}
			?>
              <tr class="<?php echo $class; ?>" <?php echo $hover; ?>>
                <td><input name="dia[<?php echo $i ?>]" type="checkbox" value="<?php echo $row_dias['cod_dia']; ?>" checked>
                  <?php echo $row_dias['desc_dia']; ?></td>
                <td><input name="hr_inicio[<?php echo $i ?>]" type="text" class="hora" value="<?php echo horabr2($row_dias['hr_inicio']); ?>" size="5" maxlength="5">
                  &agrave;
                  <input name="hr_fim[<?php echo $i ?>]" type="text"  class="hora" value="<?php echo horabr2($row_dias['hr_fim']); ?>" size="5"maxlength="5"></td>
                <td align="center"><a href="javascript:void(0)" onClick="NewWindow('lista_aula_professor.php?cod_aula=<?php echo $var; ?>&add=<?php echo $row_dias['cod_aula_dia']; ?>', 'INTRANET', 700, 500)"><?php echo $row_dias['qtde_prof']; ?></a></td>
            </tr>
          <?php
		  $i++;
		}while($row_dias = mysql_fetch_assoc($dias));
		?>
      </table>
      	<?php 
		  }
		?>
      </td>
          </tr>
        <tr valign="baseline" class="grupo">
          <td align="right" valign="middle" nowrap>&nbsp;</td>
          <td align="left" valign="middle">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap><input name="cod_aula" type="hidden" id="cod_aula" value="<?php echo $var; ?>">            <input type="hidden" name="MM_insert" value="form1"></td>
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
<script type="text/javascript" src="js/jquery.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="js/jquery.maskMoney.js"></script>
<script src='plugin/fullcalendar/lib/moment.min.js'></script>
<script src='plugin/fullcalendar/fullcalendar.min.js'></script>
<script src='plugin/fullcalendar/lang/pt-br.js' charset="iso-8859-1"></script>
<script>
$(document).ready(function(e){
	/*
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
	*/
$.validator.addMethod("time", function(value, element) {  
return this.optional(element) || /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(value);  
}, "Digite uma hora válida.");


	$("#form1").validate();
	$(".hora").rules( "add", {
		minlength: 5,
		time: true,
		messages: {
			minlength: "Preencha os 4 caracteres."
		}
	});	
		
	/*
	
	$("#form1").validate({
		rules: {
			
			desc_aula: {
				required: true
			},
			max_aluno: {
				required: true
			},
			
			hr_inicio: {
				required: function(){
					var grupo = $( "#grupo:checked" ).val();
					alert(grupo);
					if(grupo == 'S')
					{
						return true;	
					}
					else
					{
						return false;
					}
				},
				time: "required time"
			}
			".hora": {
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
	});*/
	
});
</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($dias);

mysql_free_result($dia);

mysql_free_result($dia_semana);

mysql_free_result($ver);
?>
