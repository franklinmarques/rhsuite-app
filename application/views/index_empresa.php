<?php require_once 'header.php'; ?>

<!--main content start-->
<section id="main-content">
	<section class="wrapper">

		<!-- page start-->
		<div id="alert"></div>
		<section class="panel">
			<header class="panel-heading">
				<?php $this->load->view('modal_processos', ['url' => 'home']); ?>
				<i class="fa fa-home"></i> Início
			</header>
			<div class="panel-body">

				<!-- <ul class="nav nav-pills">
                    <li role="presentation"><a class="alert-info" href="<?php //echo site_url('atividades'); ?>"><strong>Lista de Atividades</strong></a></li>                    
                    <li role="presentation"><a class="alert-info" href="<?php //echo site_url('manutencao'); ?>"><strong>Gestão de Reuniões</strong></a></li>                    
                    <li role="presentation"><a class="alert-info" href="#manutencao"><strong>Plano de Trabalho</strong></a></li>
                </ul> -->

				<div id="eventCalendarDefault"></div>
				<div class="row">
					<div class="col-md-4">
						<div class="profile-nav alt">
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- page end-->

		<!-- Modal -->
		<div class='modal fade' id='modalInserir' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
			 aria-hidden='true'>
			<div class='modal-dialog modal-lg'>
				<div class='modal-content'>
					<div class='modal-header'>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id='myModalLabel' style="font-weight: normal;">Inserir evento</h4>
					</div>
					<div class='modal-body'>
						<?php echo form_open('agenda/inserir', 'id="form" data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
						<div id="alert"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Data/hora</label>

							<div class="col-md-4">
								<div class="input-group date form_datetime-component">
									<input type="text" class="form-control text-center" readonly=""
										   value="<?= date('d/m/Y   H:i'); ?>" size="16" name="date_to">
									<span class="input-group-btn">
                                        <button type="button" class="btn btn-primary date-set"><i
												class="fa fa-calendar"></i></button>
                                    </span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Título</label>

							<div class="col-lg-9 controls">
								<input type="text" name="title" placeholder="Título" value=""
									   class="form-control"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Descrição</label>

							<div class="col-lg-9 controls">
								<textarea name="description" class="form-control" rows="1"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Link</label>

							<div class="col-lg-9 controls">
								<input type="text" name="link" placeholder="Link" value=""
									   class="form-control"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Departamento</label>
							<div class="col-lg-4 controls">
								<?php echo form_dropdown('depto', $deptos, $depto, 'id="depto" class="form-control filtro input-sm"'); ?>
							</div>
							<label class="col-sm-1 control-label">Área</label>
							<div class="col-lg-4 controls">
								<?php echo form_dropdown('area', $areas, $area, 'id="area" class="form-control filtro input-sm"'); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Compromisso agendado para</label>

							<div class="col-lg-9 controls">
								<?php echo form_dropdown('usuario_referenciado', $usuarios, '', 'id="usuario" class="form-control input-sm"'); ?>
							</div>
						</div>
						<?php echo form_close(); ?>
					</div>
					<div class='modal-footer'>
						<button type="submit" name="button" class="btn btn-primary" onclick="$('#form').submit();">
							Cadastrar
						</button>
						<button type='button' class='btn btn-default' data-dismiss='modal' id="fechaModal">Cancelar
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- page end-->
	</section>
</section>
<!--main content end-->

<div id="modal_scheduler" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div style="float: right;">
					<!--                        <button type="button" class="btn btn-warning" onclick="salvar_scheduler();">Não lembrar-->
					<!--                            novamente-->
					<!--                        </button>-->
					<a class="btn btn-primary" href="<?= site_url('atividades_scheduler'); ?>">Scheduler -
						Atividades</a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
				</div>
				<h4 class="modal-title"><strong>Tarefas pendentes</strong></h4>
			</div>
			<div class="modal-body">

				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active">
						<a href="#dia_mes" aria-controls="dia_mes" role="tab" data-toggle="tab">De hoje e deste
							mês</a>
					</li>
					<li role="presentation">
						<a href="#dia" aria-controls="mes" role="tab" data-toggle="tab">De todos os dias deste
							mês</a>
					</li>
					<li role="presentation">
						<a href="#mes" aria-controls="dia" role="tab" data-toggle="tab">De todos os dias de um mês
							qualquer</a>
					</li>
				</ul>

				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="dia_mes"
						 style="overflow-y: auto; max-height: 100%;">
						<?php if (isset($scheduler['atividades'])): ?>
							<?php foreach ($scheduler['atividades'] as $k => $atividades): ?>
								<?php if (!($atividades->dia == date('d') and $atividades->mes == date('m'))) {
									continue;
								} ?>
								<hr>
								<div id="scheduler_diario_<?= $atividades->id; ?>"
									 class="scheduler_item <?= $atividades->class_dia; ?> <?= $atividades->class_mes; ?>">
									<h4><strong>Atividade:</strong> <?= nl2br($atividades->atividade); ?></h4>
									<div><strong>Objetivo(s):</strong> <?= nl2br($atividades->objetivos); ?></div>
									<div class="text-right">
										<button type="button" class="btn btn-danger btn-xs"
												onclick="excluir_scheduler('<?= $atividades->id; ?>')">Excluir
										</button>
									</div>
								</div>
							<?php endforeach; ?>
							<br>
						<?php endif; ?>
					</div>
					<div role="tabpanel" class="tab-pane" id="dia" style="overflow-y: auto; max-height: 100%;">
						<?php if (isset($scheduler['atividades'])): ?>
							<?php foreach ($scheduler['atividades'] as $k => $atividades): ?>
								<?php if ($atividades->mes != date('m')) {
									continue;
								} ?>
								<hr>
								<div id="scheduler_diario_<?= $atividades->id; ?>"
									 class="scheduler_item <?= $atividades->class_dia; ?> <?= $atividades->class_mes; ?>">
									<h4><strong>Atividade:</strong> <?= nl2br($atividades->atividade); ?></h4>
									<div><strong>Objetivo(s):</strong> <?= nl2br($atividades->objetivos); ?></div>
									<div class="text-right">
										<button type="button" class="btn btn-danger btn-xs"
												onclick="excluir_scheduler('<?= $atividades->id; ?>')">Excluir
										</button>
									</div>
								</div>
							<?php endforeach; ?>
							<br>
						<?php endif; ?>
					</div>
					<div role="tabpanel" class="tab-pane" id="mes" style="overflow-y: auto; max-height: 100%;">
						<?php if (isset($scheduler['atividades'])): ?>
							<?php foreach ($scheduler['atividades'] as $k => $atividades): ?>
								<?php if (!(empty($atividades->mes) and $atividades->dia)) {
									continue;
								} ?>
								<hr>
								<div id="scheduler_mensal_<?= $atividades->id; ?>"
									 class="scheduler_item <?= $atividades->class_dia; ?> <?= $atividades->class_mes; ?>">
									<h4><strong>Atividade:</strong> <?= nl2br($atividades->atividade); ?></h4>
									<div><strong>Objetivo(s):</strong> <?= nl2br($atividades->objetivos); ?></div>
									<div class="text-right">
										<button type="button" class="btn btn-danger btn-xs"
												onclick="excluir_scheduler('<?= $atividades->id; ?>')">Excluir
										</button>
									</div>
								</div>
							<?php endforeach; ?>
							<br>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Grid CSS File (only needed for demo page) -->
<!--    <link rel="stylesheet" href="--><?php //echo base_url('assets/js/eventCalendar/css/paragridma.css'); ?><!--">-->

<!-- Core CSS File. The CSS code needed to make eventCalendar works -->
<link rel="stylesheet" href="<?= base_url('assets/js/eventCalendar/css/eventCalendar.css'); ?>">

<!-- Theme CSS file: it makes eventCalendar nicer -->
<link rel="stylesheet" href="<?= base_url('assets/js/eventCalendar/css/eventCalendar_theme_responsive.css'); ?>">

<!--clock init-->
<script src="<?= base_url('assets/js/css3clock/js/css3clock.js'); ?>"></script>

<!-- Date input -->
<link rel="stylesheet" href="<?= base_url('assets/js/bootstrap-datetimepicker/css/datetimepicker.css'); ?>"/>
<script src="<?= base_url('assets/js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js'); ?>"></script>

<script src="<?= base_url('assets/js/eventCalendar/js/moment.js'); ?>"></script>
<script src="<?= base_url('assets/js/eventCalendar/js/jquery.eventCalendar.js'); ?>"></script>
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Home';

        $("#eventCalendarDefault").eventCalendar({
            'eventsjson': '<?= base_url('agenda/verAgenda'); ?>',
            'startWeekOnMonday': false,
            'openEventInNewWindow': true,
            'showDescription': true,
            'monthNames': ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            'dayNames': ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
            'dayNamesShort': ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            'txt_noEvents': 'Não há eventos para este período',
            'txt_SpecificEvents_prev': '',
            'txt_SpecificEvents_after': ' - Eventos:',
            'txt_next': 'Próximo',
            'txt_prev': 'Anterior',
            'txt_NextEvents': 'VEJA ABAIXO SUAS ATIVIDADES PROGRAMADAS PARA SEREM EXECUTADAS!',
            'txt_GoToEventUrl': 'Ir ao evento',
            'txt_NumAbbrevTh': '',
            'txt_NumAbbrevSt': '',
            'txt_NumAbbrevNd': '',
            'txt_NumAbbrevRd': '',
            'txt_loading': 'Carregando...',
            'jsonDateFormat': 'human'// link to events json
        });

        //datetime picker start


        $(".form_datetime-component").datetimepicker({
            'format': 'dd/mm/yyyy   hh:ii',
            'minuteStep': 1,
            'autoclose': true,
            'todayBtn': true,
            'language': 'pt',
            'pickerPosition': 'bottom-left'
        });

        //datetime picker end

        if (<?= !empty($scheduler['atividades']) ? 'true' : 'false'; ?>) {
            $('#modal_scheduler').modal('show');
        }
    });

    function salvar_scheduler() {
        $('#btnSaveScheduler').prop('disabled', true);
        $.ajax({
            'url': '<?php echo site_url('home/atualizarScheduler') ?>',
            'data': {
                'dia': '<?= $scheduler['dia'] ?? '' ?>',
                'semana': '<?= $scheduler['semana'] ?? '' ?>',
                'mes': '<?= $scheduler['mes'] ?? '' ?>'
            },
            'beforeSend': function () {
                $('#modal_scheduler .btn').prop('disabled', true);
            },
            'success': function (json) {
                if (json.status) {
                    $('#modal_scheduler .btn').prop('disabled', false);
                    $('#btnSaveScheduler').prop('disabled', true);
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'complete': function () {
                $('#modal_scheduler .btn').prop('disabled', false);
            }
        });
    }

    function excluir_scheduler(group_id) {
        if (confirm('Deseja excluir a atividade?')) {
            $.ajax({
                'url': '<?php echo site_url('home/excluirScheduler') ?>',
                'data': {'id': group_id},
                'beforeSend': function () {
                    $('#btnSaveScheduler').prop('disabled', true);
                    $('#modal_scheduler .btn').prop('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#scheduler_diario_' + group_id).remove();
                        $('#scheduler_mensal_' + group_id).remove();
                        if ($('.scheduler_item').size() === 0) {
                            $('#modal_scheduler').modal('hide');
                        }
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'complete': function () {
                    $('#btnSaveScheduler').prop('disabled', false);
                    $('#modal_scheduler .btn').prop('disabled', false);
                }
            });
        }
    }


    function insereEvento() {
        $('#modalInserir').modal('show');
    }

    $('#modalInserir').on('show.bs.modal', function () {
        $('#form')[0].reset();
    });

    $('#modalInserir').on('hide.bs.modal', function () {
        if ($('#alert').html().length > 0) {
            $('#alert').html('');
        }
    });

    $('#form .filtro').on('change', function () {
        $.ajax({
            'url': '<?php echo site_url('agenda/atualizarFiltro') ?>',
            'type': 'GET',
            'data': {
                'depto': $('#depto').val(),
                'area': $('#area').val(),
                'usuario': $('#usuario').val()
            },
            'success': function (json) {
                $('#area').html($(json.area).html());
                $('#usuario').html($(json.usuario).html());
            }
        });
    });


    function finalizaEvento(id) {
        if (confirm('Tem certeza que deseja finalizar esse evento?')) {
            var aviso = '#alert-function';

            $.ajax({
                'url': '<?= base_url('agenda/finalizar'); ?>',
                'data': {'id': id},
                'beforeSend': function () {
                    $('html, body').animate({'scrollTop': 0}, 1500);
                    $(aviso).html('<div class="alert alert-info">Carregando...</div>').hide().fadeIn('slow');
                },
                'success': function (json) {
                    $('html, body').animate({'scrollTop': 0}, 1500);
                    if (parseInt(json['retorno'])) {
                        $(aviso).html('<div class="alert alert-success">' + json['aviso'] + '</div>').hide().fadeIn('slow', function () {
                            if (parseInt(json['redireciona']))
                                window.location = json['pagina'];
                        });
                    } else {
                        $(aviso).html('<div class="alert alert-danger">' + json['aviso'] + '</div>').hide().fadeIn('slow');
                    }
                },
                'error': function () {
                    $(aviso).html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                }
            });
        }
    }

    function deletaEvento(id) {
        if (confirm('Tem certeza que deseja excluir esse evento?')) {
            var aviso = '#alert-function';

            $.ajax({
                'url': '<?= base_url('agenda/excluir'); ?>',
                'data': {'id': id},
                'beforeSend': function () {
                    $('html, body').animate({'scrollTop': 0}, 1500);
                    $(aviso).html('<div class="alert alert-info">Carregando...</div>').hide().fadeIn('slow');
                },
                'success': function (json) {
                    $('html, body').animate({'scrollTop': 0}, 1500);
                    if (parseInt(json['retorno'])) {
                        $(aviso).html('<div class="alert alert-success">' + json['aviso'] + '</div>').hide().fadeIn('slow', function () {
                            if (parseInt(json['redireciona']))
                                window.location = json['pagina'];
                        });
                    } else {
                        $(aviso).html('<div class="alert alert-danger">' + json['aviso'] + '</div>').hide().fadeIn('slow');
                    }
                },
                'error': function () {
                    $(aviso).html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                }
            });
        }
    }

</script>

<?php
require_once 'end_js.php';
require_once 'end_html.php';
?>
