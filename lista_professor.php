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


if(isset($var2) && ($var2 <> ''))
{
	$status	= "AND p.status = '$var2'";
}

//Filtrar por nome
		
$sql = "SELECT p.*, (SELECT count(pa.cod_aula) FROM professor_aula pa WHERE pa.cod_professor = p.cod_professor AND pa.cod_aula_dia IS NULL)AS qtde_turma
		FROM professor p
		WHERE p.nome_professor LIKE '%$var%' $status
		ORDER BY p.nome_professor ASC";
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
            <td width="149" height="35" valign="middle" nowrap="nowrap">Pesquisar por nome:</td>
            <td width="194" valign="middle"><input name="nome" type="text" class="campo" id="nome" size="30" onFocus="this.className='campo_over'" onBlur="this.className='campo'"></td>
            <td width="181" valign="middle"><!--DWLayoutEmptyCell-->&nbsp;</td>
          <td width="1134">
		  <div align="right">
		  <?php if($cadastro == "S"){ ?>
		  <input type="button" name="Submit2" value="Novo" class="b-novo" onClick="Url('cad_professor.php');">
		  <?php } ?>
		  </div>
		  </td>
          </tr>
          <tr valign="middle">
            <td height="35" valign="middle" nowrap="nowrap">Status:</td>
            <td valign="middle"><label for="status"></label>
              <select name="status" id="status">
                <option value="" <?php if (!(strcmp("", $_REQUEST['status']))) {echo "selected=\"selected\"";} ?>>TODOS</option>
                <option value="S" <?php if (!(strcmp("S", $_REQUEST['status']))) {echo "selected=\"selected\"";} ?>>ATIVO</option>
                <option value="N" <?php if (!(strcmp("N", $_REQUEST['status']))) {echo "selected=\"selected\"";} ?>>DESATIVADO</option>
            </select></td>
            <td valign="middle"><input type="submit" name="Submit3" value="Filtrar" class="b-filtrar"></td>
            <td><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
        </form>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td rowspan="2"><div align="center" class="titulo">GERENCIAMENTO DE PROFESSORES </div></td>
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
    <th width="86%" align="left">Nome</th>
    <th width="4%">Ativo</th>
    <th width="4%">Modalidades</th>
    <th width="2%" align="center">Agenda</th>
    <th width="2%" align="center">Ver</th>
    <th width="4%" align="center">Alterar</th>
    <th width="4%" align="center">Excluir</th>
  </tr>
  </thead>
  <tbody>
  <?php
  do{
  ?>
  <tr class="gradeA">
    <td><?php echo $row_lista['nome_professor']; ?></td>
    <td align="center"><?php echo $row_lista['status']; ?></td>
    <td align="center">
	<?php if($visualizacao == "S"){ ?>
      <a href="lista_professor_aulas.php?cod_professor=<?php echo $row_lista['cod_professor']; ?>"><?php echo $row_lista['qtde_turma']; ?></a>
    <?php } ?>
    </td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a onClick="NewWindow(this.href,'name','700','450','yes');return false;" href="lista_agenda_professor.php?cod_professor=<?php echo $row_lista['cod_professor']; ?>"><img src="icones/folder.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a onClick="NewWindow(this.href,'name','600','450','no');return false;" href="visualiza_professor.php?cod_professor=<?php echo $row_lista['cod_professor']; ?>"><img src="icones/viewmag.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    <td align="center"><?php if($alteracao == "S"){ ?>
      <a href="atualiza_professor.php?cod_professor=<?php echo $row_lista['cod_professor']; ?>"><img src="icones/edititem.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    <td align="center">
	<?php if($exclusao == "S"){ ?>
    <a href="exclui_professor.php?cod_professor=<?php echo $row_lista['cod_professor']; ?>" class="delete" rel="Tem certeza que deseja excluir o professor <?php echo $row_lista['nome_professor']; ?>?">
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

