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
                        <li class="active">Gerenciar Fornecedores/Prestadores de Serviço</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_fornecedor_prestador()"><i
                                class="glyphicon glyphicon-plus"></i>
                        Novo fornecedor/prestador
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-striped table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th nowrap>Nome fornecedor(a)/prestador(a)</th>
                            <th>Tipo</th>
                            <th>Vínculo</th>
                            <th nowrap>Pessoa de contato</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
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
                            <h3 class="modal-title">Gerenciar fornecedor/prestador</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                <input type="hidden" name="id" value="">
                                <input type="hidden" name="id_empresa" value="<?= $idEmpresa; ?>">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Nome</label>
                                        <div class="col-md-9">
                                            <input name="nome" type="text" class="form-control"
                                                   placeholder="Digite o nome do fornecedor/prestador">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Tipo</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('tipo', $tipos, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Vínculo</label>
                                        <div class="col-md-9">
                                            <textarea name="vinculo" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Contato</label>
                                        <div class="col-md-9">
                                            <input name="pessoa_contato" type="text" class="form-control"
                                                   placeholder="Pessoa de contato">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Telefone</label>
                                        <div class="col-md-9">
                                            <input name="telefone" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">E-mail</label>
                                        <div class="col-md-9">
                                            <input name="email" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Status</label>
                                        <div class="col-md-9">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="status" type="checkbox" value="1"> Ativo
                                                </label>
                                            </div>
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
            document.title = 'CORPORATE RH - LMS - Gerenciar Fornecedores/Prestadores de Serviço';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

    <script>

        var save_method; //for save method string
        var table;


        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                'dom': "<'row'<'.col-sm-3'l><'#status.col-sm-2'><'#tipo.col-sm-4'><'col-sm-3'f>>" +
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
                    'url': '<?php echo site_url('facilities/fornecedoresPrestadores/ajaxList/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        if ($('#status [name="busca_status"]').val() !== undefined) {
                            d.status = $('#status [name="busca_status"]').val();
                        } else {
                            d.status = '';
                        }
                        if ($('#tipo [name="busca_tipo"]').val() !== undefined) {
                            d.tipo = $('#tipo [name="busca_tipo"]').val();
                        } else {
                            d.tipo = '';
                        }

                        return d;
                    },
                    'dataSrc': function (json) {
                        if (json.draw === 1) {
                            $("#status").append('<br>Status&nbsp;' + json.status);
                            $('#tipo').append('<br>Tipo&nbsp;' + json.tipo);
                        }

                        return json.data;
                    }
                },
                //Set column definition initialisation properties.
                'columnDefs': [
                    {
                        'width': '20%',
                        'targets': [0]
                    },
                    {
                        'width': '16%',
                        'targets': [1, 2, 3, 4, 5]
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


        function add_fornecedor_prestador() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            $('#modal_form').modal('show');
            $('.modal-title').text('Novo fornecedor/prestador de serviço'); // Set title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_fornecedor_prestador(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('facilities/fornecedoresPrestadores/ajaxEdit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id': id
                },
                'success': function (json) {
                    $.each(json.input, function (key, value) {
                        $('#' + key).html($(value).html());
                    });

                    $.each(json.data, function (key, value) {
                        if ($('#form [name="' + key + '"]').is(':checkbox') === false) {
                            $('#form [name="' + key + '"]').val(value);
                        } else {
                            $('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value === '1');
                        }
                    });

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar fornecedor/prestador de serviço'); // Set title to Bootstrap modal title
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
                url = "<?php echo site_url('facilities/fornecedoresPrestadores/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('facilities/fornecedoresPrestadores/ajaxUpdate') ?>";
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


        function delete_fornecedor_prestador(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('facilities/fornecedoresPrestadores/ajaxDelete') ?>',
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