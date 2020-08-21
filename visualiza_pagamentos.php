<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>

<?php 
$fvar2 = 9;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$cod = $_REQUEST['cod_aluno'];
$var = $_REQUEST['cod_pacote'];
	
if($var <> '')
{
	$pac = "AND f.cod_pacote = '$var'";	
}
		
$sql = "SELECT h.*, f.cod_pacote, f.pagto_status, f.cod_pacote, f.cod_fatura, ap.cod_aluno, f.parcela
		FROM historico_pagto_faturamento h, faturamento f, aluno_pacote ap
		WHERE ap.cod_pacote = f.cod_pacote
		AND f.cod_fatura = h.cod_fatura
		AND ap.cod_aluno = '$cod'
		$pac";

mysql_select_db($database_conecta, $conecta);
$query_lista = $sql;
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows(mysql_query($sql));
$_SESSION['sql']= $sql;

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
            <td nowrap="nowrap">Pacote:</td>
            <td colspan="2"><label for="cod_pacote"></label>
            <input type="text" name="cod_pacote" id="cod_pacote" value="<?php echo $var; ?>" class="numero"></td>
          </tr>
          <tr valign="middle">
            <td width="58" nowrap="nowrap">Periodo:</td>
            <td colspan="2"><input name="dt_inicio" type="text" class="data dt1" id="dt_inicio" size="10" value="<?php echo $_POST['dt_inicio']; ?>" >              <input name="dt_fim" type="text" class="data dt2" id="dt_fim" size="10" value="<?php echo $_POST['dt_fim']; ?>" ></td>
          </tr>
          <tr valign="middle">
            <td height="35" nowrap="nowrap"><input name="filtro" type="hidden" id="filtro" value="S"></td>
            <td colspan="2"><input type="submit" name="Submit3" value="Filtrar" class="b-filtrar"></td>
          </tr>
        </form>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td rowspan="2"><div align="center" class="titulo">MENSALIDADES PAGAS</div></td>
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
<?php 
if($totalRows_lista == 0)
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
    <th width="16%">COD PACOTE</th>
    <th width="16%">Parcela</th>
    <th width="16%">Data do pagamento</th>
    <th width="15%">Valor</th>
    <th width="66%" align="center">Informa&ccedil;&atilde;o</th>
    <th width="4%">Recibo</th>
    <th width="3%" align="center">Ver aluno</th>
    </tr>
  </thead>
  <tbody>
  <?php
  do{
  ?>
  <tr>
    <td align="center"><?php echo ($row_lista['cod_pacote']);?></td>
    <td align="center"><?php echo ($row_lista['parcela']);?></td>
    <td align="center"><?php echo voltadobanco($row_lista['dt_pago']);?></td>
    <td align="center">R$ <?php echo moeda_br($row_lista['valor']); $dev[] = $row_lista['valor']; ?></td>
    <td align=""><?php echo ($row_lista['informação']);?></td>
    <td align="center"><?php if($row_lista['pagto_status'] == 'S'){ ?><a href="recibo.php?cod=<?php echo $row_lista['cod_pacote']; ?>&cod_fatura=<?php echo $row_lista['cod_fatura']; ?>" onClick="NewWindow(this.href, 'RECIBO', '700', '600'); return false;"><img src="icones/Print.png" width="16" height="16"></a><?php } ?></td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a onClick="NewWindow(this.href,'name','800','450','no');return false;" href="visualiza_aluno.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"><img src="icones/viewmag.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    </tr>
  <?php 
  }while($row_lista = mysql_fetch_assoc($lista));
  ?>
  <tfoot>
      <tr class="gradeA">
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center" class="verde"><strong>R$ <?php echo moeda_br(array_sum($dev)); ?></strong></td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
  </tfoot>
</table>
<?php
}
?>

<div class="container_botao">
<div style="padding: 12px;" align="center">
    <?php if($exportacao == "S"){ ?>
    <input name="Button" type="button" class="b-export" value="Exportar dados" onClick ="$('#tabela').tableExport({type:'excel',escape:'false'});" />
    <script>function Exportar(){location.href="relatorios/index.php";}</script>
    <input name="print" type="button" class="b-print" value="Imprimir" onclick="Imprimir()" />
    <input name="button" type="button" class="b-voltar voltar" id="button" value="Voltar">
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
$(document).ready(function(e) {
    $("table#tabela").DataTable({
		language: {
			url: "<?php echo $tb_language; ?>"
		},
		"order": [[ 0, "asc" ]]
	});	

	$(".dt1").change(function(){
			
	})
});
</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($lista);
?>

