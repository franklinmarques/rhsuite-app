<?php require_once APPPATH . 'views/header.php'; ?>

    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li class="active">Gerenciar Treinamentos de Clientes</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_cliente();"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar cliente/usuário
                    </button>
                    <br/>
                    <br/>

                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Nome usuário(a)</th>
                            <th>Cliente</th>
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

            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Cadastrar cliente/usuário</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert_form"></div>
                            <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data"
                                  accept-charset="utf-8">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $empresa ?>" name="id_empresa"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Status <span class="text-danger">*</span></label>
                                        <div class="col-md-3">
                                            <select name="status" class="form-control">
                                                <option value="1">Ativo</option>
                                                <option value="0">Inativo</option>
                                            </select>
                                        </div>
                                        <div class="col-md-7 text-right">
                                            <button type="button" class="btn btn-success" id="btnSave" onclick="save()">
                                                Salvar
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Cliente <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input name="cliente" placeholder="Nome do cliente" class="form-control"
                                                   type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Usuário <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input name="nome" placeholder="Nome do usuário" class="form-control"
                                                   type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">E-mail <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input name="email" placeholder="E-mail do usuário" class="form-control"
                                                   type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group" id="senha">
                                        <label class="control-label col-md-2">Senha <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input name="senha" class="form-control" type="password"
                                                   placeholder="Senha do usuário" autocomplete="new-password">
                                            <span class="help-block senha"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group" id="confirmar_senha">
                                        <label class="control-label col-md-2">Confirmar senha <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <input name="confirmar_senha" class="form-control" type="password"
                                                   placeholder="Confirmar a senha do usuário" autocomplete="new-password">
                                            <span class="help-block senha"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Foto</label>
                                        <div class="col-md-10 controls">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail"
                                                     style="width: auto; height: 150px;">
                                                    <img src="https://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem"
                                                         alt="Sem imagem">
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                     style="width: auto; height: 150px;"></div>
                                                <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new btn btn-default"><i
                                                        class="fa fa-plus text-info"></i> Selecionar Imagem</span>
                                            <span class="fileinput-exists btn btn-default"><i
                                                        class="fa fa-undo text-info"></i> Alterar</span>
                                            <input type="file" name="foto" class="default" accept="image/*"/>
                                            <span class="help-block"></span>
                                        </span>
                                                    <a href="#" class="btn btn-default fileinput-exists"
                                                       data-dismiss="fileinput"><i class="fa fa-trash text-danger"></i>
                                                        Remover</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btnSave2" onclick="save()">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>
    <!--main content end-->

    <!-- Css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/js/bootstrap-fileinput/bootstrap-fileinput.css'); ?>">

<?php require_once APPPATH . 'views/end_js.php'; ?>
    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'RhSuite - Corporate RH Tools: Gerenciar Treinamentos de Clientes';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap-fileinput/bootstrap-fileinput.js'); ?>"></script>

    <script>
        var table;
        var save_method;

        $(document).ready(function () {

            table = $('#table').DataTable({
                'dom': "<'row'<'#clientes.col-sm-6'><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                'processing': true,
                'serverSide': true,
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('ead/clientes/ajaxList') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        if ($('#clientes [name="busca_cliente"]').val() !== undefined) {
                            d.cliente = $('#clientes [name="busca_cliente"]').val();
                        } else {
                            d.cliente = '';
                        }

                        return d;
                    },
                    'dataSrc': function (json) {
                        if (json.draw === 1) {
                            $("#clientes").append('<br>Cliente&nbsp;' + json.clientes);
                        }

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'width': '50%',
                        'targets': [0, 1]
                    },
                    {
                        'className': 'text-center',
                        'targets': [2]
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


        function reload_table() {
            table.ajax.reload(null, false);
        }


        function add_cliente() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#alert_form').html('');
            $('#form .form-group').removeClass('has-error');
            $('#form span.help-block').html('');
            $('#senha label span, #confirmar_senha label span').removeClass('text-primary');
            $('#senha label span, #confirmar_senha label span').addClass('text-danger');
            $('#form .senha').html('');
            $('.modal-title').text('Adicionar cliente/usuário');
            $('.fileinput-new img').prop({
                'src': 'https://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem',
                'alt': 'Sem imagem'
            });
            $('#modal_form').modal('show');
        }


        function edit_cliente(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('#form .form-group').removeClass('has-error');
            $('#form span.help-block').html('');

            $.ajax({
                'url': "<?php echo site_url('ead/clientes/ajaxEdit') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id': id
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $.each(json, function (key, value) {
                        if ($('#form input[name="' + key + '"]').prop('type') !== 'file') {
                            $('#form input[name="' + key + '"]').val(value);
                        }
                    });

                    if (json.foto) {
                        $('.fileinput-new img').prop({
                            'src': '<?= base_url('imagens/usuarios'); ?>/' + json.foto,
                            'alt': json.foto
                        });
                    } else {
                        $('.fileinput-new img').prop({
                            'src': 'https://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem',
                            'alt': 'Sem imagem'
                        });
                    }

                    $('#alert_form').html('');
                    $('#senha label span, #confirmar_senha label span').removeClass('text-danger');
                    $('#senha label span, #confirmar_senha label span').addClass('text-primary');
                    $('#form .senha').html('<small><i>Obs.: caso não queira alterar a senha, deixar este campo em branco</i></small>');
                    $('.modal-title').text('Editar cliente/usuário');
                    $('#modal_form').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Erro ao excluir o cliente/usuário');
                }
            });
        }


        function save() {
            $('#form .form-group').removeClass('has-error');
            $('#form span.help-block').html('');
            var url = '<?php echo site_url('ead/clientes/ajaxUpdate') ?>';
            if (save_method === 'add') {
                url = '<?php echo site_url('ead/clientes/ajaxAdd') ?>';
            }

            var form = $('#form')[0];
            var data = new FormData(form);

            $.ajax({
                'url': url,
                'type': 'POST',
                'dataType': 'json',
                'data': data,
                'enctype': 'multipart/form-data',
                'processData': false,
                'contentType': false,
                'cache': false,
                'beforeSend': function () {
                    $('#btnSave, #btnSave2').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else {
                        $('#modal_form').animate({'scrollTop': 0});
                        if (json.msg) {
                            $.each(json.msg, function (key, value) {
                                $('#form input[name="' + key + '"]').parents('div.form-group').addClass('has-error');
                                $('#form input[name="' + key + '"] + span.help-block').html(value);
                            });
                        }
                        if (json.erro) {
                            $('#alert_form').html('<div class="alert alert-danger">' + json.erro + '</div>').hide().fadeIn('slow');
                        }
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert_form').html('<div class="alert alert-warning">Erro ao salvar cliente/usuário</div>').hide().fadeIn('slow');
                },
                'complete': function () {
                    $('#btnSave, #btnSave2').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_cliente(id) {
            if (confirm('Tem certeza que deseja excluir esse cliente/usuário?')) {
                $.ajax({
                    'url': "<?php echo site_url('ead/clientes/ajaxDelete') ?>",
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {
                        'id': id
                    },
                    'success': function (json) {
                        if (json.status) {
                            reload_table();
                        } else if (json.erro) {
                            alert(json.erro);
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Erro ao excluir o cliente/usuário');
                    }
                });
            }
        }

    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>