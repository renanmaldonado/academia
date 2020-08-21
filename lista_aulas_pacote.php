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
$var = $_REQUEST['cod_pacote'];
//Filtrar por nome

if(isset($_GET['filtro'])){
	$filtro = " AND a.cod_aula = '".$_GET['filtro']."'";
}

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT a.nome_aluno FROM aluno a, aluno_pacote ap WHERE ap.cod_aluno = a.cod_aluno AND ap.cod_pacote = '$var'";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

mysql_select_db($database_conecta, $conecta);
$query_lista = "SELECT a.*, DATE_FORMAT(aa.dia, '%w')AS dia_semana, aa.*, au.desc_aula, au.grupo
			    FROM aluno_pacote_aula a, aluno_dia_aula aa, aula au 
				WHERE a.cod_aula = au.cod_aula 
				AND aa.cod_aluno_pacote_aula = a.cod_aluno_pacote_aula
				AND aa.cod_aluno_dia_aula NOT IN(SELECT aa2.remarcado FROM aluno_dia_aula aa2 WHERE aa2.cod_aluno_pacote_aula = aa.cod_aluno_pacote_aula AND aa2.remarcado IS NOT NULL)
				AND a.cod_pacote = '$var' $filtro
				ORDER BY au.desc_aula ASC, aa.dia ASC";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

if($totalRows_lista == 0)
{
	mysql_query("DELETE FROM aluno_pacote_aula WHERE cod_pacote = '$var'");	
}

mysql_select_db($database_conecta, $conecta);
$query_modalidade = "SELECT * FROM aula WHERE cod_aula IN (SELECT cod_aula FROM aluno_pacote_aula WHERE cod_pacote = '$var') ORDER BY desc_aula ASC";
$modalidade = mysql_query($query_modalidade, $conecta) or die(mysql_error());
$row_modalidade = mysql_fetch_assoc($modalidade);
$totalRows_modalidade = mysql_num_rows($modalidade);



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
    <td><div align="center" class="titulo">GERENCIAMENTO DE AULAS DO ALUNO <?php echo $row_ver['nome_aluno']; ?></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="19">&nbsp;</td>
    <td valign="top"><table width="100%" class="detalhe">
      <tr>
    <td width="23%" align="left"><form name="form1" method="get" action="">
      <table width="100%">
        <tr>
          <td width="30%">Modalidade:</td>
          <td width="70%"><select name="filtro" id="filtro">
            <?php
do {  
?>
            <option value="<?php echo $row_modalidade['cod_aula']?>"<?php if (!(strcmp($row_modalidade['cod_aula'], $_GET['filtro']))) {echo "selected=\"selected\"";} ?>><?php echo $row_modalidade['desc_aula']?></option>
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
        <tr>
          <td>&nbsp;</td>
          <td><input name="button2" type="submit" class="b-filtrar" id="button2" value="Filtrar">
            <input name="cod_pacote" type="hidden" id="cod_pacote" value="<?php echo $_GET['cod_pacote']; ?>"></td>
        </tr>
      </table>
    </form></td>
    <td width="58%" align="right">&nbsp;</td>
    <td width="19%" align="right"><input name="button" type="submit" class="b-novo" id="button" value="Adicionar" onClick="NewWindow('cad_aula_pacote2.php?cod_pacote=<?php echo $var; ?>','name','700','600','yes');return false;"></td>
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
  	  <th width="12%" align="left">Modalidade</th>
  	  <th width="12%" align="left">Dia</th>
        <th width="52%" align="left">Data</th>
        <th width="12%">Hora in&iacute;cial</th>
        <th width="12%">Hora final</th>
        <th width="12%">Remarcado?</th>
        <th width="5%">Remarcar</th>
        <th width="5%">Excluir</th>
    </tr>
  </thead>
  <tbody>
  	  <?php
	  do{
		  
	  ?>
      <tr>
        <td><?php echo $row_lista['desc_aula']; ?></td>
        <td><?php echo diasemana($row_lista['dia_semana']); ?></td>
        <td><?php echo voltadobanco($row_lista['dia']); ?></td>
        <td align="center"><?php echo horabr2($row_lista['hr_inicio']); ?></td>
        <td align="center"><?php echo horabr2($row_lista['hr_fim']); ?></td>
        <td align="center"><a href="javascript:void(0)" onClick="<?php echo ($row_lista['remarcado'] <> '')? "Alert('".$row_lista['informacao']."')" : "" ?>"><?php echo ($row_lista['remarcado'] <> '')? "Sim" : "Não" ?></a></td>
        <td align="center"><?php if(($alteracao == "S") && ($row_lista['grupo'] == 'N')){ ?>
          <a onClick="NewWindow(this.href, 'INTRANET', '700', '500'); return false" href="cad_aula_pacote2.php?cod_aluno_dia_aula=<?php echo $row_lista['cod_aluno_dia_aula']; ?>&cod_pacote=<?php echo $var; ?>" > <img src="icones/edititem.gif" width="16" height="16" border="0"></a>
        <?php } ?></td>
        <td><?php if($exclusao == "S"){ ?>
          <a href="exclui_aluno_dia_aula.php?cod_aluno_dia_aula=<?php echo $row_lista['cod_aluno_dia_aula']; ?>&cod_pacote=<?php echo $var; ?>" class="delete" rel="Tem certeza que deseja excluir esse registro?"> <img src="icones/ico_remover.gif" width="16" height="16" border="0"> </a>
        <?php } ?></td>
      </tr>
      <?php
	  $desc_geral = $row_lista['desconto'];
	  $ac_geral = $row_lista['acrescimo'];
	  }while($row_lista = mysql_fetch_assoc($lista));
	  ?>
  </tbody>
  <tfoot>
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

mysql_free_result($modalidade);
?>

