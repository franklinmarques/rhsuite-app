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
                        <li class="active">Cadastro de Itens de Despesas de Facilities</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_empresa()"><i class="glyphicon glyphicon-plus"></i>
                        Cadastrar empresa
                    </button>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th colspan="2" class="text-center">Empresa</th>
                            <th colspan="2" class="text-center">Unidade</th>
                            <th colspan="2" class="text-center">Item de Despesa</th>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <th class="text-center">Ações</th>
                            <th>Nome</th>
                            <th class="text-center">Ações</th>
                            <th>Nome</th>
                            <th class="text-center">Ações</th>
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
                            <h3 class="modal-title">Formulario de empresa</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Nome da empresa</label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Digite o nome da empresa"
                                                   class="form-control" type="text">
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
            <div class="modal fade" id="modal_unidades" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulario de unidades</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form_unidades" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_conta_empresa"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Nome da unidade</label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Digite o nome da unidade"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveUnidade" onclick="save_unidade()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_itens" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulario de itens de despesa</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form_itens" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_unidade"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Nome do item</label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Digite o nome do item de despesa"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Medidor</label>
                                        <div class="col-md-9">
                                            <input name="medidor" class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Endereço</label>
                                        <div class="col-md-9">
                                            <input name="endereco" class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveItem" onclick="save_item()" class="btn btn-success">
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

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Cadastro de Itens de Despesas de Facilities';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table;

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                'processing': true, //Feature control the processing indicator.
                'serverSide': true, //Feature control DataTables' server-side processing mode.
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                // Load data for the table's content from an Ajax source
                'ajax': {
                    'url': '<?php echo site_url('facilities/itensDespesas/ajaxList/'); ?>',
                    'type': 'POST'
                },
                //Set column definition initialisation properties.
                'columnDefs': [
                    {
                        'width': '33%',
                        'targets': [0, 2]
                    },
                    {
                        'width': '34%',
                        'targets': [4]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [1, 3, 5]
                    }
                ],
                'rowsGroup': [0, 1, 2, 3, 4, 5]
            });

        });

        function add_empresa() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form [name="id"]').val('');
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar empresa'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function add_unidade(id_conta_empresa) {
            save_method = 'add';
            $('#form_unidades')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form_unidades [name="id"]').val('');
            $('#form_unidades [name="id_conta_empresa"]').val(id_conta_empresa);
            $('#modal_unidades').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar unidade'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function add_item(id_unidade) {
            save_method = 'add';
            $('#form_itens')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form_itens [name="id"]').val('');
            $('#form_itens [name="id_unidade"]').val(id_unidade);
            $('#modal_itens').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar item'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_empresa(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('facilities/itensDespesas/ajaxEdit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $('#form [name="id"]').val(json.id);
                    $('#form [name="id_empresa"]').val(json.id_empresa);
                    $('#form [name="nome"]').val(json.nome);

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar empresa'); // Set title to Bootstrap modal title
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_unidade(id) {
            save_method = 'update';
            $('#form_unidades')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('facilities/itensDespesas/ajaxEditUnidade') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $('#form_unidades [name="id"]').val(json.id);
                    $('#form_unidades [name="id_empresa"]').val(json.id_empresa);
                    $('#form_unidades [name="nome"]').val(json.nome);

                    $('#modal_unidades').modal('show');
                    $('.modal-title').text('Editar unidade'); // Set title to Bootstrap modal title
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_item(id) {
            save_method = 'update';
            $('#form_itens')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('facilities/itensDespesas/ajaxEditItem') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $('#form_itens [name="id"]').val(json.id);
                    $('#form_itens [name="id_unidade"]').val(json.id_unidade);
                    $('#form_itens [name="nome"]').val(json.nome);
                    $('#form_itens [name="medidor"]').val(json.medidor);
                    $('#form_itens [name="endereco"]').val(json.endereco);

                    $('#modal_itens').modal('show');
                    $('.modal-title').text('Editar item'); // Set title to Bootstrap modal title
                },
                'error': function (jqXHR, textStatus, errorThrown) {
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
                url = "<?php echo site_url('facilities/itensDespesas/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('facilities/itensDespesas/ajaxUpdate') ?>";
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
                    }

                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }

        function save_unidade() {
            $('#btnSaveUnidade').text('Salvando...'); //change button text
            $('#btnSaveUnidade').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('facilities/itensDespesas/ajaxAddUnidade') ?>";
            } else {
                url = "<?php echo site_url('facilities/itensDespesas/ajaxUpdateUnidade') ?>";
            }

            // ajax adding data to database
            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form_unidades').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_unidades').modal('hide');
                        reload_table();
                    }

                    $('#btnSaveUnidade').text('Salvar'); //change button text
                    $('#btnSaveUnidade').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveUnidade').text('Salvar'); //change button text
                    $('#btnSaveUnidade').attr('disabled', false); //set button enable
                }
            });
        }

        function save_item() {
            $('#btnSaveItem').text('Salvando...'); //change button text
            $('#btnSaveItem').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('facilities/itensDespesas/ajaxAddItem') ?>";
            } else {
                url = "<?php echo site_url('facilities/itensDespesas/ajaxUpdateItem') ?>";
            }

            // ajax adding data to database
            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form_itens').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_itens').modal('hide');
                        reload_table();
                    }

                    $('#btnSaveItem').text('Salvar'); //change button text
                    $('#btnSaveItem').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveItem').text('Salvar'); //change button text
                    $('#btnSaveItem').attr('disabled', false); //set button enable
                }
            });
        }


        function delete_empresa(id) {
            if (confirm('Deseja remover a empresa?')) {
                $.ajax({
                    'url': '<?php echo site_url('facilities/itensDespesas/ajaxDelete') ?>',
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

        function delete_unidade(id) {
            if (confirm('Deseja remover a unidade?')) {
                $.ajax({
                    'url': '<?php echo site_url('facilities/itensDespesas/ajaxDeleteUnidade') ?>',
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

        function delete_item(id) {
            if (confirm('Deseja remover o andar?')) {
                $.ajax({
                    'url': '<?php echo site_url('facilities/itensDespesas/ajaxDeleteItem') ?>',
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

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>