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
					<li class="active">Gerenciar postos</li>
				</ol>
				<button class="btn btn-info" onclick="add_posto()"><i class="glyphicon glyphicon-plus"></i> Adicionar
					posto
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
										<?php echo form_dropdown('depto', $depto, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
									</div>
									<div class="col-md-3">
										<label class="control-label">Filtrar por área/cliente</label>
										<?php echo form_dropdown('area', $area, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
									</div>
									<div class="col-md-3">
										<label class="control-label">Filtrar por setor/unidade</label>
										<?php echo form_dropdown('setor', $setor, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
									</div>
									<div class="col-md-3">
										<label class="control-label">Filtrar por cargo</label>
										<?php echo form_dropdown('cargo', $cargo, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<label class="control-label">Filtrar por função</label>
										<?php echo form_dropdown('funcao', $funcao, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
									</div>
									<div class="col-md-3">
										<label class="control-label">Filtrar por contrato</label>
										<?php echo form_dropdown('contrato', $contrato, '', 'class="form-control input-sm filtro"'); ?>
									</div>
									<div class="col-md-2">
										<label class="control-label">Filtrar por mês</label>
										<select name="busca_mes" class="form-control input-sm">
											<option value="" selected="">Todos</option>
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
										<label class="control-label">Filtrar por ano</label>
										<input name="busca_ano" placeholder="aaaa" class="form-control input-sm"
											   maxlength="4" type="number">
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
						<th>Colaborador</th>
						<th>Mês/ano</th>
						<th>Valor posto</th>
						<th>Qtde dias</th>
						<th>Qtde horas</th>
						<th>Valor dia</th>
						<th>Valor hora</th>
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
						<h3 class="modal-title">Editar posto</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form" class="form-horizontal">
							<input type="hidden" value="" name="id"/>
							<div class="form-body">
								<div class="row form-group">
									<label class="control-label col-md-2">Colaborador</label>
									<div class="col-md-9">
										<?php echo form_dropdown('id_usuario', $usuarios, '', 'class="form-control"'); ?>
										<span class="help-block"></span>
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-2">Mês/ano</label>
									<div class="col-md-3">
										<select name="mes" id="mes" class="form-control">
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
										<input name="ano" id="ano" placeholder="aaaa" class="form-control text-right"
											   type="text">
										<span class="help-block"></span>
									</div>
									<div class="col-md-2">
										<button type="button" id="copiar_posto" class="btn btn-info"
												onclick="get_posto_anterior();">Copiar valores do posto anterior
										</button>
									</div>
								</div>
								<hr>
								<div class="row form-group">
									<label class="control-label col-md-2">Matrícula</label>
									<div class="col-md-3">
										<input name="matricula" type="text" class="form-control">
									</div>
									<label class="control-label col-md-1">Login</label>
									<div class="col-md-3">
										<input name="login" type="text" class="form-control">
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-2">Valor posto</label>
									<div class="col-md-3">
										<div class="input-group">
											<span class="input-group-addon" id="basic-addon1">R$</span>
											<input name="valor_posto" type="text" class="valor form-control text-right"
												   aria-describedby="basic-addon1">
										</div>
									</div>
									<div class="col-md-2">
										<button type="button" id="calcular_valor" class="btn btn-info">Calcular
											valores
										</button>
									</div>

								</div>
								<div class="row form-group">
									<label class="control-label col-md-2">Qtde. dias</label>
									<div class="col-md-6 form-inline">
										<input name="total_dias_mensais" type="text" style="width: 100px;"
											   class="valor form-control text-right">
										&emsp;Valor
										<div class="input-group">
											<span class="input-group-addon" id="basic-addon1">R$</span>
											<input name="valor_dia" type="text" style="width: 120px;"
												   class="form-control text-right" aria-describedby="basic-addon1">
										</div>
									</div>
									<label class="control-label col-md-2">Horário entrada</label>
									<div class="col-md-2">
										<input name="horario_entrada" type="text" class="hora form-control text-center">
									</div>
								</div>
								<div class="row form-group">
									<label class="control-label col-md-2">Qtde. horas</label>
									<div class="col-md-6 form-inline">
										<input name="total_horas_diarias" type="text" style="width: 100px;"
											   class="valor form-control text-right">
										&emsp;Valor
										<div class="input-group">
											<span class="input-group-addon" id="basic-addon1">R$</span>
											<input name="valor_hora" type="text" style="width: 120px;"
												   class="form-control text-right" aria-describedby="basic-addon1">
										</div>
									</div>
									<label class="control-label col-md-2">Horário saída</label>
									<div class="col-md-2">
										<input name="horario_saida" type="text" class="hora form-control text-center">
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

	</section>
</section>
<!--main content end-->

<?php
require_once APPPATH . 'views/end_js.php';
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Gerenciar funcionários';
    });
</script>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>

    var save_method; //for save method string
    var table;
    var avaliadores;

    $('#data_inicio, #data_termino').mask('00/00/0000');
    $('.hora').mask('00:00');

    $(document).ready(function () {

        //datatables
        table = $('#table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('st/postos/listar') ?>",
                "type": "POST",
                data: function (d) {
                    d.busca = $('#busca').serialize();
                    return d;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: '80%',
                    targets: [0]
                },
                {
                    className: "text-center",
                    targets: [1]
                },
                {
                    className: "text-right",
                    targets: [2, 3, 4, 5, 6]
                },
                {
                    className: "text-nowrap",
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false //set not orderable
                }
            ]
        });

        // atualizarFiltro();
    });

    function atualizarFiltro() {
        $.ajax({
            url: "<?php echo site_url('st/postos/atualizar_filtro') ?>",
            type: "POST",
            dataType: "JSON",
            data: $('#busca').serialize(),
            success: function (data) {
                $('[name="area"]').html($(data.area).html());
                $('[name="setor"]').html($(data.setor).html());
                $('[name="cargo"]').html($(data.cargo).html());
                $('[name="funcao"]').html($(data.funcao).html());
                $('[name="id_usuario"]').html($(data.id_usuario).html());
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    $('#limpa_filtro').on('click', function () {
        var busca = $('#busca').serialize();
        $.each(busca.split('&'), function (index, elem) {
            var vals = elem.split('=');
            if (vals[0] === 'mes' || vals[0] === 'ano') {
                $("[name='" + vals[0] + "']").val(vals[1]);
            } else {
                $("[name='" + vals[0] + "']").val($("[name='" + vals[0] + "'] option:first").val());
            }
        });
        atualizarFiltro();
    });

    $('[name="id_usuario"]').on('change', function () {
        $('#copiar_posto').prop('disabled', this.value.length === 0);
    });

    $('#calcular_valor').on('click', function () {
        calcular_valores();
    });

    $('.valor').on('change', function () {
        calcular_valores();
    });

    function calcular_valores() {
        var valor = $('[name="valor_posto"]').val();
        var dias = $('[name="total_dias_mensais"]').val();
        var horas = $('[name="total_horas_diarias"]').val();

        var valor_dia = 0;
        var valor_hora = 0;

        if (valor.length > 0) {
            if (dias.length > 0) {
                valor_dia = (valor / dias);
            }
            if (horas.length > 0) {
                valor_hora = (valor / horas);
            }
        }
        $('[name="valor_dia"]').val(valor_dia.toFixed(2));
        $('[name="valor_hora"]').val(valor_hora.toFixed(2));
    }

    function add_posto() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('[name="tipo"] option').prop('disabled', false);
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('[name="mes"]').val('<?php echo date('m') ?>');
        $('[name="ano"]').val('<?php echo date('Y') ?>');
        $('#copiar_posto').prop('disabled', true);
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar novo posto'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function get_posto_anterior() {
        $.ajax({
            url: "<?php echo site_url('st/postos/copiarUltimoPosto') ?>",
            type: "POST",
            dataType: "JSON",
            data: {id_usuario: $('[name="id_usuario"]').val()},
            success: function (json) {
                if (json.erro) {
                    alert(json.erro);
                    return false;
                }

                $('[name="matricula"]').val(json.matricula);
                $('[name="login"]').val(json.login);
                $('[name="horario_entrada"]').val(json.horario_entrada);
                $('[name="horario_saida"]').val(json.horario_saida);
                $('[name="total_dias_mensais"]').val(json.total_dias_mensais);
                $('[name="total_horas_diarias"]').val(json.total_horas_diarias);
                $('[name="valor_posto"]').val(json.valor_posto);
                $('[name="valor_dia"]').val(json.valor_dia);
                $('[name="valor_hora"]').val(json.valor_hora);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_posto(id) {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('#form input[type="hidden"]').val(''); // reset hidden input form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('st/postos/editar') ?>",
            type: "POST",
            dataType: "JSON",
            data: {id: id},
            success: function (json) {
                if (json.erro) {
                    alert(json.erro);
                    return false;
                }

                $.each(json, function (key, value) {
                    $('#form [name="' + key + '"]').val(value);
                });

                $('#copiar_posto').prop('disabled', false);
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
        var url;

        if (save_method === 'add') {
            url = "<?php echo site_url('st/postos/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('st/postos/ajax_update') ?>";
        }

        // ajax adding data to database
        $.ajax({
            url: '<?php echo site_url('st/postos/salvar') ?>',
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (json) {
                if (json.erro) {
                    alert(json.erro);
                } else if (json.status) //if success close modal and reload ajax table
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

    function delete_posto(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                url: '<?php echo site_url('st/postos/excluir') ?>',
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        reload_table();
                    }
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
