<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php 
$fvar2 = 7;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var = $_REQUEST['nome'];
$var1 = $_REQUEST['cod_professor'];

if(isset($_POST['filtro']) && ($_POST['filtro'] == 'S'))
{
	$dt1 = vaiparaobanco($_POST['dt_inicio']);
	$dt2 = vaiparaobanco($_POST['dt_fim']); 
	$filtro = "AND (p.nome_professor LIKE '%$var%')";
	$dta = "AND pp.dt_vencimento >= '$dt1'";	
	$dtb = "AND pp.dt_vencimento <= '$dt2'";
}


$sql = "SELECT pp.*
		FROM pagto_professor pp
		WHERE pp.quitado = 'S'
		AND pp.cod_professor = '$var1'
		AND pp.valor_pago > 0
		$filtro $dta $dtb
		ORDER BY pp.dt_vencimento DESC";		
$_SESSION['sql']= $sql;

mysql_select_db($database_conecta, $conecta);
$query_lista = "$sql";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT nome_professor FROM professor WHERE cod_professor = '$var1'";
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="cache-control"   content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<title>INTRANET</title>

<!-- CSS -->
<link href="plugin/datatables/media/css/main.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<link href="css/intranet.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="fundoform">
  <!--DWLayoutTable-->
  <tr> 
    <td width="21" height="19">&nbsp;</td>
    <td width="1637">&nbsp;</td>
    <td width="23">&nbsp;</td>
  </tr>
  <tr> 
    <td height="19">&nbsp;</td>
    <td valign="top"> <table width="100%" class="detalhe">
        <!--DWLayoutTable-->
        <form name="filtro" method="post" action="">
          <tr valign="middle">
            <td width="138" height="35" valign="middle" nowrap="nowrap">Data do vencimento:</td>
            <td width="288" valign="middle"><input name="dt_inicio" type="text" class="data dt1b" id="dt_inicio" value="<?php echo voltadobanco($dt1); ?>" size="10" maxlength="10" v>
              &agrave; 
              <input name="dt_fim" type="text" class="data dt2b" id="dt_fim" value="<?php echo voltadobanco($dt2); ?>" size="10" maxlength="10" ></td>
            <td width="623"><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
          <tr valign="middle">
            <td height="35" valign="middle" nowrap="nowrap"><input name="filtro" type="hidden" id="filtro" value="S"></td>
            <td valign="middle"><input type="submit" name="Submit3" value="Filtrar" class="b-filtrar"></td>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
        </form>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td rowspan="2"><div align="center" class="titulo">HIST&Oacute;RICO DE PAGAMENTO DO PROFESSOR - <?php echo $row_ver['nome_professor']; ?></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="19">&nbsp;</td>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalhe">
        <!--DWLayoutTable-->
        <tr valign="middle"> 
          <td width="461" height="19">Total de registros: <span class="font10negrito"> 
            <?php echo $totalRows_lista; ?></span></td>
          <td width="386" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
        </tr>
      </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="19">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php if($totalRows_lista == 0)
{
?>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="display cell-border" id="tabela">
    <tbody>
        <tr class="gradeA">
        	<th height="30">Sem registros...</th>
        </tr>
    </tbody>
</table>
<?php
}
else
{
?>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="display cell-border" id="tabela">
  <thead>
  <tr>
    <th width="30%">&Agrave; pagar</th>
    <th width="30%">Pago</th>
    <th width="20%" align="center">Vencimento</th>
    <th width="20%" align="center">Data do pagamento</th>
    </tr>
  </thead>
  <tbody>
  <?php
  do{
	$cod_prof = $row_lista['cod_professor'];
		  
  ?>
  <tr class="gradeA">
    <td align="center">R$
      <?php
	echo moeda_br($row_lista['valor']);
	?></td>
    <td align="center">R$
      <?php
	echo moeda_br($row_lista['valor_pago']);
	?></td>
    <td align="center"><?php echo voltadobanco($row_lista['dt_vencimento']); ?></td>
    <td align="center"><?php echo voltadobanco($row_lista['dt_pagto']); ?></td>
    </tr>
  <?php 
  }while($row_lista = mysql_fetch_assoc($lista));
  ?>
  </tbody>
</table>
<?php
}
?>

<div class="container_botao">
<div style="padding: 12px;" align="center">
    <?php if($exportacao == "S"){ ?>
    <input name="Button" type="button" class="b-export" value="Exportar dados" onClick ="$('#tabela').tableExport({type:'excel',escape:'false'});" />
    <script>function Exportar(){location.href="relatorios/index.php";}</script>
    <input name="print" type="button" class="b-print" value="Imprimir" onclick="Imprimir()"/>
    <script>function Imprimir(){window.open('relatorios/print.php','IMPRIMIR','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,fullscreen=yes');}</script>
    <?php } ?>
</div>
</div>
<br>
<br>
<br>
<br>
<!-- Javascript -->
<script type="text/javascript" src="js/jquery.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="js/jquery.maskMoney.js"></script>
<script src='plugin/fullcalendar/lib/moment.min.js'></script>
<script src='plugin/fullcalendar/fullcalendar.min.js'></script>
<script src='plugin/fullcalendar/lang/pt-br.js' charset="iso-8859-1"></script>
<script type="text/javascript" language="javascript" src="plugin/datatables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="plugin/datatables/extensions/TableTools/js/dataTables.tableTools.js"></script>

<script type="text/javascript" src="plugin/htmltable_export/tableExport.js"></script>
<script type="text/javascript" src="plugin/htmltable_export/jquery.base64.js"></script>

<script>
$(function(e) {
    $("table#tabela").DataTable({
		language: {
			url: "<?php echo $tb_language; ?>"
		}
	});	
	
	
	function customRange() {
		var minDate = $('.data.dt1b').val();
		return {
			minDate: minDate
		
		};
	}
	$('.data.dt1b').datepicker({
		maxDate: new Date()
    });
	
	$('.data.dt2b').datepicker({
        beforeShow: customRange
    });

});

</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($lista);
?>

