<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php 
$fvar2 = 2;
require_once('verifica.php'); 
?>
<?php 
header('Content-Type: text/html; charset=iso-8859-1');

unset($_SESSION['sql']); 
$var = $_REQUEST['cod_aula'];
$var1 = $_REQUEST['cod_pacote'];
//Filtrar por nome

//Validação da permissão
if($cadastro == "N")
{
	echo $mensg = "<script>alert('Você não tem permissão para cadastro neste módulo')</script>";
	echo $mensg = "<script>window.close();</script>";
	echo $mensg = "<script>history.back();</script>";
	exit;
}
		
mysql_select_db($database_conecta, $conecta);
$query_verifica = "SELECT p.*, a.grupo
				   FROM professor_aula pa, professor p, aula a
				   WHERE pa.cod_professor = p.cod_professor 
				   AND pa.cod_aula = '$var'
				   AND pa.cod_aula = a.cod_aula
				   AND p.status = 'S'
				   AND pa.cod_aula_dia IS NULL
				   ORDER BY p.nome_professor ASC";
$verifica = mysql_query($query_verifica, $conecta) or die(mysql_error());
$row_verifica = mysql_fetch_assoc($verifica);
$totalRows_verifica = mysql_num_rows($verifica);

?>
<?php
$mes = date("m");
$ano = date("Y");
$dia = date("d");
$dia_semana = date("w");

$cont=0;


?>
<?php 
if($row_verifica['grupo'] == 'N')
{
	
	mysql_select_db($database_conecta, $conecta);
$query_dia = "SELECT * FROM dia_semana ORDER BY cod_dia ASC";
$dia = mysql_query($query_dia, $conecta) or die(mysql_error());
$row_dia = mysql_fetch_assoc($dia);
$totalRows_dia = mysql_num_rows($dia);

if(isset($_GET['cod_aluno_dia_aula'])  && ($_REQUEST['cod_aluno_dia_aula'] <> ''))
{
	mysql_select_db($database_conecta, $conecta);
	echo $query_remarcado = "SELECT aa.*, ap.* FROM aluno_dia_aula aa, aluno_pacote_aula ap WHERE aa.cod_aluno_pacote_aula = ap.cod_aluno_pacote_aula AND aa.cod_aluno_dia_aula = '".$_GET['cod_aluno_dia_aula']."'";
	$remarcado = mysql_query($query_remarcado, $conecta) or die(mysql_error());
	$row_remarcado = mysql_fetch_assoc($remarcado);
	$totalRows_remarcado = mysql_num_rows($remarcado);
}

?>
<table width="100%">
  <tr valign="baseline">
    <td colspan="2" align="right" nowrap="nowrap"><hr /></td>
  </tr>
  <tr valign="baseline">
    <td width="18%" align="right" nowrap="nowrap">Professor:</td>
    <td width="82%"><select name="cod_professor" id="cod_professor">
      <?php
        do { 
        ?>
      <option value="<?php echo $row_verifica['cod_professor']; ?>" ><?php echo $row_verifica['nome_professor']?></option>
      <?php
        } while ($row_verifica = mysql_fetch_assoc($verifica));
        ?>
    </select>
    <a href="#" onclick="NewWindow('cad_aula_professor.php?cod_aula=<?php echo $var; ?>', 'INTRANET', 700, 500); return false"><img src="icones/Add.png" width="16" height="16" /></a></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Selecionar mais de um dia?</td>
    <td><input name="multi" type="checkbox" id="multi" value="S" />
      <span class="msg"></span></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right" valign="top">Dia:</td>
    <td><select name="dia[]" id="dia">
      <?php
$dt = date('Y-m-d');
do {  

if($row_dia['cod_dia'] == 10)
{
	$dt = 10;	
}
elseif(date('l') == $row_dia['en'])
{
	$dt = date('Y-m-d');
}
else
{
	$dt = date('Y-m-d', strtotime('Next '.$row_dia['en']));
}

/*
elseif(date('w') == $row_dia['cod_dia'])
{
	$dt = date('Y-m-d');
}
elseif(date('w') > $row_dia['cod_dia'])
{
	$dt = date('Y-m-d', strtotime('+6 Day', strtotime($dt)));
}
else
{
	$dt = date('Y-m-d', strtotime('+1 Day', strtotime($dt)));
}*/
?>
      <option value="<?php echo $dt; ?>" <?php echo ($dt == date('Y-m-d'))? 'selected="selected"':""; ?>><?php echo $row_dia['desc_dia']; ?></option>
      <?php
} while ($row_dia = mysql_fetch_assoc($dia));
  $rows = mysql_num_rows($dia);
  if($rows > 0) {
      mysql_data_seek($dia, 0);
	  $row_dia = mysql_fetch_assoc($dia);
  }
?>
    </select>
      <span class="dia_avulso"></span></td>
  </tr>
  <tr valign="baseline">
    <td nowrap="nowrap" align="right">Hora in&iacute;cial:</td>
    <td><input name="hr_inicio" type="text" class="hora" value="" size="5" maxlength="5" />
      Hora final:
      <input name="hr_fim" type="text" class="hora" value="" size="5" maxlength="5" />
      <input name="tipo" type="hidden" id="tipo" value="1" /></td>
  </tr>
  </table>
<script>
	$(".hora").mask("99:99");
	$(".hora").attr("placeholder", "hh:mm");
<?php 
	if(isset($_GET['cod_aluno_dia_aula']))
	{
	?>
	$("#dia option").remove();
	$("#dia").append('<option value="10">Dia específico</option>').attr("disabled", true);;
	$(".dia_avulso").html(' <input name="dia2" value="<?php echo date('d/m/Y'); ?>" class="dt1 data" size="10" maxlength="10" /> <br><br> <strong>Observação</strong><br><br><textarea name="informacao" id="informacao" cols="45" rows="5"></textarea>');	
	<?php	
	}
	?>
	
	$("#dia").change(function(e){
		var dia = $(this).val();
		if(dia == 10){
			$(".dia_avulso").html(' Selecione um dia: <input name="dia2" value="<?php echo date('d/m/Y'); ?>" class="data" size="10" maxlength="10" />');	
		}
		else
		{
			$(".dia_avulso").html('');
		}	
	});
	
	$("#multi").click(function(){
		var checado = $(this).is(':checked');	
		
		if(checado == true){
			$("#dia").attr({
				multiple: 'multiple',
				size: 8
			});
			$(".msg").html(" Precione a tecla \"Ctrl\" e selecione os dias da semana que desejar.");
			$("#dia option[value='10']").remove();
		}
		else
		{
			$("#dia").removeAttr('multiple');
			$("#dia").removeAttr('size');
			$(".msg").html('');
			$("#dia").append('<option value="10">Dia específico</option>');
		}
	});
</script>

<?php 
}
elseif($row_verifica['grupo'] == 'S')
{
	mysql_select_db($database_conecta, $conecta);
	$query_verifica2 = "SELECT ds.*, ad.cod_aula_dia, ad.hr_inicio, ad.hr_fim, (SELECT COUNT(aa.cod_aluno_pacote_aula) FROM aluno_pacote_aula aa WHERE aa.cod_aula_dia = ad.cod_aula_dia AND aa.cod_pacote = '$var1')AS pacote, (SELECT COUNT(pa.cod_professor) FROM professor_aula pa WHERE pa.cod_aula_dia = ad.cod_aula_dia)AS qtde_prof, (SELECT a.max_aluno - COUNT(aa.cod_aluno_pacote_aula) FROM aluno_pacote_aula aa WHERE aa.cod_aula_dia = ad.cod_aula_dia)AS vagas
						FROM aula_dia ad, dia_semana ds, aula a
						WHERE ad.cod_aula = '$var'
						AND ad.cod_dia = ds.cod_dia
						AND ad.cod_aula = a.cod_aula
						ORDER BY ds.cod_dia ASC, ad.hr_inicio, ad.hr_fim DESC";
	$verifica2 = mysql_query($query_verifica2, $conecta) or die(mysql_error());
	$row_verifica2 = mysql_fetch_assoc($verifica2);
	$totalRows_verifica2 = mysql_num_rows($verifica2);

?>
    <table width="100%">
  <tr>
    <td width="18%">&nbsp;</td>
    <td width="82%">
    <input type="button" value="Adicionar aula" class="b-novo" onclick="NewWindow('cad_aula_dia.php?cod_aula=<?php echo $var; ?>', 'INTRANET', 700, 500)" />
		<?php
        if($totalRows_verifica2 > 0)
        {
			$dia = date('Y-m-d');
        ?>
        <table width="100%">
			<?php
            do{
						
					if($row_verifica2['cod_dia'] == 10)
					{
						$dia = 10;	
					}
					elseif(date('l') == $row_verifica2['en'])
					{
						$dia = date('Y-m-d');
					}
					else
					{
						$dia = date('Y-m-d', strtotime('Next '.$row_verifica2['en']));
					}
					
					
					if(($row_verifica2['vagas'] == 0) || ($row_verifica2['pacote'] > 0) || ($row_verifica2['qtde_prof'] == 0))
					{ 
						$desativar = 'disabled="disabled" '; 
					}
					else
					{
						$desativar = ''; 
					}
					
					
            ?>
                <tr>
                    <td width="2%"><input name="dia[]" type="checkbox" id="select" value="<?php echo $dia; ?>|<?php echo ($row_verifica2['hr_inicio']); ?>|<?php echo ($row_verifica2['hr_fim']); ?>|<?php echo ($row_verifica2['cod_aula_dia']); ?>" <?php echo $desativar; ?> /></td>
                    <td width="5%"><?php echo $row_verifica2['desc_dia']; ?></td>
                    <td width="14%"><?php echo horabr2($row_verifica2['hr_inicio']); ?> à <?php echo horabr2($row_verifica2['hr_fim']); ?></td>
                    <td width="17%">Vagas: <?php echo $row_verifica2['vagas']; ?>
                    <input type="hidden" name="vagas[<?php echo $row_verifica2['cod_aula_dia']; ?>]" value="<?php echo $row_verifica2['vagas']; ?>" /></td>
                    <td width="62%">Professores: <a href="lista_aula_professor.php?cod_aula=<?php echo $var; ?>&add=<?php echo $row_verifica2['cod_aula_dia']; ?>"><?php echo $row_verifica2['qtde_prof']; ?></a></td>
                </tr>
            <?php
            }while($row_verifica2 = mysql_fetch_assoc($verifica2));
            ?>
            <tr>
            	<td colspan="5"><p><span class="Verdade12vermelhonegrito">Obs.</span><br />
            	  <span class="Verdana10cinzanormal">Os campos ser&atilde;o desativados nos casos:</span></p>
            	  <ol>
            	    <li><span class="Verdana10cinzanormal">O n&uacute;mero de vagas seja igual a 0</span>.</li>
            	    <li>S<span class="Verdana10cinzanormal">e n&atilde;o 
           	    houver professor(es) cadastrado na modalidade selecionada.</span></li>
           	    </ol></td>
            </tr>
        
    </table>
    <?php 
        }
        else
        {
        ?>
	
    <table>
    	<tr>
                <td align="left">Não existe nenhuma aula cadastrada par este grupo. <a href="javascript:void(0)" onclick="NewWindow('cad_aula_dia.php?cod_aula=<?php echo $var; ?>', 'INTRANET', 700, 500)">Clique aqui</a> para cadastrar</td>
        </tr>
    </table>
    <?php 
	}
	?>
    </td>
  </tr>
</table>

    <script>
	$("div.labelprof").html('Horário:');
	</script>
<?php
}
else
{
?>
	<table width="100%">
        <tr>
            <td colspan="3" align="left">Não existe nenhum professor cadastrado para essa aula. <a href="javascript:void(0)" onclick="NewWindow('cad_aula_professor.php?cod_aula=<?php echo $var; ?>', 'INTRANET', 700, 500)">Clique aqui</a> para cadastrar</td>
        </tr>
    </table>
<script>
	$("div.labelprof").html('Professor:');
	</script>
<?php
}
?>


      