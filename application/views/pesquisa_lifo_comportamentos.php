<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Gerenciar Comportamentos de Estilos LIFO</title>
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
    <button class="btn btn-info" onclick="add_comportamento()"><i class="glyphicon glyphicon-plus"></i>
        Adicionar comportamento
    </button>
    <button class="btn btn-default" onclick="javascript:window.close()"><i
                class="glyphicon glyphicon-remove"></i> Fechar
    </button>
    <br>
    <br>
    <h5 class="text-primary">
        <strong>Nome do estilo: <?= $nome ?></strong></h5>
    <br>
    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
           width="100%">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Tipo comportamento</th>
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
                    <h3 class="modal-title">Adicionar comportamento</h3>
                </div>
                <div class="modal-body form">
                    <form action="#" id="form" class="form-horizontal">
                        <input type="hidden" value="" name="id"/>
                        <input type="hidden" value="<?= $idEstilo; ?>" name="id_estilo"/>
                        <div class="form-body">
                            <div class="row form-group">
                                <label class="control-label col-md-2">Nome<span class="text-danger"> *</span></label>
                                <div class="col-md-9">
                                    <input name="nome" id="nome" placeholder="Nome do comportamento"
                                           class="form-control" type="text">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-5">Situação comportamental<span class="text-danger"> *</span></label>
                                <div class="col-md-6">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="situacao_comportamental" value="N"> Normal
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="situacao_comportamental" value="E">
                                            Estresse/pressão
                                        </label>
                                    </div>
                                </div>
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

<script>
    var save_method;
    var table;

    $(document).ready(function () {

        table = $('#table').DataTable({
            'dom': "<'row'<'col-sm-4'l><'#comportamento.col-sm-4'><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            'processing': true,
            'serverSide': true,
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('pesquisa_lifo/ajaxComportamentos/'); ?>',
                'type': 'POST',
                'data': function (d) {
                    d.id_estilo = '<?= $idEstilo; ?>';
                    d.situacao_comportamental = $('#situacao_comportamental').val();
                    if (d.situacao_comportamental === undefined) {
                        d.situacao_comportamental = '';
                    }

                    return d;
                },
                'dataSrc': function (json) {
                    if (json.draw === '1') {
                        $("#comportamento").html('<div><label style="font-weight: normal;">Tipo comportamento' +
                            '<select id="situacao_comportamental" class="form-control input-sm" autocomplete="off" aria-controls="table" onchange="reload_table();" style="margin-left: 0.5em;">' +
                            '<option value="">Todos</option>' +
                            '<option value="N">Normais</option>' +
                            '<option value="E">Estresse/pressão</option>' +
                            '</select></label></div>');
                    }
                    return json.data;
                }
            },
            'columnDefs': [
                {
                    'width': '100%',
                    'targets': [0]
                },
                {
                    'className': 'text-center text-nowrap',
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

    function add_comportamento() {
        save_method = 'add';
        $('#form')[0].reset();
        $('#form input[name="id"]').val('');
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $('.modal-title').text('Adicionar comportamento');
        $('#modal_form').modal('show');
        $('.combo_nivel1').hide();
    }

    function edit_comportamento(id) {
        save_method = 'update';
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('.combo_nivel1').hide();

        $.ajax({
            'url': '<?php echo site_url('pesquisa_lifo/ajaxEditComportamento/') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'id': id
            },
            'success': function (json) {
                $('#form input[name="id"]').val(json.id);
                $('#form input[name="nome"]').val(json.nome);
                $('#form input[name="situacao_comportamental"][value="' + json.situacao_comportamental + '"]').prop('checked', true);

                $('.modal-title').text('Editar comportamento');
                $('#modal_form').modal('show');
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function save() {
        $('#btnSave').text('Salvando...').attr('disabled', true);
        var url;
        if (save_method === 'add') {
            url = '<?php echo site_url('pesquisa_lifo/ajax_addComportamento') ?>';
        } else {
            url = '<?php echo site_url('pesquisa_lifo/ajax_updateComportamento') ?>';
        }

        $.ajax({
            'url': url,
            'type': 'POST',
            'data': $('#form').serialize(),
            'dataType': 'json',
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
                if (textStatus) {
                    alert(jqXHR.responseText);
                } else {
                    alert('Error adding / update data');
                }
                $('#btnSave').text('Salvar').attr('disabled', false);
            }
        });
    }

    function delete_comportamento(id) {
        if (confirm('Deseja remover?')) {
            $.ajax({
                'url': '<?php echo site_url('pesquisa_lifo/ajax_deleteComportamento') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (data) {
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    alert('Error deleting data');
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