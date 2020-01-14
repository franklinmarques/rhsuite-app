<?php
require_once "header.php";
?>
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

	.nav > li > a {
		padding: 10px 7px;
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
					<li class="active">Gerenciar Relatórios de Gestão</li>
				</ol>
				<button class="btn btn-info" onclick="add_relatorio()"><i class="glyphicon glyphicon-plus"></i>
					Novo relatório
				</button>
				<button class="btn btn-default" data-toggle="collapse" aria-pressed="false" data-target="#filtro"
						aria-expanded="false"
						aria-controls="filtro" style="float: right;"><i class="fa fa-search"></i>
					Pesquisa avançada
				</button>
				<br/>
				<br/>
				<div class="collapse" id="filtro">
					<div class="well well-sm">
						<form id="form_filtro" action="#" autocomplete="off">
							<div class="row form-group">
								<div class="col-md-4">
									<label class="control-label">Departamento</label>
									<?php echo form_dropdown('id_depto', $deptos, $id_depto, 'class="form-control input-sm" onchange="atualizar_filtro();"'); ?>
								</div>
								<div class="col-md-4">
									<label class="control-label">Área</label>
									<?php echo form_dropdown('id_area', $areas, $id_area, 'class="form-control input-sm" onchange="atualizar_filtro();"'); ?>
								</div>
								<div class="col-md-4">
									<label class="control-label">Setor</label>
									<?php echo form_dropdown('id_setor', $setores, $id_setor, 'class="form-control input-sm" onchange="atualizar_filtro();"'); ?>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-4">
									<label class="control-label">Colaborador</label>
									<?php echo form_dropdown('id_usuario', $usuarios, $usuario, 'class="form-control input-sm"'); ?>
								</div>
								<div class="col-md-2">
									<label class="control-label">Status</label>
									<select name="status" class="form-control input-sm">
										<option value="">Todos</option>
										<option value="M">Em elaboração</option>
										<option value="E">Elaborado</option>
										<option value="A">Em análise</option>
										<option value="P">Pendências</option>
										<option value="C">Aceito</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Mês</label>
									<select name="mes_referencia" class="form-control input-sm">
										<option value="">Todos</option>
										<option value="01">Janeiro</option>
										<option value="02">Fevereiro</option>
										<option value="03">Março</option>
										<option value="04">Abril</option>
										<option value="05">Maio</option>
										<option value="06">Junho</option>
										<option value="07">Julho</option>
										<option value="08">Agosto</option>
										<option value="09">Setembro</option>
										<option value="10">Outubro</option>
										<option value="11">Novembro</option>
										<option value="12">Dezembro</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Ano</label>
									<input name="ano_referencia" class="form-control text-center ano input-sm"
										   type="text">
								</div>
								<div class="col-md-2">
									<br>
									<button type="button" class="btn btn-default" onclick="reload_table()"><i
											class="fa fa-search"></i></button>
									<button type="button" class="btn btn-default" onclick="limpar_filtro()">Limpar
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<table id="table" class="table table-striped" cellspacing="0" width="100%">
					<thead>
					<tr>
						<th>Mês</th>
						<th>Ano</th>
						<th>Departamento</th>
						<th>Status</th>
						<th>Parecer</th>
						<th>Ações</th>
					</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<!-- page end-->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_form" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div style="float: right;">
							<button type="button" onclick="save()" class="btn btn-success btnSave">Salvar</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						</div>
						<h3 class="modal-title">Formulario de Relatório de Gestão</h3>
					</div>
					<div class="modal-body form">
						<div id="alert"></div>
						<form action="#" id="form" class="form-horizontal" autocomplete="off">
							<input type="hidden" value="" name="id"/>
							<input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
							<input type="hidden" value="<?= $usuario; ?>" name="id_usuario"/>
							<input type="hidden" value="<?= $id_depto; ?>" name="id_depto"/>
							<input type="hidden" value="<?= $id_area; ?>" name="id_area"/>
							<input type="hidden" value="<?= $id_setor; ?>" name="id_setor"/>

							<div class="form-body">
								<div class="form-group">
									<label class="control-label col-sm-1">Status</label>
									<div class="col-sm-2">
										<select name="status" class="form-control" style="width:150px;">
											<option value="">selecione...</option>
											<option value="M">Em elaboração</option>
											<option value="E">Elaborado</option>
											<option value="A">Em análise</option>
											<option value="P">Pendências</option>
											<option value="C">Aceito</option>
										</select>
									</div>
									<label class="control-label col-sm-2">Data fechamento</label>
									<div class="col-sm-2">
										<input name="data_fechamento" placeholder="dd/mm/aaaa"
											   class="form-control text-center date" type="text">
										<span class="help-block"></span>
									</div>
									<label class="control-label col-sm-2">Mês/ano referência</label>
									<div class="col-sm-3 form-inline">
										<select name="mes_referencia" class="form-control">
											<option value="">selecione...</option>
											<option value="01">Janeiro</option>
											<option value="02">Fevereiro</option>
											<option value="03">Março</option>
											<option value="04">Abril</option>
											<option value="05">Maio</option>
											<option value="06">Junho</option>
											<option value="07">Julho</option>
											<option value="08">Agosto</option>
											<option value="09">Setembro</option>
											<option value="10">Outubro</option>
											<option value="11">Novembro</option>
											<option value="12">Dezembro</option>
										</select>
										<input name="ano_referencia" class="form-control text-center ano"
											   type="text" placeholder="aaaa" style="width:64px;">
									</div>
								</div>
								<hr>
								<ul class="nav nav-tabs" role="tablist">
									<li role="presentation" class="active">
										<a href="#indicadores" aria-controls="indicadores" role="tab"
										   data-toggle="tab">Inicadores</a></li>
									<li role="presentation">
										<a href="#riscos_oportunidades" aria-controls="riscos_oportunidades"
										   role="tab" data-toggle="tab">Riscos e oportunidades</a></li>
									<li role="presentation">
										<a href="#ocorrencias" aria-controls="ocorrencias"
										   role="tab" data-toggle="tab">Ocorrências</a></li>
									<li role="presentation">
										<a href="#necessidades_investimentos"
										   aria-controls="necessidades_investimentos" role="tab" data-toggle="tab">Necessidades/investimentos</a>
									</li>
									<li role="presentation">
										<a href="#objetivos_futuros" aria-controls="objetivos_futuros" role="tab"
										   data-toggle="tab">Objetivos curto prazo</a></li>
									<li role="presentation">
										<a href="#objetivos_imediatos" aria-controls="objetivos_imediatos"
										   role="tab"
										   data-toggle="tab">Objetivos médio/longo prazo</a></li>
									<li role="presentation">
										<a href="#parecer_final" aria-controls="parecer_final" role="tab"
										   data-toggle="tab">Parecer</a></li>
									<li role="presentation" style="padding-left:50px;">
										<a href="#observacoes" aria-controls="observacoes" role="tab"
										   data-toggle="tab"><strong>Observações</strong></a></li>
								</ul>

								<div class="tab-content">
									<div role="tabpanel" class="tab-pane active" id="indicadores">
										<h4 class="text-primary"><strong>Apresente no campo abaixo os indicadores
												chave de sua área conforme exemplo:</strong></h4>
										<p class="text-primary">
											Indicador > npp = 1450<br>
											Parecer > O numero de peças produzidas este mês foi 5% abaixo do
											produzido no mês anterior devido ao feriado do dia 15.
										</p>
										<textarea name="indicadores" class="form-control descritivo"
												  rows="12"></textarea>
									</div>
									<div role="tabpanel" class="tab-pane" id="riscos_oportunidades">
										<h4 class="text-primary"><strong>Apresente no campo abaixo os riscos e
												oportunidades observadas no ultimo período:</strong></h4>
										<p class="text-primary">
											Riscos > O cliente tem apresentado que não está satisfeito com o tempo
											de atendimento de nossos colaboradores.<br>
											Oportunidades > O cliente apresentou que irá abrir uma nova unidade no
											próximo semestre, ou seja existe possibilidade de ampliarmos nossa
											operação nesta nova unidade.
										</p>
										<textarea name="riscos_oportunidades" class="form-control descritivo"
												  rows="12"></textarea>
									</div>
									<div role="tabpanel" class="tab-pane" id="ocorrencias">
										<h4 class="text-primary"><strong>Apresente no campo abaixo resumo das
												ocorrências mais relevantes do ultimo período:</strong></h4>
										<p class="text-primary">
											Ocorrências internas (Colaboradores, processo) > Nosso sistema de
											internet focou fora do ar por dois dias ocasionando perda de receita na
											unidade X.<br>
											Ocorrências com o cliente > O cliente deseja reunião para discutir
											redução de quadro.
										</p>
										<textarea name="ocorrencias" class="form-control descritivo"
												  rows="12"></textarea>
									</div>
									<div role="tabpanel" class="tab-pane" id="necessidades_investimentos">
										<h4 class="text-primary"><strong>Apresente no campo abaixo resumo das
												Necessidades/Investimentos requeridos por sua área:</strong></h4>
										<p class="text-primary">
											Necessidades : Necessitamos de ampliar o quadro de pessoal colocando um
											atendente extra no setor X.<br>
											Investimentos : Necessitamos capacitar o time de vendedores. Efetuamos
											pesquisa e orçamento para esse treinamento. Segue abaixo os valores dos
											orçamentos.
										</p>
										<textarea name="necessidades_investimentos" class="form-control descritivo"
												  rows="12"></textarea>
									</div>
									<div role="tabpanel" class="tab-pane" id="objetivos_futuros">
										<h4 class="text-primary"><strong>Apresente no campo abaixo resumo dos
												objetivos de curto prazo para sua área</strong></h4>
										<p class="text-primary">
											Objetivos : Nosso objetivo para o próximo bimestre é treinar os
											vendedores X e Y. Adicionalmente estamos engajados em reduzir em 5% o
											absenteismo da equipe de atendimento.
										</p>
										<textarea name="objetivos_futuros" class="form-control descritivo"
												  rows="12"></textarea>
									</div>
									<div role="tabpanel" class="tab-pane" id="objetivos_imediatos">
										<h4 class="text-primary"><strong>Apresente no campo abaixo resumo dos
												objetivos de médio/longo prazo para sua área</strong></h4>
										<p class="text-primary">
											Objetivos : Nosso objetivo para o semestre é manter o número de
											absenteísmo por falta abaixo de 3% no mês. Objetivamentos também ampliar
											a carteira de clientes em 7% ate o final do ano.
										</p>
										<textarea name="objetivos_imediatos" class="form-control descritivo"
												  rows="12"></textarea>
									</div>
									<div role="tabpanel" class="tab-pane" id="parecer_final">
										<h4 class="text-primary"><strong>Apresente no campo abaixo um "Parecer" ou
												"Resumo" sobre sua área:</strong></h4>
										<p class="text-primary">
											Parecer/Resumo : Meu departamento/Área/Setor apresentou bom desempenho
											no ultimo mês/bimestre quando comparamos com os objetivos traçados para
											o mesmo. Os pequenos desvios observados nos indicadores de resultados
											quantitativos se devem aos feriados ocorridos no período...
										</p>
										<textarea name="parecer_final" class="form-control descritivo"
												  rows="12"></textarea>
									</div>
									<div role="tabpanel" class="tab-pane" id="observacoes">
										<h5 class="text-primary"><strong>Sr. Gestor, apresente no campo abaixo suas
												observações, duvidas, sugestões para o relatório de gestão
												apresentado:</strong></h5>
										<p class="text-primary">
											Adicionalmente ajuste o campo de Status para "Fechado" se aceitou por
											completo o relatório ou para "Pendencias" caso deseje maiores
											informações ou conversar pessoalmente com seu colaborador. Anote suas
											dúvidas para que o gestor possa responde por meio desse relatorio ou
											possa se preparar para respondê-las pessoalmente.
										</p>
										<textarea name="observacoes" class="form-control descritivo"
												  rows="12"></textarea>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<!-- End Bootstrap modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_visualizacao" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body form">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<!-- End Bootstrap modal -->

	</section>
</section>
<!--main content end-->

<?php
require_once "end_js.php";
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
	$(document).ready(function () {
		document.title = 'CORPORATE RH - LMS - Gerenciar Relatórios de Gestão';
	});
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/ckeditor/adapters/jquery.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
<script>

	var save_method;
	var table;

	$(document).ready(function () {

		$('.date').mask('00/00/0000');
		$('.ano').mask('0000');

		$('.descritivo').ckeditor({
			'height': '600',
			'filebrowserBrowseUrl': '<?= base_url('browser/browse.php'); ?>',
			'toolbarGroups': [{
				'name': 'basicstyles',
				'groups': ['basicstyles']
			},
				{
					'name': 'links',
					'groups': ['links']
				},
				{
					'name': 'paragraph',
					'groups': ['list']
				},
				{
					'name': 'document',
					'groups': ['mode']
				},
				{
					'name': 'insert',
					'groups': ['insert']
				},
				{
					'name': 'styles',
					'groups': ['styles']
				},
				{
					'name': 'about',
					'groups': ['about']
				}
			],
			// Remove the redundant buttons from toolbar groups defined above.
			'removeButtons': 'Underline,Strike,Subscript,Superscript,NewPage,Preview,Save,Anchor,Styles,Specialchar,Flash,PageBreak,Slideshow'
		});

		table = $('#table').DataTable({
			'processing': true,
			'serverSide': true,
			'order': [],
			'iDisplayLength': -1,
			'lengthChange': false,
			'searching': false,
			'language': {
				'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
			},
			'ajax': {
				'url': '<?php echo site_url('relatoriosGestao/ajaxList/') ?>',
				'type': 'POST',
				'data': function (d) {
					d.busca = $('#form_filtro').serialize();
					return d;
				}
			},
			'columnDefs': [
				{
					'className': 'text-center',
					'targets': [0, 1]
				},
				{
					'createdCell': function (td, cellData, rowData, row, col) {
						switch (rowData[6]) {
							case 'E':
								$(td).css({'background-color': '#f5c6cb', 'border-color': '#ed969e'});
								break;
							case 'A':
								$(td).css({'background-color': '#b8daff', 'border-color': '#7abaff'});
								break;
							case 'P':
								$(td).css({'background-color': '#ffeeba', 'border-color': '#ffdf7e'});
								break;
							case 'C':
								$(td).css({'background-color': '#c3e6cb', 'border-color': '#8fd19e'});
								break;
						}
					},
					'className': 'text-center text-nowrap',
					'targets': [3]
				},
				{
					'width': '30%',
					'targets': [2]
				},
				{
					'width': '50%',
					'targets': [4]
				},
				{
					'className': 'text-center text-nowrap',
					'targets': [-1],
					'orderable': false
				}
			]
		});

	});

	function atualizar_filtro() {
		$.ajax({
			'url': '<?php echo site_url('relatoriosGestao/atualizarFiltro') ?>',
			'dataType': 'json',
			'data': $('#form_filtro').serialize(),
			'success': function (json) {
				$('#form_filtro [name="id_area"]').html($(json.area).html());
				$('#form_filtro [name="id_setor"]').html($(json.setor).html());
				$('#form_filtro [name="id_usuario"]').html($(json.usuario).html());
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}


	function add_relatorio() {
		save_method = 'add';
		$('#form')[0].reset();
		$('.form-group').removeClass('has-error');
		$('.help-block').empty();

		var date = moment('<?= date('d/m/Y'); ?>', 'DD/MM/YYYY');

		$('#form [name="status"]').val('M');
		$('#form [name="data_fechamento"]').val(date.format('DD/MM/YYYY'));
		$('#form [name="mes_referencia"]').val(date.format('MM'));
		$('#form [name="ano_referencia"]').val(date.get('year'));

		$('#modal_form').modal('show');
		$('#modal_form .modal-title').text('Adicionar Relatório de Gestão');
		$('.combo_nivel1').hide();
	}


	function edit_relatorio(id) {
		save_method = 'update';
		$('#form')[0].reset();
		$('.form-group').removeClass('has-error');
		$('.help-block').empty();


		$.ajax({
			'url': '<?php echo site_url('relatoriosGestao/ajaxEdit') ?>',
			'dataType': 'json',
			'data': {'id': id},
			'success': function (json) {
				$.each(json, function (key, value) {
					$('[name="' + key + '"]').val(value);
				});

				$('#modal_form .modal-title').text('Editar Relatório de Gestão');
				$('#modal_form').modal('show');
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}


	function reload_table() {
		table.ajax.reload(null, false);
	}


	function limpar_filtro() {
		$('#form_filtro')[0].reset();
		reload_table();
	}


	function save() {
		$('.btnSave').text('Salvando...').attr('disabled', true);
		var url;

		if (save_method === 'add') {
			url = '<?php echo site_url('relatoriosGestao/ajaxAdd') ?>';
		} else {
			url = '<?php echo site_url('relatoriosGestao/ajaxUpdate') ?>';
		}

		$.ajax({
			'url': url,
			'type': 'POST',
			'data': $('#form').serialize(),
			'dataType': 'json',
			'success': function (json) {
				if (json.status) {
					$('#modal_form').modal('hide');
					reload_table();
				} else if (json.erro) {
					alert(json.erro);
				}

				$('.btnSave').text('Salvar').attr('disabled', false);
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error adding / update data');
				$('.btnSave').text('Salvar').attr('disabled', false);
			}
		});
	}


	function delete_relatorio(id) {
		if (confirm('Deseja remover?')) {

			$.ajax({
				'url': '<?php echo site_url('relatoriosGestao/ajaxDelete') ?>',
				'type': 'POST',
				'dataType': 'json',
				'data': {'id': id},
				'success': function () {
					reload_table();
				},
				'error': function (jqXHR, textStatus, errorThrown) {
					$('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
				}
			});

		}
	}


	function visualizar(id) {
		$.ajax({
			'url': '<?php echo site_url('relatoriosGestao/visualizar') ?>',
			'dataType': 'html',
			'data': {'id': id},
			'beforeSend': function () {
				$('#alert').html('');
			},
			'success': function (data) {
				$('#modal_visualizacao .modal-body').html(data);
				$('#modal_visualizacao').modal('show');
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				$('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
			}
		});
	}


</script>

<?php
require_once "end_html.php";
?>
