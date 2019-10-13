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
					<li class="active">Gestão de Processos</li>
				</ol>
				<a class="btn btn-primary" href="<?= site_url('gestaoProcessos/novo'); ?>"><i
						class="glyphicon glyphicon-plus"></i> Novo processo</a>
				<br>
				<br>
				<table id="table" class="table table-striped" cellspacing="0" width="100%">
					<thead>
					<tr>
						<th>URL página</th>
						<th>Orientações gerais</th>
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
							<button type="button" class="btn btn-success" id="btnSave" onclick="save()">Salvar
							</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						</div>
						<h3 class="modal-title">Formulario de Relatório de Gestão</h3>
					</div>
					<div class="modal-body form">
						<div id="alert"></div>
						<form action="#" id="form" class="form-horizontal" enctype="multipart/form-data"
							  autocomplete="off">
							<input type="hidden" value="" name="id"/>
							<input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>

							<div class="form-body">
								<div class="form-group">
									<label class="control-label col-sm-2">URL página</label>
									<div class="col-sm-10">
										<div class="input-group">
                                                <span class="input-group-addon"
													  id="url_pagina"><?= site_url(); ?></span>
											<input type="text" class="form-control" name="url_pagina"
												   aria-describedby="url_pagina">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Orientacoes gerais</label>
									<div class="col-sm-10">
                                            <textarea name="orientacoes_gerais" class="form-control"
													  rows="5"></textarea>
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-2 text-nowrap">Processo 1</label>
									<div class="col-md-10">
										<div class="fileinput fileinput-new input-group" data-provides="fileinput">
											<div class="form-control" data-trigger="fileinput">
												<i class="glyphicon glyphicon-file fileinput-exists"></i>
												<span class="fileinput-filename"></span>
											</div>
											<div class="input-group-addon btn btn-default btn-file">
												<span class="fileinput-new">Selecionar arquivo</span>
												<span class="fileinput-exists">Alterar</span>
												<input type="file" name="processo_1" accept=".pdf"/>
											</div>
											<a href="#" class="input-group-addon btn btn-default fileinput-exists"
											   data-dismiss="fileinput">Remover</a>
										</div>
										<span id="nome_processo_1" class="help-block"></span>
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-2 text-nowrap">Processo 2</label>
									<div class="col-md-10">
										<div class="fileinput fileinput-new input-group" data-provides="fileinput">
											<div class="form-control" data-trigger="fileinput">
												<i class="glyphicon glyphicon-file fileinput-exists"></i>
												<span class="fileinput-filename"></span>
											</div>
											<div class="input-group-addon btn btn-default btn-file">
												<span class="fileinput-new">Selecionar arquivo</span>
												<span class="fileinput-exists">Alterar</span>
												<input type="file" name="processo_2" accept=".pdf"/>
											</div>
											<a href="#" class="input-group-addon btn btn-default fileinput-exists"
											   data-dismiss="fileinput">Remover</a>
										</div>
										<span id="nome_processo_2" class="help-block"></span>
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-2 text-nowrap">Documentação 1</label>
									<div class="col-md-10">
										<div class="fileinput fileinput-new input-group" data-provides="fileinput">
											<div class="form-control" data-trigger="fileinput">
												<i class="glyphicon glyphicon-file fileinput-exists"></i>
												<span class="fileinput-filename"></span>
											</div>
											<div class="input-group-addon btn btn-default btn-file">
												<span class="fileinput-new">Selecionar arquivo</span>
												<span class="fileinput-exists">Alterar</span>
												<input type="file" name="documentacao_1" accept=".pdf"/>
											</div>
											<a href="#" class="input-group-addon btn btn-default fileinput-exists"
											   data-dismiss="fileinput">Remover</a>
										</div>
										<span id="nome_documentacao_1" class="help-block"></span>
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-2 text-nowrap">Documentação 2</label>
									<div class="col-md-10">
										<div class="fileinput fileinput-new input-group" data-provides="fileinput">
											<div class="form-control" data-trigger="fileinput">
												<i class="glyphicon glyphicon-file fileinput-exists"></i>
												<span class="fileinput-filename"></span>
											</div>
											<div class="input-group-addon btn btn-default btn-file">
												<span class="fileinput-new">Selecionar arquivo</span>
												<span class="fileinput-exists">Alterar</span>
												<input type="file" name="documentacao_2" accept=".pdf"/>
											</div>
											<a href="#" class="input-group-addon btn btn-default fileinput-exists"
											   data-dismiss="fileinput">Remover</a>
										</div>
										<span id="nome_documentacao_2" class="help-block"></span>
									</div>
								</div>

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

<?php
require_once "end_js.php";
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Gestão de Processos';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>


<script>

    var save_method;
    var table;

    $(document).ready(function () {

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
                'url': '<?php echo site_url('gestaoProcessos/ajaxList/') ?>',
                'type': 'POST'
            },
            'columnDefs': [
                {
                    'width': '30%',
                    'targets': [0]
                },
                {
                    'width': '70%',
                    'targets': [1]
                },
                {
                    'className': 'text-center text-nowrap',
                    'targets': [-1],
                    'orderable': false
                }
            ]
        });

    });


    function add_processo() {
        save_method = 'add';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_form').modal('show');
        $('#modal_form .modal-title').text('Adicionar Relatório de Gestão');
        $('.combo_nivel1').hide();
    }


    function edit_processo(id) {
        save_method = 'update';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();


        $.ajax({
            'url': '<?php echo site_url('gestaoProcessos/ajaxEdit') ?>',
            'dataType': 'json',
            'data': {'id': id},
            'success': function (json) {
                if (json.arquivo_processo_1) {
                    $('#nome_processo_1').html('Nome do arquivo selecionado: <i>' + json.arquivo_processo_1 + '</i>');
                }
                if (json.arquivo_processo_1) {
                    $('#nome_processo_2').html('Nome do arquivo selecionado: <i>' + json.arquivo_processo_2 + '</i>');
                }
                if (json.arquivo_documentacao_2) {
                    $('#nome_documentacao_1').html('Nome do arquivo selecionado: <i>' + json.arquivo_documentacao_1 + '</i>');
                }
                if (json.arquivo_documentacao_2) {
                    $('#nome_documentacao_2').html('Nome do arquivo selecionado: <i>' + json.arquivo_documentacao_2 + '</i>');
                }

                $.each(json, function (key, value) {
                    if ($('[name="' + key + '"]').prop('type') !== 'file') {
                        $('[name="' + key + '"]').val(value);
                    }
                });

                $('#modal_form').modal('show');
                $('.modal-title').text('Editar atividade');
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function reload_table() {
        table.ajax.reload(null, false);
    }


    function save() {
        $('#btnSave').text('Salvando...').attr('disabled', true);
        var url;

        if (save_method === 'add') {
            url = '<?php echo site_url('gestaoProcessos/ajaxAdd') ?>';
        } else {
            url = '<?php echo site_url('gestaoProcessos/ajaxUpdate') ?>';
        }

        $.ajax({
            'url': url,
            'type': 'POST',
            'data': new FormData($('#form')[0]),
            'enctype': 'multipart/form-data',
            'processData': false,
            'contentType': false,
            'cache': false,
            'success': function (json) {
                if (json.status) {
                    $('#modal_form').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }

                $('#btnSave').text('Salvar').attr('disabled', false);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSave').text('Salvar').attr('disabled', false);
            }
        });
    }


    function delete_processo(id) {
        if (confirm('Deseja remover?')) {

            $.ajax({
                'url': '<?php echo site_url('gestaoProcessos/excluir') ?>',
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


</script>

<?php
require_once "end_html.php";
?>
