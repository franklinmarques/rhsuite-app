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

        .table > tbody > tr > td.colaborador-success,
        .table > tbody > tr > td.date-width-success {
            color: #fff;
            background-color: #5cb85c !important;
        }

        .table > tbody > tr > td.colaborador-success:hover,
        .table > tbody > tr > td.date-width-success:hover {
            background-color: #47a447 !important;
        }

        .table > tbody > tr > td.colaborador-primary,
        .table > tbody > tr > td.date-width-primary {
            color: #fff;
            background-color: #027EEA !important;
        }

        .table > tbody > tr > td.colaborador-primary:hover,
        .table > tbody > tr > td.date-width-primary:hover {
            background-color: #007EEB;
        }

        .table > tbody > tr > td.colaborador-disabled,
        .table > tbody > tr > td.date-width-disabled {
            color: #fff;
            background-color: #5C679A !important;
        }

        .table > tbody > tr > td.colaborador-disabled:hover,
        .table > tbody > tr > td.date-width-disabled:hover {
            background-color: #576192;
        }

        .table > tbody > tr > td.date-width-warning {
            /*color: #fff;*/
            background-color: #f0ad4e !important;
        }

        .table > tbody > tr > td.date-width-warning:hover {
            background-color: #ed9c28 !important;
        }

        .table > tbody > tr > td.date-width-danger {
            color: #fff;
            background-color: #d9534f !important;
        }

        .table > tbody > tr > td.date-width-danger:hover {
            background-color: #d2322d !important;
        }

        .table > tbody > tr > td.date-width-disabled {
            color: #fff;
            background-color: #8866bb !important;
        }

        .table > tbody > tr > td.date-width-disabled:hover {
            background-color: #7253b0 !important;
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
                        <li class="active">Gerenciamento de vagas</li>
                    </ol>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-info" onclick="add_vaga();"><i class="glyphicon glyphicon-plus"></i>
                                Cadastrar nova vaga
                            </button>
                        </div>
                    </div>
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Código</th>
                                <th>Status</th>
                                <th>Data abertura</th>
                                <th>Cargo/Função</th>
                                <th>Qtde. vagas</th>
                                <th>Cidade</th>
                                <th>Bairro</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- page end-->

            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar vaga</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Código da vaga</label>
                                        <div class="col-md-2">
                                            <input name="codigo" class="form-control" type="text" readonly>
                                        </div>
                                        <label class="control-label col-md-1">Status</label>
                                        <div class="col-md-2">
                                            <select name="status" class="form-control">
                                                <option value="1">Aberta</option>
                                                <option value="0">Fechada</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5 text-right">
                                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
                                                Salvar
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Cargo <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_cargo', $cargos, '', 'id="cargo" class="form-control" onchange="atualizar_funcoes(this.value);"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Função <span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_funcao', $funcoes, '', 'id="funcao" class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Formação mínima</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('formacao_minima', $escolaridades, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Formação específica mínima</label>
                                        <div class="col-md-9">
                                            <textarea name="formacao_especifica_minima" class="form-control"
                                                      rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Perfil profissional desejado</label>
                                        <div class="col-md-9">
                                            <textarea name="perfil_profissional_desejado" class="form-control"
                                                      rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Qtde. vagas <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-2">
                                            <input name="quantidade" class="form-control valor" type="text">
                                        </div>
                                        <label class="control-label col-md-2">Estado da vaga</label>
                                        <div class="col-md-3">
                                            <?php echo form_dropdown('estado_vaga', $estados, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Cidade da vaga</label>
                                        <div class="col-md-9">
                                            <input name="cidade_vaga" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Bairro da vaga</label>
                                        <div class="col-md-9">
                                            <input name="bairro_vaga" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Tipo de vínculo <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-4">
                                            <select name="tipo_vinculo" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="1">CLT</option>
                                                <option value="2">Prestador de serviço (PJ, MEI)</option>
                                                <option value="3">Autônomo</option>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-2">Remuneração<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input name="remuneracao" class="form-control valor" type="text"
                                                       maxlength="255">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Benefícios</label>
                                        <div class="col-md-9">
                                            <textarea name="beneficios" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Horário de trabalho</label>
                                        <div class="col-md-9">
                                            <textarea name="horario_trabalho" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Contato do selecionador</label>
                                        <div class="col-md-9">
                                            <textarea name="contato_selecionador" class="form-control"
                                                      rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave2" onclick="save()" class="btn btn-success">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>
    <!--main content end-->

<?php require_once 'end_js.php'; ?>

    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciamento de Vagas';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>
    <script>

        var table;
        var save_method;

        $('.valor').mask('##.###.##0,00', {reverse: true});

        $(document).ready(function () {


            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('gestaoDeVagas/ajaxList/') ?>',
                    'type': 'POST'
                },
                'columnDefs': [
                    {
                        'className': 'text-center',
                        'targets': [1, 2, 4]
                    },
                    {
                        'width': '50%',
                        'targets': [5, 6]
                    },
                    {
                        'className': 'text-center text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ]
            });

        });


        function reload_table() {
            table.ajax.reload(null, false);
        }


        function add_vaga() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('gestaoDeVagas/ajaxNova/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'success': function (json) {

                    $('#modal_form [name="codigo"]').val(json.codigo);
                    $('#cargo').html($(json.cargos).html());
                    $('#funcao').html($(json.funcoes).html());

                    $('.modal-title').text('Cadastrar nova vaga');
                    $('#modal_form').modal('show');

                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_vaga(codigo) {
            save_method = 'update';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('gestaoDeVagas/ajaxEdit/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'codigo': codigo},
                'success': function (json) {

                    $('#cargo').html($(json.input.cargos).html());
                    $('#funcao').html($(json.input.funcoes).html());

                    $.each(json.data, function (key, value) {
                        $('#modal_form [name="' + key + '"]').val(value);
                    });

                    $('.modal-title').text('Editar vaga');
                    $('#modal_form').modal('show');

                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function atualizar_funcoes(id_cargo) {
            $('#funcao').prop('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('gestaoDeVagas/atualizarFuncoes/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id_cargo': id_cargo},
                'success': function (json) {
                    $('#funcao').html($(json.funcoes).html()).prop('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#funcao').prop('disabled', false);
                }
            });
        }


        function save() {
            $('#btnSave, btnSave2').text('Salvando...').attr('disabled', true);
            var url;

            if (save_method === 'add') {
                url = '<?php echo site_url('gestaoDeVagas/ajaxAdd') ?>';
            } else {
                url = '<?php echo site_url('gestaoDeVagas/ajaxUpdate') ?>';
            }

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
                        alert(json.erro);
                    }

                    $('#btnSave, btnSave2').text('Salvar').attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave, btnSave2').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_vaga(codigo) {
            if (confirm('Deseja remover a vaga?')) {
                $.ajax({
                    'url': '<?php echo site_url('gestaoDeVagas/ajaxDelete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'codigo': codigo},
                    'success': function (json) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }


    </script>

<?php require_once 'end_html.php'; ?>