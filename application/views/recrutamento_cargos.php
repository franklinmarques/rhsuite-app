<?php
require_once "header.php";
?>
    <style>
        div.dataTables_wrapper div.dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200px;
            font-weight: bold;
            margin-left: -100px;
            margin-top: -26px;
            text-align: center;
            padding: 1em 0;
        }

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
                        <?php if ($nome_recrutamento): ?>
                            <li><a href="<?= site_url('recrutamento') ?>">Gestão de processos seletivos</a></li>
                            <li class="active"><?= $nome_recrutamento ?></li>
                        <?php else: ?>
                            <li class="active">Gestão de cargos de processos seletivos</li>
                        <?php endif; ?>
                    </ol>
                    <button class="btn btn-success" onclick="add_cargo()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar cargo-função
                    </button>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Cargo/função</th>
                            <th>Ações para cargo</th>
                            <th>Candidatos</th>
                            <th>Aproveitamento</th>
                            <th>Ações para candidato</th>
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
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulario de cargo/função</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $recrutamento ?>" name="id_recrutamento"/>
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
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_candidato" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Adicionar candidato</h3>
                        </div>
                        <div class="modal-body form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="control-label">Estado</label>
                                                <?php echo form_dropdown('estado', array(), '', 'id="estado" class="form-control filtro input-sm"'); ?>
                                            </div>
                                            <div class="col-md-7">
                                                <label class="control-label">Cidade</label>
                                                <?php echo form_dropdown('cidade', array(), '', 'id="cidade" class="form-control filtro input-sm"'); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <label>&nbsp;</label><br>
                                                <div class="btn-group" role="group" aria-label="...">
                                                    <button type="submit" id="limpa_filtro"
                                                            class="btn btn-sm btn-default">Limpar filtros
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <label class="control-label">Bairro</label>
                                                <?php echo form_dropdown('bairro', array(), '', 'id="bairro" class="form-control filtro input-sm"'); ?>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="control-label">Deficiência</label>
                                                <?php echo form_dropdown('deficiencia', array(), '', 'id="deficiencia" class="form-control filtro input-sm"'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form action="#" id="form_candidato" class="form-horizontal">
                                <div class="form-body" style="padding: 0 20px 20px;">
                                    <input type="hidden" value="" name="id"/>
                                    <input type="hidden" value="" name="id_cargo"/>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <label class="control-label">Candidato a ser incluso no processo
                                                seletivo</label>
                                            <?php echo form_dropdown('id_usuario', array(), '', 'id="id_usuario" class="form-control"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveCandidato" onclick="salvar_candidato()"
                                    class="btn btn-primary">Salvar
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
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
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Avaliações por período de experiência - ';
        });</script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table;
        var candidato;

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
                    "url": "<?php echo site_url('recrutamento_cargos/ajax_list/' . $recrutamento) ?>",
                    "type": "POST"
                },
                rowsGroup: [0, 1],
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '50%',
                        targets: [0, 2]
                    },
                    {
                        "mRender": function (data) {
                            if (data === null) {
                                data = '<span class="text-muted">Nenhum candidato encontrado</span>';
                            }
                            return data;
                        },
                        "targets": [2]
                    },
                    {
                        className: "text-center",
                        "mRender": function (data) {
                            if (data !== null) {
                                data += '%';
                            }
                            return data;
                        },
                        "targets": [3]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1, -4], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ]
            });

        });

        $('#id_usuario').on('change', function () {
            candidato = $(this).val();
        });

        $('.filtro').on('change', function () {
            filtra_candidatos();
        });

        $('#limpa_filtro').on('click', function () {
            $('.filtro').val('');
            filtra_candidatos();
        });

        function filtra_candidatos() {
            $.ajax({
                url: "<?php echo site_url('recrutamento_cargos/ajax_candidatos/') ?>/",
                type: "POST",
                dataType: "JSON",
                data: {
                    id_cargo: $('[name="id_cargo"]').val(),
                    estado: $('#estado').val(),
                    cidade: $('#cidade').val(),
                    bairro: $('#bairro').val(),
                    deficiencia: $('#deficiencia').val()
                },
                success: function (data) {
                    $('#estado').html(data.estados);
                    $('#cidade').html(data.cidades);
                    $('#bairro').html(data.bairros);
                    $('#deficiencia').html(data.deficiencias);
                    $('#id_usuario').html(data.candidatos).val(candidato);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function add_cargo() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_recrutamento"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('#modal_form .modal-title').text('Adicionar cargo/função'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function add_candidato(id) {
            $('#form_candidato')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('[name="id_cargo"]').val(id);
            $('.filtro').val('');
            candidato = '';
            filtra_candidatos();
            $('#modal_candidato').modal('show'); // show bootstrap modal
            $('.combo_nivel1').hide();
        }

        function edit_cargo(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_recrutamento"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('recrutamento_cargos/ajax_edit/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('[name="id"]').val(data.id);
                    $('[name="id_recrutamento"]').val(data.id_recrutamento);
                    $('[name="cargo"]').val(data.cargo);

                    $('#modal_form').modal('show');
                    $('#modal_form .modal-title').text('Editar cargo/função'); // Set title to Bootstrap modal title
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
                url = "<?php echo site_url('recrutamento_cargos/ajax_add') ?>";
            } else {
                url = "<?php echo site_url('recrutamento_cargos/ajax_update') ?>";
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

        function salvar_candidato() {
            $('#btnSaveCandidato').text('Salvando...'); //change button text
            $('#btnSaveCandidato').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('recrutamento_cargos/ajax_addCandidato') ?>",
                type: "POST",
                data: $('#form_candidato').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_candidato').modal('hide');
                        reload_table();
                    }

                    $('#btnSaveCandidato').text('Salvar'); //change button text
                    $('#btnSaveCandidato').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveCandidato').text('Salvar'); //change button text
                    $('#btnSaveCandidato').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_cargo(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('recrutamento_cargos/ajax_delete') ?>/" + id,
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

        function delete_candidato(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('recrutamento_cargos/ajax_deleteCandidato') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        //if success reload ajax table
                        $('#modal_candidato').modal('hide');
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