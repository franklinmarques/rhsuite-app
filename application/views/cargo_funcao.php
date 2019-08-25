<?php require_once "header.php"; ?>
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

        /*    .wizard > .steps > ul > li {
                width: 33% !important;
            }*/

        .wizard > .steps a, .wizard > .steps a:hover, .wizard > .steps a:active {
            background: #eee !important;
        }

        .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
            background: #111343 !important;
            color: #fff;
            cursor: default;
        }

        .wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
            background: #758fb0 !important;
            color: #fff;
        }
    </style>

    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="glyphicons glyphicons-nameplate"></i>&nbsp;
                            Gerenciar Cargos/Funções
                        </header>
                        <div class="panel-body">
                            <button id="novo_cargo" class="btn btn-info" onclick="add_cargo()"><i
                                        class="glyphicon glyphicon-plus"></i> Novo cargo
                            </button>
                            <br>
                            <br>
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered table-condensed"
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Cargo</th>
                                        <th nowrap>Família CBO</th>
                                        <th>Ações</th>
                                        <th nowrap>Função/ocupação</th>
                                        <th nowrap>Ocupação CBO</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_cargo" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Adicionar cargo</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form_cargo" class="form-horizontal">
                                <input type="hidden" name="id" value=""/>
                                <input type="hidden" name="id_empresa"
                                       value="<?= $this->session->userdata('empresa'); ?>"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Cargo</label>
                                        <div class="col-md-9">
                                            <input name="nome" class="form-control" type="text" maxlength="255"
                                                   placeholder="Nome do cargo" autofocus>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Família CBO</label>
                                        <div class="col-md-3">
                                            <input name="familia_CBO" id="familia_CBO" class="form-control" type="text"
                                                   maxlength="4">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveCargo" onclick="save_cargo()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_funcao" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Adicionar função/ocupação</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form_funcao" class="form-horizontal">
                                <input type="hidden" value="" name="id_cargo"/>
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="col-sm-2 control-label">Cargo</label>
                                        <div class="col-sm-9">
                                            <input name="nome_cargo" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Função</label>
                                        <div class="col-md-9">
                                            <input name="nome" class="form-control" type="text" maxlength="255"
                                                   placeholder="Nome da função ou ocupação" autofocus>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">CBO - Ocupação</label>
                                        <div class="col-md-2">
                                            <input name="ocupacao_CBO" id="ocupacao_CBO" class="form-control"
                                                   type="text"
                                                   maxlength="2">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveFuncao" onclick="save_funcao()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- End Bootstrap modal -->

            <!-- page end-->
        </section>
    </section>
    <!--main content end-->

    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<?php require_once "end_js.php"; ?>
    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'RhSuite - Corporate RH Tools: Gerenciar Cargos/Funções';
        });

    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

    <script>
        var save_method;
        var table;

        $('#familia_CBO').mask('0000');
        $('#ocupacao_CBO').mask('00');


        $(document).ready(function () {

            table = $('#table').DataTable({
                'processing': true, //Feature control the processing indicator.
                'serverSide': true, //Feature control DataTables' server-side processing mode.
                'order': [], //Initial no order.
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, 500, -1], [5, 10, 25, 50, 100, 500, 'Todos']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('cargo_funcao/listar') ?>',
                    'type': 'POST'
                },
                'columnDefs': [
                    {
                        'width': '50%',
                        'targets': [0, 3]
                    },
                    {
                        'mRender': function (data) {
                            if (data === null) {
                                data = '<span class="text-muted">Nenhuma função encontrada</span>';
                            }
                            return data;
                        },
                        'targets': [3]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [-1, -4], //last column
                        'orderable': false, //set not orderable
                        'searchable': false //set not orderable
                    }
                ],
                'rowsGroup': [0, 1, 2]
            });

        });


        function add_cargo() {
            save_method = 'add';
            $('#form_cargo')[0].reset(); // reset form on modals
            $('#form_cargo [name="id"]').val('');
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_cargo').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar novo cargo'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }


        function add_funcao(id_cargo, nome_cargo) {
            save_method = 'add';
            $('#form_funcao')[0].reset(); // reset form on modals
            $('#form_funcao [name="id"]').val('');
            $('#form_funcao [name="nome_cargo"]').val(nome_cargo);
            $('#form_funcao [name="id_cargo"]').val(id_cargo).prop('readonly', true);
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_funcao').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar nova função/ocupação'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }


        function edit_cargo(id) {
            save_method = 'update';
            $('#form_cargo')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('cargo_funcao/editarCargo') ?>',
                'type': 'POST',
                'dataType': 'JSON',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $.each(json, function (key, value) {
                        $('#form_cargo [name="' + key + '"]').val(value);
                    });

                    $('#modal_cargo').modal('show');
                    $('.modal-title').text('Editar cargo'); // Set title to Bootstrap modal title
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_funcao(id, nome_cargo) {
            save_method = 'update';
            $('#form_funcao')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('cargo_funcao/editarFuncao') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $.each(json, function (key, value) {
                        $('#form_funcao [name="' + key + '"]').val(value);
                    });

                    $('#form_funcao [name="nome_cargo"]').val(nome_cargo).prop('readonly', true);
                    $('#modal_funcao').modal('show');
                    $('.modal-title').text('Editar função/ocupação'); // Set title to Bootstrap modal title
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function save_cargo() {
            $.ajax({
                'url': '<?php echo site_url('cargo_funcao/salvarCargo') ?>',
                'type': 'POST',
                'data': $('#form_cargo').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveCargo').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_cargo').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveCargo').text('Salvar').attr('disabled', false);
                }
            });
        }


        function save_funcao() {
            $.ajax({
                'url': '<?php echo site_url('cargo_funcao/salvarFuncao') ?>',
                'type': 'POST',
                'data': $('#form_funcao').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveFuncao').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_funcao').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveFuncao').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_cargo(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('cargo_funcao/excluirCargo') ?>',
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
                        $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                        alert('Error deleting data');
                    }
                });
            }
        }


        function delete_funcao(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('cargo_funcao/excluirFuncao') ?>',
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

<?php require_once "end_html.php"; ?>