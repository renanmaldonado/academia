<?php 
    //funçao vai para o banco virgula para ponto
	function valorbanco($texto)
	{
		$texto=str_replace(array("."), "", $texto);
		$texto=str_replace(array(","), ".", $texto);
		return $texto;
	}
	//transforma ponto em vazio
	function limpaparaobanco($texto)
	{
		$texto=str_replace(array("."), "", $texto);
		return $texto;
	}
    //transforma virgula em ponto
	function LimparTexto($texto)
	{
		$texto=str_replace(array(","), ".", $texto);
		return $texto;
	}
	
	//funçao que retorna o valor em formato de moeda
	function moeda_br($numero){
		if($numero == ''){
			$numero = "0.00";	
		}
    return number_format(LimparTexto($numero), 2, ',', '.');
    }
	
	//funçao que retorna o valor em formato de moeda
	function lista($texto){
	return str_replace(array("."), "", moeda_br($texto));
    }

?>