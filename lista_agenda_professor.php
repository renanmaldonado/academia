<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>

<?php 
$fvar2 = 4;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var = $_REQUEST['nome'];
$var2 = $_REQUEST['status'];
$var3 = $_REQUEST['cod_professor'];


if(isset($_REQUEST['filtro']) && ($_REQUEST['filtro'] == 'S'))
{
	$status	= "AND a.desc_aula LIKE '%$var%'";
	if($_REQUEST['dia'] <> '')
	{
		$dia = "AND ad.dia = '".vaiparaobanco($_REQUEST['dia'])."'";
	}
	
	$nome = "AND a.desc_aula LIKE '%$var%'";
}

//Filtrar por nome

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT * FROM professor WHERE cod_professor = '$var3'";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);
		
$sql = "SELECT DISTINCT(ad.cod_aluno_dia_aula), ad.dia, a.desc_aula, ad.hr_inicio, ad.hr_fim, al.nome_aluno, ap.cod_pacote, a.cod_aula
		FROM aluno_pacote_aula aa, aluno_dia_aula ad, aula a, aluno_pacote ap, aluno al, aluno_pacote_professor app
		WHERE aa.cod_aluno_pacote_aula = ad.cod_aluno_pacote_aula
		AND aa.cod_aula = a.cod_aula 
		AND aa.cod_pacote = ap.cod_pacote
		AND ad.cod_aluno_dia_aula = app.cod_aluno_dia_aula
		AND app.cod_professor = '$var3'
		AND	ap.cod_aluno = al.cod_aluno
		AND ad.cod_aluno_dia_aula NOT IN(SELECT ad1.cod_aluno_dia_aula FROM aluno_dia_aula ad1 WHERE ad.cod_aluno_dia_aula = ad1.remarcado)
		$dia $nome";
$_SESSION['sql']= $sql;

mysql_select_db($database_conecta, $conecta);
$query_lista = $sql;
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
            <td width="149" height="35" valign="middle" nowrap="nowrap"><p>Modalidade:</p></td>
            <td width="194" valign="middle"><input name="nome" type="text" class="campo" id="nome" onFocus="this.className='campo_over'" onBlur="this.className='campo'" value="<?php echo $var; ?>" size="30"></td>
            <td width="1134">
            </td>
          </tr>
          <tr valign="middle">
            <td height="35" valign="middle" nowrap="nowrap">Data:</td>
            <td valign="middle">
            <input name="cod_professor" id="cod_professor" type="hidden" value="<?php echo $var3; ?>" >
            <input name="dia" type="text" id="dia" class="data dt2b" value="<?php echo $_REQUEST['dia']; ?>" size="10">
            <input name="filtro" type="hidden" id="filtro" value="S">
            
            </td>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
          <tr valign="middle">
            <td height="35" valign="middle" nowrap="nowrap"><!--DWLayoutEmptyCell-->&nbsp;</td>
            <td valign="middle"><input type="submit" name="Submit3" value="Filtrar" class="b-filtrar"></td>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
        </form>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td rowspan="2"><div align="center" class="titulo">AGENDA DO PROFESSOR - <?php echo $row_ver['nome_professor']; ?></div></td>
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
    <th width="20%" align="left">Modalidade</th>
    <th width="15%">Dia</th>
    <th width="11%">Hora in&iacute;cial</th>
    <th width="13%">Hora final</th>
    <th width="38%">Aluno</th>
    <th width="3%" align="center">Ver</th>
    </tr>
  </thead>
  <tbody>
  <?php
  do{
  ?>
  <tr class="gradeA">
    <td><?php echo $row_lista['desc_aula']; ?></td>
    <td align="center"><?php echo voltadobanco($row_lista['dia']); ?></td>
    <td align="center"><?php echo horabr2($row_lista['hr_inicio']); ?></td>
    <td align="center"><?php echo horabr2($row_lista['hr_fim']); ?></td>
    <td align="center"><?php echo $row_lista['nome_aluno']; ?></td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a onClick="NewWindow(this.href,'name','600','450','no');return false;" href="lista_aulas_pacote.php?cod_pacote=<?php echo $row_lista['cod_pacote']; ?>&filtro=<?php echo $row_lista['cod_aula']; ?>"><img src="icones/viewmag.gif" width="16" height="16" border="0"></a>
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

