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
                        <li class="active">Gestão Comercial - Gerenciar Contratos</li>
                    </ol>
                    <form action="#" id="estrutura" class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Filtrar por departamento</label>
                                <?php echo form_dropdown('id_depto', $deptos, $depto_atual, 'onchange="filtrar_estrutura()" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label>Filtrar por área</label>
                                <?php echo form_dropdown('id_area', $areas, $area_atual, 'onchange="filtrar_estrutura();" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label>Filtrar por setor</label>
                                <?php echo form_dropdown('id_setor', $setores, $setor_atual, 'onchange="filtrar_estrutura();" class="form-control input-sm"'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Filtrar por cliente</label>
                                <?php echo form_dropdown('id_cliente', ['' => 'Todos'], '', 'onchange="filtrar_estrutura();" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-6">
                                <label>Filtrar por proposta</label>
                                <?php echo form_dropdown('codigo_proposta', ['' => 'Todas'], '', 'onchange="reload_table();" class="form-control input-sm"'); ?>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <button id="btnAdd" type="button" class="btn btn-info" onclick="add_contrato()" autocomplete="off">
                        <i class="glyphicon glyphicon-plus"></i> Novo contrato
                    </button>
                    <a id="pdf" class="btn btn-primary disabled" href="#" target="_blank"><i
                                class="glyphicon glyphicon-print"></i> Imprimir
                    </a>
                    <br>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Cód. contrato</th>
                            <th>Cód. proposta</th>
                            <th>Cliente</th>
                            <th nowrap>Centro de custo</th>
                            <th nowrap>Data vencimento</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- page end-->

                <!-- Bootstrap modal -->
                <div class="modal fade" id="modal_form" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h3 class="modal-title">Gerenciar contrato</h3>
                            </div>
                            <div class="modal-body form">

                                <ul id="abas" class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#contrato_cadastro" aria-controls="contrato_cadastro" role="tab"
                                           data-toggle="tab">Gerenciar contrato</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#contrato_visualizacao" aria-controls="contrato_visualizacao"
                                           role="tab" data-toggle="tab">Visualizar arquivo</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="contrato_cadastro">
                                        <div id="alert"></div>
                                        <form action="#" id="form" enctype="multipart/form-data"
                                              class="form-horizontal">
                                            <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                                            <div class="form-body">
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">Cód. contrato</label>
                                                    <div class="col-md-3">
                                                        <input name="codigo" class="form-control" type="text" readonly>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <label class="control-label col-md-1">Status</label>
                                                    <div class="col-md-2">
                                                        <?php echo form_dropdown('status_ativo', $statusAtivo, '', 'class="form-control"'); ?>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <button type="button" class="btn btn-success" id="btnSave"
                                                                onclick="save()">Salvar
                                                        </button>
                                                        <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">
                                                            Cancelar
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">Departamento</label>
                                                    <div class="col-md-9">
                                                        <?php echo form_dropdown('id_depto', $deptos, '', 'id="id_depto" class="form-control estrutura" onchange="montar_estrutura();"'); ?>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">Área</label>
                                                    <div class="col-md-9">
                                                        <?php echo form_dropdown('id_area', $areas, '', 'id="id_area" class="form-control estrutura" onchange="montar_estrutura();"'); ?>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">Setor</label>
                                                    <div class="col-md-9">
                                                        <?php echo form_dropdown('id_setor', $setores, '', 'id="id_setor" class="form-control estrutura" onchange="montar_estrutura();"'); ?>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">Cliente</label>
                                                    <div class="col-md-4">
                                                        <?php echo form_dropdown('id_cliente', ['' => 'selecione...'], '', 'id="id_cliente" class="form-control estrutura" onchange="montar_estrutura();"'); ?>
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <label class="control-label col-md-2">Cód. proposta</label>
                                                    <div class="col-md-3">
                                                        <?php echo form_dropdown('codigo_proposta', ['' => 'selecione...'], '', 'id="codigo_proposta" class="form-control estrutura"'); ?>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">Centro de custo</label>
                                                    <div class="col-md-5">
                                                        <input name="centro_custo" type="text" class="form-control">
                                                        <span class="help-block"></span>
                                                    </div>
                                                    <label class="control-label col-md-2 text-nowrap">Vencimento
                                                        contrato</label>
                                                    <div class="col-md-2">
                                                        <input name="data_vencimento" type="text"
                                                               class="form-control text-center date"
                                                               placeholder="dd/mm/aaaa">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="control-label col-md-2">Anexar contrato</label>
                                                    <div class="col-md-9">
                                                        <div id="arquivo" class="fileinput input-group"
                                                             data-provides="fileinput">
                                                            <div class="form-control" data-trigger="fileinput">
                                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                                <span class="fileinput-preview fileinput-filename"></span>
                                                            </div>
                                                            <div class="input-group-addon btn btn-default btn-file"
                                                                 name="buu">
                                                                <span class="fileinput-new">Selecionar arquivo</span>
                                                                <span class="fileinput-exists">Alterar</span>
                                                                <input type="file" accept=".pdf" name="arquivo"/>
                                                            </div>
                                                            <a href="#" data-dismiss="fileinput"
                                                               class="input-group-addon btn btn-default fileinput-exists">Limpar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="contrato_visualizacao">
                                        <div class="form-body">
                                            <div class="row form-horizontal">
                                                <label class="control-label col-md-3">Selecionar documento</label>
                                                <div class="col-md-5">
                                                    <select id="contratos" class="form-control">
                                                        <option value="">selecione...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <button type="button" class="btn btn-info" id="btnDownloadContrato"
                                                            onclick="download_arquivo();"><i
                                                                class="fa fa-download"></i>Download
                                                    </button>
                                                    <button type="button" class="btn btn-danger" id="btnDeleteContrato"
                                                            onclick="delete_arquivo();">Excluir
                                                    </button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                                        Fechar
                                                    </button>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <iframe id="documento"
                                                            src="https://docs.google.com/gview?embedded=true&url=<?= base_url('arquivos/icom/contratos/'); ?>"
                                                            style="width:100%; height:600px; margin:0;"
                                                            frameborder="0"></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <!-- End Bootstrap modal -->

        </section>
    </section>
    <!--main content end-->

<?php require_once APPPATH . 'views/end_js.php'; ?>

    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gestão Comercial: Gerenciar Contratos';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table;

        $('.valor').mask('#.###.##0,00', {reverse: true});
        $('.date').mask('00/00/0000');


        $(document).ready(function () {

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'order': [],
                'ajax': {
                    'url': '<?php echo site_url('icom/contratos/listar/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#estrutura').serialize();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '25%',
                        'targets': [0, 1]
                    },
                    {
                        'width': '50%',
                        'targets': [2]
                    },
                    {
                        'className': 'text-center',
                        'targets': [4]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [-1],
                        'orderable': false,
                        'searchable': false
                    }
                ],
                'preDrawCallback': function () {
                    var id_setor = $('#estrutura [name="id_setor"]').val();
                    if (id_setor.length > 0) {
                        $('#pdf').prop('href', '<?= site_url('icom/contratos/relatorio'); ?>/q?setor=' + id_setor).removeClass('disabled');
                    } else {
                        $('#pdf').prop('href', '#').addClass('disabled');
                    }
                }
            });

        });


        $('#abas li a').on('click', function (e) {
            if ($(this).parent().hasClass('disabled')) {
                return false;
                console.log(1);
            } else {
                console.log(0);
            }
        });


        $('#contratos').on('change', function () {
            $('#btnDeleteContrato').prop('disabled', this.value.length === 0);
        });


        function filtrar_estrutura() {
            var data = $('#estrutura').serialize();
            $.ajax({
                'url': '<?php echo site_url('icom/contratos/filtrarEstrutura') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': data,
                'beforeSend': function () {
                    $('#estrutura select').prop('disabled', true);
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        $('#estrutura [name="id_area"]').html(json.areas);
                        $('#estrutura [name="id_setor"]').html(json.setores);
                        $('#estrutura [name="id_cliente"]').html(json.clientes);
                        $('#estrutura [name="codigo_proposta"]').html(json.propostas);

                        $('#estrutura select').prop('disabled', false);
                        reload_table();
                    }
                },
                'complete': function () {
                    $('#estrutura select').prop('disabled', false);
                }
            });
        }


        function add_contrato() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form [name="id"]').val('');
            $('#id_depto option[value=""]').text('selecione...');
            $('#id_area, #id_setor, #id_cliente').html('<option value="">selecione...</option>');
            $('#arquivo').removeClass('fileinput-exists').addClass('fileinput-new')
                .fileinput({'name': ''}).find('[type="hidden"]').val('');
            $('#abas li:eq(1), #contrato_visualizacao').removeClass('active');
            $('#abas li:eq(1)').addClass('disabled');
            $('#abas li:eq(0), #contrato_cadastro').addClass('active');
            $('.modal-title').text('Adicionar contrato');
            $('.combo_nivel1').hide();
            $('#modal_form').modal('show');
        }


        function edit_contrato(id) {
            $.ajax({
                'url': '<?php echo site_url('icom/contratos/editar') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'beforeSend': function () {
                    save_method = 'update';
                    $('#abas li:eq(1), #contrato_visualizacao').removeClass('active disabled');
                    $('#abas li:eq(0), #contrato_cadastro').addClass('active');
                    $('#form')[0].reset();
                    $('.form-group').removeClass('has-error');
                    $('.help-block').empty();
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $('#id_depto').html($(json.deptos).html());
                    $('#id_area').html($(json.areas).html());
                    $('#id_setor').html($(json.setores).html());
                    $('#id_cliente').html($(json.clientes).html());
                    $('#codigo_proposta').html($(json.propostas).html());

                    $('#form [name="id_cliente"]').html(json.clientes);
                    $('#form [name="codigo_proposta"]').html(json.propostas);

                    $.each(json, function (key, value) {
                        if ($('#form [name="' + key + '"]').is(':file') === false) {
                            $('#form [name="' + key + '"]').val(value);
                        }
                    });

                    if (json.arquivo) {
                        $('#arquivo').removeClass('fileinput-new').addClass('fileinput-exists')
                            .fileinput({'name': 'arquivo'}).find('[type="hidden"]').val(json.arquivo);
                        $('#arquivo .fileinput-preview').html(json.arquivo);
                    } else {
                        $('#arquivo').removeClass('fileinput-exists').addClass('fileinput-new')
                            .fileinput({'name': 'arquivo'}).find('[type="hidden"]').val('');
                        $('#arquivo .fileinput-preview').html('');
                    }

                    $('#contratos').html($(json.contratos).html()).trigger('change');
                    $('#documento').attr('src', 'https://docs.google.com/gview?embedded=true&url=' + json.url_arquivo);

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar contrato');
                }
            });
        }


        function montar_estrutura() {
            $.ajax({
                'url': '<?php echo site_url('icom/contratos/montarEstrutura') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('.estrutura').serialize(),
                'beforeSend': function () {
                    $('.estrutura, #btnSave').prop('disabled', true);
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        $('#id_area').html(json.areas);
                        $('#id_setor').html(json.setores);
                        $('#id_cliente').html(json.clientes);
                        $('#codigo_proposta').html(json.propostas);
                    }
                },
                'complete': function () {
                    $('.estrutura, #btnSave').prop('disabled', false);
                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false);
        }


        function save() {
            $.ajax({
                'url': '<?php echo site_url('icom/contratos/salvar') ?>',
                'type': 'POST',
                'data': new FormData($('#form')[0]),
                'dataType': 'json',
                'enctype': 'multipart/form-data',
                'processData': false,
                'contentType': false,
                'cache': false,
                'beforeSend': function () {
                    $('#btnSave').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'complete': function () {
                    $('#btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_contrato(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('icom/contratos/excluir') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        if (json.status) {
                            reload_table();
                        } else if (json.erro) {
                            alert(json.erro);
                        }
                    }
                });
            }
        }

        function delete_arquivo() {
            if (confirm('Tem certeza que deseja excluir o arquivo?')) {
                $.ajax({
                    'url': '<?php echo site_url('icom/contratos/excluirArquivo'); ?>',
                    'type': 'POST',
                    'data': {
                        'arquivo': $('#contratos').val()
                    },
                    'dataType': 'json',
                    'beforeSend': function () {
                        $('#btnDeleteContrato').text('Excluindo...');
                        $('#btnDownloadContrato, #btnDeleteContrato').attr('disabled', true);
                    },
                    'success': function (json) {
                        if (json.erro) {
                            alert(json.erro);
                        } else if (json.status) {
                            $('#modal_form').modal('hide');
                        }
                    },
                    'complete': function () {
                        $('#btnDeleteContrato').text('Excluir');
                        $('#btnDownloadContrato, #btnDeleteContrato').attr('disabled', false);
                    }
                });
            }
        }


    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>
