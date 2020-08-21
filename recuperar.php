<?php
$data = date("Y-m-d H:i:s");
require_once('servidor.php'); 
require_once('config/versao.php'); 
require_once('Connections/conecta.php'); 

session_start();
unset($_SESSION['id']);

$s = $_GET['s'];
$email = $_GET['email'];

mysql_select_db($database_conecta, $conecta);
$query_busca = "SELECT * FROM usuario WHERE email = '$email' AND senha='$s'";
$busca = mysql_query($query_busca, $conecta) or die(mysql_error());
$row_busca = mysql_fetch_assoc($busca);
$totalRows_busca = mysql_num_rows($busca);

$user = $row_busca['cod_usuario'];

if($totalRows_busca == 0)
{
	echo $mensg = "<script>alert('Dados incorretos, envie novamente um pedido de lembrete de senha!!!')</script>";    
	echo $acesso = "<script>window.location.href='login.php'</script>";
}
if(isset($_POST['Submit']))
{ 
 		if ((!isset($_POST['login'])) || (!isset($_POST['senha'])))
		{ 
        		echo $mensg = "<script>alert('Digite login e senha!!!')</script>";    
				echo $acesso = "<script>window.location.href='login.php'</script>";
    	}
		else
		{   
	 		$conecta = mysql_connect($hostname_conecta, $username_conecta, $password_conecta) or die (mysql_error()); 
        	mysql_select_db($database_conecta, $conecta); 
        
			$usuario_seguro = addslashes($_POST['login']);
	  		$senha_segura = addslashes($_POST['senha']);
			if (is_numeric($senha_segura))
			{
				
				//Criptografia 512 bits
				$pass = hash('sha512', $senha_segura);
				//$pass = $senha_segura;
				
				/* Verifica se existe usuários com aquela senha digitada!! */ 
       			$updateSQL = "UPDATE usuario SET senha = '$pass' WHERE login = '$usuario_seguro'"; 
				mysql_select_db($database_conecta, $conecta);
				$Result1 = mysql_query($updateSQL, $conecta) or die(mysql_error());
		 
				echo $mensg = "<script>alert('Senha alterada com sucesso!!!')</script>";
				echo $acesso = "<script>window.location.href='login.php'</script>";
			}
		}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<style type="text/css">
<!--
body {
	background-image: url(extras/bg-login.jpg);
	background-repeat: repeat-x;
}
-->
</style>
<noscript>
  <meta http-equiv="Refresh" content="1; url=javascript.php">
</noscript>
<title>ACESSO RESTRITO</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="cache-control"   content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<link href="css/teclado.css" rel="stylesheet" type="text/css">
<link href="css/intranet.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="js/teclado.js" type="text/javascript"></script>
<script language="JavaScript" src="js/script.js" type="text/javascript"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>

</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table width="761" height="598" border="0" align="center" cellpadding="0" cellspacing="0" background="extras/login.jpg" id="Tabela_01">
  <!--DWLayoutTable-->
  <tr> 
    <td width="41" height="47">&nbsp;</td>
    <td width="525"><span class="fonte">Seu ip &eacute; <?php echo $proxy_ip; ?> | <?php echo utf8_decode($versao_intranet); ?></span></td>
    <td width="195">&nbsp;</td>
  </tr>
  <tr>
    <td height="51">&nbsp;</td>
    <td valign="middle" class="titulopagina"><?php echo $intranet; ?> </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="84">&nbsp;</td>
    <td><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="416" colspan="3" valign="top">
	<table width="100%" height="378" border="0" cellpadding="0" cellspacing="0">
	  <!--DWLayoutTable-->
        <form name="form1" method="post" action="" autocomplete="off">
        <tr> 
          <td height="20" colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
          <td width="222" valign="middle"><div align="center" class="Verdade12vermelhonegrito"></div></td>
          <td width="81">&nbsp;</td>
          <td width="46">&nbsp;</td>
        </tr>
        <tr> 
          <td width="48" height="342"></td>
          <td colspan="3" valign="top"><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                  <!--DWLayoutTable-->
                  <tr>
                    <td width="268" height="33">&nbsp;</td>
                    <td width="23">&nbsp;</td>
                    <td width="343">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20" valign="middle"><div align="center"><span class="font10negrito"><strong>LOGIN ATUAL </strong></span></div></td>
                    <td>&nbsp;</td>
                    <td rowspan="5" valign="middle"><div align="center" class="Verdade12vermelhonegrito">Digite uma senha.</div></td>
                  </tr>
                  <tr>
                    <td height="43" valign="middle">
                      <div align="center">
                        <input name="login" type="text" class="campo" id="login" value="<?php echo $row_busca['login']; ?>" style="height:30px; width:250px; font-size:20px; text-align:center">
                      </div></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20" valign="middle"><div align="center"><span class="font10negrito"><strong>NOVA SENHA </strong></span></div></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="48" valign="middle">
                      <div align="center">
                        <input name="senha" type="password" class="campo" id="senha"  style="height:30px; width:250px; font-size:20px; text-align:center">
                      </div></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td rowspan="2" valign="middle"><div align="center">
   
                          <input name="Submit" type="submit" class="b-salvar" id="submit" value="Salvar">
                          </div></td>
                    <td height="81">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="21">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="0"></td>
                    <td></td>
                    <td></td>
                  </tr>
              </table></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="18"></td>
          <td width="364"></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        </form>
    </table></td>
  </tr>
</table>

</center>
</body>
</html>
<?php
mysql_free_result($busca);
?>
