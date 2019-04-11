<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Gerenciar Ordem de Serviço de Alunos</title>
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
    <button class="btn btn-info" onclick="add_aluno()"><i class="glyphicon glyphicon-plus"></i>
        Matricular aluno(a)
    </button>
    <button class="btn btn-default" onclick="javascript:window.close()"><i
                class="glyphicon glyphicon-remove"></i> Fechar
    </button>
    <br>
    <br>
    <h5 class="text-primary">
        <strong>Cliente/diretoria: <?= $nomeCliente ?></strong></h5>
    <h5 class="text-primary">
        <strong>Unidade de ensino: <?= $nomeEscola ?></strong></h5>
    <h5 class="text-primary">
        <strong>Contrato: <?= $nomeContrato ?></strong></h5>
    <h5 class="text-primary">
        <strong>Ordem de Serviço: <?= $ordemServico ?></strong>
    </h5>
    <h5 class="text-primary">
        <strong>Ano/semestre: <?= $anoSemestre ?></strong>
    </h5>
    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
           width="100%">
        <thead>
        <tr>
            <th>Curso</th>
            <th>Aluno(a)</th>
            <th>Data início</th>
            <th>Data término</th>
            <th>Módulo</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <div class="modal fade" id="modal_form" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Matricular aluno(a)</h3>
                </div>
                <div class="modal-body form">
                    <form action="#" id="form" class="form-horizontal">
                        <input type="hidden" value="" name="id"/>
                        <input type="hidden" value="<?= $this->uri->rsegment(3) ?>" name="id_ordem_servico_escola"
                               class="filtro"/>
                        <div class="form-body">
                            <div class="row form-group">
                                <label class="control-label col-md-2">Aluno<span class="text-danger"> *</span></label>
                                <div class="col-md-9">
                                    <?php echo form_dropdown('id_aluno', $alunos, '', 'id="aluno" class="form-control filtro"'); ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-2">Curso<span class="text-danger"> *</span></label>
                                <div class="col-md-9">
                                    <?php echo form_dropdown('id_aluno_curso', ['' => 'selecione...'], '', 'id="aluno_curso" class="form-control filtro"'); ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-2">Data início</label>
                                <div class="col-md-2">
                                    <input name="data_inicio" class="form-control text-center data"
                                           placeholder="dd/mm/aaaa">
                                </div>
                                <label class="control-label col-md-2">Data término</label>
                                <div class="col-md-2">
                                    <input name="data_termino" class="form-control text-center data"
                                           placeholder="dd/mm/aaaa">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-2">Módulo</label>
                                <div class="col-md-6">
                                    <input name="modulo" class="form-control" placeholder="N&ordm; do módulo">
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
<script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>
    var save_method;
    var table;

    $(document).ready(function () {

        $('.data').mask('00/00/0000');

        table = $('#table').DataTable({
            'info': false,
            'processing': true,
            'serverSide': true,
            'lengthChange': false,
            'searching': false,
            'paging': false,
            'order': [[1, 'asc'], [0, 'desc']],
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('ei/ordemServico_alunos/ajaxList/' . $this->uri->rsegment(3)) ?>',
                'type': 'POST'
            },
            'columnDefs': [
                {
                    'width': '40%',
                    'targets': [0, 1]
                },
                {
                    'className': 'text-center text-nowrap',
                    'targets': [2, 3]
                },
                {
                    'width': '20%',
                    'targets': [4]
                },
                {
                    'className': 'text-center text-nowrap',
                    'targets': [-1],
                    'orderable': false
                }
            ],
            'rowsGroup': [0, 1]
        });

    });


    $('#aluno').on('change', function () {
        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_alunos/montarEstrutura/') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'busca': $('.filtro').serialize()
            },
            'success': function (json) {
                $('#aluno_curso').html($(json.aluno_curso).html());
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    });


    function add_aluno() {
        save_method = 'add';
        $('#form')[0].reset();
        $('#form [name="id"]').val('');
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#diretoria, #contrato').val('').prop('disabled', false);
        $('#diretoria').trigger('change');
        $('#aluno_curso').html('<option value="">selecione...</option>');

        $('.modal-title').text('Matricular aluno(a)');
        $('#modal_form').modal('show');
        $('.combo_nivel1').hide();
    }


    function edit_aluno(id) {
        save_method = 'update';
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('.combo_nivel1').hide();

        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_alunos/ajaxEdit/') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'id': id,
                'busca': $('.filtro').serialize()
            },
            'success': function (json) {
                $('#aluno_curso').html($(json.aluno_curso).html());

                $.each(json, function (key, value) {
                    $('#form [name="' + key + '"]').val(value);
                });

                $('.modal-title').text('Editar aluno(a) matriculado(a)');
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
            url = '<?php echo site_url('ei/ordemServico_alunos/ajaxAdd') ?>';
        } else {
            url = '<?php echo site_url('ei/ordemServico_alunos/ajaxUpdate') ?>';
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


    function delete_aluno(id) {
        if (confirm('Deseja remover este(a) aluno(a)?')) {
            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico_alunos/ajaxDelete') ?>',
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