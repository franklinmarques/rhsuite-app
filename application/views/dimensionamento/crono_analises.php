<?php require_once APPPATH . 'views/header.php'; ?>

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
                        <li class="active">Gerenciar Crono Análises</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_crono_analise()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar crono análise
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Identificação da análise</th>
                            <th nowrap>Período inicial</th>
                            <th nowrap>Período final</th>
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
                            <h3 class="modal-title">Formulario de crono análise</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <input name="nome" class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2 text-nowrap">Data início <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-3">
                                            <input name="data_inicio" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center date" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-3">Data término <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-3">
                                            <input name="data_termino" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center date" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btnSave" onclick="save()">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_executores" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Gerenciar executores</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_executores" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id_crono_analise"/>
                                <!--<div class="row">
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
                                        <?php /*echo form_dropdown('', ['' => 'Todos'], '', 'id="id_diretoria" class="form-control input-sm"'); */ ?>
                                    </div>
                                </div>-->
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <?php echo form_multiselect('id_usuario[]', [], [], 'id="id_usuarios" class="form-control demo1"'); ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveEscolas" onclick="salvar_executores();"
                                    class="btn btn-success">
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

<?php require_once APPPATH . 'views/end_js.php'; ?>

    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Crono Análises';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>

    <script>

        var save_method, demo1;
        var table;

        $('.date').mask('00/00/0000');


        $(document).ready(function () {

            table = $('#table').DataTable({
                'info': false,
                'processing': true,
                'serverSide': true,
                'order': [],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('dimensionamento/cronoAnalises/ajaxList/') ?>',
                    'type': 'POST'
                },
                'columnDefs': [
                    {
                        'width': '100%',
                        'targets': [0]
                    },
                    {
                        'className': 'text-center',
                        'targets': [1, 2]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [-1],
                        'orderable': false,
                        'searchable': false
                    }
                ]
            });


            demo1 = $('.demo1').bootstrapDualListbox({
                'nonSelectedListLabel': 'Colaboradores disponíveis',
                'selectedListLabel': 'Colaboradores crono analisados',
                'preserveSelectionOnMove': 'moved',
                'moveOnSelect': false,
                'filterPlaceHolder': 'Filtrar',
                'helperSelectNamePostfix': false,
                'selectorMinimalHeight': 132,
                'infoText': false
            });

        });


        function add_crono_analise() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar crono análise');
            $('.combo_nivel1').hide();
        }


        function edit_crono_analise(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('dimensionamento/cronoAnalises/ajaxEdit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }
                    $.each(json, function (key, value) {
                        $('#form [name="' + key + '"]').val(value);
                    });

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar crono análise');

                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_executores(id) {
            $.ajax({
                'url': '<?php echo site_url('dimensionamento/cronoAnalises/editarExecutores') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'beforeSend': function () {
                    save_method = 'update';
                    $('#form_executores')[0].reset();
                    $('.form-group').removeClass('has-error');
                    $('.help-block').empty();
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }
                    $('#form_executores [name="id_crono_analise"]').val(id);
                    $('#id_usuarios').html($(json.executores).html());

                    demo1.bootstrapDualListbox('refresh', true);
                    $('#modal_executores').modal('show');
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
                url = '<?php echo site_url('dimensionamento/cronoAnalises/ajaxAdd') ?>';
            } else {
                url = '<?php echo site_url('dimensionamento/cronoAnalises/ajaxUpdate') ?>';
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
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }


        function salvar_executores() {
            $.ajax({
                'url': '<?php echo site_url('dimensionamento/cronoAnalises/salvarExecutores') ?>',
                'type': 'POST',
                'data': $('#form_executores').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveExecutores').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_executores').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveExecutores').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_crono_analise(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('dimensionamento/cronoAnalises/ajaxDelete') ?>',
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
                        alert('Error deleting data');
                    }
                });

            }
        }

    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>