<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php 
$fvar2 = 6;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var = $_REQUEST['nome'];

if(isset($var) && ($var <> ''))
{
	$filtro = "AND a.nome_aluno LIKE '%$var%'";	
}

//Filtrar por nome
		
$sql = "SELECT a.cod_aluno, a.nome_aluno, ai.*
		FROM aluno a, aluno_pacote ai
		WHERE a.cod_aluno = ai.cod_aluno
		AND ai.status = 'S'
		$filtro
		ORDER BY a.nome_aluno ASC";
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
            <td width="131" valign="middle" nowrap="nowrap">Pesquisar por nome:</td>
            <td width="194" valign="middle"><input name="nome" type="text" class="campo" id="nome" value="<?php echo $_POST['nome']; ?>" size="30">              <label for="status"></label></td>
            <td width="152" valign="middle"><input type="submit" name="Submit3" value="Filtrar" class="b-filtrar"></td>
            <td width="761"><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
        </form>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td rowspan="2"><div align="center" class="titulo">GERENCIAMENTO DE ALUNOS </div></td>
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
    <th width="31%" align="left">Nome</th>
    <th width="14%">Valor</th>
    <th width="14%">Descontos</th>
    <th width="14%">Acr&eacute;ssimo</th>
    <th width="14%">Valor final</th>
    <th width="3%">Aulas</th>
    <th width="2%" align="center">Ver</th>
    </tr>
  </thead>
  <tbody>
  <?php
  do{
	  
	  if($row_lista['status'] == "N")
	  {
		  $class = "gradeY";  
	  }
	  if($row_lista['qtde_pacote'] == 0){
		  $class = "gradeY"; 
	  }
	  if($row_lista['vencido'] > 0)
	  {
		  $class = "gradeX"; 
	  }			  
  ?>
  <tr class="<?php echo $class; ?>">
    <td><?php echo $row_lista['nome_aluno']; ?></td>
    <td align="center">R$ <?php echo moeda_br($row_lista['valor_final'] - $row_lista['valor_desconto'] + $row_lista['valor_acrescimo']); $valor[] = $row_lista['valor_final'] - $row_lista['valor_desconto'] + $row_lista['valor_acrescimo']; ?></td>
    <td align="center">R$ <?php echo moeda_br($row_lista['desconto']);  $desc[] = $row_lista['desconto']; ?></td>
    <td align="center">R$ <?php echo moeda_br($row_lista['acrescimo']); $acr[] = $row_lista['acrescimo']; ?></td>
    <td align="center">R$ <?php echo moeda_br($row_lista['valor_final']); $val_final[] = $row_lista['valor_final']; ?></td>
    <td align="center">
      <?php if($visualizacao == "S"){ ?>
      <a href="lista_aluno_aula.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"><img src="icones/folder.gif" width="16" height="16" border="0"></a><a href="lista_turma_aluno.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"></a>
      <?php } ?>
    </td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a onClick="NewWindow(this.href,'name','800','450','no');return false;" href="visualiza_aluno.php?cod_aluno=<?php echo $row_lista['cod_aluno']; ?>"><img src="icones/viewmag.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    </tr>
  <?php 
  }while($row_lista = mysql_fetch_assoc($lista));
  ?>
  <tfoot>
      <tr class="gradeA">
        <td>&nbsp;</td>
        <td align="center" bgcolor="#CEFFCE"><strong>R$ <?php echo moeda_br(array_sum($valor)); ?></strong></td>
        <td align="center" bgcolor="#FFD5D6"><strong>R$ <?php echo moeda_br(array_sum($desc)); ?></strong></td>
        <td align="center" bgcolor="#D9D9FF"><strong>R$ <?php echo moeda_br(array_sum($acr)); ?></strong></td>
        <td align="center" bgcolor="#FFE7AE"><strong>R$ <?php echo moeda_br(array_sum($val_final)); ?></strong></td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
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

