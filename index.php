<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php
$cod = $_SESSION['id'];

mysql_select_db($database_conecta, $conecta);
$query_verifica = "SELECT * FROM usuario WHERE cod_usuario = '$cod' AND status= 'S'";
$verifica = mysql_query($query_verifica, $conecta) or die(mysql_error());
$row_verifica = mysql_fetch_assoc($verifica);
$totalRows_verifica = mysql_num_rows($verifica);

if($totalRows_verifica == 0)
{
	if($totalRows_verifica > 0)
	{
		echo $mensg = "<script>alert('Você não tem acesso ao sistema!')</script>";
		echo $mensg2 = "<script>document.location='update.php'</script>";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<noscript>
  <meta http-equiv="Refresh" content="1; url=javascript.php">
</noscript>
<title>INTRANET</title>
</head>

<frameset rows="*,30" frameborder="NO" border="0" framespacing="0">
  <frameset rows="120,*" cols="*" framespacing="0" frameborder="yes" border="2" bordercolor="#CCCCCC">
    <frame src="topo.php" name="topFrame" scrolling="NO" noresize >
    <frameset cols="250,*" framespacing="0" frameborder="yes" border="2" bordercolor="#CCCCCC">
      <frame src="menu.php" name="leftFrame" scrolling="auto">
      <frame src="inicio.php" name="mainFrame" id="mainFrame">
    </frameset>
  </frameset>
  <frame src="rodape.php" name="bottomFrame" scrolling="NO" noresize>
</frameset>
<noframes><body>
</body></noframes>
</html>
<?php
mysql_free_result($verifica);
?>
