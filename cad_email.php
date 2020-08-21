<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php require_once("funcoes/verifica_cpf.php"); ?>
<?php 
//Validação do módulo
$fvar2 = 10;
require_once('verifica.php'); 
$_SESSION['cod'] = '';

//Validação da permissão
if($cadastro == "N")
{
	echo $mensg = "<script>alert('Você não tem permissão para cadastro neste módulo')</script>";
	echo $mensg = "<script>window.close();</script>";
	echo $mensg = "<script>history.back();</script>";
	exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	if($_POST['filtro'] == 1)
	{
		$c = count($_POST['para']);
		$i = 1;
		foreach($_POST['para'] as $para){
			if($i < $c)
			{
				$v = "; ";
			}
			else
			{
				$v = "";	
			}
			$contato .= $para.$v;
			$i++;
		}
	}

	$msg = str_replace('src="', 'src="http://pilatesvilamada.com.br/intranet/', $_POST['msg']);
	
  $insertSQL = sprintf("INSERT INTO emails_enviados (para, todo_dia, agendamento, titulo, msg) VALUES ('$contato', %s, %s, %s, %s)",
                       GetSQLValueString(isset($_POST['todo_dia']) ? "true" : "", "defined","'S'","'N'"),
                       GetSQLValueString(vaiparaobanco($_POST['agendamento']), "date"),
                       GetSQLValueString($_POST['titulo'], "text"),
                       GetSQLValueString($msg, "text"));

  mysql_select_db($database_conecta, $conecta);
  $Result1 = mysql_query($insertSQL, $conecta) or die(mysql_error());

  $insertGoTo = "lista_email.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<noscript>
  <meta http-equiv="Refresh" content="1; url=javascript.php">
</noscript>
<title>INTRANET</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="cache-control"   content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<link href="plugin/datatables/media/css/main.css" rel="stylesheet" type="text/css">
<link href="css/intranet.css" rel="stylesheet" type="text/css">

<script src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.numeric.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="js/jquery.maskMoney.js"></script>
<script src='plugin/fullcalendar/lib/moment.min.js'></script>
<script src='plugin/fullcalendar/fullcalendar.min.js'></script>
<script src='plugin/fullcalendar/lang/pt-br.js' charset="iso-8859-1"></script>
<script src='plugin/tinymce/tinymce.min.js'></script>
<script src='plugin/tinymce/langs/pt_BR.js'></script>
<script>
tinymce.init({
	mode : "exact",
	selector:'textarea',
	relative_urls : true, // Default value
	document_base_url: "http://pilatesvilamada.com.br/intranet/",		
	/*inline: true,*/
	plugins: ["image"],
	menubar: false,
	});
</script>
</head>

<body>
<form method="POST" name="form1" id="form1" action="<?php echo $editFormAction; ?>">
  <table width="100%" align="center" class="fundoform">
    <!--DWLayoutTable-->
    <tr valign="middle">
      <td height="34" colspan="3"><div align="center" class="titulo">CADASTRAR MENSAGEM</div></td>
    </tr>
    <tr>
      <td width="27" height="200">&nbsp;</td>
      <td width="1232" valign="top"><table width="100%" align="center" class="detalhe">
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Filtro:</td>
          <td valign="middle"><select name="filtro" id="filtro">
            <option value="1">Avulso</option>
            <option value="2">Aniversariantes</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Para:</td>
          <td valign="middle" class="para">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td width="22%" align="right" valign="middle" nowrap>T&iacute;tulo:</td>
          <td width="78%" valign="middle"><input type="text" name="titulo" id="titulo" value="" size="60"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Todos os dias:</td>
          <td valign="middle"><input name="todo_dia" type="checkbox" id="todo_dia" value="S" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Agendamento:</td>
          <td valign="middle"><input name="agendamento" type="text" class="data dt1" id="agendamento" size="10" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap>Mensagem:</td>
          <td valign="middle"><textarea name="msg" id="msg" cols="45" rows="5"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td valign="middle"><input type="submit" class="b-salvar" value="Salvar">
            <input name="cancelar" type="button" class="b-cancelar fechar" id="cancelar" value="Cancelar"></td>
        </tr>
      </table></td>
      <td width="28">&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<script type="text/javascript">
//<![CDATA[
$("#filtro").each(function() {
    var tipo = $(this).val();
	$('.para').load('para.php?cod=' + tipo);
	
	$(this).change(function(){
		var tipo = $(this).val();
		$('.para').load('para.php?cod=' + tipo);	
	});
}); 

jQuery(document).ready(function() {
	
	jQuery('.telefone').mask("(99) 9999-9999?9").ready(function(event) {
        var target, phone, element;
        target = (event.currentTarget) ? event.currentTarget : event.srcElement;
        phone = target.value.replace(/\D/g, '');
        element = $(target);
        element.unmask();
        if(phone.length > 10) {
            element.mask("(99) 99999-999?9");
        } else {
            element.mask("(99) 9999-9999?9");
        }
    });
	
	$("#form1").validate({
		rules: {
			nome_aluno: {
				required: true
			},
			email: {
				email: true
			}
		},
		messages: {
			nome_aluno: {
				required: "Digite o nome do aluno."	
			},
			email: {
				email: "Digite um e-mail válido!"
			}
		}
	});
	
});
</script>
<script type="text/javascript" language="javascript" src="js/scripts.js"></script>
</body>
</html>