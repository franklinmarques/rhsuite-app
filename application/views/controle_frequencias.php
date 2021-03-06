<?php require_once 'header.php'; ?>

<style>
	#table_processing,
	#table_totalizacao_processing,
	#table_apontamento_consolidado_processing,
	#table_totalizacao_consolidada_processing {
		position: fixed;
		font-weight: bold;
		left: 56%;
		color: #c9302c;
		font-size: 16px;
	}

	.btn-success {
		background-color: #5cb85c;
		border-color: #4cae4c;
		color: #fff;
	}

	.btn-primary {
		background-color: #337ab7 !important;
		border-color: #2e6da4 !important;
		color: #fff;
	}

	.btn-info {
		color: #fff;
		background-color: #5bc0de;
		border-color: #46b8da;
	}

	.btn-warning {
		color: #fff;
		background-color: #f0ad4e;
		border-color: #eea236;
	}

	.btn-danger {
		color: #fff;
		background-color: #d9534f;
		border-color: #d43f3a;
	}

	.text-nowrap {
		white-space: nowrap;
	}

	.date-width {
		padding-right: 3px !important;
		padding-left: 3px !important;
		text-align: center;
		white-space: normal;
	}

	.DTFC_RightWrapper {
		display: none;
	}

	.table_apontamento > tbody > tr > td {
		background-color: #fff;
	}

	.table_apontamento > tbody > tr > td:hover {
		background-color: #e8e8e8;
	}

	.table_apontamento > tbody > tr > td.colaborador-success {
		color: #fff;
		background-color: #5cb85c !important;
	}

	.table_apontamento > tbody > tr > td.date-width-success {
		color: #5cb85c;
		font-weight: bolder;
	}

	.table_apontamento > tbody > tr > td.colaborador-success:hover {
		background-color: #47a447 !important;
	}

	.table_apontamento > tbody > tr > td.date-width-success:hover {
		color: #47a447 !important;
		font-weight: bolder;
	}

	.table_apontamento > tbody > tr > td.colaborador-primary,
	.table_apontamento > tbody > tr > td.date-width-primary {
		color: #fff;
		background-color: #027EEA !important;
		font-weight: bolder;
	}

	.table_apontamento > tbody > tr > td.colaborador-primary:hover,
	.table_apontamento > tbody > tr > td.date-width-primary:hover {
		background-color: #007EEB;
	}

	.table_apontamento > tbody > tr > td.colaborador-disabled,
	.table_apontamento > tbody > tr > td.date-width-disabled {
		color: #fff;
		background-color: #5C679A !important;
	}

	.table_apontamento > tbody > tr > td.colaborador-disabled:hover,
	.table_apontamento > tbody > tr > td.date-width-disabled:hover {
		background-color: #576192;
	}

	.table_apontamento > tbody > tr > td.date-width-warning {
		color: #f0ad4e;
		font-weight: bolder;
	}

	.table_apontamento > tbody > tr > td.date-width-warning:hover {
		color: #ed9c28 !important;
		font-weight: bolder;
	}

	.table_apontamento > tbody > tr > td.date-width-danger {
		color: #d9534f;
		font-weight: bolder;
	}

	.table_apontamento > tbody > tr > td.date-width-danger:hover {
		color: #d2322d !important;
		font-weight: bolder;
	}

	.table_apontamento > tbody > tr > td.date-width-disabled {
		color: #fff;
		background-color: #8866bb !important;
	}

	.table_apontamento > tbody > tr > td.date-width-disabled:hover {
		background-color: #7253b0 !important;
	}
</style>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">

		<!-- page start-->
		<div class="row">
			<div class="col-md-12">
				<div id="alert"></div>
				<ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
					<li class="active">Relatório de Controle de Frequência</li>
					<?php $this->load->view('modal_processos', ['url' => 'controle_frequencias']); ?>
				</ol>

				<div class="row">
					<div class="col-md-3">
						<label for="depto">Departamento</label>
						<?php echo form_dropdown('', $depto, '', 'id="depto" class="form-control input-sm filtro" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
					</div>
					<div class="col-md-3">
						<label for="area">Área</label>
						<?php echo form_dropdown('', $area, '', 'id="area" class="form-control input-sm filtro" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
					</div>
					<div class="col-md-3">
						<label for="setor">Setor</label>
						<?php echo form_dropdown('', $setor, '', 'id="setor" class="form-control input-sm filtro" onchange="reload_table();" autocomplete="off"'); ?>
					</div>
					<div class="col-md-3">
						<label for="setor">Status</label>
						<select name="status" id="status" class="form-control input-sm filtro"
								onchange="reload_table();" autocomplete="off">
							<option value="1" selected="selected">Ativo</option>
							<option value="2">Inativo</option>
							<option value="3">Em experiência</option>
							<option value="4">Em desligamento</option>
							<option value="5">Desligado</option>
							<option value="6">Afastado (maternidade)</option>
							<option value="7">Afastado (aposentadoria)</option>
							<option value="8">Afastado (doença)</option>
							<option value="9">Afastado (acidente)</option>
							<option value="10">Desistiu da vaga</option>
						</select>
					</div>
				</div>
				<br>
				<br>

				<div class="panel panel-default">
					<!-- Default panel contents -->
					<div class="panel-heading">
						<span id="mes_ano"><?= ucfirst($mes) . ' ' . date('Y') ?></span>
						<div style="float:right; margin-top: -0.5%;">
							<button id="mes_anterior" title="Mês anterior" class="btn btn-primary btn-sm"
									onclick="voltar_mes()">
								<i class="glyphicon glyphicon-arrow-left"></i> <span class="hidden-xs hidden-sm">Mês anterior</span>
							</button>
							<?php if ($this->session->userdata('nivel') != 11): ?>
								<div class="btn-group">
									<button id="btnOpcoesMes" type="button"
											class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
											aria-haspopup="true" aria-expanded="false">Opções do mês <span
											class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										<li><a href="javascript:void();" onclick="add_mes()"><i
													class="glyphicon glyphicon-import text-success"></i> Alocar
												colaboradores</a></li>
										<li><a href="javascript:void();" onclick="excluir_mes()"><i
													class="glyphicon glyphicon-erase text-danger"></i> Excluir
												mês</a></li>
										<li><a href="#" data-toggle="modal" data-target="#modal_colaborador"><i
													class="glyphicon glyphicon-plus text-info"></i> Alocar
												novo colaborador</a></li>
										<li><a href="#" data-toggle="modal"
											   data-target="#modal_colaborador_alocado"><i
													class="glyphicon glyphicon-minus text-danger"></i> Desalocar
												colaborador</a></li>
									</ul>
								</div>
							<?php endif; ?>
							<button id="mes_seguinte" title="Mês seguinte" id="mes_seguinte"
									class="btn btn-primary btn-sm" onclick="avancar_mes()">
								<span class="hidden-xs hidden-sm">Mês seguinte</span> <i
									class="glyphicon glyphicon-arrow-right"></i>
							</button>
						</div>
					</div>
					<div class="panel-body">

						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" style="font-size: 14px; font-weight: bolder" class="active"><a
									href="#apontamento" aria-controls="apontamento" role="tab"
									data-toggle="tab">Apontamento</a></li>
						</ul>

						<div class="tab-content" style="border: 1px solid #ddd; border-top-width: 0;">
							<div role="tabpanel" class="tab-pane active" id="apontamento">
								<br>
								<div class="row" style="margin: 0 2px;">
									<div class="col-sm-6 col-sm-offset-6 form-inline">
										<label>Colaborador(a)</label>
										<select name="status" id="colaboradores" class="form-control input-sm filtro"
												onchange="reload_table();" autocomplete="off" style="width:250px;">
											<option value="">Todos</option>
										</select>
										<button type="button" class="btn btn-sm btn-info"
												id="pdfRelatorioFrequencia" disabled
												onclick="imprimir_relatorio_frequencia();"><i
												class="glyphicon glyphicon-print"></i> Imprimir
										</button>
									</div>
								</div>
								<table id="table"
									   class="table table-hover table_apontamento table-condensed table-bordered"
									   cellspacing="0" width="100%">
									<thead>
									<tr>
										<th rowspan="2" class="warning" style="vertical-align: middle;">
											Colaborador(a)
										</th>
										<th rowspan="2" class="warning" style="vertical-align: middle;">
											Data
										</th>
										<th rowspan="2" class="warning" style="vertical-align: middle;">
											Hora
										</th>
										<th rowspan="2" class="warning" style="vertical-align: middle;">
											Tipo evento
										</th>
										<th colspan="5" class="warning text-center">
											Justificativa
										</th>
										<th rowspan="2" class="warning" style="vertical-align: middle;">
											Ações
										</th>
									</tr>
									<tr>
										<th class="warning">Descrição</th>
										<th class="warning">Status</th>
										<th class="warning">Data tratamento</th>
										<th class="warning">Responsável tratamento</th>
										<th class="warning">Observações</th>
									</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- page end-->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_form" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Tratamento das justificativas</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form" class="form-horizontal">
							<input type="hidden" value="" name="id"/>
							<div class="row form-group">
								<label class="control-label col-md-3" style="margin-top: -13px; font-weight: bold;">Colaborador(a):<br>Data:<br>Horário:</label>
								<div class="col-md-5" style="margin-top: -13px;">
									<label class="sr-only"></label>
									<p class="form-control-static">
										<span id="evento_nome"></span><br>
										<span id="evento_data"></span><br>
										<span id="evento_hora"></span>
									</p>
								</div>
								<div class="col-md-4 text-right">
									<button type="button" id="btnSalvarEvento" onclick="salvar_evento()"
											class="btn btn-success">
										Salvar
									</button>
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
									</button>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-3" style="margin-top: -13px; font-weight: bold;">Justificativa:</label>
								<div class="col-md-9">
									<textarea name="justificativa" class="form-control" rows="5"
											  readonly></textarea>
								</div>
							</div>
							<hr style="margin-top: 0px;">
							<div class="form-body" style="padding-top: 0;">
								<div class="row form-group">
									<label class="control-label col-md-3">Status</label>
									<div class="col-md-6">
										<label class="radio-inline">
											<input type="radio" name="aceite_justificativa" value="1"> Aceita
										</label>
										<label class="radio-inline">
											<input type="radio" name="aceite_justificativa" value="0"> Não aceita
										</label>
									</div>
								</div>
								<div class="row">
									<label class="control-label col-md-3">Observações</label>
									<div class="col-md-9">
										<textarea name="observacoes_aceite" class="form-control" rows="5"></textarea>
										<span class="help-block"></span>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->


	</section>
</section>
<!--main content end-->

<?php require_once 'end_js.php'; ?>

<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
	$(document).ready(function () {
		document.title = 'CORPORATE RH - LMS - Apontamentos de Faltas e Atrasos';
	});
</script>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

<script>

	var table;
	var mes_ano;

	$('.date').mask('00/00/0000');
	$('.hora').mask('00:00');
	$('.numero').mask('00');

	$(document).ready(function () {

		mes_ano = moment().locale('pt-br');

		table = $('#table').DataTable({
			'processing': true,
			'serverSide': true,
			'iDisplayLength': 25,
			'lengthMenu': [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
			'order': [[0, 'asc']],
			'language': {
				'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
			},
			'ajax': {
				'url': '<?php echo site_url('controleFrequencias/listar') ?>',
				'type': 'POST',
				'data': function (d) {
					d.depto = $('#depto').val();
					d.area = $('#area').val();
					d.setor = $('#setor').val();
					d.status = $('#status').val();
					d.mes = mes_ano.get('month') + 1;
					d.ano = mes_ano.get('year');
					d.colaborador = $('#colaboradores').val();

					return d;
				},
				'dataSrc': function (json) {
					$('#colaboradores').html($(json.colaboradores).html());
					$('#pdfRelatorioFrequencia').prop('disabled', $('#colaboradores').val().length === 0);
					return json.data;
				}
			},
			'columnDefs': [
				{
					'width': '20%',
					'targets': [0, 7]
				},
				{
					'width': '30%',
					'targets': [4, 8]
				},
				{
					'createdCell': function (td, cellData, rowData, row, col) {
						if (rowData[col] === 'Manual') {
							$(td).css('background-color', '#ff0');
						}
					},
					'className': 'text-center',
					'targets': [5]
				},
				{
					'className': 'text-center text-nowrap',
					'orderable': false,
					'searchable': false,
					'targets': [-1]
				}
			],
			'preDrawCallback': function () {
				$('.filtro').prop('disabled', true);
				$('#mes_ano').text(mes_ano.format('MMMM YYYY').replace(/^\w/, function (c) {
					return c.toUpperCase();
				}));
			},
			'drawCallback': function () {
				$('.filtro').prop('disabled', false);
				$('#mes_anterior, #mes_seguinte').prop('disabled', false);
			}
		});

	});


	// Ajusta a largura das colunas dos tabelas do tipo DataTables em uma aba
	$(document).on('shown.bs.tab', function () {
		$.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
	});


	function voltar_mes() {
		$('#mes_anterior, #mes_seguinte').prop('disabled', true).hover();
		mes_ano.subtract(1, 'months');
		reload_table(true);
	}


	function avancar_mes() {
		$('#mes_anterior, #mes_seguinte').prop('disabled', true).hover();
		mes_ano.add(1, 'months');
		reload_table(true);
	}


	function filtrar_estrutura() {
		$.ajax({
			'url': '<?php echo site_url('controleFrequencias/filtrar') ?>',
			'type': 'POST',
			'data': {
				'depto': $('#depto').val(),
				'area': $('#area').val(),
				'setor': $('#setor').val()
			},
			'beforeSend': function () {
				$('.filtro').prop('disabled', true);
			},
			'dataType': 'json',
			'success': function (json) {
				$('#area').html($(json.area).html());
				$('#setor').html($(json.setor).html());

				reload_table();
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error adding / update data');
			}
		});
	}


	function reload_table() {
		table.ajax.reload(null, false);
	}


	function edit_evento(id) {
		$.ajax({
			'url': '<?php echo site_url('controleFrequencias/editar') ?>',
			'type': 'POST',
			'data': {
				'id': id
			},
			'beforeSend': function () {
				$('#form')[0].reset();
				$('.filtro').prop('disabled', true);
			},
			'dataType': 'json',
			'success': function (json) {
				$('#evento_nome').text(json.nome);
				$('#evento_data').text(moment(json.data_hora, 'YYYY-MM-DD HH:II:SS').format('DD/MM/YYYY'));
				$('#evento_hora').text(moment(json.data_hora, 'YYYY-MM-DD HH:mm:ss').format('HH:mm'));

				$.each(json, function (key, value) {
					if ($('#form [name="' + key + '"]').prop('type') === 'radio') {
						$('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value !== null);
					} else {
						$('#form [name="' + key + '"]').val(value);
					}
				});

				$('#modal_form').modal('show');
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error adding / update data');
			}
		});
	}


	function salvar_evento() {
		$.ajax({
			'url': '<?php echo site_url('controleFrequencias/salvar') ?>',
			'type': 'POST',
			'data': $('#form').serialize(),
			'beforeSend': function () {
				$('#btnSalvarEvento').prop('disabled', true);
			},
			'dataType': 'json',
			'success': function (json) {
				if (json.status) {
					$('#modal_form').modal('hide');
					reload_table();
				} else if (json.erro) {
					alert(json.erro);
				}
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error adding / update data');
			},
			'complete': function () {
				$('#btnSalvarEvento').prop('disabled', false);
			}
		});
	}


	function delete_evento(id) {
		if (confirm('Deseja remover?')) {
			$.ajax({
				'url': '<?php echo site_url('controleFrequencias/excluir') ?>',
				'type': 'POST',
				'dataType': 'json',
				'data': {'id': id},
				'success': function (json) {
					if (json.status) {
						reload_table();
					} else if (json.erro) {
						alert(json.erro);
					}
				}
			});
		}
	}


	function imprimir_relatorio_frequencia() {
		var q = new Array();
		q.push("id_usuario=" + $('#colaboradores').val());
		q.push("mes=" + (mes_ano.get('month') + 1));
		q.push("ano=" + mes_ano.get('year'));

		window.open('<?php echo site_url('controleFrequencias/imprimir'); ?>/q?' + q.join('&'), '_blank');
	}

</script>

<?php require_once 'end_html.php'; ?>
