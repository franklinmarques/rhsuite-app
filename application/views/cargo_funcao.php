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
                            <div id="wizard">
                                <h6>Cargos</h6>
                                <section style="padding: 0; border-top: 1px solid #ddd; height: auto;">
                                    <br>
                                    <button id="novo_cargo" class="btn btn-info" onclick="add_cargo()"><i
                                                class="glyphicon glyphicon-plus"></i> Novo cargo
                                    </button>
                                    <br>
                                    <div class="table-responsive">
                                        <table id="table_cargo" class="table table-striped table-condensed"
                                               cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Cargo</th>
                                                <th nowrap>CBO - Família Ocupacional</th>
                                                <th>Ações</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>

                                <h6>Funções</h6>
                                <section style="padding: 0; border-top: 1px solid #ddd;">
                                    <br>
                                    <button id="nova_funcao" class="btn btn-info" onclick="add_funcao()"><i
                                                class="glyphicon glyphicon-plus"></i> Nova função
                                    </button>
                                    <br>
                                    <div class="table-responsive">
                                        <table id="table_funcao" class="table table-striped table-condensed"
                                               cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Cargo</th>
                                                <th nowrap>Família CBO</th>
                                                <th nowrap>Função/ocupação</th>
                                                <th nowrap>Ocupação CBO</th>
                                                <th>Ações</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
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
                                <input type="hidden" value="<?php echo $empresa; ?>" name="id_empresa"/>
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome</label>
                                        <div class="col-md-9">
                                            <input name="nome" class="form-control" type="text" maxlength="255"
                                                   autofocus>
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
                                        <label class="control-label col-md-2">Nome</label>
                                        <div class="col-md-9">
                                            <input name="nome" class="form-control" type="text" maxlength="255"
                                                   autofocus>
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
    <link href="<?php echo base_url('assets/css/jquery.steps.css?1') ?>" rel="stylesheet">

<?php require_once "end_js.php"; ?>
    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'RhSuite - Corporate RH Tools: Gerenciar Cargos/Funções';
        });

    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery-steps/jquery.steps.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

    <script>
        var save_method;
        var table_cargo, table_funcao;
        var id_cargo = '';

        var steps = $("#wizard").steps({
            headerTag: "h6",
            bodyTag: "section",
//        transitionEffect: "slideLeft",
            transitionEffect: 0,
            autoFocus: true,
            enableFinishButton: false,
            enablePagination: false,
            enableAllSteps: true,
            titleTemplate: "#title#",
            startIndex: <?php echo $indice; ?>
        });

        $(document).ready(function () {

            $('#familia_CBO').mask('0000');
            $('#ocupacao_CBO').mask('00');

            //datatables
            table_cargo = $('#table_cargo').DataTable({
                "info": true,
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                "iDisplayLength": -1,
                "bLengthChange": false,
                "searching": false,
                "paging": false,
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('cargo_funcao/ajax_cargo/') ?>",
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

            table_funcao = $('#table_funcao').DataTable({
                "info": true,
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                "iDisplayLength": -1,
                "bLengthChange": false,
                "searching": false,
                "paging": false,
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('cargo_funcao/ajax_funcao/') ?>",
                    "type": "POST",
                    "data": function (d) {
                        d.id_cargo = id_cargo;
                        return d;
                    }
                },

                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '50%',
                        targets: [0, 2]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ],
                "drawCallback": function () {
                    if (id_cargo.length === 0) {
                        $('#nova_funcao').addClass('btn-warning').removeClass('btn-info');
                    } else {
                        $('#nova_funcao').addClass('btn-info').removeClass('btn-warning');
                    }
                }
            });

        });

        $('.modal').on('shown.bs.modal', function () {
            $(this).find('[autofocus]').focus().select();
        });

        function add_cargo() {
            save_method = 'add';
            $('#form_cargo')[0].reset(); // reset form on modals
            $('#form_cargo [name="id"]').val('');
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_cargo').modal('show'); // show bootstrap modal
            $('#form_cargo [name="nome"]').focus();
            $('.modal-title').text('Adicionar novo cargo'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function add_funcao() {
            if (id_cargo.length === 0) {
                alert('Selecione o cargo onde será adicionada a nova função.');
                return false;
            }
            save_method = 'add';
            $('#form_funcao')[0].reset(); // reset form on modals
            $('#form_funcao [name="id"]').val('');
            $('#form_funcao [name="id_cargo"]').val(id_cargo);
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_funcao').modal('show'); // show bootstrap modal
            $('#form_funcao [name="nome"]').focus();
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
                url: "<?php echo site_url('cargo_funcao/ajax_editCargo') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (json) {
                    $('[name="id"]').val(json.id);
                    $('[name="id_empresa"]').val(json.id_empresa);
                    $('[name="nome"]').val(json.nome);
                    $('[name="familia_CBO"]').val(json.familia_CBO);

                    $('#modal_cargo').modal('show');
                    $('.modal-title').text('Editar cargo - ' + json.nome); // Set title to Bootstrap modal title
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_funcao(id) {
            save_method = 'update';
            $('#form_funcao')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('cargo_funcao/ajax_editFuncao') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (json) {
                    $('[name="id"]').val(json.id);
                    $('[name="id_cargo"]').val(json.id_cargo);
                    $('[name="nome"]').val(json.nome);
                    $('[name="ocupacao_CBO"]').val(json.ocupacao_CBO);

                    $('#modal_funcao').modal('show');
                    $('.modal-title').text('Editar função/ocupação - ' + json.nome); // Set title to Bootstrap modal title
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function save_cargo() {
            $('#btnSaveCargo').text('Salvando...'); //change button text
            $('#btnSaveCargo').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('cargo_funcao/ajax_addCargo') ?>";
            } else {
                url = "<?php echo site_url('cargo_funcao/ajax_updateCargo') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_cargo').serialize(),
                dataType: "JSON",
                success: function (json) {
                    if (json.status) {
                        $('#modal_cargo').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveCargo').text('Salvar'); //change button text
                    $('#btnSaveCargo').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveCargo').text('Salvar'); //change button text
                    $('#btnSaveCargo').attr('disabled', false); //set button enable
                }
            });
        }

        function save_funcao() {
            $('#btnSaveFuncao').text('Salvando...'); //change button text
            $('#btnSaveFuncao').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('cargo_funcao/ajax_addFuncao') ?>";
            } else {
                url = "<?php echo site_url('cargo_funcao/ajax_updateFuncao') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_funcao').serialize(),
                dataType: "JSON",
                success: function (json) {
                    if (json.status) {
                        $('#modal_funcao').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveFuncao').text('Salvar'); //change button text
                    $('#btnSaveFuncao').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveFuncao').text('Salvar'); //change button text
                    $('#btnSaveFuncao').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_cargo(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    url: "<?php echo site_url('cargo_funcao/ajax_deleteCargo') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {id: id},
                    success: function (data) {
                        $('#modal_cargo').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                        alert('Error deleting data');
                    }
                });
            }
        }

        function delete_funcao(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    url: "<?php echo site_url('cargo_funcao/ajax_deleteFuncao') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {id: id},
                    success: function (data) {
                        $('#modal_funcao').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                        alert('Error deleting data');
                    }
                });
            }
        }

        function reload_table() {
            table_cargo.ajax.reload(null, false);
            table_funcao.ajax.reload(null, false);
        }

        function nextFuncao(id) {
            id_cargo = id;
            //steps.next(id);
            $('#wizard-t-1').trigger('click');
            reload_table();
        }
    </script>

<?php require_once "end_html.php"; ?>