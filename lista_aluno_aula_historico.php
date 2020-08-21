<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php 
$fvar2 = 2;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var = $_REQUEST['cod_aluno'];
//Filtrar por nome
	
	
if(isset($_POST['filtro']) && ($_POST['filtro'] == 'S')){
	if(isset($_POST['dt_inicio']))
	{
	$dt1 = "AND atu.dt_aula >= '".vaiparaobanco($_POST['dt_inicio'])."'";
	}
	elseif(isset($_POST['dt_fim']))
	{
	$dt2 = "AND atu.dt_aula <= '".vaiparaobanco($_POST['dt_fim'])."'";
	}
}
		
$sql = "(SELECT atu.hr_inicio, atu.hr_fim, a.desc_aula, p.nome_professor, atu.dt_aula AS dt_marcada, '' AS hr_inicio1, '' AS hr_fim1, atu.dt_aula
		FROM (aluno_pacote atu, aula a)
		LEFT JOIN professor p ON(p.cod_professor = atu.cod_professor)
		WHERE atu.cod_aula = a.cod_aula
		AND atu.cod_aluno = '$var'
		$filtro
		$dt1
		$dt2)";	
			

mysql_select_db($database_conecta, $conecta);
$query_lista = $sql;
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

$_SESSION['sql'] = $query_lista;	

mysql_select_db($database_conecta, $conecta);
$query_aula = "SELECT * FROM aula ORDER BY desc_aula ASC";
$aula = mysql_query($query_aula, $conecta) or die(mysql_error());
$row_aula = mysql_fetch_assoc($aula);
$totalRows_aula = mysql_num_rows($aula);

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT cod_aluno, nome_aluno FROM aluno WHERE cod_aluno = '$var'";
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
    <td height="19">&nbsp;</td>
    <td><div align="center" class="titulo">GERENCIAMENTO DE HIST&Oacute;RICO DE AULAS DO ALUNO <a href="#" onClick="NewWindow('visualiza_aluno.php?cod_aluno=<?php echo $row_ver['cod_aluno'];  ?>', 'INTRANET', 500, 300)"><?php echo $row_ver['nome_aluno']; ?></a></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="19">&nbsp;</td>
    <td valign="top">
    <form name="filtro" method="post" action=""><table width="100%" class="detalhe">
      <tr>
        <td width="138" height="35" valign="middle" nowrap="nowrap">Periodo:</td>
        <td width="74" valign="middle"><input name="dt_inicio" type="text" class="data dt1" id="dt_inicio" size="10" value="<?php echo $_POST['dt_inicio']; ?>" ></td>
        <td width="214" valign="middle"><input name="dt_fim" type="text" class="data dt2" id="dt_fim" size="10" value="<?php echo $_POST['dt_fim']; ?>" ></td>
        <td width="67%" align="right">&nbsp;</td>
      </tr>
      <tr>
        <td height="35" valign="middle" nowrap="nowrap"><input name="filtro" type="hidden" id="filtro" value="S"></td>
        <td colspan="2" valign="middle"><input type="submit" name="Submit3" value="Filtrar" class="b-filtrar"></td>
        <td align="right">&nbsp;</td>
      </tr>
    </table>
</form>
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
    <td valign="top">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalhe">
        <!--DWLayoutTable-->
        <tr valign="middle"> 
          <td height="19">Total de registros: <span class="font10negrito"> 
            <?php echo $totalRows_lista; ?></span></td>
          <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
        </tr>
      </table></td>
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
    <th width="15%" align="left">Dia</th>
    <th width="21%" align="left">MODALIDADE</th>
    <th width="18%" align="center">Professor</th>
    <th width="10%" align="center">Hora in&iacute;cial</th>
    <th width="10%" align="center">Hora final</th>
    </tr>
  </thead>
  <tbody>
  <?php
  do{
  ?>
  <tr class="gradeA">
    <td><?php echo voltadobanco($row_lista['dt_aula']); ?></td>
    <td><?php echo $row_lista['desc_aula']; ?></td>
    <td align="center"><?php echo $row_lista['nome_professor']; ?></td>
    <td align="center"><?php echo horabr2($row_lista['hr_inicio']); ?></td>
    <td align="center"><?php echo horabr2($row_lista['hr_fim']); ?></td>
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

