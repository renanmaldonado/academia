<?php 
ini_set('session.save_path', 'tmp');
session_start();
unset($_SESSION['id']);
unset($_SESSION['ip']);
unset($_SESSION['admin']);
?>
<?php
$data = date("Y-m-d H:i:s");
require_once('Connections/conecta.php');


$ip_sources = array( "HTTP_X_FORWARDED_FOR", "HTTP_X_FORWARDED", "HTTP_FORWARDED_FOR", "HTTP_FORWARDED", "HTTP_X_COMING_FROM", "HTTP_COMING_FROM", "REMOTE_ADDR", );
foreach ($ip_sources as $ip_source){ 
	if (isset($_SERVER[$ip_source]))
	{ 
		$proxy_ip = $_SERVER[$ip_source]; break; 
	} 
}
$proxy_ip = (isset($proxy_ip)) ? $proxy_ip : @getenv("REMOTE_ADDR"); 

$nome = $_ENV['COMPUTERNAME'];
$sys = $_SERVER['HTTP_USER_AGENT'];
$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$dados = $nome . " - " . $sys . " - " . $host; 
?>
<?php
if(isset($_POST['submit']))
{ 
 		if ((!isset($_POST['login'])) || (!isset($_POST['senha'])))
		{ 
        	echo $mensg = "<script>alert('O login e senha precisam ser preenchidos!')</script>";   
    	}
		else
		{   
			$usuario_seguro = addslashes($_POST['login']);
	  		$senha_segura = addslashes($_POST['senha']);

				//Criptografia 512 bits
				$pass = hash('sha512', $senha_segura);
				//$pass = $senha_segura;
				
				/* Verifica se existe usuários com aquela senha digitada!! */ 
				mysql_select_db($database_conecta, $conecta);
				$query_logon = "SELECT u.* 
								FROM usuario u 
								WHERE u.login = '$usuario_seguro' 
								AND u.senha ='$pass' 
								AND u.intranet = 'S' 
								AND u.status = 'S'";
				$logon = mysql_query($query_logon, $conecta) or die(mysql_error());
				$row_logon = mysql_fetch_assoc($logon);
				$totalRows_logon = mysql_num_rows($logon);
				        
				/* Conta o numero de usuários com aquela senha */ 
 				$id = $row_logon['cod_usuario'];
				$acesso = $row_logon['atualizado'];
				$ip = $proxy_ip;
				$admin = $row_logon['admin'];
		             			
				if($totalRows_logon == 1)
				{
						if($acesso == "N")
						{ 
							echo $mensg = "<script>alert('Seu usuário esta inativo!')</script>";
							echo $mensg = "<script>window.location.href='login.php'</script>"; 
							exit;
						}
						else
						{ 
							
							$_SESSION['id'] = $id;
							$_SESSION['ip'] = $ip;
							
							echo $mensg = "<script>alert('Seja bem vindo ao painel de controle')</script>";
							echo $mensg = "<script>window.location.href='index.php'</script>";
							exit;
						}
				}
				elseif($totalRows_logon >= 1)
				{
						echo $mensg = "<script>alert('Este usuário esta duplicado!')</script>";
						echo $mensg = "<script>window.location.href='login.php'</script>"; 
						exit;						
				}
				else
				{
					
						echo $mensg = "<script>alert('Usuário não encontrado!')</script>";
						echo $mensg = "<script>window.location.href='login.php'</script>"; 
						exit;
				}

		}
		//Fim do else (validação dos campos)
}
//Validação do submit
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
    <td width="525">&nbsp;</td>
    <td width="195" align="center" valign="middle"><!--DWLayoutEmptyCell-->&nbsp;</td>
  </tr>
  <tr>
    <td height="51">&nbsp;</td>
    <td valign="middle" class="titulopagina"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="84">&nbsp;</td>
    <td>&nbsp;</td>
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
          <td colspan="3" valign="top"><table width="664" border="0">
            <tr>
              <td width="298" rowspan="9" align="center">&nbsp;</td>
              <td width="339">&nbsp;</td>
              <td width="13" rowspan="9">&nbsp;</td>
            </tr>
            <tr>
              <td><div align="center"><span class="font10negrito"><strong>LOGIN</strong></span></div></td>
            </tr>
            <tr>
              <td><div align="center">
                <input name="login" type="text" class="campo" id="login" value="" style="height:24px; width:250px; font-size:20px; text-align:center">
                </div></td>
            </tr>
            <tr>
              <td><div align="center"><span class="font10negrito"><strong>SENHA </strong></span></div></td>
            </tr>
            <tr>
              <td height="53"><div align="center">
                <input name="senha" type="password" class="campo" id="senha" style="height:24px; width:250px; font-size:20px; text-align:center">
                </div></td>
            </tr>
            <tr>
              <td><div align="center">
                
                <input name="submit" type="submit" class="b-salvar" id="submit" value="Entrar" style="width:128px;">
                <input type="reset" name="btnClear" class="b-cancelar" value="Limpar" style="width:128px;">
                <br><br>
                <input name="Button" type="button" class="b-liberar" value="Lembrar senha" onClick="window.open('senha.php','page','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=700,height=200');" >
              </div></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              </tr>
            <tr>
              <td>&nbsp;</td>
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