<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CORPORATE RH - LMS - Gerenciar itens de Manuenção Periódica de Ativos</title>
	<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

	<!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
	<!--WARNING: Respond.js doesn't work if you view the page via file://-->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<script src="<?= base_url("assets/js/jquery.js"); ?>"></script>
	<style>
		@page {
			margin: 40px 20px;
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

		tr.group, tr.group:hover {
			background-color: #ddd !important;
		}
	</style>
</head>
<body style="color: #000;">
<div class="container-fluid">
	<br>
	<button class="btn btn-info" onclick="add_manutencao()"><i class="glyphicon glyphicon-plus"></i>
		Adicionar item de manutenção periódica de ativos
	</button>
	<button class="btn btn-default" onclick="javascript:window.close()"><i
			class="glyphicon glyphicon-remove"></i> Fechar
	</button>
	<br>
	<br>
	<h5 class="text-primary">
		<strong>Unidade: <?= $unidade ?></strong>
	</h5>
	<h5 class="text-primary">
		<strong>Andar: <?= $andar ?></strong>
	</h5>
	<h5 class="text-primary">
		<strong>Sala: <?= $sala ?></strong>
	</h5>
	<?php if ($nomeAtivo): ?>
		<h5 class="text-primary">
			<strong>Ativo: <?= $nomeAtivo ?></strong>
		</h5>
	<?php endif; ?>
	<br>
	<table id="table" class="table table-striped table-condensed" cellspacing="0" width="100%">
		<thead>
		<tr>
			<th>Item</th>
			<th>Ações</th>
		</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

	<div class="modal fade" id="modal_form" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h3 class="modal-title">Cadastro de itens de manutenção periódica de ativos</h3>
				</div>
				<div class="modal-body form">
					<form action="#" id="form" class="form-horizontal">
						<input type="hidden" value="" name="id"/>
						<input type="hidden" value="<?= $idItem; ?>" name="id_item"/>
						<div class="row form-group">
							<label class="control-label col-md-3">Nome do item<span
									class="text-danger"> *</span></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="nome"
									   placeholder="Digite o nome do item">
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" id="btnSave" onclick="save()" class="btn btn-success">Salvar</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	</div>

</div>
<div id="script_js" style="display: none;"></div>
<script src="<?= base_url("assets/bs3/js/bootstrap.min.js"); ?>"></script>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>
    var save_method;
    var table;

    $(document).ready(function () {
        $.ajaxSetup({
            'type': 'POST',
            'dataType': 'json',
            'error': function (jqXHR, textStatus, errorThrown) {
                alert(textStatus + ' ' + jqXHR.status + ': ' + (jqXHR.status === 0 ? 'Disconnected' : errorThrown));
                if (jqXHR.status === 401) {
                    window.close();
                }
            }
        });

        $('.meses').mask('0000');

        table = $('#table').DataTable({
            'processing': true,
            'serverSide': true,
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('facilities/ativosManutencao/ajaxList/' . $this->uri->rsegment(3)) ?>',
                'type': 'POST'
            },
            'columnDefs': [
                {
                    'width': '100%',
                    'targets': [0]
                },
                {
                    'className': 'text-center text-nowrap',
                    'targets': [-1],
                    'orderable': false,
                    'searchable': false
                }
            ]
        });

    });

    function add_manutencao() {
        save_method = 'add';
        $('#form')[0].reset();
        $('#form [name="id"]').val('');
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $('.modal-title').text('Cadastrar item de manutenção periódica de ativos');
        $('#modal_form').modal('show');
        $('.combo_nivel1').hide();
    }

    function edit_manutencao(id) {
        save_method = 'update';
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('.combo_nivel1').hide();

        $.ajax({
            url: '<?php echo site_url('facilities/ativosManutencao/ajaxEdit/') ?>',
            data: {
                id: id,
                busca: $('.filtro').serialize()
            },
            success: function (json) {
                $.each(json, function (key, value) {
                    $('#form [name="' + key + '"]').val(value);
                });

                $('.modal-title').text('Editar item de manutenção periódica de ativos');
                $('#modal_form').modal('show');
            }
        });
    }

    function save() {
        $('#btnSave').text('Salvando...').attr('disabled', true);
        var url;
        if (save_method === 'add') {
            url = '<?php echo site_url('facilities/ativosManutencao/ajaxAdd') ?>';
        } else {
            url = '<?php echo site_url('facilities/ativosManutencao/ajaxUpdate') ?>';
        }

        $.ajax({
            url: url,
            data: $('#form').serialize(),
            success: function (json) {
                if (json.status) {
                    $('#modal_form').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            complete: function () {
                $('#btnSave').text('Salvar').attr('disabled', false);
            }
        });
    }

    function delete_manutencao(id) {
        if (confirm('Deseja remover?')) {
            $.ajax({
                url: '<?php echo site_url('facilities/ativosManutencao/ajaxDelete') ?>',
                data: {id: id},
                success: function (data) {
                    reload_table();
                }
            });

        }
    }

    function reload_table() {
        table.ajax.reload(null, false);
    }
</script>
</body>
</html>
