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
                        <li class="active">Gestão de modelos de vistorias/manutenções</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_modelo_vistoria()"><i
                                class="glyphicon glyphicon-plus"></i>
                        Cadastrar modelo
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th nowrap>Identificação do plano</th>
                            <th nowrap>Tipo modelo</th>
                            <th nowrap>Versão do plano</th>
                            <th>Status</th>
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
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Cadastro/gestão de modelo de vistoria/manutenção</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $idEmpresa; ?>" name="id_empresa"/>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-1 control-label">Nome</label>
                                        <div class="col-md-6 controls">
                                            <input name="nome" placeholder="Digite o nome de identificação do plano"
                                                   class="form-control" type="text">
                                        </div>
                                        <label class="col-md-1 control-label">Versão</label>
                                        <div class="col-md-2 controls">
                                            <input name="versao" class="form-control" type="text">
                                        </div>
                                        <div class="col-md-2 controls">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="status" type="checkbox" value="1"> Plano ativo</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Empresa (Facilities)</label>
                                        <div class="col-md-7 controls">
                                            <?php echo form_dropdown('id_facility_empresa', $facilityEmpresas, '', 'id="id_facility_empresa" class="form-control"'); ?>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
                                                Salvar
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Unidades</label>
                                        <div class="col-md-7 controls">
                                            <?php echo form_dropdown('', ['' => 'Todas'], '', 'id="id_unidades" class="form-control"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="radio-inline">
                                                <input type="radio" class="tipo" name="tipo" value="V" checked="">
                                                Vistoria
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" class="tipo" name="tipo" value="M"> Manutenção
                                            </label>
                                        </div>
                                    </div>
                                    <table id="table_itens" class="table table-striped table-bordered table-condensed"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Unidade</th>
                                            <th>Andar</th>
                                            <th>Piso</th>
                                            <th>Ativo/facility</th>
                                            <th>Item</th>
                                            <th>Tipo ação</th>
                                            <th>Ativar item</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
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

    <script>

        var save_method; //for save method string
        var table, table_itens;


        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                dom: "<'row'<'.col-sm-4'l><'#status.col-sm-4'><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                'processing': true, //Feature control the processing indicator.
                'serverSide': true, //Feature control DataTables' server-side processing mode.
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                // Load data for the table's content from an Ajax source
                'ajax': {
                    'url': '<?php echo site_url('facilities/modelos/ajaxList') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        if ($('#status [name="status"]').val() !== undefined) {
                            d.status = $('#status [name="status"]').val();
                        } else {
                            d.status = '';
                        }
                        return d;
                    },
                    'dataSrc': function (json) {
                        if (json.draw === '1') {
                            $("#status").append('<br>Status ' + json.status);
                        }

                        return json.data;
                    }
                },
                //Set column definition initialisation properties.
                'columnDefs': [
                    {
                        'width': '80%',
                        'targets': [0]
                    },
                    {
                        'width': '20%',
                        'targets': [1]
                    },
                    {
                        'className': 'text-center',
                        'targets': [1, 2, 3]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ],
                'rowsGroup': [0, 1]
            });


            table_itens = $('#table_itens').DataTable({
                'processing': true,
                'serverSide': true,
                'lengthChange': false,
                'searching': false,
                'paging': false,
                'order': [[1, 'asc'], [0, 'desc']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('facilities/modelos/ajaxListInspecao'); ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.id = $('#form input[name="id"]').val();
                        d.id_facility_empresa = $('#id_facility_empresa').val();
                        d.id_unidade = $('#id_unidades').val();
                        d.tipo = $('#form .tipo:checked').val();

                        return d;
                    },
                    'dataSrc': function (json) {
                        $('#id_unidades').html($(json.unidades).html());

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'width': '15%',
                        'targets': [0, 1, 2, 3]
                    },
                    {
                        'width': '40%',
                        'targets': [4]
                    },
                    {
                        'className': 'text-center',
                        'orderable': false,
                        'targets': [5]
                    },
                    {
                        'className': 'text-center',
                        'targets': [-1],
                        'orderable': false
                    }
                ],
                'rowsGroup': [0, 1, 2, 3, 5, 4]
            });

        });

        $('#id_facility_empresa, #id_unidades, .tipo').on('change', function () {
            reload_table_itens();
        });

        function add_modelo_vistoria() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#form [name="id"]').val('');
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form [name="id"]').val('');

            $('#modal_form').modal('show');
            $('.combo_nivel1').hide();
        }

        $('#modal_form').on('hidden.bs.modal', function () {
            $('#form')[0].reset();
            reload_table_itens();
        });

        function edit_modelo_vistoria(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': "<?php echo site_url('facilities/modelos/ajaxEdit') ?>",
                'type': "POST",
                'dataType': "json",
                'data': {'id': id},
                'success': function (json) {
                    $.each(json, function (key, value) {
                        if ($('#form [name="' + key + '"]').is(':checkbox')) {
                            $('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value === '1');
                        } else if ($('#form [name="' + key + '"]').is(':radio')) {
                            $('#form [name="' + key + '"][value="' + value + '"]').prop('checked', true);
                        } else {
                            $('#form [name="' + key + '"]').val(value);
                        }
                    });

                    reload_table_itens();

                    $('#modal_form').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }

        function reload_table_itens() {
            table_itens.ajax.reload(null, false); //reload datatable ajax
        }


        function save() {
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('facilities/modelos/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('facilities/modelos/ajaxUpdate') ?>";
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

        function delete_modelo_vistoria(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': "<?php echo site_url('facilities/modelos/ajaxDelete') ?>",
                    'type': "POST",
                    'dataType': "json",
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

        function copiar_modelo_vistoria(id) {
            $(this).prop('disabled', true).html('<i class="glyphicon glyphicon-plus"></i> Copiando...');
            if (confirm('Deseja copiar o plano de vistoria?')) {
                $.ajax({
                    'url': "<?php echo site_url('facilities/modelos/copiar') ?>",
                    'type': "POST",
                    'dataType': "json",
                    'data': {'id': id},
                    'success': function () {
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                        $(this).prop('disabled', false).html('<i class="glyphicon glyphicon-plus"></i> Copiar plano');
                    }
                });
            }
        }

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>