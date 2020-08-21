<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conecta = "Localhost";
$database_conecta = "academia";
$username_conecta = "root";
$password_conecta = "5850";
$conecta = @mysql_connect($hostname_conecta, $username_conecta, $password_conecta) or trigger_error(mysql_error(),E_USER_ERROR); 
?>