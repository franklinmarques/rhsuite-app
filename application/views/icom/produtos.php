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
                        <li class="active">Gerenciar Produtos</li>
                    </ol>
                    <form action="#" id="estrutura" class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Filtrar por departamento</label>
                                <?php echo form_dropdown('id_depto', $deptos, $depto_atual, 'onchange="filtrar_estrutura()" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label>Filtrar por área</label>
                                <?php echo form_dropdown('id_area', $areas, $area_atual, 'onchange="filtrar_estrutura();" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label>Filtrar por setor</label>
                                <?php echo form_dropdown('id_setor', $setores, $setor_atual, 'onchange="checar_setor();"class="form-control input-sm"'); ?>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <button id="btnAdd" type="button" class="btn btn-info" onclick="add_produto()" autocomplete="off"
                            disabled><i class="glyphicon glyphicon-plus"></i> Novo produto
                    </button>
                    <br>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Identificação do produto</th>
                            <th nowrap>Tipo de produto</th>
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
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h3 class="modal-title">Gerenciar produto</h3>
                            </div>
                            <div class="modal-body form">
                                <div id="alert"></div>
                                <form action="#" id="form" class="form-horizontal">
                                    <input type="hidden" value="" name="id"/>
                                    <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                                    <div class="form-body">
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Código</label>
                                            <div class="col-md-4">
                                                <input name="codigo" class="form-control" type="text">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Nome</label>
                                            <div class="col-md-8">
                                                <input name="nome" class="form-control" type="text">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Tipo</label>
                                            <div class="col-md-4">
                                                <?php echo form_dropdown('tipo', $tipos, '', 'class="form-control"'); ?>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Preço de locação</label>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">R$</span>
                                                    <input name="preco" type="text" class="form-control valor"
                                                           aria-describedby="basic-addon1">
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Tipo preço</label>
                                            <div class="col-md-5">
                                                <?php echo form_dropdown('tipo_preco', $tiposPreco, '', 'class="form-control"'); ?>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" id="btnSave" onclick="save()">Salvar
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

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Produtos';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table;

        $('.valor').mask('#.###.##0,00', {reverse: true});


        $(document).ready(function () {

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'order': [],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('icom/produtos/listar/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#estrutura').serialize();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '100%',
                        'targets': [0]
                    },
                    {
                        'className': 'text-center',
                        'targets': [1]
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


        function filtrar_estrutura() {
            var data = $('#estrutura').serialize();
            $.ajax({
                'url': '<?php echo site_url('icom/produtos/filtrarEstrutura') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': data,
                'beforeSend': function () {
                    $('#estrutura select').prop('disabled', true);
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $('#estrutura [name="id_area"]').html(json.areas);
                    $('#estrutura [name="id_setor"]').html(json.setores);

                    $('#estrutura select').prop('disabled', false);
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                },
                'complete': function () {
                    $('#btnAdd').prop('disabled', $('#estrutura [name="id_setor"]').val().length === 0);
                    $('#estrutura select').prop('disabled', false);
                }
            });
        }

        function checar_setor() {
            $('#btnAdd').prop('disabled', $('#estrutura [name="id_setor"]').val().length === 0);
            reload_table();
        }


        function add_produto() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form [name="id"]').val('');
            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar produto');
            $('.combo_nivel1').hide();
        }


        function edit_produto(id) {
            $.ajax({
                'url': '<?php echo site_url('icom/produtos/editar') ?>',
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
                    $('.modal-title').text('Editar produto');
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
                'url': '<?php echo site_url('icom/produtos/salvar') ?>',
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


        function delete_produto(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('icom/produtos/excluir') ?>',
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