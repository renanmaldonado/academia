<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>

<?php 
$fvar2 = 5;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var = $_REQUEST['nome'];

if(isset($var) && ($var <> ''))
{
	$filtro = "WHERE (a.desc_aula LIKE '%$var%')";	
}

//Filtrar por nome
		
$sql = "SELECT a.*, (SELECT COUNT(ap.cod_aluno) FROM aluno_pacote ap, aluno_pacote_aula aa WHERE ap.cod_pacote = aa.cod_pacote AND aa.cod_aula = a.cod_aula AND ap.status = 'S')AS qtde_aluno, (SELECT COUNT(pr.cod_professor) FROM professor_aula pr, professor p WHERE pr.cod_aula = a.cod_aula AND pr.cod_aula_dia IS NULL AND pr.cod_professor = p.cod_professor AND p.status = 'S')AS qtde_professor
		FROM aula a 
		$filtro
		ORDER BY a.desc_aula ASC";
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
            <td width="181" valign="middle"><input type="submit" name="Submit3" value="Buscar nome" class="b-filtrar"></td>
          <td width="1134">
		  <div align="right">
		  <?php if($cadastro == "S"){ ?>
		  <input type="button" name="Submit2" value="Novo" class="b-novo" onClick="Url('cad_aula.php');">
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
    <td rowspan="2"><div align="center" class="titulo">GERENCIAMENTO DE AULAS</div></td>
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
    <th width="54%" align="left">Aula</th>
    <th width="11%" align="center">Alunos</th>
    <th width="10%" align="center">Professor</th>
    <th width="15%" align="center">Editar grupo</th>
    <th width="5%" align="center">Alterar</th>
    <th width="5%" align="center">Excluir</th>
  </tr>
  </thead>
  <tbody>
  <?php
  do{
  ?>
  <tr class="gradeA">
    <td><?php echo $row_lista['desc_aula']; ?></td>
    <td align="center">
      <a onClick="NewWindow(this.href,'name','600','450','no');return false;" href="lista_aula_alunos.php?cod_aula=<?php echo $row_lista['cod_aula']; ?>"><?php echo $row_lista['qtde_aluno']; ?></a>
    </td>
    <td align="center"><a onClick="NewWindow(this.href,'name','800','450','yes');return false;" href="lista_aula_professor.php?cod_aula=<?php echo $row_lista['cod_aula']; ?>"><?php echo $row_lista['qtde_professor']; ?></a></td>
    <td align="center"><?php if(($alteracao == "S") && ($row_lista['grupo'] == 'S') ){ ?>
      <a href="cad_aula_dia.php?cod_aula=<?php echo $row_lista['cod_aula']; ?>"><img src="icones/edititem.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    <td align="center"><?php if($alteracao == "S"){ ?>
      <a href="atualiza_aula.php?cod_aula=<?php echo $row_lista['cod_aula']; ?>"><img src="icones/edititem.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    <td align="center">
	<?php if($exclusao == "S"){ ?>
    <a href="exclui_aula.php?cod_aula=<?php echo $row_lista['cod_aula']; ?>" class="delete" rel="Tem certeza que deseja excluir a modalidade <?php echo $row_lista['desc_aula']; ?>?">
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

