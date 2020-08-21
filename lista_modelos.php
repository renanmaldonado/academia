<?php 
require_once("../Connections/config.php");
require_once('../Connections/conecta.php');
require_once("../config.php");
require_once('sessao.php'); 
require_once('../funcoes/formata_data.php'); 


//classes de configuração do sistema
require_once("classes/sistema.php");

$fvar2 = 11;
require_once('verifica.php'); 


$modulo = 'Modelos de página';
$_SESSION['pag'] = "lista_paginas.php";

//paginas do modulo
$listar = "lista_paginas.php";
$cria = "cad_banner.php";
$deleta = "exclui_banner.php";
$edita = "lista_blocos.php";
$visualiza = "visualiza_banner.php";

?>
<?php
mysql_select_db($database_conecta, $conecta);
$query_lista = "SELECT *
				FROM modelo_pagina
				WHERE cod_pagina = '$cod'
				ORDER BY desc_pagina ASC";
$lista = mysql_query($query_lista, $conecta) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <!-- THIS IS DOWNLOADED FROM WWW.SXRIPTGATES.COM - SO THIS IS YOUR NEW SITE FOR DOWNLOAD SCRIPT ;) -->
    <title><?php echo TITULO; ?></title>
    <meta name="author" content="SuggeElson" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="application-name" content="Supr admin template" />
    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

     <!-- Le styles -->
    <!-- Use new way for google web fonts 
    http://www.smashingmagazine.com/2012/07/11/avoiding-faux-weights-styles-google-web-fonts -->
    <!-- Headings -->
    <!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css' />  -->
    <!-- Text -->
    <!-- <link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css' /> --> 
    <!--[if lt IE 9]>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400" rel="stylesheet" type="text/css" />
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet" type="text/css" />
    <link href="http://fonts.googleapis.com/css?family=Droid+Sans:400" rel="stylesheet" type="text/css" />
    <link href="http://fonts.googleapis.com/css?family=Droid+Sans:700" rel="stylesheet" type="text/css" />

    <![endif]-->
    
    <link href="<?php echo INTRANET ?>/css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo INTRANET ?>/css/bootstrap/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo INTRANET ?>/css/supr-theme/jquery.ui.supr.css" rel="stylesheet" type="text/css" />
    <link href="css/icons.css" rel="stylesheet" type="text/css" />
    <!-- Plugin stylesheets -->
    <link href="<?php echo INTRANET ?>/plugins/qtip/jquery.qtip.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo INTRANET ?>/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo INTRANET ?>/plugins/jpages/jPages.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo INTRANET ?>/plugins/prettify/prettify.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo INTRANET ?>/plugins/inputlimiter/jquery.inputlimiter.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo INTRANET ?>/plugins/ibutton/jquery.ibutton.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo INTRANET ?>/plugins/uniform/uniform.default.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo INTRANET ?>/plugins/color-picker/color-picker.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo INTRANET ?>/plugins/select/select2.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo INTRANET ?>/plugins/validate/validate.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo INTRANET ?>/plugins/pnotify/jquery.pnotify.default.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo INTRANET ?>/plugins/pretty-photo/prettyPhoto.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo INTRANET ?>/plugins/smartWizzard/smart_wizard.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo INTRANET ?>/plugins/dataTables/jquery.dataTables.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo INTRANET ?>/plugins/elfinder/elfinder.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo INTRANET ?>/plugins/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" rel="stylesheet" />

    <!-- Main stylesheets -->
    <link href="<?php echo INTRANET ?>/css/main.css" rel="stylesheet" type="text/css" /> 

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/apple-touch-icon-144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon-57-precomposed.png" />
    <style type="text/css">
    body,td,th {
	font-family: "Droid Sans", Helvetica, Arial, sans-serif;
	}
	#qLoverlay {
		background: none !important;	
	}

    </style>
    
    
    
    <script type="text/javascript">
        //adding load class to body and hide page
        document.documentElement.className += 'loadstate';
    </script>
    <script>
    	function Direcionar(url){
			location.href=url;
		}
    </script>
   
    </head>

	<body>
    <!-- loading animation -->
    <div id="qLoverlay"></div>
    <div id="qLbar"></div>
	<div class="row-fluid">
    	<div class="span12" style="padding: 0 25px">
            <div class="heading">

              <h3><?php echo $modulo; ?></h3>                    

                <div class="resBtnSearch">
                    <a href="#"><span class="icon16 brocco-icon-search"></span></a>
                </div>

                <div class="search">

                    <form id="searchform" action="#" />
                        <input type="text" class="top-search" placeholder="Procurar..." />
                        <input type="submit" class="search-btn" value="" />
                    </form>
            
                </div><!-- End search -->
                
                <ul class="breadcrumb">
                    <li>Voc&ecirc; est&aacute; aqui:</li>
                    <li>
                    <a class="tip" href="<?php echo $listar; ?>" oldtitle="Voltar para Categorias" title="" aria-describedby="ui-tooltip-0">
                    	<span class="icon16 icomoon-icon-screen"></span>
                    </a>
                        <span class="divider">
                            <span class="icon16 icomoon-icon-arrow-right"></span>
                        </span>
                    </li>
                    <li class="active">Tamanho do banner</li>
                </ul>

            </div>
    	</div>
    </div>
	
    <div id="wrapper" style="padding: 0 25px;">
	<div class="row-fluid">
    <div class="span12">
    
        <div class="box">
    
            <div class="title">
    
                <h4>
                  <span class="icon16 entypo-icon-upload-2"></span>
                    <span>P&aacute;ginas</span>
                </h4>
                <a href="#" class="minimize">Minimize</a>
            </div>
            
          <div class="content">
            
            
            <div style="height: 40px; position: relative">	
                <div align="right" style="position: absolute; top: 0; right: 0; z-index: 9999">						
				
                </div>
            </div>
            
            <div>
               
               <table cellpadding="0" cellspacing="0" border="0" class="dynamicTable display table table-bordered">
                <thead>
                    <tr>
                        <th width="53%">Nome da p&aacute;gina</th>
                        <th width="9%">Gerenciar blocos</th>
                        <?php 
						if($alteracao == 'S'){
						?>
                        <?php 
						}
						?>
                        <?php 
						if($exclusao == 'S'){
						?>
                        <?php 
						}
						?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    do{
						$cod = $row_lista['cod_pagina'];
                    ?>
                    <tr>
                        <td><?php echo $row_lista['desc_pagina']; ?></td>
                        <td><a href="javascript:void(0)" onclick="Direcionar('<?php echo $edita; ?>?cod=<?php echo $cod; ?>')"><img src="images/alterar.png"></a></td>
                        <?php 
						if($alteracao == 'S'){
						?>
                        <?php 
						}
						?>
                        <?php 
						if($exclusao == 'S'){
						?>
                        <?php 
						}
						?>
                    </tr>
                    <?php 
                    }while($row_lista = mysql_fetch_assoc($lista));
                    ?>
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th>Nome da p&aacute;gina</th>
                        <th align="center">Gerenciar blocos</th>
                        <?php 
						if($alteracao == 'S'){
						?>
                        <?php 
						}
						?>
                        <?php 
						if($exclusao == 'S'){
						?>
                        <?php 
						}
						?>
                    </tr>
                </tfoot>
            </table>
            </div>
          </div>
    
        </div><!-- End .box -->
    </div>
</div>
</div>
<!-- Le javascript
    ================================================== -->
    
	    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>  
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/jquery.mousewheel.js"></script>
    <!-- THIS IS DOWNLOADED FROM WWW.SXRIPTGATES.COM - SO THIS IS YOUR NEW SITE FOR DOWNLOAD SCRIPT ;) -->
    <!-- Load plugins -->
    <script type="text/javascript" src="plugins/qtip/jquery.qtip.min.js"></script>
    <script type="text/javascript" src="plugins/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="plugins/flot/jquery.flot.grow.js"></script>
    <script type="text/javascript" src="plugins/flot/jquery.flot.pie.js"></script>
    <script type="text/javascript" src="plugins/flot/jquery.flot.resize.js"></script>
    <script type="text/javascript" src="plugins/flot/jquery.flot.tooltip_0.4.4.js"></script>
    <script type="text/javascript" src="plugins/flot/jquery.flot.orderBars.js"></script>

    <script type="text/javascript" src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <script type="text/javascript" src="plugins/knob/jquery.knob.js"></script>
    <script type="text/javascript" src="plugins/fullcalendar/fullcalendar.min.js"></script>
    <script type="text/javascript" src="plugins/prettify/prettify.js"></script>

    <script type="text/javascript" src="plugins/watermark/jquery.watermark.min.js"></script>
    <script type="text/javascript" src="plugins/elastic/jquery.elastic.js"></script>
    <script type="text/javascript" src="plugins/inputlimiter/jquery.inputlimiter.1.3.min.js"></script>
    <script type="text/javascript" src="plugins/maskedinput/jquery.maskedinput-1.3.min.js"></script>
    <script type="text/javascript" src="plugins/ibutton/jquery.ibutton.min.js"></script>
    <script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="plugins/stepper/ui.stepper.js"></script>
    <script type="text/javascript" src="plugins/color-picker/colorpicker.js"></script>
    <script type="text/javascript" src="plugins/timeentry/jquery.timeentry.min.js"></script>
    <script type="text/javascript" src="plugins/select/select2.min.js"></script>
    <script type="text/javascript" src="plugins/dualselect/jquery.dualListBox-1.3.min.js"></script>
    <script type="text/javascript" src="plugins/tiny_mce1/jquery.tinymce.js"></script>
    <script type="text/javascript" src="plugins/validate/jquery.validate.min.js"></script>

    <script type="text/javascript" src="plugins/animated-progress-bar/jquery.progressbar.js"></script>
    <script type="text/javascript" src="plugins/pnotify/jquery.pnotify.min.js"></script>
    <script type="text/javascript" src="plugins/lazy-load/jquery.lazyload.min.js"></script>
    <script type="text/javascript" src="plugins/jpages/jPages.min.js"></script>
    <script type="text/javascript" src="plugins/pretty-photo/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="plugins/smartWizzard/jquery.smartWizard-2.0.min.js"></script>

    <script type="text/javascript" src="plugins/ios-fix/ios-orientationchange-fix.js"></script>

    <script type="text/javascript" src="plugins/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="plugins/elfinder/elfinder.min.js"></script>
    <script type="text/javascript" src="plugins/plupload/plupload.js"></script>
    <script type="text/javascript" src="plugins/plupload/plupload.html4.js"></script>
    <script type="text/javascript" src="plugins/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>

    <!-- Init plugins -->
    <script type="text/javascript" src="js/statistic.js"></script><!-- Control graphs ( chart, pies and etc) -->

    <!-- Important Place before main.js  -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
    <script type="text/javascript" src="plugins/touch-punch/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>

	<script>
	$(function()
	{
		$('.delete').click(function()
		{
			return confirm('Tem certeza que deseja excluir esse registro?')
		});
	
	})
	</script> 

    </body>
</html>