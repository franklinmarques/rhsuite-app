<?php
require_once "header.php";
?>
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
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="glyphicons glyphicons-nameplate"></i>&nbsp; Descritor de Cargos/Funções
                        </header>
                        <div class="panel-body">
                            <form action="#" id="busca">
                                <div class="col-md-4">
                                    <label class="control-label">Filtrar por cargo</label>
                                    <?php echo form_dropdown('cargo', $cargo, '', 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Filtrar por função</label>
                                    <?php echo form_dropdown('funcao', $funcao, '', 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Filtrar por versão</label>
                                    <?php echo form_dropdown('versao', $versao, '', 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                                </div>
                                <div class="col-md-2 text-right">
                                    <br>
                                    <button type="button" id="limpar" class="btn btn-default">Limpar filtro</button>
                                </div>
                            </form>
                        </div>

                        <br>
                        <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Cargo</th>
                                <th>Função</th>
                                <th>CBO</th>
                                <th>Ação</th>
                                <th>Versões</th>
                                <th>Ações para candidato</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
            <!-- page end-->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Adicionar versão de cargo/função</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_versao_anterior"/>
                                <input type="hidden" value="" name="id_cargo"/>
                                <input type="hidden" value="" name="id_funcao"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-1">Versão</label>
                                        <div class="col-md-5">
                                            <input name="versao" class="form-control" type="text"
                                                   placeholder="Nome da versão do cargo/função">
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-3">
                                            <!--<div class="dropdown">
                                                <button class="btn btn-info dropdown-toggle" type="button"
                                                        id="btnCopiarVersaoAnterior" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="true">
                                                    Copiar versão anterior
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnCopiarVersaoAnterior">
                                                    <li><a href="#" onclick="copiar_versao_anterior(0)">Copiar
                                                            estrutura</a></li>
                                                    <li><a href="#" onclick="copiar_versao_anterior(1)">Copiar estrutura
                                                            e conteúdo</a></li>
                                                </ul>
                                            </div>-->


                                        </div>
                                        <div class="col-md-3 text-right">
                                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
                                                Salvar
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-offset-1">
                                            <button type="button" id="btnCopiarVersaoAnterior"
                                                    onclick="copiar_versao_anterior(0)" class="btn btn-info">
                                                Copiar estrutura da versão anterior
                                            </button>
                                            <button type="button" id="btnCopiarVersaoAnterior1"
                                                    onclick="copiar_versao_anterior(1)" class="btn btn-info">
                                                Copiar estrutura e conteúdo da versão anterior
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <select id="estruturas" name="estruturas[]" multiple class="form-control demo2">
                                            <option value="sumario">Descrição sumária</option>
                                            <option value="formacao_experiencia">Formação e experiência</option>
                                            <option value="condicoes_gerais_exercicio">Condições gerais de exercício
                                            </option>
                                            <option value="codigo_internacional_CIUO88">Código Internacional CIUO88
                                            </option>
                                            <option value="notas">Notas</option>
                                            <option value="recursos_trabalho">Recursos de trabalho</option>
                                            <option value="atividades">Atribuições e atividades</option>
                                            <option value="responsabilidades">Responsabilidades</option>
                                            <option value="habilidades_basicas">Conhecimentos e habilidades - Básicas
                                            </option>
                                            <option value="habilidades_intermediarias">Conhecimentos e habilidades -
                                                Intermediárias
                                            </option>
                                            <option value="habilidades_avancadas">Conhecimentos e habilidades -
                                                Avançadas
                                            </option>
                                            <option value="ambiente_trabalho">Especificações gerais - Ambiente de
                                                trabalho
                                            </option>
                                            <option value="condicoes_trabalho">Especificações gerais - Condições de
                                                trabalho
                                            </option>
                                            <option value="esforcos_fisicos">Especificações gerais - Esforços físicos
                                            </option>
                                            <option value="grau_autonomia">Especificações gerais - Grau de autonomia
                                            </option>
                                            <option value="grau_complexidade">Especificações gerais - Grau de
                                                complexidade
                                            </option>
                                            <option value="grau_iniciativa">Especificações gerais - Grau de iniciativa
                                            </option>
                                            <option value="competencias_tecnicas">Competências Técnicas</option>
                                            <option value="competencias_comportamentais">Competências Comportamentais
                                            </option>
                                            <option value="tempo_experiencia">Tempo de experiência no cargo/função
                                            </option>
                                            <option value="formacao_minima">Formação/escolaridade mínima</option>
                                            <option value="formacao_plena">Formação/escolaridade para exercício pleno
                                            </option>
                                            <option value="esforcos_mentais">Esforços mentais</option>
                                            <option value="grau_pressao">Grau de pressão/estresse</option>
                                        </select>
                                    </div>

                                    <br>
                                    <h5>Descritivos personalizados para esta versão de cargo/função</h5>
                                    <hr style="margin-top: 0px;">

                                    <div class="form-group">
                                        <label class="control-label col-md-2">Descritivo n&ordm;1</label>
                                        <div class="col-md-9">
                                            <input name="campo_livre1" class="form-control" type="text"
                                                   placeholder="Nome do novo descritivo">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Descritivo n&ordm;2</label>
                                        <div class="col-md-9">
                                            <input name="campo_livre2" class="form-control" type="text"
                                                   placeholder="Nome do novo descritivo">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Descritivo n&ordm;3</label>
                                        <div class="col-md-9">
                                            <input name="campo_livre3" class="form-control" type="text"
                                                   placeholder="Nome do novo descritivo">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Descritivo n&ordm;4</label>
                                        <div class="col-md-9">
                                            <input name="campo_livre4" class="form-control" type="text"
                                                   placeholder="Nome do novo descritivo">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Descritivo n&ordm;5</label>
                                        <div class="col-md-9">
                                            <input name="campo_livre5" class="form-control" type="text"
                                                   placeholder="Nome do novo descritivo">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_respondentes" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Responsáveis pelo preenchimento dos descritivos de cargo/função</h3>
                        </div>
                        <div class="modal-body form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="control-label">Filtrar por departamento</label>
                                                <?php echo form_dropdown('depto', $depto, '', 'class="form-control filtro input-sm"'); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">Filtrar por área</label>
                                                <?php echo form_dropdown('area', $area, '', 'class="form-control filtro input-sm"'); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">Filtrar por setor</label>
                                                <?php echo form_dropdown('setor', $setor, '', 'class="form-control filtro input-sm"'); ?>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <label>&nbsp;</label><br>
                                                <!--                                                <div class="btn-group" role="group" aria-label="...">-->
                                                <!--<button type="button" id="limpa_filtro"
                                                        class="btn btn-default">Limpar filtros
                                                </button>-->
                                                <button type="button" id="btnSaveRespondentes"
                                                        onclick="save_respondentes()" class="btn btn-success">Salvar
                                                </button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    Cancelar
                                                </button>
                                                <!--                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form action="#" id="form_respondentes" class="form-horizontal">
                                <div class="form-body" style="padding: 0 20px 20px;">
                                    <input type="hidden" value="" name="id_descritor"/>
                                    <div class="row form-group">
                                        <?php echo form_multiselect('id_usuario[]', $respondentes, array(), 'size="10" id="respondentes" class="demo2"') ?>
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

<?php
require_once "end_js.php";
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar detalhes de eventos';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script>

        var save_method; //for save method string
        var table;
        var demo1, demo2;

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                iDisplayLength: 500,
                lengthMenu: [[5, 10, 25, 50, 100, 500], [5, 10, 25, 50, 100, 500]],
                "order": [[0, 'asc'], [1, 'asc'], [3, 'asc']], //Initial no order.
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('jobDescriptor/ajax_list/') ?>",
                    "type": "POST",
                    'data': function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    }
                },
                rowsGroup: [0, 1, 2],
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '50%',
                        targets: [0, 1]
                    },
                    {
                        className: 'text-center text-nowrap',
                        searchable: false,
                        targets: [4]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [2, 5], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ]
            });

            demo1 = $('#estruturas').bootstrapDualListbox({
                filterPlaceHolder: 'Filtrar',
                moveOnSelect: false,
                preserveSelectionOnMove: 'moved',
                nonSelectedListLabel: 'Descritivos disponíveis',
                selectedListLabel: 'Descritivos selecionados',
                helperSelectNamePostfix: false,
                selectorMinimalHeight: 172,
                infoText: false
            });
            demo2 = $('#respondentes').bootstrapDualListbox({
                filterPlaceHolder: 'Filtrar',
                moveOnSelect: false,
                preserveSelectionOnMove: 'moved',
                nonSelectedListLabel: 'Colaboradores disponíveis',
                selectedListLabel: 'Colaboradores selecionados',
                helperSelectNamePostfix: false,
                selectorMinimalHeight: 172,
                infoText: false
            });

        });

        function atualizarFiltro() {
            $.ajax({
                url: "<?php echo site_url('jobDescriptor/atualizar_filtro/') ?>",
                type: "POST",
                dataType: "JSON",
                data: $('#busca').serialize(),
                success: function (json) {
                    if (json.area !== undefined) {
                        $('[name="area"]').html($(json.area).html());
                    }
                    if (json.setor !== undefined) {
                        $('[name="setor"]').html($(json.setor).html());
                    }
                    $('[name="cargo"]').html($(json.cargo).html());
                    $('[name="funcao"]').html($(json.funcao).html());

                    $('[name="versao"]').html($(json.versao).html());

                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        $('#limpar').on('click', function () {
            var busca = $('#busca').serialize();
            $.each(busca.split('&'), function (index, elem) {
                var vals = elem.split('=');
                if (vals[0] === 'data_avaliacao') {
                    $("[name='" + vals[0] + "']").val('');
                } else {
                    $("[name='" + vals[0] + "']").val($("[name='" + vals[0] + "'] option:first").val());
                }
            });
            atualizarFiltro();
        });

        function add_versao(id_cargo, id_funcao) {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('#form [name="id"], #form [name="id_versao_anterior"]').val('')
            $('#form [name="id_cargo"]').val(id_cargo);
            $('#form [name="id_funcao"]').val(id_funcao);
            demo1.bootstrapDualListbox('refresh', true);
            $('.modal-title').text('Adicionar  versão de cargo/função'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function copiar_versao_anterior(estrutura = 0) {
            // $('#btnCopiarVersaoAnterior').html('Copiando versão anterior... <span class="caret"></span>').prop('disabled', true);
            if (estrutura === 0) {
                $('#btnCopiarVersaoAnterior').html('Copiando estrutura da versão anterior...');
            } else {
                $('#btnCopiarVersaoAnterior1').html('Copiando estrutura e conteúdo da versão anterior...');
            }
            $('#btnCopiarVersaoAnterior, #btnCopiarVersaoAnterior1').prop('disabled', true);
            $.ajax({
                url: "<?php echo site_url('jobDescriptor/ajaxVersaoAnterior') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    id: $('#form [name="id"]').val(),
                    id_cargo: $('#form [name="id_cargo"]').val(),
                    id_funcao: $('#form [name="id_funcao"]').val(),
                    copiar_estrutura: estrutura
                },
                success: function (json) {
                    if (json.length !== 0) {
                        $('[name="id_versao_anterior"]').val(json.id_versao_anterior);
                        $('#estruturas').val(json.estruturas);
                        demo1.bootstrapDualListbox('refresh', true);

                        $('[name="campo_livre1"]').val(json.campo_livre1);
                        $('[name="campo_livre2"]').val(json.campo_livre2);
                        $('[name="campo_livre3"]').val(json.campo_livre3);
                        $('[name="campo_livre4"]').val(json.campo_livre4);
                        $('[name="campo_livre5"]').val(json.campo_livre5);
                    } else {
                        alert('Esta é a versão mais recente');
                    }
                    // $('#btnCopiarVersaoAnterior').html('Copiar versão anterior <span class="caret"></span>').prop('disabled', false);
                    if (estrutura === 0) {
                        $('#btnCopiarVersaoAnterior').html('Copiar estrutura da versão anterior');
                    } else {
                        $('#btnCopiarVersaoAnterior1').html('Copiar estrutura e conteúdo da versão anterior');
                    }
                    $('#btnCopiarVersaoAnterior, #btnCopiarVersaoAnterior1').prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    // $('#btnCopiarVersaoAnterior').html('Copiar versão anterior <span class="caret"></span>').prop('disabled', false);
                    if (estrutura === 0) {
                        $('#btnCopiarVersaoAnterior').html('Copiar estrutura da versão anterior');
                    } else {
                        $('#btnCopiarVersaoAnterior1').html('Copiar estrutura e conteúdo da versão anterior');
                    }
                    $('#btnCopiarVersaoAnterior, #btnCopiarVersaoAnterior1').prop('disabled', false);
                }
            });
        }

        function edit_versao(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('jobDescriptor/ajax_edit') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (json) {
                    $('[name="id"]').val(json.id);
                    $('[name="id_versao_anterior"]').val('');
                    $('[name="id_empresa"]').val(json.id_empresa);
                    $('[name="id_cargo"]').val(json.id_cargo);
                    $('[name="id_funcao"]').val(json.id_funcao);
                    $('[name="versao"]').val(json.versao);

                    $('#estruturas').val(json.estruturas);
                    demo1.bootstrapDualListbox('refresh', true);

                    $('[name="campo_livre1"]').val(json.campo_livre1);
                    $('[name="campo_livre2"]').val(json.campo_livre2);
                    $('[name="campo_livre3"]').val(json.campo_livre3);
                    $('[name="campo_livre4"]').val(json.campo_livre4);
                    $('[name="campo_livre5"]').val(json.campo_livre5);

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar  versão de cargo/função'); // Set title to Bootstrap modal title

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_respondentes(id) {
            $('#form_respondentes')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('jobDescriptor/ajax_respondentes/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (json) {
                    $('#form_respondentes [name="id_descritor"]').val(json.id_descritor);
                    $('#respondentes').val(json.id_usuario);
                    $('.filtro').val('');
                    demo2.bootstrapDualListbox('refresh', true);

                    $('#modal_respondentes').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
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
                url = "<?php echo site_url('jobDescriptor/ajax_add') ?>";
            } else {
                url = "<?php echo site_url('jobDescriptor/ajax_update') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (json) {
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
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }

        function save_respondentes() {
            $('#btnSaveRespondentes').text('Salvando...'); //change button text
            $('#btnSaveRespondentes').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('jobDescriptor/ajax_saveRespondentes') ?>",
                type: "POST",
                data: $('#form_respondentes').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_respondentes').modal('hide');
                        reload_table();
                    }

                    $('#btnSaveRespondentes').text('Salvar'); //change button text
                    $('#btnSaveRespondentes').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveRespondentes').text('Salvar'); //change button text
                    $('#btnSaveRespondentes').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_versao(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('jobDescriptor/ajax_delete') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {id: id},
                    success: function (data) {
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
//                    alert('Error deleting data');
                    }
                });

            }
        }

    </script>

<?php
require_once "end_html.php";
?>