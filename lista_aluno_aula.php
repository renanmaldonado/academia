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
$var1 = $_REQUEST['cod_aula'];
$var2 = $_REQUEST['cod_aluno_pacote_config'];
//Filtrar por nome
	

mysql_select_db($database_conecta, $conecta);
$query_verifica_pacote = "SELECT * FROM aluno_pacote_id WHERE cod_aluno = '$var' AND status = 'S'";
$verifica_pacote = mysql_query($query_verifica_pacote, $conecta) or die(mysql_error());
$row_verifica_pacote = mysql_fetch_assoc($verifica_pacote);
$totalRows_verifica_pacote = mysql_num_rows($verifica_pacote);

if($totalRows_verifica_pacote == 0)
{
	$insert = mysql_query("INSERT INTO aluno_pacote_id (cod_aluno, dt_cadastro) VALUES ('$var', DATE(NOW()))", $conecta);
	$pac = mysql_insert_id();
	header("Location: lista_aluno_aula.php?cod_aluno=$var&cod_pacote_id=$pac");
	exit;
}	
	
$filtro = $_POST['cod_aula'];	
if(isset($filtro) && ($filtro != ''))
{
	$filtro = "AND atu.cod_aula = '$filtro'";	
}
else
{
	$filtro = 'AND atu.cod_aula > 0';	
}

mysql_select_db($database_conecta, $conecta);
$query_verifica_config= "SELECT pc.* 
						 FROM aluno_pacote_config pc, aluno_pacote_id ai
						 WHERE cod_aluno_pacote_config = '$var2' 
						 AND pc.cod_pacote_id = ai.cod_pacote_id
						 AND ai.cod_aluno = '$var'
						 AND ai.status = 'S'";
$verifica_config = mysql_query($query_verifica_config, $conecta) or die(mysql_error());
$row_verifica_config = mysql_fetch_assoc($verifica_config);
$totalRows_verifica_pacote = mysql_num_rows($verifica_config);
		
$sql = "SELECT atu.*, a.desc_aula, p.nome_professor, (SELECT SUM(pa.valor_cobrado)
																   FROM professor_aula pa
																   WHERE pa.cod_aula = atu.cod_aula 
																   AND pa.cod_professor = atu.cod_professor)AS valor_cobrado, ai.acrescimo, ai.desconto, ai.justifica_desconto, ai.justifica_acrescimo, a.valor_padrao
		FROM (aluno_pacote atu, aula a, aluno_pacote_id ai)
		LEFT JOIN professor p ON(p.cod_professor = atu.cod_professor)
		WHERE atu.cod_aula = a.cod_aula
		AND ai.cod_pacote_id = atu.cod_pacote_id
		AND ai.status = 'S'
		AND atu.cod_aluno = '$var'
		AND atu.status = 'S'
		$filtro
		ORDER BY atu.dt_aula, atu.hr_inicio ASC";
$_SESSION['sql']= $sql;

mysql_select_db($database_conecta, $conecta);
$query_lista = "$sql";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

mysql_select_db($database_conecta, $conecta);
$query_pacote_id = "SELECT * FROM aluno_pacote_id WHERE cod_aluno = '$var' AND status = 'S'";
$pacote_id = mysql_query($query_pacote_id, $conecta) or die(mysql_error());
$row_pacote_id = mysql_fetch_assoc($pacote_id);
$totalRows_pacote_id = mysql_num_rows($pacote_id);

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT nome_aluno FROM aluno WHERE cod_aluno = '$var'";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

mysql_select_db($database_conecta, $conecta);
$query_aula = "SELECT a.*
			   FROM aula a, aluno_pacote_config pc, aluno_pacote_id ai
			   WHERE a.cod_aula = pc.cod_aula
			   AND pc.cod_pacote_id = ai.cod_pacote_id
			   AND ai.status = 'S'
			   AND ai.cod_aluno = '$var'
			   ORDER BY a.desc_aula ASC";
$aula = mysql_query($query_aula, $conecta) or die(mysql_error());
$row_aula = mysql_fetch_assoc($aula);
$totalRows_aula = mysql_num_rows($aula);

mysql_select_db($database_conecta, $conecta);
$query_dia = "SELECT * FROM dia_semana ORDER BY cod_dia ASC";
$dia = mysql_query($query_dia, $conecta) or die(mysql_error());
$row_dia = mysql_fetch_assoc($dia);
$totalRows_dia = mysql_num_rows($dia);


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
    <td width="23%" align="left"><form name="form1" method="get" action="">
      <table width="100%" cellpadding="0">
        <tr>
          <td width="43%"><select name="cod_aula" id="cod_aula">
          <option value="">Todas</option>
          <?php
		  do{
		  ?>
          <option value="<?php echo $row_aula['cod_aula']; ?>" <?php echo ($row_aula['cod_aula'] == $_REQUEST['cod_aula']) ? "selected" : "" ; ?> ><?php echo $row_aula['desc_aula']; ?></option>
          <?php
		  }while($row_aula = mysql_fetch_assoc($aula));
		  ?>
          </select>
            <input name="cod_aluno" type="hidden" id="cod_aluno" value="<?php echo $var; ?>">
            <input name="cod_aluno_pacote_config" type="hidden" id="cod_aluno_pacote_config" value="<?php echo $var2; ?>"></td>
          <td width="57%" align="right"><input name="button2" type="submit" class="b-filtrar" id="button2" value="Buscar"></td>
        </tr>
      </table>
    </form></td>
    <td width="58%" align="right">&nbsp;</td>
    <td width="19%" align="right">
    <?php
	if(($cadastro == 'S') && ($row_verifica_config['qtde_aula'] > $totalRows_lista))
	{
	?>
    	<input name="button" type="submit" class="b-novo" id="button" value="Adicionar" onClick="NewWindow('cad_aluno_aula.php?cod_aluno=<?php echo $var; ?>&cod_pacote_id=<?php echo $row_verifica_pacote['cod_pacote_id']; ?>&cod_aula=<?php echo $var1; ?>&cod_aluno_pacote_config=<?php echo $var2; ?>','name','600','600','yes');return false;">
    <?php
	}
	?>
    </td>
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
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalhe">
        <!--DWLayoutTable-->
        <tr valign="middle"> 
          <td height="19">Total de registros: <span class="font10negrito"> 
            <?php echo $totalRows_lista; ?></span></td>
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
    <th width="8%" align="left">Modalidade</th>
    <th width="8%" align="left">Dia</th>
    <th width="9%" align="center">Hora in&iacute;cial</th>
    <th width="9%" align="center">Hora final</th>
    <th width="9%" align="center">Desconto</th>
    <th width="9%" align="center">Acr&eacute;scimo</th>
    <th width="9%" align="center">Valor</th>
    <th width="9%" align="center">Alterar</th>
    <th width="4%" align="center">Excluir</th>
  </tr>
  </thead>
  <tbody>
  <?php
  do{
	  $pac = $row_lista['cod_aluno_pacote'];
  ?>
  <tr class="gradeA">
    <td><?php echo $row_lista['desc_aula']; ?></td>
    <td><?php echo voltadobanco($row_lista['dt_aula']); ?></td>
    <td align="center"><a href="atualiza_hora_aula.php?cod_aluno_pacote=<?php echo $row_lista['cod_aluno_pacote']; ?>" onClick="NewWindow(this.href,'name','600','600','yes');return false;"><?php echo horabr2($row_lista['hr_inicio']); ?></a></td>
    <td align="center"><?php echo horabr2($row_lista['hr_fim']); ?></td>
    <td align="center">R$ <?php echo moeda_br($row_lista['desconto_aula']); $desc_aula[] = $row_lista['desconto_aula']; ?></td>
    <td align="center">R$ <?php echo moeda_br($row_lista['acrescimo_aula']); $acre_aula[] = $row_lista['acrescimo_aula']; ?></td>
    <td align="center">R$ <?php echo ($row_lista['cod_aula'] == 1)? $row_lista['valor_padrao'] : moeda_br($row_lista['valor_cobrado']); 
								$val1[] = ($row_lista['cod_aula'] == 1)? $row_lista['valor_padrao'] : $row_lista['valor_cobrado'];
								$acrescimo = $row_lista['acrescimo']; 
								$desconto = $row_lista['desconto']; 
								$justifica_acrescimo = $row_lista['justifica_acrescimo']; 
								$justifica_desconto = $row_lista['justifica_desconto']; 
								?></td>
    <td align="center"><?php if($alteracao == "S"){ ?>
      <a href="atualiza_aula_aluno.php?cod_aluno_pacote=<?php echo $row_lista['cod_aluno_pacote']; ?>" onClick="NewWindow(this.href,'name','600','600','yes');return false;"><img src="icones/edititem.gif" width="16" height="16"></a>
      <?php 
	}
	?></td>
    <td align="center">
      <?php if($exclusao == "S"){ ?>
      <a href="exclui_aluno_aula.php?cod_aluno_pacote=<?php echo $row_lista['cod_aluno_pacote']; ?>&cod_aluno=<?php echo $row_lista['cod_aluno']; ?>&cod_aluno_pacote_config=<?php echo $var2; ?>&cod_aula=<?php echo $var1; ?>" class="delete" rel="Tem certeza que deseja excluir esse registro?">
        <img src="icones/ico_remover.gif" width="16" height="16" border="0" class="">
        </a>
      <?php } ?>
    </td>
  </tr>
  <?php 
  }while($row_lista = mysql_fetch_assoc($lista));
  ?>
  </tbody>
  <tfoot>
  	<tr align="center">
  	  <td></td>
        <td></td>
        <td bgcolor="#FFD5D6" valign="top"><strong>Desc. Geral: <br>
        R$ <a href="#" title="<?php echo $justifica_desconto; ?>" <?php echo ($justifica_desconto == '')? "" :'onClick="Alert(this.title)"'; ?>><?php echo moeda_br($desconto); ?></a></strong></td>
        <td bgcolor="#D9D9FF" valign="top"><strong>Ac. Geral: <br>
        R$ <a href="#" title="<?php echo $justifica_acrescimo; ?>" <?php echo ($justifica_acrescimo == '')? "" :'onClick="Alert(this.title)"'; ?>><?php echo moeda_br($acrescimo); ?></a></strong></td>
      <td bgcolor="#FFD5D6" valign="top"><strong>Desc. Aula:<br>          
        R$<a href="#"> <?php echo moeda_br(array_sum($desc_aula)); ?></a></strong></td>
      <td bgcolor="#D9D9FF" valign="top"><strong>Ac. Aula:<br>
        R$ <a href="#"><?php echo moeda_br(array_sum($acre_aula)); ?></a></strong></td>
      <td bgcolor="#CEFFCE" valign="top"><strong>Total: <br>
        R$ <a href="#"><?php echo moeda_br(array_sum($val1)); ?></a></strong></td>
        <td bgcolor="#FFE7AE" valign="top"><strong>Valor final:<br>
        R$ <a href="#"><?php echo moeda_br(array_sum($val1) + $acrescimo - $desconto + array_sum($acre_aula) - array_sum($desc_aula)); ?></a></strong></td>
        <td valign="top">&nbsp;</td>
    </tr>
  </tfoot>
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
    <?php 
	if($alteracao == 'S')
	{
	?>
    <input name="editar" type="button" class="b-novo" id="editar" onClick="NewWindow('atualiza_pacote.php?cod_pacote_id=<?php echo $row_pacote_id['cod_pacote_id']; ?>','name','800','600','yes');return false;" value="Editar">
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

