<?php
require_once APPPATH . "views/header.php";
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
					<li class="active">Gerenciar pacientes</li>
					<?php $this->load->view('modal_processos', ['url' => 'papd/pacientes']); ?>
				</ol>
				<div class="row">
					<div class="col-md-12">
						<a class="btn btn-primary" href="<?= site_url('papd/pacientes/cadastro') ?>"><i
								class="glyphicon glyphicon-plus"></i> Cadastrar novo</a>
						<a class="btn btn-info" data-toggle="modal" data-target="#modal_form"
						   title="Relatórios de Controle de Frequência"><i
								class="glyphicon glyphicon-download-alt"></i> Relatórios de Controle de
							Frequência</a>
						<a class="btn btn-info" data-toggle="modal" data-target="#modal_consolidado_mif_zarit"
						   title="Consolidado MIF-ZARIT"><i class="glyphicon glyphicon-list-alt"></i> Consolidado
							MIF-ZARIT</a>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<div class="well well-sm">
							<div class="row">
								<div class="col-md-3">
									<label class="control-label">Filtrar por deficiência (HD)</label>
									<?php echo form_dropdown('deficiencia', $deficiencia, '', 'id="deficiencia" class="form-control filtro input-sm"'); ?>
								</div>
								<div class="col-md-3">
									<label class="control-label">Filtrar por status</label>
									<?php echo form_dropdown('status', $status, '', 'id="status" class="form-control filtro input-sm"'); ?>
								</div>
								<div class="col-md-4">
									<label class="control-label">Filtrar por contrato</label>
									<?php echo form_dropdown('contrato', $contratos, '', 'id="contrato" class="form-control filtro input-sm"'); ?>
								</div>
								<div class="col-md-2">
									<label>&nbsp;</label><br>
									<div class="btn-group" role="group" aria-label="...">
										<button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
											Limpar filtros
										</button>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label class="control-label">Filtrar por estado</label>
									<?php echo form_dropdown('estado', $estado, '', 'id="estado" class="form-control filtro input-sm"'); ?>
								</div>
								<div class="col-md-4">
									<label class="control-label">Filtrar por cidade</label>
									<?php echo form_dropdown('cidade', $cidade, '', 'id="cidade" class="form-control filtro input-sm"'); ?>
								</div>
								<div class="col-md-4">
									<label class="control-label">Filtrar por bairro</label>
									<?php echo form_dropdown('bairro', $bairro, '', 'id="bairro" class="form-control filtro input-sm"'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<table id="table" class="table table-hover" cellspacing="0" width="calc(100%)"
					   style="border-radius: 0 !important;">
					<thead>
					<tr>
						<th>Paciente</th>
						<th>Status</th>
						<th>Deficiência/hipótese diagnóstica</th>
						<th class="text-center">Data ingresso</th>
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
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Relatórios de controle de frequência</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form" class="form-horizontal">
							<div class="form-body">
								<div class="row form-group">
									<label class="control-label col-md-5">Mês e ano das declarações</label>
									<div class="col-md-4">
										<?php echo form_dropdown('mes', $meses, date('m'), 'class="form-control" autocomplete="off"'); ?>
									</div>
									<div class="col-md-3">
										<input name="ano" value="<?= date('Y') ?>" placeholder="aaaa"
											   class="form-control text-right" maxlength="4" type="number">
										<span class="help-block"></span>
									</div>
								</div>
							</div>
							<div class="form-footer form-horizontal">
								<!--                                <p id="statuss">
																	Animated Progress Bars
																</p>
																<div class="progress progress-xs">
																	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
																		<span class="sr-only">40% Complete (success)</span>
																	</div>
																</div>-->
								<progress id="progresso" value="0" max="0" style="visibility: hidden;"></progress>
								<label id="statuss"></label>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" onclick="frequencia_coletiva()"
								title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<!-- End Bootstrap modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_consolidado_mif_zarit" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Consolidado MIF-ZARIT</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form_consolidado_mif_zarit" class="form-horizontal">
							<div class="form-body">
								<div class="row form-group">
									<label class="control-label col-md-1 text-nowrap">Ano inicial</label>
									<div class="col-md-2">
										<input name="ano_inicial" value="<?= date('Y') ?>" placeholder="aaaa"
											   class="form-control text-right" maxlength="4" type="number">
									</div>
									<label class="control-label col-md-1 text-nowrap">Ano final</label>
									<div class="col-md-2">
										<input name="ano_final" value="<?= date('Y') + 4 ?>" placeholder="aaaa"
											   class="form-control text-right" maxlength="4" type="number">
									</div>
									<div class="col-md-1">
										<button type="button" class="btn btn-default"
												onclick="reload_table_consolidado_mif_zarit();"><i
												class="glyphicon glyphicon-search"></i> Filtrar
										</button>
									</div>
									<div class="col-md-5 text-right">
										<a id="pdf_mif_zarit" href="#" class="btn btn-info"><i
												class="glyphicon glyphicon-print"></i> Imprimir</a>
										<button type="button" class="btn btn-default" data-dismiss="modal">Fechar
										</button>
									</div>
								</div>
							</div>
						</form>
						<table id="table_consolidado_mif_zarit" class="table table-bordered table-condensed"
							   cellspacing="0" width="100%">
							<thead>
							<tr class="active">
								<th rowspan="2">Paciente</th>
								<th colspan="2" class="text-center ano">2015</th>
								<th colspan="2" class="text-center ano">2016</th>
								<th colspan="2" class="text-center ano">2017</th>
								<th colspan="2" class="text-center ano">2018</th>
								<th colspan="2" class="text-center ano">2019</th>
							</tr>
							<tr class="active">
								<th class="text-center mif">MIF</th>
								<th class="text-center zarit">ZARIT</th>
								<th class="text-center mif">MIF</th>
								<th class="text-center zarit">ZARIT</th>
								<th class="text-center mif">MIF</th>
								<th class="text-center zarit">ZARIT</th>
								<th class="text-center mif">MIF</th>
								<th class="text-center zarit">ZARIT</th>
								<th class="text-center mif">MIF</th>
								<th class="text-center zarit">ZARIT</th>
							</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<!-- End Bootstrap modal -->

	</section>
</section>
<!--main content end-->

<?php
require_once APPPATH . "views/end_js.php";
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Gerenciar pacientes';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

<script>

    var table, table_consolidado_mif_zarit;

    $(document).ready(function () {
        //datatables
        table = $('#table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "iDisplayLength": 25,
            "order": [[0, 'asc']],
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('papd/pacientes/ajax_list') ?>",
                "type": "POST",
                timeout: 90000,
                data: function (d) {
                    d.deficiencia = $('#deficiencia').val();
                    d.status = $('#status').val();
                    d.contrato = $('#contrato').val();
                    d.estado = $('#estado').val();
                    d.cidade = $('#cidade').val();
                    d.bairro = $('#bairro').val();
                    return d;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: '50%',
                    "targets": [0, 2]
                },
                {
                    className: "text-nowrap",
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false
                }
            ]
        });


        table_consolidado_mif_zarit = $('#table_consolidado_mif_zarit').DataTable({
            'processing': true, //Feature control the processing indicator.
            'serverSide': true,
            'lengthChange': false,
            'searching': false,
            'paging': false,
            'language': {
                'url': "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            'ajax': {
                'url': "<?php echo site_url('papd/pacientes/ajax_consolidados_mif_zarit') ?>",
                'type': 'POST',
                'data': function (d) {
                    d.ano_inicial = $('#form_consolidado_mif_zarit [name="ano_inicial"]').val();
                    d.ano_final = $('#form_consolidado_mif_zarit [name="ano_final"]').val();
                    return d;
                },
                'dataSrc': function (json) {
                    var i = 0
                    for (i = 0; i < 5; i++) {
                        $('#table_consolidado_mif_zarit .ano:eq(' + i + ')').html(json.ano_inicial + i);
                    }
                    $('#form_consolidado_mif_zarit [name="ano_final"]').val(json.ano_final);
                    $('#pdf_mif_zarit').prop('href', '<?= site_url('papd/relatorios/pdfConsolidado_mif_zarit/q?ano_inicial='); ?>' + json.ano_inicial + '&ano_final=' + json.ano_final);

                    return json.data;
                }
            },
            'columnDefs': [
                {
                    'width': '100%',
                    'targets': [0]
                },
                {
                    'className': "text-center text-nowrap",
                    'orderable': false,
                    'targets': ['mif', 'zarit']
                }
            ]
        });

//        setPdf_atributes();
    });

    $('.filtro').on('change', function () {
        reload_table();
//        setPdf_atributes();
    });

    $('#limpa_filtro').on('click', function () {
        $('.filtro').val('');
        reload_table();
    });

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax
    }

    function reload_table_consolidado_mif_zarit() {
        table_consolidado_mif_zarit.ajax.reload(null, false); //reload datatable ajax
    }

    function delete_paciente(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('papd/pacientes/ajax_delete') ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    id: id
                },
                success: function (data) {
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    reload_table();
                }
            });
        }
    }

    function frequencia_coletiva() {

        $.ajax({
            url: "<?php echo site_url('papd/relatorios/pdfFrequencia_coletiva2') ?>",
            type: "POST",
            dataType: "json",
            data: $('.filtro').serialize(),
            success: function (data) {
                var count = 0;
                var respostaa = true;

                function frequencia_individual(v) {
                    $.ajax({
                        url: "<?php echo site_url('papd/relatorios/frequencia2') ?>",
                        type: "GET",
                        dataType: 'json',
                        data: {
                            id: v.id,
                            paciente: v.paciente,
                            pacote: data.pacote,
                            mes: $('[name="mes"]').val(),
                            ano: $('[name="ano"]').val()
                        },
                        success: function (json) {
                            if (json.status !== true) {
                                respostaa = false;
                            }
                            count = count + 1;

                            $('#statuss').html('Gerando relatório ' + (count) + ' de ' + data.max);
                            $('#progresso').attr('value', (count));
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert(textStatus + ' ' + jqXHR.status + ': ' + (jqXHR.status === 0 ? 'Disconnected' : errorThrown));
                            if (jqXHR.status === 401) {
                                $('#session_timeout').modal('show');
                            } else {
                                respostaa = false;
                            }
                        },
                        complete: function () {
                            if (respostaa === false) {
                                $('#statuss').html('Erro ao exportar');
                                return false;
                            }
                            if (count < data.rows.length) {
                                frequencia_individual(data.rows[count]);
                            } else {
                                $('#statuss').html('Preparando arquivo...');
                                location.href = '<?= site_url('papd/relatorios/frequencia3'); ?>/' + data.pacote;
                                $('#statuss').html('Concluído.');
                            }
                        }
                    });
                }

                $('#progresso').css('visibility', 'visible').attr('max', data.max);
                $('#statuss').html('Gerando relatório 0 de ' + data.max);

                frequencia_individual(data.rows[0]);

//                $.each(data.rows, function (i, v) {
//
//                    $.ajax({
//                        url: "<?php //echo site_url('papd/relatorios/frequencia2') ?>",
//                        type: "POST",
//                        dataType: 'json',
//                        data: {
//                            id: v.id,
//                            paciente: v.paciente,
//                            pacote: data.pacote
//                        },
//                        success: function (json) {
//                            if (json.status !== true) {
//                                respostaa = false;
//                            }
//                            count = count + 1;
//
//                            $('#statuss').html('Gerando relatório ' + (count) + ' de ' + data.max);
//                            $('#progresso').attr('value', (count));
//                        },
//                        error: function (jqXHR, textStatus, errorThrown)
//                        {
//                            respostaa = false;
//                        },
//                        complete: function () {
//                            if (respostaa === false) {
//                                $('#statuss').html('Erro ao exportar');
//                                return false;
//                            }
//                            if (count === data.rows.length) {
//                                $('#statuss').html('Preparando arquivo...');
//                                location.href = '<?= site_url('papd/relatorios/frequencia3'); ?>/' + data.pacote;
//                                $('#statuss').html('Concluído.');
//                            }
//                        }
//                    });
//
//                });
            }
        });
    }

    $('#modal_consolidado_mif_zarit').on('shown.bs.modal', function () {
        $('#form_consolidado_mif_zarit')[0].reset();
        reload_table_consolidado_mif_zarit();
    });

    $('#modal_form').on('hidden.bs.modal', function () {
        $('#progresso').attr({value: 0, max: 0}).css('visibility', 'hidden');
        $('#statuss').html('');
    });

    function setPdf_atributes() {
        var search = '';
        var q = new Array();

        $('.filtro').each(function (i, v) {
            if (v.value.length > 0 && (v.value !== 'Todos' || v.value !== 'Todas')) {
                q[i] = v.name + "=" + v.value;
            }
        });
        q.push("mes=" + $('[name="mes"]').val());
        q.push("ano=" + $('[name="ano"]').val());

        q = q.filter(function (v) {
            return v.length > 0;
        });
        if (q.length > 0) {
            search = '/q?' + q.join('&');
        }

        $('#pdf').prop('href', "<?= site_url('papd/relatorios/pdfFrequencia_coletiva/'); ?>" + search);
    }

</script>

<?php
require_once APPPATH . "views/end_html.php";
?>
