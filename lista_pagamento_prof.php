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

if(isset($_REQUEST['filtro']) && ($_REQUEST['filtro'] == 'S'))
{
	$dt1 = vaiparaobanco($_REQUEST['dt_inicio']);
	$dt2 = vaiparaobanco($_REQUEST['dt_fim']); 
	$filtro = "AND (p.nome_professor LIKE '%$var%')";
	$dta = "AND pp.dt_vencimento >= '$dt1'";	
	$dtb = "AND pp.dt_vencimento <= '$dt2'";
}
else
{
	$dt1 = date('Y-m-01');
	$dt2 = date('Y-m-d');
	
	$dta = "AND pp.dt_vencimento >= '$dt1'";	
	$dtb = "AND pp.dt_vencimento <= '$dt2'";
}

//Filtrar por nome
/*
$sql_mensalidade = "SELECT p.cod_professor 
					 FROM professor p 
					 WHERE pa.cod_professor NOT IN (SELECT pp.cod_professor 
												    FROM pagto_professor pp 
												    WHERE pp.cod_professor = p.cod_professor 
												    AND YEAR(pp.dt_vencimento) <= YEAR(NOW())
												    AND MONTH(pp.dt_vencimento) <= MONTH(NOW()))
					 AND p.status = 'S'";
$query_mensalidade = mysql_query($sql_mensalidade);
$row_mensalidade = mysql_fetch_assoc($query_mensalidade);
$total_mensalidade = mysql_num_rows($query_mensalidade);
$venc = date('Y-n-01',mktime(0,0,0,date('n') + 1,date('d'),date('Y')));
$venc = date('Y-m-d', strtotime("-1 days", strtotime($venc)));
	
if($total_mensalidade > 0){
	do{	
		$sql_insert = "INSERT INTO pagto_professor (cod_professor, valor, dt_gerado, dt_vencimento) VALUES ('".$row_mensalidade['cod_professor']."', '".$row_mensalidade['valor_professor']."', DATE(NOW()), '$venc')";
		mysql_query($sql_insert) or die (mysql_error());	
	}while($row_mensalidade = mysql_fetch_assoc($query_mensalidade));
}
*/
$sql1 = "SELECT DISTINCT(p.cod_professor), p.nome_professor, p.cod_professor
		FROM pagto_professor pp, professor p
		WHERE pp.cod_professor = p.cod_professor
		AND pp.quitado = 'N'
		AND pp.cod_atr IN (1,4)
		$filtro
		$dta $dtb";	
		
$sql = "SELECT DISTINCT(p.cod_professor), p.nome_professor, p.cod_professor
		FROM pagto_professor pp, professor p
		WHERE pp.cod_professor = p.cod_professor
		AND pp.quitado = 'N'
		AND pp.cod_atr IN (1,4)
		$filtro
		$dta $dtb";		
$_SESSION['sql']= $sql;

mysql_select_db($database_conecta, $conecta);
$query_lista = "$sql";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows(mysql_query($sql1));
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
            <td width="138" height="35" valign="middle" nowrap="nowrap">Pesquisar por nome:</td>
            <td width="288" valign="middle"><input name="nome" type="text" class="campo" id="nome" size="30" value="<?php echo $_POST['nome']; ?>"></td>
            <td width="623">
            </td>
          </tr>
          <tr valign="middle">
            <td height="35" valign="middle" nowrap="nowrap">Data do vencimento:</td>
            <td valign="middle"><input name="dt_inicio" type="text" class="data dt1b" id="dt_inicio" value="<?php echo voltadobanco($dt1); ?>" size="10" maxlength="10" v>
              &agrave; 
              <input name="dt_fim" type="text" class="data dt2b" id="dt_fim" value="<?php echo voltadobanco($dt2); ?>" size="10" maxlength="10" ></td>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
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
    <td rowspan="2"><div align="center" class="titulo">GERENCIAMENTO DE PAGAMENTO PROFESSORES </div></td>
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
    <th width="41%" align="left">Nome</th>
    <th width="28%">&Agrave; pagar</th>
    <th width="20%" align="center">Vencimento</th>
    <th width="4%" align="center">Pagto</th>
    <th width="3%" align="center">Ver</th>
    </tr>
  </thead>
  <tbody>
  <?php
  do{	
  $prof = $row_lista['cod_professor'];
  	$sql1 = "SELECT pp.cod_professor, SUM(pp.valor)AS valor, pp.dt_vencimento 
			FROM pagto_professor pp 
			WHERE pp.quitado = 'N' 
			AND pp.cod_professor = '$prof'
			$dta $dtb";	 
	$query = mysql_query($sql1) or die(mysql_error());
	$row = mysql_fetch_assoc($query);		
			 
  ?>
  <tr class="gradeA">
    <td><?php echo $row_lista['nome_professor']; ?></td>
    <td align="center">R$
      <?php
	echo moeda_br($row['valor']);
	?></td>
    <td align="center"><?php echo voltadobanco($row['dt_vencimento']); ?></td>
    <td align="center"><?php if($cadastro == "S"){ ?>
      <a href="atualiza_pagamento_professor.php?cod_professor=<?php echo $row_lista['cod_professor']; ?>" onClick="NewWindow(this.href,'name','700','450','no');return false;"><img src="icones/Dollar.png" width="16" height="16" border="0"></a>
      <?php } ?></td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a onClick="NewWindow(this.href,'name','600','450','no');return false;" href="visualiza_professor.php?cod_professor=<?php echo $row_lista['cod_professor']; ?>"><img src="icones/viewmag.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
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

});

</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($lista);
?>

