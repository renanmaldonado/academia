<?php require_once('sessao.php'); ?>
<?php require_once('Connections/conecta.php'); ?>
<?php require_once('config.php'); ?>
<?php require_once('funcoes/formata_data.php'); ?>
<?php require_once('funcoes/formata_maiuscula.php'); ?>
<?php 
$fvar2 = 2;
require_once('verifica.php'); 
?>
<?php 
header('Content-Type: text/html; charset=iso-8859-1');

unset($_SESSION['sql']); 

$ex_dia = explode(';',$_GET['dia']); 
$var = $ex_dia[0];
$var1 = $_GET['cod_aula'];
$var2 = $_GET['cod_aluno'];

mysql_select_db($database_conecta);
$query_lista = "SELECT ap.cod_aluno, ap.dt_aula, ap.hr_inicio, ap.hr_fim, ap.status, (SELECT COUNT(ap2.cod_aluno) FROM aluno_pacote ap2 WHERE ap2.cod_aluno = '$var2' AND ap2.cod_aula = ap.cod_aula AND ap2.dt_aula = ap.dt_aula AND ap2.hr_inicio = ap.hr_inicio)AS aluno, a.desc_aula, p.nome_professor, a.max_aluno, (a.max_aluno - (SELECT COUNT(ap2.cod_aluno) FROM aluno_pacote ap2 WHERE ap2.cod_aula = a.cod_aula AND ap2.dt_aula = ap.dt_aula AND ap2.hr_inicio = ap.hr_inicio AND ap2.status = 'S'))AS vagas
				FROM (aluno_pacote ap, aula a, professor p)
				WHERE ap.cod_aula = a.cod_aula
				AND ap.cod_professor = p.cod_professor
				AND ap.status = 'S'
				GROUP BY ap.dt_aula, ap.hr_inicio";				
$lista = mysql_query($query_lista) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);


?>

<div id="calendar" style="height: 300px; margin: 10px 0; overflow-x: hidden; overflow-y: scroll;" ></div>
<script>

$('#calendar').fullCalendar({
	header: {
		left: '',//prev,next today
		center: '',
		right: ''
	},

	defaultDate: '<?php echo date('Y-m-d'); ?>',
	height: 'auto',
	defaultView: 'month', //basicWeek, agendaDay
	editable: false,
	lang: 'pt-br',
	eventLimit: true, // allow "more" link when too many events
	id: 'teste',
	events: [
			<?php
			if($totalRows_lista > 0)
			{
				do{
					mysql_select_db($database_conecta);
					$query_aulas_aluno = "SELECT ap.*
										  FROM aluno_pacote ap
										  WHERE ap.cod_aluno = '$var2'
										  AND ap.cod_aula = '".$row_lista['cod_aula']."'
										  AND ap.hr_inicio = '".$row_lista['hr_inicio']."'
										  AND ap.status = 'S'";
					$aulas_aluno = mysql_query($query_aulas_aluno) or die(mysql_error());
					$row_aulas_aluno = mysql_fetch_assoc($aulas_aluno);
					$totalRows_aulas_aluno = mysql_num_rows($aulas_aluno);
				?>
					{
						aluno: '<?php echo $row_lista['cod_aluno']; ?>',
						title: '<?php echo $row_lista['desc_aula'] . " - Prof. " . $row_lista['nome_professor']; ; echo($row_lista['max_aluno'] > 1)? " - Vagas ".$row_lista['vagas'] : "" ; ?>',
						start: '<?php echo $row_lista['dt_aula']; ?>T<?php echo $row_lista['hr_inicio']; ?>',
						end: '<?php echo $row_lista['dt_aula']; ?>T<?php echo $row_lista['dt_inicio']; ?>'

						<?php					
						if($row_lista['cod_aluno'] == $var2)
						{
							if($row_lista['max_aluno'] > 1)
							{
								if($totalRows_aulas_aluno >= 1)
								{
									echo ", color: 'red',";
									echo "textColor: '#FFF'";

								}
								else
								{
									echo ", color: 'yellow',";
									echo "textColor: '#666666'";
								}
							}
							else
							{
								echo ", color: 'green'";
							}
						}
						else
						{
							if($row_lista['aluno'] >= 1)
							{
								echo ", color: 'red',";
								echo "textColor: '#FFF'";

							}
							else
							{
								echo "";
							}
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