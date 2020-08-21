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
$var = $_REQUEST['nome'];

if(isset($_REQUEST['filtro']) && ($_REQUEST['filtro'] == 'S'))
{
	if(isset($filtro))
	{
		$filtro = "AND a.nome_aluno LIKE '%$var%'";
	}
	
	if(isset($_REQUEST['dt_inicio']) && ($_REQUEST['dt_inicio'] <> ''))
	{
		$dt = "AND pa.dt_pagto >= '".$_REQUEST['dt_inicio']."'";
	}
	
	if(isset($_REQUEST['dt_fim']) && ($_REQUEST['dt_fim'] <> ''))
	{
		$dt .= "AND pa.dt_pagto <= '".$_REQUEST['dt_fim']."'";
	}
	
}

//Filtrar por nome
		
$sql = "SELECT DISTINCT(a.cod_aluno), f.dt_pago, a.nome_aluno, (SELECT SUM(valor_pago) FROM faturamento f1, aluno_pacote ap1 WHERE f1.cod_pacote = ap1.cod_pacote AND ap1.cod_aluno = a.cod_aluno)AS valor
		FROM faturamento f, aluno_pacote ap, aluno a
		WHERE f.cod_pacote = ap.cod_pacote
		AND ap.cod_aluno = a.cod_aluno
		AND f.pagto_status <> 'N'
		$filtro $aula $dt
		ORDER BY a.nome_aluno ASC";
$_SESSION['sql']= $sql;

mysql_select_db($database_conecta, $conecta);
$query_lista = $sql;
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows(mysql_query($sql));

mysql_select_db($database_conecta, $conecta);
$query_modalidades = "SELECT * FROM aula ORDER BY desc_aula ASC";
$modalidades = mysql_query($query_modalidades, $conecta) or die(mysql_error());
$row_modalidades = mysql_fetch_assoc($modalidades);
$totalRows_modalidades = mysql_num_rows($modalidades);
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
            <td width="138" nowrap="nowrap">Pesquisar por nome:</td>
            <td colspan="2"><input name="nome" type="text" class="campo" id="nome" size="30" onFocus="this.className='campo_over'" onBlur="this.className='campo'">
            <input name="filtro" type="hidden" id="filtro" value="S"></td>
            <td rowspan="2"><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
          <tr valign="middle">
            <td height="35" nowrap="nowrap">Periodo:</td>
            <td width="74"><input name="dt_inicio" type="text" class="data dt1" id="dt_inicio" size="10" value="<?php echo $_POST['dt_inicio']; ?>" ></td>
            <td width="214"><input name="dt_fim" type="text" class="data dt2" id="dt_fim" size="10" value="<?php echo $_POST['dt_fim']; ?>" ></td>
          </tr>
          <tr valign="middle">
            <td height="35" nowrap="nowrap"><input name="filtro" type="hidden" id="filtro" value="S"></td>
            <td colspan="2"><input type="submit" name="Submit3" value="Filtrar" class="b-filtrar"></td>
            <td width="1134"><!--DWLayoutEmptyCell-->&nbsp;</td>
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
    <th width="46%" align="left">Nome</th>
    <th width="21%">&Uacute;ltimo pagamento</th>
    <th width="22%">Valor</th>
    <th width="4%">Pacotes</th>
    <th width="3%" align="center">Ver aluno</th>
    <th width="4%" align="center">Ver hist&oacute;rico</th>
    </tr>
  </thead>
  <tbody>
  <?php
  do{
  ?>
  <tr>
    <td><?php echo $row_lista['nome_aluno']; ?></td>
    <td align="center"><?php echo voltadobanco($row_lista['dt_pago']);?></td>
    <td align="center">R$ <?php echo moeda_br($row_lista['valor']); $dev[] = $row_lista['valor']; ?></td>
    <td align="center">
      <?php if($visualizacao == "S"){ ?>
      <a href="lista_pacote.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"><img src="icones/folder.gif" width="16" height="16" border="0"></a><a href="lista_turma_aluno.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"></a>
      <?php } ?>
    </td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a onClick="NewWindow(this.href,'name','800','450','no');return false;" href="visualiza_aluno.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"><img src="icones/viewmag.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a href="visualiza_pagamentos.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"><img src="icones/viewmag.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    </tr>
  <?php 
  }while($row_lista = mysql_fetch_assoc($lista));
  ?>
  <tfoot>
      <tr class="gradeA">
        <td>&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center" bgcolor="#CEFFCE"><strong>R$ <?php echo moeda_br(array_sum($dev)); ?></strong></td>
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
    <input name="Button" type="button" class="b-export" value="Exportar dados" onClick ="$('#tabela').tableExport({type:'excel',escape:'false'});" />
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

