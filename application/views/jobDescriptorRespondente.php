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
</style>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">

		<!-- page start-->
		<div class="row">
			<div class="col-md-12">
				<div id="alert"></div>
				<section class="panel">
					<header class="panel-heading">
						<i class="glyphicons glyphicons-nameplate"></i>&nbsp; Descritivos de Cargos/Funções
					</header>
					<br>
					<table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
						   width="100%">
						<thead>
						<tr>
							<th>Cargo</th>
							<th>Função</th>
							<th>Versões</th>
							<th>Ações</th>
						</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</section>
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
						<h3 class="modal-title">Editar descritivos de cargo/função</h3>
					</div>
					<div class="modal-body form">
						<div id="alert"></div>
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<strong>Cargo: </strong><span id="nome_cargo"></span><br>
								<strong>Função: </strong><span id="nome_funcao"></span><br>
								<strong>Versão: </strong><span id="versao"></span>
							</div>
						</div>
						<hr style="margin-bottom: 0px;">
						<form action="#" id="form" class="form-horizontal" autocomplete="off">
							<input type="hidden" value="" name="id"/>
							<input type="hidden" value="" name="id_descritor"/>
							<div class="form-body">
								<div class="row form-group" id="sumario">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Descrição sumária</strong></h4>
										<textarea name="sumario" class="form-control" rows="1"></textarea>
										<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="formacao_experiencia">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Formação e experiência</strong><h4>
												<textarea name="formacao_experiencia" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="condicoes_gerais_exercicio">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Condições gerais de exercício</strong><h4>
										<textarea name="condicoes_gerais_exercicio" class="form-control"
												  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="codigo_internacional_CIUO88">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Código Internacional CIUO88</strong><h4>
										<textarea name="codigo_internacional_CIUO88" class="form-control"
												  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="notas">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Notas</strong><h4>
												<textarea name="notas" class="form-control" rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="recursos_trabalho">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Recursos de trabalho</strong><h4>
												<textarea name="recursos_trabalho" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="atividades">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Atribuições e atividades</strong><h4>
												<textarea name="atividades" class="form-control" rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="responsabilidade">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Responsabilidades</strong><h4>
												<textarea name="responsabilidades" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="conhecimentos_habilidades">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Conhecimentos e habilidades</strong><h4>
										<textarea name="conhecimentos_habilidades" class="form-control"
												  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="habilidades_basicas">
									<h4 class="text-primary"><strong>Conhecimentos e habilidades - Básicas</strong>
										<h4>
											<div class="col-md-12">
												<textarea name="habilidades_basicas" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
											</div>
								</div>
								<div class="row form-group" id="habilidades_intermediarias">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Conhecimentos e habilidades -
												Intermediárias</strong><h4>
										<textarea name="habilidades_intermediarias" class="form-control"
												  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="habilidades_avancadas">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Conhecimentos e habilidades -
												Avançadas</strong><h4>
												<textarea name="habilidades_avancadas" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="ambiente_trabalho">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Especificações gerais - Ambiente de
												trabalho</strong><h4>
												<textarea name="ambiente_trabalho" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="condicoes_trabalho">
									<h4 class="text-primary"><strong>Especificações gerais - Condições de
											trabalho</strong>
										<h4>
											<div class="col-md-12">
												<textarea name="condicoes_trabalho" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
											</div>
								</div>
								<div class="row form-group" id="esforcos_fisicos">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Especificações gerais - Esforços
												físicos</strong><h4>
												<textarea name="esforcos_fisicos" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="grau_autonomia">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Especificações gerais - Grau de
												autonomia</strong><h4>
												<textarea name="grau_autonomia" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="grau_complexidade">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Especificações gerais - Grau de
												complexidade</strong><h4>
												<textarea name="grau_complexidade" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="grau_iniciativa">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Especificações gerais - Grau de
												iniciativa</strong><h4>
												<textarea name="grau_iniciativa" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="competencias_tecnicas">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Competências Técnicas</strong><h4>
												<textarea name="competencias_tecnicas" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="competencias_comportamentais">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Competências Comportamentais</strong><h4>
										<textarea name="competencias_comportamentais" class="form-control"
												  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="tempo_experiencia">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Tempo de experiência no cargo/função</strong>
											<h4>
												<textarea name="tempo_experiencia" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="formacao_minima">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Formação/escolaridade mínima</strong><h4>
												<textarea name="formacao_minima" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="formacao_plena">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Formação/escolaridade para exercício
												pleno</strong><h4>
												<textarea name="formacao_plena" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="esforcos_mentais">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Esforços mentais</strong><h4>
												<textarea name="esforcos_mentais" class="form-control"
														  rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="grau_pressao">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Grau de pressão/estresse</strong><h4>
												<textarea name="grau_pressao" class="form-control" rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="campo_livre1">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Campo livre 1</strong><h4>
												<textarea name="campo_livre1" class="form-control" rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="campo_livre2">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Campo livre 2</strong><h4>
												<textarea name="campo_livre2" class="form-control" rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="campo_livre3">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Campo livre 3</strong><h4>
												<textarea name="campo_livre3" class="form-control" rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="campo_livre4">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Campo livre 4</strong><h4>
												<textarea name="campo_livre4" class="form-control" rows="1"></textarea>
												<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group" id="campo_livre5">
									<div class="col-md-12">
										<h4 class="text-primary"><strong>Campo livre 5</strong><h4>
												<textarea name="campo_livre5" class="form-control" rows="1"></textarea>
												<span class="help-block"></span>
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
        document.title = 'CORPORATE RH - LMS - Descritivos de cargo/função';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/ckeditor/adapters/jquery.js'); ?>"></script>

<script>

    var table;

    $(document).ready(function () {
        $("#form textarea").ckeditor({
            'height': '100',
            'toolbar': [
                ['Source', 'Bold', 'Italic', 'Underline', '-', 'Undo', 'Redo', '-',
                    'Outdent', 'Indent', 'NumberedList', 'BulletedList', '-',
                    'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-',
                    'Link', 'Smiley', 'TextColor', 'BGColor', '-', 'Font', 'FontSize'
                ]
            ],
            'removePlugins': 'elementspath'
        });

        //datatables
        table = $('#table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            iDisplayLength: 500,
            lengthMenu: [[5, 10, 25, 50, 100, 500], [5, 10, 25, 50, 100, 500]],
            "order": [[0, 'asc'], [1, 'asc'], [3, 'asc']], //Initial no order.
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('jobDescriptorRespondente/ajax_list') ?>",
                "type": "POST"
            },
            rowsGroup: [0, 1, 2],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: '50%',
                    targets: [0, 1]
                },
                {
                    className: 'text-center text-nowrap',
                    searchable: false,
                    targets: [2]
                },
                {
                    className: "text-nowrap",
                    "targets": [3], //last column
                    "orderable": false, //set not orderable
                    "searchable": false //set not orderable
                }
            ]
        });

    });


    function edit_descritivo(id_descritor) {
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('jobDescriptorRespondente/ajax_edit') ?>",
            type: "POST",
            dataType: "JSON",
            data: {id_descritor: id_descritor},
            success: function (json) {
                $('[name="id"]').val(json.descritivos.id);
                $('[name="id_descritor"]').val(json.estruturas.id);

                $('#nome_cargo').html(json.nome_cargo);
                $('#nome_funcao').html(json.nome_funcao);
                $('#versao').html(json.versao);
                $('#campo_livre1 label').html(json.id_campo_livre1);
                $('#campo_livre2 label').html(json.id_campo_livre2);
                $('#campo_livre3 label').html(json.id_campo_livre3);
                $('#campo_livre4 label').html(json.id_campo_livre4);
                $('#campo_livre5 label').html(json.id_campo_livre5);

                $.each(json.estruturas, function (elem, value) {
                    if (value > 0) {
                        $('#' + elem).show();
                    } else {
                        $('#' + elem).hide();
                    }
                });

                $.each(json.descritivos, function (elem, value) {
                    $('[name="' + elem + '"]').val(value);
                });

                $('#modal_form').modal('show');
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

        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('jobDescriptorRespondente/ajax_save') ?>",
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


</script>

<?php
require_once "end_html.php";
?>
