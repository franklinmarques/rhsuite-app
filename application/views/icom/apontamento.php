<?php require_once APPPATH . 'views/header.php'; ?>

<style>
	#table_processing,
	#table_funcionarios_processing,
	#table_cuidadores_processing,
	#table_controle_materiais_processing,
	#table_colaboradores_processing {
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
		vertical-align: middle !important;
		white-space: normal;
	}

	.evento, .desconto_mes, .total_horas_mes {
		border-radius: 1px;
		border-width: 2px !important;
		border-style: outset solid solid outset !important;
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

	.table_apontamento > tbody > tr > td.colaborador-success,
	.table_apontamento > tbody > tr > td.date-width-success {
		color: #fff;
		background-color: #5cb85c !important;
	}

	.table_apontamento > tbody > tr > td.colaborador-success:hover,
	.table_apontamento > tbody > tr > td.date-width-success:hover {
		background-color: #47a447 !important;
	}

	.table_apontamento > tbody > tr > td.colaborador-primary,
	.table_apontamento > tbody > tr > td.date-width-primary {
		color: #fff;
		background-color: #337ab7 !important;
	}

	.table_apontamento > tbody > tr > td.colaborador-primary:hover,
	.table_apontamento > tbody > tr > td.date-width-primary:hover {
		background-color: #23527c;
	}

	.table_apontamento > tbody > tr > td.colaborador-info,
	.table_apontamento > tbody > tr > td.date-width-info {
		color: #fff;
		background-color: #5bc0de !important;
	}

	.table_apontamento > tbody > tr > td.colaborador-info:hover,
	.table_apontamento > tbody > tr > td.date-width-info:hover {
		background-color: #23527c;
	}

	.table_apontamento > tbody > tr > td.date-width-warning {
		/*color: #fff;*/
		background-color: #f0ad4e !important;
	}

	.table_apontamento > tbody > tr > td.date-width-warning:hover {
		background-color: #ed9c28 !important;
	}

	.table_apontamento > tbody > tr > td.date-width-danger {
		color: #fff;
		background-color: #d9534f !important;
	}

	.table_apontamento > tbody > tr > td.date-width-danger:hover {
		background-color: #d2322d !important;
	}

	#insumos .table tr td:first-child, insumos .table tr td:last-child {
		width: 50%;
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
					<li class="active">ICOM - Apontamentos Diários</li>
					<?php $this->load->view('modal_processos', ['url' => 'icom/apontamento']); ?>
				</ol>
				<div class="row">
					<div class="col-md-6">
						<div class="btn-group">
							<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
									aria-haspopup="true" aria-expanded="false">
								<i class="glyphicon glyphicon-list-alt"></i> Gerenciar <span
									class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<?php if ($this->session->userdata('nivel') != 11): ?>
									<li><a href="<?= site_url('icom/colaboradores'); ?>"><i
												class="glyphicon glyphicon-list text-primary"></i> Colaboradores</a>
									</li>
								<?php endif; ?>
								<li><a href="apontamento_contratos"><i
											class="glyphicon glyphicon-list text-primary"></i> Contratos</a>
								</li>
								<li><a href="javascript:void(0)" onclick="edit_posto();"><i
											class="glyphicon glyphicon-list text-primary"></i> Postos</a>
								</li>
								<li role="separator" class="divider"></li>
								<li><a href="javascript:;" onclick="iniciar_mes();"><i
											class="glyphicon glyphicon-plus text-info"></i> Iniciar
										mês</a></li>
								<li><a href="javascript:;" onclick="limpar_mes();"><i
											class="glyphicon glyphicon-minus text-danger"></i> Limpar
										mês</a>
								</li>
								<li><a href="javascript:void(0)" onclick="preparar_novo_alocado();"><i
											class="glyphicon glyphicon-plus text-info"></i> Alocar novo(s)
										colaborador(es)</a>
								</li>
								<li><a href="eventos"><i class="glyphicon glyphicon-list-alt text-primary"></i>
										Relatório de eventos</a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-6 right">
						<p class="bg-info text-info" style="padding: 5px;">
							<button style="float: right; margin: 12px 8px 0;" title="Pesquisa avançada"
									class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal_filtro">
								<i class="glyphicon glyphicon-filter"></i> <span class="hidden-xs">Filtrar</span>
							</button>
							<span>
                                <small>&emsp;<strong>Departamento:</strong> <span
										id="alerta_depto"><?= empty($depto_atual) ? 'Todos' : $deptos[$depto_atual] ?></span></small><br>
                                <small>&emsp;<strong>Área:</strong> <span
										id="alerta_area"><?= empty($area_atual) ? 'Todos' : $areas[$area_atual] ?></span></small><br>
                                <small>&emsp;<strong>Setor:</strong> <span
										id="alerta_setor"><?= empty($setor_atual) ? 'Todos' : $setores[$setor_atual] ?></span></small><br>
                                </span>
						</p>
					</div>
				</div>

				<div class="panel panel-default">
					<!-- Default panel contents -->
					<div class="panel-heading">
						<span id="mes_ano"><?= $meses[date('m')] . ' ' . date('Y') ?></span>
						<div style="float:right; margin-top: -0.5%;">
							<button id="mes_anterior" title="Mês anterior" class="btn btn-info btn-sm"
									onclick="mes_anterior()">
								<i class="glyphicon glyphicon-arrow-left"></i> <span class="hidden-xs hidden-sm">Mês anterior</span>
							</button>
							<div class="btn-group">
								<button type="button" class="btn btn-primary btn-sm dropdown-toggle"
										data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Opções do mês <span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<!--<li><a href="javascript:void(0);" onclick="add_mes()"><i
													class="glyphicon glyphicon-import text-success"></i> Alocar
											mês</a></li>
									<li><a href="javascript:void(0);" onclick="excluir_mes()"><i
													class="glyphicon glyphicon-erase text-danger"></i> Limpar
											mês</a></li>
									<li><a href="#" data-toggle="modal" data-target="#modal_colaborador"><i
													class="glyphicon glyphicon-plus text-info"></i> Alocar
											novo colaborador</a></li>-->
									<li><a href="javascript:void(0)" onclick="relatorio_feedback_mensal();"><i
												class="glyphicon glyphicon-list text-info"></i>
											Relatório de Feedback</a></li>
									<li><a href="javascript:void(0)" onclick="mapaCarregamentoDeOS();"><i
												class="glyphicon glyphicon-list text-info"></i>
											Rel. Mapa de Carregamento de O.S.</a></li>
									<li><a href="javascript:void(0)" onclick="medicao();"><i
												class="glyphicon glyphicon-list text-info"></i> Relatório de
											Medição Mensal</a></li>
									<li><a href="javascript:void(0)" onclick="pagamentoPrestadores();"><i
												class="glyphicon glyphicon-list text-info"></i> Relatório
											consolidado de Pagamento</a></li>
									<li><a href="javascript:void(0)" onclick="faturamentoConsolidado();"><i
												class="glyphicon glyphicon-list text-info"></i> Relatório
											consolidado de Faturamento</a></li>
								</ul>
							</div>
							<button title="Mês seguinte" id="mes_seguinte" class="btn btn-info btn-sm"
									onclick="mes_seguinte()">
								<span class="hidden-xs hidden-sm">Mês seguinte</span> <i
									class="glyphicon glyphicon-arrow-right"></i>
							</button>
						</div>
					</div>
					<div class="panel-body">

						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" style="font-size: 13px; font-weight: bolder" class="active"><a
									href="#apontamento" aria-controls="apontamento" role="tab"
									data-toggle="tab">Apontamentos</a></li>
							<li role="presentation" style="font-size: 13px; font-weight: bolder"><a
									href="#totalizacao" aria-controls="totalizacao" role="tab"
									data-toggle="tab">Totalizacao</a></li>
							<li role="presentation" style="font-size: 13px; font-weight: bolder"><a
									href="#avaliacao_performance" aria-controls="colaboradores" role="tab"
									data-toggle="tab">Avaliação Performance</a></li>
						</ul>

						<div class="tab-content" style="border: 1px solid #ddd; border-top-width: 0;">
							<div role="tabpanel" class="tab-pane active" id="apontamento">
								<br>
								<table id="table"
									   class="table table-hover table-striped table_apontamento table-condensed table-bordered"
									   cellspacing="0" width="100%">
									<thead>
									<tr>
										<th colspan="2" class="warning text-center">Colaborador(a)</th>
										<th rowspan="2" class="warning">Horario</th>
										<th rowspan="2" class="warning">Regime<br>contratação</th>
										<th rowspan="2" class="warning">Banco<br>horas</th>
										<td colspan="31" class="date-width" id="dias"><strong>Dias</strong></td>
									</tr>
									<tr>
										<th class="warning" style="padding-right: 5px;">Ação</th>
										<th class="warning">Nome</th>
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
								<br>
							</div>

							<div role="tabpanel" class="tab-pane" id="totalizacao">
								<br>
								<div class="row" style="margin: 0 2px;">
									<div class="col-sm-3 col-sm-offset-9 text-right">
										<button type="button" class="btn btn-sm btn-info"
												id="pdfTotalizacao"
												onclick="imprimir_totalizacao();"><i
												class="glyphicon glyphicon-print"></i> Imprimir
										</button>
									</div>
								</div>
								<table id="table_totalizacao"
									   class="table table-hover table-striped table-condensed table-bordered"
									   cellspacing="0" width="100%">
									<thead>
									<tr>
										<th rowspan="2" class="warning">Nome colaborador</th>
										<th colspan="3" class="text-center">Horarios</th>
										<th colspan="5" class="text-center">Saldo mensal</th>
									</tr>
									<tr>
										<th class="text-center">Entrada</th>
										<th class="text-center">Saída</th>
										<th class="text-center">Banco horas</th>
										<th class="text-center">Positivo</th>
										<th class="text-center">Negativo</th>
										<th class="text-center">Total</th>
										<th class="text-center">Desconto em folha</th>
										<th class="text-center">Hora extra</th>
									</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								<br>
							</div>

							<div role="tabpanel" class="tab-pane" id="avaliacao_performance">
								<br>
								<div class="row" style="margin: 0 2px;">
									<div class="col-sm-3 col-sm-offset-9 text-right">
										<button type="button" class="btn btn-sm btn-info"
												id="pdfAvaliacaoPerformance"
												onclick="imprimir_avaliacao_performance();"><i
												class="glyphicon glyphicon-print"></i> Imprimir
										</button>
									</div>
								</div>
								<table id="table_avaliacao_performance"
									   class="table table-hover table-striped table_apontamento table-condensed table-bordered"
									   cellspacing="0" width="100%">
									<thead>
									<tr>
										<th class="warning">Intérpretes</th>
										<th colspan="2" class="warning text-center">Profissionalismo</th>
										<th colspan="5" class="warning text-center">Atendimento</th>
										<th colspan="5" class="warning text-center">Aspectos técnicos</th>
										<th rowspan="2" class="warning text-center" style="vertical-align: middle;">
											Média
										</th>
									</tr>
									<tr>
										<th class="warning">Nome</th>
										<th class="warning">Compromet.</th>
										<th class="warning">Pontualidade</th>
										<th class="warning">Script</th>
										<th class="warning">Simpatia</th>
										<th class="warning">Empatia</th>
										<th class="warning">Postura</th>
										<th class="warning">Ferramenta</th>
										<th class="warning">Tradutório</th>
										<th class="warning">Linguístico</th>
										<th class="warning">Neutralidade</th>
										<th class="warning">Discrição</th>
										<th class="warning">Fidelidade</th>
									</tr>
									<tr>
										<th class="warning text-nowrap">Igual ou abaixo da média (%)</th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
									</tr>
									<tr>
										<th class="warning text-nowrap">Acima da média (%)</th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
										<th class="active text-center"></th>
									</tr>
									<tr>
										<th class="warning">Média</th>
										<th class="active"></th>
										<th class="active"></th>
										<th class="active"></th>
										<th class="active"></th>
										<th class="active"></th>
										<th class="active"></th>
										<th class="active"></th>
										<th class="active"></th>
										<th class="active"></th>
										<th class="active"></th>
										<th class="active"></th>
										<th class="active"></th>
										<th class="active"></th>
									</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								<br>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- page end-->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_filtro" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Pesquisa avançada</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="busca" class="form-horizontal" autocomplete="off">
							<input type="hidden" name="id_empresa" value="<?= $empresa ?>">
							<div class="row">
								<div class="col-md-6">
									<label class="control-label">Filtrar por departamento</label>
									<?php echo form_dropdown('id_depto', $deptos, $depto_atual, 'onchange="filtrar_alocacao()" class="form-control input-sm"'); ?>
								</div>
								<div class="col-md-6">
									<label class="control-label">Filtrar por área</label>
									<?php echo form_dropdown('id_area', $areas, $area_atual, 'onchange="filtrar_alocacao();" class="form-control input-sm"'); ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label class="control-label">Filtrar por setor</label>
									<?php echo form_dropdown('id_setor', $setores, $setor_atual, 'class="form-control input-sm"'); ?>
								</div>
								<div class="col-md-2">
									<label class="control-label">Mês</label>
									<?php echo form_dropdown('mes', $meses, date('m'), 'class="form-control input-sm"'); ?>
								</div>
								<div class="col-md-2">
									<label class="control-label">Ano</label>
									<input name="ano" type="number" value="<?= date('Y') ?>" size="4"
										   class="form-control input-sm" placeholder="aaaa">
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSaveFiltro" onclick="filtrar()" class="btn btn-info"
								data-dismiss="modal">OK
						</button>
						<button type="button" id="limpar" class="btn btn-default">Limpar filtros</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_form" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Relatório de eventos</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form" class="form-horizontal" autocomplete="off">
							<input type="hidden" value="" name="id"/>
							<input type="hidden" value="" name="id_alocado"/>
							<input type="hidden" value="" name="data"/>
							<div class="row form-group">
								<label class="control-label col-md-2"><strong>Colaborador(a):<br>Data:</strong></label>
								<div class="col-md-6">
									<p id="colaborador_data" class="form-control-static"></p>
								</div>
								<div class="col-sm-4 text-right text-nowrap">
									<button type="button" class="btn btn-success" id="btnSaveEvento"
											onclick="save_evento();"> Salvar
									</button>
									<button type="button" class="btn btn-danger" id="btnLimparEvento"
											onclick="delete_evento();"> Excluir
									</button>
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
									</button>
								</div>
							</div>
							<hr>
							<div class="row form-group">
								<label class="control-label col-md-2">Tipo de evento <span
										class="text-danger">*</span></label>
								<div class="col col-md-3">
									<div class="radio">
										<label>
											<input type="radio" name="tipo_evento" value="FJ" checked>
											Falta com atestado próprio
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="tipo_evento" value="FN">
											Falta sem atestado
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="tipo_evento" value="FC">
											Falta combinada
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="tipo_evento" value="BH">
											Banco de horas
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="tipo_evento" value="ME">
											Multi-eventos
										</label>
									</div>
								</div>
								<div class="col col-md-3">
									<div class="radio">
										<label>
											<input type="radio" name="tipo_evento" value="AJ">
											Atraso com atestado próprio
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="tipo_evento" value="AS">
											Atraso sem atestado
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="tipo_evento" value="SJ">
											Saída antec. com atestado próprio
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="tipo_evento" value="SN">
											Saída antecipada sem atestado
										</label>
									</div>
								</div>
								<div class="col col-md-4">
									<div class="radio">
										<label>
											<input type="radio" name="tipo_evento" value="CO">
											Compensação (Trabalho dia de folga - MEI)
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="tipo_evento" value="EA">
											Entrada antecipada
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="tipo_evento" value="SP">
											Saída pós-horário
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="tipo_evento" value="HE">
											Hora extra (Trabalho dia de feriado - CLT)
										</label>
									</div>
								</div>
							</div>
							<br>
							<div class="row form-group">
								<label class="control-label col-md-2">Horário entrada</label>
								<div class="col-md-2">
									<input name="horario_entrada" type="text" value=""
										   class="form-control text-center hora" placeholder="hh:mm">
								</div>
								<label class="control-label col-md-2">Horário saída intervalo</label>
								<div class="col-md-2">
									<input name="horario_intervalo" type="text" value=""
										   class="form-control text-center hora" placeholder="hh:mm">
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-2">Horário retorno intervalo</label>
								<div class="col-md-2">
									<input name="horario_retorno" type="text" value=""
										   class="form-control text-center hora" placeholder="hh:mm">
								</div>
								<label class="control-label col-md-2">Horário saída</label>
								<div class="col-md-2">
									<input name="horario_saida" type="text" value=""
										   class="form-control text-center hora" placeholder="hh:mm">
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-2">Banco de horas</label>
								<div class="col-md-2">
									<input name="saldo_banco_horas" type="text" value=""
										   class="form-control text-center banco_horas" placeholder="hh:mm">
								</div>
								<label class="control-label col-md-2">Observações</label>
								<div class="col-md-5">
                                        <textarea name="observacoes" class="form-control"
												  rows="3"></textarea>
								</div>
							</div>
						</form>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_avaliado" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Avaliação de performance</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form_avaliado" class="form-horizontal" autocomplete="off">
							<input type="hidden" value="" name="id"/>
							<input type="hidden" value="" name="id_usuario"/>
							<div class="row form-group">
								<label
									class="control-label col-md-2"><strong>Colaborador(a):<br>Mês/ano:</strong></label>
								<div class="col-md-7">
									<p id="form_avaliado_dados" class="form-control-static"></p>
								</div>
								<div class="col-sm-3 text-right text-nowrap">
									<button type="button" class="btn btn-success" id="btnSaveAvaliado"
											onclick="save_avaliacao_performance();"> Salvar
									</button>
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
									</button>
								</div>
							</div>
							<hr>
							<div class="row form-group">
								<label class="control-label col-md-2"><strong>Legenda:</strong></label>
								<div class="col-md-9">
									<p class="form-control-static"><?= $nivel_performance; ?>.</p>
								</div>
							</div>
							<br>
							<div class="row form-group">
								<div class="col-md-2 col-md-offset-1">
									<label class="control-label">Comprometimento</label>
									<select name="comprometimento" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Simpatia</label>
									<select name="simpatia" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Script</label>
									<select name="script" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Pontualidade</label>
									<select name="pontualidade" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Empatia</label>
									<select name="empatia" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-2 col-md-offset-1">
									<label class="control-label">Postura</label>
									<select name="postura" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Ferramenta</label>
									<select name="ferramenta" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Tradutório</label>
									<select name="tradutorio" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Linguístico</label>
									<select name="linguistico" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Neutralidade</label>
									<select name="neutralidade" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-2 col-md-offset-1">
									<label class="control-label">Discrição</label>
									<select name="discricao" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Fidelidade</label>
									<select name="fidelidade" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Extra 1</label>
									<select name="extra_1" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Extra 2</label>
									<select name="extra_2" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label">Extra 3</label>
									<select name="extra_3" class="form-control">
										<option value="">selecione...</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
							</div>
							<hr>
							<div class="row form-group">
								<div class="col-sm-8 col-sm-offset-3">
									<label class="radio-inline">
										<input type="radio" name="tipo_feedback" id="feedback_novo1" value="0" checked>
										Novo feedback
									</label>
									<label class="radio-inline">
										<input type="radio" name="tipo_feedback" id="feedback_existente1" value="1">
										Ver/editar feedbacks
										existentes
									</label>
								</div>
							</div>
							<div class="row form-group feedback_novo">
								<label class="control-label col-md-3">Colaborador(a) orientador(a)</label>
								<div class="col-md-5">
									<input type="text" name="nome_usuario_orientador" class="form-control feedback">
								</div>
								<label class="control-label col-md-1">Data</label>
								<div class="col-md-2">
									<input type="text" name="data_feedback"
										   class="form-control text-center data feedback"
										   placeholder="dd/mm/aaaa">
								</div>
							</div>
							<div class="row form-group feedback_existente" style="display: none;">
								<label class="control-label col-md-3">Colaborador(a) orientador(a)</label>
								<div class="col-md-8">
									<select name="id_feedback" class="form-control feedback"
											onchange="selecionar_feedback_avaliado(this);">
										<option value="">selecione...</option>
									</select>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-10 col-md-offset-1">
									<label class="control-label">Feedback repassado + Plano de ações de melhoria</label>
									<textarea name="descricao" class="form-control descritivo_feedback feedback"
											  rows="5"></textarea>
								</div>
							</div>
						</form>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_banco_horas" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Ajuste de Banco de Horas</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form_banco_horas" class="form-horizontal" autocomplete="off">
							<input type="hidden" value="" name="id_usuario"/>
							<div class="row form-group">
								<label class="control-label col-md-5"><strong>Colaborador(a):<br>Banco de horas na
										data atual:</strong></label>
								<div class="col-md-6">
									<p id="form_banco_horas_dados" class="form-control-static"></p>
								</div>
							</div>
							<hr>
							<div class="row form-group">
								<label class="control-label col-md-3">Banco de horas</label>
								<div class="col-md-3">
									<input type="text" name="banco_horas" class="form-control text-center horas"
										   placeholder="hhh:mm">
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-success" id="btnSaveBancoHoras"
								onclick="save_banco_horas();">
							Salvar
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_posto" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Editar posto</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form_posto" class="form-horizontal" autocomplete="off">
							<input type="hidden" value="" name="id"/>
							<div class="row form-group">
								<label class="control-label col-md-2">Departamento</label>
								<div class="col-md-8">
									<select name="id_depto" class="form-control posto_estrutura"
											onchange="filtrar_posto_estrutura(this)">
										<option value="">selecione...</option>
									</select>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-2">Área</label>
								<div class="col-md-8">
									<select name="id_area" class="form-control posto_estrutura"
											onchange="filtrar_posto_estrutura(this)">
										<option value="">selecione...</option>
									</select>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-2">Setor</label>
								<div class="col-md-8">
									<select name="id_setor" class="form-control posto_estrutura"
											onchange="filtrar_posto_estrutura(this)">
										<option value="">selecione...</option>
									</select>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-2">Colaborador(a)</label>
								<div class="col-md-8">
									<select name="id_usuario" class="form-control posto_estrutura"
											onchange="filtrar_alocado();">
										<option value="">selecione...</option>
									</select>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-2">Função</label>
								<div class="col-md-8">
									<select name="id_funcao" class="form-control posto_estrutura">
										<option value="">selecione...</option>
									</select>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-2">Matrícula</label>
								<div class="col-md-4">
									<input type="text" name="matricula" class="form-control">
								</div>
								<label class="control-label col-md-2">CLT/MEI</label>
								<div class="col-md-2">
									<select name="categoria" class="form-control">
										<option value="">selecione...</option>
										<option value="CLT">CLT</option>
										<option value="MEI">MEI</option>
									</select>
								</div>
							</div>
							<hr>
							<h5>Colaborador MEI</h5>
							<div class="row form-group">
								<label class="control-label col-md-2">Valor hora colaborador</label>
								<div class="col-md-3" style="width: 200px;">
									<div class="input-group">
										<span class="input-group-addon" id="basic-addon1">R$</span>
										<input name="valor_hora_mei" type="text" class="form-control valor mei"
											   aria-describedby="basic-addon1">
									</div>
									<span class="help-block"></span>
								</div>
								<label class="control-label col-md-2">Qtde. horas/mês</label>
								<div class="col-md-2" style="width: 120px;">
									<input name="qtde_horas_mei" type="text"
										   class="form-control text-center hora_mes mei"
										   placeholder="hhh:mm">
									<span class="help-block"></span>
								</div>
								<label class="control-label col-md-2">Qtde. horas/dia</label>
								<div class="col-md-2" style="width: 120px;">
									<input name="qtde_horas_dia_mei" type="text"
										   class="form-control text-center hora mei"
										   placeholder="hhh:mm">
									<span class="help-block"></span>
								</div>
							</div>
							<hr>
							<h5>Colaborador CLT</h5>
							<div class="row form-group">
								<label class="control-label col-md-2">Valor remuneração mensal</label>
								<div class="col-md-3" style="width: 200px;">
									<div class="input-group">
										<span class="input-group-addon" id="basic-addon1">R$</span>
										<input name="valor_mes_clt" type="text" class="form-control valor clt"
											   aria-describedby="basic-addon1">
									</div>
									<span class="help-block"></span>
								</div>
								<label class="control-label col-md-2">Qtde. horas/mês</label>
								<div class="col-md-2" style="width: 120px;">
									<input name="qtde_meses_clt" type="text"
										   class="form-control text-center hora_mes clt"
										   placeholder="hh:mm">
									<span class="help-block"></span>
								</div>
								<label class="control-label col-md-2">Qtde. horas/dia</label>
								<div class="col-md-2" style="width: 120px;">
									<input name="qtde_horas_dia_clt" type="text"
										   class="form-control text-center hora clt"
										   placeholder="hh:mm">
									<span class="help-block"></span>
								</div>
							</div>
							<hr>
							<div class="row form-group">
								<label class="control-label col-md-3">Horário entrada</label>
								<div class="col-md-2">
									<input name="horario_entrada" type="text" value=""
										   class="form-control text-center hora" placeholder="hh:mm">
								</div>
								<label class="control-label col-md-3">Horário saída intervalo</label>
								<div class="col-md-2">
									<input name="horario_intervalo" type="text" value=""
										   class="form-control text-center hora" placeholder="hh:mm">
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-3">Horário entrada intervalo</label>
								<div class="col-md-2">
									<input name="horario_retorno" type="text" value=""
										   class="form-control text-center hora" placeholder="hh:mm">
								</div>
								<label class="control-label col-md-3">Horário saída</label>
								<div class="col-md-2">
									<input name="horario_saida" type="text" value=""
										   class="form-control text-center hora" placeholder="hh:mm">
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-success" id="btnSavePosto" onclick="save_posto();">
							Salvar
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_alocados" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Alocar novo(s) colaborador(es)</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form_alocados" class="form-horizontal" autocomplete="off">
							<input type="hidden" value="" name="id_alocacao"/>
							<div class="row form-group">
								<div class="col-md-12">
									<?php echo form_multiselect('id_usuario[]', [], [], 'id="alocados" class="demo1" size="8"'); ?>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-success" id="btnSaveAlocados"
								onclick="save_novo_alocado();"> Salvar
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_feedback" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Gerenciar feedbacks do colaborador</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form_feedback" class="form-horizontal" autocomplete="off">
							<input type="hidden" name="id_usuario" value="">
							<div class="row form-group">
								<label class="control-label col-md-3"><strong>Colaborador(a)
										orientado(a):</strong></label>
								<div class="col-md-4">
									<p class="form-control-static" id="feedback_nome_usuario"></p>
								</div>
								<div class="col-md-5 text-right">
									<button type="button" class="btn btn-info" id="btnImprimirFeedback"
											onclick="imprimir_feedback();"><i class="fa fa-print"></i> Imprimir
									</button>
									<button type="button" class="btn btn-success" id="btnSaveFeedback"
											onclick="save_feedback();"> Salvar
									</button>
									<button type="button" class="btn btn-danger" id="btnLimparFeedback"
											onclick="excluir_feedback();"> Excluir
									</button>
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
									</button>
								</div>
							</div>
							<hr>
							<div class="row form-group">
								<div class="col-sm-8 col-sm-offset-3">
									<label class="radio-inline">
										<input type="radio" name="tipo" id="feedback_novo" value="0" checked>
										Novo feedback
									</label>
									<label class="radio-inline">
										<input type="radio" name="tipo" id="feedback_existente" value="1">
										Ver/editar feedbacks
										existentes
									</label>
								</div>
							</div>
							<div class="row form-group feedback_novo">
								<label class="control-label col-md-3">Colaborador(a) orientador(a)</label>
								<div class="col-md-5">
									<input type="text" name="nome_usuario_orientador" class="form-control">
								</div>
								<label class="control-label col-md-1">Data</label>
								<div class="col-md-2">
									<input type="text" name="data" class="form-control text-center data"
										   placeholder="dd/mm/aaaa">
								</div>
							</div>
							<div class="row form-group feedback_existente" style="display: none;">
								<label class="control-label col-md-3">Colaborador(a) orientador(a)</label>
								<div class="col-md-8">
									<select name="id" class="form-control" onchange="selecionar_feedback(this);">
										<option value="">selecione...</option>
									</select>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-3">Feedback repassado + Plano de ações de
									melhoria</label>
								<div class="col-md-8">
									<textarea name="descricao" class="form-control descritivo_feedback"
											  rows="5"></textarea>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-3">Resultado do feedback/Plano de
									ações</label>
								<div class="col-md-8">
									<textarea name="resultado" class="form-control descritivo_feedback"
											  rows="5"></textarea>
								</div>
							</div>
						</form>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_feedback_mensal" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Relatório de Feedback</h3>
					</div>
					<div class="modal-body form">
						<div>
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#feedback_folha_assinaturas"
																		  aria-controls="home" role="tab"
																		  data-toggle="tab">Folha de assinaturas</a>
								</li>
								<li role="presentation"><a href="#feedback_coleta_assinatura" aria-controls="profile"
														   role="tab" data-toggle="tab">Folha coleta assinaturas</a>
								</li>
							</ul>
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="feedback_folha_assinaturas">
									<hr style="margin-top: 0px;">
									<div id="relatorio_feedback_mensal" class="row">

									</div>
								</div>
								<div role="tabpanel" class="tab-pane" id="feedback_coleta_assinatura">
									<form action="#" id="form_feedback_mensal" class="form-horizontal">
										<div class="form-body">
											<div class="row form-group">
												<label class="control-label col-sm-4">Arquivo de
													assinatura (<span id="mes_ano_arquivo_feedback"></span>)</label>
												<div class="col-sm-6 controls">
													<div class="fileinput fileinput-new input-group"
														 data-provides="fileinput">
														<div class="form-control" data-trigger="fileinput">
															<i class="glyphicon glyphicon-file fileinput-exists"></i>
															<span class="fileinput-filename"></span>
														</div>
														<div class="input-group-addon btn btn-default btn-file">
															<span class="fileinput-new">Selecionar arquivo</span>
															<span class="fileinput-exists">Alterar</span>
															<input type="file" name="nome_arquivo" accept=".pdf"/>
														</div>
														<a href="#"
														   class="input-group-addon btn btn-default fileinput-exists"
														   data-dismiss="fileinput">Remover</a>
													</div>
													<i class="help-block">Somente arquivos .pdf</i>
												</div>
												<div class="col-sm-2">
													<button type="button" class="btn btn-success">
														<i class="fa fa-upload"></i> Importar
													</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

	</section>
</section>
<!--main content end-->

<?php require_once APPPATH . 'views/end_js.php'; ?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">
<link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
	$(document).ready(function () {
		document.title = 'CORPORATE RH - LMS - Gestão Operacional ICOM';
	});
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
<script
	src="<?php echo base_url('assets/js/jquery.fileDownload-master/src/Scripts/jquery.fileDownload.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/ckeditor/adapters/jquery.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.maskMoney.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

<script>

	var table, table_totalizacao, table_avaliacao_performance;
	var busca, save_method, demo1;
	var edicaoEvento = true;

	$('.data').mask('00/00/0000');
	$('.hora').mask('00:00');
	$('.banco_horas').mask('Z00:00', {
		'translation': {
			'Z': {
				'pattern': /[\-\+]/, 'optional': true
			}
		}
	});
	$('.hora_mes').mask('#00:00', {'reverse': true});
	$('.horas2').mask('Z###00:00', {
		'reverse': true,
		'translation': {
			':': {
				'pattern': /:-/, 'optional': true
			},
			'0': {
				'pattern': /[0-9]-/, 'optional': true
			},
			'#': {
				'pattern': /[0-9]-/, 'optional': true
			},
			'Z': {
				'pattern': /-/, 'optional': true
			}
		}
	});
	$('.valor').mask('##.###.##0,00', {'reverse': true});

	$('.descritivo_feedback').ckeditor({
		'height': '150',
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
			// {
			// 	'name': 'insert',
			// 	'groups': ['insert']
			// },
			// {
			// 	'name': 'styles',
			// 	'groups': ['styles']
			// },
			{
				'name': 'about',
				'groups': ['about']
			}
		],
		// Remove the redundant buttons from toolbar groups defined above.
		'removeButtons': 'Underline,Strike,Subscript,Superscript,NewPage,Preview,Save,Anchor,Styles,Specialchar,Flash,PageBreak,Slideshow'
	});

	$(document).ready(function () {
		busca = $('#busca').serialize();
		var language = "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>";


		table = $('#table').DataTable({
			'processing': true,
			'serverSide': true,
			'order': [1, 'asc'],
			'iDisplayLength': -1,
			'lengthMenu': [[5, 10, 25, 50, 100, 500, -1], [5, 10, 25, 50, 100, 500, 'Todos']],
			'language': {
				'url': language
			},
			'ajax': {
				'url': '<?php echo site_url('icom/apontamento/listarEventos') ?>',
				'type': 'POST',
				'timeout': 90000,
				'data': function (d) {
					d.busca = busca;
					return d;
				},
				'dataSrc': function (json) {
					$('#mes_ano').html(json.calendar.mes_ano[0].toUpperCase() + json.calendar.mes_ano.slice(1));

					var dt1 = new Date();
					var dt2 = new Date();
					dt2.setFullYear(json.calendar.ano, (json.calendar.mes - 1));

					var semana = 1;
					var colunasUsuario = 4;
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
						$('#dias').html('<strong>Dias</strong> (clique em um dia do mês para replicar/limpar feriados ou emendas de feriados)');
					} else {
						$('#dias').html('<strong>Dias</strong>');
					}
					if (json.draw === 1) {
						$("#legenda").html('<button title="Mostrar legenda de eventos" data-toggle="modal" data-target="#modal_legenda" style="margin: 15px 10px 0;" class="btn btn-default btn-sm">' +
							'<i class="glyphicon glyphicon-exclamation-sign"></i> <span class="hidden-xs"> Mostrar legenda de eventos</span>' +
							'</button>');
					}
					return json.data;
				}
			},
			'columnDefs': [
				{
					'createdCell': function (td, cellData, rowData, row, col) {
						$(td).addClass('evento').css({
							'cursor': 'pointer',
							'vertical-align': 'middle'
						}).on('click', function () {
							edit_avaliacao_performance(rowData[36]);
						});
						$(td).html('<a>' + rowData[col] + '</a>');
					},
					'width': '100%',
					'targets': [1]
				},
				{
					'className': 'text-center',
					'targets': [2]
				},
				{
					'className': 'text-center',
					'orderable': false,
					'searchable': false,
					'targets': [0]
				},
				{
					'className': 'text-center',
					'targets': [3]
				},
				{
					'createdCell': function (td, cellData, rowData, row, col) {
						$(td).addClass('evento').css({
							'cursor': 'pointer',
							'vertical-align': 'middle'
						}).on('click', function () {
							edit_banco_horas(rowData[36]);
						});
						$(td).html('<a>' + rowData[col] + '</a>');
					},
					'className': 'text-center text-nowrap',
					'targets': [4]
				},
				{
					'createdCell': function (td, cellData, rowData, row, col) {
						if (rowData[col]) {
							$(td).css({'color': '#fff', 'background-color': '#47a447'});
						} else if ($(table.column(col).header()).hasClass('text-danger')) {
							$(td).css('background-color', '#e9e9e9');
						}

						$(td).popover({
							'container': 'body',
							'placement': 'auto bottom',
							'trigger': 'hover',
							'content': function () {
								if (rowData[col].length === 0) {
									return '<span style="color: #aaa;">Vazio</span>';
								} else {
									return '<strong>Eventos cadastrados:</strong> ' + rowData[col]['tipo_evento'];
								}
							},
							'html': true
						});
						$(td).addClass('evento').css({
							'cursor': 'pointer',
							'vertical-align': 'middle'
						}).on('click', function () {
							$(td).popover('hide');
							edit_evento(rowData[36], col - 4);
						});
						$(td).html(rowData[col]['tipo_evento']);
					},
					'className': 'text-center',
					'orderable': false,
					'searchable': false,
					'targets': 'date-width'
				}
			]
		});

		table_totalizacao = $('#table_totalizacao').DataTable({
			'processing': true,
			'serverSide': true,
			'order': [0, 'asc'],
			'iDisplayLength': -1,
			'lengthMenu': [[5, 10, 25, 50, 100, 500, -1], [5, 10, 25, 50, 100, 500, 'Todos']],
			'language': {
				'url': language
			},
			'ajax': {
				'url': '<?php echo site_url('icom/apontamento/listarTotalizacoes') ?>',
				'type': 'POST',
				'timeout': 90000,
				'data': function (d) {
					d.busca = busca;
					return d;
				}
			},
			'columnDefs': [
				{
					'width': '100%',
					'targets': [0]
				},
				{
					'className': 'text-center',
					'searchable': false,
					'targets': [1, 2, 3, 4, 5, 6, 7, 8]
				}
			],
			'preDrawCallback': function () {
				$('#pdfTotalizacao').prop('disabled', ($('#busca [name="id_depto"]').val() === '' || $('#busca [name="id_area"]').val() === '' || $('#busca [name="id_setor"]').val() === ''));
			}
		});

		table_avaliacao_performance = $('#table_avaliacao_performance').DataTable({
			'processing': true,
			'serverSide': true,
			'order': [0, 'asc'],
			'iDisplayLength': -1,
			'lengthMenu': [[5, 10, 25, 50, 100, 500, -1], [5, 10, 25, 50, 100, 500, 'Todos']],
			'language': {
				'url': language
			},
			'ajax': {
				'url': '<?php echo site_url('icom/apontamento/listarAvaliacoesPerformance') ?>',
				'type': 'POST',
				'timeout': 90000,
				'data': function (d) {
					d.busca = busca;
					return d;
				},
				'dataSrc': function (json) {
					for (i = 0; i <= 12; i++) {
						$(table_avaliacao_performance.context[0].aoHeader[2][i + 1].cell).html(json.abaixo_media[i]);
						$(table_avaliacao_performance.context[0].aoHeader[3][i + 1].cell).html(json.acima_media[i]);
						$(table_avaliacao_performance.column(i + 1).header()).html(json.media[i]);
					}

					return json.data;
				}
			},
			'columnDefs': [
				{
					'createdCell': function (td, cellData, rowData, row, col) {
						$(td).css({
							'cursor': 'pointer'
						}).on('click', function () {
							edit_feedback(rowData[14]);
						});
						$(td).html('<a>' + rowData[col] + '</a>');
					},
					'width': '100%',
					'targets': [0]
				},
				{
					'createdCell': function (td, cellData, rowData, row, col) {
						if (rowData[col] !== null) {
							$(td).css({
								'color': rowData[col] < table_avaliacao_performance.context[0].json.media_real[col - 1] ? '#d9534f' : '#5cb85c'
							}).html('<strong>' + (rowData[col]).toLocaleString('pt-BR', {
								'minimumFractionDigits': 2,
								'maximumFractionDigits': 2
							}) + '</strong>');
						}
					},
					'className': 'text-center',
					'orderable': false,
					'targets': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
				}
			],
			'preDrawCallback': function () {
				$('#pdfAvaliacaoPerformance').prop('disabled', ($('#busca [name="id_depto"]').val() === '' || $('#busca [name="id_area"]').val() === '' || $('#busca [name="id_setor"]').val() === ''));
			}
		});

		demo1 = $('.demo1').bootstrapDualListbox({
			'nonSelectedListLabel': 'Colaboradores disponíveis',
			'selectedListLabel': 'Colaboradores selecionados',
			'preserveSelectionOnMove': 'moved',
			'moveOnSelect': false,
			'filterPlaceHolder': 'Filtrar',
			'helperSelectNamePostfix': false,
			'selectorMinimalHeight': 132,
			'infoText': false
		});

	});


	$('#form [name="tipo_evento"]').on('change', function () {
		$('#form [name="saldo_banco_horas"]').prop('readonly', $('#form [name="tipo_evento"]:checked').val() !== 'BH');
	});


	$('#form_feedback [name="tipo"]').on('change', function () {
		var tipo_feedback = $('#form_feedback [name="tipo"]:checked').val();
		$('#form_feedback')[0].reset();
		$('#btnImprimirFeedback, #btnLimparFeedback').hide();
		$('#form_feedback [name="tipo"][value="' + tipo_feedback + '"]').prop('checked', true);
		if (tipo_feedback === '0') {
			$('#form_feedback .feedback_novo').show();
			$('#form_feedback .feedback_existente').hide();
			$('#form_feedback [name="resultado"]').prop('disabled', true);
		} else if (tipo_feedback === '1') {
			$('#form_feedback .feedback_novo').hide();
			$('#form_feedback .feedback_existente').show();
			$('#form_feedback [name="resultado"]').prop('disabled', false);
		}
	});


	$('#form_avaliado [name="tipo_feedback"]').on('change', function () {
		var tipo_feedback_avaliado = $('#form_avaliado [name="tipo_feedback"]:checked').val();
		$('#form_avaliado .feedback').val('');
		if (tipo_feedback_avaliado === '0') {
			$('#form_avaliado .feedback_novo').show();
			$('#form_avaliado .feedback_existente').hide();
		} else if (tipo_feedback_avaliado === '1') {
			$('#form_avaliado .feedback_novo').hide();
			$('#form_avaliado .feedback_existente').show();
		}
	});


	$('#form_posto [name="categoria"]').on('change', function () {
		$('#form_posto .clt, #form_posto .mei').prop('disabled', true);
		if (this.value === 'MEI') {
			$('#form_posto .mei').prop('disabled', false);
			$('#form_posto .clt').val('');
		} else if (this.value === 'CLT') {
			$('#form_posto .clt').prop('disabled', false);
			$('#form_posto .mei').val('');
		}
	});


	function filtrar_alocacao() {
		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/filtrarAlocacao/') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': $('#busca').serialize(),
			'beforeSend': function () {
				$('#busca [name="id_depto"]').prop('disabled', true);
				$('#busca [name="id_area"]').prop('disabled', true);
				$('#busca [name="id_setor"]').prop('disabled', true);
			},
			'success': function (json) {
				$('#busca [name="id_depto"]').prop('disabled', false);
				$('#busca [name="id_area"]').html($(json.areas).html()).prop;
				$('#busca [name="id_setor"]').html($(json.setores).html());
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			},
			'complete': function () {
				$('#busca [name="id_depto"]').prop('disabled', false);
				$('#busca [name="id_area"]').prop('disabled', false);
				$('#busca [name="id_setor"]').prop('disabled', false);
			}
		});
	}


	function filtrar_posto_estrutura() {
		$.ajax({
			'url': '<?php echo site_url('icom/postos/montarEstrutura') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': $('#form_posto .posto_estrutura').serialize(),
			'beforeSend': function () {
				$('.posto_estrutura').prop('disabled', true);
			},
			'success': function (json) {
				$('#form_posto [name="id_area"]').html($(json.areas).html());
				$('#form_posto [name="id_setor"]').html($(json.setores).html());
				$('#form_posto [name="id_usuario"]').html($(json.usuarios).html());
				$('#form_posto [name="id_funcao"]').html($(json.funcoes).html());
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			},
			'complete': function () {
				$('.posto_estrutura').prop('disabled', false);
			}
		});
	}


	function filtrar_alocado() {
		$.ajax({
			'url': '<?php echo site_url('icom/postos/editarColaboradorAlocado') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': $('#form_posto .posto_estrutura, #busca [name="mes"], #busca [name="ano"]').serialize(),
			'beforeSend': function () {
				$('.posto_estrutura').prop('disabled', true);
			},
			'success': function (json) {
				$('#form_posto [name="id"]').val('');
				$('#form_posto [name="id_funcao"]').val('');
				$('#form_posto [name="valor_hora_mei"]').val('');
				$('#form_posto [name="valor_mes_clt"]').val('');
				$('#form_posto [name="qtde_horas_mei"]').val('');
				$('#form_posto [name="qtde_meses_clt"]').val('');
				$('#form_posto [name="horario_entrada"]').val('');
				$('#form_posto [name="horario_intervalo"]').val('');
				$('#form_posto [name="horario_retorno"]').val('');
				$('#form_posto [name="horario_saida"]').val('');
				$.each(json, function (key, value) {
					$('#form_posto [name="' + key + '"]').val(value);
				});

				$('#form_posto [name="categoria"]').trigger('change');
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			},
			'complete': function () {
				$('.posto_estrutura').prop('disabled', false);
			}
		});
	}


	$('#limpar').on('click', function () {
		$.each(busca.split('&'), function (index, elem) {
			var vals = elem.split('=');
			if (vals[0] === 'mes' || vals[0] === 'ano') {
				$("[name='" + vals[0] + "']").val(vals[1]);
			} else {
				$("[name='" + vals[0] + "']").val($("[name='" + vals[0] + "'] option:first").val());
			}
		});

		filtrar_alocacao();
	});


	function mes_anterior() {
		$('#mes_anterior, #mes_seguinte').prop('disabled', true).hover();

		var dt = moment({
			'year': $('#busca [name="ano"]').val(),
			'month': $('#busca [name="mes"]').val() - 1,
			'day': 1
		});

		dt.subtract(1, 'month');

		$('#busca [name="mes"]').val(moment(dt).format('MM'));
		$('#busca [name="ano"]').val(dt.year());

		busca = $('#busca').serialize();
		reload_table(true);
		$('#mes_anterior, #mes_seguinte').prop('disabled', false);
	}


	function mes_seguinte() {
		if ($('#mes_seguinte').hasClass('disabled')) {
			return false;
		}

		$('#mes_anterior, #mes_seguinte').prop('disabled', true).hover();

		var dt = moment({
			'year': $('#busca [name="ano"]').val(),
			'month': $('#busca [name="mes"]').val() - 1,
			'day': 1
		});

		dt.add(1, 'month');

		$('#busca [name="mes"]').val(moment(dt).format('MM'));
		$('#busca [name="ano"]').val(dt.year());

		busca = $('#busca').serialize();
		reload_table(true);
		$('#mes_anterior, #mes_seguinte').prop('disabled', false);
	}


	function filtrar() {
		var data_busca = {
			'year': $('#busca [name="ano"]').val(),
			'month': $('#busca [name="mes"]').val() - 1,
			'day': 1
		};
		var data_proximo_mes = moment(data_busca).add(1, 'month');

		busca = $('#busca').serialize();
		reload_table();
		if (moment(data_proximo_mes).isBefore(data_busca)) {
			$('[name="mes"]').val(moment(data_proximo_mes).format('MM'));
			$('[name="ano"]').val(data_proximo_mes.year());
		}
		$('#alerta_depto').text($('#busca [name="id_depto"] option:selected').html());
		$('#alerta_area').text($('#busca [name="id_area"] option:selected').html());
		$('#alerta_setor').text($('#busca [name="id_setor"] option:selected').html());
	}


	function alocacao_filtrada() {
		return busca.split('&').every(function (e) {
			return e.indexOf('=') < (e.length - 1);
		});
	}


	function iniciar_mes() {
		if (alocacao_filtrada() === false) {
			alert('Para iniciar o mês, ajuste os filtros de Departamento, Área e Setor.');
			return false;
		}

		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/alocarNovoMes') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': busca,
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
				} else {
					alert('Mês alocado com sucesso.');
					reload_table();
				}
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}


	function limpar_mes() {
		if (alocacao_filtrada() === false) {
			alert('Para limpar o mês, ajuste os filtros de Departamento, Área e Setor.');
			return false;
		}

		if (confirm('Deseja limpar o mês?')) {
			$.ajax({
				'url': '<?php echo site_url('icom/apontamento/desalocarMes') ?>',
				'type': 'POST',
				'dataType': 'json',
				'data': busca,
				'success': function (json) {
					if (json.erro) {
						alert(json.erro);
					} else {
						alert('Mês desalocado com sucesso.');
						reload_table();
					}
				},
				'error': function (jqXHR, textStatus, errorThrown) {
					alert('Error get data from ajax');
				}
			});
		}
	}


	function edit_avaliacao_performance(id_alocado) {
		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/editarAvaliacaoPerformance') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': {'id_alocado': id_alocado},
			'beforeSend': function () {
				$('#form_avaliado')[0].reset();
				$('#form_avaliado [name="tipo_feedback"][value="0"]').prop('checked', true).trigger('change');
			},
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
					return false;
				}

				$.each(json, function (key, value) {
					if ($('#form_avaliado [name="' + key + '"]').prop('type') === 'radio') {
						$('#form_avaliado [name="' + key + '"][value="' + value + '"]').prop('checked', value !== null);
					} else {
						$('#form_avaliado [name="' + key + '"]').val(value);
					}
				});

				$('#form_avaliado_dados').html(json.dados);
				$('#form_avaliado [name="id_feedback"]').html($(json.id_feedback).html());

				$('#modal_avaliado').modal('show');
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}


	function edit_banco_horas(id_alocado) {
		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/editarBancoHoras') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': {'id_alocado': id_alocado},
			'beforeSend': function () {
				$('#form_banco_horas')[0].reset();
			},
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
					return false;
				}

				$.each(json, function (key, value) {
					if ($('#form_banco_horas [name="' + key + '"]').prop('type') === 'radio') {
						$('#form_banco_horas [name="' + key + '"][value="' + value + '"]').prop('checked', value !== null);
					} else {
						$('#form_banco_horas [name="' + key + '"]').val(value);
					}
				});

				$('#form_banco_horas_dados').html(json.dados);

				$('#modal_banco_horas').modal('show');
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}


	function edit_evento(id_alocado, dia) {
		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/editarEvento') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': {
				'id_alocado': id_alocado,
				'data': moment({
					'year': $('#busca [name="ano"]').val(),
					'month': $('#busca [name="mes"]').val() - 1,
					'day': dia
				}).format('YYYY-MM-DD')
			},
			'beforeSend': function () {
				$('#form')[0].reset();
			},
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
					return false;
				}

				$.each(json, function (key, value) {
					if ($('#form [name="' + key + '"]').prop('type') === 'radio') {
						$('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value !== null);
					} else {
						$('#form [name="' + key + '"]').val(value);
					}
				});

				if (json.id) {
					save_method = 'update';
					$('#modal_form .modal-title').text('Editar evento operacional');
					$('#btnLimparEvento').show();
				} else {
					save_method = 'add';
					$('#modal_form .modal-title').text('Adicionar evento operacional');
					$('#btnLimparEvento').hide();
				}

				$('#form [name="tipo_evento"]').trigger('change');
				$('#colaborador_data').html(json.colaborador_data);

				$('#modal_form').modal('show');
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}


	function edit_posto() {
		$('#form_posto')[0].reset();
		$('#form_posto [name="id"]').val('')

		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/editarPosto') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': busca,
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
					return false;
				}

				$.each(json, function (key, value) {
					$('#form_posto [name="' + key + '"]').val(value);
				});

				$('#form_posto [name="id_depto"]').html($(json.deptos).html());
				$('#form_posto [name="id_area"]').html($(json.areas).html());
				$('#form_posto [name="id_setor"]').html($(json.setores).html());
				$('#form_posto [name="id_usuario"]').html($(json.usuarios).html());
				$('#form_posto [name="id_funcao"]').html($(json.funcoes).html());


				$('#form_posto [name="categoria"]').trigger('change');

				$('#modal_posto').modal('show');
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}


	function preparar_novo_alocado() {
		if ($('#busca [name="id_depto"]').val() === '' || $('#busca [name="id_area"]').val() === '' ||
			$('#busca [name="id_setor"]').val() === '' || $('#busca [name="ano"]').val() === '') {
			alert('Para alocar um novo cloadorador, ajuste os filtros de Departamento, Área, Setor, Mês e Ano.');
			return false;
		}

		$('#form_alocados')[0].reset();

		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/prepararNovoAlocado') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': busca,
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
					return false;
				}

				$('#form_alocados [name="id_alocacao"]').val(json.id_alocacao);
				$('#alocados').html($(json.id_usuario).html());

				demo1.bootstrapDualListbox('refresh', true);

				$('#modal_alocados').modal('show');
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}


	function save_avaliacao_performance() {
		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/salvarAvaliacaoPerformance') ?>',
			'type': 'POST',
			'data': $('#form_avaliado').serialize(),
			'dataType': 'json',
			'beforeSend': function () {
				$('#btnSaveAvaliado').text('Salvando...').attr('disabled', true);
			},
			'success': function (json) {
				if (json.status) {
					$('#modal_avaliado').modal('hide');
					reload_table();
				} else if (json.erro) {
					alert(json.erro);
				}
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error adding / update data');
			},
			'complete': function () {
				$('#btnSaveAvaliado').text('Salvar').attr('disabled', false);
			}
		});
	}


	function save_banco_horas() {
		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/salvarBancoHoras') ?>',
			'type': 'POST',
			'data': $('#form_banco_horas').serialize(),
			'dataType': 'json',
			'beforeSend': function () {
				$('#btnSaveBancoHoras').text('Salvando...').attr('disabled', true);
			},
			'success': function (json) {
				if (json.status) {
					$('#modal_banco_horas').modal('hide');
					reload_table();
				} else if (json.erro) {
					alert(json.erro);
				}
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error adding / update data');
			},
			'complete': function () {
				$('#btnSaveBancoHoras').text('Salvar').attr('disabled', false);
			}
		});
	}


	function save_evento() {
		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/salvarEvento') ?>',
			'type': 'POST',
			'data': $('#form').serialize(),
			'dataType': 'json',
			'beforeSend': function () {
				$('#btnSaveEvento').text('Salvando...');
				$('#btnSaveEvento, #btnLimparEvento').attr('disabled', true);
			},
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
				$('#btnSaveEvento').text('Salvar');
				$('#btnSaveEvento, #btnLimparEvento').attr('disabled', false);
			}
		});
	}


	function save_posto() {
		$.ajax({
			'url': '<?php echo site_url('icom/postos/salvar') ?>',
			'type': 'POST',
			'data': $('#form_posto').serialize(),
			'dataType': 'json',
			'beforeSend': function () {
				$('#btnSavePosto').text('Salvando...').attr('disabled', true);
			},
			'success': function (json) {
				if (json.status) {
					$('#modal_posto').modal('hide');
					reload_table();
				} else if (json.erro) {
					alert(json.erro);
				}
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error adding / update data');
			},
			'complete': function () {
				$('#btnSavePosto').text('Salvar').attr('disabled', false);
			}
		});
	}


	function save_novo_alocado() {
		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/salvarNovoAlocado') ?>',
			'type': 'POST',
			'data': $('#form_alocados').serialize(),
			'dataType': 'json',
			'beforeSend': function () {
				$('#btnSaveAlocados').text('Salvando...').attr('disabled', true);
			},
			'success': function (json) {
				if (json.status) {
					$('#modal_alocados').modal('hide');
					reload_table();
				} else if (json.erro) {
					alert(json.erro);
				}
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error adding / update data');
			},
			'complete': function () {
				$('#btnSaveAlocados').text('Salvar').attr('disabled', false);
			}
		});
	}


	function delete_evento() {
		if (confirm('Deseja limpar o evento?')) {
			$.ajax({
				'url': '<?php echo site_url('icom/apontamento/excluirEvento') ?>',
				'type': 'POST',
				'dataType': 'json',
				'data': {
					'id': $('#form [name="id"]').val()
				},
				'beforeSend': function () {
					$('#btnLimparEvento').text('Excluindo...');
					$('#btnLimparEvento, #btnSaveEvento').attr('disabled', true);
				},
				'success': function (json) {
					if (json.status) {
						$('#modal_form').modal('hide');
						reload_table();
					} else if (json.erro) {
						alert(json.erro);
					}
				},
				'error': function (jqXHR, textStatus, errorThrown) {
					alert('Error deleting data');
				},
				'complete': function () {
					$('#btnLimparEvento').text('Excluir');
					$('#btnLimparEvento, #btnSaveEvento').attr('disabled', false);
				}
			});
		}
	}


	function delete_alocado(id) {
		if (confirm('Deseja desalocado o(a) colaborador(a)?')) {
			$.ajax({
				'url': '<?php echo site_url('icom/apontamento/excluirAlocado') ?>',
				'type': 'POST',
				'dataType': 'json',
				'data': {'id': id},
				'beforeSend': function () {
					$('#table tr td button.danger').attr('disabled', true);
				},
				'success': function (json) {
					if (json.status) {
						reload_table();
					} else if (json.erro) {
						alert(json.erro);
					}
				},
				'error': function (jqXHR, textStatus, errorThrown) {
					alert('Error deleting data');
				},
				'complete': function () {
					$('#table tr td button.danger').attr('disabled', false);
				}
			});
		}
	}


	function reload_table(reset = false) {
		edicaoEvento = false;
		$('#mes_ano').append('&ensp;(Processando - Aguarde...)');
		var count = 0;
		var stmt = function (json) {
			count = count + 1;
			if (count === 3) {
				edicaoEvento = true;
			}
		};
		table.ajax.reload(stmt, reset);
		table_totalizacao.ajax.reload(stmt, reset);
		table_avaliacao_performance.ajax.reload(stmt, reset);
		// table_totalizacao.ajax.reload(stmt, reset);
		// table_colaboradores.ajax.reload(stmt, reset);
	}


	function imprimir_totalizacao() {
		if ($('#busca [name="id_depto"]').val() === '' || $('#busca [name="id_area"]').val() === '' ||
			$('#busca [name="id_setor"]').val() === '' || $('#busca [name="ano"]').val() === '') {
			alert('Para gerar o relatório, ajuste os filtros de Departamento, Área, Setor, Mês e Ano.');
			return false;
		}

		var q = new Array();
		q.push("id_depto=" + $('#busca [name="id_depto"]').val());
		q.push("id_area=" + $('#busca [name="id_area"]').val());
		q.push("id_setor=" + $('#busca [name="id_setor"]').val());
		q.push("mes=" + $('#busca [name="mes"]').val());
		q.push("ano=" + $('#busca [name="ano"]').val());

		window.open('<?php echo site_url('icom/apontamento/pdfTotalizacao'); ?>/q?' + q.join('&'), '_blank');
	}


	function imprimir_avaliacao_performance() {
		if ($('#busca [name="id_depto"]').val() === '' || $('#busca [name="id_area"]').val() === '' ||
			$('#busca [name="id_setor"]').val() === '' || $('#busca [name="ano"]').val() === '') {
			alert('Para gerar o relatório, ajuste os filtros de Departamento, Área, Setor, Mês e Ano.');
			return false;
		}

		var q = new Array();
		q.push("id_depto=" + $('#busca [name="id_depto"]').val());
		q.push("id_area=" + $('#busca [name="id_area"]').val());
		q.push("id_setor=" + $('#busca [name="id_setor"]').val());
		q.push("mes=" + $('#busca [name="mes"]').val());
		q.push("ano=" + $('#busca [name="ano"]').val());

		window.open('<?php echo site_url('icom/apontamento/pdfAvaliacaoPerformance'); ?>/q?' + q.join('&'), '_blank');
	}

	function imprimir_feedback() {
		window.open('<?php echo site_url('icom/apontamento/pdfAvaliadoFeedback'); ?>/q?id=' + $('#form_feedback [name="id"]').val(), '_blank');
	}


	function edit_feedback(id_alocado) {
		$('#form_feedback [name="tipo"][value="0"]').prop('checked', true).trigger('change');
		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/editarFeedback') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': {'id_alocado': id_alocado},
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
					return false;
				}
				$.each(json, function (key, value) {
					$('#form_feedback [name="' + key + '"]').val(value);
				});

				$('#feedback_nome_usuario').html(json.nome_usuario_orientado);
				$('#form_feedback [name="id"]').html($(json.id_feedback).html());

				$('#modal_feedback').modal('show');
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error deleting data');
			}
		});
	}


	function selecionar_feedback(elem) {
		var id = elem.value;

		if (id.length === 0) {
			$('#form_feedback [name="descricao"], #form_feedback [name="resultado"]').val('');
			$('#btnImprimirFeedback, #btnLimparFeedback').hide();
		} else {
			$.ajax({
				'url': '<?php echo site_url('icom/apontamento/selecionarFeedback') ?>',
				'type': 'POST',
				'dataType': 'json',
				'data': {'id': id},
				'beforeSend': function () {
					$('#btnImprimirFeedback, #btnSaveFeedback, #btnLimparFeedback').attr('disabled', true);
					$('#form_feedback [name="id"], #form_feedback [name="tipo"]').attr('disabled', true);
				},
				'success': function (json) {
					if (json.erro) {
						alert(json.erro);
						return false;
					}
					$.each(json, function (key, value) {
						$('#form_feedback [name="' + key + '"]').val(value);
					});
					$('#btnImprimirFeedback, #btnLimparFeedback').show();
				},
				'complete': function () {
					$('#btnImprimirFeedback, #btnSaveFeedback, #btnLimparFeedback').attr('disabled', false);
					$('#form_feedback [name="id"], #form_feedback [name="tipo"]').attr('disabled', false);
				}
			});
		}
	}


	function selecionar_feedback_avaliado(elem) {
		var id = elem.value;

		if (id.length === 0) {
			$('#form_avaliado .feedback').val('');
		} else {
			$.ajax({
				'url': '<?php echo site_url('icom/apontamento/selecionarFeedback') ?>',
				'type': 'POST',
				'dataType': 'json',
				'data': {'id': id},
				'success': function (json) {
					if (json.erro) {
						alert(json.erro);
						return false;
					}
					$('#form_avaliado [name="descricao"]').val(json.descricao);
				}
			});
		}
	}


	function save_feedback() {
		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/salvarFeedback') ?>',
			'type': 'POST',
			'data': $('#form_feedback').serialize(),
			'dataType': 'json',
			'beforeSend': function () {
				$('#btnSaveFeedback').text('Salvando...');
				$('#btnImprimirFeedback, #btnSaveFeedback, #btnLimparFeedback').attr('disabled', true);
			},
			'success': function (json) {
				if (json.status) {
					$('#modal_feedback').modal('hide');
					reload_table();
				} else if (json.erro) {
					alert(json.erro);
				}
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Error adding/update data');
			},
			'complete': function () {
				$('#btnSaveFeedback').text('Salvar');
				$('#btnImprimirFeedback, #btnSaveFeedback, #btnLimparFeedback').attr('disabled', false);
			}
		});
	}

	function excluir_feedback() {
		if (confirm('Deseja excluir o feedback?')) {
			$.ajax({
				'url': '<?php echo site_url('icom/apontamento/excluirFeedback') ?>',
				'type': 'POST',
				'dataType': 'json',
				'data': {
					'id': $('#form_feedback [name="id"]').val()
				},
				'beforeSend': function () {
					$('#btnLimparFeedback').text('Excluindo...');
					$('#btnImprimirFeedback, #btnSaveFeedback, #btnLimparFeedback').attr('disabled', true);
				},
				'success': function (json) {
					if (json.erro) {
						alert(json.erro);
					} else {
						$('#modal_feedback').modal('hide');
					}
				},
				'error': function (jqXHR, textStatus, errorThrown) {
					alert('Error deleting data');
				},
				'complete': function () {
					$('#btnLimparFeedback').text('Excluir');
					$('#btnImprimirFeedback, #btnSaveFeedback, #btnLimparFeedback').attr('disabled', false);
				}
			});
		}
	}

	function relatorio_feedback_mensal() {
		$.ajax({
			'url': '<?php echo site_url('icom/apontamento/relatorioDeFeedbackMensal') ?>',
			'type': 'POST',
			'dataType': 'json',
			'data': {
				'busca': busca
			},
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
					return false;
				}
				$('#relatorio_feedback_mensal').html(json.folha);
				$('#mes_ano_arquivo_feedback').html(json.mes_ano);

				$('#modal_feedback_mensal').modal('show');
			}
		});
	}

</script>

<?php require_once APPPATH . 'views/end_html.php'; ?>
