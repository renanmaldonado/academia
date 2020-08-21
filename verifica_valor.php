<?php require_once('funcoes/formata_moeda.php'); ?>
<?php
$valor_total = $_REQUEST['valor_total'];
$valor_pago = valorbanco($_REQUEST['valor_pago']);

if($valor_total < $valor_pago)
{
	echo "false";	
}
else
{
	echo "true";	
}
?>