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
                        <li class="active">Gestão de Manutenções</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_manutencao()"><i class="glyphicon glyphicon-plus"></i>
                        Cadastrar manutenção
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th nowrap>Identificação de manutenção</th>
                            <th>Mês/ano</th>
                            <th>Status</th>
                            <th>Pendências</th>
                            <th>Responsável</th>
                            <th>Execução</th>
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
                            <h3 class="modal-title">Cadastro/gestão de manuteção</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $idEmpresa; ?>" name="id_empresa"/>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Modelo de manutenção</label>
                                        <div class="col-md-9 controls">
                                            <?php echo form_dropdown('id_modelo', $modelos, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Mês</label>
                                        <div class="col-md-4 controls">
                                            <?php echo form_dropdown('mes', $meses, '01', 'class="form-control"'); ?>
                                        </div>
                                        <label class="col-md-1 control-label">Ano</label>
                                        <div class="col-md-2 controls">
                                            <input name="ano" class="form-control text-center ano" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Status</label>
                                        <div class="col-md-4 controls">
                                            <select name="status" class="form-control">
                                                <option value="P">Programada</option>
                                                <option value="N">Não realizada</option>
                                                <option value="R">Realizada</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 controls">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="pendencias" type="checkbox" value="1"> Possui
                                                    pendências</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Responsável</label>
                                        <div class="col-md-9 controls">
                                            <?php echo form_dropdown('id_usuario_vistoriador', $responsaveis, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Executor</label>
                                        <div class="col-md-4 controls">
                                            <select name="tipo_executor" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="I">Interno</option>
                                                <option value="E">Externo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancelar
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

            <div class="modal fade" id="modal_laudo" role="dialog">
                <div class="modal-dialog" style="width: 782px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Gerenciar manutenção periódica (laudos de terceiros)</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_laudo" class="form-horizontal" enctype="multipart/form-data"
                                  accept-charset="utf-8">
                                <input type="hidden" value="" name="id_realizacao"/>
                                <div class="row form-group">
                                    <label class="col-md-2 control-label">Ativo/facility</label>
                                    <div class="col col-md-7">
                                        <?php echo form_dropdown('id_item', $itens, '', 'class="form-control"'); ?>
                                    </div>
                                    <div class="col col-md-3 text-right">
                                        <button type="button" id="btnSaveLaudo" onclick="salvar_laudo()"
                                                class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>
                                            Salvar
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar
                                        </button>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2 control-label">Arquivo</label>
                                    <div class="col col-md-9">
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-default btn-file">
                                                <input type="file" name="arquivo" accept="application/pdf">
                                                <span class="fileinput-new">Selecionar arquivo</span>
                                                <span class="fileinput-exists">Alterar</span>
                                            </span>
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                               data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5>Dados de armazenagem física</h5>
                                <div class="row form-group">
                                    <label class="col-md-2 control-label">Local/armazém</label>
                                    <div class="col col-md-9">
                                        <input type="text" name="local_armazem" class="form-control">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2 control-label">Sala/box</label>
                                    <div class="col col-md-9">
                                        <input type="text" name="sala_box" class="form-control">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2 control-label">Arquivo físico</label>
                                    <div class="col col-md-9">
                                        <input type="text" name="arquivo_fisico" class="form-control">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2 control-label">Pasta/caixa</label>
                                    <div class="col col-md-9">
                                        <input type="text" name="pasta_caixa" class="form-control">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-3 control-label">Código localizador</label>
                                    <div class="col col-md-8">
                                        <input type="text" name="codigo_localizador" class="form-control" readonly>
                                    </div>
                                </div>
                            </form>
                            <table id="table_laudo" class="table table-bordered table-condensed" width="100%">
                                <thead>
                                <tr>
                                    <th>Ativo/facility</th>
                                    <th>Arquivo</th>
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

        </section>
    </section>

    <!--main content end-->

<?php
require_once APPPATH . 'views/end_js.php';
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Facilities: Gestão de Manutenções';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table, table_laudo;

        $('.ano').mask('0000');

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                dom: "<'row'<'.col-sm-3'l><'#status.col-sm-3'><'#ano.col-sm-2'><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                'processing': true, //Feature control the processing indicator.
                'serverSide': true, //Feature control DataTables' server-side processing mode.
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                // Load data for the table's content from an Ajax source
                'ajax': {
                    'url': '<?php echo site_url('facilities/manutencoes/ajaxList') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        if ($('#status [name="busca_status"]').val() !== undefined) {
                            d.status = $('#status [name="busca_status"]').val();
                        } else {
                            d.status = '';
                        }
                        if ($('#ano [name="busca_ano"]').val() !== undefined) {
                            d.ano = $('#ano [name="busca_ano"]').val();
                        } else {
                            d.ano = '';
                        }
                        return d;
                    },
                    'dataSrc': function (json) {
                        if (json.draw === 1) {
                            $("#status").append('<br>Status&nbsp;' + json.status);
                            $("#ano").append('<br>Ano&nbsp;' + json.ano);
                        }

                        return json.data;
                    }
                },
                //Set column definition initialisation properties.
                'columnDefs': [
                    {
                        'width': '50%',
                        'targets': [0, 4]
                    },
                    {
                        'className': 'text-center text-nowrap',
                        'targets': [1, 5]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[7] === 'P') {
                                $(td).css('background-color', '#ff0');
                            } else if (rowData[7] === 'N') {
                                $(td).css({'background-color': '#f00', 'color': '#fff'});
                            } else if (rowData[7] === 'R') {
                                $(td).css({'background-color': '#0c0', 'color': '#fff'});
                            }
                        },
                        'className': 'text-center text-nowrap',
                        'targets': [2]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[8] === null) {
                                $(td).css('background-color', '#ff0');
                            } else if (rowData[8] === '1') {
                                $(td).css({'background-color': '#f00', 'color': '#fff'});
                            }
                        },
                        'className': 'text-center text-nowrap',
                        'targets': [3]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ],
                'rowsGroup': [0, 1, 2, 3, 4]
            });


            table_laudo = $('#table_laudo').DataTable({
                'processing': true,
                'serverSide': true,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('facilities/manutencoes/ajaxLaudos') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.id_realizacao = $('#form_laudo input[name="id_realizacao"]').val();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '45%',
                        'targets': [0]
                    },
                    {
                        'width': '55%',
                        'targets': [1]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ]
            });

        });

        // Ajusta a largura das colunas dos tabelas do tipo DataTables em uma modal
        $(document).on('shown.bs.modal', function (e) {
            $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
        });


        function add_manutencao() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#form [name="id"]').val('');
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form [name="id"]').val('');
            $('#modal_form').modal('show');
            $('.combo_nivel1').hide();
        }

        function edit_manutencao(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': "<?php echo site_url('facilities/manutencoes/ajaxEdit') ?>",
                'type': "POST",
                'dataType': "json",
                'data': {'id': id},
                'success': function (json) {
                    $.each(json, function (key, value) {
                        if ($('#form [name="' + key + '"]').is(':checkbox') === false) {
                            $('#form [name="' + key + '"]').val(value);
                        } else {
                            $('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value === '1');
                        }
                    });

                    $('#modal_form').modal('show');
                }
            });
        }

        function laudos(id) {
            $('#form_laudo')[0].reset();

            $.ajax({
                'url': "<?php echo site_url('facilities/manutencoes/editLaudos') ?>",
                'type': "POST",
                'dataType': "json",
                'data': {'id': id},
                'success': function (json) {
                    $('#form_laudo [name="id_realizacao"]').val(id);
                    $('#form_laudo [name="id_item"]').html($(json.itens).html());
                    $('#form_laudo [name="codigo_localizador"]').val(json.codigo_localizador);
                    reload_table_laudo();
                    $('#modal_laudo').modal('show');
                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }

        function reload_table_laudo() {
            table_laudo.ajax.reload(null, false); //reload datatable ajax
        }


        function save() {
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('facilities/manutencoes/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('facilities/manutencoes/ajaxUpdate') ?>";
            }

            // ajax adding data to database
            $.ajax({
                'url': url,
                'type': "POST",
                'data': $('#form').serialize(),
                'dataType': "json",
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
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }

        function salvar_laudo() {
            $('#btnSaveLaudo').text('Salvando...'); //change button text
            $('#btnSaveLaudo').attr('disabled', true); //set button disable

            var form = $('#form_laudo')[0];
            var data = new FormData(form);

            $.ajax({
                'url': '<?php echo site_url('facilities/manutencoes/salvarLaudo') ?>',
                'type': 'POST',
                'data': data,
                'dataType': "json",
                'enctype': 'multipart/form-data',
                'processData': false,
                'contentType': false,
                'cache': false,
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#form_laudo')[0].reset();
                        reload_table_laudo();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'complete': function () {
                    $('#btnSaveLaudo').text('Salvar'); //change button text
                    $('#btnSaveLaudo').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_manutencao(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': "<?php echo site_url('facilities/manutencoes/ajaxDelete') ?>",
                    'type': "POST",
                    'dataType': "json",
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
