<?php 
ini_set('session.save_path', 'tmp');
@session_start();
if (basename($_SERVER["PHP_SELF"]) == "sessao_frame.php")
{
	session_destroy();
	header("Location: login.php");
} 
if
(
	(!isset($_SESSION['id'])) OR
	(!isset($_SESSION['ip']))
)
	{
		session_destroy();
		header("Location: login.php");
	}
   
?>