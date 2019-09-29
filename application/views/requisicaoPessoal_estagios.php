<?php require_once 'header.php'; ?>

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
                        <li class="active">Textos e-mails de apoio</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_estagio()"><i class="glyphicon glyphicon-plus"></i>
                        Cadastrar texto
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-condensed" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Estágio do processo</th>
                            <th>Destino e-mail</th>
                            <th>E-mail responsável</th>
                            <th>Texto padrão</th>
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
                <div class="modal-dialog" style="width: 640px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Texto de e-mail de apoio</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert_form"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Estágio</label>
                                        <div class="col-md-9">
                                            <input name="nome" class="form-control" type="text" maxlength="255">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Destino e-mail</label>
                                        <div class="col-md-9">
                                            <input name="destino_email" class="form-control" type="text"
                                                   maxlength="255">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">E-mail responsável</label>
                                        <div class="col-md-9">
                                            <input name="email_responsavel" class="form-control" type="text"
                                                   maxlength="255">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Texto padrão</label>
                                        <div class="col-md-9">
                                            <textarea name="mensagem" class="form-control" row="10"></textarea>
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

<?php require_once 'end_js.php'; ?>

    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Texto E-mails de Apoio';
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
                'processing': true,
                'serverSide': true,
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('requisicaoPessoal_estagios/ajaxList/') ?>',
                    'type': 'POST'
                },
                'columnDefs': [
                    {
                        'width': '25%',
                        'targets': [0, 1, 2, 3]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [-1],
                        'orderable': false,
                        'searchable': false
                    }
                ]
            });

        });

        function add_estagio() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#alert_form').html('');
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar texto e-mail de apoio'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_estagio(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#alert_form').html('');
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': "<?php echo site_url('requisicaoPessoal_estagios/ajaxEdit') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $.each(json, function (key, value) {
                        if ($('#form [name="' + key + '"]').is(':checkbox') === false) {
                            $('#form [name="' + key + '"]').val(value);
                        } else {
                            $('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value === '1');
                        }
                    });

                    $('.modal-title').text('Editar texto e-mail de apoio'); // Set title to Bootstrap modal title
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

        function save() {
            $('#btnSave').text('Salvando...').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = '<?php echo site_url('requisicaoPessoal_estagios/ajaxAdd') ?>';
            } else {
                url = '<?php echo site_url('requisicaoPessoal_estagios/ajaxUpdate') ?>';
            }

            // ajax adding data to database
            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        $('#alert_form').html('<div class="alert alert-danger">' + json.erro + '</div>').hide().fadeIn('slow');
                    }

                    $('#btnSave').text('Salvar').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert_form').html('<div class="alert alert-danger">Erro ao salvar os dados</div>').hide().fadeIn('slow');
                    $('#btnSave').text('Salvar').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_estagio(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('requisicaoPessoal_estagios/ajaxDelete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function () {
                        $('#modal_form').modal('hide');
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Erro, tente novamente!');
                    }
                });

            }
        }

    </script>

<?php require_once 'end_html.php'; ?>