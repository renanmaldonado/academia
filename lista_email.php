<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>

<?php 
$fvar2 = 10;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var = $_REQUEST['nome'];

//Filtrar por nome
		
$sql_lista = "SELECT *
			  FROM emails_enviados
			  ORDER BY dt_enviado DESC";
$_SESSION['sql']= $sql_lista;

mysql_select_db($database_conecta, $conecta);
$query_lista = $sql_lista;
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
          <tr valign="middle">
            <td width="1134" height="35" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
          <tr valign="middle">
            <td width="1134" height="35" align="right" valign="bottom"><?php if($cadastro == "S"){ ?>
              <input type="button" name="Submit2" value="Novo" class="b-novo" onClick="NewWindow('cad_email.php');return false;">
            <?php } ?></td>
          </tr>
        
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td rowspan="2"><div align="center" class="titulo">NEWSLETTER</div></td>
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
    <th width="62%" align="left">t&iacute;tulo</th>
    <th width="16%" align="left">Agendamento</th>
    <th width="16%" align="left">Data de envio</th>
    <th width="2%" align="center">Enviar e-mail</th>
    <th width="2%" align="center">Ver</th>
    <th width="4%" align="center">Excluir</th>
  </tr>
  </thead>
  <tbody>
  <?php
  do{
  ?>
  <tr class="<?php echo $class; ?>">
    <td><?php echo $row_lista['titulo']; ?></td>
    <td><?php echo voltadobanco($row_lista['agendamento']); ?></td>
    <td><?php echo voltadobanco($row_lista['dt_enviado']); ?></td>
    <td align="center"><a href="cron.php?cod_msg=<?php echo $row_lista['cod_msg']; ?>" onClick="NewWindow(this.href, 'ENVIAR E-MAIL', '300', '300');return false"><img src="icones/up.gif" width="16" height="16"></a></td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a onClick="NewWindow(this.href,'name','800','450','no');return false;" href="visualiza_msg.php?cod_msg=<?php echo $row_lista['cod_msg']; ?>"><img src="icones/viewmag.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    <td align="center">
      <?php if($exclusao == "S"){ ?>
      <a href="exclui_email.php?cod_msg=<?php echo $row_lista['cod_msg']; ?>" class="delete" rel="Tem certeza que deseja excluir esse registro?">
        <img src="icones/ico_remover.gif" width="16" height="16" border="0">
        </a>
      <?php } ?>
    </td>
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
    <input name="Button" type="button" class="b-export" value="Exportar dados" onClick ="Exportar()" />
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
<script type="text/javascript" language="javascript" src="plugin/datatables/media/js/jquery.js"></script>
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

});
</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($lista);
?>

