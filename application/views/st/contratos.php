<?php
require_once APPPATH . 'views/header.php';
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
</style>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">

		<!-- page start-->
		<div class="row">
			<div class="col-md-12">
				<div id="alert"></div>
				<ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
					<li><a href="<?= site_url('st/apontamento') ?>">Apontamentos diários</a></li>
					<li class="active">Gerenciar contratos</li>
				</ol>
				<button class="btn btn-info" onclick="add_contrato()"><i class="glyphicon glyphicon-plus"></i>
					Adicionar contrato
				</button>
				<button class="btn btn-default" onclick="javascript:history.back()"><i
						class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
				</button>
				<br/>
				<br/>
				<div class="row">
					<div class="col-md-12">
						<div class="well well-sm">
							<form action="#" id="busca" class="form-horizontal" autocomplete="off">
								<div class="row">
									<div class="col-md-3">
										<label class="control-label">Filtrar por departamento</label>
										<?php echo form_dropdown('busca[depto]', $deptos, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
									</div>
									<div class="col-md-3">
										<label class="control-label">Filtrar por área/cliente</label>
										<?php echo form_dropdown('busca[area]', $area_cliente, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
									</div>
									<div class="col-md-3">
										<label class="control-label">Filtrar por setor/unidade</label>
										<?php echo form_dropdown('busca[setor]', $setor_unidade, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
									</div>
									<div class="col-md-2">
										<label class="control-label">Filtrar por contrato</label>
										<?php echo form_dropdown('busca[contrato]', $contratos, '', 'class="form-control input-sm filtro"'); ?>
									</div>
									<div class="col-md-1">
										<label>&nbsp;</label><br>
										<div class="btn-group" role="group" aria-label="...">
											<button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
												Limpar
											</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<table id="table" class="table table-striped" cellspacing="0" width="100%">
					<thead>
					<tr>
						<th>Cliente</th>
						<th>Departamento/Área</th>
						<th>Contrato</th>
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
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Editar contrato</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form" class="form-horizontal">
							<input type="hidden" value="" name="id"/>
							<div class="form-body">
								<div class="row form-group">
									<label class="control-label col-md-3">Cliente</label>
									<div class="col-md-8">
										<input name="nome"
											   placeholder="Nome do cliente (tamanho máx. de 100 caracteres)"
											   class="form-control" type="text" size="100">
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-3">Departamento</label>
									<div class="col-md-8">
										<?php echo form_dropdown('depto', $depto, '', 'id="depto" class="estrutura form-control"'); ?>
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-3">Área</label>
									<div class="col-md-8">
										<?php echo form_dropdown('area', $area, '', 'id="area" class="estrutura form-control"'); ?>
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-3">Contrato</label>
									<div class="col-md-8">
										<input name="contrato" placeholder="Nome do contrato" id="contrato"
											   class="form-control" type="text" size="100">
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-3">Gestor(a)</label>
									<div class="col-md-8">
										<?php echo form_dropdown('id_usuario', $usuarios, '', 'id="id_usuario" class="form-control"'); ?>
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-3">Data assinatura</label>
									<div class="col-md-2">
										<input type="text" class="date form-control text-center"
											   name="data_assinatura" id="data_assinatura" value=""
											   placeholder="dd/mm/aaaa">
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSave" onclick="save()" class="btn btn-success">Salvar</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_unidades" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Gerenciar unidades/setores</h3>
					</div>
					<div class="modal-body form">
						<div class="row">
							<div class="col-md-2 text-right"><strong>Contrato:</strong></div>
							<div class="col-md-9">
								<span id="unidade_contrato"></span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 text-right"><strong>Cliente:</strong></div>
							<div class="col-md-9">
								<span id="unidade_cliente"></span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 text-right"><strong>Departamento:</strong></div>
							<div class="col-md-9">
								<span id="unidade_depto"></span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 text-right"><strong>Área:</strong></div>
							<div class="col-md-9">
								<span id="unidade_area"></span>
							</div>
						</div>
						<hr style="margin-top: 10px; margin-bottom: 0px;">
						<form action="#" id="form_unidades" class="form-horizontal">
							<input type="hidden" value="" name="id_contrato"/>
							<div class="form-body">
								<div class="row form-group">
									<?php echo form_multiselect('setor[]', array(), array(), 'size="10" id="unidades" class="demo2"') ?>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSaveUnidades" onclick="save_unidades()"
								class="btn btn-success">Salvar
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->


		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_servicos" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Gerenciar serviços</h3>
					</div>
					<div class="modal-body form">
						<div class="row">
							<div class="col-sm-9">
								<div class="row">
									<div class="col-md-3 text-right"><strong>Contrato:</strong></div>
									<div class="col-md-9">
										<span id="servicos_contrato"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3 text-right"><strong>Cliente:</strong></div>
									<div class="col-md-9">
										<span id="servicos_cliente"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3 text-right"><strong>Departamento:</strong></div>
									<div class="col-md-9">
										<span id="servicos_depto"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3 text-right"><strong>Área:</strong></div>
									<div class="col-md-9">
										<span id="servicos_area"></span>
									</div>
								</div>
							</div>
							<div class="col-sm-3">
								<button type="button" id="btnSaveServicos" onclick="save_servicos()"
										class="btn btn-success">Salvar
								</button>
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
							</div>
						</div>
						<hr>
						<form action="#" id="form_servicos_compartilhados" class="form_servicos form-horizontal"
							  autocomplete="off">
							<input type="hidden" name="id_contrato" value="">
							<div class="row form-group">
								<label class="control-label col-md-3"><strong>Mês/ano reajuste
										existentes</strong></label>
								<div class="col-md-2">
									<?php echo form_dropdown('id_reajuste', array('' => 'selecione...'), '', 'id="id_reajuste" class="form-control"'); ?>
								</div>
								<label class="control-label col-md-3"><strong>Mês/ano novo reajuste</strong></label>
								<div class="col-md-2">
									<input name="data_reajuste" placeholder="mm/aaaa"
										   class="form-control text-center mes_ano" type="text">
								</div>
							</div>
							<hr>
							<div class="row form-group">
								<div class="col-md-8 col-md-offset-1">
									<h5>Serviços compartilhados</h5>
								</div>
								<div class="col-md-2">
									<h5>Valor (R$)</h5>
								</div>
							</div>
							<div class="row form-group">
								<input type="hidden" name="id[]" value="">
								<div class="col-md-8 col-md-offset-1">
									<input name="descricao[]" placeholder="Serviço 1" class="form-control input-sm"
										   type="text">
								</div>
								<div class="col-md-2">
									<input name="valor[]" placeholder="Valor"
										   class="valor form-control input-sm text-right" type="text">
								</div>
							</div>
							<div class="row form-group">
								<input type="hidden" name="id[]" value="">
								<div class="col-md-8 col-md-offset-1">
									<input name="descricao[]" placeholder="Serviço 2" class="form-control input-sm"
										   type="text">
								</div>
								<div class="col-md-2">
									<input name="valor[]" placeholder="Valor"
										   class="valor form-control input-sm text-right" type="text">
								</div>
							</div>
							<div class="row form-group">
								<input type="hidden" name="id[]" value="">
								<div class="col-md-8 col-md-offset-1">
									<input name="descricao[]" placeholder="Serviço 3" class="form-control input-sm"
										   type="text">
								</div>
								<div class="col-md-2">
									<input name="valor[]" placeholder="Valor"
										   class="valor form-control input-sm text-right" type="text">
								</div>
							</div>
							<div class="row form-group">
								<input type="hidden" name="id[]" value="">
								<div class="col-md-8 col-md-offset-1">
									<input name="descricao[]" placeholder="Serviço 4" class="form-control input-sm"
										   type="text">
								</div>
								<div class="col-md-2">
									<input name="valor[]" placeholder="Valor"
										   class="valor form-control input-sm text-right" type="text">
								</div>
							</div>
						</form>
						<hr>
						<form action="#" id="form_servicos_nao_compartilhados" class="form_servicos form-horizontal"
							  autocomplete="off">
							<div class="row form-group">
								<div class="col-md-8 col-md-offset-1">
									<h5>Serviços não compartilhados</h5>
								</div>
								<div class="col-md-2">
									<h5>Valor (R$)</h5>
								</div>
							</div>
							<div class="row form-group">
								<input type="hidden" name="id[]" value="">
								<div class="col-md-8 col-md-offset-1">
									<input name="descricao[]" placeholder="Serviço 1" class="form-control input-sm"
										   type="text">
								</div>
								<div class="col-md-2">
									<input name="valor[]" placeholder="Valor"
										   class="valor form-control input-sm text-right" type="text">
								</div>
							</div>
							<div class="row form-group">
								<input type="hidden" name="id[]" value="">
								<div class="col-md-8 col-md-offset-1">
									<input name="descricao[]" placeholder="Serviço 2" class="form-control input-sm"
										   type="text">
								</div>
								<div class="col-md-2">
									<input name="valor[]" placeholder="Valor"
										   class="valor form-control input-sm text-right" type="text">
								</div>
							</div>
							<div class="row form-group">
								<input type="hidden" name="id[]" value="">
								<div class="col-md-8 col-md-offset-1">
									<input name="descricao[]" placeholder="Serviço 3" class="form-control input-sm"
										   type="text">
								</div>
								<div class="col-md-2">
									<input name="valor[]" placeholder="Valor"
										   class="valor form-control input-sm text-right" type="text">
								</div>
							</div>
							<div class="row form-group">
								<input type="hidden" name="id[]" value="">
								<div class="col-md-8 col-md-offset-1">
									<input name="descricao[]" placeholder="Serviço 4" class="form-control input-sm"
										   type="text">
								</div>
								<div class="col-md-2">
									<input name="valor[]" placeholder="Valor"
										   class="valor form-control input-sm text-right" type="text">
								</div>
							</div>
						</form>
					</div>
					<!--<div class="modal-footer">
						<button type="button" id="btnSaveServicos" onclick="save_servicos()"
								class="btn btn-primary">Salvar
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					</div>-->
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_reajuste" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Gerenciar reajuste</h3>
					</div>
					<div class="modal-body form">
						<div class="row">
							<div class="col-md-3 text-right"><strong>Contrato:</strong></div>
							<div class="col-md-8">
								<span id="reajuste_contrato"></span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 text-right"><strong>Cliente:</strong></div>
							<div class="col-md-8">
								<span id="reajuste_cliente"></span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 text-right"><strong>Departamento:</strong></div>
							<div class="col-md-8">
								<span id="reajuste_depto"></span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 text-right"><strong>Área:</strong></div>
							<div class="col-md-8">
								<span id="reajuste_area"></span>
							</div>
						</div>
						<hr>
						<form action="#" id="form_reajuste" class="form-horizontal">
							<input type="hidden" id="id_cliente" name="id_cliente" value="">
							<div class="form-body">
								<div class="row form-group">
									<input type="hidden" name="id[]" value="">
									<label class="control-label col-md-2"><strong>1º Índice</strong></label>
									<label class="control-label col-md-1">Data</label>
									<div class="col-md-3">
										<input name="data_reajuste[]" placeholder="dd/mm/aaaa"
											   class="data_reajuste form-control text-center" type="text">
									</div>
									<label class="control-label col-md-1">Valor</label>
									<div class="col-md-4">
										<div class="input-group">
											<input name="valor_indice[]" placeholder="Valor"
												   class="form-control text-right" type="number" min="0"
												   max="9999999999.99999999" step="0.00000001">
											<span class="input-group-addon" id="basic-addon1">%</span>
										</div>
									</div>
								</div>
								<hr style="margin-top: 0px; margin-bottom: 10px;">
								<div class="row form-group">
									<input type="hidden" name="id[]" value="">
									<label class="control-label col-md-2"><strong>2º Índice</strong></label>
									<label class="control-label col-md-1">Data</label>
									<div class="col-md-3">
										<input name="data_reajuste[]" placeholder="dd/mm/aaaa"
											   class="data_reajuste form-control text-center" type="text">
									</div>
									<label class="control-label col-md-1">Valor</label>
									<div class="col-md-4">
										<div class="input-group">
											<input name="valor_indice[]" placeholder="Valor"
												   class="form-control text-right" type="number" min="0"
												   max="9999999999.99999999" step="0.00000001">
											<span class="input-group-addon" id="basic-addon1">%</span>
										</div>
									</div>
								</div>

								<hr style="margin-top: 0px; margin-bottom: 10px;">
								<div class="row form-group">
									<input type="hidden" name="id[]" value="">
									<label class="control-label col-md-2"><strong>3º Índice</strong></label>
									<label class="control-label col-md-1">Data</label>
									<div class="col-md-3">
										<input name="data_reajuste[]" placeholder="dd/mm/aaaa"
											   class="data_reajuste form-control text-center" type="text">
									</div>
									<label class="control-label col-md-1">Valor</label>
									<div class="col-md-4">
										<div class="input-group">
											<input name="valor_indice[]" placeholder="Valor"
												   class="form-control text-right" type="number" min="0"
												   max="9999999999.99999999" step="0.00000001">
											<span class="input-group-addon" id="basic-addon1">%</span>
										</div>
									</div>
								</div>

								<hr style="margin-top: 0px; margin-bottom: 10px;">
								<div class="row form-group">
									<input type="hidden" name="id[]" value="">
									<label class="control-label col-md-2"><strong>4º Índice</strong></label>
									<label class="control-label col-md-1">Data</label>
									<div class="col-md-3">
										<input name="data_reajuste[]" placeholder="dd/mm/aaaa"
											   class="data_reajuste form-control text-center" type="text">
									</div>
									<label class="control-label col-md-1">Valor</label>
									<div class="col-md-4">
										<div class="input-group">
											<input name="valor_indice[]" placeholder="Valor"
												   class="form-control text-right" type="number" min="0"
												   max="9999999999.99999999" step="0.00000001">
											<span class="input-group-addon" id="basic-addon1">%</span>
										</div>
									</div>
								</div>

								<hr style="margin-top: 0px; margin-bottom: 10px;">
								<div class="row form-group">
									<input type="hidden" name="id[]" value="">
									<label class="control-label col-md-2"><strong>5º Índice</strong></label>
									<label class="control-label col-md-1">Data</label>
									<div class="col-md-3">
										<input name="data_reajuste[]" placeholder="dd/mm/aaaa"
											   class="data_reajuste form-control text-center" type="text">
									</div>
									<label class="control-label col-md-1">Valor</label>
									<div class="col-md-4">
										<div class="input-group">
											<input name="valor_indice[]" placeholder="Valor"
												   class="form-control text-right" type="number" min="0"
												   max="9999999999.99999999" step="0.00000001">
											<span class="input-group-addon" id="basic-addon1">%</span>
										</div>
									</div>
								</div>

							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSaveReajuste" onclick="save_reajuste()"
								class="btn btn-success">Salvar
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<!-- End Bootstrap modal -->

	</section>
</section>
<!--main content end-->

<?php
require_once APPPATH . 'views/end_js.php';
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
	  rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Gerenciar funcionários';
    });</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>

    var save_method; //for save method string
    var table;
    var avaliadores;

    $('#data_assinatura, .data_reajuste').mask('00/00/0000');
    $('.mes_ano').mask('00/0000');
    $('.valor').mask('##.###.##0,00', {reverse: true});

    $(document).ready(function () {

        //datatables
        table = $('#table').DataTable({
            "info": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('st/contratos/listar') ?>",
                "type": "POST",
                data: function (d) {
                    d.busca = $('#busca').serialize();
                    return d;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: '40%',
                    targets: [0, 1]
                },
                {
                    width: '20%',
                    targets: [2]
                },
                {
                    className: "text-nowrap",
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false //set not orderable
                }
            ]
        });

        demo2 = $('#unidades').bootstrapDualListbox({
            nonSelectedListLabel: 'Unidades disponíveis',
            selectedListLabel: 'Unidades selecionadas',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            helperSelectNamePostfix: false,
            filterPlaceHolder: 'Filtrar',
            selectorMinimalHeight: 132,
            infoText: false
        });

        // atualizarFiltro();
    });

    function atualizarFiltro() {
        $.ajax({
            url: "<?php echo site_url('st/contratos/atualizarFiltros') ?>",
            type: "POST",
            dataType: "JSON",
            data: $('#busca').serialize(),
            success: function (data) {
                $('[name="busca[area]"]').html($(data.area).html());
                $('[name="busca[setor]').html($(data.setor).html());
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    $('#limpa_filtro').on('click', function () {
        var busca = unescape($('#busca').serialize());
        $.each(busca.split('&'), function (index, elem) {
            var vals = elem.split('=');
            $("[name='" + vals[0] + "']").val($("[name='" + vals[0] + "'] option:first").val());
        });
        atualizarFiltro();
    });

    $('.estrutura').on('change', function () {
        atualizar_estrutura();
    });

    $('#id_reajuste').on('change', function () {
        atualizar_servicos(this.value);
    });

    function atualizar_estrutura() {
        $.ajax({
            url: "<?php echo site_url('st/contratos/atualizarEstrutura') ?>",
            type: "POST",
            dataType: "json",
            data: {
                depto: $('#depto').val(),
                area: $('#area').val(),
                setor: $('#setor').val()
            },
            success: function (data) {
                $('#area').html($(data.area).html());
                $('#setor').html($(data.setor).html());
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function atualizar_servicos(data_reajuste) {
        $.ajax({
            url: "<?php echo site_url('st/contratos/atualizarServicos') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                id: $('.form_servicos [name="id_contrato"]').val(),
                data_reajuste: data_reajuste
            },
            success: function (json) {
                $('#servicos_contrato').text(json.contrato.contrato);
                $('#servicos_cliente').text(json.contrato.nome);
                $('#servicos_depto').text(json.contrato.depto);
                $('#servicos_area').text(json.contrato.area);

                $('.form_servicos [name="id_contrato"]').val(json.contrato.id);

                $.each(json.compartilhados, function (i, v) {
                    $('#form_servicos_compartilhados [name="id[]"]:eq(' + i + ')').val(v.id);
                    $('#form_servicos_compartilhados [name="descricao[]"]:eq(' + i + ')').val(v.descricao);
                    $('#form_servicos_compartilhados [name="valor[]"]:eq(' + i + ')').val(v.valor);
                });
                $.each(json.nao_compartilhados, function (i, v) {
                    $('#form_servicos_nao_compartilhados [name="id[]"]:eq(' + i + ')').val(v.id);
                    $('#form_servicos_nao_compartilhados [name="descricao[]"]:eq(' + i + ')').val(v.descricao);
                    $('#form_servicos_nao_compartilhados [name="valor[]"]:eq(' + i + ')').val(v.valor);
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function add_contrato() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('[name="tipo"] option').prop('disabled', false);
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar novo contrato'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function edit_contrato(id) {
        $('#form')[0].reset(); // reset form on modals
        $('#form input[type="hidden"]').val(''); // reset hidden input form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('st/contratos/editar') ?>",
            type: "POST",
            dataType: "JSON",
            data: {id: id},
            success: function (data) {
                $('[name="id"]').val(data.id);
                $('[name="id_usuario"]').val(data.id_usuario);
                $('[name="nome"]').val(data.nome);
                $('[name="depto"]').val(data.depto);
                $('[name="area"]').val(data.area);
                $('[name="setor"]').val(data.setor);
                atualizar_estrutura();
                $('[name="contrato"]').val(data.contrato);
                $('[name="data_assinatura"]').val(data.data_assinatura);

                $('#modal_form').modal('show');

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_unidades(id) {
        $('#form_unidades')[0].reset(); // reset form on modals
        $('#form_unidades input[type="hidden"]').val(''); // reset hidden input form on modals
        $('#form_unidades .form-group').removeClass('has-error'); // clear error class
        $('#form_unidades .help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('st/contratos/gerenciarUnidades') ?>",
            type: "POST",
            dataType: "JSON",
            data: {id: id},
            success: function (data) {
                $('#unidade_contrato').text(data.cliente.contrato);
                $('#unidade_cliente').text(data.cliente.nome);
                $('#unidade_depto').text(data.cliente.depto);
                $('#unidade_area').text(data.cliente.area);

                $('#form_unidades [name="id_contrato"]').val(data.id_contrato);
                $('#form_unidades #unidades').html(data.setores);
                $('#modal_unidades').modal('show');

                demo2.bootstrapDualListbox('refresh', true);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_servicos(id) {
        $('.form_servicos')[0].reset(); // reset form on modals
        $('.form_servicos')[1].reset(); // reset form on modals
        $('.form_servicos input[type="hidden"]').val(''); // reset hidden input form on modals
        $('.form_servicos .form-group').removeClass('has-error'); // clear error class
        $('.form_servicos .help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('st/contratos/gerenciarServicos') ?>",
            type: "POST",
            dataType: "JSON",
            data: {id: id},
            success: function (data) {
                $('#servicos_contrato').text(data.contrato.contrato);
                $('#servicos_cliente').text(data.contrato.nome);
                $('#servicos_depto').text(data.contrato.depto);
                $('#servicos_area').text(data.contrato.area);

                $('#id_reajuste').html($(data.reajuste).html());
                $('.form_servicos [name="id_contrato"]').val(data.contrato.id);

                $.each(data.compartilhados, function (i, v) {
                    $('#form_servicos_compartilhados [name="id[]"]:eq(' + i + ')').val(v.id);
                    $('#form_servicos_compartilhados [name="descricao[]"]:eq(' + i + ')').val(v.descricao);
                    $('#form_servicos_compartilhados [name="valor[]"]:eq(' + i + ')').val(v.valor);
                });
                $.each(data.nao_compartilhados, function (i, v) {
                    $('#form_servicos_nao_compartilhados [name="id[]"]:eq(' + i + ')').val(v.id);
                    $('#form_servicos_nao_compartilhados [name="descricao[]"]:eq(' + i + ')').val(v.descricao);
                    $('#form_servicos_nao_compartilhados [name="valor[]"]:eq(' + i + ')').val(v.valor);
                });
                $('#modal_servicos').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_reajuste(id) {
        $('#form_reajuste')[0].reset(); // reset form on modals
        $('#form_reajuste input[type="hidden"]').val(''); // reset hidden input form on modals

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('st/contratos/gerenciarReajustes') ?>",
            type: "POST",
            dataType: "JSON",
            data: {id: id},
            success: function (data) {
                $('#reajuste_contrato').text(data.cliente.contrato);
                $('#reajuste_cliente').text(data.cliente.nome);
                $('#reajuste_depto').text(data.cliente.depto);
                $('#reajuste_area').text(data.cliente.area);

                $('#id_cliente').val(data.cliente.id);
                $.each(data.values, function (i, v) {
                    $('#form_reajuste [name="id[]"]:eq(' + i + ')').val(v.id);
                    $('#form_reajuste [name="data_reajuste[]"]:eq(' + i + ')').val(v.data_reajuste);
                    $('#form_reajuste [name="valor_indice[]"]:eq(' + i + ')').val(v.valor_indice);
                });

                $('#modal_reajuste').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax
    }

    function save() {
        $('#btnSave').text('Salvando...'); //change button text
        $('#btnSave').attr('disabled', true); //set button disable
        var url;

        if (save_method === 'add') {
            url = "<?php echo site_url('st/contratos/salvar') ?>";
        } else {
            url = "<?php echo site_url('st/contratos/salvar') ?>";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data) {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_form').modal('hide');
                    reload_table();
                }

                $('#btnSave').text('Salvar'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSave').text('Salvar'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable
            }
        });
    }

    function save_unidades() {
        $('#btnSaveUnidades').text('Salvando...'); //change button text
        $('#btnSaveUnidades').attr('disabled', true); //set button disable
        demo2.bootstrapDualListbox('refresh', true);

        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('st/contratos/salvarUnidades') ?>",
            type: "POST",
            data: $('#form_unidades').serialize(),
            dataType: "JSON",
            success: function (data) {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_unidades').modal('hide');
                }

                $('#btnSaveUnidades').text('Salvar'); //change button text
                $('#btnSaveUnidades').attr('disabled', false); //set button enable
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSaveUnidades').text('Salvar'); //change button text
                $('#btnSaveUnidades').attr('disabled', false); //set button enable
            }
        });
    }

    function save_servicos() {
        $('#btnSaveServicos').text('Salvando...'); //change button text
        $('#btnSaveServicos').attr('disabled', true); //set button disable

        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('st/contratos/salvarServicos') ?>",
            type: "POST",
            data: $('.form_servicos').serialize(),
            dataType: "JSON",
            success: function (data) {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_servicos').modal('hide');
                }

                $('#btnSaveServicos').text('Salvar'); //change button text
                $('#btnSaveServicos').attr('disabled', false); //set button enable
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSaveServicos').text('Salvar'); //change button text
                $('#btnSaveServicos').attr('disabled', false); //set button enable
            }
        });
    }

    function save_reajuste() {
        $('#btnSaveReajuste').text('Salvando...'); //change button text
        $('#btnSaveReajuste').attr('disabled', true); //set button disable

        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('st/contratos/salvarReajustes') ?>",
            type: "POST",
            data: $('#form_reajuste').serialize(),
            dataType: "JSON",
            success: function (data) {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_reajuste').modal('hide');
                }

                $('#btnSaveReajuste').text('Salvar'); //change button text
                $('#btnSaveReajuste').attr('disabled', false); //set button enable
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSaveReajuste').text('Salvar'); //change button text
                $('#btnSaveReajuste').attr('disabled', false); //set button enable
            }
        });
    }

    function delete_contrato(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('st/contratos/excluir') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data) {
                    //if success reload ajax table
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
        }
    }

</script>

<?php
require_once APPPATH . 'views/end_html.php';
?>
