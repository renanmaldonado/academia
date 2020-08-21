<?php require_once('Connections/conecta.php'); ?>
<?php require_once('servidor.php'); ?>
<?php
if ( isset($_POST['email__']) ) {

	function verificaDado($dado) {
		$dado = strip_tags($dado);
		$dado = trim($dado);
		$dado = get_magic_quotes_gpc() == 0 ? addslashes($dado) : $dado;
		$dado = preg_replace("@(--|\#|\*|;)@s", "", $dado);
		return $dado;
	}
	
	$email = verificaDado($_POST['email__']);
	
	mysql_select_db($database_conecta, $conecta);
	$rsDadosUsuario = mysql_query("SELECT nome, login, senha FROM usuario WHERE email = '$email' AND (senha <> '' OR senha is not null)");
	$row_rsDadosUsuario = mysql_fetch_assoc($rsDadosUsuario);
	$totalRows_rsDadosUsuario = mysql_num_rows($rsDadosUsuario);
	
	if($totalRows_rsDadosUsuario == 1)
	{
		require_once('config/smtp_rdorval.php');
		
		$mail->FromName = 'Suporte RDORVAL';
		$mail->AddAddress($email, "Lembrete de senha - " . $portal);
		$mail->Subject  = "Suporte RDORVAL - " . $portal; // Assunto da mensagem
		$msg  = "Olá " . $row_rsDadosUsuario['nome'] . "!<br><br>
				 Você perdeu sua senha e precisa redefini-la clicando (ou acessando) este link: <a href='" . $servidor . "/intranet/recuperar.php?s=" .
				 $row_rsDadosUsuario['senha'] . "&email=" . $email . "'>CLIQUE AQUI</a>
				 <br><br>Qualquer dúvida, entre em contato com a sua Associação<br><br>
				 RDORVAL SOLUÇÕES EM TECNOLOGIA";

		$mail->Body = $msg;	
		$enviado = $mail->Send();
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();
		
		//Exibe uma mensagem de resultado 
		if($enviado)
		{
			echo $mensg = "<script>alert('E-mail enviado com sucesso')</script>";
			echo $mensg = "<script>window.close();</script>";  
			exit;

		}
		else
		{
			echo $mensg = "<script>alert('" . $mail->ErrorInfo . "')</script>";
			echo $mensg = "<script>window.close();</script>"; 
			exit;
		}		
	}
	elseif($totalRows_rsDadosUsuario > 1)
	{
		echo $mensg = "<script>alert('Mais de um usuário possui este e-mail cadastrado!')</script>";
		echo $mensg = "<script>window.close();</script>"; 
		exit;
	}
	else
	{
		echo $mensg = "<script>alert('Este e-mail não esta cadastrado')</script>";
		echo $mensg = "<script>window.close();</script>"; 
		exit;
	}
	
} // if->request->email__
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>LEMBRETE DE SENHA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="js/AC_RunActiveContent.js" type="text/javascript"></script>
<link href="css/intranet.css" rel="stylesheet" type="text/css">

<style type="text/css">
<!--
body {
	background-image: url(extras/bg-login.jpg);
}
-->
</style></head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table width="780" height="109" border="0" align="center" cellpadding="0" cellspacing="0" id="Table_01">
  <!--DWLayoutTable-->
	<tr>
		<td width="7020" height="109" colspan="9" valign="top">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" background="images/interna_14.jpg">
		  <tr>
		    <td width="25" height="46">&nbsp;</td>
		    <td width="725" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
		    <td width="30">&nbsp;</td>
	      </tr>
		  <tr>
		    <td height="48"><div align="center"></div></td>
		    <td valign="middle"><div align="center">
			<fieldset>
<legend class="titulo">
    <label for="email__" class="fonte12simples">Informe seu e-mail:</label>
    <input name="email__" type="text" class="campo" id="email__" size="35" />
    <input type="submit" class="b-salvar" id="sbtLembreteSenha" value="Enviar" />
    </legend>

</fieldset>

			</div></td>
		    <td>&nbsp;</td>
	      </tr>
		  <tr>
		    <td height="19">&nbsp;</td>
		    <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
		    <td>&nbsp;</td>
	      </tr>
		  <tr>
		    <td height="46">&nbsp;</td>
		    <td valign="middle"><div align="center" class="font10vermelha">
		      <div align="left">SER&Aacute; DISPARADO UM E-MAIL PARA VOC&Ecirc; COM O SEU LOGIN E SENHA...</div>
		    </div></td>
		    <td>&nbsp;</td>
	      </tr>
	  </table>	  </td>
    </tr>
</table>
</form></body>
</html>