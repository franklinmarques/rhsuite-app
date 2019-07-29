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

    .wizard > .steps a, .wizard > .steps a:hover, .wizard > .steps a:active {
        background: #eee !important;
    }

    .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
        background: #111343 !important;
        color: #fff;
        cursor: default;
    }

    .wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
        background: #758fb0 !important;
        color: #fff;
    }
</style>

<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        <i class="glyphicons glyphicons-nameplate"></i>&nbsp;
                        Gerenciar Estrutura Organizacional
                    </header>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-4">
                                <label for="depto">Departamento</label>
                                <?php echo form_dropdown('', $depto, '', 'id="depto" class="form-control input-sm" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label for="area">Area</label>
                                <?php echo form_dropdown('', $area, '', 'id="area" class="form-control input-sm" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label for="setor">Setor</label>
                                <?php echo form_dropdown('', $setor, '', 'id="setor" class="form-control input-sm" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
                            </div>
                        </div>
                        <hr>

                        <div id="wizard">
                            <h6>Processos</h6>
                            <div style="padding: 0; border-top: 1px solid #ddd; height: auto;">
                                <br>
                                <button id="novo_processo" class="btn btn-warning" onclick="add_processo();"><i
                                            class="glyphicon glyphicon-plus"></i> Novo processo
                                </button>
                                <br>
                                <div class="table-responsive">
                                    <table id="table_processo" class="table table-striped table-condensed"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Processos</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <h6>Atividades</h6>
                            <div style="padding: 0; border-top: 1px solid #ddd;">
                                <br>
                                <button id="nova_atividade" class="btn btn-info" onclick="add_atividade();"><i
                                            class="glyphicon glyphicon-plus"></i> Nova atividade
                                </button>
                                <br>
                                <div class="table-responsive">
                                    <table id="table_atividade" class="table table-striped table-condensed"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Processo</th>
                                            <th>Atividades</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <h6>Etapas</h6>
                            <div style="padding: 0; border-top: 1px solid #ddd;">
                                <br>
                                <button id="novo_etapa" class="btn btn-info" onclick="add_etapa();"><i
                                            class="glyphicon glyphicon-plus"></i> Nova etapa
                                </button>
                                <br>
                                <table id="table_etapa" class="table table-striped table-condensed" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Processo</th>
                                        <th>Atividade</th>
                                        <th>Etapa</th>
                                        <th nowrap>Grau complexidade</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <h6>Itens</h6>
                            <div style="padding: 0; border-top: 1px solid #ddd;">
                                <br>
                                <button id="novo_item" class="btn btn-info" onclick="add_item();"><i
                                            class="glyphicon glyphicon-plus"></i> Novo item
                                </button>
                                <br>
                                <table id="table_item" class="table table-striped table-condensed" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Processo</th>
                                        <th>Atividade</th>
                                        <th>Etapa</th>
                                        <th>Item</th>
                                        <th>Descrição</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_processo" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Adicionar processo</h3>
                    </div>
                    <div class="modal-body form">
                        <div id="alert"></div>
                        <form action="#" id="form_processo" class="form-horizontal" autocomplete="off">
                            <input type="hidden" value="" name="id"/>
                            <input type="hidden" value="<?php echo $empresa; ?>" name="id_empresa"/>
                            <input type="hidden" value="" name="id_depto"/>
                            <input type="hidden" value="" name="id_area"/>
                            <input type="hidden" value="" name="id_setor"/>
                            <div class="form-body">
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Nome <span
                                                class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input name="nome" class="form-control" type="text" maxlength="255">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveProcesso" onclick="save_processo()" class="btn btn-success">
                            Salvar
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_atividade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Adicionar atividade</h3>
                    </div>
                    <div class="modal-body form">
                        <div id="alert"></div>
                        <form action="#" id="form_atividade" class="form-horizontal">
                            <input type="hidden" value="" name="id"/>
                            <input type="hidden" value="" name="id_processo"/>
                            <div class="form-body">
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Nome <span
                                                class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input name="nome" class="form-control" type="text" maxlength="255">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveAtividade" onclick="save_atividade()" class="btn btn-success">
                            Salvar
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_etapa" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Adicionar etapa</h3>
                    </div>
                    <div class="modal-body form">
                        <div id="alert"></div>
                        <form action="#" id="form_etapa" class="form-horizontal">
                            <input type="hidden" value="" name="id"/>
                            <input type="hidden" value="" name="id_atividade"/>
                            <div class="form-body">
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Nome <span
                                                class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input name="nome" class="form-control" type="text" maxlength="255">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-5">Grau de complexidade da etapa</label>
                                    <div class="col-md-6">
                                        <select name="grau_complexidade" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="5">Extremamente alta</option>
                                            <option value="4">Alta</option>
                                            <option value="3">Média</option>
                                            <option value="2">Baixa</option>
                                            <option value="1">Extremamente baixa</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-5 text-nowrap">Volume/tamanho do item
                                        envolvido</label>
                                    <div class="col-md-6">
                                        <select name="tamanho_item" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="5">Extremamente grande</option>
                                            <option value="4">Grande</option>
                                            <option value="3">Médio</option>
                                            <option value="2">Pequeno</option>
                                            <option value="1">Extremamente pequeno</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-5">Peso do item envolvido</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input name="peso_item" class="form-control peso" type="text"
                                                   class="form-control" aria-describedby="basic-addon">
                                            <span class="input-group-addon" id="basic-addon">Kg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveItem" onclick="save_etapa()" class="btn btn-success">
                            Salvar
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_item" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Adicionar item</h3>
                    </div>
                    <div class="modal-body form">
                        <div id="alert"></div>
                        <form action="#" id="form_item" class="form-horizontal">
                            <input type="hidden" value="" name="id"/>
                            <input type="hidden" value="" name="id_etapa"/>
                            <div class="form-body">
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Nome <span
                                                class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input name="nome" class="form-control" type="text" maxlength="50">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Descrição</label>
                                    <div class="col-md-9">
                                        <input name="descricao" class="form-control" type="text" maxlength="50">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Unidade</label>
                                    <div class="col-md-4">
                                        <input name="unidade_medida" class="form-control" type="text" maxlength="10">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-1">Valor</label>
                                    <div class="col-md-4">
                                        <input name="valor" class="form-control valor" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveItem" onclick="save_item()" class="btn btn-success">
                            Salvar
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->

        <!-- page end-->
    </section>
</section>
<!--main content end-->

<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/css/jquery.steps.css?1') ?>" rel="stylesheet">

<?php require_once APPPATH . 'views/end_js.php'; ?>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'RhSuite - Corporate RH Tools: Gerenciar Estruturas de Dimensionamento';
    });

</script>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery-steps/jquery.steps.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>
    var save_method;
    var table_processo, table_atividade, table_etapa, table_item;
    var estruturas = false;
    var id_processo = '';
    var id_atividade = '';
    var id_etapa = '';

    $('.valor').mask('#######0,00', {'reverse': true});
    $('.peso').mask('#####0,000', {'reverse': true});

    var steps = $('#wizard').steps({
        'headerTag': 'h6',
        'bodyTag': 'div',
        'transitionEffect': 0,
        'autoFocus': true,
        'enableFinishButton': false,
        'enablePagination': false,
        'enableAllSteps': true,
        'titleTemplate': '#title#',
        'startIndex': <?php echo $indice; ?>
    });


    $(document).ready(function () {

        table_processo = $('#table_processo').DataTable({
            'processing': true,
            'serverSide': true,
            'order': [],
            'iDisplayLength': -1,
            'bLengthChange': false,
            'searching': false,
            'paging': false,
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('dimensionamento/estruturas/ajaxListProcessos') ?>',
                'type': 'POST',
                'data': function (d) {
                    d.depto = $('#depto').val();
                    d.area = $('#area').val();
                    d.setor = $('#setor').val();
                    return d;
                }
            },
            'columnDefs': [
                {
                    'width': '100%',
                    'targets': [0]
                },
                {
                    'className': 'text-nowrap',
                    'targets': [-1],
                    'orderable': false,
                    'searchable': false
                }
            ],
            'drawCallback': function () {
                if (estruturas) {
                    $('#novo_processo').addClass('btn-info').removeClass('btn-warning');
                } else {
                    $('#novo_processo').addClass('btn-warning').removeClass('btn-info');
                }
            }
        });

        table_atividade = $('#table_atividade').DataTable({
            'processing': true,
            'serverSide': true,
            'order': [],
            'iDisplayLength': -1,
            'bLengthChange': false,
            'searching': false,
            'paging': false,
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('dimensionamento/estruturas/ajaxListAtividades/') ?>',
                'type': 'POST',
                'data': function (d) {
                    d.depto = $('#depto').val();
                    d.area = $('#area').val();
                    d.setor = $('#setor').val();
                    d.id_processo = id_processo;
                    return d;
                }
            },
            'columnDefs': [
                {
                    'width': '50%',
                    'targets': [0, 1]
                },
                {
                    'mRender': function (data) {
                        if (data === null) {
                            data = '<span class="text-muted">Não há atividades cadastradas</span>';
                        }
                        return data;
                    },
                    "targets": [1]
                },
                {
                    'className': 'text-nowrap',
                    'targets': [-1],
                    'orderable': false,
                    'searchable': false
                }
            ],
            'rowsGroup': [0],
            'drawCallback': function () {
                if (id_processo.length === 0) {
                    $('#nova_atividade').addClass('btn-warning').removeClass('btn-info');
                } else {
                    $('#nova_atividade').addClass('btn-info').removeClass('btn-warning');
                }
            }
        });

        table_etapa = $('#table_etapa').DataTable({
            'processing': true,
            'serverSide': true,
            'order': [],
            'iDisplayLength': -1,
            'bLengthChange': false,
            'searching': false,
            'paging': false,
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('dimensionamento/estruturas/ajaxListEtapas/') ?>',
                'type': 'POST',
                'data': function (d) {
                    d.depto = $('#depto').val();
                    d.area = $('#area').val();
                    d.setor = $('#setor').val();
                    d.id_processo = id_processo;
                    d.id_atividade = id_atividade;
                    return d;
                }
            },
            'columnDefs': [
                {
                    'width': '33%',
                    'targets': [0, 1, 2]
                },
                {
                    'mRender': function (data) {
                        if (data === null) {
                            data = '<span class="text-muted">Não há etapas cadastradas</span>';
                        }
                        return data;
                    },
                    "targets": [2]
                },
                {
                    'className': 'text-nowrap',
                    'targets': [-1],
                    'orderable': false,
                    'searchable': false
                }
            ],
            'rowsGroup': [0, 1, 2],
            'drawCallback': function () {
                if (id_atividade.length === 0) {
                    $('#novo_etapa').addClass('btn-warning').removeClass('btn-info');
                } else {
                    $('#novo_etapa').addClass('btn-info').removeClass('btn-warning');
                }
            }
        });

        table_item = $('#table_item').DataTable({
            'processing': true,
            'serverSide': true,
            'order': [],
            'iDisplayLength': -1,
            'bLengthChange': false,
            'searching': false,
            'paging': false,
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('dimensionamento/estruturas/ajaxListItens/') ?>',
                'type': 'POST',
                'data': function (d) {
                    d.depto = $('#depto').val();
                    d.area = $('#area').val();
                    d.setor = $('#setor').val();
                    d.id_processo = id_processo;
                    d.id_atividade = id_atividade;
                    d.id_etapa = id_etapa;
                    return d;
                }
            },
            'columnDefs': [
                {
                    'width': '20%',
                    'targets': [0, 1, 2, 3, 4]
                },
                {
                    'mRender': function (data) {
                        if (data === null) {
                            data = '<span class="text-muted">Não há itens cadastrados</span>';
                        }
                        return data;
                    },
                    "targets": [3]
                },
                {
                    'className': 'text-nowrap',
                    'targets': [-1],
                    'orderable': false,
                    'searchable': false
                }
            ],
            'rowsGroup': [0, 1, 2],
            'drawCallback': function () {
                if (id_etapa.length === 0) {
                    $('#novo_item').addClass('btn-warning').removeClass('btn-info');
                } else {
                    $('#novo_item').addClass('btn-info').removeClass('btn-warning');
                }
            }
        });

    });


    $(document).on('shown.bs.tab', function () {
        $.fn.dataTable.tables({'visible': true, 'api': true}).columns.adjust();
    });


    function filtrar_estrutura() {
        $.ajax({
            'url': '<?php echo site_url('dimensionamento/estruturas/filtrarEstrutura') ?>',
            'type': 'POST',
            'data': {
                'depto': $('#depto').val(),
                'area': $('#area').val(),
                'setor': $('#setor').val()
            },
            'dataType': 'JSON',
            'success': function (json) {
                $('#area').html($(json.area).html());
                $('#setor').html($(json.setor).html());
                estruturas = ($('#depto').val() !== '' && $('#area').val() !== '' && $('#setor').val() !== '');
                console.log(estruturas);
                reload_table();
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }


    function add_processo() {
        if (estruturas === false) {
            alert('Selecione o departamento, a área e o setor onde será adicionada o novo processo.');
            return false;
        }

        save_method = 'add';
        $('#form_processo')[0].reset();
        $('#form_processo [name="id"]').val('');
        $('#form_processo [name="id_depto"]').val($('#depto').val());
        $('#form_processo [name="id_area"]').val($('#area').val());
        $('#form_processo [name="id_setor"]').val($('#setor').val());
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_processo').modal('show');
        $('.modal-title').text('Adicionar novo processo');
        $('.combo_nivel1').hide();
    }


    function add_atividade() {
        if (id_processo.length === 0) {
            alert('Selecione o processo onde será cadastrado a nova atividade.');
            return false;
        }

        save_method = 'add';
        $('#form_atividade')[0].reset();
        $('#form_atividade [name="id"]').val('');
        $('#form_atividade [name="id_processo"]').val(id_processo);
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_atividade').modal('show');
        $('.modal-title').text('Adicionar nova atividade');
        $('.combo_nivel1').hide();
    }


    function add_etapa() {
        if (id_processo.length === 0 && id_atividade.length === 0) {
            alert('Selecione o processo e a atividade onde serão cadastrados a nova etapa.');
            return false;
        } else if (id_processo.length === 0 || id_atividade.length === 0) {
            alert('Selecione a atividade onde será cadastrada a nova etapa.');
            return false;
        }

        save_method = 'add';
        $('#form_etapa')[0].reset();
        $('#form_etapa [name="id"]').val('');
        $('#form_etapa [name="id_atividade"]').val(id_atividade);
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_etapa').modal('show');
        $('.modal-title').text('Adicionar nova etapa');
        $('.combo_nivel1').hide();
    }


    function add_item() {
        if (id_processo.length === 0 && id_atividade.length === 0 && id_etapa.length === 0) {
            alert('Selecione o processo, a atividade e a etapa onde serão cadastrados o novo item.');
            return false;
        } else if (id_atividade.length === 0 && id_etapa.length === 0) {
            alert('Selecione a atividade e a etapa onde serão cadastradas o novo item.');
            return false;
        } else if (id_etapa.length === 0) {
            alert('Selecione a etapa onde será cadastrada o novo item.');
            return false;
        }

        save_method = 'add';
        $('#form_item')[0].reset();
        $('#form_item [name="id"]').val('');
        $('#form_item [name="id_etapa"]').val(id_etapa);
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_item').modal('show');
        $('.modal-title').text('Adicionar nova item');
        $('.combo_nivel1').hide();
    }


    function edit_processo(id) {
        save_method = 'update';
        $('#form_processo')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            'url': '<?php echo site_url('dimensionamento/estruturas/ajaxEditProcesso') ?>',
            'type': 'POST',
            'dataType': 'JSON',
            'data': {'id': id},
            'success': function (json) {
                if (json.erro) {
                    alert(json.erro);
                    return false;
                }
                $.each(json, function (key, value) {
                    $('#form_processo [name="' + key + '"]').val(value);
                });

                $('#modal_processo').modal('show');
                $('.modal-title').text('Editar processo - ' + json.nome);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function edit_atividade(id) {
        save_method = 'update';
        $('#form_atividade')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            'url': '<?php echo site_url('dimensionamento/estruturas/ajaxEditAtividade') ?>',
            'type': 'POST',
            'dataType': 'JSON',
            'data': {'id': id},
            'success': function (json) {
                if (json.erro) {
                    alert(json.erro);
                    return false;
                }
                $.each(json, function (key, value) {
                    $('#form_atividade [name="' + key + '"]').val(value);
                });

                $('#modal_atividade').modal('show');
                $('.modal-title').text('Editar atividade - ' + json.nome);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function edit_etapa(id) {
        save_method = 'update';
        $('#form_etapa')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            'url': '<?php echo site_url('dimensionamento/estruturas/ajaxEditEtapa') ?>',
            'type': 'POST',
            'dataType': 'JSON',
            'data': {'id': id},
            'success': function (json) {
                if (json.erro) {
                    alert(json.erro);
                    return false;
                }
                $.each(json, function (key, value) {
                    $('#form_etapa [name="' + key + '"]').val(value);
                });

                $('#modal_etapa').modal('show');
                $('.modal-title').text('Editar etapa - ' + json.nome);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function edit_item(id) {
        save_method = 'update';
        $('#form_item')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            'url': '<?php echo site_url('dimensionamento/estruturas/ajaxEditItem') ?>',
            'type': 'POST',
            'dataType': 'JSON',
            'data': {'id': id},
            'success': function (json) {
                if (json.erro) {
                    alert(json.erro);
                    return false;
                }
                $.each(json, function (key, value) {
                    $('#form_item [name="' + key + '"]').val(value);
                });

                $('#modal_item').modal('show');
                $('.modal-title').text('Editar item - ' + json.nome);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function save_processo() {
        $('#btnSaveProcesso').text('Salvando...').attr('disabled', true);
        var url;
        if (save_method === 'add') {
            url = '<?php echo site_url('dimensionamento/estruturas/ajaxAddProcesso') ?>';
        } else {
            url = '<?php echo site_url('dimensionamento/estruturas/ajaxUpdateProcesso') ?>';
        }

        $.ajax({
            'url': url,
            'type': 'POST',
            'data': $('#form_processo').serialize(),
            'dataType': 'JSON',
            'success': function (json) {
                if (json.status) {
                    $('#modal_processo').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }

                $('#btnSaveProcesso').text('Salvar').attr('disabled', false);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSaveProcesso').text('Salvar').attr('disabled', false);
            }
        });
    }


    function save_atividade() {
        $('#btnSaveAtividade').text('Salvando...').attr('disabled', true);
        var url;
        if (save_method === 'add') {
            url = '<?php echo site_url('dimensionamento/estruturas/ajaxAddAtividade') ?>';
        } else {
            url = '<?php echo site_url('dimensionamento/estruturas/ajaxUpdateAtividade') ?>';
        }

        $.ajax({
            'url': url,
            'type': 'POST',
            'data': $('#form_atividade').serialize(),
            'dataType': 'JSON',
            'success': function (json) {
                if (json.status) {
                    $('#modal_atividade').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }

                $('#btnSaveAtividade').text('Salvar').attr('disabled', false);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSaveAtividade').text('Salvar').attr('disabled', false);
            }
        });
    }


    function save_etapa() {
        $('#btnSaveItem').text('Salvando...').attr('disabled', true);
        var url;
        if (save_method === 'add') {
            url = '<?php echo site_url('dimensionamento/estruturas/ajaxAddEtapa') ?>';
        } else {
            url = '<?php echo site_url('dimensionamento/estruturas/ajaxUpdateEtapa') ?>';
        }

        $.ajax({
            'url': url,
            'type': 'POST',
            'data': $('#form_etapa').serialize(),
            'dataType': 'JSON',
            'success': function (json) {
                if (json.status) {
                    $('#modal_etapa').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }

                $('#btnSaveItem').text('Salvar').attr('disabled', false);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSaveItem').text('Salvar').attr('disabled', false);
            }
        });
    }


    function save_item() {
        $('#btnSaveItem').text('Salvando...').attr('disabled', true);
        var url;
        if (save_method === 'add') {
            url = '<?php echo site_url('dimensionamento/estruturas/ajaxAddItem') ?>';
        } else {
            url = '<?php echo site_url('dimensionamento/estruturas/ajaxUpdateItem') ?>';
        }

        $.ajax({
            'url': url,
            'type': 'POST',
            'data': $('#form_item').serialize(),
            'dataType': 'JSON',
            'success': function (json) {
                if (json.status) {
                    $('#modal_item').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }

                $('#btnSaveItem').text('Salvar').attr('disabled', false);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSaveItem').text('Salvar').attr('disabled', false);
            }
        });
    }


    function delete_processo(id) {
        if (confirm('Deseja remover?')) {
            $.ajax({
                'url': '<?php echo site_url('dimensionamento/estruturas/ajaxDeleteProcesso') ?>',
                'type': 'POST',
                'dataType': 'JSON',
                'data': {'id': id},
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    alert('Error deleting data');
                }
            });
        }
    }


    function delete_atividade(id) {
        if (confirm('Deseja remover?')) {
            $.ajax({
                'url': '<?php echo site_url('dimensionamento/estruturas/ajaxDeleteAtividade') ?>',
                'type': 'POST',
                'dataType': 'JSON',
                'data': {'id': id},
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    alert('Error deleting data');
                }
            });
        }
    }


    function delete_etapa(id) {
        if (confirm('Deseja remover?')) {
            $.ajax({
                'url': '<?php echo site_url('dimensionamento/estruturas/ajaxDeleteEtapa') ?>',
                'type': 'POST',
                'dataType': 'JSON',
                'data': {'id': id},
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    alert('Error deleting data');
                }
            });
        }
    }


    function delete_item(id) {
        if (confirm('Deseja remover?')) {
            $.ajax({
                'url': '<?php echo site_url('dimensionamento/estruturas/ajaxDeleteItem') ?>',
                'type': 'POST',
                'dataType': 'JSON',
                'data': {'id': id},
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    alert('Error deleting data');
                }
            });
        }
    }


    function reload_table() {
        table_processo.ajax.reload(null, false);
        table_atividade.ajax.reload(null, false);
        table_etapa.ajax.reload(null, false);
        table_item.ajax.reload(null, false);
    }


    function next_atividade(id_processo_pai) {
        if (id_processo !== id_processo_pai || id_processo === '') {
            id_processo = id_processo_pai;
            id_atividade = '';
            id_etapa = '';
        }
        $('#wizard-t-1').trigger('click');
        reload_table();
    }


    function next_etapa(id_atividade_mae) {
        if (id_atividade !== id_atividade_mae || id_atividade === '') {
            id_atividade = id_atividade_mae;
            id_etapa = '';
        }
        $('#wizard-t-2').trigger('click');
        reload_table();
    }


    function next_item(id_etapa_mae) {
        id_etapa = id_etapa_mae;
        $('#wizard-t-3').trigger('click');
        reload_table();
    }

</script>

<?php require_once APPPATH . 'views/end_html.php'; ?>

