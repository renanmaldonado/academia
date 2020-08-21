<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php require_once('funcoes/formata_moeda.php'); ?>
<?php 
//Validação do módulo
$fvar2 = 8;
require_once('verifica.php'); 
$_SESSION['cod'] = '';

//Validação da permissão
if($cadastro == "N")
{
	echo $mensg = "<script>alert('Você não tem permissão para cadastro neste módulo')</script>";
	echo $mensg = "<script>window.close();</script>";
	echo $mensg = "<script>history.back();</script>";
	exit;
}
?>
<?php
$var = $_REQUEST['cod'];
$var1 = $_REQUEST['cod_fatura'];

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT pa.*, a.nome_aluno, (SELECT SUM(f.valor_pago) FROM faturamento f WHERE f.cod_fatura = '$var1')AS valor
			  FROM pagto_aluno pa, aluno a
			  WHERE pa.cod_pacote = '$var'
			  AND a.cod_aluno = pa.cod_aluno";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);


mysql_select_db($database_conecta, $conecta);
$query_aulas = "SELECT DISTINCT(pa.cod_aula), a.desc_aula
				FROM pagto_aluno pa, aula a
				WHERE pa.cod_pacote = '$var' 
				AND pa.cod_aula = a.cod_aula
				AND pa.dt_vencimento = (SELECT f.dt_vencimento FROM faturamento f WHERE f.cod_fatura = '$var1')";
$aulas = mysql_query($query_aulas, $conecta) or die(mysql_error());
$row_aulas = mysql_fetch_assoc($aulas);
$totalRows_aulas = mysql_num_rows($aulas);

$modalidades = "<ul>";
do{
	mysql_select_db($database_conecta, $conecta);
	$sql = "SELECT COUNT(aa.cod_aula)AS qtde
			FROM aluno_pacote_aula aa, aluno_dia_aula ad, faturamento f
			WHERE aa.cod_aluno_pacote_aula = ad.cod_aluno_pacote_aula
			AND aa.cod_pacote = f.cod_pacote
			AND ad.parcela = f.parcela
			AND ad.parcela = (SELECT parcela FROM faturamento WHERE cod_fatura = '$var1')
			AND aa.cod_pacote = '$var'	
			AND aa.cod_aula = '".$row_aulas['cod_aula']."'";
	$query = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);

	$modalidades .= "<li>".$row['qtde']." aulas de ".$row_aulas['desc_aula']."</li>";
}while($row_aulas = mysql_fetch_assoc($aulas));
$modalidades .= "</ul>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>RECIBO - PILATES VILA MADÁ</title>

<style>
body{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;	
}
</style>
<script>
window.print();
</script>
</head>

<body>
<div style="position:absolute; width: auto; top: 0; left: 0; padding: 5px;">
<input name="imprimir" onclick="javascript:window.print();" type="button" value="Imprimir" />
</div>
<table width="650">
  <tbody>
    <tr>
      <td align="right"><img src="images/logo2.jpg" /></td>
    </tr>
    <tr>
      <td align="left">

        <p align="center"><strong>RECIBO</strong></p>
        <p>Recebemos de <strong><?php echo $row_ver['nome_aluno'];  ?></strong> a quantia de R$   <strong><?php echo moeda_br(abs($row_ver['valor'])); ?></strong> ,   referente ao pagamento das aulas:</p>
        <p>
        	<?php echo $modalidades; ?>
        </p>
        <p>S&atilde;o Paulo, <?php echo dt_extenso(date('Y-m-d')," de ", " de "); ?>          </p>
        <p>&nbsp;</p>
        <p><strong>JC Pilates Ltda          </strong></p>
        <p>&nbsp;</p>
        <p><strong>CONDI&Ccedil;&Otilde;ES PARA REPOSI&Ccedil;&Otilde;ES DE AULAS          </strong></p>
        <p><strong>Desmarcar a aula com direito a reposi&ccedil;&atilde;o ser&aacute; poss&iacute;vel desde que:          </strong></p>
        <ol>
          <li><strong>desmarque a aula com anteced&ecirc;ncia m&iacute;nima de 6 horas &uacute;teis;            </strong></li>
          <li><strong>reponha a aula desmarcada dentro do mesmo m&ecirc;s;            </strong></li>
          <li><strong>ser&aacute; permitido apenas uma &uacute;nica remarca&ccedil;&atilde;o por aula.            </strong></li>
          <li><strong> n&atilde;o consideramos desmarca&ccedil;&atilde;o por e-mail
            Fora dessas hip&oacute;teses, &eacute; considerada FALTA (aula dada).
            </strong></p>
          </strong></li>
        </ol>
        <p>ACUPUNTURA - RPG - TREINAMENTO FUNCIONAL - PILATES - FISIOTERAPIA        <br />
          Rua Medeiros de Albuquerque, n. 413 &ndash; Vila Madalena
          <br />
        CEP.: 05436-060 - S&atilde;o Paulo &ndash; SP.    Tel.: (11) 2337-0280 - (11) 8872-5297 - (11) 2337-0386</p></td>
    </tr>
  </tbody>
</table>
</body>
</html>