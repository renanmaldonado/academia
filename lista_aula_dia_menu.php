<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
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

mysql_select_db($database_conecta, $conecta);
$query_lista_dias = "SELECT * FROM dia_semana ORDER BY cod_dia ASC";
$lista_dias = mysql_query($query_lista_dias, $conecta) or die(mysql_error());
$row_lista_dias = mysql_fetch_assoc($lista_dias);
$totalRows_lista_dias = mysql_num_rows($lista_dias);
?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php 
$fvar2 = 2;
require_once('verifica.php'); 
?>
<?php 
header('Content-Type: text/html; charset=iso-8859-1');

unset($_SESSION['sql']); 
$var = $_REQUEST['cod_aula'];
//Filtrar por nome

//Validação da permissão
if($cadastro == "N")
{
	echo $mensg = "<script>alert('Você não tem permissão para cadastro neste módulo')</script>";
	echo $mensg = "<script>window.close();</script>";
	echo $mensg = "<script>history.back();</script>";
	exit;
}
		
mysql_select_db($database_conecta, $conecta);
$query_ver_dia = "SELECT ds.*, ad.*, a.grupo 
				FROM dia_semana ds, aula_dia ad, aula a
				WHERE ds.cod_dia = ad.dia 
				AND a.cod_aula = ad.cod_aula
				AND ad.cod_aula = '$var' 
				ORDER BY ds.cod_dia ASC";
$ver_dia = mysql_query($query_ver_dia, $conecta) or die(mysql_error());
$row_ver_dia = mysql_fetch_assoc($ver_dia);
$totalRows_ver_dia = mysql_num_rows($ver_dia);

?>
<?php
$mes = date("m");
$ano = date("Y");
$dia = date("d");
$dia_semana = date("w");

$cont=0;


?>
<?php 
if($row_ver_dia['grupo'] == 'S')
{
?>
<select name="cod_dia" id="cod_dia">
	<?php
    do { 
	if($cont < 7){
		$dia_calendario = date("Y-m-d",mktime(0,0,0,$mes,$dia-$dia_semana,$ano));
	}
	else
	{
		$dia_calendario = "";	
	}
    ?>
    	<option value="<?php echo $row_ver_dia['cod_dia']; ?>;<?php echo $dia_calendario; ?>" ><?php echo $row_ver_dia['desc_dia']?> - <?php echo horabr2($row_ver_dia['hr_inicio']); ?> à <?php echo horabr2($row_ver_dia['hr_fim']); ?></option>
    <?php
	$dia_semana--;
   	$cont++;
    } while ($row_ver_dia = mysql_fetch_assoc($ver_dia));
    ?>
</select>
<?php 
}
else
{
?>
<form action="" method="post" name="up">
<table width="100%">
  <tr class="Verdana12cinzanegrito">
    <td>Dia</td>
    <td>Hora</td>
  </tr>
  <?php 
  $i = 0;
  do { ?>
    <tr>
      <td><input type="checkbox" name="dia[]" value="<?php echo $row_lista_dias['cod_dia']; ?>">
        <?php echo $row_lista_dias['desc_dia']; ?></td>
      <td><input name="hr_inicio[<?php echo $i; ?>]" type="text" size="5" maxlength="5">
        &agrave;
        <input name="hr_fim[<?php echo $i; ?>]" type="text" size="5" maxlength="5"></td>
    </tr>
    <?php 
	$i++;
	} while ($row_lista_dias = mysql_fetch_assoc($lista_dias)); ?>
</table>
</form>


<?php 
}
?>
           
<script>
var dia = $("#cod_dia").val();

$("#horario").load("lista_hora_disponivel.php?dia=" + dia + "&cod_aula=<?php echo $var1 ?>&cod_aluno=<?php echo $var ?>");

$("#cod_dia").change(function(){
	var dia = $("#cod_dia").val();
	$("#horario").load("lista_hora_disponivel.php?dia=" + dia + "&cod_aula=<?php echo $var1 ?>&cod_aluno=<?php echo $var ?>");
});

/*
$("#cod_dia").change(function(){
	var url = "verifica_hora_disponivel.php";
	$.ajax(
		{
		  type: "POST",
		  url: url,
		  data: $("#form1").serializeArray(),
		  success: function(data, textStatus, jqXHR)
		  {
			$("#hr_inicio-error").html(data); 
		  },
		  error: function(jqXHR, textStatus, errorThrown)
		  {
			$("#hr_inicio-error']").html('Mensagem de erro aqui!');   
		  }
		}
	);		
		
});
*/
</script>            
<?php
mysql_free_result($lista_dias);
?>
