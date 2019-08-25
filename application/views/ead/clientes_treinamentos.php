<?php require_once APPPATH . 'views/header.php'; ?>

    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('ead/clientes'); ?>">Gerenciar Clientes</a></li>
                        <li class="active">Gerenciar Treinamentos de Clientes</li>
                    </ol>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <button class="btn btn-info" onclick="add_treinamento();"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar treinamento
                    </button>
                    <!--                    <button id="email" class="btn btn-warning" onclick="enviar_email()"-->
                    <!--                            title="Enviar e-mail de convocação"><i class="glyphicon glyphicon-envelope"></i>Enviar-->
                    <!--                        e-mail de convocação-->
                    <!--                    </button>-->
                    <br/>
                    <br/>

                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Nome treinamento</th>
                            <th nowrap>Data início</th>
                            <th nowrap>Data término</th>
                            <th nowrap>Avaliação final</th>
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
                                        aria-hidden="true">&times;</span>
                            </button>
                            <h3 class="modal-title">Formulario de Treinamento</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert_form"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $idCliente; ?>" name="id_usuario"/>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Tipo treinamento</label>
                                        <div class="col-md-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="tipo_treinamento" value="P" checked="">
                                                Presencial
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="tipo_treinamento" value="E"> EAD
                                            </label>
                                        </div>
                                        <label class="control-label col-md-2">Local</label>
                                        <div class="col-md-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="local_treinamento" value="I"
                                                       class="input_presencial">
                                                Interno
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="local_treinamento" value="E"
                                                       class="input_presencial">
                                                Externo
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2 text-nowrap">Nome treinamento <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-sm-10 controls">
                                            <div class="presencial">
                                                <input name="nome" placeholder="Nome de treinamento presencial"
                                                       class="form-control"
                                                       type="text">
                                            </div>
                                            <div class="ead" style="display: none;">
                                                <?php echo form_dropdown('id_curso', array('' => 'selecione...'), '', 'class="form-control"'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Carga horária</label>
                                        <div class="col-md-2">
                                            <input class="hora form-control text-center hora" placeholder="hh:mm"
                                                   name="carga_horaria_presencial" type="text">
                                        </div>
                                        <label class="control-label col-md-2">Período realização</label>
                                        <div class="col-md-5">
                                            <div class="form-inline form-group" style="padding-left: 15px;">
                                                <label for="data_inicio" style="font-weight: normal"> De </label>
                                                <input class="data form-control text-center data" name="data_inicio"
                                                       placeholder="dd/mm/aaaa" value="" style="width: 150px;"
                                                       type="text">
                                                <label for="data_maxima" style="font-weight: normal"> até </label>
                                                <input class="data form-control text-center data" name="data_maxima"
                                                       placeholder="dd/mm/aaaa" value="" style="width: 150px;"
                                                       type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Avaliação final</label>
                                        <div class="col-md-2">
                                            <input class="data form-control text-center input_presencial"
                                                   name="avaliacao_presencial"
                                                   type="number"
                                                   value="" min="0" max="100">
                                        </div>
                                        <label class="control-label col-md-4">Nota mínima para emitir
                                            certificado</label>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <input name="nota_aprovacao" id="nota_aprovacao" value="" size="3"
                                                       min="0" max="100"
                                                       class="form-control text-right" type="number">
                                                <span class="input-group-addon">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Fornecedor/palestrante</label>
                                        <div class="col-sm-10 controls">
                                            <input name="nome_fornecedor" placeholder="Nome do fornecedor"
                                                   class="form-control input_presencial" type="text">
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

    <!-- Css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>">

<?php require_once APPPATH . 'views/end_js.php'; ?>
    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'RhSuite - Corporate RH Tools: Gerenciar Treinamentos de Clientes';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>
        var table;
        var save_method;

        $(document).ready(function () {

            $('.data').mask('00/00/0000');
            $('.hora').mask('#00:00', {
                'translation': {
                    '#': {
                        'pattern': /\d/,
                        'optional': true
                    }
                },
                'reverse': true
            });

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('ead/clientes_treinamentos/ajaxList') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.id_cliente = '<?= $idCliente; ?>';

                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '100%',
                        'targets': [0]
                    },
                    {
                        'className': 'text-center',
                        'targets': [1, 2, 3]
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


        $('input[name="tipo_treinamento"]').on('change', function () {
            if (this.value === 'P') {
                $('.ead').hide();
                $('.presencial').show();
                $('.input_presencial').prop('disabled', false);
            } else if (this.value === 'E') {
                $('.presencial').hide();
                $('.ead').show();
                $('.input_presencial').prop('disabled', true);
            }
        });


        function reload_table() {
            table.ajax.reload(null, false);
        }


        function add_treinamento() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#alert_form').html('');
            $('#form .form-group').removeClass('has-error');
            $('#form span.help-block').html('');

            $.ajax({
                'url': '<?php echo site_url('ead/clientes_treinamentos/ajaxEdit'); ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_usuario': '<?= $idCliente; ?>'
                },
                'success': function (json) {
                    $('#form [name="tipo_treinamento"][value="P"]').prop('checked', true).trigger('change');
                    $('#form [name="local_treinamento"][value="I"]').prop('checked', true);
                    $('#form [name="id_curso"]').html($(json.cursos).html());

                    $('#modal_form').modal('show');
                    $('#main-content .modal-title').text('Adicionar Treinamento');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_treinamento(id) {
            save_method = 'update';
            $('#alert_form').html('');
            $('#form .form-group').removeClass('has-error');
            $('#form span.help-block').html('');

            $.ajax({
                'url': '<?php echo site_url('ead/clientes_treinamentos/ajaxEdit'); ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_usuario': '<?= $idCliente; ?>',
                    'id': id
                },
                'success': function (json) {
                    if (json.tipo_treinamento !== null) {
                        if (json.tipo_treinamento.length > 0) {
                            $('#form [name="tipo_treinamento"][value="' + json.tipo_treinamento + '"]').prop('checked', true).trigger('change');
                        }
                    }

                    $('#form [name="id"]').val(json.id);
                    $('#form [name="nome"]').val(json.nome);
                    $('#form [name="id_curso"]').html($(json.cursos).html());
                    $('#form [name="data_inicio"]').val(json.data_inicio);
                    $('#form [name="data_maxima"]').val(json.data_maxima);
                    $('#form [name="nota_aprovacao"]').val(json.nota_aprovacao);
                    $('#form [name="carga_horaria_presencial"]').val(json.carga_horaria_presencial);
                    $('#form [name="avaliacao_presencial"]').val(json.avaliacao_presencial);
                    $('#form [name="nome_fornecedor"]').val(json.nome_fornecedor);

                    $('#modal_form').modal('show');
                    $('#main-content .modal-title').text('Editar Treinamento');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Erro ao excluir o treinamento');
                }
            });
        }


        function save() {
            $('#form .form-group').removeClass('has-error');
            $('#form span.help-block').html('');
            var url = '<?php echo site_url('ead/clientes_treinamentos/ajaxUpdate') ?>';
            if (save_method === 'add') {
                url = '<?php echo site_url('ead/clientes_treinamentos/ajaxAdd') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form').serialize(),
                'beforeSend': function () {
                    $('#btnSave').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else {
                        $('#modal_form').animate({scrollTop: 0});
                        if (json.msg) {
                            $.each(json.msg, function (key, value) {
                                $('#form input[name="' + key + '"]').parent('div').parent('div.form-group').addClass('has-error');
                                $('#form input[name="' + key + '"] + span.help-block').html(value);
                            });
                        }
                        if (json.erro) {
                            $('#alert_form').html('<div class="alert alert-danger">' + json.erro + '</div>').hide().fadeIn('slow');
                        }
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert_form').html('<div class="alert alert-danger">Erro ao salvar treinamento</div>').hide().fadeIn('slow');
                },
                'complete': function () {
                    $('#btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_treinamento(id) {
            if (confirm('Tem certeza que deseja excluir esse treinamento?')) {
                $.ajax({
                    'url': '<?php echo site_url('ead/clientes_treinamentos/ajaxDelete') ?>',
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
                        alert('Erro ao excluir o treinamento');
                    }
                });
            }
        }

    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>