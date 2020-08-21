<?php
function ValidaData($dat){
$data = explode("/","$dat"); // fatia a string $dat em pedados, usando / como referência
$d = $data[0];
$m = $data[1];
$y = $data[2];
 
// verifica se a data é válida!
// 1 = true (válida)
// 0 = false (inválida)
$res = checkdate($m,$d,$y);
if ($res == 1){
	return true;
} else {
	return false;
}
}