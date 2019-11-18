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
                        <li class="active">Cadastro Estrutural de Facilities</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_empresa()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar empresa
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th colspan="2" class="text-center">Empresa</th>
                            <th colspan="2" class="text-center">Unidade</th>
                            <th colspan="2" class="text-center">Andar</th>
                            <th colspan="2" class="text-center">Sala</th>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <th>Ações</th>
                            <th>Nome</th>
                            <th>Ações</th>
                            <th>Nome</th>
                            <th>Ações</th>
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
                                <input type="hidden" value="" name="id_empresa"/>
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
            <div class="modal fade" id="modal_andares" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulario de andares</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form_andares" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_unidade"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Nome do andar</label>
                                        <div class="col-md-9">
                                            <input name="andar" placeholder="Digite o nome do andar"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveAndar" onclick="save_andar()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_salas" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulario de salas</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form_salas" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_andar"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Nome da sala</label>
                                        <div class="col-md-9">
                                            <input name="sala" placeholder="Digite o nome da sala"
                                                   class="form-control" type="text" maxlength="40">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveSala" onclick="save_sala()" class="btn btn-success">
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
            document.title = 'CORPORATE RH - LMS - Cadastro Estrutural de Facilities';
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
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "iDisplayLength": -1,
                "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                "order": [[0, 'asc'], [2, 'asc'], [4, 'asc'], [6, 'asc']],
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('facilities/estruturas/ajaxList/') ?>",
                    "type": "POST"
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '20%',
                        targets: [0, 2, 4]
                    },
                    {
                        width: '40%',
                        targets: [6]
                    },
                    {
                        className: "text-nowrap",
                        "orderable": false,
                        "searchable": false,
                        "targets": [1, 3, 5, 7]
                    }
                ],
                'rowsGroup': [0, 1, 2, 3, 4, 5, 6]
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

        function add_unidade(id_empresa) {
            save_method = 'add';
            $('#form_unidades')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form_unidades [name="id"]').val('');
            $('#form_unidades [name="id_empresa"]').val(id_empresa);
            $('#modal_unidades').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar unidade'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function add_andar(id_unidade) {
            save_method = 'add';
            $('#form_andares')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form_andares [name="id"]').val('');
            $('#form_andares [name="id_unidade"]').val(id_unidade);
            $('#modal_andares').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar andar'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function add_sala(id_andar) {
            save_method = 'add';
            $('#form_salas')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form_salas [name="id"]').val('');
            $('#form_salas [name="id_andar"]').val(id_andar);
            $('#modal_salas').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar sala'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_empresa(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('facilities/estruturas/ajaxEdit') ?>",
                type: "POST",
                dataType: "json",
                data: {id: id},
                success: function (json) {
                    $('#form [name="id"]').val(json.id);
                    $('#form [name="id_empresa"]').val(json.id_empresa);
                    $('#form [name="nome"]').val(json.nome);

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar empresa'); // Set title to Bootstrap modal title
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
                url: "<?php echo site_url('facilities/estruturas/ajaxEditUnidade') ?>",
                type: "POST",
                dataType: "json",
                data: {id: id},
                success: function (json) {
                    $('#form_unidades [name="id"]').val(json.id);
                    $('#form_unidades [name="id_empresa"]').val(json.id_empresa);
                    $('#form_unidades [name="nome"]').val(json.nome);

                    $('#modal_unidades').modal('show');
                    $('.modal-title').text('Editar unidade'); // Set title to Bootstrap modal title
                }
            });
        }

        function edit_andar(id) {
            save_method = 'update';
            $('#form_andares')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('facilities/estruturas/ajaxEditAndar') ?>",
                type: "POST",
                dataType: "json",
                data: {id: id},
                success: function (json) {
                    $('#form_andares [name="id"]').val(json.id);
                    $('#form_andares [name="id_unidade"]').val(json.id_unidade);
                    $('#form_andares [name="andar"]').val(json.andar);

                    $('#modal_andares').modal('show');
                    $('.modal-title').text('Editar andar'); // Set title to Bootstrap modal title
                }
            });
        }

        function edit_sala(id) {
            save_method = 'update';
            $('#form_salas')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('facilities/estruturas/ajaxEditSala') ?>",
                type: "POST",
                dataType: "json",
                data: {id: id},
                success: function (json) {
                    $('#form_salas [name="id"]').val(json.id);
                    $('#form_salas [name="id_andar"]').val(json.id_andar);
                    $('#form_salas [name="sala"]').val(json.sala);

                    $('#modal_salas').modal('show');
                    $('.modal-title').text('Editar sala'); // Set title to Bootstrap modal title
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
                url = "<?php echo site_url('facilities/estruturas/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('facilities/estruturas/ajaxUpdate') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "json",
                success: function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }
                },
                complete: function () {
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
                url = "<?php echo site_url('facilities/estruturas/ajaxAddUnidade') ?>";
            } else {
                url = "<?php echo site_url('facilities/estruturas/ajaxUpdateUnidade') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_unidades').serialize(),
                dataType: "json",
                success: function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_unidades').modal('hide');
                        reload_table();
                    }
                },
                complete: function () {
                    $('#btnSaveUnidade').text('Salvar'); //change button text
                    $('#btnSaveUnidade').attr('disabled', false); //set button enable
                }
            });
        }

        function save_andar() {
            $('#btnSaveAndar').text('Salvando...'); //change button text
            $('#btnSaveAndar').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('facilities/estruturas/ajaxAddAndar') ?>";
            } else {
                url = "<?php echo site_url('facilities/estruturas/ajaxUpdateAndar') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_andares').serialize(),
                dataType: "json",
                success: function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_andares').modal('hide');
                        reload_table();
                    }
                },
                complete: function () {
                    $('#btnSaveAndar').text('Salvar'); //change button text
                    $('#btnSaveAndar').attr('disabled', false); //set button enable
                }
            });
        }

        function save_sala() {
            $('#btnSaveSala').text('Salvando...'); //change button text
            $('#btnSaveSala').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('facilities/estruturas/ajaxAddSala') ?>";
            } else {
                url = "<?php echo site_url('facilities/estruturas/ajaxUpdateSala') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_salas').serialize(),
                dataType: "json",
                success: function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_salas').modal('hide');
                        reload_table();
                    }
                },
                complete: function (jqXHR, textStatus, errorThrown) {
                    $('#btnSaveSala').text('Salvar'); //change button text
                    $('#btnSaveSala').attr('disabled', false); //set button enable
                }
            });
        }


        function delete_empresa(id) {
            if (confirm('Deseja remover a empresa?')) {
                $.ajax({
                    url: "<?php echo site_url('facilities/estruturas/ajaxDelete') ?>",
                    type: "POST",
                    dataType: "json",
                    data: {id: id},
                    success: function () {
                        reload_table();
                    }
                });
            }
        }

        function delete_unidade(id) {
            if (confirm('Deseja remover a unidade?')) {
                $.ajax({
                    url: "<?php echo site_url('facilities/estruturas/ajaxDeleteUnidade') ?>",
                    type: "POST",
                    dataType: "json",
                    data: {id: id},
                    success: function () {
                        reload_table();
                    }
                });
            }
        }

        function delete_andar(id) {
            if (confirm('Deseja remover o andar?')) {
                $.ajax({
                    url: "<?php echo site_url('facilities/estruturas/ajaxDeleteAndar') ?>",
                    type: "POST",
                    dataType: "json",
                    data: {id: id},
                    success: function () {
                        reload_table();
                    }
                });
            }
        }

        function delete_sala(id) {
            if (confirm('Deseja remover a sala?')) {
                $.ajax({
                    url: "<?php echo site_url('facilities/estruturas/ajaxDeleteSala') ?>",
                    type: "POST",
                    dataType: "json",
                    data: {id: id},
                    success: function () {
                        reload_table();
                    }
                });
            }
        }

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>
