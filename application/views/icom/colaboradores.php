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
                        <li class="active">Gestão Comercial - Gerenciar Clientes</li>
                    </ol>
                    <button id="btnAdd" type="button" class="btn btn-info" onclick="add_cliente()" autocomplete="off"><i
                                class="glyphicon glyphicon-plus"></i> Novo cliente
                    </button>
                    <a id="pdf" class="btn btn-primary" href="<?= site_url('icom/clientes/relatorio'); ?>"
                       target="_blank"><i class="glyphicon glyphicon-print"></i> Imprimir
                    </a>
                    <br>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Nome do cliente</th>
                            <th>Nome contato</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- page end-->

                <!-- Bootstrap modal -->
                <div class="modal fade" id="modal_form" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div style="float:right;">
                                    <button type="button" class="btn btn-success" id="btnSave" onclick="save()">Salvar
                                    </button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                </div>
                                <h3 class="modal-title">Gerenciar cliente</h3>
                            </div>
                            <div class="modal-body form">
                                <div id="alert"></div>
                                <form action="#" id="form" class="form-horizontal">
                                    <input type="hidden" value="" name="id"/>
                                    <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                                    <div class="form-body">
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Nome</label>
                                            <div class="col-md-9">
                                                <input name="nome" class="form-control" type="text">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Observações</label>
                                            <div class="col-md-9">
                                                <textarea name="observacoes" class="form-control"></textarea>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4>Contato principal</h4>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">Nome</label>
                                                    <div class="col-md-9">
                                                        <input name="contato_principal" class="form-control"
                                                               type="text">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">Telefone</label>
                                                    <div class="col-md-9">
                                                        <input name="telefone_contato_principal" class="form-control"
                                                               type="text">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">E-mail</label>
                                                    <div class="col-md-9">
                                                        <input name="email_contato_principal" class="form-control"
                                                               type="text">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">Cargo</label>
                                                    <div class="col-md-9">
                                                        <input name="cargo_contato_principal" class="form-control"
                                                               type="text">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" style="border-left:1px solid #eee;">
                                                <h4>Contato secundário</h4>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">Nome</label>
                                                    <div class="col-md-9">
                                                        <input name="contato_secundario" class="form-control"
                                                               type="text">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">Telefone</label>
                                                    <div class="col-md-9">
                                                        <input name="telefone_contato_secundario" class="form-control"
                                                               type="text">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">E-mail</label>
                                                    <div class="col-md-9">
                                                        <input name="email_contato_secundario" class="form-control"
                                                               type="text">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">Cargo</label>
                                                    <div class="col-md-9">
                                                        <input name="cargo_contato_secundario" class="form-control"
                                                               type="text">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gestão Comercial: Gerenciar Clientes';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

    <script>

        var save_method;
        var table;


        $(document).ready(function () {

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'order': [],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('icom/clientes/listar') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#estrutura').serialize();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '30%',
                        'targets': [0, 1, 3]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [2]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [-1],
                        'orderable': false,
                        'searchable': false
                    }
                ]
            });

        });


        function add_cliente() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form [name="id"]').val('');
            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar cliente');
            $('.combo_nivel1').hide();
        }


        function edit_cliente(id) {
            $.ajax({
                'url': '<?php echo site_url('icom/clientes/editar') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'beforeSend': function () {
                    save_method = 'update';
                    $('#form')[0].reset();
                    $('.form-group').removeClass('has-error');
                    $('.help-block').empty();
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $.each(json, function (key, value) {
                        $('#form [name="' + key + '"]').val(value);
                    });

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar cliente');
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
            $.ajax({
                'url': '<?php echo site_url('icom/clientes/salvar') ?>',
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSave').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_cliente(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('icom/clientes/excluir') ?>',
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