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
                        <li class="active">Cadastro de Contas Mensais de Facilities</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_despesa()"><i class="glyphicon glyphicon-plus"></i>
                        Cadastrar despesa
                    </button>
                    <a class="btn btn-primary" href="<?= site_url('facilities/itensDespesas/'); ?>">Gerenciar itens de
                        despesas</a>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Unidade</th>
                            <th>Item de Despesa</th>
                            <th class="text-center">Mês/ano</th>
                            <th class="text-center">Consumo</th>
                            <th class="text-center text-nowrap">Valor (R$)</th>
                            <th class="text-center">Vencimento</th>
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
                            <h3 class="modal-title">Formulario de despesa</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_item"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Empresa</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('', $empresas, '', 'id="id_conta_empresa" class="form-control"'); ?>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Unidade</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('', $unidades, '', 'id="id_unidade" class="form-control"'); ?>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Item despesa</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_item', $itens, '', 'id="id_item" class="form-control"'); ?>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Valor</label>
                                        <div class="col-md-4">
                                            <input name="valor" type="text" class="form-control text-right valor">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-2">Vencimento</label>
                                        <div class="col-md-3">
                                            <input name="data_vencimento" type="text" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center date">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Mês</label>
                                        <div class="col-md-4">
                                            <select name="mes" class="form-control">
                                                <option value="01">Janeiro</option>
                                                <option value="02">Fevereiro</option>
                                                <option value="03">Março</option>
                                                <option value="04">Abril</option>
                                                <option value="05">Maio</option>
                                                <option value="06">Junho</option>
                                                <option value="07">Julho</option>
                                                <option value="08">Agosto</option>
                                                <option value="09">Setembro</option>
                                                <option value="10">Outubro</option>
                                                <option value="11">Novembro</option>
                                                <option value="12">Dezembro</option>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-1">Ano</label>
                                        <div class="col-md-2">
                                            <input name="ano" type="text" class="form-control text-center ano">
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
            document.title = 'CORPORATE RH - LMS - Cadastro de Contas Mensais de Facilities';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table;

        $('.date').mask('00/00/0000');
        $('.ano').mask('0000');
        $('.valor').mask('##.###.##0,00', {reverse: true});

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                'processing': true, //Feature control the processing indicator.
                'serverSide': true, //Feature control DataTables' server-side processing mode.
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                // Load data for the table's content from an Ajax source
                'ajax': {
                    'url': '<?php echo site_url('facilities/contasMensais/ajaxList/'); ?>',
                    'type': 'POST'
                },
                //Set column definition initialisation properties.
                'columnDefs': [
                    {
                        'width': '33%',
                        'targets': [0, 1]
                    },
                    {
                        'width': '34%',
                        'targets': [2]
                    },
                    {
                        'className': 'text-center',
                        'targets': [3, 5, 6]
                    },
                    {
                        'className': 'text-center text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ],
                'rowsGroup': [0, 1, 2, 7]
            });

        });


        $('#id_conta_empresa, #id_unidade').on('change', function () {
            atualizar_itens_despesas();
        });

        function add_despesa() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form [name="id"]').val('');
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar despesa'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }


        function atualizar_itens_despesas() {
            $('#id_conta_empresa, #id_unidade, #id_item').prop('disabled', true);

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('facilities/contasMensais/atualizarItensDespesas'); ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_conta_empresa': $('#id_conta_empresa').val(),
                    'id_unidade': $('#id_unidade').val(),
                    'id_item': $('#id_item').val()
                },
                'success': function (json) {
                    $('#id_unidade').html($(json.unidades).html());
                    $('#id_item').html($(json.itens).html());
                },
                'complete': function (jqXHR, textStatus, errorThrown) {
                    $('#id_conta_empresa, #id_unidade, #id_item').prop('disabled', false);
                }
            });
        }


        function edit_despesa(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('facilities/contasMensais/ajaxEdit'); ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $.each(json, function (key, value) {
                        if ($('[name="' + key + '"]').is('select') === false) {
                            $('[name="' + key + '"]').val(value);
                        }
                    });

                    $('#id_conta_empresa').html($(json.empresas).html());
                    $('#id_unidade').html($(json.unidades).html());
                    $('#id_item').html($(json.itens).html());

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar despesa'); // Set title to Bootstrap modal title
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
                url = "<?php echo site_url('facilities/contasMensais/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('facilities/contasMensais/ajaxUpdate') ?>";
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
                },
                'complete': function () {
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }


        function delete_despesa(id) {
            if (confirm('Deseja remover a despesa?')) {
                $.ajax({
                    'url': '<?php echo site_url('facilities/contasMensais/ajaxDelete') ?>',
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
