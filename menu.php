<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php
$func = $_SESSION['id'];

mysql_select_db($database_conecta, $conecta);
$query_menu = "SELECT * FROM modulos_pai WHERE cod_modulo_pai IN(SELECT cod_modulo_pai FROM modulos
																 WHERE cod_modulo IN (SELECT cod_modulo FROM modulos_usuario WHERE cod_usuario = '$func')) 
			   ORDER BY nome_modulo_pai ASC";
$menu = mysql_query($query_menu, $conecta) or die(mysql_error());
$row_menu = mysql_fetch_assoc($menu);
$totalRows_menu = mysql_num_rows($menu);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>MENU</title>
<script src="js/jquery.js"></script>
<script type="text/javascript">
        $(document).ready(function(){
            $(".conteudoMenu:not(:last)").hide();
            $(".itemMenu").click(function(){
                $(this).next(".conteudoMenu").slideToggle(300);
            });
        })
</script> 
<script src="js/scripts.js"></script> 
<style type="text/css">
    <!--
    body, td, th {
        font-family: Verdana, Geneva, sans-serif;
        font-size: 12px;
		background-color: #333333;
    }
    ul#menuGeral {
        margin:0px;
        padding:0px;
        font-family:Verdana, Arial, Helvetica, sans-serif;
        font-size:12px;
        list-style:none;
    }
    ul#menuGeral li {
    }
    /*-Para todos os atributos 'a', aqui será a marcação da sanfona-*/
    ul#menuGeral a {
        display:block;
        padding:3px;
        cursor:pointer;
        color:#CCC;
        text-decoration:none;
		height:30px;
		border-bottom: 1px solid #1A1A1A;
    	border-top: 1px solid #4D4D4D;
    }
    /*-Para todos os atributos 'a' dentro de uma 'li'-*/
    ul#menuGeral li a {
        padding: 10px 10px 0px 38px;
        background-color:#333333;
        margin-bottom:0px;
		font-weight:bold;
		padding-top:13px;
    }
    ul#menuGeral li a:hover {
        background-color:#3A3A3A;
		border-bottom: 1px solid #1A1A1A;
    	border-top: 1px solid #4D4D4D;
    }
    /*-O conteudo que fica debaixo da opção da sanfona-*/
    ul#menuGeral .conteudoMenu {
        padding:5px;
        background-color:#333333;
    }
    ul#menuGeral .conteudoMenu a {
        background-color:#333333;
        padding-left:12px;
        margin-bottom:0px;
        border:0px;
		font-weight:normal;
		color:#FFF;

    }
    ul#menuGeral .conteudoMenu a:hover {
        background-color:#3B3B3B;
        padding-left:12px;
        margin-bottom:0px;
        border:0px;
		font-weight:normal;
		color:#FF0;
	}
	.estilo1
	{
		background-image: url(css/icones/mailopened32.png);
		background-repeat: no-repeat;
		background-position: left;
	}
    -->
    </style>
</head>
<body>
<ul id="menuGeral">
<?php do 
	{ 
	$pai = $row_menu['cod_modulo_pai'];
	mysql_select_db($database_conecta, $conecta);
	$query_links = "SELECT * FROM modulos WHERE cod_modulo_pai = '$pai' AND cod_modulo IN (SELECT cod_modulo FROM modulos_usuario WHERE cod_usuario = '$func') ORDER BY  nome_modulo ASC";
	$links = mysql_query($query_links, $conecta) or die(mysql_error());
	$row_links = mysql_fetch_assoc($links);
	$totalRows_links = mysql_num_rows($links);
			
	?>
  	<li><a href="#" class="itemMenu" style="background-image: url(css/icones/<?php echo $row_menu['code']; ?>); background-repeat: no-repeat; background-position: left;"><?php echo $row_menu['nome_modulo_pai']; ?></a>
      <div class="conteudoMenu">
      	
        <?php
        do{
		?>
        <a href="<?php echo $row_links['code']; ?>" target="mainFrame"><?php echo $row_links['nome_modulo']; ?></a>
        <?php 
		} while ($row_links = mysql_fetch_assoc($links));
		?>
      </div>
    </li>
    <?php } while ($row_menu = mysql_fetch_assoc($menu)); ?>
    
    
    <li><a href="#" class="itemMenu" style="background-image: url(css/icones/lightbulb32.png); background-repeat: no-repeat; background-position: left;">EXTRAS</a>
    <div class="conteudoMenu">  
    
      <?php
			if(isset($_SESSION['master']))
			{
			?>
            <a href="return_login.php" target="_top">Meu login</a>
			<?php 
			}
			?>
            <a href="atualiza_senha.php" onClick="NewWindow(this.href,'name','600','450','no');return false;">Alterar minha senha</a><a href="inicio.php?v=S" target="mainFrame">Página inicial</a>
            <a href="login.php" target="mainFrame">Sair do sistema</a>
    
    </div>
    </li>

</ul>
</body>
</html>