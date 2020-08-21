<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php require_once("funcoes/formata_moeda.php"); ?>
<?php
$cod = $_REQUEST['cod_pacote'];
$aluno = $_REQUEST['cod_aluno'];

mysql_select_db($database_conecta, $conecta);
$query_ver_aluno = "SELECT * FROM aluno WHERE cod_aluno = '$aluno'";
$ver_aluno = mysql_query($query_ver_aluno, $conecta) or die(mysql_error());
$row_ver_aluno = mysql_fetch_assoc($ver_aluno);
$totalRows_ver_aluno = mysql_num_rows($ver_aluno);

mysql_select_db($database_conecta, $conecta);
$query_verifica = "SELECT * FROM faturamento WHERE cod_pacote = '$cod'";
$verifica = mysql_query($query_verifica, $conecta) or die(mysql_error());
$row_verifica = mysql_fetch_assoc($verifica);
$totalRows_verifica = mysql_num_rows($verifica);


if($cod <> '')
{
	$var = "WHERE cod_pacote = '$cod' AND cod_aluno = '$aluno'";
}

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT * FROM aluno_pacote 
			  $var";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);


do{

$cod = $row_ver['cod_pacote'];
$aluno = $row_ver['cod_aluno'];
	
mysql_select_db($database_conecta, $conecta);
$query_itens = "SELECT SUM(valor)AS valor
				FROM pagto_aluno 
				WHERE cod_pacote = '$cod' 
				AND cod_aluno = '$aluno'";
$itens = mysql_query($query_itens, $conecta) or die(mysql_error());
$row_itens = mysql_fetch_assoc($itens);
$totalRows_itens = mysql_num_rows($itens);



	$num = ($row_ver['pacote'] == 0)? 1 : $row_ver['pacote'];
	
	$valor = $row_itens['valor'] / $num;
	
	$nova_dt = date('Y-m-').Verifica_dia($row_ver['dia_pagto']);
	$dt_inicio = $nova_dt;
	
	
	function get_nextDay($day, $ano='Y', $mes="m", $ts=null)
	{
		if (is_null($ts)) {
			$ts = time();
		}
	 
		$month = date($mes, $ts);
		$year  = date($ano, $ts);
	 
		$wanted_ts = mktime(0,0,0, $month, $day, $year);
	 
		if ($wanted_ts < $ts) {
			$wanted_ts = strtotime('+1 month', $wanted_ts);
		}
	 
		return $wanted_ts;
	}
	 $dt_inicio = $row_ver['dt_inicio'];
	 $ex_dt = explode('-', $dt_inicio);
	 $ano = $ex_dt[0];
	 $mes = $ex_dt[1];
	 
	// Hoje é dia 11 de março de 2005
	$timestamp = get_nextDay(Verifica_dia($row_ver['dia_pagto']), $ano, $mes);
	 
	// isso vai imprimir '10/04/2005'
	$dt = $ano."-".$mes."-".$row_ver['dia_pagto'];
	$nova_dt = $dt;
	
	$sql = mysql_query("SELECT ad.* 
						FROM aluno_pacote_aula aa, aluno_dia_aula ad
						WHERE aa.cod_aluno_pacote_aula = ad.cod_aluno_pacote_aula
						AND aa.cod_pacote = '$cod'");
	$row = mysql_fetch_assoc($sql);	
	$total = mysql_num_rows($sql);	
	$parcela = $total / $row_ver['pacote'];			
	$parcela = explode('.', $parcela);
	$parcela = $parcela[0];
	
	$p = 1;
	$i = 1;
	do{
		
		if($p > $row_ver['pacote'])
		{
			$p = $row_ver['pacote']; 	
		}
		
		mysql_query("UPDATE aluno_dia_aula SET parcela='$p' WHERE cod_aluno_dia_aula = '".$row['cod_aluno_dia_aula']."'") or die(mysql_error());
		
		if($i == $parcela)
		{
			$p++;
			$i = 0;	
		}
		
		$i++;				
	}while($row = mysql_fetch_assoc($sql));
	
	$p = 1;	
	for($i = 0; $i < $num; $i++){
	
		if($totalRows_verifica > 0){
			mysql_query("UPDATE faturamento SET valor='$valor' WHERE cod_pacote = '$cod'") or die (mysql_error());
		}
		else
		{
			mysql_query("INSERT INTO faturamento(cod_pacote, valor, dt_vencimento, parcela) VALUES ('$cod', '$valor', '$nova_dt', '$p')") or die(mysql_error());
		}
		
		$nova_dt = date("Y-m-d", strtotime(date("Y-m-d", strtotime($nova_dt)) . " +1 month")); 
		$p++;
	}
}while($row_ver = mysql_fetch_assoc($ver));

header("Location: lista_mensalidades_receber.php?filtro=S&nome=".$row_ver_aluno['nome_aluno']);