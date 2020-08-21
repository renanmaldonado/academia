<?php

// Sanitizing the data, kind of done via error messages first. Twice is better!
function clean_var($variable) {
    if($variable <> ''){
        $variable = strip_tags(stripslashes(trim(rtrim(utf8_decode($variable)))));

    }
    return $variable;
}
function anti_injection($sql)
{
	// remove palavras que contenham sintaxe sql
	$sql = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"),"",$sql);
	$sql = trim($sql);//limpa espaços vazio
	$sql = strip_tags($sql);//tira tags html e php
	$sql = addslashes($sql);//Adiciona barras invertidas a uma string
	//$sql = str_replace("'",'', $sql);
	return $sql;
}
function Jpeg($arquivo){
	
	$file = pathinfo($arquivo, PATHINFO_EXTENSION);
	return $file;
	
}
