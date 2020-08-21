<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php 
$fvar2 = 5;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var = $_REQUEST['nome'];
$var1 = $_REQUEST['cod_aula'];

if(isset($var) && ($var <> ''))
{
	$filtro = "AND p.nome_professor LIKE '%$var%')";	
}

if(isset($_GET['add']))
{
	/*$sql = "SELECT DISTINCT(p.cod_professor), p.nome_professor, pa.cod_aula_dia, (SELECT a.valor_professor FROM professor_aula a WHERE a.cod_aula = '$var1' AND a.cod_professor = p.cod_professor LIMIT 1)AS valor_professor, (SELECT a.valor_cobrado FROM professor_aula a WHERE a.cod_aula = '$var1' AND a.cod_professor = p.cod_professor LIMIT 1)AS valor_cobrado
			FROM professor p
			RIGHT JOIN professor_aula pa ON(pa.cod_professor = p.cod_professor AND pa.cod_aula = '$var1' AND pa.cod_aula_dia = '".$_GET['add']."')
			WHERE p.status = 'S'";*/
			
	$sql = "SELECT DISTINCT(pa.cod_professor), p.dia_pagto, p.nome_professor, pa.valor_cobrado, pa.valor_professor, pa.mensal
			FROM professor p, professor_aula pa
			WHERE pa.cod_aula = '$var1'
			AND p.cod_professor = pa.cod_professor";	
}
else
{
	$sql = "SELECT DISTINCT(p.cod_professor), p.dia_pagto, p.nome_professor, pa.cod_aula_dia, pa.mensal
			FROM professor p, professor_aula pa
			WHERE p.status = 'S'
			AND pa.cod_professor = p.cod_professor 
			AND pa.cod_aula = '$var1'
			AND pa.cod_aula_dia IS NULL";
}
//Filtrar por nome


$_SESSION['sql']= $sql;

mysql_select_db($database_conecta, $conecta);
$query_lista = "$sql";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

$_SESSION['sql']= $sql;

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT * FROM aula WHERE cod_aula = '$var1'";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

if((isset($_POST['insert'])) && ($_POST['insert'] == 'S')){
	
		
	$sql1 = "SELECT a.max_aluno FROM aula a WHERE a.cod_aula = '$var1'";
	$query1 = mysql_query($sql1) or die(mysql_error());
	$row1 = mysql_fetch_assoc($query1);
	
	if($row1['max_aluno'] > 0)
	{
		$mens = "S";	
	}
	else
	{
		$mens = "N";		
	}
	
	$count = count($_POST['select']);	
	mysql_query("DELETE FROM professor_aula WHERE cod_aula = '$var1' AND cod_aula_dia = '".$_GET['add']."'");
	
	if($count > 0)
	{
		foreach($_POST['select'] as $pr){
			
			$insert = mysql_query("INSERT INTO professor_aula (dt_cadastro, mensal, cod_professor, cod_aula, valor_professor, valor_cobrado, cod_aula_dia)VALUES(DATE(NOW()), '$mens','$pr','$var1','".valorbanco($_POST['valor_professor'][$pr])."','".valorbanco($_POST['valor_cobrado'][$pr])."', '".$_GET['add']."')");		
			$ultimo = mysql_insert_id();
			if($_POST['dia_pagto'][$pr] <= date('d'))
			{
				$venc = date("Y-m-".$_POST['dia_pagto'][$pr], strtotime('+1 month', strtotime(date('Y-m-d'))));
			}
			else
			{
				$venc = date("Y-m-".$_POST['dia_pagto'][$pr]) ;
			}
			//mysql_query("INSERT INTO pagto_professor (cod_professor, cod_professor_aula, valor, dt_gerado, dt_vencimento) VALUES ('$pr', '$ultimo', '".valorbanco($_POST['valor_professor'][$pr])."', DATE(NOW()), '$venc')") or die(mysql_error());
		}
	}
	if($insert)
	{
		
		echo "<script>alert('Os dados foram salvos com sucesso!');window.location.href='lista_aula_professor.php?cod_aula=$var1&add=".$_GET['add']."'</script>";
	}
}


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
		  <input type="button" name="Submit2" value="Novo" class="b-novo" onClick="Url('cad_aula_professor.php?cod_aula=<?php echo $var1; ?>');">
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
    <td rowspan="2">
    <?php 
	if($totalRows_lista <= 1)
	{
	?>
    	<div align="center" class="titulo">PROFESSOR DA AULA - <?php echo $row_ver['desc_aula'] ?></div>
    <?php 
	}
	else
	{
	?>
    	<div align="center" class="titulo">PROFESSORES DA AULA - <?php echo $row_ver['desc_aula'] ?></div>
    <?php 
	}
	?>
    
    </td>
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
<form action="" method="post" name="insert" id="insert">
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="display cell-border" id="tabela">
  <thead>
  <tr>
    <?php
	if(isset($_GET['add']))
	{
	?>
    <th width="5%" align="left">Selecionar</th>
    <th width="13%" align="left">Valor do professor</th>
    <th width="15%" align="left">Valor cobrado</th>
    <?php
	}
	?>
    <th width="62%" align="left">Professor</th>
    <th width="5%" align="center">Excluir</th>
  </tr>
  </thead>
  <tbody>
  <?php
  do{
  ?>
  <tr class="gradeA">
    <?php
	if(isset($_GET['add']))
	{
		$sql = "SELECT pa.*
				FROM professor_aula pa 
				WHERE pa.cod_aula = '$var1' 
				AND pa.cod_professor = '".$row_lista['cod_professor']."'
				AND pa.cod_aula_dia = '".$_GET['add']."'";
		$query = mysql_query($sql);
		$row = mysql_fetch_assoc($query);
		$total = mysql_num_rows($query);
	?>
    <td><input type="checkbox" name="select[]" id="select" value="<?php echo $row_lista['cod_professor']; ?>" <?php echo ($total > 0)? 'checked' : ''; ?> ></td>
    <td><input name="valor_professor[<?php echo $row_lista['cod_professor']; ?>]" type="hidden" class="moeda" id="valor_professor" size="8" value="<?php echo moeda_br($row_lista['valor_professor']) ?>">
    R$ <?php echo moeda_br($row_lista['valor_professor']) ?></td>
    <td><input name="valor_cobrado[<?php echo $row_lista['cod_professor']; ?>]" type="hidden" class="moeda" id="valor_cobrado" size="8" value="<?php echo moeda_br($row_lista['valor_cobrado']) ?>">
    R$ <?php echo moeda_br($row_lista['valor_cobrado']) ?>
    <input name="mensal[<?php echo $row_lista['cod_professor']; ?>]" type="hidden" value="<?php echo $row_lista['mensal']; ?>">
    <input type="hidden" name="dia_pagto[<?php echo $row_lista['cod_professor']; ?>]" id="dia_pagto" value="<?php echo Verifica_dia($row_lista['dia_pagto']); ?>"></td>
    <?php 
	}
	?>
    <td><?php echo $row_lista['nome_professor']; ?></td>
    <td align="center">
      <?php if($exclusao == "S"){ ?>
      <a href="exclui_aula_professor.php?cod_professor=<?php echo $row_lista['cod_professor']; ?>&cod_aula=<?php echo $var1; ?>" class="delete" rel="Tem certeza que deseja excluir o professor <?php echo $row_lista['nome_professor']; ?> dessa aula?">
        <img src="icones/ico_remover.gif" width="16" height="16" border="0" class="">
        </a>
      <?php } ?>
    </td>
  </tr>
  <?php 
  }while($row_lista = mysql_fetch_assoc($lista));
  ?>
  </tbody>
</table>
<input name="cod_aula" type="hidden" value="<?php echo $var1; ?>">
<input name="insert" type="hidden" id="insert" value="S">
</form>

<?php
}
?>

<div class="container_botao">
<div style="padding: 12px;" align="center">
    <?php if($exportacao == "S"){ ?>
    <input name="Button" type="button" class="b-export" value="Exportar dados" onclick="Exportar()" />
    <script>function Exportar(){location.href="relatorios/index.php";}</script>
	
    <?php
	if(isset($_GET['add']))
	{
	?>
      <input name="voltar" type="button" class="b-voltar" value="Voltar"> 
      <input name="salvar" type="button" id="salvar" class="b-salvar" value="Salvar"> 
    <?php 
	}
	?>
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
<script src="js/jquery.js"></script>
<script src="js/jquery.numeric.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.maskedinput.js"></script>
<script src="js/jquery.maskMoney.js"></script>
<script type="text/javascript" language="javascript" src="plugin/datatables/media/js/jquery.dataTables.js"></script>

<script>
$(function(e) {
    $("table#tabela").DataTable({
		language: {
			url: "<?php echo $tb_language; ?>"
		},
		"order": [[ 0, "asc" ]]
	});	
	$("#salvar").click(function(){
		$("#insert").submit();	
	});
});
</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>
<?php
mysql_free_result($lista);
?>

