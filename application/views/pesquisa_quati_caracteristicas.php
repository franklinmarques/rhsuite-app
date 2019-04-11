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
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('pesquisaQuati/estilos'); ?>">Testes de Personalidade - Tipologia de
                                Jung</a></li>
                        <li class="active">Características comportamentais</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_caracteristica()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar característica comportamental
                    </button>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo comportamental</th>
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
                            <h3 class="modal-title">Adicionar característica comportamental</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $idEmpresa; ?>" name="id_empresa"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <input name="nome" id="nome" placeholder="Nome da característica"
                                                   class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Tipo comportamental<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="tipo_comportamental" value="X">
                                                    Introvertido
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="tipo_comportamental" value="I"> Intuitivo
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="tipo_comportamental" value="R"> Racional
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="tipo_comportamental" value="Y">
                                                    Extrovertido
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="tipo_comportamental" value="S"> Sensitivo
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="tipo_comportamental" value="E"> Emocional
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
            </div><!-- /.modal -->

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
            document.title = 'CORPORATE RH - LMS - Tipologia de Jung - Características comportamentais';
        });</script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table;

        $(document).ready(function () {

            table = $('#table').DataTable({
                'dom': "<'row'<'col-sm-4'l><'#caracteristica.col-sm-4'><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                'processing': true,
                'serverSide': true,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('pesquisaQuati/ajaxCaracteristicas/'); ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.id_empresa = '<?= $idEmpresa; ?>';
                        d.tipo_comportamental = $('#tipo_comportamental').val();
                        if (d.tipo_comportamental === undefined) {
                            d.tipo_comportamental = '';
                        }

                        return d;
                    },
                    'dataSrc': function (json) {
                        if (json.draw === '1') {
                            $("#caracteristica").html('<div><br><label style="font-weight: normal;">Tipo comportamental' +
                                '<select id="tipo_comportamental" class="form-control input-sm" autocomplete="off" aria-controls="table" onchange="reload_table();" style="margin-left: 0.5em;">' +
                                '<option value="">Todos</option>' +
                                '<option value="X">Introvertidos</option>' +
                                '<option value="Y">Extrovertidos</option>' +
                                '<option value="I">Intuitivos</option>' +
                                '<option value="S">Sensitivos</option>' +
                                '<option value="R">Racionais</option>' +
                                '<option value="E">Emocionais</option>' +
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

        function add_caracteristica() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form input[name="id"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $('.modal-title').text('Adicionar característica comportamental');
            $('#modal_form').modal('show');
            $('.combo_nivel1').hide();
        }

        function edit_caracteristica(id) {
            save_method = 'update';
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('.combo_nivel1').hide();

            $.ajax({
                'url': '<?php echo site_url('pesquisaQuati/ajaxEditCaracteristica/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id': id
                },
                'success': function (json) {
                    $('#form input[name="id"]').val(json.id);
                    $('#form input[name="nome"]').val(json.nome);
                    $('#form input[name="tipo_comportamental"][value="' + json.tipo_comportamental + '"]').prop('checked', true);

                    $('.modal-title').text('Editar característica comportamental');
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
                url = '<?php echo site_url('pesquisaQuati/ajax_addCaracteristica') ?>';
            } else {
                url = '<?php echo site_url('pesquisaQuati/ajax_updateCaracteristica') ?>';
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

        function delete_caracteristica(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('pesquisaQuati/ajax_deleteCaracteristica') ?>',
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

<?php
require_once "end_html.php";
?>