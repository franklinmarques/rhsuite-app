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
				<ol class="breadcrumb" style="margin-bottom: 5px;">
					<li>
						<a href="<?= site_url("competencias/cargos/") ?>">Cargo-função: </a> <?= $options_cargos['cargo_funcao'] ?>
					</li>
					<li class="active">Competência <?= $nome_tipo ?></li>
				</ol>
				<div class="row form-inline">
					<div class="col-sm-6 col-md-5">
						<button class="btn btn-success" onclick="add_competencias()"><i
								class="glyphicon glyphicon-plus"></i> Adicionar competência <?= $nome_tipo ?>
						</button>
						<button class="btn btn-default" onclick="javascript:history.back()"><i
								class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
						</button>
					</div>
					<div class="col-sm-6 col-md-7 text-danger text-right">
						<em>* A soma dos pesos deve ser igual a 100 &nbsp;</em>
						<!--<button class="btn btn-primary" type="button" onclick="distribuir_peso()">Distribuir peso</button>-->
					</div>
				</div>
				<br/>
				<br/>
				<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
					<tr>
						<th>Competência <?= $nome_tipo ?></th>
						<th>Tipo</th>
						<th>Peso <span class="text-danger">*</span></th>
						<th>Grau comp.</th>
						<th><?= $ncf ?></th>
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
			<div class="modal-dialog" style="width: 990px">
				<div class="modal-content" style="width: 990px">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Competências Form</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form" class="form-horizontal">
							<input type="hidden" value="" name="id"/>
							<input type="hidden" name="id_cargo" id="id_cargo"
								   value="<?= $options_cargos['id_cargo'] ?>">
							<input type="hidden" name="id_modelo" id="id_modelo"/>
							<div class="form-body">
								<div class="form-group">
									<label class="control-label col-md-3">Cargo/função</label>
									<div class="col-md-9">
										<label class="sr-only"
											   style="margin-top: 7px;"><?= $options_cargos['cargo_funcao'] ?></label>
										<p class="form-control-static"><?= $options_cargos['cargo_funcao'] ?></p>
										<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group input_competencia">
									<div class="col-md-7">
										<div class="row">
											<label class="control-label col-md-5">Competência</label>

											<div class="col-md-7">
												<input id="nome" name="nome" placeholder="Digite a competência "
													   class="form-control" type="text">
												<span class="help-block"></span>
											</div>
										</div>
										<?php if ($tipo): ?>
											<input type="hidden" name="tipo_competencia" id="tipo_competencia" value="">
										<?php else: ?>
											<div class="row form-group">
												<label class="control-label col-md-5">Tipo de competência</label>
												<div class="col-md-7">
													<label class="radio-inline">
														<input type="radio" name="tipo_competencia"
															   class="tipo_competencia" value="T"> Técnica
													</label>
													<label class="radio-inline">
														<input type="radio" name="tipo_competencia"
															   class="tipo_competencia" value="C"> Comportamental
													</label>
												</div>
											</div>
										<?php endif; ?>
										<div class="row">
											<label class="control-label col-md-5">Peso da competência</label>
											<div class="col-md-3">
												<input type="number" class="form-control text-right"
													   placeholder="0 - 100" name="peso" id="peso" min="0" max="100"/>

												<span class="help-block"></span>
											</div>
										</div>
									</div>
									<div class="col-md-5">
										<div class="panel panel-default">
											<div class="panel-heading">Biblioteca de competências</div>
											<div class="panel-body">
												<div style="overflow-x: hidden;overflow-y: scroll; height: 170px">
													<ul id="sugestao" class="list-group">
														<?php foreach ($competencias_sugestao as $k => $sugestao): ?>
															<li id="<?= $sugestao->id ?>" style="cursor:pointer;"
																class="sugestao_competencia list-group-item"
																data-tipo="<?= $sugestao->tipo ?>"><?= $sugestao->nome ?></li>
														<?php endforeach; ?>
													</ul>
													<span class="help-block"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
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

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Competência <?= $nome_tipo ?>';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

<script>

    var save_method; //for save method string
    var table;

    $(document).ready(function () {

        $('.sugestao_competencia').click(function () {
            $("#nome").val($(this).text());
            $("#id_modelo").val($(this).attr('id'));
            var tipo = $(this).data('tipo');
            $('#tipo_competencia').val(tipo);
            $('.tipo_competencia').val([tipo]);
        });

        //datatables
        table = $('#table').DataTable({
            'processing': true, //Feature control the processing indicator.
            'serverSide': true, //Feature control DataTables' server-side processing mode.
            'iDisplayLength': 25,
            'order': [], //Initial no order.
            // Load data for the table's content from an Ajax source
            'ajax': {
                'url': '<?php echo site_url('competencias/tipo/ajax_list/' . $this->uri->rsegment(3) . '/' . $tipo) ?>',
                'type': 'POST',
                'timeout': 9000
            },
            //Set column definition initialisation properties.
            'columnDefs': [
                {
                    'width': '100%',
                    'targets': [0]
                },
                {
                    'className': 'text-center',
                    'visible': <?= $tipo ? 'false' : 'true' ?>,
                    'targets': [1]
                },
                {
                    'className': 'text-right text-nowrap',
                    'cellType': 'td',
                    'targets': [2, 3, 4]
                },
                {
                    'className': 'text-nowrap',
                    'targets': [-1], //last column
                    'orderable': false, //set not orderable
                    'searchable': false //set not orderable
                }
            ]
        });

        //datepicker
        $('.datepicker').datepicker({
            'autoclose': true,
            'format': 'yyyy-mm-dd',
            'todayHighlight': true,
            'orientation': 'top auto',
            'todayBtn': true
        });

    });

    function add_competencias() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('[name="id"], [name="id_modelo"]').val('');
        $('#tipo_competencia').val('<?= $tipo ?>');
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.selectbox_nivel2').hide();
        $('.modal-title').text('Adicionar competência <?= $nome_tipo ?>'); // Set Title to Bootstrap modal title
    }

    function edit_competencias(id) {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            'url': '<?php echo site_url('competencias/tipo/ajax_edit') ?>',
            'type': 'POST',
            'dataType': 'json',
            'timeout': 9000,
            'data': {
                'id': id
            },
            'success': function (json) {
                $('[name="id"]').val(json.id);
                $('[name="nome"]').val(json.nome);
                $('#tipo_competencia').val(json.tipo_competencia);
                $('.tipo_competencia').val([json.tipo_competencia]);
                $('[name="peso"]').val(json.peso);
                $('[name="id_modelo"]').val(json.id_modelo);

                $('.modal-title').text('Editar competência <?= $nome_tipo ?>'); // Set title to Bootstrap modal title
                $('#modal_form').modal('show');
            }
        });

    }

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }


    function save() {
        var url;
        if (save_method === 'add') {
            url = "<?php echo site_url('competencias/tipo/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('competencias/tipo/ajax_update') ?>";
        }

        // ajax adding data to database
        $.ajax({
            'url': url,
            'type': 'POST',
            'data': $('#form').serialize(),
            'dataType': 'json',
            'timeout': 9000,
            'beforeSend': function () {
                $('#btnSave').text('Salvando...').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) //if success close modal and reload ajax table
                {
                    $('#modal_form').modal('hide');
                    reload_table();
                }
            },
            'complete': function () {
                $('#btnSave').text('Salvar').attr('disabled', false);
            }
        });
    }


    function add_nivel3(id) {
        save_method = 'addNivel2';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Add Nível 3'); // Set title to Bootstrap modal title
        $('.combo_nivel1').show();
        $('.selectbox_nivel2').show();
        $('.input_nivel3').show();
        $('.input_nivel2').hide();
        $('.radio_nivel3').show();

    }

    function delete_competencias(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                'url': '<?php echo site_url('competencias/tipo/ajax_delete') ?>',
                'type': 'POST',
                'dataType': 'json',
                'timeout': 9000,
                'data': {
                    'id': id
                },
                'success': function (data) {
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    reload_table();
                }
            });

        }
    }

    function distribuir_peso(id) {
        if (confirm('Deseja distribuir o peso de todas as competências técnicas?\nEssa operação é irreversível!')) {
            // ajax delete data to database
            $.ajax({
                'url': '<?php echo site_url('competencias/tipo/ajax_delete') ?>',
                'type': 'POST',
                'dataType': 'json',
                'timeout': 9000,
                'data': {
                    'id': id
                },
                'success': function (json) {
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    reload_table();
                }
            });

        }
    }

</script>

<?php
require_once APPPATH . 'views/end_html.php';
?>

