<?php 
function upper($str)
{
	$LATIN_UC_CHARS = '�����������������������������';
	$LATIN_LC_CHARS = '�����������������������������';
	$str = strtr ($str, $LATIN_LC_CHARS, $LATIN_UC_CHARS);
	$str = strtoupper($str);
	return $str;
}

function minuscula($str)
{
	$LATIN_UC_CHARS = '�����������������������������';
	$LATIN_LC_CHARS = '�����������������������������';
	$str = strtr ($str, $LATIN_LC_CHARS, $LATIN_UC_CHARS);
	$str = strtolower($str);
	return $str;
}

function convertem($term, $tp) {
    if ($tp == "1") $palavra = strtr(strtoupper($term),"������������������������������","������������������������������");
    elseif ($tp == "0") $palavra = strtr(strtolower($term),"������������������������������","������������������������������");
    return $palavra;
} 
?>