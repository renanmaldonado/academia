<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php 
$fvar2 = 2;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var = $_REQUEST['cod_aluno'];
//Filtrar por nome

$sql = "SELECT ap.*, 
		(SELECT COUNT(DISTINCT(aa.cod_aula)) FROM aluno_pacote_aula aa WHERE aa.cod_pacote = ap.cod_pacote)AS qtde_aula,
		(SELECT SUM(pa.valor) FROM pagto_aluno pa WHERE pa.cod_pacote = ap.cod_pacote AND pa.cod_atr = 3)AS desco,
		(SELECT SUM(pa.valor) FROM pagto_aluno pa WHERE pa.cod_pacote = ap.cod_pacote AND pa.cod_atr = 2 LIMIT 1)AS acres,
		(SELECT SUM(pa.valor) FROM pagto_aluno pa WHERE pa.cod_pacote = ap.cod_pacote AND pa.cod_atr = 1 AND pa.valor > 0)AS valor,
		(SELECT COUNT(f.cod_fatura) FROM faturamento f WHERE f.cod_pacote = ap.cod_pacote AND f.pagto_status = 'S')AS recibo,
		(SELECT COUNT(f.cod_fatura) FROM faturamento f WHERE f.cod_pacote = ap.cod_pacote)AS faturamento  
		FROM aluno_pacote ap
		WHERE ap.cod_aluno = '$var'";
				
$_SESSION['sql']= $sql;

mysql_select_db($database_conecta, $conecta);
$query_lista = "$sql";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT a.nome_aluno FROM aluno a WHERE a.cod_aluno = '$var'";
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
<link href="css/intranet.css" rel="stylesheet" type="text/css">

</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="fundoform">
  <!--DWLayoutTable-->
  <tr> 
    <td height="19">&nbsp;</td>
    <td><div align="center" class="titulo">GERENCIAMENTO DE PACOTES DO ALUNO - <?php echo $row_ver['nome_aluno']; ?></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="19">&nbsp;</td>
    <td valign="top"><table width="100%" class="detalhe">
      <tr>
    <td width="23%" align="left">&nbsp;</td>
    <td width="58%" align="right">&nbsp;</td>
    <td width="19%" align="right"><input name="button" type="submit" class="b-novo" id="button" value="Adicionar" onClick="NewWindow('cad_pacote.php?cod_aluno=<?php echo $var; ?>','name','700','600','yes');return false;"></td>
      </tr>
</table>
</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td>
</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php if($totalRows_lista == 0)
{
?>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="display cell-border">
    <tr class="gradeA">
		<td height="30" align="center">Sem registros...</td>
    </tr>
</table>
<?php
}
else
{
?>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="display cell-border" id="tabela">
  <thead>
  	<tr>
  	  <th width="2%" align="left">COD</th>
        <th width="16%" align="left">Pacote</th>
        <th width="7%" align="center">Aulas</th>
        <th width="10%">Valor do pacote</th>
        <th width="10%">Desconto</th>
        <th width="10%">Acr&eacute;scimo</th>
        <th width="11%">Valor</th>
        <th width="9%">Data in&iacute;cial</th>
        <th width="10%">Data final</th>
        <th width="4%">Gerar fatura</th>
        <th width="3%">Recibo</th>
        <th width="4%">Atualiza</th>
        <th width="4%">Excluir</th>
    </tr>
  </thead>
  <tbody>
  	  <?php
	  do{
		  
	  ?>
      <tr>
        <td><?php echo $row_lista['cod_pacote']; ?></td>
        <td><?php 
		switch ($row_lista['pacote']) {
			case "12": $plano = Anual; break;
			case "6": $plano = Semestral; break;
			case "3": $plano = Trimestral; break;
			case "2": $plano = Bimestral; break;
			case "1": $plano = Mensal; break;
			case "0": $plano = Avulso; break;
		}

		echo $plano; ?></td>
        <td align="center"><a href="lista_aulas_pacote.php?cod_pacote=<?php echo $row_lista['cod_pacote']; ?>"><?php echo $row_lista['qtde_aula']; ?></a></td>
        <td align="center">R$ <?php echo moeda_br($row_lista['valor']); $val = $row_lista['valor'] + $val; ?></td>
        <td align="center">R$ <?php echo moeda_br($row_lista['desco']); $val1 = $row_lista['desco'] + $val1;?></td>
        <td align="center">R$ <?php echo moeda_br($row_lista['acres']); $val2 = $row_lista['acres'] + $val2; ?></td>
        <td align="center">R$ <?php echo moeda_br($row_lista['valor'] + $row_lista['acres'] + $row_lista['desco']); $val3 = $row_lista['valor'] + $row_lista['acres'] + $row_lista['desco'] + $val3; ?></td>
        <td align="center"><?php echo voltadobanco($row_lista['dt_inicio']); ?></td>
        <td align="center"><?php echo voltadobanco($row_lista['dt_fim']); ?></td>
        <td align="center">
		<?php 
		if($visualizacao == "S"){ 
		
		if($row_lista['faturamento'] == 0)
		{
			$class = '';
			$text = "Faturar";
		}
		else
		{
			$class = 'class="delete" rel="Esse faturamento já foi realizado. Deseja refazer o faturamento desse pacote?"';	
			$text = "Faturado";
		}

		?>
          <a <?php echo $class; ?> href="gerar_fatura.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>&cod_pacote=<?php echo $row_lista['cod_pacote']; ?>" ><?php echo $text; ?></a>
        <?php } ?></td>
        <td align="center">
        <?php
		if($row_lista['recibo'] > 0)
		{
		?>
        <a href="visualiza_pagamentos.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>&cod_pacote=<?php echo $row_lista['cod_pacote']; ?>" onClick="NewWindow(this.href, 'RECIBO', '700', '600'); return false;"><img src="icones/Print.png" width="16" height="16"></a>
        <?php 
		}
		?>
        </td>
        <td align="center"><?php if($alteracao == "S"){ ?>          <a href="atualiza_pacote.php?cod_pacote=<?php echo $row_lista['cod_pacote']; ?>" onClick="NewWindow(this.href,'name','700','600','yes');return false;"><img src="icones/edititem.gif" width="16" height="16"></a>
        <?php } ?></td>
        <td><?php if($exclusao == "S"){ ?>
          <a href="exclui_pacote.php?cod_aluno=<?php echo $var; ?>&cod_pacote=<?php echo $row_lista['cod_pacote']; ?>" class="delete" rel="Tem certeza que deseja excluir esse registro?"> <img src="icones/ico_remover.gif" width="16" height="16" border="0"> </a>
        <?php } ?></td>
      </tr>
      <?php
	  $desc_geral = $row_lista['desconto'];
	  $ac_geral = $row_lista['acrescimo'];
	  }while($row_lista = mysql_fetch_assoc($lista));
	  ?>
  </tbody>
  <tfoot>
  <tr>
    <td>&nbsp;</td>
    <td></td>
    <td></td>
    <td class="Verdana12cinzanegrito gradeZ" align="center">R$ <?php echo moeda_br($val); ?></td>
    <td class="Verdana12cinzanegrito gradeX" align="center">R$ <?php echo moeda_br($val1); ?></td>
    <td class="Verdana12cinzanegrito gradeY" align="center">R$ <?php echo moeda_br($val2); ?></td>
    <td class="Verdana12cinzanegrito gradeZ" align="center">R$ <?php echo moeda_br($val3); ?></td>
    <td align="center">&nbsp;</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
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
    <script>function Imprimir(){window.open('relatorios/print.php','IMPRIMIR','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,fullscreen=yes');}</script>
    <?php } ?>
    <input name="voltar" type="button" class="b-voltar voltar" value="Voltar">
</div>
</div>
<br>
<br>
<br>
<br>

<!-- Javascript -->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="plugin/datatables/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="plugin/datatables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="plugin/datatables/extensions/TableTools/js/dataTables.tableTools.js"></script>

<script type="text/javascript" src="plugin/htmltable_export/tableExport.js"></script>
<script type="text/javascript" src="plugin/htmltable_export/jquery.base64.js"></script>
<script>
$(document).ready(function(e) {
    $("#tabela").DataTable({
		language: {
			url: "<?php echo $tb_language; ?>"
		},
		"bSort": false
	});	
	
});
</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($lista);
?>

