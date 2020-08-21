<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php 
$fvar2 = 2;
require_once('verifica.php'); 
?>
<?php 
unset($_SESSION['sql']); 
$var = $_REQUEST['cod_aluno'];
//Filtrar por nome

$sql = "SELECT *
		FROM aluno_pacote_aula";		
$_SESSION['sql']= $sql;

mysql_select_db($database_conecta, $conecta);
$query_lista = "$sql";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT a.nome_aluno FROM aluno a, aluno_pacote ap WHERE a.cod_aluno = ap.cod_aluno AND a.cod_aluno = '$var'";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

mysql_select_db($database_conecta, $conecta);
$query_aula = "SELECT * FROM aula ORDER BY desc_aula ASC";
$aula = mysql_query($query_aula, $conecta) or die(mysql_error());
$row_aula = mysql_fetch_assoc($aula);
$totalRows_aula = mysql_num_rows($aula);

mysql_select_db($database_conecta, $conecta);
$query_verifica_pacote = "SELECT * FROM aluno_pacote WHERE cod_aluno = '$var' AND status = 'S'";
$verifica_pacote = mysql_query($query_verifica_pacote, $conecta) or die(mysql_error());
$row_verifica_pacote = mysql_fetch_assoc($verifica_pacote);
$totalRows_verifica_pacote = mysql_num_rows($verifica_pacote);

if($totalRows_verifica_pacote == 0)
{
	$insert = mysql_query("INSERT INTO aluno_pacote (cod_aluno, dt_cadastro) VALUES ('$var', DATE(NOW()))", $conecta);
	$pac = mysql_insert_id();
	header("Location: lista_aluno_aula_config.php?cod_aluno=$var&cod_pacote=$pac");
	exit;
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
    <td height="19">&nbsp;</td>
    <td><div align="center" class="titulo">GERENCIAMENTO DE AULAS DO ALUNO <?php echo $row_ver['nome_aluno']; ?></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="19">&nbsp;</td>
    <td valign="top"><table width="100%" class="detalhe">
      <tr>
    <td width="23%" align="left"><form name="form1" method="post" action="">
      <table width="100%" cellpadding="0">
        <tr>
          <td width="43%" align="left">Modalidade: </td>
          <td width="57%" align="left"><select name="cod_aula" id="cod_aula">
            <option value="">Todas</option>
            <?php
		  do{
		  ?>
            <option value="<?php echo $row_aula['cod_aula']; ?>" <?php echo ($row_aula['cod_aula'] == $_POST['cod_aula']) ? "selected" : "" ; ?> ><?php echo $row_aula['desc_aula']; ?></option>
            <?php
		  }while($row_aula = mysql_fetch_assoc($aula));
		  ?>
          </select></td>
        </tr>
        <tr>
          <td align="left">Status:</td>
          <td align="left"><select name="status" id="status">
            <option value="" <?php echo ($_POST['status'] == '')? 'selected' : ''; ?>>Todas</option>
            <option value="1" <?php echo ($_POST['status'] == '1')? 'selected' : ''; ?>>Ativos</option>
            <option value="0" <?php echo ($_POST['status'] == '0')? 'selected' : ''; ?>>Desativados</option>
            </select>
            </td>
          </tr>
        <tr>
          <td align="left"><input name="filtro" type="hidden" id="filtro" value="S">
            <input name="cod_aluno" type="hidden" id="cod_aluno" value="<?php echo $var; ?>"></td>
          <td align="left"><input name="button2" type="submit" class="b-filtrar" id="button2" value="Buscar"></td>
        </tr>
      </table>
    </form></td>
    <td width="58%" align="right">&nbsp;</td>
    <td width="19%" align="right"><input name="button" type="submit" class="b-novo" id="button" value="Adicionar" onClick="NewWindow('cad_aluno_aula_config.php?cod_aluno=<?php echo $var; ?>&cod_pacote_id=<?php echo $row_verifica_pacote['cod_pacote_id']; ?>','name','700','600','yes');return false;"></td>
      </tr>
</table>
</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="19">&nbsp;</td>
    <td valign="top">
    <table width="100%" class="detalhe">
        <!--DWLayoutTable-->
        <tr valign="middle"> 
          <td height="19">Total de registros: <span class="font10negrito"> 
            <?php echo $totalRows_lista; ?></span></td>
          <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
        </tr>
        <tr valign="middle">
          <td height="19">Desconto Geral: R$ <?php echo moeda_br($row_pacote_id['acrescimo']); ?></td>
          <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
        </tr>
        <tr valign="middle">
          <td height="19">Acrescimo Geral: R$ <?php echo moeda_br($row_pacote_id['desconto']); ?></td>
          <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
        </tr>
      </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td>
</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php if($totalRows_lista == 0)
{
?>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="display cell-border">
    <tr class="gradeA">
		<td height="30" align="center">Sem registros...</td>
    </tr>
</table>
<?php
}
else
{
?>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="display cell-border" id="tabela">
  <thead>
  	<tr>
        <th width="30%">Aula</th>
        <th width="10%">Professor</th>
        <th width="14%">Valor da aula</th>
        <th width="14%">Desc.</th>
        <th width="14%">Acr.</th>
        <th width="14%">Valor</th>
        <th width="10%">M&aacute;ximo de aula</th>
        <th width="6%" align="center">Cadastradas</th>
        <th width="10%">Data in&iacute;cial</th>
        <th width="9%">Data final</th>
        <th width="7%">Excluir</th>
    </tr>
  </thead>
  <tbody>
  	  <?php
	  do{
		  
	  ?>
      <tr>
        <td><?php echo $row_lista['desc_aula']; ?></td>
        <td align="center"><?php echo $row_lista['nome_professor']; ?></td>
        <td align="center">R$ <?php echo moeda_br($row_lista['valor_aula']); $val0[] = $row_lista['valor_aula']; ?></td>
        <td align="center">R$ <?php echo moeda_br($row_lista['desconto_aula']); $desc[] = $row_lista['desconto_aula']; ?></td>
        <td align="center">R$ <?php echo moeda_br($row_lista['acrescimo_aula']); $ac[] = $row_lista['acrescimo_aula']; ?></td>
        <td align="center">R$ <?php echo moeda_br($row_lista['valor'] + $row_lista['valor_padrao'] +  $row_lista['acrescimo_aula'] - $row_lista['desconto_aula']); $pac_id = $row_lista['cod_pacote_id']; $val[] = $row_lista['valor'] + $row_lista['valor_padrao'] +  $row_lista['acrescimo_aula'] - $row_lista['desconto_aula']; ?></td>
        <td align="center"><?php echo $row_lista['qtde_aula']; ?></td>
        <td align="center"><?php $cod_pac = $row_lista['cod_pacote_id']; ?><a href="lista_aluno_aula.php?cod_aluno=<?php echo $var; ?>&cod_aula=<?php echo $row_lista['cod_aula']; ?>&cod_aluno_pacote_config=<?php echo $row_lista['cod_aluno_pacote_config']; ?>"><?php echo $row_lista['qtde_cadastrado']; ?></a></td>
        <td align="center"><?php echo voltadobanco($row_lista['dt_inicio']); ?></td>
        <td align="center"><?php echo voltadobanco($row_lista['dt_fim']); ?></td>
        <td><?php if($exclusao == "S"){ ?>
          <a href="exclui_aluno_aula_config.php?cod_aluno_pacote_config=<?php echo $row_lista['cod_aluno_pacote_config']; ?>&cod_aluno=<?php echo $row_lista['cod_aluno']; ?>" class="delete" rel="Tem certeza que deseja excluir esse registro?"> <img src="icones/ico_remover.gif" width="16" height="16" border="0"> </a>
        <?php } ?></td>
      </tr>
      <?php
	  $desc_geral = $row_lista['desconto'];
	  $ac_geral = $row_lista['acrescimo'];
	  }while($row_lista = mysql_fetch_assoc($lista));
	  ?>
  </tbody>
  <tfoot>
  	<tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td bgcolor="#D9D9FF" align="center"><strong>Valor real<br>
R$ <?php echo moeda_br(array_sum($val0)); ?> </strong></td>
        <td bgcolor="#FFD5D6" align="center"><strong>Desc. Final<br>
R$ <?php echo moeda_br(array_sum($desc)); ?></strong></td>
        <td bgcolor="##FFE7AE" align="center"><strong>Ac. Final<br>
R$ <?php echo moeda_br(array_sum($ac)); ?> </strong></td>
        <td bgcolor="#D9D9FF" align="center">
          <strong>Valor<br>
        R$ <?php echo moeda_br(array_sum($val)); ?>

        </strong></td>
        <td bgcolor="#CEFFCE" align="center" valign="middle"><strong>Valor total<br>
        R$ <?php 
		
		  $sql_aluno = "SELECT pa.*, (SELECT ai.dia_pagto 
								      FROM aluno_pacote_id ai 
								      WHERE pa.cod_pacote_id = ai.cod_pacote_id 
  								      LIMIT 1)AS dia_pagto
		  				FROM pagto_aluno pa
						WHERE pa.cod_pacote_id = '".$pac_id."' 
						AND pa.cod_aluno = '$var' 
						AND pa.quitado = 'N'";
	  	  $query_aluno = mysql_query($sql_aluno);
		  $row_aluno = mysql_fetch_assoc($query_aluno);
		  $total_aluno = mysql_num_rows($query_aluno);
		  

		echo moeda_br(array_sum($val) - $desc_geral + $ac_geral); $valor = moeda_br(array_sum($val) - $desc_geral + $ac_geral); ?></strong></td>
        <?php 
		$sql_update = "UPDATE aluno_pacote_id SET valor_final = '".valorbanco($valor)."', valor_aulas = '".array_sum($val0)."', valor_desconto = '".(array_sum($desc) + $desc_geral)."', valor_acrescimo = '".(array_sum($ac) + $ac_geral)."' WHERE cod_pacote_id = '$cod_pac'";
		mysql_query($sql_update) or die (mysql_error());
		/*
		function Numero($n){
	
			$numero = strlen($n);
			
			if($numero == 1)
			{
				$n = "0".$n;
			}
			
			return $n;
				
		}
		$dia = Numero($row_aluno['dia_pagto']);
		$hoje = date("Y-m-d");
		
		if($day < date("d"))
		{
			$vencimento = date("Y-m-$dia", strtotime('+1 month', strtotime($hoje)));
		}
		else
		{
			$vencimento = date("Y-m-$dia");
		}
		
		
		if($total_aluno == 0)
		{
			$insert = mysql_query("INSERT INTO pagto_aluno (cod_aluno, cod_pacote_id, valor, dt_vencimento, quitado) VALUES ('$var', '$pac_id', '".(array_sum($val) - $desc_geral + $ac_geral)."', '$vencimento', 'N')") or die("Não foi possível gerar o pagamento!");  
		}
		else
		{
			$total_pago = mysql_query("SELECT SUM(valor_pago)AS valor_pago FROM pagto_aluno WHERE cod_pacote_id = '".$pac_id."' AND cod_aluno = '$var' AND quitado = 'S'");
			$row_pago = mysql_fetch_assoc($total_pago);
			
			$update = mysql_query("UPDATE pagto_aluno SET valor = '".(array_sum($val) - $desc_geral + $ac_geral - $row_pago['valor_pago'])."' WHERE cod_pacote_id = '$pac_id' AND quitado = 'N'") or die("Não foi possível atualizar o pagamento!");  
		}*/
		?>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
  </tfoot>
</table>
<?php
}
?>

<div class="container_botao">
<div style="padding: 12px;" align="center">
    <?php if($exportacao == "S"){ ?>
    <input name="Button" type="button" class="b-export" value="Exportar dados" onClick ="$('#tabela').tableExport({type:'excel',escape:'false'});" />
    <script>function Exportar(){location.href="relatorios/index.php";}</script>
    <input name="print" type="button" class="b-print" value="Imprimir" onclick="Imprimir()" />
    <script>function Imprimir(){window.open('relatorios/print.php','IMPRIMIR','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,fullscreen=yes');}</script>
    <?php } ?>
    <?php 
	if($alteracao == 'S')
	{
	?>
    <input name="editar" type="button" class="b-novo" id="editar" onClick="NewWindow('atualiza_pacote.php?cod_pacote=<?php echo $row_verifica_pacote['cod_pacote']; ?>','name','800','600','yes');return false;" value="Editar">
	<?php 
	}
	?>
    <input name="voltar" type="button" class="b-voltar voltar" value="Voltar">
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

mysql_free_result($aula);
?>

