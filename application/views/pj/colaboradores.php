<?php require_once APPPATH . 'views/header.php'; ?>

<section id="main-content">
	<section class="wrapper">

		<div class="row">
			<div class="col-md-12">
				<div id="alert"></div>
				<ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
					<li class="active">Gerenciar PJs</li>
				</ol>
				<div class="row">
					<div class="col-sm-12 text-right">
						<a id="pdf" class="btn btn-sm btn-danger"
						   href="<?= site_url('apontamento_colaboradores/pdf/'); ?>"
						   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i>
							Exportar PDF</a>
					</div>
				</div>
				<br>
				<form id="busca">
					<div class="row">
						<div class="col-md-4">
							<label class="control-label">Filtrar por departamento</label>
							<?php echo form_dropdown('depto', $depto, $depto_atual, 'class="form-control input-sm" onchange="filtrar();"'); ?>
						</div>
						<div class="col-md-4">
							<label class="control-label">Filtrar por área/cliente</label>
							<?php echo form_dropdown('area', $area, $area_atual, 'class="form-control input-sm" onchange="filtrar();"'); ?>
						</div>
						<div class="col-md-3">
							<label class="control-label">Filtrar por setor/unidade</label>
							<?php echo form_dropdown('setor', $setor, $setor_atual, 'class="form-control input-sm" onchange="filtrar();"'); ?>
						</div>
						<div class="col-md-3">
							<label class="control-label">Filtrar por cargo</label>
							<?php echo form_dropdown('cargo', $cargo, '', 'class="form-control input-sm" onchange="filtrar();"'); ?>
						</div>
						<div class="col-md-3">
							<label class="control-label">Filtrar por função</label>
							<?php echo form_dropdown('funcao', $funcao, '', 'class="form-control input-sm" onchange="filtrar();"'); ?>
						</div>
						<div class="col-md-3 col-lg-2">
							<label class="control-label">Filtrar status vínculo</label>
							<select name="status" class="form-control input-sm" onchange="reload_table();">
								<option value="">Todos</option>
								<option value="1">Ativos</option>
								<option value="2">Inativos</option>
								<option value="3">Em experiência</option>
								<option value="4">Em desligamento</option>
								<option value="5">Desligados</option>
								<option value="6">Afastados (maternidade)</option>
								<option value="7">Afastados (aposentadoria)</option>
								<option value="8">Afastados (doença)</option>
								<option value="9">Afastados (acidente)</option>
								<option value="10">Desistiram da vaga</option>
							</select>
						</div>
						<div class="col-md-3">
							<label class="control-label">Filtrar por contrato</label>
							<?php echo form_dropdown('contrato', $contrato, '', 'class="form-control input-sm" onchange="reload_table();"'); ?>
						</div>
					</div>
				</form>
				<hr>
				<table id="table" class="table table-striped table-condensed" cellspacing="0" width="100%">
					<thead>
					<tr>
						<th>Nome</th>
						<th>Função</th>
						<th nowrap>N&ordm; contrato</th>
						<th nowrap>Data início</th>
						<th nowrap>Data término</th>
						<th>Ações</th>
					</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_contratos" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="btn btn-default" data-dismiss="modal" style="float:right;">
							Fechar
						</button>
						<h3 class="modal-title">Gerenciamento de contratos</h3>
					</div>
					<div class="modal-body form">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active">
								<a href="#contrato_visualizacao" aria-controls="contrato_visualizacao" role="tab"
								   data-toggle="tab">Visualizar</a>
							</li>
							<li role="presentation">
								<a href="#contrato_cadastro" aria-controls="contrato_cadastro" role="tab"
								   data-toggle="tab">Cadastrar</a>
							</li>
						</ul>

						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="contrato_visualizacao">
								<div class="form-body">
									<div class="row form-horizontal">
										<div class="col-md-3">
											<label class="radio-inline">
												<input type="radio" name="tipo" value="1" class="tipo"> C.V.
											</label>
											<label class="radio-inline">
												<input type="radio" name="tipo" value="2" class="tipo"> Contrato
											</label>
										</div>
										<label class="control-label col-md-3">Selecionar C.V. ou Contrato</label>
										<div class="col-md-4">
											<select id="curriculos" class="form-control">
												<option value="">selecione...</option>
											</select>
											<select id="contratos" class="form-control"
													style="display:none;">
												<option value="">selecione...</option>
											</select>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-xs-12">
											<iframe id="documento"
													src="https://docs.google.com/gview?embedded=true&url=<?= base_url('arquivos/documentos/colaborador/'); ?>"
													style="width:100%; height:600px; margin:0;"
													frameborder="0"></iframe>
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="contrato_cadastro">
								<div id="alert"></div>
								<form action="#" id="form_contrato" class="form-horizontal">
									<input type="hidden" value="" name="id"/>
									<input type="hidden" value="" name="id_usuario"/>
									<div class="form-body">
										<div class="row">
											<div class="col-md-12 text-right">
												<button type="button" class="btn btn-danger" id="btnDeleteContrato"
														onclick="delete_contrato();">Excluir
												</button>
												<button type="button" class="btn btn-success" id="btnSaveContrato"
														onclick="save_contrato();">Salvar
												</button>
											</div>
										</div>
										<br>
										<div class="form-group">
											<label class="control-label col-md-2">Tipo</label>
											<div class="col-md-3">
												<label class="radio-inline">
													<input type="radio" name="tipo" value="1"> C.V.
												</label>
												<label class="radio-inline">
													<input type="radio" name="tipo" value="2"> Contrato
												</label>
											</div>
											<label class="control-label col-md-3">Selecionar C.V. ou Contrato</label>
											<div class="col-md-4">
												<select id="curriculos2" class="form-control">
													<option value="">Novo CV...</option>
												</select>
												<select id="contratos2" class="form-control"
														style="display:none;">
													<option value="">Novo contrato...</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-2">Descrição</label>
											<div class="col-md-7">
												<input name="nome" placeholder="Descrição" class="form-control"
													   type="text">
												<span class="help-block"></span>
											</div>
											<div class="col-md-2">
												<div class="checkbox">
													<label>
														<input type="checkbox" name="status_ativo" value="1"><strong>Status
															ativo</strong>
													</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">Arquivo (.pdf)</label>
											<div class="col-md-10">
												<div id="arquivo_documento" class="fileinput input-group"
													 data-provides="fileinput">
													<div class="form-control" data-trigger="fileinput">
														<i class="glyphicon glyphicon-file fileinput-exists"></i>
														<span class="fileinput-preview fileinput-filename"></span>
													</div>
													<div class="input-group-addon btn btn-default btn-file" name="">
														<span class="fileinput-new">Selecionar arquivo</span>
														<span class="fileinput-exists">Alterar</span>
														<input type="file" accept=".pdf" name="arquivo"/>
													</div>
													<a href="#" data-dismiss="fileinput"
													   class="input-group-addon btn btn-default fileinput-exists">Limpar</a>
												</div>
											</div>
										</div>
										<div class="form-group contrato">
											<label class="control-label col-md-2">Data início</label>
											<div class="col-md-2">
												<input name="data_inicio" placeholder="dd/mm/aaaa"
													   class="form-control text-center date" type="text">
												<span class="help-block"></span>
											</div>
											<label class="control-label col-md-2">Data término</label>
											<div class="col-md-2">
												<input name="data_termino" placeholder="dd/mm/aaaa"
													   class="form-control text-center date" type="text">
												<span class="help-block"></span>
											</div>
											<label class="control-label col-md-2">Qtde horas/mês</label>
											<div class="col-md-2">
												<input name="qtde_horas_mensais" placeholder="hhh:mm"
													   class="form-control text-center hour" type="text">
												<span class="help-block"></span>
											</div>
										</div>
										<div class="form-group contrato">
											<label class="control-label col-md-2">Valor hora/período</label>
											<div class="col-md-3">
												<div class="input-group">
													<span class="input-group-addon">R$</span>
													<input type="text" name="valor_hora_periodo"
														   class="form-control text-right valor">
												</div>
											</div>
											<label class="control-label col-md-2">Valor mensal</label>
											<div class="col-md-3">
												<div class="input-group">
													<span class="input-group-addon">R$</span>
													<input type="text" name="valor_mensal"
														   class="form-control text-right valor">
												</div>
											</div>
										</div>
										<div class="form-group contrato">
											<label class="control-label col-md-2">Localidade</label>
											<div class="col-md-9">
												<textarea name="localidade" placeholder="localidade"
														  class="form-control" rows="5"></textarea>
												<span class="help-block"></span>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

	</section>
</section>

<?php require_once APPPATH . 'views/end_js.php'; ?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

<script>
	$(document).ready(function () {
		document.title = 'CORPORATE RH - LMS - Gerenciar PJs';
	});
</script>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

<script>

	var save_method;
	var table;

	$('.date').mask('00/00/0000');
	$('.hour').mask('000:00');
	$('.valor').mask('##.###.##0,00', {'reverse': true});

	$(document).ready(function () {

		table = $('#table').DataTable({
			'info': false,
			'processing': true,
			'serverSide': true,
			'order': [[0, 'asc']],
			'iDisplayLength': 100,
			'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
			'language': {
				'searchPlaceholder': 'Nome, função, contrato...'
			},
			'ajax': {
				'url': '<?php echo site_url('pj/colaboradores/listar') ?>',
				'type': 'POST',
				'data': function (d) {
					d.busca = $('#busca').serialize();
					return d;
				}
			},
			'columnDefs': [
				{
					'width': '40%',
					'targets': [0, 1]
				},
				{
					'width': '20%',
					'targets': [2]
				},
				{
					'createdCell': function (td, cellData, rowData, row, col) {
						if (moment(rowData[col], 'DD/MM/YYYY').isSame(moment(), 'month')) {
							$(td).css({'color': '#fff', 'background-color': '#d9534f'});
						} else if (moment(rowData[col], 'DD/MM/YYYY').isBetween(moment(), moment().add(1, 'months'))) {
							$(td).css({'color': '#000', 'background-color': '#ff0'});
						}
					},
					'className': 'text-center',
					'orderable': false,
					'targets': [4]
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


	function filtrar() {
		$.ajax({
			'url': '<?php echo site_url('pj/colaboradores/filtrar') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': $('#busca').serialize(),
			'success': function (json) {
				$.each(json, function (key, value) {
					$('.filtro[name="' + key + '"]').html($(value).html());
				});
				reload_table();
			}
		});
	}


	function gerenciar_contratos(id_usuario) {
		$('#form_contrato')[0].reset();
		$('#form_contrato [name="id"]').val('');
		$('#modal_contratos ul li:eq(1), #contrato_cadastro').removeClass('active');
		$('#modal_contratos ul li:eq(0), #contrato_visualizacao').addClass('active');
		$('#documento').attr('src', 'https://docs.google.com/gview?embedded=true&url=<?= base_url('arquivos/documentos/colaborador/') ?>');

		$.ajax({
			'url': '<?php echo site_url('pj/colaboradores/gerenciarContratos') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': {'id_usuario': id_usuario},
			'success': function (json) {
				$('#curriculos').html($(json.curriculos).html());
				$('#contratos').html($(json.contratos).html());
				$('#curriculos2').html($(json.id_curriculos).html());
				$('#contratos2').html($(json.id_contratos).html());

				$('.tipo[value="1"], #form_contrato [name="tipo"][value="1"]').prop('checked', true).trigger('change');
				$('#form_contrato [name="id_usuario"]').val(id_usuario);
				$('#form_contrato [name="status_ativo"]').prop('checked', true);

				$('#btnDeleteContrato').hide();
				$('#modal_contratos').modal('show');
			}
		});
	}


	$('.tipo').on('change', function () {
		if (this.value === '1') {
			$('#curriculos').show();
			$('#contratos').hide();
		} else if (this.value === '2') {
			$('#curriculos').hide();
			$('#contratos').show();
		}

		$('#curriculos, #contratos').val('').trigger('change');
	});


	$('#form_contrato [name="tipo"]').on('change', function () {
		if (this.value === '1') {
			$('#curriculos2').show();
			$('#contratos2').hide();
			$('.contrato').find('select, input, textarea').prop('disabled', true);
		} else if (this.value === '2') {
			$('#curriculos2').hide();
			$('#contratos2').show();
			$('.contrato').find('select, input, textarea').prop('disabled', false);
		}
	});


	$('#curriculos, #contratos').on('change', function () {
		var url = '<?= base_url('arquivos/documentos/colaborador/') ?>';
		$('#documento').attr('src', 'https://docs.google.com/gview?embedded=true&url=' + url + '/' + this.value);
	});


	$('#curriculos2, #contratos2').on('change', function () {
		var id = this.value;
		var tipo = $('#form_contrato [name="tipo"]:checked').val();

		if (id.length === 0) {
			$('#form_contrato')[0].reset();
			$('#form_contrato [name="id"]').val('');
			$('#form_contrato [name="tipo"][value="' + tipo + '"]').prop('checked', true);
			$('#form_contrato [name="status_ativo"]').prop('checked', true);
			$('#btnDeleteContrato').hide();
			return false;
		}

		$.ajax({
			'url': '<?php echo site_url('pj/colaboradores/editarContrato') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': {'id': id},
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
				} else {
					$.each(json, function (key, value) {
						if ($('#form_contrato [name="' + key + '"]').is(':checkbox') === false) {
							$('#form_contrato [name="' + key + '"]').val(value);
						} else {
							$('#form_contrato [name="' + key + '"]').prop('checked', value === '1');
						}
					});

					$('#btnDeleteContrato').show();
				}
			}
		});
	});


	function save_contrato() {
		$.ajax({
			'url': '<?php echo site_url('pj/colaboradores/salvarContrato'); ?>',
			'type': 'POST',
			'data': new FormData($('#form_contrato')[0]),
			'dataType': 'json',
			'enctype': 'multipart/form-data',
			'processData': false,
			'contentType': false,
			'cache': false,
			'beforeSend': function () {
				$('#btnSaveContrato').text('Salvando...').attr('disabled', true);
			},
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
				} else if (json.status) {
					$('#modal_contratos').modal('hide');
					reload_table();
				}
			},
			'complete': function () {
				$('#btnSaveContrato').text('Salvar').attr('disabled', false);
			}
		});
	}


	function delete_contrato() {
		if (confirm('Tem certeza que deseja excluir o arquivo?')) {
			$.ajax({
				'url': '<?php echo site_url('pj/colaboradores/excluirContrato'); ?>',
				'type': 'POST',
				'data': {
					'id': $('#form_contrato [name="id"]').val()
				},
				'dataType': 'json',
				'beforeSend': function () {
					$('#btnDeleteContrato').text('Excluindo...').attr('disabled', true);
				},
				'success': function (json) {
					if (json.erro) {
						alert(json.erro);
					} else if (json.status) {
						$('#modal_contratos').modal('hide');
						reload_table();
					}
				},
				'complete': function () {
					$('#btnDeleteContrato').text('Excluir').attr('disabled', false);
				}
			});
		}
	}


	function reload_table() {
		table.ajax.reload(null, false);
	}


	function setPdf_atributes() {
		var search = '';
		var q = new Array();

		$('.filtro').each(function (i, v) {
			if (v.value.length > 0) {
				q[i] = v.name + "=" + v.value;
			}
		});

		q = q.filter(function (v) {
			return v.length > 0;
		});
		if (q.length > 0) {
			search = '/q?' + q.join('&');
		}

		$('#pdf').prop('href', "<?= site_url('pj/colaboradores/pdf/'); ?>" + search);
	}

</script>

<?php require_once APPPATH . 'views/end_html.php'; ?>
