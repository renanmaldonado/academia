<?php
session_start();

/*
@autor: Moacir Sel�nger Fernandes
@email: hassed@hassed.com
Qualquer d�vida � s� mandar um email
*/
 
// Fun��o que valida o CPF
function validaCPF($cpf)
{	// Verifiva se o n�mero digitado cont�m todos os digitos
$cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
// Verifica se nenhuma das sequ�ncias abaixo foi digitada, caso seja, retorna falso
if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999')
{
return false;
}
else
{ // Calcula os n�meros para verificar se o CPF � verdadeiro
for ($t = 9; $t < 11; $t++) {
for ($d = 0, $c = 0; $c < $t; $c++) {
$d += $cpf{$c} * (($t + 1) - $c);
}
 
$d = ((10 * $d) % 11) % 10;
 
if ($cpf{$c} != $d) {
return false;
}
}
 
return true;
}
}
mysql_select_db($database_conecta, $conecta);
function CPF($cpf){

	$id = $_SESSION['id'];
	$query_visualiza = "SELECT * FROM usuario WHERE cpf = '$cpf' AND cod_usuario <> '$id'";
	$visualiza = mysql_query($query_visualiza) or die(mysql_error());
	$row_visualiza = mysql_fetch_assoc($visualiza);
	$totalRows_visualiza = mysql_num_rows($visualiza);
	
	$cpf = validaCPF($cpf);
	if($cpf == true)
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
		return  '0';
	}
	
	
}

function isCnpjValid($cnpj)
	 	{
			//Etapa 1: Cria um array com apenas os digitos num�ricos, isso permite receber o cnpj em diferentes formatos como "00.000.000/0000-00", "00000000000000", "00 000 000 0000 00" etc...
			$j=0;
			for($i=0; $i<(strlen($cnpj)); $i++)
				{
					if(is_numeric($cnpj[$i]))
						{
							$num[$j]=$cnpj[$i];
							$j++;
						}
				}
			//Etapa 2: Conta os d�gitos, um Cnpj v�lido possui 14 d�gitos num�ricos.
			if(count($num)!=14)
				{
					$isCnpjValid=false;
				}
			//Etapa 3: O n�mero 00000000000 embora n�o seja um cnpj real resultaria um cnpj v�lido ap�s o calculo dos d�gitos verificares e por isso precisa ser filtradas nesta etapa.
			if ($num[0]==0 && $num[1]==0 && $num[2]==0 && $num[3]==0 && $num[4]==0 && $num[5]==0 && $num[6]==0 && $num[7]==0 && $num[8]==0 && $num[9]==0 && $num[10]==0 && $num[11]==0)
				{
					$isCnpjValid=false;
				}
			//Etapa 4: Calcula e compara o primeiro d�gito verificador.
			else
				{
					$j=5;
					for($i=0; $i<4; $i++)
						{
							$multiplica[$i]=$num[$i]*$j;
							$j--;
						}
					$soma = array_sum($multiplica);
					$j=9;
					for($i=4; $i<12; $i++)
						{
							$multiplica[$i]=$num[$i]*$j;
							$j--;
						}
					$soma = array_sum($multiplica);	
					$resto = $soma%11;			
					if($resto<2)
						{
							$dg=0;
						}
					else
						{
							$dg=11-$resto;
						}
					if($dg!=$num[12])
						{
							$isCnpjValid=false;
						} 
				}
			//Etapa 5: Calcula e compara o segundo d�gito verificador.
			if(!isset($isCnpjValid))
				{
					$j=6;
					for($i=0; $i<5; $i++)
						{
							$multiplica[$i]=$num[$i]*$j;
							$j--;
						}
					$soma = array_sum($multiplica);
					$j=9;
					for($i=5; $i<13; $i++)
						{
							$multiplica[$i]=$num[$i]*$j;
							$j--;
						}
					$soma = array_sum($multiplica);	
					$resto = $soma%11;			
					if($resto<2)
						{
							$dg=0;
						}
					else
						{
							$dg=11-$resto;
						}
					if($dg!=$num[13])
						{
							$isCnpjValid=false;
						}
					else
						{
							$isCnpjValid=true;
						}
				}
			//Trecho usado para depurar erros.
			/*
			if($isCnpjValid==true)
				{
					echo "<p><font color=\"GREEN\">Cnpj � V�lido</font></p>";
				}
			if($isCnpjValid==false)
				{
					echo "<p><font color=\"RED\">Cnpj Inv�lido</font></p>";
				}
			*/
			//Etapa 6: Retorna o Resultado em um valor booleano.
			return $isCnpjValid;			
		}