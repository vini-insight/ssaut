<?php

session_start();
$escola = $_SESSION['usuarioId'];
include_once("../db/conexao.php");

$query = "SELECT COUNT(id) AS qtd FROM turmas WHERE escola='$escola'"; //CONTA QUANTIDADE DE TURMAS QUE ESCOLA TEM
$result_query = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result_query);
$qtd = $row['qtd'];//RETORNA E ATRIBUI QUANTIDADE DE TURMAS PRA VARIÁVEL 
if ($qtd > 0) $agendar = TRUE; // SE TIVER PELO MENOS UMA TURMA ENTÃO PERMITE AGENDAR

$query = "SELECT id, estagiario, title, descricao, vagas, start, end, color FROM eventos";// PESQUISA TODOS OS EVENTOS DISPONIVEIS NO BD
$result_query = mysqli_query($conn, $query); // RETORNA PESQUISA COM RESULTADO DE TODOS OS EVENTOS DISPONIVEIS.

?>

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset='utf-8' />
		<link href="../css/index.css" rel="stylesheet">  <!-- PARA CEU DE TELA INTEIRA NO FUNDO -->
		<title>Agenda e Eventos</title>
		<link href='../css/bootstrap.min.css' rel='stylesheet'>
		<link href='../css/fullcalendar.min.css' rel='stylesheet' />
		<link href='../css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
		<link href='../css/personalizado.css' rel='stylesheet' />
		<script src='../js/jquery.min.js'></script>
		<script src='../js/bootstrap.min.js'></script>
		<script src='../js/moment.min.js'></script>
		<script src='../js/fullcalendar.min.js'></script>
		<script src='../locale/pt-br.js'></script>
		<script>
			$(document).ready(function() {
				$('#calendar').fullCalendar({
					header: {
						left: 'prev,next today',
						center: 'title',
						right: 'month,agendaWeek,agendaDay'
					},
					defaultDate: Date(),
					navLinks: true, // can click day/week names to navigate views
					editable: true,
					eventLimit: true, // allow "more" link when too many events
					eventClick: function(event) {
						
						$('#visualizar #id').text(event.id); // SETA TEXTO ID
						$('#visualizar #id').val(event.id); // SETA VALOR ID
						$('#visualizar #estagiario').text(event.estagiario);
						$('#visualizar #estagiario').val(event.estagiario);
						$('#visualizar #title').text(event.title);
						$('#visualizar #title').val(event.title);
						$('#visualizar #descricao').text(event.descricao);
						$('#visualizar #descricao').val(event.descricao);
						$('#visualizar #vagas').text(event.vagas); //SETA TEXTO DAS VAGAS
						$('#visualizar #vagas').val(event.vagas);	//SETA QUANTIDADE DAS VAGAS // PRA CONSEGUIR ENVIAR ESSE VALOR IMPUT HIDDEN
						$('#visualizar #start').text(event.start.format('DD/MM/YYYY HH:mm:ss'));
						$('#visualizar #start').val(event.start.format('DD/MM/YYYY HH:mm:ss'));
						$('#visualizar #end').text(event.end.format('DD/MM/YYYY HH:mm:ss'));
						$('#visualizar #end').val(event.end.format('DD/MM/YYYY HH:mm:ss'));												
						$('#visualizar #color').val(event.color);

						//PRA EXIBIR OU NÃO OS BOTÕES CORRETOS.
						var vagas = $('#visualizar #vagas').val(); // RETORNA QUANTIDADE DE VAGAS
						if(vagas == 0)
						{
							//document.getElementById("zero").style.visibility = 'hidden'; //OCULTA BOTÃO
							document.getElementById("zero").disabled = true; // DESABILITA BOTÃO
						}
						else
						{
							//document.getElementById("zero").style.visibility = 'visible'; // MOSTRA BOTÃO							
							document.getElementById("zero").disabled = false; // HABILITA BOTÃO
						}
						//PRA EXIBIR OU NÃO OS BOTÕES CORRETOS.
						

						$('#visualizar').modal('show');
						/*
						$('#visualizar').modal({
						  keyboard: true
						});
						*/
						return false;

					},
					
					selectable: true,
					selectHelper: true,
					select: function(start, end){
						$('#cadastrar #start').val(moment(start).format('DD/MM/YYYY HH:mm:ss'));
						$('#cadastrar #end').val(moment(end).format('DD/MM/YYYY HH:mm:ss'));
						$('#cadastrar').modal('show');						
					},
					events: [
						<?php
							while($row_events = mysqli_fetch_array($result_query)){// ATRIBUI OS VALORES DE CADA LINHA DA PESQUISA REALIZADA COM TODOS OS EVENTOS DISPONIVEIS.
								?>
								{
								id: '<?php echo $row_events['id']; ?>',
								estagiario: '<?php echo $row_events['estagiario']; ?>',
								title: '<?php echo $row_events['title']; ?>',
								descricao: '<?php echo $row_events['descricao']; ?>',
								vagas: '<?php echo $row_events['vagas']; ?>',
								start: '<?php echo $row_events['start']; ?>',
								end: '<?php echo $row_events['end']; ?>',
								color: '<?php echo $row_events['color']; ?>',								
								},<?php
							}
						?>
					]
				});
			});
			
			//Mascara para o campo data e hora
			function DataHora(evento, objeto){
				var keypress=(window.event)?event.keyCode:evento.which;
				campo = eval (objeto);
				if (campo.value == '00/00/0000 00:00:00'){
					campo.value=""
				}
			 
				caracteres = '0123456789';
				separacao1 = '/';
				separacao2 = ' ';
				separacao3 = ':';
				conjunto1 = 2;
				conjunto2 = 5;
				conjunto3 = 10;
				conjunto4 = 13;
				conjunto5 = 16;
				if ((caracteres.search(String.fromCharCode (keypress))!=-1) && campo.value.length < (19)){
					if (campo.value.length == conjunto1 )
					campo.value = campo.value + separacao1;
					else if (campo.value.length == conjunto2)
					campo.value = campo.value + separacao1;
					else if (campo.value.length == conjunto3)
					campo.value = campo.value + separacao2;
					else if (campo.value.length == conjunto4)
					campo.value = campo.value + separacao3;
					else if (campo.value.length == conjunto5)
					campo.value = campo.value + separacao3;
				}else{
					event.returnValue = false;
				}
			}
		</script>
	</head>
	<body>
		<div class="fluid-container" id="">
    		<div id="" class="text-center" style="color:#000">
        		<div id="ceu" class="img_fundo" style="padding-bottom: 10%; padding-top: 0%">
		
					<a href="../index.php"> HOME </a><br>

					<div class="container">
						<font color="white">
							<h1 class="text-center"> Escolha um Evento para Agendar sua Visita </h1>
						</font>
						<BR>
						
						<?php
						
						if(isset($_SESSION['msg']))
						{
							echo $_SESSION['msg'];
							unset($_SESSION['msg']);
						}
						
						?>

						<div id='calendar'></div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="visualizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-center">Dados do Evento</h4>
					</div>
					<div class="modal-body">
						<div class="visualizar">
							<dl class="dl-horizontal">								
								<dt>Titulo do Evento</dt>
								<dd id="title"></dd>
								<dt>Descrição do Evento</dt>
								<dd id="descricao"></dd>
								<dt>Inicio do Evento</dt>
								<dd id="start"></dd>
								<dt>Fim do Evento</dt>
								<dd id="end"></dd>								
								<dt>Vagas</dt>
								<dd id="vagas"></dd>
							</dl>
							<button id="zero" class="btn btn-agenda-visita btn-success">AGENDAR VISITA</button>
							<button class="btn btn-lista-espera btn-primary">LISTA ESPERA</button>
							<button class="btn btn-solicitar-vagas btn-warning">SOLICITAR VAGAS</button>
						</div>
						<div class="form">
							<form class="form-horizontal" method="POST" action="proc-edit-evento-escola.php">								
								<!--								
								-->
								<?php
								//if ($agendar == TRUE) {
								if ($qtd) {	// SE DIFERENTE DE ZERO É VERDADEIRO
								?>
									<label>Escolha a Turma : </label>
									<select name="turmas" id="turmas" required="">
										<option value="">Selecione</option>
								<?php
									require_once("compara-select.php");
									//$query = "SELECT vagas FROM eventos WHERE id=$id";// PESQUISA TODOS OS EVENTOS DISPONIVEIS NO BD
									//$result_query = mysqli_query($conn, $query); // RETORNA PESQUISA COM RESULTADO DE TODOS OS EVENTOS DISPONIVEIS.
								?>
									</select><BR><BR>								
									
									<input type="hidden" class="form-control" name="id" id="id">
									<input type="hidden" class="form-control" name="vagas" id="vagas">
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button type="button" class="btn btn-cancela-turma btn-secondary">cancelar</button>
											<button type="submit" class="btn btn-ok btn-success">OK</button>											
											<!-- <button type="submit" class="btn btn-primary">Lista Espera</button>
											<button type="submit" class="btn btn-warning">Solicitar Vagas</button>
											<a href='lista-espera.php' class='btn btn-primary'>Lista Espera </a>
											<a href='mais-vagas.php' class='btn btn-warning '>Solicitar Vagas</a> -->
										</div>
									</div>								
								<?php
								}
								//else {
								if (!$qtd) {
								?>
									<div class='alert alert-warning' role='alert'>
										<H7>
											Para realizar qualquer agendamento é necessário cadastrar a Turma (com seus respectivos Alunos) que deseja levar para fazer a visita. Retorne ao menu inicial, OU, clique no botão da direita											
											<a href="form-cad-turma.php" class="btn btn-success pull-right">Cadastar Turmas e Alunos</a>
										</H7>										
									</div>									
								<?php // IDEIA COLOCAR header("Location:  AQUI E EXIBIR A MENSAGEM POR SESSION NA PAGINA DE CADASTRO DE TURMAS E ALUNOS
								}
								?>
							</form>						
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- 
		<div class="modal fade" id="confirmar-agendamento-modal" tabindex="-1" role="dialog" aria-labelledby="confirmar-agendamento-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"> Confirmar Agendamento? </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">	-->							
								<!-- <button type="submit" class="btn btn-primary">Lista Espera</button>
								<button type="submit" class="btn btn-warning">Solicitar Vagas</button>
								<a href='lista-espera.php' class='btn btn-primary'>Lista Espera </a>
								<a href='mais-vagas.php' class='btn btn-warning '>Solicitar Vagas</a> -->
							<!-- 
							</div>
						</div>
                    </div>
                    <div class="modal-footer">
                        <a href="cancela.php">
                        	<button type="button" class="btn btn-cancela-agenda btn-secondary">cancelar</button>
                        </a>
						<a href="proc-edit-evento-escola.php">
							<button type="submit" class="btn btn-confirma btn-success">CONFIRMAR</button>
						</a>
                    </div>
                </div>
            </div>
        </div>
        -->

        
		
		<script>
			$('.btn-agenda-visita').on("click", function() {
				$('.form').slideToggle();
				$('.visualizar').slideToggle();
			});
			$('.btn-cancela-turma').on("click", function() {
				$('.visualizar').slideToggle();
				$('.form').slideToggle();
			});
			$('.btn-lista-espera').on("click", function() {
				$('.form').slideToggle();
				$('.visualizar').slideToggle();
			});
			$('.btn-solicitar-vagas').on("click", function() {
				$('.form').slideToggle();
				$('.visualizar').slideToggle();
			});
		</script>

		<?php		/*
		if(isset($_SESSION['turma']))
		{
		?>
		<script>
			$(document).ready(function()
			{
				$('#confirmar-agendamento-modal').modal('show');								
			});
		</script>
		<?php
		}			*/			
		?>
	</body>
</html>