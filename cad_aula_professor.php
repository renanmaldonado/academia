<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php 
$fvar2 = 4;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var = $_REQUEST['nome'];
$var1 = $_REQUEST['cod_aula'];

if(isset($var) && ($var <> ''))
{
	$filtro = "AND p.nome_professor LIKE '%$var%'";	
}

//Filtrar por nome

		
$sql = "SELECT p.*
		FROM professor p
		WHERE p.cod_professor NOT IN(SELECT pr.cod_professor FROM professor_aula pr WHERE pr.cod_professor = p.cod_professor AND pr.cod_aula = '$var1')
		$filtro
		ORDER BY p.nome_professor ASC";
$_SESSION['sql']= $sql;

mysql_select_db($database_conecta, $conecta);
$query_lista = "$sql";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT * FROM aula WHERE cod_aula = '$var1'";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

if(isset($_POST['select']))
{
	$i = 0;
	foreach($_POST['select'] as $prof){
		$val_prof = valorbanco($_POST['valor_professor'][$i]);
		$val_cobr = valorbanco($_POST['valor_cobrado'][$i]);
		
		$sql1 = "SELECT a.max_aluno FROM aula a WHERE a.cod_aula = '$var1'";
		$query1 = mysql_query($sql1);
		$row1 = mysql_fetch_assoc($query1);
		
		if($row1['max_aluno'] > 0)
		{
			$mens = "S";	
		}
		else
		{
			$mens = "N";		
		}
		
		mysql_query("INSERT INTO professor_aula (mensal, cod_professor, cod_aula, valor_professor, valor_cobrado, dt_cadastro) VALUES ('$mens', '$prof', '$var1', '$val_prof', '$val_cobr', DATE(NOW()))") or die("<script>alert('Não foi possível salvar as informações! <br>".mysql_error()."')</script>".exit);
		$ultimo = mysql_insert_id();
		
		/*
		if($mens == 'S')
		{
			$hj = date("Y-m-".Dia($row_ver['dia_pagto_prof']), strtotime('+1 month', strtotime(date('Y-m-d')))) ;
			$vencimento = $hj;
			
			$valor = valorbanco($_POST['valor_professor'][$i]);
			mysql_query("INSERT INTO pagto_professor (cod_professor_aula, valor, cod_professor, dt_gerado, dt_vencimento) 
						 VALUES ('$ultimo', '$valor', '$prof', DATE(NOW()), '$vencimento')") or die(mysql_error());
		}
		*/
		$i++;
	}
	header("Location: cad_aula_professor.php?cod_aula=$var1");
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
<style>
.oculta{
	display: none;	
}
</style>
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
          <td width="1134"><!--DWLayoutEmptyCell-->&nbsp;</td>
          </tr>
        </form>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td rowspan="2"><div align="center" class="titulo">ADICIONAR PROFESSOR NA AULA - <?php echo $row_ver['desc_aula']; ?></div></td>
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
<form name="form1" id="form1" method="post">
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="display cell-border" id="tabela">
  <thead>
  <tr>
    <th width="8%" align="left">Selecionar</th>
    <th width="10%" align="left">Valor do professor</th>
    <th width="10%" align="left">Valor cobrado</th>
    <th width="48%" align="left">Nome</th>
    <th width="3%" align="center">Ver</th>
    </tr>
  </thead>
  <tbody>
  <?php
  $i = 0;
  do{
  ?>
  <tr class="gradeA">
    <td><input type="checkbox" name="select[]" id="select<?php echo $i; ?>" value="<?php echo $row_lista['cod_professor']; ?>">
      <input name="cad_aula" type="hidden" id="cad_aula" value="<?php echo $var1; ?>">
    </td>
    <td>
      <input name="valor_professor[<?php echo $i; ?>]" type="text" class="moeda professor select<?php echo $i; ?>" size="20" autocomplete="off"></td>
    <td><input name="valor_cobrado[<?php echo $i; ?>]" type="text" class="moeda cobrado select<?php echo $i; ?>" size="20" autocomplete="off"></td>
    <td><?php echo $row_lista['nome_professor']; ?></td>
    <td align="center"><?php if($visualizacao == "S"){ ?>
      <a onClick="NewWindow(this.href,'name','600','450','no');return false;" href="visualiza_professor.php?cod_professor=<?php echo $row_lista['cod_professor']; ?>"><img src="icones/viewmag.gif" width="16" height="16" border="0"></a>
      <?php } ?></td>
    </tr>
  <?php 
  $i++;
  }while($row_lista = mysql_fetch_assoc($lista));
  ?>
  </tbody>
</table>
</form>
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
    <input type="button" class="b-voltar voltar" value="Voltar"> 
    <input name="salvar" type="button" class="b-salvar" value="Salvar">
</div>
</div>
<br>
<br>
<br>
<br>
<!-- Javascript -->
<script type="text/javascript" src="js/jquery.js"></script>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="js/jquery.maskMoney.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.numeric.js"></script>
<script type="text/javascript" language="javascript" src="plugin/datatables/media/js/jquery.dataTables.js"></script>
<script>
jQuery(function() {
    
	$('.moeda').focus(function(){
		$(this).val('');
	});
	
	$('.moeda').focusout(function(e) {
		if($(this).val() == '')
		{
			$(this).val('0,00');
		}
	});
	
	$("table#tabela").DataTable({
		language: {
			url: "<?php echo $tb_language; ?>"
		},
		"order": [[ 0, "asc" ]]
	});
	
	$(".b-salvar").hide();
	$(".moeda").hide();
	$(".hide").hide();
	$(".cobrado").val('<?php echo moeda_br($row_ver['valor_cobrado']); ?>');
	$(".professor").val('<?php echo moeda_br($row_ver['valor_professor']); ?>');
				
	$("input[name='select[]']").change(function(event){
		
		var check = $("input[name='select[]']:checked").length > 0;
		var check_id = $(this).attr('id');
		var mostra = $("#" + check_id + ":checked").length > 0;
		
				
		if(mostra == false)
		{
			$("." + check_id).hide();
			$("." + check_id).val('');
			$("." + check_id).attr('required','required');
			$(".hide").removeAttr('required');
		}
		else
		{
			$("." + check_id).show();
			$("." + check_id).attr('required','required');
			$(".hide").removeAttr('required');
		}
	
		if(check == true){			
			$(".b-salvar").show();
			
			$(".b-salvar").click(function(){
				//document.getElementById("form1").submit();	
				$("#form1").submit();
				$(".error").html('Campo obrigatório!');
			});
		}
		else
		{
			$(".b-salvar").hide();
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

