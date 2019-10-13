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
                        <?php if ($nome_cargo): ?>
                            <li><a href="<?= site_url('requisicaoPessoal') ?>">Gerenciar Requisições de Pessoal</a></li>
                            <li>
                                <a href="<?= site_url('recrutamentoPresencial_cargos/gerenciar/' . $requisicao) ?>"><?= $nome_requisicao ?></a>
                            </li>
                            <li class="active"><?= $nome_cargo ?> - <?= $nome_candidato ?></li>
                        <?php else: ?>
                            <li><a href="<?= site_url('recrutamento_candidatos') ?>">Gerenciamento de candidatos</a>
                            </li>
                            <li><a href="<?= site_url('recrutamento/candidatos/' . $id_usuario) ?>">Gestão de processos
                                    seletivos - <?= $nome_candidato ?></a></li>
                            <li class="active"><?= $nome_requisicao ?></li>
                        <?php endif; ?>
                    </ol>
                    <?php if ($nome_cargo): ?>
                        <button class="btn btn-info" onclick="add_teste()"><i class="glyphicon glyphicon-plus"></i>
                            Adicionar teste
                        </button>
                    <?php else: ?>
                        <button class="btn btn-info" onclick="add_cargo()"><i class="glyphicon glyphicon-plus"></i>
                            Adicionar cargo-função
                        </button>
                    <?php endif; ?>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th id="sorter">Cargo/função</th>
                            <th>Ação para cargo</th>
                            <th>Teste</th>
                            <th>Início</th>
                            <th>Término</th>
                            <th>Aproveitamento</th>
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
            <div class="modal fade" id="modal_cargos" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulario de cargo/função</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_cargos" class="form-horizontal">
                                <input type="hidden" value="<?= $requisicao ?>" name="id_requisicao"/>
                                <input type="hidden" value="<?= $id_usuario ?>" name="id_usuario"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Nome do cargo/função</label>
                                        <div class="col-md-9">
                                            <input name="cargo" id="cargo" placeholder="Nome do cargo/função"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSalvar_cargo" onclick="salvar_cargo()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulario de teste</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="P" name="tipo_teste"/>
                                <input type="hidden" value="<?= $id_candidato ?>" name="id_candidato"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Modelo de teste</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_modelo', $modelos, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Data e hora de início</label>
                                        <div class="col-md-9 form-inline">
                                            <input name="data_inicio" id="data_inicio" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center" type="text" style="width: 150px;">
                                            <input name="hora_inicio" id="hora_inicio" placeholder="hh:mm"
                                                   class="form-control text-center" type="text" style="width: 80px;">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Data e hora de término</label>
                                        <div class="col-md-9 form-inline">
                                            <input name="data_termino" id="data_termino" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center" type="text" style="width: 150px;">
                                            <input name="hora_termino" id="hora_termino" placeholder="hh:mm"
                                                   class="form-control text-center" type="text" style="width: 80px;">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-inline form-group">
                                        <label class="control-label col-md-3">Nota aproveitamento</label>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control text-right"
                                                   name="nota_aproveitamento" id="nota_aproveitamento"
                                                   style="width: 110px;">
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
            document.title = 'CORPORATE RH - LMS - Gestão de testes de processo seletivo - <?= $nome_candidato ?>';
        });</script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table;

        $('#data_inicio, #data_termino').mask('00/00/0000');
        $('#hora_inicio, #hora_termino').mask('00:00');
        $('#nota_aproveitamento').mask('##0,0', {reverse: true});

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                "info": false,
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('recrutamentoPresencial_processos/ajax_list/' . $id_usuario . '/' . ($nome_cargo ? $id_candidato : '') . '/2') ?>",
                    "type": "POST"
                },
                rowsGroup: [0, 1],
                //Set column definition initialisation properties.
                "columnDefs": [
//                {
//                    orderData: [0,2,3,4,5],
//                    targets: [0]
//                },
//                {
//                    orderData: [2,3,4,5],
//                    targets: [2]
//                },
//                {
//                    orderData: [3,4,5],
//                    targets: [3]
//                },
//                {
//                    orderData: [4,5],
//                    targets: [4]
//                },
                    {
                        width: "<?= $nome_cargo ? '100%' : '50%' ?>",
                        targets: [0, 2]
                    },
                    {
                        visible: <?= $nome_cargo ? 'false' : 'true' ?>,
                        targets: [0, 1]
                    },
                    {
                        className: 'text-center',
                        targets: [-3, -4, 5]
                    },
                    {
                        "mRender": function (data) {
                            if (data === null) {
                                data = '<span class="text-muted">Nenhum modelo de teste encontrado</span>';
                            }
                            return data;
                        },
                        "targets": [2]
                    },
                    {
                        "mRender": function (data) {
                            if (data !== null) {
                                data += '%';
                            }
                            return data;
                        },
                        "targets": [5]
                    },
                    {
                        title: " <?= $nome_cargo ? 'Ações' : 'Ações para teste' ?>"
                    },
                    {
                        className: "text-nowrap",
                        orderable: false,
                        searchable: false,
                        targets: [1, -1]
                    }
                ]
            });

        });

        function add_teste() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_candidato"], [name="tipo_teste"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#hora_inicio').val('00:00');
            $('#hora_termino').val('23:59');
            $('#modal_form').modal('show'); // show bootstrap modal
            $('#modal_form .modal-title').text('Adicionar teste'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_teste(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_candidato"], [name="tipo_teste"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('recrutamentoPresencial_processos/ajax_edit/') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    $('[name="id"]').val(data.id);
                    $('[name="id_modelo"]').val(data.id_modelo);
                    $('[name="data_inicio"]').val(data.data_inicio);
                    $('[name="data_termino"]').val(data.data_termino);
                    $('[name="hora_inicio"]').val(data.hora_inicio);
                    $('[name="hora_termino"]').val(data.hora_termino);
                    $('[name="nota_aproveitamento"]').val(data.nota_aproveitamento);


                    $('#modal_form').modal('show');
                    $('#modal_form .modal-title').text('Editar teste'); // Set title to Bootstrap modal title
                },
                error: function (jqXHR, textStatus, errorThrown) {
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
                url = "<?php echo site_url('recrutamentoPresencial_processos/ajax_add') ?>";
            } else {
                url = "<?php echo site_url('recrutamentoPresencial_processos/ajax_update') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
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

        function delete_teste(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('recrutamentoPresencial_processos/ajax_delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

    </script>

<?php
require_once "end_html.php";
?>