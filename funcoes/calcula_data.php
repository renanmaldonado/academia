<?php
function Dias($dias){
	
	$dt = date("Y-m-d", mktime (0,0,0,date("m"),date("d")+$dias,date("Y")));
	
	return $dt;
}
function Mes($d, $m, $y){
    
        /*$mês = mktime( 0, 0, 0, $m, 1, $y );  
        setlocale('LC_ALL', 'pt_BR');           
        $numero = intval(date("t",$mes));
	*/
	$dt = date("Y-m-d", mktime (0,0,0,$m,$d,$y));
	
	return $dt;
}
?>
