<?php require_once APPPATH . 'views/header.php'; ?>

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('ei/apontamento') ?>">Apontamentos diários</a></li>
                        <li class="active">Gerenciar Ordens de Serviço</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_os()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar O.S.
                    </button>
                    <button class="btn btn-info" onclick="copiar_os()"><i class="glyphicon glyphicon-duplicate"></i>
                        Copiar O.S. semestre anterior
                    </button>
                    <a id="pdf" class="btn btn-primary" href="<?= site_url('ei/relatorios/pdfMapaCarregamentoOS/'); ?>"
                       title="Relatório Mapa Escolas X Alunos" target="_blank"><i class="glyphicon glyphicon-print"></i>
                        Mapa Escolas X Alunos</a>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <form action="#" id="busca" class="form-horizontal" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="control-label">Área/cliente</label>
                                            <?php echo form_dropdown('busca[diretoria]', $diretorias, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="control-label">Contrato</label>
                                            <?php echo form_dropdown('busca[contrato]', $contratos, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label><br>
                                            <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
                                                Limpar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="control-label">Município</label>
                                            <?php echo form_dropdown('busca[municipio]', $municipios, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Escolas</label>
                                            <?php echo form_dropdown('busca[escola]', $escolas, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">Ano/semestre</label>
                                            <?php echo form_dropdown('busca[ano_semestre]', $anoSemestres, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">Ordem serviço</label>
                                            <?php echo form_dropdown('busca[ordem_servico]', $ordensServico, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table id="table" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Contrato</th>
                            <th>Ordem serviço</th>
                            <th>Ano / semestre</th>
                            <th>Ações para O.S.</th>
                            <th>Unidade de ensino</th>
                            <th>Ações para unidade de ensino</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Adicionar Ordem de Serviço</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Área/cliente</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('', $id_diretoria, '', 'id="diretoria" class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Contrato<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_contrato', $id_contrato, '', 'id="contrato" class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Ordem de Serviço<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-6">
                                            <input name="nome" placeholder="Nome da Ordem de Serviço"
                                                   class="form-control" type="text" maxlength="30">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Número de empenho</label>
                                        <div class="col-md-9">
                                            <input name="numero_empenho" placeholder="Número de empenho"
                                                   class="form-control" type="text" maxlength="255">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Ano</label>
                                        <div class="col-md-2">
                                            <input name="ano" class="form-control text-center ano" placeholder="aaaa"
                                                   type="text">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="radio-inline">
                                                <input type="radio" name="semestre" value="1" checked> 1&ordm; semestre
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="semestre" value="2"> 2&ordm; semestre
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="modal_copia_os" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Copiar Ordens de Serviço</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_copia_os" class="form-horizontal">
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Copiar do ano</label>
                                        <div class="col-md-2">
                                            <input name="ano_anterior" class="form-control text-center ano"
                                                   placeholder="aaaa" type="text" onchange="selecionar_os();">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="radio-inline">
                                                <input type="radio" name="semestre_anterior" value="1"
                                                       onchange="selecionar_os();"> 1&ordm; semestre
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="semestre_anterior" value="2"
                                                       onchange="selecionar_os();"> 2&ordm; semestre
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <?php echo form_multiselect('id[]', array(), array(), 'id="ordens_servico" class="demo2" size="8"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2 text-nowrap">Nome da nova O.S. <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-4">
                                            <input name="nome" class="form-control" type="text"
                                                   placeholder="Digite o nme da nova O.S.">
                                        </div>
                                        <label class="control-label col-md-1 text-nowrap">Contrato <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-5">
                                            <?php echo form_dropdown('id_contrato', $id_contrato, '', 'id="contrato" class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2 text-nowrap">Número de empenho</label>
                                        <div class="col-md-4">
                                            <input name="numero_empenho" placeholder="Número de empenho"
                                                   class="form-control" type="text" maxlength="255">
                                        </div>
                                        <label class="control-label col-md-1">Ano <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-2" style="width: 12%;">
                                            <input name="ano" class="form-control text-center ano" placeholder="aaaa"
                                                   type="text">
                                        </div>
                                        <div class="col-md-3 text-nowrap">
                                            <label class="radio-inline">
                                                <input type="radio" name="semestre" value="1"> 1&ordm; semestre
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="semestre" value="2"> 2&ordm; semestre
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveCopiaOS" onclick="save_copia_os()" class="btn btn-success">
                                Criar cópia(s)
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="modal_escolas" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Adicionar unidade de ensino</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_escolas" class="form-horizontal">
                                <input type="hidden" value="" name="id_ordem_servico"/>
                                <div class="form-body">
                                    <div class="row" style="font-size: 14px;">
                                        <label class="control-label col-md-3"><strong>Área/cliente:</strong></label>
                                        <div class="col-md-8" style="font-weight: bold;">
                                            <p class="form-control-static diretoria"></p>
                                            <!--                                            --><?php //echo form_dropdown('', $id_diretoria, '', 'id="diretoria_escolas" class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row" style="font-size: 14px;">
                                        <label class="control-label col-md-3"><strong>Contrato:</strong></label>
                                        <div class="col-md-8" style="font-weight: bold;">
                                            <p class="form-control-static contrato"></p>
                                        </div>
                                    </div>
                                    <div class="row" style="font-size: 14px;">
                                        <label class="control-label col-md-3"><strong>Ordem de Serviço:</strong></label>
                                        <div class="col-md-8" style="font-weight: bold;">
                                            <p class="form-control-static ordem_servico"></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="control-label">Filtrar escolas disponíveis por
                                                município</label>
                                            <?php echo form_dropdown('', $municipios, '', 'id="municipio" class="form-control"'); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Visualizar escolas selecionadas por
                                                município</label>
                                            <?php echo form_dropdown('', $municipios, '', 'id="municipio2" class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <?php echo form_multiselect('id_escola[]', $id_escola, array(), 'id="escola" class="demo1" size="8"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveEscolas" onclick="save_escolas()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>


        </section>
    </section>

<?php require_once APPPATH . 'views/end_js.php'; ?>

    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Ordens de Serviço';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>
        var save_method;
        var table, demo1, demo2;

        $('.ano').mask('0000');

        $(document).ready(function () {
            table = $('#table').DataTable({
                'dom': "<'row'<'col-sm-4'l><'#total_escolas.col-sm-2'><'#total_alunos.col-sm-2'><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                'processing': true,
                'serverSide': true,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                'iDisplayLength': -1,
                'ajax': {
                    'url': '<?php echo site_url('ei/ordemServico/ajaxList') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    },
                    'dataSrc': function (json) {
                        $('#total_escolas').html('<br>Total de escolas: ' + json.total_escolas);
                        $('#total_alunos').html('<br>Total de alunos: ' + json.total_alunos);

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'width': '20%',
                        'targets': [0, 1]
                    },
                    {
                        'width': '60%',
                        'targets': [4]
                    },
                    {
                        'className': 'text-center',
                        'targets': [2]
                    },
                    {
                        'className': 'text-center text-nowrap',
                        'targets': [-1, -3],
                        'orderable': false,
                        'searchable': false
                    }
                ],
                'rowsGroup': [0, 1, 2, 3]
            });


            demo1 = $('.demo1').bootstrapDualListbox({
                'nonSelectedListLabel': 'Escolas disponíveis',
                'selectedListLabel': 'Escolas selecionadas',
                'preserveSelectionOnMove': 'moved',
                'moveOnSelect': false,
                'filterPlaceHolder': 'Filtrar',
                'helperSelectNamePostfix': false,
                'selectorMinimalHeight': 132,
                'infoText': false
            });

            demo2 = $('.demo2').bootstrapDualListbox({
                'nonSelectedListLabel': 'Ordens de Serviço cadastradas',
                'selectedListLabel': 'Ordens de Serviço a serem copiadas',
                'preserveSelectionOnMove': 'moved',
                'moveOnSelect': false,
                'filterPlaceHolder': 'Filtrar',
                'helperSelectNamePostfix': false,
                'selectorMinimalHeight': 128,
                'infoText': false
            });

            setPdf_atributes();
        });

        $('#diretoria').on('change', function () {
            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico/atualizarContratos') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_diretoria': this.value,
                    'id_contrato': $('#contrato').val()
                },
                'success': function (json) {
                    $('#contrato').html($(json.contrato).html());
                }
            });
        });

        $('#limpa_filtro').on('click', function () {
            var busca = unescape($('#busca').serialize());
            $.each(busca.split('&'), function (index, elem) {
                var vals = elem.split('=');
                $("[name='" + vals[0] + "']").val($("[name='" + vals[0] + "'] option:first").val());
            });
            atualizarFiltro();
        });

        function atualizarFiltro() {
            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico/atualizarFiltro') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize(),
                'success': function (json) {
                    $('[name="busca[contrato]"]').html($(json.contrato).html());
                    $('[name="busca[ano_semestre]"]').html($(json.ano_semestre).html());
                    $('[name="busca[ordem_servico]"]').html($(json.ordem_servico).html());
                    $('[name="busca[municipio]"]').html($(json.municipio).html());
                    $('[name="busca[escola]"]').html($(json.escola).html());
                    reload_table();
                }
            });
        }

        function add_os() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('.modal-title').text('Adicionar Ordem de Serviço');
            $('#diretoria, #contrato').val('').prop('disabled', false);
            $('#copiar_os').show();
            $('#diretoria').trigger('change');
            $('#modal_form').modal('show');
            $('.combo_nivel1').hide();
        }

        function add_aluno() {
            save_method = 'add';
            $('#form_aluno')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#diretoria, #contrato').val('');
            $('#diretoria').trigger('change');

            $('#modal_aluno').modal('show');
            $('.combo_nivel1').hide();
        }

        function add_horario() {
            save_method = 'add';
            $('#form_horario')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#diretoria, #contrato').val('');
            $('#diretoria').trigger('change');
            $('#modal_horario').modal('show');
            $('.combo_nivel1').hide();
        }

        function edit_os(id) {
            save_method = 'update';
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('.combo_nivel1').hide();

            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico/ajaxEdit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $('#form [name="id"]').val(json.id);
                    $('#form [name="nome"]').val(json.nome);
                    $('#form [name="numero_empenho"]').val(json.numero_empenho);
                    $('#form [name="ano"]').val(json.ano);
                    $('#form [name="semestre"][value="' + json.semestre + '"]').prop('checked', true);
                    $('#diretoria').val(json.diretoria).prop('disabled', true);
                    $('#contrato').html($(json.contrato).html()).prop('disabled', true);
                    $('#copiar_os').hide();

                    $('.modal-title').text('Editar Ordem de Serviço');
                    $('#modal_form').modal('show');
                }
            });
        }

        function copiar_os() {
            $('#form_copia_os')[0].reset();
            selecionar_os();
            $('#modal_copia_os').modal('show');
        }

        function selecionar_os() {
            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico/copiarOS') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'ano': $('#form_copia_os [name="ano_anterior"]').val(),
                    'semestre': $('#form_copia_os [name="semestre_anterior"]:checked').val(),
                    'id': $('#ordens_servico').val()
                },
                'beforeSend': function () {
                    $('.bootstrap-duallistbox-container').find('*').prop('disabled', true);
                    $('#btnSaveCopiaOS').prop('disabled', true);
                },
                'success': function (json) {
                    $('#ordens_servico').html($(json.ordens_servico).html());
                    demo2.bootstrapDualListbox('refresh', true);
                },
                'complete': function () {
                    $('.bootstrap-duallistbox-container').find('*').prop('disabled', false);
                    $('#btnSaveCopiaOS').prop('disabled', false);
                }
            });
        }

        function add_escola(id) {
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('.combo_nivel1').hide();

            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico/ajaxEditEscola') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $('.diretoria').text(json.diretoria);
                    $('.contrato').text(json.contrato);
                    $('.ordem_servico').text(json.nome);
                    $('.ano_semestre').text(json.ano_semestre);
                    $('#form_escolas [name="id_ordem_servico"]').val(json.id);
                    $('#municipio').html($(json.municipio).html());
                    $('#escola').html($(json.escola).html());
                    demo1.bootstrapDualListbox('refresh', true);

                    $('#modal_escolas').modal('show');
                }
            });
        }


        $('#municipio').on('change', function () {
            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico/atualizarEscolas') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_ordem_servico': $('#form_escolas [name="id_ordem_servico"]').val(),
                    'municipio': $('#municipio').val(),
                    'escolas': $('#escola').val()
                },
                'beforeSend': function () {
                    $('.bootstrap-duallistbox-container').find('*').prop('disabled', true);
                    $('#btnSaveEscolas').prop('disabled', true);
                    $('#municipio2').val();
                },
                'success': function (json) {
                    $('#escola').html($(json.escola).html());
                    demo1.bootstrapDualListbox('refresh', true);
                },
                'complete': function () {
                    $('.bootstrap-duallistbox-container').find('*').prop('disabled', false);
                    $('#btnSaveEscolas').prop('disabled', false);
                }
            });
        });


        $('#municipio2').on('change', function () {
            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico/filtrarEscolasSelecionadas') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_ordem_servico': $('#form_escolas [name="id_ordem_servico"]').val(),
                    'municipio': $('#municipio2').val(),
                    'escolas': $('#escola').val()
                },
                'beforeSend': function () {
                    $('.bootstrap-duallistbox-container').find('*').prop('disabled', true);
                    $('#btnSaveEscolas').prop('disabled', true);
                },
                'success': function (json) {
                    $('.demo1 option:selected').hide();
                    $.each(json.escolas, function (key, value) {
                        $('.demo1 option:selected[value="' + value + '"]').show();
                    });
                    demo1.bootstrapDualListbox('refresh', true);
                },
                'complete': function () {
                    $('.bootstrap-duallistbox-container').find('*').prop('disabled', false);
                    $('#btnSaveEscolas').prop('disabled', false);
                }
            });
        });


        function save() {
            var url;
            if (save_method === 'add') {
                url = '<?php echo site_url('ei/ordemServico/ajaxAdd') ?>';
            } else {
                url = '<?php echo site_url('ei/ordemServico/ajaxUpdate') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSave').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }
                },
                'complete': function () {
                    $('#btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }

        function save_copia_os() {
            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico/salvarCopiaOS') ?>',
                'type': 'POST',
                'data': $('#form_copia_os').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveCopiaOS').text('Criando cópia(s)...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_copia_os').modal('hide');
                        atualizarFiltro();
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'complete': function () {
                    $('#btnSaveCopiaOS').text('Criar cópia(s)').attr('disabled', false);
                }
            });
        }

        function save_escolas() {
            $('.demo1 option:selected').show();
            demo1.bootstrapDualListbox('refresh', true);

            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico/ajaxAddEscola') ?>',
                'type': 'POST',
                'data': $('#form_escolas').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveEscolas').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_escolas').modal('hide');
                        reload_table();
                    }
                },
                'complete': function () {
                    $('#btnSaveEscolas').text('Salvar').attr('disabled', false);
                }
            });
        }

        function save_curso() {
            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico/ajaxAddCurso') ?>',
                'type': 'POST',
                'data': $('#form_curso').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveCurso').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_curso').modal('hide');
                        reload_table();
                    }
                },
                'complete': function () {
                    $('#btnSaveCurso').text('Salvar').attr('disabled', false);
                }
            });
        }

        function save_profissional() {
            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico/ajaxAddProfissional') ?>',
                'type': 'POST',
                'data': $('#form_profissional').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveProfissional').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_profissional').modal('hide');
                        reload_table();
                    }
                },
                'complete': function () {
                    $('#btnSaveProfissional').text('Salvar').attr('disabled', false);
                }
            });
        }

        function delete_os(id, recursao = false) {
            if (recursao) {
                var senha_exclusao = prompt('Deseja remover a ordem de serviço? \nUma vez removida, a ordem de serviço não poderá ser recuperada!\n\nSenha inválida! Digite novamente a senha para realizar a exclusão da OS');
            } else {
                var senha_exclusao = prompt('Deseja remover a ordem de serviço? \nUma vez removida, a ordem de serviço não poderá ser recuperada!\n\nDigite a senha para realizar a exclusão da OS');
            }
            if (senha_exclusao !== null) {
                $.ajax({
                    'url': '<?php echo site_url('ei/ordemServico/ajaxDelete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {
                        'id': id,
                        'senha_exclusao': senha_exclusao
                    },
                    'success': function (json) {
                        if (json.acesso_negado) {
                            delete_os(id, true);
                        } else {
                            reload_table();
                        }
                    }
                });
            }
        }

        function delete_curso(id) {
            if (confirm('Deseja remover o curso?')) {
                $.ajax({
                    'url': '<?php echo site_url('ei/ordemServico/ajaxDeleteCurso') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        reload_table();
                    }
                });

            }
        }

        function reload_table() {
            table.ajax.reload(null, false);
            setPdf_atributes();
        }

        function alunos(id_escola) {
            var logado = <?php echo $this->session->userdata('logado') ? 'true' : 'false'; ?>;
            if (logado) {
                window.open("<?php echo site_url('ei/ordemServico_alunos/gerenciar'); ?>/" + id_escola, 'Profissionais', 'STATUS=NO, TOOLBAR=NO, LOCATION=NO, DIRECTORIES=NO, RESISABLE=NO, SCROLLBARS=YES, TOP=100, LEFT=250, WIDTH=1010, HEIGHT=500');
            } else {
                window.open("<?php echo site_url('home/sair'); ?>");
            }
        }

        function profissionais(id_escola) {
            var logado = <?php echo $this->session->userdata('logado') ? 'true' : 'false'; ?>;
            if (logado) {
                window.open("<?php echo site_url('ei/ordemServico_profissionais/gerenciar'); ?>/" + id_escola, 'Alunos', 'STATUS=NO, TOOLBAR=NO, LOCATION=NO, DIRECTORIES=NO, RESISABLE=NO, SCROLLBARS=YES, TOP=80, LEFT=180, WIDTH=1130, HEIGHT=560');
            } else {
                window.open("<?php echo site_url('home/sair'); ?>");
            }
        }

        function setPdf_atributes() {
            var search = '/q?' + $('#busca').serialize();

            $('#pdf').prop('href', "<?= site_url('ei/relatorios/pdfMapaCarregamentoOS/'); ?>" + search);
        }
    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>
