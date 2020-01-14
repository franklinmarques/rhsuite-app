<?php require_once APPPATH . 'views/header.php'; ?>

<style>
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

	#table_periodo > thead > tr > th {
		padding-right: 5px;
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
					<li class="active">Gestão Comercial - Gerenciar Sessões de Libras</li>
				</ol>
				<form action="#" id="busca" class="form-horizontal" autocomplete="off">
					<div class="row">
						<div class="col-md-2">
							<label>Filtrar por mês</label>
							<?php echo form_dropdown('mes', $meses, date('m'), 'onchange="reload_table();" class="form-control input-sm"'); ?>
						</div>
						<div class="col-md-2">
							<label>Filtrar por ano</label>
							<input name="ano" type="text" class="form-control text-center input-sm ano"
								   onchange="reload_table();" value="<?= date('Y'); ?>" placeholder="aaaa">
						</div>
						<div class="col-md-2">
							<label>Filtrar por dia</label>
							<?php echo form_dropdown('dia', $dias, '', 'onchange="reload_table();" class="form-control input-sm"'); ?>
						</div>
						<div class="col-md-4">
							<label>Filtrar por produto</label>
							<?php echo form_dropdown('produto', $filtro_produtos, '', 'onchange="reload_table();" class="form-control input-sm"'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<label>Filtrar por cliente</label>
							<?php echo form_dropdown('cliente', $filtro_clientes, '', 'onchange="filtrar_cliente(this);" class="form-control input-sm"'); ?>
						</div>
						<div class="col-md-2">
							<br>
							<button id="btnFechamento" class="btn btn-primary btn-sm"
									onclick="emitir_solicitacao_faturamento();" disabled>Emitir
								Sol. Faturamento
							</button>
						</div>
						<div class="col-md-4">
							<label>Filtrar por profissional</label>
							<?php echo form_dropdown('profissional', $filtro_profissionais, '', 'onchange="filtrar_profissional(this);" class="form-control input-sm"'); ?>
						</div>
						<div class="col-md-2">
							<br>
							<button id="btnPagamento" class="btn btn-primary btn-sm"
									onclick="emitir_solicitacao_pagamento();" disabled>Emitir Sol.
								Pagamento
							</button>
						</div>
					</div>
					<br>
				</form>
				<table id="table_periodo" class="table table-hover table-condensed table-bordered" cellspacing="0"
					   width="100%">
					<thead>
					<tr>
						<th rowspan="2" class="warning">Período</th>
						<td colspan="31" class="text-center date-width" id="nome_mes_ano">
							<strong>Total de eventos no mês de <?= $mes_ano; ?></strong>
						</td>
					</tr>
					<tr>
						<?php for ($i = 1; $i <= 31; $i++): ?>
							<?php if (date('N', mktime(0, 0, 0, date('m'), $i, date('Y'))) < 6): ?>
								<th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
							<?php else: ?>
								<th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
							<?php endif; ?>
						<?php endfor; ?>
					</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				<hr>
				<button id="btnAdd" type="button" class="btn btn-info" onclick="add_sessao()" autocomplete="off"><i
						class="glyphicon glyphicon-plus"></i> Nova sessão
				</button>
				<br>
				<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
					<tr>
						<th>Data</th>
						<th>Produto</th>
						<th>Cliente</th>
						<th>Horário início</th>
						<th>Qtde. horas</th>
						<th>Profissional</th>
						<th class="text-center" nowrap>Faturamento (R$)<br><span id="total_faturamento"></span></th>
						<th class="text-center" nowrap>Pagamento (R$)<br><span id="total_pagamento"></span></th>
						<th>Ações</th>
					</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<!-- page end-->

			<!-- Bootstrap modal -->
			<div class="modal fade" id="modal_form" role="dialog">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<div style="float:right;">
								<button type="button" class="btn btn-success" id="btnSave" onclick="save()">Salvar
								</button>
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
							</div>
							<h3 class="modal-title">Gerenciar produto</h3>
						</div>
						<div class="modal-body form">
							<div id="alert"></div>
							<form action="#" id="form" class="form-horizontal">
								<input type="hidden" value="" name="id"/>
								<div class="form-body">
									<div class="row form-group">
										<label class="control-label col-md-2">Cliente <span
												class="text-danger">*</span></label>
										<div class="col-md-9">
											<?php echo form_dropdown('id_cliente', $clientes, '', 'class="form-control" onchange="filtrar_contratos();"'); ?>
											<span class="help-block"></span>
										</div>
									</div>
									<div class="row form-group">
										<label class="control-label col-md-2">Produto <span
												class="text-danger">*</span></label>
										<div class="col-md-9">
											<?php echo form_dropdown('id_produto', $produtos, '', 'class="form-control" onchange="calcular_valor_produto();"'); ?>
											<span class="help-block"></span>
										</div>
									</div>
									<div class="row form-group">
										<label class="control-label col-md-2">Contrato</label>
										<div class="col-md-3">
											<?php echo form_dropdown('codigo_contrato', $contratos, '', 'class="form-control"'); ?>
											<span class="help-block"></span>
										</div>
										<label class="control-label col-md-2">Data evento <span
												class="text-danger">*</span></label>
										<div class="col-md-2">
											<input name="data_evento" class="form-control text-center date"
												   type="text" placeholder="dd/mm/aaaa">
											<span class="help-block"></span>
										</div>
									</div>
									<div class="row form-group">
										<label class="control-label col-md-2">Horário início <span
												class="text-danger">*</span></label>
										<div class="col-md-2">
											<input name="horario_inicio" class="form-control text-center hora"
												   type="text" placeholder="hh:mm">
											<span class="help-block"></span>
										</div>
										<label class="control-label col-md-2">Horário término <span
												class="text-danger">*</span></label>
										<div class="col-md-2">
											<input name="horario_termino" class="form-control text-center hora"
												   type="text" placeholder="hh:mm">
											<span class="help-block"></span>
										</div>
										<label class="control-label col-md-1 text-nowrap">Qtde. horas</label>
										<div class="col-md-2">
											<input name="qtde_horas" class="form-control qtde" type="text" readonly>
											<span class="help-block"></span>
										</div>
									</div>
									<div class="row form-group">

										<label class="control-label col-md-2">Desconto</label>
										<div class="col-md-3">
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">R$</span>
												<input name="valor_desconto" type="text" class="form-control valor"
													   aria-describedby="basic-addon1"
													   onchange="calcular_valor_produto();">
											</div>
											<span class="help-block"></span>
										</div>
										<label class="control-label col-md-3">Valor a ser faturado</label>
										<div class="col-md-3">
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">R$</span>
												<input name="valor_faturamento" type="text"
													   class="form-control valor"
													   aria-describedby="basic-addon1">
											</div>
											<span class="help-block"></span>
										</div>
									</div>
									<div class="row form-group">
										<label class="control-label col-md-2">Custo operacional</label>
										<div class="col-md-3">
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">R$</span>
												<input name="custo_operacional" type="text"
													   class="form-control valor"
													   aria-describedby="basic-addon1">
											</div>
											<span class="help-block"></span>
										</div>
										<label class="control-label col-md-1">Impostos</label>
										<div class="col-md-3">
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">R$</span>
												<input name="custo_impostos" type="text" class="form-control valor"
													   aria-describedby="basic-addon1">
											</div>
											<span class="help-block"></span>
										</div>
									</div>
									<div class="row form-group">
										<label class="control-label col-md-2">Local do evento</label>
										<div class="col-md-9">
											<textarea name="local_evento" class="form-control"></textarea>
											<span class="help-block"></span>
										</div>
									</div>
									<div class="row form-group">
										<label class="control-label col-md-2">Observações sobre o evento</label>
										<div class="col-md-9">
											<textarea name="observacoes" class="form-control"></textarea>
											<span class="help-block"></span>
										</div>
									</div>
									<fieldset>
										<legend><h4>Profissionais alocados</h4></legend>
										<div class="row form-group">
											<label class="control-label col-md-4">Depto. do prestador de serviço <span
													class="text-danger">*</span></label>
											<div class="col-md-6">
												<?php echo form_dropdown('id_depto_prestador_servico', $deptos, '', 'class="form-control" onchange="filtrar_profissionais();"'); ?>
												<span class="help-block"></span>
											</div>
										</div>
										<div class="row form-group profissional">
											<label class="control-label col-md-1">Nome</label>
											<div class="col-md-5">
												<?php echo form_dropdown('id_profissional_alocado[]', ['' => 'selecione...'], '', 'class="form-control"'); ?>
											</div>
											<label class="control-label col-md-2">Valor a ser pago</label>
											<div class="col-md-2">
												<div class="input-group">
													<span class="input-group-addon">R$</span>
													<input name="valor_pagamento_profissional[]" type="text"
														   class="form-control valor">
												</div>
											</div>
											<div class="col-sm-1 remover_profissional" style="display: none;">
												<button type="button" style="border-radius: 14px;"
														class="btn btn-warning btn-sm"
														onclick="remove_profissional(this);">Remover
												</button>
											</div>
										</div>
										<div class="row" id="adicionar_profissional">
											<div class="col-sm-2 col-sm-offset-1">
												<button type="button" style="border-radius: 14px;"
														class="btn btn-info btn-sm" onclick="add_profissional();">
													<i class="glyphicon glyphicon-plus"></i> Adicionar profissional
												</button>
											</div>
											<br>
											<br>
										</div>
									</fieldset>

								</div>
							</form>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<!-- End Bootstrap modal -->

	</section>
</section>
<!--main content end-->

<?php require_once APPPATH . 'views/end_js.php'; ?>

<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
	$(document).ready(function () {
		document.title = 'CORPORATE RH - LMS - Gestão Comercial: Gerenciar Sessões de Libras';
	});
</script>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

<script>

	var save_method;
	var table, table_periodo;

	$('.date').mask('00/00/0000');
	$('.ano').mask('0000');
	$('.qtde').mask('#0,00', {reverse: true});
	$('.valor').mask('#.###.##0,00', {reverse: true});
	$('.hora').on('change', function () {
		var horario_inicio = moment.duration($('#form [name="horario_inicio"]').val(), 'HH:mm').asSeconds();
		var horario_termino = moment.duration($('#form [name="horario_termino"]').val(), 'HH:mm').asSeconds();
		if (horario_inicio > 0 && horario_termino > 0) {
			$('#form [name="qtde_horas"]').val(parseFloat((horario_termino - horario_inicio) / 3600).toFixed(2).toString().replace('.', ','));
		} else {
			$('#form [name="qtde_horas"]').val('');
		}
		calcular_valor_produto();
	}).mask('00:00');


	$(document).ready(function () {

		table = $('#table').DataTable({
			'processing': true,
			'serverSide': true,
			'order': [],
			'iDisplayLength': 100,
			'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
			'ajax': {
				'url': '<?php echo site_url('icom/sessoes/listar') ?>',
				'type': 'POST',
				'data': function (d) {
					d.busca = $('#busca').serialize();
					return d;
				},
				'dataSrc': function (json) {
					$('#total_faturamento').html('[' + json.totalFaturamento + ']');
					$('#total_pagamento').html('[' + json.totalPagamento + ']');

					return json.data;
				}
			},
			'columnDefs': [
				{
					'width': '30%',
					'targets': [1]
				},
				{
					'className': 'text-center',
					'targets': [0, 3, 4]
				},
				{
					'createdCell': function (td, cellData, rowData, row, col) {
						if (rowData[9]) {
							$(td).css({'color': '#fff', 'background-color': '#47a447'});
						}
					},
					'width': '30%',
					'targets': [2]
				},
				{
					'createdCell': function (td, cellData, rowData, row, col) {
						if (rowData[10]) {
							$(td).css({'color': '#fff', 'background-color': '#47a447'});
						}
					},
					'width': '30%',
					'targets': [5]
				},
				{
					'className': 'text-right',
					'targets': [6, 7]
				},
				{
					'className': 'text-nowrap',
					'targets': [-1],
					'orderable': false,
					'searchable': false
				}
			]
		});

	});

	table_periodo = $('#table_periodo').DataTable({
		'processing': true,
		'serverSide': true,
		'lengthChange': false,
		'iDisplayLength': -1,
		'ordering': false,
		'searching': false,
		'info': false,
		'paging': false,
		'ajax': {
			'url': '<?php echo site_url('icom/sessoes/listarPeriodos') ?>',
			'type': 'POST',
			'data': function (d) {
				d.busca = $('#busca').serialize();
				return d;
			},
			'dataSrc': function (json) {
				$('#busca [name="dia"]').html($(json.days).html());
				$('#busca [name="cliente"]').html($(json.clientes).html());
				$('#busca [name="profissional"]').html($(json.profissionais).html());

				var dataEvento = moment(json.lastDayOfMonth, 'YYYY-MM-DD', 'pt-br');

				$('#nome_mes_ano').html('<strong>Total de eventos no mês de ' +
					dataEvento.format('MMMM YYYY').replace(' ', ' de ') + '</strong>');

				var dataAtual = moment().format('YYYY-MM-DD');
				var totalDias = dataEvento.date();
				dataEvento.date(1);

				for (i = 1; i <= 31; i++) {
					if (totalDias < i) {
						table_periodo.column(i).visible(false);
					} else {
						table_periodo.column(i).visible(true);

						var coluna = $(table_periodo.columns(i).header());
						coluna.removeClass('text-danger').css('background-color', '')
							.attr('title', dataEvento.format('dddd, DD # MMMM # YYYY').replace(/#/g, 'de'));

						if (dataEvento.day() === 6 || dataEvento.day() === 0) {
							coluna.addClass('text-danger').css('background-color', '#dbdbdb');
						}

						if (dataEvento.isSame(dataAtual)) {
							coluna.css('background-color', '#0f0');
						}

						dataEvento.add(1, 'days');
					}
				}

				return json.data;
			}
		},
		'columnDefs': [
			{
				'width': '100%',
				'targets': [0]
			},
			{
				'createdCell': function (td, cellData, rowData, row, col) {
					if (rowData[col]) {
						$(td).css({'color': '#fff', 'background-color': '#47a447'});
					} else if ($(table_periodo.column(col).header()).hasClass('text-danger')) {
						$(td).css('background-color', '#e9e9e9');
					}
				},
				'className': 'text-center',
				'targets': 'date-width'
			}
		]
	});


	function filtrar_cliente(elem) {
		$('#btnFechamento').prop('disabled', elem.value.length === 0);
		reload_table();
	}


	function filtrar_profissional(elem) {
		$('#btnPagamento').prop('disabled', elem.value.length === 0);
		reload_table();
	}


	function add_profissional() {
		var profissional = $('.profissional:last').html();

		$('<div class="row form-group profissional">' + profissional + '</div>').insertAfter('.profissional:last');
		$('.profissional:last input.valor').mask('#.###.##0,00', {reverse: true});
		$('.remover_profissional:last').show();
	}


	function remove_profissional(event) {
		$(event).parents('.profissional').remove();
	}


	function reset_profissional() {
		$('.profissional:gt(0)').remove();
		$('#form [name="id_profissional_alocado[]"]').html('<option value="">selecione...</option>');
	}


	function add_sessao() {
		save_method = 'add';
		$('#form')[0].reset();
		$('#form [name="id"]').val('');
		$('#form [name="valor_desconto"], #form [name="custo_operacional"], #form [name="custo_impostos"]').val('0,00');
		reset_profissional();
		$('#adicionar_profissional').show();
		$('#modal_form').modal('show');
		$('.modal-title').text('Adicionar sessão de atividades');
		$('.combo_nivel1').hide();
	}


	function edit_sessao(id) {
		$.ajax({
			'url': '<?php echo site_url('icom/sessoes/editar') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': {'id': id},
			'beforeSend': function () {
				save_method = 'update';
				$('#form')[0].reset();
				$('.form-group').removeClass('has-error');
				$('.help-block').empty();
			},
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
					return false;
				}

				$.each(json, function (key, value) {
					$('#form [name="' + key + '"]').val(value);
				});

				$('#form [name="codigo_contrato"]').html($(json.contratos).html());

				reset_profissional();
				$('#adicionar_profissional').hide();
				// $('#form [name="id_profissional_alocado"]').html($(json.id_profissional_alocado).html());
				/*$.each(json.profissionais, function (key, value) {
					$('#form .profissional:eq(' + key + ') [name="id_sessao_profissional[]"]').val(value.id_sessao_profissional);
					$('#form .profissional:eq(' + key + ') [name="id_profissional_alocado[]"]').html($(value.id_profissional_alocado).html());
					$('#form .profissional:eq(' + key + ') [name="valor_pagamento[]"]').val(value.valor_pagamento);
					add_profissional();
				});*/
				/*if (json.profissionais.length > 0) {
					$('.profissional:last').remove();
				}*/

				$('#form [name="id_profissional_alocado[]"]').html($(json.id_profissional_alocado).html());
				$('#form [name="valor_pagamento_profissional[]"]').val(json.valor_pagamento_profissional);

				$('#modal_form').modal('show');
				$('.modal-title').text('Editar sessão de atividades');
			}
		});
	}


	function filtrar_contratos() {
		$.ajax({
			'url': '<?php echo site_url('icom/sessoes/filtrarContratos') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': {
				'id_cliente': $('#form [name="id_cliente"]').val(),
				'codigo_contrato': $('#form [name="codigo_contrato"]').val()
			},
			'beforeSend': function () {
				$('#form [name="codigo_contrato"]').attr('disabled', true);
			},
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
				} else {
					$('#form [name="codigo_contrato"]').html($(json.contratos).html());
				}
			},
			'complete': function () {
				$('#form [name="codigo_contrato"]').attr('disabled', false);
			}
		});
	}


	function filtrar_profissionais() {
		$.ajax({
			'url': '<?php echo site_url('icom/sessoes/filtrarProfissionais') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': {
				'id_depto': $('#form [name="id_depto_prestador_servico"]').val(),
				'id_usuario': $('#form [name="id_profissional_alocado[]"]').val()
			},
			'beforeSend': function () {
				$('#form [name="id_depto_prestador_servico"], #form [name="id_profissional_alocado"]').attr('disabled', true);
			},
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
				} else {
					reset_profissional();
					$('#form [name="id_profissional_alocado[]"]').html($(json.usuarios).html());
				}
			},
			'complete': function () {
				$('#form [name="id_depto_prestador_servico"], #form [name="id_profissional_alocado"]').attr('disabled', false);
			}
		});
	}


	function calcular_valor_produto() {
		$.ajax({
			'url': '<?php echo site_url('icom/sessoes/calcularValorProduto') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': {
				'id_produto': $('#form [name="id_produto"]').val()
			},
			'beforeSend': function () {
				$('#form .hora, #form [name="valor_desconto"], #form [name="valor_faturamento"]').attr('disabled', true);
			},
			'success': function (json) {
				if (json.preco) {
					var qtde_horas = parseInt($('#form [name="qtde_horas"]').val());
					var desconto = parseFloat($('#form [name="valor_desconto"]').val().replace('.', '').replace(',', '.'));
					var valor_faturamento = 0;
					var valor_pagamento_profissional = 0;
					if (qtde_horas > 0) {
						valor_faturamento = json.preco * qtde_horas;
						valor_pagamento_profissional = json.custo * qtde_horas;
					}
					if (desconto > 0) {
						valor_faturamento -= desconto;
					}
					$('#form [name="valor_faturamento"]').val(valor_faturamento.toLocaleString('pt-BR', {'minimumFractionDigits': 2}));
					$('#form [name="valor_pagamento[]"]').val(valor_pagamento_profissional.toLocaleString('pt-BR', {'minimumFractionDigits': 2}));
				} else {
					$('#form [name="valor_faturamento"], #form [name="valor_pagamento[]"]').val('');
				}
			},
			'complete': function () {
				$('#form .hora, #form [name="valor_desconto"], #form [name="valor_faturamento"]').attr('disabled', false);
			}
		});
	}


	function reload_table() {
		table.ajax.reload(null, false);
		table_periodo.ajax.reload(null, false);
	}


	function save() {
		$.ajax({
			'url': '<?php echo site_url('icom/sessoes/salvar') ?>',
			'type': 'POST',
			'data': $('#form').serialize(),
			'dataType': 'json',
			'beforeSend': function () {
				$('#btnSave').text('Salvando...').attr('disabled', true);
			},
			'success': function (json) {
				if (json.status) {
					$('#modal_form').modal('hide');
					reload_table();
				} else if (json.erro) {
					alert(json.erro);
				}
			},
			'complete': function () {
				$('#btnSave').text('Salvar').attr('disabled', false);
			}
		});
	}


	function delete_sessao(id) {
		if (confirm('Deseja remover?')) {
			$.ajax({
				'url': '<?php echo site_url('icom/sessoes/excluir') ?>',
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


	function emitir_solicitacao_faturamento() {
		var q = new Array();
		q.push("mes=" + $('#busca [name="mes"]').val());
		q.push("ano=" + $('#busca [name="ano"]').val());
		q.push("cliente=" + $('#busca [name="cliente"]').val());

		window.open('<?php echo site_url('icom/sessoes/solicitacaoFaturamento'); ?>/q?' + q.join('&'), '_blank');
	}


	function emitir_solicitacao_pagamento() {
		var q = new Array();
		q.push("mes=" + $('#busca [name="mes"]').val());
		q.push("ano=" + $('#busca [name="ano"]').val());
		q.push("profissional=" + $('#busca [name="profissional"]').val());

		window.open('<?php echo site_url('icom/sessoes/solicitacaoPagamento'); ?>/q?' + q.join('&'), '_blank');
	}

</script>

<?php require_once APPPATH . 'views/end_html.php'; ?>
