<?php
function criaResumo($string, $caracteres, $tipo = 1, $valor = 0) {
	$string = strip_tags($string);
	if (strlen($string) > $caracteres) {
		while (substr($string,$caracteres,1) <> ' ' && ($caracteres < strlen($string))){
			$caracteres++;
		};
	};
	
		return substr($string,0,$caracteres);
}

function ConsultaValor($tipo = 1, $valor = 0){
	if($tipo == 2)
	{
		return "<span>Leia</span>";
	}
	else
	{
		return "<span>$valor</span>";
	}
}