<?php require_once('Connections/conecta.php'); ?>
<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>

<?php 
$fvar2 = 8;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var = $_REQUEST['nome'];

$dia = date('01/m/Y');
$ultimo_dia = date(cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')).'/m/Y');

$dt1 = "AND MONTH(f.dt_vencimento) <= MONTH(NOW()) AND (f.dt_previsao <= DATE(NOW()) OR f.valor_pago < f.valor)";	

if(isset($_REQUEST['filtro']) && ($_REQUEST['filtro'] == 'S'))
{
	$filtro = "AND a.nome_aluno LIKE '%$var%'";	
	
	
	if($_REQUEST['dt_inicio'] <> '')
	{
		$dt1 = "AND (f.dt_vencimento >= '".vaiparaobanco($_REQUEST['dt_inicio'])."' OR f.dt_previsao <= '".vaiparaobanco($_REQUEST['dt_inicio'])."')";	
	}
	
	if($_REQUEST['dt_fim'] <> '')
	{
		$dt2 = "AND (f.dt_vencimento <= '".vaiparaobanco($_REQUEST['dt_fim'])."' OR f.dt_previsao <= '".vaiparaobanco($_REQUEST['dt_fim'])."')";	
	}


}


if(isset($_REQUEST['cod_aluno']))
{
	$cod_aluno = "AND f.cod_aluno = '".$_REQUEST['cod_aluno']."'";	
}
if(isset($_REQUEST['cod_pacote']))
{
	$cod_pacote = "AND f.cod_pacote = '".$_REQUEST['cod_pacote']."'";	
}

	
$sql = "SELECT f.cod_fatura, a.cod_aluno, a.nome_aluno, f.valor as valor_final, (f.valor - f.valor_pago) as valor_devedor, f.dt_vencimento, f.dt_previsao
		FROM (faturamento f, aluno_pacote ap, aluno a)
		WHERE f.cod_pacote = ap.cod_pacote
		AND ap.cod_aluno = a.cod_aluno
		AND f.pagto_status <> 'S'
		AND f.valor_pago <> f.valor
		$cod_aluno $cod_pacote
		$filtro
		$dt1 $dt2";	
$_SESSION['sql']= $sql;

mysql_select_db($database_conecta, $conecta);
$query_lista = "$sql";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

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
        <form name="filtro" method="get" action="">
          <tr valign="middle">
            <td valign="middle" nowrap="nowrap">Pesquisar por nome:</td>
            <td valign="middle"><input name="nome" type="text" class="campo" id="nome" value="<?php echo $_REQUEST['nome']; ?>" size="30"></td>
            <td rowspan="3"><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>

          <tr valign="middle">
            <td nowrap="nowrap">Periodo:</td>
            <td>
            <input name="dt_inicio" type="text" class="data dt1b" id="dt_inicio" value="<?php echo ($_REQUEST['dt_inicio'] == '')? $dia : $_REQUEST['dt_inicio']; ?>" size="10" maxlength="10">
            &agrave;
            <input name="dt_fim" type="text" class="data dt2b" id="dt_fim" size="10" value="<?php echo ($_REQUEST['dt_fim'] == '')? $ultimo_dia :  $_REQUEST['dt_fim']; ?>" maxlength="10"></td>
            <td height="16"><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
          <tr valign="middle">
            <td height="35" nowrap="nowrap"><input name="filtro" type="hidden" id="filtro" value="S"></td>
            <td><input type="submit" name="Submit3" value="Filtrar" class="b-filtrar"></td>
            <td width="1134">
            </td>
          </tr>
        </form>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td rowspan="2"><div align="center" class="titulo">PAGAMENTOS &Agrave; RECEBER</div></td>
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
    <th width="46%" align="left">Nome</th>
    <th width="16%">Valor final</th>
    <th width="16%">Valor devedor</th>
    <th width="16%">Vencimento</th>
    <th width="16%">Previs&atilde;o</th>
    <th width="5%">Pagto</th>
    <th width="5%">Pacotes</th>
    <th width="2%" align="center">Ver</th>
    </tr>
  </thead>
  <tbody>
  <?php
  do{
	  
	  if(($row_lista['dt_vencimento'] < date('Y-m-d')) || (($row_lista['dt_previsao'] < date('Y-m-d')) && ($row_lista['dt_previsao'] <> '')))
	  {
		$class = "ui-state-error-text";  
	  }
	  else
	  {
		$class = "";	  
	  }

  ?>
  <tr class="<?php echo $class; ?>">
    <td><?php echo $row_lista['nome_aluno']; ?></td>
    <td align="center">R$ <?php 
	$val_fim1 = $row_lista['valor_final']; 
	$val_final1[] = $val_fim1; 
	echo moeda_br($val_fim1);
	?></td>
    <td align="center">R$ <?php echo moeda_br($row_lista['valor_devedor']); $val_fim2[] = $row_lista['valor_devedor']; ?> </td>
    <td align="center"><?php echo voltadobanco($row_lista['dt_vencimento']); ?></td>
    <td align="center"><?php echo voltadobanco($row_lista['dt_previsao']); ?></td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a href="atualiza_pagamento.php?cod_fatura=<?php echo $row_lista['cod_fatura']; ?>" onClick="NewWindow(this.href,'name','700','450','no');return false;"><img src="icones/Dollar.png" width="16" height="16" border="0"></a>
      <?php } ?></td>
    <td align="center">
      <?php if($visualizacao == "S"){ ?>
      <a href="lista_pacote.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"><img src="icones/folder.gif" width="16" height="16" border="0"></a><a href="lista_turma_aluno.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"></a>
      <?php } ?>
    </td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a onClick="NewWindow(this.href,'name','800','450','auto');return false;" href="visualiza_aluno.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"><img src="icones/viewmag.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    </tr>
  <?php 
  }while($row_lista = mysql_fetch_assoc($lista));
  ?>
  <tfoot>
      <tr class="gradeA">
        <td>&nbsp;</td>
        <td align="center" class="azul"><strong>R$ <?php echo moeda_br(array_sum($val_final1)); ?></strong></td>
        <td align="center" class="vermelho"><strong>R$ <?php echo moeda_br(array_sum($val_fim2)); ?>&nbsp;</strong></td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
  </tfoot>
  </tbody>
</table>
<?php
}
?>

<div class="container_botao">
<div style="padding: 12px;" align="center">
    <?php if($exportacao == "S"){ ?>
    <input name="Button" type="button" class="b-export" value="Exportar dados" onclick="Exportar()" />
    <script>function Exportar(){location.href="relatorios/index.php";}</script>
    <input name="print" type="button" class="b-print" value="Imprimir" onclick="Imprimir()" />
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
		},
		"order": [ 3, "asc" ],
		"bPaginate": false
	});	

});

</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($lista);
?>

