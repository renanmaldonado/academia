<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php
$var = $_SESSION['id'];

mysql_select_db($database_conecta, $conecta);
$query_ver = "SELECT * FROM usuario WHERE cod_usuario = '$var'";
$ver = mysql_query($query_ver, $conecta) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);


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

<!-- CSS -->
<link href="plugin/datatables/media/css/main.css" rel="stylesheet" type="text/css">
<link href="css/intranet.css" rel="stylesheet" type="text/css">
</head>
<body>
<h1 align="center" class="Verdana12cinzanegrito" >Olá <?php echo $row_ver['nome']; ?>.</h1>
<h3 align="center" class="Verdana12cinzanormal">&nbsp;</h3>
<div align="center">
<ul style="width: auto">
  <li class="Verdana12cinzanormal">Para maiores informa&ccedil;&otilde;es entre com contato ou fa&ccedil;a download manual no link abaixo.
  </li>
</ul>
</div>
<p align="center"><a href="manual.pdf"><img src="extras/pdf_icone.png" ></a></p>
</body>
</html>