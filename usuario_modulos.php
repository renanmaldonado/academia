<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php 
$fvar2 = 1;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var1 = $_GET['cod_usuario'];

	$sql = "SELECT mu.cod_modulo, m.nome_modulo, mu.cod_usuario, mu.cadastro, mu.alteracao, mu.exclusao, mu.visualizacao, mu.exportacao, mu.dt_cadastro
			FROM modulos_usuario mu, modulos m
			WHERE mu.cod_usuario = '$var1' AND mu.cod_modulo = m.cod_modulo
			ORDER BY m.nome_modulo ASC";
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
<link href="css/intranet.css" rel="stylesheet" type="text/css">

</head>
<body topmargin="0" leftmargin="0" rightmargin="0" class="ex_highlight_row">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="fundoform">
  <!--DWLayoutTable-->
  <tr> 
    <td width="21" height="19">&nbsp;</td>
    <td width="1637">&nbsp;</td>
    <td width="23">&nbsp;</td>
  </tr>
  <tr> 
    <td height="19">&nbsp;</td>
    <td valign="top"> <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalhe">
        <!--DWLayoutTable-->
        <form name="filtro" method="post" action="">
          <tr valign="middle">
            <td width="1015" height="35" valign="middle"><!--DWLayoutEmptyCell-->&nbsp;</td>
          <td width="633">
		  <div align="right">
		  <?php if($cadastro == "S"){ ?>
		  <input type="button" name="Submit2" value="Novo" class="b-novo" onClick="HomeButton();">
		  <script>function HomeButton(){location.href="cad_usuario_acesso.php?cod_usuario=<?php echo $var1; ?>";}</script>
		  <?php } ?>
		  </div>
		  </td>
          </tr>
        </form>
      </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td rowspan="2"><div align="center" class="titulo">GERENCIAMENTO DE ACESSOS</div></td>
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
	<thead>
  	<tr valign="middle"> 
    	<th></th>
  	</tr>
	</thead>
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
  <tr valign="middle"> 
    <th width="2%">Cod</th>
    <th width="70%">M&oacute;dulo</th>
    <th width="5%">Cadastro</th>
    <th width="5%">Visualiza&ccedil;&atilde;o</th>
    <th width="5%">Altera&ccedil;&atilde;o</th>
    <th width="5%">Exclus&atilde;o</th>
    <th width="5%">Exporta&ccedil;&atilde;o</th>
    <th width="4%">Alterar</th>
    <th width="4%">Excluir</th>
  </tr>
</thead>
<tbody>
<?php do {?>
  <tr class="gradeA">
    <td align="center"><?php echo $row_lista['cod_modulo']; ?></td>
    <td><?php echo upper($row_lista['nome_modulo']); ?></td>
    <td align="center"><?php echo upper($row_lista['cadastro']); ?></td>
    <td align="center"><?php echo upper($row_lista['visualizacao']); ?></td>
    <td align="center"><?php echo upper($row_lista['alteracao']); ?></td>
    <td align="center"><?php echo upper($row_lista['exclusao']); ?></td>
    <td align="center"><?php echo upper($row_lista['exportacao']); ?></td>
    <td align="center"><?php if($alteracao == "S"){ ?><a href="atualiza_usuario_acesso.php?cod_usuario=<?php echo $row_lista['cod_usuario']; ?>&cod_modulo=<?php echo $row_lista['cod_modulo']; ?>" ><img src="icones/edititem.gif" width="16" height="16" border="0"></a><?php } ?></td>
    <td align="center"><?php if($exclusao == "S"){ ?><a href="exclui_usuario_acesso.php?cod_usuario=<?php echo $row_lista['cod_usuario']; ?>&cod_modulo=<?php echo $row_lista['cod_modulo']; ?>" rel="Deseja bloquear esse módulo para esse usuário?" class="delete"><img src="icones/ico_remover.gif" width="16" height="16" border="0"></a><?php } ?></td>
  </tr>
  <?php } while ($row_lista = mysql_fetch_assoc($lista)); ?>
  </tbody>
</table>
  <?php } ?>
<div class="container_botao">
<div style="padding: 12px;" align="center">
    <?php if($exportacao == "S"){ ?>
    <input name="Button" type="button" class="b-export" value="Exportar dados" onclick="Exportar()" />
    <script>function Exportar(){location.href="relatorios/index.php";}</script>
    <input name="print" type="button" class="b-print" value="Imprimir" onclick="Imprimir()" />
    <script>function Imprimir(){window.open('relatorios/print.php','IMPRIMIR','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,fullscreen=yes');}</script>
    <?php } ?>
    <input name="button" type="submit" class="b-voltar voltar" id="button" value="Voltar">
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

