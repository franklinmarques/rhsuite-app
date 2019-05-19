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
                        <li class="active">Gerenciar fontes/aprovadores</li>
                    </ol>
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-info" onclick="add_fonte()"><i class="glyphicon glyphicon-plus"></i>
                                Adicionar fonte
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-info" onclick="add_aprovador()"><i
                                        class="glyphicon glyphicon-plus"></i>
                                Adicionar aprovador
                            </button>
                        </div>
                    </div>
                    <br/>
                    <br/>
                    <div class="row">
                        <div class="col-md-6">
                            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table id="table_aprovador" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                            <h3 class="modal-title">Formulario de fontes de apontamento</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                                <input type="hidden" value="" name="id"/>
                                <div id="fonte_atual">
                                    <span class="form-group-static"></span>
                                    <hr>
                                </div>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Nome da fonte</label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Digite o nome da fonte" class="form-control"
                                                   type="text">
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

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_aprovador" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Adicionar aprovador</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert1"></div>
                            <form action="#" id="form_aprovador" class="form-horizontal">
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Filtrar por cargo:</label>
                                        <div class="col-md-9">
                                            <select id="cargo" class="form-control">
                                                <option value="">Todos</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Aprovador</label>
                                        <div class="col-md-9">
                                            <select name="id_usuario" class="form-control">
                                                <option value="">selecione...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveAprovador" onclick="save_aprovador()"
                                    class="btn btn-success">Salvar
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
require_once "end_js.php";
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar fontes/aprovadores';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script>

        var save_method; //for save method string
        var table, table_aprovador;

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                'iDisplayLength': 100,
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('requisicaoPessoal_fontes/ajaxList/') ?>",
                    "type": "POST"
                },

                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '100%',
                        targets: [0]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ]
            });

            table_aprovador = $('#table_aprovador').DataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('requisicaoPessoal_fontes/ajaxListAprovador/') ?>",
                    "type": "POST"
                },

                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '100%',
                        targets: [0]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ]
            });

        });


        $('#cargo').on('change', function () {
            $.ajax({
                url: "<?php echo site_url('requisicaoPessoal_fontes/atualizarAprovador') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    cargo: $('#cargo').val(),
                    id_usuario: $('#modal_aprovador [name="id_usuario"]').val()
                },
                success: function (json) {
                    $('#modal_aprovador [name="id_usuario"]').html($(json.id_usuario).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });

        function add_fonte() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#fonte_atual').hide();
            $('#fonte_atual span').html('');
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar fonte de candidatos'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function add_aprovador() {
            $('#form_aprovador')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $.ajax({
                url: "<?php echo site_url('requisicaoPessoal_fontes/ajaxEditAprovador') ?>",
                type: "POST",
                dataType: "json",
                success: function (json) {
                    $('#cargo').html($(json.cargo).html());
                    $('#modal_aprovador [name="id_usuario"]').html($(json.id_usuario).html());

                    $('#modal_aprovador').modal('show');
                    $('.combo_nivel1').hide();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_fonte(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('requisicaoPessoal_fontes/ajaxEdit') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (json) {
                    $('[name="id"]').val(json.id);
                    $('[name="id_empresa"]').val(json.id_empresa);
                    $('[name="nome"]').val(json.nome);
                    $('#fonte_atual span').html(json.nome);
                    $('#fonte_atual').show();

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar fonte de candidatos'); // Set title to Bootstrap modal title

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

        }

        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }

        function reload_table_aprovador() {
            table_aprovador.ajax.reload(null, false); //reload datatable ajax
        }

        function save() {
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('requisicaoPessoal_fontes/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('requisicaoPessoal_fontes/ajaxUpdate') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }

        function save_aprovador() {
            $('#btnSaveAprovador').text('Salvando...'); //change button text
            $('#btnSaveAprovador').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                url: '<?php echo site_url('requisicaoPessoal_fontes/ajaxAddAprovador') ?>',
                type: "POST",
                data: $('#form_aprovador').serialize(),
                dataType: "JSON",
                success: function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_aprovador').modal('hide');
                        reload_table_aprovador();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveAprovador').text('Salvar'); //change button text
                    $('#btnSaveAprovador').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveAprovador').text('Salvar'); //change button text
                    $('#btnSaveAprovador').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_fonte(id) {
            if (confirm('Deseja remover a fonte de contratação?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('requisicaoPessoal_fontes/ajaxDelete') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {id: id},
                    success: function (json) {
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
//                    alert('Error deleting data');
                    }
                });

            }
        }

        function delete_aprovador(id) {
            if (confirm('Deseja remover o aprovador?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('requisicaoPessoal_fontes/ajaxDeleteAprovador') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {id: id},
                    success: function (json) {
                        //if success reload ajax table
                        reload_table_aprovador();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
//                    alert('Error deleting data');
                    }
                });

            }
        }

    </script>

<?php
require_once "end_html.php";
?>