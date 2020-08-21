<?php 
$path = "../";
require_once('../sessao.php'); ?>
<?php require_once('../Connections/conecta.php'); ?>
<?php require_once('../funcoes/formata_data.php'); ?>
<?php require_once('../funcoes/formata_maiuscula.php'); ?>
<?php 

mysql_select_db($database_conecta, $conecta);
$query_lista = $_SESSION['sql'];
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$totalRows_lista = mysql_num_rows($lista);
	
//Pegando os nomes dos campos
$num_campos = mysql_num_fields($lista);//Obtém o número de campos do resultado

// montando a tabela
for($i = 0;$i<$num_campos; $i++)
{
	//Pega o nome dos campos
	$campos[] = mysql_field_name($lista, $i);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<noscript>
  <meta http-equiv="Refresh" content="1; url=javascript.php">
</noscript>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="cache-control"   content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<title>INTRANET</title>
</head>
<body onload="self.print();">
<?php

//Montando o cabeçalho da tabela
$tabela = '<table style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"><tr style="background-color:#666666; color:#FFFFFF">';
for($i = 0;$i < $num_campos; $i++)
{
	$dados = $campos[$i];
	$dados = str_replace('cod_', '', $dados);
	$dados = str_replace('dt_', 'data ', $dados);
	$dados = str_replace('n_', 'numero ', $dados);
	$dados = str_replace('_', ' ', $dados);
	$tabela .= '<td height="25" align="center"><strong>' . upper($dados) . '</strong></th>';
}

//Montando o corpo da tabela
$tabela .= '<tbody>';
while($r = mysql_fetch_array($lista))
{
	if($alternateColor++%2==0)
	{
		$cor = "#FFFFFF";
	}
	else
	{
		$cor = "#E2E2E2";
	} 
	$tabela .= '<tr bgcolor="' . $cor . '">';
	for($i = 0;$i < $num_campos; $i++)
	{
		$valores = $r[$campos[$i]];
		$tabela .= '<td height="25" align="center" nowrap>' . upper($valores) . '</td>';
	}
	$tabela .= '</tr>';
}
//Finalizando a tabela
$tabela .= '</tbody></table>';

//Imprimindo a tabela
echo $tabela;
?>
</body>
</html>


  
  

