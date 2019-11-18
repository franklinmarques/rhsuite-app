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
					<li><a href="<?= site_url('ei/apontamento') ?>">Apontamentos diários</a></li>
					<li class="active">Vincular supervisores</li>
				</ol>
				<button class="btn btn-info" onclick="add_supervisor()"><i
						class="glyphicon glyphicon-plus"></i> Vincular supervisor
				</button>
				<button class="btn btn-default" onclick="javascript:history.back()"><i
						class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
				</button>
				<a id="pdf" class="btn btn-sm btn-danger" style="float:right;"
				   href="<?= site_url('ei/supervisores/pdf/'); ?>"
				   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
				<br/>
				<br/>
				<div class="row">
					<div class="col-md-12">
						<div class="well well-sm">
							<form action="#" id="busca" class="form-horizontal" autocomplete="off">
								<div class="row">
									<div class="col-md-2">
										<label class="control-label">Ano/semestre</label>
										<?php echo form_dropdown('busca[ano_semestre]', $busca_anoSemestre, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
									</div>
									<div class="col-md-3">
										<label class="control-label">Supervisor</label>
										<?php echo form_dropdown('busca[supervisor]', $busca_supervisor, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
									</div>
									<div class="col-md-3">
										<label class="control-label">Cliente/diretoria</label>
										<?php echo form_dropdown('busca[diretoria]', $busca_diretoria, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
									</div>
									<div class="col-md-3">
										<label class="control-label">Unidade de ensino</label>
										<?php echo form_dropdown('busca[escola]', $busca_escola, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
									</div>
									<div class="col-md-1" style="padding-left: 5px;">
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
				<table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
					   width="100%">
					<thead>
					<tr>
						<th>Supervisor</th>
						<th>Ano/semestre</th>
						<th>Ações</th>
						<th>Unidade de ensino</th>
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
						<h3 class="modal-title">Adicionar supervisor</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form" class="form-horizontal" autocomplete="off">
							<input type="hidden" name="id" value="">
							<input type="hidden" name="is_supervisor" value="1">
							<div class="row form-group">
								<label class="control-label col-md-2">Departamento<span
										class="text-danger"> *</span></label>
								<div class="col-md-9">
									<?php echo form_dropdown('depto', $deptos, '', 'id="depto" class="form-control"'); ?>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-2">Área<span
										class="text-danger"> *</span></label>
								<div class="col-md-9">
									<?php echo form_dropdown('area', $areas, '', 'id="area" class="form-control"'); ?>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-2">Setor<span
										class="text-danger"> *</span></label>
								<div class="col-md-9">
									<?php echo form_dropdown('setor', $setores, '', 'id="setor" class="form-control"'); ?>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-2">Supervisor<span
										class="text-danger"> *</span></label>
								<div class="col-md-9">
									<?php echo form_dropdown('id_usuario', $supervisores, '', 'id="supervisor" class="form-control"'); ?>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-2">Ano<span class="text-danger"> *</span></label>
								<div class="col-md-2">
									<input name="ano" class="form-control text-center ano" placeholder="aaaa"
										   type="text">
								</div>
								<div class="col-md-3">
									<label class="radio-inline">
										<input type="radio" name="semestre" value="1" checked> 1&ordm; semestre
									</label>
									<label class="radio-inline">
										<input type="radio" name="semestre" value="2"> 2&ordm; semestre
									</label>
								</div>
								<label class="control-label col-md-2">Carga horária</label>
								<div class="col-md-2">
									<input name="carga_horaria" class="form-control text-center hora"
										   placeholder="hh:mm" type="text">
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-12">
									<?php echo form_multiselect('funcoes[]', $funcoes, array(), 'id="funcoes" class="form-control demo1"'); ?>
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
		<div class="modal fade" id="modal_escolas" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Vincular supervisor a unidades de ensino</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form_escolas" class="form-horizontal" autocomplete="off">
							<input type="hidden" name="id_coordenacao" value="">
							<div class="row">
								<label class="control-label col-md-3">Supervisor(a):</label>
								<div class="col-md-8">
									<p id="nome_supervisor" class="form-control-static"></p>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-3">Ano/semestre:</label>
								<div class="col-md-8">
									<p id="ano_semestre" class="form-control-static"></p>
								</div>
							</div>
							<div class="row form-group">
								<label class="control-label col-md-3">Cliente/diretoria</label>
								<div class="col-md-8">
									<?php echo form_dropdown('', $diretorias, '', 'id="id_diretoria" class="form-control input-sm"'); ?>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-12">
									<?php echo form_multiselect('id_escolas[]', array(), array(), 'id="id_escolas" class="form-control demo2"'); ?>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSaveEscolas" onclick="save_escolas()" class="btn btn-success">
							Salvar
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
    });
</script>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>

    var save_method;
    var table, demo1, demo2;

    $('.ano').mask('0000');
    $('.hora').mask('00:00');

    $(document).ready(function () {

        //datatables
        table = $('#table').DataTable({
            'processing': true, //Feature control the processing indicator.
            'serverSide': true, //Feature control DataTables' server-side processing mode.
            'iDisplayLength': 500,
            'lengthMenu': [[5, 10, 25, 50, 100, 250, 500], [5, 10, 25, 50, 100, 250, 500]],
            // Load data for the table's content from an Ajax source
            'ajax': {
                'url': '<?php echo site_url('ei/supervisores/ajax_list') ?>',
                'type': 'POST',
                'data': function (d) {
                    d.busca = $('#busca').serialize();
                    return d;
                }
            },
            //Set column definition initialisation properties.
            'columnDefs': [
                {
                    'width': '50%',
                    'targets': [0]
                },
                {
                    'createdCell': function (td, cellData, rowData, row, col) {
                        if (rowData[col] === '') {
                            $(td).addClass('text-center').css('vertical-align', 'middle');
                            $(td).html('<span class="text-muted">Nenhuma escola vinculada</span>');
                        } else {
                            $(td).html(rowData[col]);
                        }
                    },
                    'width': '50%',
                    'targets': [3]
                },
                {
                    'className': 'text-center',
                    'targets': [1]
                },
                {
                    'className': 'text-center text-nowrap',
                    'targets': [-2],//last column
                    'orderable': false, //set not orderable
                    'searchable': false //set not orderable
                }
            ],
            'rowsGroup': [0, 1, 2, -1, 3]
        });

        demo1 = $('.demo1').bootstrapDualListbox({
            'nonSelectedListLabel': 'Funções disponíveis',
            'selectedListLabel': 'Funções supervisionados',
            'preserveSelectionOnMove': 'moved',
            'moveOnSelect': false,
            'filterPlaceHolder': 'Filtrar',
            'helperSelectNamePostfix': false,
            'selectorMinimalHeight': 132,
            'infoText': false
        });

        demo2 = $('.demo2').bootstrapDualListbox({
            'nonSelectedListLabel': 'Unidades disponíveis',
            'selectedListLabel': 'Unidades selecionadas',
            'preserveSelectionOnMove': 'moved',
            'moveOnSelect': false,
            'filterPlaceHolder': 'Filtrar',
            'helperSelectNamePostfix': false,
            'selectorMinimalHeight': 132,
            'infoText': false
        });

    });


    $('#depto, #area, #setor').on('change', function () {
        $.ajax({
            'url': '<?php echo site_url('ei/supervisores/atualizar_supervisores') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'depto': $('#depto').val(),
                'area': $('#area').val(),
                'setor': $('#setor').val(),
                'supervisor': $('#supervisor').val()
            },
            'success': function (json) {
                $('#area').html($(json.area).html());
                $('#setor').html($(json.setor).html());
                $('#supervisor').html($(json.supervisor).html());
            }
        });
    });


    $('#id_diretoria').on('change', function () {
        $.ajax({
            'url': '<?php echo site_url('ei/supervisores/atualizar_unidades') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'id_diretoria': $('#id_diretoria').val(),
                'id_escolas': $('#id_escolas').val(),
            },
            'success': function (json) {
                $('#id_escolas').html($(json.escolas).html());
                demo2.bootstrapDualListbox('refresh', true);
            }
        });
    });

    function atualizarFiltro() {
        $.ajax({
            'url': '<?php echo site_url('ei/supervisores/atualizar_filtro') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': $('#busca').serialize(),
            'success': function (json) {
                $('[name="busca[supervisor]"]').html($(json.supervisor).html());
                $('[name="busca[diretoria]"]').html($(json.diretoria).html());
                $('[name="busca[escola]"]').html($(json.escola).html());
                reload_table();
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

    function add_supervisor() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('#form [name="id"]').val('');
        $('[name="tipo"] option').prop('disabled', false);
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('.modal-title').text('Adicionar supervisor');
        $('#depto, #area, #setor, #supervisor').val('');
        $('#depto').trigger('change');
        $('#funcoes').val('');
        demo1.bootstrapDualListbox('refresh', true);

        $('#modal_form').modal('show'); // show bootstrap modal
        $('.combo_nivel1').hide();
    }

    function edit_supervisor(id_supervisor) {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('#form input[type="hidden"]').val(''); // reset hidden input form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            'url': '<?php echo site_url('ei/supervisores/ajax_edit') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'id': id_supervisor
            },
            'success': function (json) {
                $('#depto').val(json.depto);
                $('#area').html($(json.area).html());
                $('#setor').html($(json.setor).html());
                $('#supervisor').html($(json.id_usuario).html());
                $('#form [name="id"]').val(json.id);
                $('#form [name="is_supervisor"]').val(json.is_supervisor);
                $('#form [name="ano"]').val(json.ano);
                $('#form [name="carga_horaria"]').val(json.carga_horaria);
                $('#form [name="semestre"][value="' + json.semestre + '"]').prop('checked', true);
                $('#funcoes').html($(json.cargos).html());
                demo1.bootstrapDualListbox('refresh', true);

                $('.modal-title').text('Editar supervisor');
                $('#modal_form').modal('show');
            }
        });
    }

    function vincular_unidades(id_supervisor) {
        save_method = 'update';
        $('#form_escolas')[0].reset(); // reset form on modals
        $('#form_escolas input[type="hidden"]').val(''); // reset hidden input form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            'url': '<?php echo site_url('ei/supervisores/ajax_editUnidades') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'id': id_supervisor
            },
            'success': function (json) {
                $('#form_escolas [name="id_coordenacao"]').val(json.id);
                $('#nome_supervisor').html(json.nome_supervisor);
                $('#ano_semestre').html(json.ano_semestre);
                $('#id_escolas').html($(json.escolas).html());
                demo2.bootstrapDualListbox('refresh', true);

                $('.modal-title').text('Gerenciar unidades de ensino do supervisor');
                $('#modal_escolas').modal('show');
            }
        });
    }

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax
    }

    function save() {
        var url;
        if (save_method === 'add') {
            url = '<?php echo site_url('ei/supervisores/ajax_add') ?>';
        } else {
            url = '<?php echo site_url('ei/supervisores/ajax_update') ?>';
        }

        // ajax adding data to database
        $.ajax({
            'url': url,
            'type': 'POST',
            'data': $('#form').serialize(),
            'dataType': 'json',
            'beforeSend': function () {
                $('#btnSave').text('Salvando...').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) //if success close modal and reload ajax table
                {
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

    function save_escolas() {
        // ajax adding data to database
        $.ajax({
            'url': '<?php echo site_url('ei/supervisores/salvarEscolas') ?>',
            'type': 'POST',
            'data': $('#form_escolas').serialize(),
            'dataType': 'json',
            'beforeSend': function () {
                $('#btnSaveEscolas').text('Salvando...').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) //if success close modal and reload ajax table
                {
                    $('#modal_escolas').modal('hide');
                    reload_table();
                }
            },
            'complete': function () {
                $('#btnSaveEscolas').text('Salvar').attr('disabled', false);
            }
        });
    }

    function delete_supervisor(id_supervisor) {
        if (confirm('Deseja remover o(a) supervisor(a)?')) {
            // ajax delete data to database
            $.ajax({
                'url': '<?php echo site_url('ei/supervisores/ajax_delete') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id': id_supervisor
                },
                'success': function (json) {
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    reload_table();
                }
            });
        }
    }

    function desvincular_unidade(id_supervisor) {
        if (confirm('Deseja desvincular a unidade de ensino do(a) supervisor(a)?')) {
            // ajax delete data to database
            $.ajax({
                'url': '<?php echo site_url('ei/supervisores/ajax_deleteEscola') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id': id_supervisor
                },
                'success': function (json) {
                    reload_table();
                }
            });
        }
    }

</script>

<?php
require_once APPPATH . 'views/end_html.php';
?>
