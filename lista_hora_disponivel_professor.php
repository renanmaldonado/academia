<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php 
$fvar2 = 4;
require_once('verifica.php'); 
?>
<?php 
header('Content-Type: text/html; charset=iso-8859-1');

unset($_SESSION['sql']); 

$ex_dia = explode(';',$_GET['dia']); 
$var = vaiparaobanco($ex_dia[0]);
$var1 = $_GET['cod_aula'];
$var3 = $_GET['cod_professor'];

if($var1 <> "")
{
	$var1 = "AND ap.cod_aula = '$var1'";
}
else
{
	$var1 = "";	
}

mysql_select_db($database_conecta);
$query_lista = "SELECT ap.*, a.desc_aula, p.nome_professor
				FROM aluno_pacote ap, aula a, professor p
				WHERE ap.cod_aula = a.cod_aula
				AND ap.cod_professor = p.cod_professor
				AND ap.dt_aula = '$var'
				$var1
				AND ap.cod_professor = '$var3'
				AND ap.status = 'S'";				
$lista = mysql_query($query_lista) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);


?>

<div id="calendar" style="height: 100%; margin: 10px 0; overflow-x: hidden; overflow-y: hidden;" ></div>
<script>
$('#calendar').fullCalendar({
	header: {
		left: '',
		center: '',
		right: ''
	},

	defaultDate: '<?php echo $ex_dia[0]; ?>',
	height: 'auto',
	defaultView: 'basicDay', //basicWeek, agendaDay
	editable: false,
	lang: 'pt-br',
	eventLimit: true, // allow "more" link when too many events
	events: [
		<?php
		if($totalRows_lista > 0)
		{
			do{
		?>
			{
				id: '<?php echo $row_lista['hr_inicio']; ?>',
				aluno: '<?php echo $row_lista['cod_aluno']; ?>',
				title: '<?php echo $row_lista['desc_aula']; ?>',
				start: '<?php echo $ex_dia[0]; ?>T<?php echo $row_lista['hr_inicio']; ?>',
				end: '<?php echo $ex_dia[0]; ?>T<?php echo $row_lista['hr_fim']; ?>',
				url: 'http://google.com/'
				<?php
				if($row_lista['cod_aluno'] == $var2)
				{
					echo ", color: 'green'";	
				}
				else
				{
					echo "";
				}	
				?>
			}
		<?php 
				echo ($i == $totalRows_lista)? "" : ",";
			}while($row_lista = mysql_fetch_assoc($lista));
		}
		?>
	],
	eventAfterRender:function( event, element, view ) { 
		var string = event.id;
		var str = "_" + string.substring(0,(string.length - 3));
		var aluno = "_" + event.aluno;
		var edent = str.replace(':','');
		$(element).attr("id","event_id" + edent);
	}
});


</script>