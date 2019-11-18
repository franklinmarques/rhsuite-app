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
                        <li class="active">Cadastro de itens de Vistoria/Manutenção</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_item()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar item de Ativo/Facility
                    </button>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr class="active">
                            <th colspan="4" class="text-center">Ativo/Facility</th>
                            <th colspan="3" class="text-center">Item de Vistoria/Manutenção</th>
                        </tr>
                        <tr>
                            <th>Empresa</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Ações</th>
                            <th>Tipo</th>
                            <th>Nome</th>
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
                            <h3 class="modal-title">Formulario de ativo/facility</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Empresa</label>
                                        <div class="col-md-9 controls">
                                            <?php echo form_dropdown('id_facility_empresa', $idFacilityEmpresa, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome</label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Digite o nome do item" class="form-control"
                                                   type="text" maxlength="255">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Tipo</label>
                                        <div class="col-md-4">
                                            <select name="ativo" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="1">Ativo</option>
                                                <option value="0">Facility</option>
                                            </select>
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

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_revisao" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulario de vistoria/manutenção</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form_revisao" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_item"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome</label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Digite o nome do item" class="form-control"
                                                   type="text" maxlength="255">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Tipo</label>
                                        <div class="col-md-4">
                                            <select name="tipo" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="V">Vistoria</option>
                                                <option value="M">Manutenção</option>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveRevisao" onclick="save_revisao()" class="btn btn-success">
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

<?php
require_once APPPATH . 'views/end_js.php';
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-combobox/css/bootstrap-combobox.css"); ?>">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Cadastro de itens de Vistoria/Manutenção';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-combobox/js/bootstrap-combobox.js"); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table;


        $(document).ready(function () {
            $('.data').mask('00/00/0000');
            $('.combobox').combobox();

            //datatables
            table = $('#table').DataTable({
                'processing': true, //Feature control the processing indicator.
                'serverSide': true, //Feature control DataTables' server-side processing mode.
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                // Load data for the table's content from an Ajax source
                'ajax': {
                    'url': '<?php echo site_url('facilities/empresas/ajaxList/') ?>',
                    'type': 'POST'
                },
                //Set column definition initialisation properties.
                'columnDefs': [
                    {
                        'width': '30%',
                        'targets': [0, 1]
                    },
                    {
                        'className': 'text-center',
                        'targets': [2, 4]
                    },
                    {
                        'width': '40%',
                        'mRender': function (data) {
                            if (data === null) {
                                data = '<span class="text-muted">Nenhum item encontrado</span>';
                            }
                            return data;
                        },
                        'targets': [5]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1, -4]
                    }
                ],
                'rowsGroup': [0, 1, 2, 3, 4]
            });

        });


        function add_item() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#form [name="id"]').val('');
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form [name="id"]').val('');
            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar ativo/facility'); // Set title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }


        function add_revisao(id_item) {
            save_method = 'add';
            $('#form_revisao')[0].reset(); // reset form on modals
            $('#form_revisao [name="id"]').val('');
            $('#form_revisao [name="id_item"]').val(id_item);
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form_revisao [name="id"]').val('');
            $('#modal_revisao').modal('show');
            $('.modal-title').text('Adicionar vistoria/manutenção'); // Set title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }


        function edit_item(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('facilities/empresas/ajaxEdit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $.each(json, function (key, value) {
                        $('#form [name="' + key + '"]').val(value);
                    });

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar ativo/facility'); // Set title to Bootstrap modal title
                }
            });
        }


        function edit_revisao(id) {
            save_method = 'update';
            $('#form_revisao')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('facilities/empresas/ajaxEditRevisao') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $.each(json, function (key, value) {
                        $('#form_revisao [name="' + key + '"]').val(value);
                    });

                    $('#modal_revisao').modal('show');
                    $('.modal-title').text('Editar vistoria/manutenção'); // Set title to Bootstrap modal title
                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }


        function save() {
            $('#btnSave').text('Salvando...').attr('disabled', true);
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('facilities/empresas/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('facilities/empresas/ajaxUpdate') ?>";
            }

            // ajax adding data to database
            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'complete': function () {
                    $('#btnSave').text('Salvar').attr('disabled', false); //set button enable
                }
            });
        }


        function save_revisao() {
            $('#btnSaveRevisao').text('Salvando...').attr('disabled', true);
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('facilities/empresas/ajaxAddRevisao') ?>";
            } else {
                url = "<?php echo site_url('facilities/empresas/ajaxUpdateRevisao') ?>";
            }

            // ajax adding data to database
            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form_revisao').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_revisao').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveRevisao').text('Salvar').attr('disabled', false); //set button enable
                },
                'complete': function () {
                    $('#btnSaveRevisao').text('Salvar').attr('disabled', false); //set button enable
                }
            });
        }


        function delete_item(id) {
            if (confirm('Deseja remover o item?')) {
                $.ajax({
                    'url': '<?php echo site_url('facilities/empresas/ajaxDelete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function () {
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }


        function delete_revisao(id) {
            if (confirm('Deseja remover vistoria/manutenção?')) {
                $.ajax({
                    'url': '<?php echo site_url('facilities/empresas/ajaxDeleteRevisao') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function () {
                        reload_table();
                    }
                });
            }
        }

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>
