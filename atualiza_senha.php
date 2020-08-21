<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php
$func = $_SESSION['id'];

mysql_select_db($database_conecta, $conecta);
$query_atualiza = sprintf("SELECT nome, senha FROM usuario WHERE cod_usuario = '$func'");
$atualiza = mysql_query($query_atualiza, $conecta) or die(mysql_error());
$row_atualiza = mysql_fetch_assoc($atualiza);
$totalRows_atualiza = mysql_num_rows($atualiza);
?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . $_SERVER['QUERY_STRING'];
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

  if($_POST['senha'] == "")
  {
	  $pass = hash('sha512', $_POST['senha_atual']);
  }
  else
  {
	  $pass = hash('sha512', $_POST['senha']);
  }

  $updateSQL = sprintf("UPDATE usuario SET senha='$pass' WHERE cod_usuario = '$func'");

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($updateSQL, $conecta) or die(mysql_error());

  $updateGoTo = "msg_sucesso.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<noscript>
  <meta http-equiv="Refresh" content="1; url=javascript.php">
</noscript>
<title>INTRANET</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="cache-control"   content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<link href="plugin/datatables/media/css/main.css" rel="stylesheet" type="text/css">
<link href="css/intranet.css" rel="stylesheet" type="text/css">
<script src="js/jquery.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.maskedinput.js"></script>
<script type="text/javascript">
$(document).ready(function(){

});

</script>
<script src="js/scripts.js"></script>

</head>

<body>
<form method="POST" name="form1" id="form1" action="<?php echo $editFormAction; ?>">
  <table width="100%" align="center">
    <!--DWLayoutTable-->
    <tr valign="baseline" class="detalhe"> 
      <td height="22" colspan="2" align="right" valign="middle" nowrap class="fonte"> 
        <div align="center" class="titulo">ALTERA&Ccedil;&Atilde;O DE SENHA</div></td>
    </tr>
    <tr valign="baseline"> 
      <td width="154" align="right" valign="middle" nowrap class="fonte">&nbsp;</td>
      <td width="846" valign="middle"><span class="fonteazul"></span></td>
    </tr>
    <tr valign="baseline"> 
      <td align="right" valign="middle" nowrap class="labels">Usu&aacute;rio</td>
      <td valign="middle"> <span class="font10negrito"><?php echo $row_atualiza['nome']; ?></span></td>
    </tr>
    <tr valign="baseline"> 
      <td align="right" valign="middle" nowrap class="labels">Nova senha:</td>
      <td><input name="senha" type="password" class="campo" onFocus="this.className='campo_over'" onBlur="this.className='campo'" onKeyUp="javascript:somente_numero(this);" size="32">
      <input name="senha_atual" type="hidden" id="senha_atual" value="<?php echo $row_atualiza['senha']; ?>"></td>
    </tr>
    <tr valign="baseline"> 
      <td align="right" valign="middle" nowrap class="fonte">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline" class="detalhe"> 
      <td colspan="2" align="right" nowrap><div align="center"> 
          <input type="hidden" name="MM_update" value="form1">
          <br>
        </div></td>
    </tr>
  </table>
	<br><br>
<div style="position:fixed; bottom:0; height:40px; left: 0; right: 0; background-image:url(../extras/transp_cinza.png); background-repeat: repeat-x; width:100%">
<div style="padding: 7px;" align="center">
        <input type="submit" class="b-salvar" value="Salvar" />
        <input type="button" name="btnClear" class="b-cancelar fechar" value="Cancelar" />
</div>
</div>
</form>
</body>
</html>
<?php
mysql_free_result($atualiza);
?>

