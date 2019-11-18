<?php require_once 'header.php'; ?>

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

	/*    .wizard > .steps > ul > li {
			width: 33% !important;
		}*/

	.wizard > .steps a, .wizard > .steps a:hover, .wizard > .steps a:active {
		background: #eee !important;
	}

	.wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
		background: #111343 !important;
		color: #fff;
		cursor: default;
	}

	.wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
		background: #758fb0 !important;
		color: #fff;
	}
</style>

<!--main content start-->
<section id="main-content">
	<section class="wrapper">

		<!-- page start-->
		<div class="row">
			<div class="col-sm-12">
				<section class="panel">
					<header class="panel-heading">
						<i class="glyphicons glyphicons-nameplate"></i>&nbsp;
						Gerenciar Estrutura Organizacional
					</header>
					<div class="panel-body">
						<div id="wizard">
							<h6>Departamentos</h6>
							<div style="padding: 0; border-top: 1px solid #ddd; height: auto;">
								<br>
								<button id="novo_depto" class="btn btn-info" onclick="add_depto()"><i
										class="glyphicon glyphicon-plus"></i> Novo departamento
								</button>
								<br>
								<div class="table-responsive">
									<table id="table_depto" class="table table-striped table-condensed"
										   cellspacing="0" width="100%">
										<thead>
										<tr>
											<th>Departamento</th>
											<th>Ações</th>
										</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>

							<h6>Áreas</h6>
							<div style="padding: 0; border-top: 1px solid #ddd;">
								<br>
								<button id="nova_area" class="btn btn-info" onclick="add_area()"><i
										class="glyphicon glyphicon-plus"></i> Nova área
								</button>
								<br>
								<div class="table-responsive">
									<table id="table_area" class="table table-striped table-condensed"
										   cellspacing="0" width="100%">
										<thead>
										<tr>
											<th>Departamento</th>
											<th>Área</th>
											<th>Ações</th>
										</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>

							<h6>Setores</h6>
							<div style="padding: 0; border-top: 1px solid #ddd;">
								<br>
								<button id="novo_setor" class="btn btn-info" onclick="add_setor()"><i
										class="glyphicon glyphicon-plus"></i> Novo setor
								</button>
								<br>
								<!--<div class="table-responsive">-->
								<table id="table_setor" class="table table-striped table-condensed" cellspacing="0"
									   width="100%">
									<thead>
									<tr>
										<th>Departamento</th>
										<th>Área</th>
										<th>Setor</th>
										<th>Ações</th>
									</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								<!--</div>-->
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_depto" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Adicionar departamento</h3>
					</div>
					<div class="modal-body form">
						<div id="alert"></div>
						<form action="#" id="form_depto" class="form-horizontal">
							<input type="hidden" value="<?php echo $empresa; ?>" name="id_empresa"/>
							<input type="hidden" value="" name="id"/>
							<div class="form-body">
								<div class="row form-group">
									<label class="control-label col-md-2">Depto.</label>
									<div class="col-md-9">
										<input name="nome" class="form-control" type="text" maxlength="255"
											   placeholder="Nome do departamento">
										<span class="help-block"></span>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSaveDepto" onclick="save_depto()" class="btn btn-success">
							Salvar
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_area" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Adicionar área</h3>
					</div>
					<div class="modal-body form">
						<div id="alert"></div>
						<form action="#" id="form_area" class="form-horizontal">
							<input type="hidden" value="" name="id_departamento"/>
							<input type="hidden" value="" name="id"/>
							<div class="form-body">
								<div class="row form-group">
									<label class="col-sm-2 control-label">Depto.</label>
									<div class="col-sm-9">
										<input name="nome_depto" class="form-control" type="text" readonly>
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-2">Área</label>
									<div class="col-md-9">
										<input name="nome" class="form-control" type="text" maxlength="255"
											   placeholder="Nome da área">
										<span class="help-block"></span>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSaveArea" onclick="save_area()" class="btn btn-success">
							Salvar
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Bootstrap modal -->
		<div class="modal fade" id="modal_setor" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Adicionar setor</h3>
					</div>
					<div class="modal-body form">
						<div id="alert"></div>
						<form action="#" id="form_setor" class="form-horizontal">
							<input type="hidden" value="" name="id_area"/>
							<input type="hidden" value="" name="id"/>
							<div class="form-body">
								<div class="row form-group">
									<label class="col-sm-2 control-label">Depto.</label>
									<div class="col-sm-9">
										<input name="nome_depto" class="form-control" type="text" readonly>
									</div>
								</div>
								<div class="row form-group">
									<label class="col-sm-2 control-label">Área</label>
									<div class="col-sm-9">
										<input name="nome_area" class="form-control" type="text" readonly>
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-2">Setor</label>
									<div class="col-md-9">
										<input name="nome" class="form-control" type="text" maxlength="255"
											   placeholder="Nome do setor">
										<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-2">CNPJ</label>
									<div class="col-md-9">
										<input name="cnpj" class="form-control cnpj" type="text">
										<span class="help-block"></span>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSaveSetor" onclick="save_setor()" class="btn btn-success">
							Salvar
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<!-- End Bootstrap modal -->

		<!-- page end-->
	</section>
</section>
<!--main content end-->

<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/jquery.steps.css?1') ?>" rel="stylesheet">

<?php require_once 'end_js.php'; ?>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'RhSuite - Corporate RH Tools: Gerenciar Estruturas';
    });

</script>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery-steps/jquery.steps.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

<script>
    var save_method;
    var table_depto, table_area, table_setor;
    var id_depto = '';
    var nome_depto = '';
    var id_area = '';
    var nome_area = '';

    var steps = $('#wizard').steps({
        'headerTag': "h6",
        'bodyTag': "div",
        // 'transitionEffect': 'slideLeft',
        'transitionEffect': 0,
        'autoFocus': true,
        'enableFinishButton': false,
        'enablePagination': false,
        'enableAllSteps': true,
        'titleTemplate': '#title#',
        'startIndex': <?= $indice ?? 0; ?>
    });

    $('.cnpj').mask('00.000.000/0000-00', {reverse: true});

    $(document).ready(function () {

        table_depto = $('#table_depto').DataTable({
            'info': true,
            'processing': true, //Feature control the processing indicator.
            'serverSide': true, //Feature control DataTables' server-side processing mode.
            'order': [], //Initial no order.
            'iDisplayLength': -1,
            'bLengthChange': false,
            'searching': false,
            'paging': false,
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('estruturas/listarDepartamentos') ?>',
                'type': 'POST'
            },
            'columnDefs': [
                {
                    'width': '100%',
                    'targets': [0]
                },
                {
                    'className': 'text-nowrap',
                    'targets': [-1], //last column
                    'orderable': false, //set not orderable
                    'searchable': false //set not orderable
                }
            ]
        });

        table_area = $('#table_area').DataTable({
            'info': true,
            'processing': true, //Feature control the processing indicator.
            'serverSide': true, //Feature control DataTables' server-side processing mode.
            'order': [], //Initial no order.
            'iDisplayLength': -1,
            'bLengthChange': false,
            'searching': false,
            'paging': false,
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('estruturas/listarAreas') ?>',
                'type': 'POST',
                'data': function (d) {
                    d.id_depto = id_depto;
                    return d;
                }
            },
            'columnDefs': [
                {
                    'width': '50%',
                    'targets': [0, 1]
                },
                {
                    'mRender': function (data) {
                        if (data === null) {
                            data = '<span class="text-muted">Nenhuma área encontrada</span>';
                        }
                        return data;
                    },
                    'targets': [1]
                },
                {
                    'className': 'text-nowrap',
                    'targets': [-1], //last column
                    'orderable': false, //set not orderable
                    'searchable': false //set not orderable
                }
            ],
            'drawCallback': function () {
                if (id_depto.length === 0) {
                    $('#nova_area').addClass('btn-warning').removeClass('btn-info');
                } else {
                    $('#nova_area').addClass('btn-info').removeClass('btn-warning');
                }
            }
        });

        table_setor = $('#table_setor').DataTable({
            'info': true,
            'processing': true, //Feature control the processing indicator.
            'serverSide': true, //Feature control DataTables' server-side processing mode.
            'order': [], //Initial no order.
            'iDisplayLength': -1,
            'bLengthChange': false,
            'searching': false,
            'paging': false,
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('estruturas/listarSetores') ?>',
                'type': 'POST',
                'data': function (d) {
                    d.id_depto = id_depto;
                    d.id_area = id_area;
                    return d;
                }
            },
            'columnDefs': [
                {
                    'width': '33%',
                    'targets': [0, 1, 2]
                },
                {
                    'mRender': function (data) {
                        if (data === null) {
                            data = '<span class="text-muted">Nenhum setor encontrado</span>';
                        }
                        return data;
                    },
                    'targets': [2]
                },
                {
                    'className': 'text-nowrap',
                    'targets': [-1], //last column
                    'orderable': false, //set not orderable
                    'searchable': false //set not orderable
                }
            ],
            'drawCallback': function () {
                if (id_depto.length === 0 || id_area.length === 0) {
                    $('#novo_setor').addClass('btn-warning').removeClass('btn-info');
                } else {
                    $('#novo_setor').addClass('btn-info').removeClass('btn-warning');
                }
            }
        });

    });


    function add_depto() {
        save_method = 'add';
        $('#form_depto')[0].reset(); // reset form on modals
        $('#form_depto [name="id"]').val('');
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_depto').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar novo departamento'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }


    function add_area() {
        if (id_depto.length === 0) {
            alert('Selecione o departamento onde será adicionada a nova área.');
            return false;
        }
        save_method = 'add';
        $('#form_area')[0].reset(); // reset form on modals
        $('#form_area [name="id"]').val('');
        $('#form_area [name="id_departamento"]').val(id_depto);
        $('#form_area [name="nome_depto"]').val(nome_depto).prop('readonly', true);
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_area').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar nova área'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }


    function add_setor() {
        if (id_depto.length === 0 && id_area.length === 0) {
            alert('Selecione o departamento e a área onde serão adicionados o novo setor.');
            return false;
        } else if (id_depto.length === 0 || id_area.length === 0) {
            alert('Selecione a área onde será adicionado o novo setor.');
            return false;
        }
        save_method = 'add';
        $('#form_setor')[0].reset(); // reset form on modals
        $('#form_setor [name="id"]').val('');
        $('#form_setor [name="id_area"]').val(id_area);
        $('#form_setor [name="nome_depto"]').val(nome_depto).prop('readonly', true);
        $('#form_setor [name="nome_area"]').val(nome_area).prop('readonly', true);
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_setor').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar novo setor'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }


    function edit_depto(id) {
        save_method = 'update';
        $('#form_depto')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            'url': '<?php echo site_url('estruturas/editarDepartamento') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {'id': id},
            'success': function (json) {
                if (json.erro) {
                    alert(json.erro);
                    return false;
                }

                $.each(json, function (key, value) {
                    $('#form_depto [name="' + key + '"]').val(value);
                });

                $('.modal-title').text('Editar departamento'); // Set title to Bootstrap modal title
                $('#modal_depto').modal('show');
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_area(id, nome_depto) {
        save_method = 'update';
        $('#form_area')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            'url': '<?php echo site_url('estruturas/editarArea') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {'id': id},
            'success': function (json) {
                if (json.erro) {
                    alert(json.erro);
                    return false;
                }

                $.each(json, function (key, value) {
                    $('#form_area [name="' + key + '"]').val(value);
                });

                $('#form_area [name="nome_depto"]').val(nome_depto).prop('readonly', true);
                $('.modal-title').text('Editar área'); // Set title to Bootstrap modal title
                $('#modal_area').modal('show');
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_setor(id, nome_depto, nome_area) {
        save_method = 'update';
        $('#form_setor')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            'url': '<?php echo site_url('estruturas/editarSetor') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {'id': id},
            'success': function (json) {
                if (json.erro) {
                    alert(json.erro);
                    return false;
                }

                $.each(json, function (key, value) {
                    $('#form_setor [name="' + key + '"]').val(value);
                });

                $('#form_setor [name="nome_depto"]').val(nome_depto).prop('readonly', true);
                $('#form_setor [name="nome_area"]').val(nome_area).prop('readonly', true);
                $('.modal-title').text('Editar setor'); // Set title to Bootstrap modal title
                $('#modal_setor').modal('show');
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function save_depto() {
        $.ajax({
            'url': '<?php echo site_url('estruturas/salvarDepartamento') ?>',
            'type': 'POST',
            'data': $('#form_depto').serialize(),
            'dataType': 'json',
            'beforeSend': function () {
                $('#btnSaveDepto').text('Salvando...').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) {
                    $('#modal_depto').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            },
            'complete': function () {
                $('#btnSaveDepto').text('Salvar').attr('disabled', false);
            }
        });
    }


    function save_area() {
        $.ajax({
            'url': '<?php echo site_url('estruturas/salvarArea') ?>',
            'type': 'POST',
            'data': $('#form_area').serialize(),
            'dataType': 'json',
            'beforeSend': function () {
                $('#btnSaveArea').text('Salvando...').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) {
                    $('#modal_area').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            },
            'complete': function () {
                $('#btnSaveArea').text('Salvar').attr('disabled', false);
            }
        });
    }


    function save_setor() {
        $.ajax({
            'url': '<?php echo site_url('estruturas/salvarSetor') ?>',
            'type': 'POST',
            'data': $('#form_setor').serialize(),
            'dataType': 'json',
            'beforeSend': function () {
                $('#btnSaveSetor').text('Salvando...').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) {
                    $('#modal_setor').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            },
            'complete': function () {
                $('#btnSaveSetor').text('Salvar').attr('disabled', false);
            }
        });
    }


    function delete_depto(id) {
        if (confirm('Deseja remover?')) {
            $.ajax({
                'url': '<?php echo site_url('estruturas/excluirDepartamento') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    alert('Error deleting data');
                }
            });
        }
    }


    function delete_area(id) {
        if (confirm('Deseja remover?')) {
            $.ajax({
                'url': '<?php echo site_url('estruturas/excluirArea') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    alert('Error deleting data');
                }
            });
        }
    }


    function delete_setor(id) {
        if (confirm('Deseja remover?')) {
            $.ajax({
                'url': '<?php echo site_url('estruturas/excluirSetor') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    alert('Error deleting data');
                }
            });
        }
    }


    function reload_table() {
        table_depto.ajax.reload(null, false);
        table_area.ajax.reload(null, false);
        table_setor.ajax.reload(null, false);
    }


    function nextArea(id, nome) {
        if (id_depto !== id || id_depto === '') {
            id_depto = id;
            nome_depto = nome;
            id_area = '';
            nome_area = '';
        }
        //steps.next(id);
        $('#wizard-t-1').trigger('click');
        reload_table();
    }


    function nextSetor(id, nome) {
        id_area = id;
        nome_area = nome;
        $('#wizard-t-2').trigger('click');
        reload_table();
    }

</script>

<?php require_once 'end_html.php'; ?>
