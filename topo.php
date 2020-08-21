<?php require_once('sessao_frame.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php
$var1 = $_SESSION['club'];
$colname_usuario = "-1";
if (isset($_SESSION['id'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['id'] : addslashes($_SESSION['id']);
}
mysql_select_db($database_conecta, $conecta);
$query_usuario = sprintf("SELECT cod_usuario f, f.nome
						  FROM usuario f
						  WHERE f.cod_usuario = %s 
						  AND f.intranet = 'S' 
						  AND f.status = 'S'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conecta) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

?>
<style type="text/css">
<!--
body {
	background: url("extras/bg_dark.jpg") repeat fixed center top;
	background-color: #F6F6F6;
	background-size:cover;
}
-->
</style>
<link href="css/intranet.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="js/script.js" type="text/javascript"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<body leftmargin="0" topmargin="0" rightmargin="0">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <!--DWLayoutTable-->
  <tr>
    <td width="85%" rowspan="2" align="left" valign="middle"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="16" rowspan="2" valign="middle"><div align="center">
	
          </div></td>
      <td width="68" valign="middle"></td>
        <td width="145"  valign="middle"></td>
        <td width="145"  valign="middle"></td>
        <td width="527"  valign="middle"></td>
        </tr>
        <tr>
          <td colspan="4" valign="middle"><table width="98%" border="0" align="left" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr valign="middle">
              <td  height="22" align="left" nowrap><span class="titulopaginamaior"><?php echo $_SESSION['id']; ?> | <?php echo upper($row_usuario['nome']); ?></span></td>
              </tr>
            <tr valign="middle">
              <td height="29" align="left" nowrap><span class="fonte12branca"><?php echo upper($row_usuario['nome_funcao']); ?></span><span class="font10clara"><br>
                ..................................................</span></td>
              </tr>
            <tr valign="middle">
              <td height="34" align="left" nowrap></td>
              </tr>
          </table></td>
        </tr>
    </table></td>
    <td width="6%"></td>
    <td width="8%"  height="1"></td>
    <td width="1%"></td>
  </tr>
  <tr>
    <td align="center" valign="middle"><a href="inicio.php" target="mainFrame"><img src="extras/home.png" width="150" height="100"></a></td>
    <td align="center" valign="middle"><a href="login.php" target="_top"><img src="extras/logout.png" width="150" height="100"></a></td>
    <td align="center" valign="middle"><!--DWLayoutEmptyCell-->&nbsp;</td>
  </tr>
  <tr>
    <td height="15"></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
</body>
<?php
mysql_free_result($usuario);
?>
