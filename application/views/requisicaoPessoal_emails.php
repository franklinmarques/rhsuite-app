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
                        <li class="active">Gerenciar e-mails de apoio</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_email()"><i class="glyphicon glyphicon-plus"></i>
                        Cadastrar e-mail
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-condensed" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>E-mail</th>
                            <th>Colaborador(a)</th>
                            <th nowrap>Tipo colaborador(a)</th>
                            <th nowrap>Tipo e-mail</th>
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
                            <h3 class="modal-title">Formulario de e-mail de apoio</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert_form"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">E-mail</label>
                                        <div class="col-md-9">
                                            <input name="email" class="form-control" type="text" maxlength="255"
                                                   placeholder="Digite um endereço de e-mail">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Tipo e-mail</label>
                                        <div class="col-md-9">
                                            <select name="tipo_email" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="1">Nova Requisição de Pessoal</option>
                                                <option value="2">Solicitação de agendamento Exame Médico</option>
                                                <option value="3">Nova requisição + Solicitação de agendamento</option>
                                                <option value="4">Administrador</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Colaborador(a)</label>
                                        <div class="col-md-9">
                                            <input name="colaborador" class="form-control" type="text" maxlength="255"
                                                   placeholder="Digite o nome do colaborador">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Tipo colaborador(a)</label>
                                        <div class="col-md-6">
                                            <select name="tipo_usuario" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="4">Administrador</option>
                                                <option value="1">Selecionador</option>
                                                <option value="2">Departamento de Pessoal</option>
                                                <option value="3">Gestão de Pessoas</option>
                                            </select>
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
            document.title = 'CORPORATE RH - LMS - Gerenciar E-mails de Apoio';
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
                    'url': '<?php echo site_url('requisicaoPessoal_emails/ajaxList/') ?>',
                    'type': 'POST'
                },
                'columnDefs': [
                    {
                        'width': '50%',
                        'targets': [0, 1]
                    },
                    {
                        'className': 'text-center text-nowrap',
                        'targets': [2, 3]
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

        function add_email() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#alert_form').html('');
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar e-mail de apoio'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_email(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#alert_form').html('');
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': "<?php echo site_url('requisicaoPessoal_emails/ajaxEdit') ?>",
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

                    $('.modal-title').text('Editar e-mail de apoio'); // Set title to Bootstrap modal title
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
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = '<?php echo site_url('requisicaoPessoal_emails/ajaxAdd') ?>';
            } else {
                url = '<?php echo site_url('requisicaoPessoal_emails/ajaxUpdate') ?>';
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

                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert_form').html('<div class="alert alert-danger">Erro ao salvar os dados</div>').hide().fadeIn('slow');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_email(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('requisicaoPessoal_emails/ajaxDelete') ?>',
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