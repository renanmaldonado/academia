<?php
function ValidaData($dat){
$data = explode("/","$dat"); // fatia a string $dat em pedados, usando / como refer�ncia
$d = $data[0];
$m = $data[1];
$y = $data[2];
 
// verifica se a data � v�lida!
// 1 = true (v�lida)
// 0 = false (inv�lida)
$res = checkdate($m,$d,$y);
if ($res == 1){
	return true;
} else {
	return false;
}
}