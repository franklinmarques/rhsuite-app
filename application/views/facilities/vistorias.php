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
                        <li class="active">Gestão de Vistorias</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_vistoria()"><i class="glyphicon glyphicon-plus"></i>
                        Cadastrar vistoria
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th nowrap>Identificação de vistoria</th>
                            <th>Mês/ano</th>
                            <th>Status</th>
                            <th>Pendências</th>
                            <th>Vistoriador(es)</th>
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
                            <h3 class="modal-title">Cadastro/gestão de vistoria</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $idEmpresa; ?>" name="id_empresa"/>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Modelo de vistoria</label>
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
                                        <label class="col-md-2 control-label">Vistoriador(a)</label>
                                        <div class="col-md-9 controls">
                                            <?php echo form_dropdown('id_usuario_vistoriador', $vistoriadores, '', 'class="form-control"'); ?>
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
            document.title = 'CORPORATE RH - LMS - Facilities: Gestão de Vistorias';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table;

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
                    'url': '<?php echo site_url('facilities/vistorias/ajaxList') ?>',
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
                        'targets': [1]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[6] === 'P') {
                                $(td).css('background-color', '#ff0');
                            } else if (rowData[6] === 'N') {
                                $(td).css({'background-color': '#f00', 'color': '#fff'});
                            } else if (rowData[6] === 'R') {
                                $(td).css({'background-color': '#0c0', 'color': '#fff'});
                            }
                        },
                        'className': 'text-center text-nowrap',
                        'targets': [2]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[7] === null) {
                                $(td).css('background-color', '#ff0');
                            } else if (rowData[7] === '1') {
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

        });

        function add_vistoria() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#form [name="id"]').val('');
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form [name="id"]').val('');
            $('#modal_form').modal('show');
            $('.combo_nivel1').hide();
        }

        function edit_vistoria(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': "<?php echo site_url('facilities/vistorias/ajaxEdit') ?>",
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


        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }


        function save() {
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('facilities/vistorias/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('facilities/vistorias/ajaxUpdate') ?>";
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

        function delete_vistoria(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': "<?php echo site_url('facilities/vistorias/ajaxDelete') ?>",
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
