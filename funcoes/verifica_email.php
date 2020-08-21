<?php
session_start();

function validaemail($email){
	$er = "/^(([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}){0,1}$/";
    if (preg_match($er, $email)){
    return 'valid';
    } else {
    return 'invalid';
    }   
}

function Email($email){

	$id = $_SESSION['id'];
		
	$query_visualiza = "SELECT * FROM usuario WHERE email = '$email' AND cod_usuario <> '$id'";
	$visualiza = mysql_query($query_visualiza) or die(mysql_error());
	$row_visualiza = mysql_fetch_assoc($visualiza);
	$totalRows_visualiza = mysql_num_rows($visualiza);
	
	$email = validaemail($email);
	if($email == 'valid')
	{
		if($totalRows_visualiza  == 0)
		{
			return  'true';
		}
		else
		{
			return  'false';
		}
	}
	else
	{
		return  'invalid';
	}
	
	
}