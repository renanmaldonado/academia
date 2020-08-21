<?php 
$path = "../";
require_once('../sessao.php'); 
?>
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

// definimos o tipo de arquivo
header("Content-type: application/msexcell");

// Como será gravado o arquivo
header("Content-Disposition: attachment; filename=dados.xls");

// montando a tabela
for($i = 0;$i<$num_campos; $i++)
{
	//Pega o nome dos campos
	$campos[] = mysql_field_name($lista, $i);
}

//Montando o cabeçalho da tabela
$tabela = '<table><tr>';
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
	$tabela .= '<tr>';
	for($i = 0;$i < $num_campos; $i++)
	{
		$valores = $r[$campos[$i]];
		$tabela .= '<td height="25" align="center">' . upper($valores) . '</td>';
	}
	$tabela .= '</tr>';
}
//Finalizando a tabela
$tabela .= '</tbody></table>';

//Imprimindo a tabela
echo $tabela;
?>

  
  

