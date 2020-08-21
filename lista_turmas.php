<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>

<?php 
$fvar2 = 3;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var = $_REQUEST['cod_dia'];

if(isset($var) && ($var <> ''))
{
	$filtro = "AND t.cod_dia = '$var'";	
}

//Filtrar por nome
		
$sql = "SELECT t.*, d.desc_dia, a.desc_aula, (SELECT COUNT(at.cod_aluno) FROM aluno_turma at WHERE at.cod_turma = t.cod_turma AND at.status = 'S')AS qtde_inscritos
		FROM turma t, dia_semana d, aula a
		WHERE t.cod_dia = d.cod_dia 
		AND t.cod_aula = a.cod_aula
		$filtro
		ORDER BY d.cod_dia ASC";
$_SESSION['sql']= $sql;

mysql_select_db($database_conecta, $conecta);
$query_lista = "$sql";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

mysql_select_db($database_conecta, $conecta);
$query_dias = "SELECT * FROM dia_semana ORDER BY cod_dia ASC";
$dias = mysql_query($query_dias, $conecta) or die(mysql_error());
$row_dias = mysql_fetch_assoc($dias);
$totalRows_dias = mysql_num_rows($dias);
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
            <td width="142" height="35" valign="middle" nowrap="nowrap">Pesquisar por nome:</td>
            <td width="124" valign="middle"><select name="cod_dia" id="cod_dia">
              <option value="" <?php if (!(strcmp("", $_REQUEST['cod_dia']))) {echo "selected=\"selected\"";} ?>>Todos os dias</option>
              <?php
do {  
?>
              <option value="<?php echo $row_dias['cod_dia']?>"<?php if (!(strcmp($row_dias['cod_dia'], $_REQUEST['cod_dia']))) {echo "selected=\"selected\"";} ?>><?php echo $row_dias['desc_dia']?></option>
              <?php
} while ($row_dias = mysql_fetch_assoc($dias));
  $rows = mysql_num_rows($dias);
  if($rows > 0) {
      mysql_data_seek($dias, 0);
	  $row_dias = mysql_fetch_assoc($dias);
  }
?>
            </select></td>
            <td width="213" valign="middle"><input type="submit" name="Submit3" value="Buscar nome" class="b-filtrar"></td>
          <td width="761">
		  <div align="right">
		  <?php if($cadastro == "S"){ ?>
		  <input type="button" name="Submit2" value="Novo" class="b-novo" onClick="HomeButton();">
		  <script>function HomeButton(){location.href="cad_aluno.php";}</script>
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
    <td rowspan="2"><div align="center" class="titulo">GERENCIAMENTO DE TURMAS </div></td>
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
    <th width="1%" align="left" class="oculto"></th>
    <th width="16%" align="left">Dia</th>
    <th width="21%" align="left">Aula</th>
    <th width="14%" align="center">Hora in&iacute;cial</th>
    <th width="14%" align="center">Hora final</th>
    <th width="19%" align="center">Inscritos/M&aacute;ximo</th>
    <th width="4%">M&oacute;dulos</th>
    <th width="2%" align="center">Ver</th>
    <th width="4%" align="center">Alterar</th>
    <th width="5%" align="center">Excluir</th>
  </tr>
  </thead>
  <tbody>
  <?php
  do{
  ?>
  <tr class="gradeA">
    <td class="oculto"><?php echo $row_lista['cod_dia']; ?></td>
    <td><?php echo $row_lista['desc_dia']; ?></td>
    <td><?php echo $row_lista['desc_aula']; ?></td>
    <td align="center"><?php echo horabr2($row_lista['hr_inicio']); ?></td>
    <td align="center"><?php echo horabr2($row_lista['hr_fim']); ?></td>
    <td align="center"><a href="visualiza_aluno_turma.php?cod_turma=<?php echo $row_lista['cod_turma']; ?>"><?php echo $row_lista['qtde_inscritos']; ?> de <?php echo $row_lista['qtde_alunos']; ?></a></td>
    <td align="center"><?php if($alteracao == "S"){ ?>
      <a href="usuario_modulos.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"><img src="icones/folder.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a onClick="NewWindow(this.href,'name','600','450','no');return false;" href="visualiza_aluno.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"><img src="icones/viewmag.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    <td align="center"><?php if($alteracao == "S"){ ?>
      <a href="atualiza_aluno.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"><img src="icones/edititem.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    <td align="center">
	<?php if($exclusao == "S"){ ?>
    <a href="exclui_aluno.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>" class="delete" rel="Tem certeza que deseja excluir o aluno <?php echo $row_lista['nome_aluno']; ?>?">
      <img src="icones/ico_remover.gif" width="16" height="16" border="0" class="delete">
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
		"ordering": false
	});	

});
</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($lista);

mysql_free_result($dias);
?>

