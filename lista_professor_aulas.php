<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>

<?php 
$fvar2 = 4;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var = $_REQUEST['cod_professor'];


//Filtrar por nome
		
$sql = "SELECT p.*, a.desc_aula, a.valor_mensal
		FROM professor_aula p, aula a
		WHERE p.cod_aula = a.cod_aula
		AND p.cod_professor = '$var'
		AND p.cod_aula_dia IS NULL
		ORDER BY a.desc_aula ASC";
$_SESSION['sql']= $sql;

mysql_select_db($database_conecta, $conecta);
$query_lista = "$sql";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT * FROM professor WHERE cod_professor = '$var'";
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
            <td height="35" valign="middle" nowrap="nowrap">
              <div align="right">
                <?php if($cadastro == "S"){ ?>
                <input type="button" name="Submit2" value="Novo" class="b-novo" onClick="Url('cad_professor_aula.php?cod_professor=<?php echo $var; ?>');">
                <?php } ?>
              </div>            </td>
          </tr>
        </form>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td rowspan="2"><div align="center" class="titulo">MODALIDADES DO PROFESSOR - <?php echo $row_ver['nome_professor']; ?></div></td>
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
    <th width="45%" align="left">Modalidade</th>
    <th width="20%" align="center">Cobrado</th>
    <th width="20%" align="center">Custo</th>
    <th width="20%" align="center">Valor da aula</th>
    <th width="5%" align="center">Ver</th>
    <th width="5%" align="center">Excluir</th>
  </tr>
  </thead>
  <tbody>
  <?php
  do{
  ?>
  <tr class="gradeA">
    <td><?php echo $row_lista['desc_aula']; ?></td>
    <td align="center"><?php if($row_lista['valor_mensal'] == "S"){ echo "Mensal"; $c = "/"; }else{ echo "Por aula"; $c = "*"; } ?></td>
    <td align="center"><?php echo moeda_br($row_lista['valor_professor']);  $val0[] = $row_lista['valor_professor']; ?></td>
    <td align="center"><?php echo moeda_br($row_lista['valor_cobrado']); $val1[] = $row_lista['valor_cobrado']; ?></td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a onClick="NewWindow(this.href,'name','600','450','no');return false;" href="visualiza_professor.php?cod_professor=<?php echo $row_lista['cod_professor']; ?>"><img src="icones/viewmag.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    <td align="center">
	<?php if($exclusao == "S"){ ?>
    <a href="exclui_professor_aula.php?cod=<?php echo $row_lista['cod_professor_aula']; ?>&cod_professor=<?php echo $row_lista['cod_professor']; ?>" class="delete" rel="Tem certeza que deseja excluir essa modalidade do professor?">
      <img src="icones/ico_remover.gif" width="16" height="16" border="0">
    </a>
	<?php } ?>
    </td>
  </tr>
  <?php 
  }while($row_lista = mysql_fetch_assoc($lista));
  ?>
  <tfoot>
  	<tr>
        <td></td>
        <td bgcolor=""  align="center">&nbsp;</td>
        <td bgcolor="#FFD5D6"  align="center"><strong>Total: R$ <?php echo moeda_br(array_sum($val0)); ?></strong></td>
        <td bgcolor="#D9D9FF" align="center"><strong>Total: R$ <?php echo moeda_br(array_sum($val1)); ?></strong></td>
        <td bgcolor="#CEFFCE" colspan="2" align="center"><strong>Lucro: R$ <?php echo moeda_br(array_sum($val1) - array_sum($val0)); ?></strong></td>
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
    <input name="Button" type="button" class="b-export" value="Exportar dados" onclick="Url('relatorios/index.php')" />
    <input name="print" type="button" class="b-print" value="Imprimir" onclick="NewWindow('relatorios/print.php','name','600','450','no')" />
    <?php } ?>
    <input name="botao" type="button" class="b-voltar voltar" id="botao" value="Voltar">
</div>
</div>
<br>
<br>
<br>
<br>
<!-- Javascript -->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="plugin/datatables/media/js/jquery.dataTables.js"></script>
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

