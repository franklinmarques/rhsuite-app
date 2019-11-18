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
                        <li class="active">Gerenciar Propostas Técnico-Comerciais</li>
                    </ol>
                    <form action="#" id="estrutura" class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Filtrar por departamento</label>
                                <?php echo form_dropdown('id_depto', $deptos, $depto_atual, 'onchange="filtrar_estrutura()" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-6">
                                <label>Filtrar por área</label>
                                <?php echo form_dropdown('id_area', $areas, $area_atual, 'onchange="filtrar_estrutura();" class="form-control input-sm"'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Filtrar por setor</label>
                                <?php echo form_dropdown('id_setor', $setores, $setor_atual, 'onchange="filtrar_estrutura();"class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-6">
                                <label>Filtrar por cliente</label>
                                <?php echo form_dropdown('id_cliente', ['' => 'Todos'], '', 'onchange="reload_table();"class="form-control input-sm"'); ?>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <button id="btnAdd" type="button" class="btn btn-info" onclick="add_proposta()" autocomplete="off">
                        <i class="glyphicon glyphicon-plus"></i> Nova proposta
                    </button>
                    <a id="pdf" class="btn btn-primary disabled" href="#" target="_blank"><i
                                class="glyphicon glyphicon-print"></i> Imprimir
                    </a>
                    <br>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Cód proposta</th>
                            <th>Status</th>
                            <th>Descrição proposta</th>
                            <th>Cliente</th>
                            <th nowrap>Data entrega</th>
                            <th nowrap>Valor (R$)</th>
                            <th nowrap>Margem<br>líquida (R$)</th>
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
                                <h3 class="modal-title">Gerenciar proposta</h3>
                            </div>
                            <div class="modal-body form">
                                <div id="alert"></div>
                                <form action="#" id="form" enctype="multipart/form-data" class="form-horizontal">
                                    <div class="form-body">
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Códígo proposta</label>
                                            <div class="col-md-3">
                                                <input name="codigo" class="form-control" type="text" readonly>
                                                <span class="help-block"></span>
                                            </div>
                                            <label class="control-label col-md-1">Status</label>
                                            <div class="col-md-2">
                                                <?php echo form_dropdown('status', $status, '', 'class="form-control"'); ?>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <button type="button" class="btn btn-success" id="btnSave"
                                                        onclick="save()">Salvar
                                                </button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    Cancelar
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Descrição proposta</label>
                                            <div class="col-md-9">
                                                <input name="descricao" class="form-control" type="text">
                                                <span class="help-block"></span>
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
                                            <div class="col-md-9">
                                                <?php echo form_dropdown('id_cliente', ['' => 'selecione...'], '', 'id="id_cliente" class="form-control estrutura"'); ?>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2 text-nowrap">Data entrega
                                                proposta</label>
                                            <div class="col-md-2">
                                                <input name="data_entrega" type="text"
                                                       class="form-control text-center date" placeholder="dd/mm/aaaa">
                                                <span class="help-block"></span>
                                            </div>
                                            <label class="control-label col-md-3">Probabilidade fechamento</label>
                                            <div class="col-md-2">
                                                <select name="probabilidade_fechamento" class="form-control">
                                                    <option value="">selecione...</option>
                                                    <option value="0">0%</option>
                                                    <option value="10">10%</option>
                                                    <option value="20">20%</option>
                                                    <option value="30">30%</option>
                                                    <option value="40">40%</option>
                                                    <option value="50">50%</option>
                                                    <option value="60">60%</option>
                                                    <option value="70">70%</option>
                                                    <option value="80">80%</option>
                                                    <option value="90">90%</option>
                                                    <option value="100">100%</option>
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Valor da proposta</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">R$</span>
                                                    <input name="valor" type="text" class="form-control valor"
                                                           aria-describedby="basic-addon1"
                                                           onchange="calcular_margem_liquida();">
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                            <label class="control-label col-md-1">Impostos</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">R$</span>
                                                    <input name="impostos" type="text" class="form-control valor"
                                                           aria-describedby="basic-addon1"
                                                           onchange="calcular_margem_liquida();">
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Detalhes proposta</label>
                                            <div class="col-md-9">
                                                <textarea name="detalhes" class="form-control" rows="3"></textarea>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Margem líquida</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">R$</span>
                                                    <input name="margem_liquida" type="text" class="form-control valor"
                                                           aria-describedby="basic-addon1" readonly>
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                            <label class="control-label col-md-3">Custo produto/serviço</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">R$</span>
                                                    <input name="custo_produto_servico" type="text"
                                                           onchange="calcular_margem_liquida();"
                                                           class="form-control valor" aria-describedby="basic-addon1">
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Margem líquida (%)</label>
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <input name="margem_liquida_percentual" type="text"
                                                           class="form-control valor" aria-describedby="basic-addon2"
                                                           readonly>
                                                    <span class="input-group-addon" id="basic-addon2">%</span>
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                            <label class="control-label col-md-3">Custo administrativo</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">R$</span>
                                                    <input name="custo_administrativo" type="text"
                                                           onchange="calcular_margem_liquida();"
                                                           class="form-control valor" aria-describedby="basic-addon1">
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Anexar proposta</label>
                                            <div class="col-md-7">
                                                <div id="arquivo" class="fileinput input-group"
                                                     data-provides="fileinput">
                                                    <div class="form-control" data-trigger="fileinput">
                                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                        <span class="fileinput-preview fileinput-filename"></span>
                                                    </div>
                                                    <div class="input-group-addon btn btn-default btn-file">
                                                        <span class="fileinput-new">Selecionar arquivo</span>
                                                        <span class="fileinput-exists">Alterar</span>
                                                        <input type="file" accept=".pdf" name="arquivo"/>
                                                    </div>
                                                    <a href="#"
                                                       class="input-group-addon btn btn-default fileinput-exists"
                                                       data-dismiss="fileinput">Limpar</a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-right">
                                                <button type="button" class="btn btn-info" id="btnDownload"
                                                        onclick="baixar_arquivo()"><i class="fa fa-download"></i>
                                                    Download
                                                </button>
                                            </div>
                                        </div>
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

<?php require_once APPPATH . 'views/end_js.php'; ?>

    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Propostas Técnicos-Comerciais';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table;

        $('.date').mask('00/00/0000');
        $('.valor').mask('#.###.##0,00', {'reverse': true});


        $(document).ready(function () {

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'order': [],
                'ajax': {
                    'url': '<?php echo site_url('icom/propostas/listar/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#estrutura').serialize();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '20%',
                        'targets': [0]
                    },
                    {
                        'width': '40%',
                        'targets': [2, 3]
                    },
                    {
                        'className': 'text-center',
                        'targets': [1, 4]
                    },
                    {
                        'className': 'text-right',
                        'targets': [5, 6]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ],
                'preDrawCallback': function () {
                    var id_setor = $('#estrutura [name="id_setor"]').val();
                    if (id_setor.length > 0) {
                        $('#pdf').prop('href', '<?= site_url('icom/propostas/relatorio'); ?>/q?setor=' + id_setor).removeClass('disabled');
                    } else {
                        $('#pdf').prop('href', '#').addClass('disabled');
                    }
                }
            });

        });


        function filtrar_estrutura() {
            var data = $('#estrutura').serialize();
            $.ajax({
                'url': '<?php echo site_url('icom/propostas/filtrarEstrutura') ?>',
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

                        $('#estrutura select').prop('disabled', false);
                        reload_table();
                    }
                },
                'complete': function () {
                    $('#estrutura select').prop('disabled', false);
                }
            });
        }


        function calcular_margem_liquida() {
            var valor_proposta = parseFloat($('#form [name="valor"]').val().replace('.', '').replace(',', '.'));
            var impostos = parseFloat($('#form [name="impostos"]').val().replace('.', '').replace(',', '.'));
            var custo_produto = parseFloat($('#form [name="custo_produto_servico"]').val().replace('.', '').replace(',', '.'));
            var custo_administrativo = parseFloat($('#form [name="custo_administrativo"]').val().replace('.', '').replace(',', '.'));

            if (isNaN(valor_proposta)) {
                $('#form [name="margem_liquida"], #form [name="margem_liquida_percentual"]').val('');
                return false;
            }
            if (isNaN(impostos)) {
                impostos = 0;
            }
            if (isNaN(custo_produto)) {
                custo_produto = 0;
            }
            if (isNaN(custo_administrativo)) {
                custo_administrativo = 0;
            }

            var margem_liquida = valor_proposta - impostos - custo_produto - custo_administrativo;
            var margem_liquida_percentual = parseFloat(((margem_liquida / valor_proposta) * 100).toFixed(2));

            $('#form [name="margem_liquida"]').val(margem_liquida.toLocaleString('pt-BR', {'minimumFractionDigits': 2}));
            $('#form [name="margem_liquida_percentual"]').val(margem_liquida_percentual.toString().replace('.', ','));
        }


        function add_proposta() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form [name="id"]').val('');
            $('#id_depto option[value=""]').text('selecione...');
            $('#id_area, #id_setor, #id_cliente').html('<option value="">selecione...</option>');
            $('#arquivo').removeClass('fileinput-exists').addClass('fileinput-new')
                .fileinput({'name': ''}).find('[type="hidden"]').val('');
            $('.modal-title').text('Adicionar proposta');
            $('.combo_nivel1').hide();
            $('#modal_form').modal('show');
        }


        function edit_proposta(id) {
            $.ajax({
                'url': '<?php echo site_url('icom/propostas/editar') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'beforeSend': function () {
                    save_method = 'update';
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

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar proposta');
                }
            });
        }


        function montar_estrutura() {
            $.ajax({
                'url': '<?php echo site_url('icom/propostas/montarEstrutura') ?>',
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
                'url': '<?php echo site_url('icom/propostas/salvar') ?>',
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


        function delete_proposta(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('icom/propostas/excluir') ?>',
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


        function baixar_arquivo() {
            $.fileDownload('<?= site_url('icom/propostas/downloadArquivo') ?>/', {
                'httpMethod': 'POST',
                'data': {'id': id}
            });
        }


    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>
