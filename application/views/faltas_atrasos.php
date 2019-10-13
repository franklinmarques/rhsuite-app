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
					<li class="active">Apontamentos de Faltas e Atrasos</li>
					<?php $this->load->view('modal_processos', ['url' => 'faltas_atrasos']); ?>
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
								<table id="table"
									   class="table table-hover table_apontamento table-condensed table-bordered"
									   cellspacing="0" width="100%">
									<thead>
									<tr>
										<th rowspan="2" class="warning" style="vertical-align: middle;">
											Colaborador(a)
										</th>
										<th rowspan="2" class="warning" style="vertical-align: middle;">
											Colaborador(a) substituito(a)
										</th>
										<th rowspan="2" class="warning" style="vertical-align: middle;">
											Banco de Horas
										</th>
										<th colspan="31" class="date-width" id="dias">Dias</th>
									</tr>
									<tr>
										<?php for ($i = 1; $i <= 31; $i++): ?>
											<th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
										<?php endfor; ?>
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
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Editar evento de apontamento</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form" class="form-horizontal">
							<input type="hidden" value="" name="id"/>
							<input type="hidden" value="" name="id_colaborador"/>
							<input type="hidden" value="" name="data"/>
							<input type="hidden" value="" name="id_depto"/>
							<input type="hidden" value="" name="id_area"/>
							<input type="hidden" value="" name="id_setor"/>
							<div class="row form-group">
								<label class="control-label col-md-2" style="margin-top: -13px; font-weight: bold;">Colaborador(a):<br>Data:</label>
								<div class="col-md-5" style="margin-top: -13px;">
									<label class="sr-only"></label>
									<p class="form-control-static">
										<span id="evento_nome"></span><br>
										<span id="evento_data"></span>
									</p>
								</div>
								<div class="col-md-5 text-right">
									<button type="button" id="btnSalvarEvento" onclick="salvar_evento()"
											class="btn btn-success">
										Salvar
									</button>
									<button type="button" id="btnExcluirEvento" onclick="excluir_evento()"
											class="btn btn-danger">
										Excluir
									</button>
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
									</button>
								</div>
							</div>
							<hr style="margin-top: 0px;">
							<div class="form-body" style="padding-top: 0;">
								<div class="row">
									<h5 style="margin-top: 0;">Tipo de evento</h5>
									<div class="col col-md-3">
										<div class="radio">
											<label>
												<input type="radio" name="status" value="FJ" checked>
												Falta com atestado próprio
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="status" value="FN">
												Falta sem atestado
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="status" value="FR">
												Feriado
											</label>
										</div>
									</div>
									<div class="col col-md-3">
										<div class="radio">
											<label>
												<input type="radio" name="status" value="AJ">
												Atraso com atestado próprio
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="status" value="AN">
												Atraso sem atestado
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="status" value="AE">
												Apontamento extra
											</label>
										</div>
									</div>
									<div class="col col-md-3">
										<div class="radio">
											<label>
												<input type="radio" name="status" value="SJ">
												Saída antec. com atestado próprio
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="status" value="SN">
												Saída antecipada sem atestado
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="status" value="PR">
												Apontamento de produção
											</label>
										</div>
									</div>
									<div class="col col-md-3">
										<div class="radio">
											<label>
												<input type="radio" name="status" value="PD">
												Posto descoberto
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="status" value="PI">
												Posto desativado
											</label>
										</div>
									</div>
								</div>
								<hr style="border-top: 1px solid #b0b0b0;">
								<div class="row form-group">
									<label class="control-label col-md-2">Desconto folha</label>
									<div class="col-md-2">
										<input name="desconto_folha" class="hora form-control text-center" type="text"
											   value="" placeholder="hh:mm">
									</div>
									<label class="control-label col-md-2">Apontamento +</label>
									<div class="col-md-2">
										<input name="apontamento_positivo" class="hora form-control text-center"
											   value="" placeholder="hh:mm" maxlength="5" autocomplete="off"
											   type="text">
									</div>
									<label class="control-label col-md-2">Apontamento -</label>
									<div class="col-md-2">
										<input name="apontamento_negativo" class="hora form-control text-center"
											   value="" placeholder="hh:mm" maxlength="5" autocomplete="off"
											   type="text">
									</div>
								</div>
								<hr style="border-top: 1px solid #b0b0b0;">
								<div class="row form-group">
									<label class="control-label col-md-2">Glosa horas</label>
									<div class="col-md-2">
										<input name="glosa_horas" class="hora form-control text-center" type="text"
											   value="" placeholder="hh:mm">
									</div>
									<label class="control-label col-md-2">Horário entrada</label>
									<div class="col-md-2">
										<input name="horario_entrada" class="hora form-control text-center" value=""
											   placeholder="hh:mm" maxlength="5" autocomplete="off" type="text">
									</div>
									<label class="control-label col-md-2">Horário intervalo</label>
									<div class="col-md-2">
										<input name="horario_intervalo" class="hora form-control text-center" value=""
											   placeholder="hh:mm" maxlength="5" autocomplete="off" type="text">
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-2">Glosa dias</label>
									<div class="col-md-2">
										<input name="glosa_dias" class="form-control text-right numero" type="text"
											   value="">
									</div>
									<label class="control-label col-md-2">Horário retorno</label>
									<div class="col-md-2">
										<input name="horario_retorno" class="hora form-control text-center" value=""
											   placeholder="hh:mm" maxlength="5" autocomplete="off" type="text">
									</div>
									<label class="control-label col-md-2">Horário saída</label>
									<div class="col-md-2">
										<input name="horario_saida" class="hora form-control text-center" value=""
											   placeholder="hh:mm" maxlength="5" autocomplete="off" type="text">
									</div>
								</div>
								<hr style="border-top: 1px solid #b0b0b0;">


								<div class="row form-group">
									<div class="col-md-6">
										<label class="control-label col-md-2">Backup</label>
										<div class="col-md-10">
											<?php echo form_dropdown('id_colaborador_sub', ['' => 'selecione...'], '', 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<label class="control-label col-md-2">Detalhes</label>
										<div class="col-md-10">
											<?php echo form_dropdown('id_detalhes', ['' => 'selecione...'], '', 'class="form-control"'); ?>
										</div>
									</div>
								</div>


								<div class="row form-group">
									<label class="control-label col-md-2">Observações gerais</label>
									<div class="col-md-10">
                                                <textarea name="observacoes" class="form-control"
														  rows="2"></textarea>
										<span class="help-block"></span>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_eventos" role="dialog">
			<div class="modal-dialog modal-sm" style="width: 320px;">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Edição de eventos</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form_eventos" class="form-horizontal">
							<input type="hidden" value="" name="data"/>
							<div class="row form-group">
								<label class="control-label col-md-3">Data</label>
								<div class="col-md-9">
									<label class="sr-only"></label>
									<p class="form-control-static">
										<span id="data_evento"></span>
									</p>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-3">Status</label>
								<div class="col-sm-9">
									<div class="radio">
										<label>
											<input type="radio" name="status" value="FC" checked>
											Feriado escola/cuidador
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="status" value="FE" checked>
											Feriado escola
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="status" value="EM">
											Emenda de feriado
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="status" value="PC">
											Posto coberto
										</label>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSaveEventos" onclick="save_eventos()"
								class="btn btn-success">Replicar
						</button>
						<button type="button" id="btnDeleteEventos" onclick="delete_eventos()"
								class="btn btn-danger">Limpar
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
                'url': '<?php echo site_url('faltasAtrasos/listar') ?>',
                'type': 'POST',
                'data': function (d) {
                    d.depto = $('#depto').val();
                    d.area = $('#area').val();
                    d.setor = $('#setor').val();
                    d.status = $('#status').val();
                    d.mes = mes_ano.get('month') + 1;
                    d.ano = mes_ano.get('year');

                    return d;
                },
                'dataSrc': function (json) {
                    var dt1 = new Date();
                    var dt2 = new Date();
                    dt2.setFullYear(json.calendar.ano, (json.calendar.mes - 1));

                    var semana = 1;
                    var colunasUsuario = 2;
                    for (i = 1; i <= 31; i++) {
                        if (i > 28) {
                            if (i > json.calendar.qtde_dias) {
                                table.column(i + colunasUsuario).visible(false, false);
                                continue;
                            } else {
                                table.column(i + colunasUsuario).visible(true, false);
                            }
                        }
                        var coluna = $(table.columns(i + colunasUsuario).header());
                        coluna.removeClass('text-danger').css('background-color', '');
                        coluna.attr({
                            'data-dia': json.calendar.ano + '-' + json.calendar.mes + '-' + coluna.text(),
                            'data-mes_ano': json.calendar.semana[semana] + ', ' + coluna.text() + '/' + json.calendar.mes + '/' + json.calendar.ano,
                            'title': json.calendar.semana[semana] + ', ' + coluna.text() + ' de ' + json.calendar.mes_ano.replace(' ', ' de ')
                        });
                        if (json.calendar.semana[semana] === 'Sábado' || json.calendar.semana[semana] === 'Domingo') {
                            coluna.addClass('text-danger').css('background-color', '#dbdbdb');
                        }
                        if ((dt1.getTime() === dt2.getTime()) && dt1.getDate() === i) {
                            coluna.css('background-color', '#0f0');
                        }
                        if (i % 7 === 0) {
                            semana = 1;
                        } else {
                            semana++;
                        }
                        if (json.data.length > 0) {
                            coluna.css('cursor', 'pointer').on('click', function () {
                                $('#data_evento').html(this.dataset.mes_ano);
                                $('#form_eventos [name="data"]').val(this.dataset.dia);
                                $('#modal_eventos').modal('show');
                            });
                        }
                    }
                    if (json.data.length > 0) {
                        $('#dias').html('<strong>Dias</strong> (clique em um dia do mês para replicar/limpar feriados, emendas de feriados ou postos descobertos)');
                    } else {
                        $('#dias').html('<strong>Dias</strong>');
                    }

                    return json.data;
                }
            },
            'columnDefs': [
                {
                    'width': '40%',
                    'targets': [0, 1]
                },
                {
                    'createdCell': function (td, cellData, rowData, row, col) {
                        switch (rowData[col]['text']) {
                            case 'FA':
                            case 'PV':
                                $(td).css({'color': '#000', 'background-color': '#f0ad4e'});
                                break;
                            case 'AT':
                            case 'SA':
                                $(td).css({'color': '#000', 'background-color': '#ff0'});
                                break;
                            case 'FE':
                            case 'EM':
                                $(td).css({'color': '#fff', 'background-color': '#337ab7'});
                                break;
                            case 'AF':
                            case 'AF':
                                $(td).css({'color': '#000', 'background-color': '#fff'});
                                break;
                        }
                        $(td).css('cursor', 'pointer').on('click', function () {
                            var dia = mes_ano;
                            dia.date(table.column(col).header(1).textContent);
                            edit_evento(rowData[34], dia.format('YYYY-MM-DD'), rowData[0]);
                        });
                        $(td).popover({
                            'container': 'body',
                            'placement': 'auto bottom',
                            'trigger': 'hover',
                            'content': function () {
                                if (rowData[col]['text']) {
                                    return '<strong>Nome:</strong> ' + rowData[col]['nome'] + '<br>' +
                                        '<strong>Data:</strong> ' + rowData[col]['data'] + '<br>' +
                                        '<strong>Status:</strong> ' + rowData[col]['status'];
                                } else {
                                    var dia1 = mes_ano;
                                    dia1.date(table.column(col).header(1).textContent);
                                    return '<strong>Nome:</strong> ' + rowData[0] + '<br>' +
                                        '<strong>Data:</strong> ' + dia1.format('DD/MM/YYYY') + '<br>' +
                                        '<strong>Status:</strong> <span style="color: #aaa;">Vazio</span>';
                                }
                            },
                            'html': true
                        });
                        $(td).html(rowData[col]['text']);
                    },
                    'orderable': false,
                    'searchable': false,
                    'targets': 'date-width'
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
            'url': '<?php echo site_url('faltasAtrasos/filtrarEstrutura') ?>',
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


    function edit_evento(id_colaborador, data, nome) {
        $.ajax({
            'url': '<?php echo site_url('faltasAtrasos/editarEvento') ?>',
            'type': 'POST',
            'data': {
                'id_colaborador': id_colaborador,
                'data': data
            },
            'beforeSend': function () {
                $('#form')[0].reset();
                $('.filtro').prop('disabled', true);
            },
            'dataType': 'json',
            'success': function (json) {
                $('#evento_nome').text(nome);
                $('#evento_data').text(moment(data, 'YYYY-MM-DD').format('DD/MM/YYYY'));

                if (json !== null) {
                    $.each(json, function (key, value) {
                        if ($('#form [name="' + key + '"]').prop('type') === 'radio') {
                            $('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value !== null);
                        } else {
                            $('#form [name="' + key + '"]').val(value);
                        }
                    });
                    $('#btnExcluirEvento').show();
                } else {
                    $('#form [name="id"]').val('');
                    $('#form [name="id_colaborador"]').val(id_colaborador);
                    $('#form [name="data"]').val(data);
                    $('#form [name="id_depto"]').val($('#depto').val());
                    $('#form [name="id_area"]').val($('#area').val());
                    $('#form [name="id_setor"]').val($('#setor').val());
                    $('#btnExcluirEvento').hide();
                }

                $('#modal_form').modal('show');
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }


    function salvar_evento() {
        $.ajax({
            'url': '<?php echo site_url('faltasAtrasos/salvarEvento') ?>',
            'type': 'POST',
            'data': $('#form').serialize(),
            'beforeSend': function () {
                $('#btnSalvarEvento, #btnExcluirEvento').prop('disabled', true);
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
                $('#btnSalvarEvento, #btnExcluirEvento').prop('disabled', false);
            }
        });
    }


    function excluir_evento() {
        $.ajax({
            'url': '<?php echo site_url('faltasAtrasos/excluirEvento') ?>',
            'type': 'POST',
            'data': {
                'id': $('#form [name="id"]').val()
            },
            'beforeSend': function () {
                $('#btnSalvarEvento, #btnExcluirEvento').prop('disabled', true);
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
                $('#btnSalvarEvento, #btnExcluirEvento').prop('disabled', false);
            }
        });
    }


    function save_eventos() {
        $.ajax({
            'url': '<?php echo site_url('faltasAtrasos/salvarEventos') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'depto': $('#depto').val(),
                'area': $('#area').val(),
                'setor': $('#setor').val(),
                'status': $('#status').val(),
                'eventos': $('#form_eventos').serialize()
            },
            'beforeSend': function () {
                $('#btnSaveEventos').text('Replicando...');
                $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', true);
            },
            'success': function (json) {
                $('#modal_eventos').modal('hide');
                reload_table();
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            },
            'complete': function () {
                $('#btnSaveEventos').text('Replicar');
                $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', false);
            }
        });
    }


    function delete_eventos() {
        if (confirm('Deseja limpar os eventos desta data?')) {
            $.ajax({
                'url': '<?php echo site_url('faltasAtrasos/excluirEventos') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'depto': $('#depto').val(),
                    'area': $('#area').val(),
                    'setor': $('#setor').val(),
                    'status': $('#status').val(),
                    'eventos': $('#form_eventos').serialize()
                },
                'beforeSend': function () {
                    $('#btnDeleteEventos').text('Limpando...');
                    $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', true);
                },
                'success': function (json) {
                    $('#modal_eventos').modal('hide');
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                },
                'complete': function () {
                    $('#btnDeleteEventos').text('Limpar');
                    $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', false);
                }
            });
        }
    }

</script>

<?php require_once 'end_html.php'; ?>
