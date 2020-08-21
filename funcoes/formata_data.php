<?php
//Funчуo para salvar a data no banco no formato correto
function vaiparaobanco($dt)
{
	$transforma = explode("/",$dt);
	$retorna = $transforma[2]."-".$transforma[1]."-".$transforma[0];
	return $retorna;
}
	
//Funчуo para transforma a data do banco no formato brasileiro
function voltadobanco($dt)
{
	 if($dt == "")
	 {
		 	
	 }
	 else
	 {
	 	$transforma = explode("-",$dt); 
	 	$retorna = $transforma[2]."/".$transforma[1]."/".$transforma[0]; 
	 	return $retorna;
	 } 
}
	
//Funчуo que mostra a hora da data do SQL SERVER
function horabr($data_sqlserver)
{
	if ($data_sqlserver == "")
	return "";
	$dtTimeStamp = strtotime($data_sqlserver);
	$dataConvertida = date('H:m:s', $dtTimeStamp);
	return $dataConvertida;
}

//Funчуo que mostra a hora da data do SQL SERVER
function horabr2($data_sqlserver)
{
	return substr($data_sqlserver,0,-3);
}


//Funчуo que mostra a data no formato Brasil da data do SQL SERVER
function databr($data_sqlserver)
{
	if ($data_sqlserver == "")
	return "";

	$dtTimeStamp = strtotime($data_sqlserver);
	$dataConvertida = date('d/m/Y', $dtTimeStamp);
	return $dataConvertida;
}


//Funчуo que mostra a data e hora no formato do MYSQL
function dthr_mysql($data)
{
	 if($data == "")
	 {
		 	return "";
	 }
	 else
	 {
			$dt_timestamp = strtotime(str_replace("/","-",$data));
			$novo_formato = date('Y-m-d H:i:s',$dt_timestamp); 
			return $novo_formato;
	}
}
//Funчуo que mostra a data e hora no formato Brasil
function dthr_br($data)
{
	$dt_timestamp = strtotime(str_replace("/","-",$data));
	$novo_formato = date('d/m/Y H:i:s',$dt_timestamp); 
	return $novo_formato;
}

//Funчуo que mostra a data e hora no formato Brasil
function dthr_br1($data)
{
	$dt_timestamp = strtotime(str_replace("/","-",$data));
	$novo_formato = date('d/m/Y H:i',$dt_timestamp); 
	return $novo_formato;
}


//Funчуo converter data brasileira em MYSQL
function converter_data($strData)
{
	// Recebemos a data no formato: dd/mm/aaaa
	// Convertemos a data para o formato: aaaa-mm-dd
	if($strData == "")
	{
		$strDataFinal = "null";
	}
	elseif ( preg_match("#/#",$strData) == 1 )
	{
		$strDataFinal = "'";
		$strDataFinal .= implode('-', array_reverse(explode('/',$strData)));
		$strDataFinal .= "'";
	}
	return $strDataFinal;
}

function br_mysql($dt)
{
	$transforma = explode("/",$dt);
	$retorna = "20" . $transforma[2]."-".$transforma[1]."-".$transforma[0];
	return $retorna;
}
function DescData($mes){	
			  switch ($mes){
 
				case 1: $mes = "janeiro"; break;
				case 2: $mes = "fevereiro"; break;
				case 3: $mes = "marчo"; break;
				case 4: $mes = "abril"; break;
				case 5: $mes = "maio"; break;
				case 6: $mes = "junho"; break;
				case 7: $mes = "julho"; break;
				case 8: $mes = "agosto"; break;
				case 9: $mes = "setembro"; break;
				case 10: $mes = "outubro"; break;
				case 11: $mes = "novembro"; break;
				case 12: $mes = "dezembro"; break;
				 
				}
				
				return $mes;
}
function dt_extenso($dt,$txt=" ",$txt1=", ")
{
	$transforma = explode("-",$dt); 
	$retorna = $transforma[2].$txt.DescData($transforma[1]).$txt1.$transforma[0].'.'; 
	return $retorna;
		
}
function Verifica_dia($dia){
	if($dia < 9)
	{
		return '0'.$dia;	
	}
	else
	{
		return $dia;
	}
}

function Dia($dia){	
			  switch ($dia){
 
				case 1: $dia = "1"; break;
				case 2: $dia = "2"; break;
				case 3: $dia = "3"; break;
				case 4: $dia = "4"; break;
				case 5: $dia = "5"; break;
				case 6: $dia = "6"; break;
				case 7: $dia = "7"; break;
				 
				}
				
				return $dia;
}

function Diasemana($diasemana) {
	//$ano =  substr("$data", 0, 4);
	//$mes =  substr("$data", 5, -3);
	//$dia =  substr("$data", 8, 9);

	//$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );

	switch($diasemana) {
		case"0": $diasemana = "Domingo";       break;
		case"1": $diasemana = "Segunda-Feira"; break;
		case"2": $diasemana = "Terчa-Feira";   break;
		case"3": $diasemana = "Quarta-Feira";  break;
		case"4": $diasemana = "Quinta-Feira";  break;
		case"5": $diasemana = "Sexta-Feira";   break;
		case"6": $diasemana = "Sсbado";        break;
	}

	return $diasemana;
}

function DiasemanaW($data) {

	$diasemana = date("w", strtotime($data) );

	switch($diasemana) {
		case"0": $diasemana = "0";       break;
		case"1": $diasemana = "1"; break;
		case"2": $diasemana = "2";   break;
		case"3": $diasemana = "3";  break;
		case"4": $diasemana = "4";  break;
		case"5": $diasemana = "5";   break;
		case"6": $diasemana = "6";        break;
	}

	return $diasemana;
}

function UltimoDia($mes, $ano){
    $mes = $mes; // Mъs desejado, pode ser por ser obtido por POST, GET, etc.
    $ano = $ano; // Ano atual
    $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // Mсgica, plim!	
	
	return $ultimo_dia;
}
?>