<?php require_once APPPATH . 'views/header.php'; ?>

    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-search"></i> Gerenciar funcionário
                        </header>
                        <div class="col-sm-5">
                            <br>
                            <button class="btn btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                        </div>
                        <div class="panel-body">
                            <?php echo form_open('ei/colaboradores/getcolaboradores', 'data-html="html-funcionarios" class="form-horizontal" style="margin-top: 15px;" id="busca-funcionarios"'); ?>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-sm-offset-2 controls">
                                        <p><label class="control-label"></label></p>
                                        <input type="text" name="busca" placeholder="Buscar..."
                                               class="form-control input-sm"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <p><label></label></p>
                                        <div class="btn-group" role="group" aria-label="...">
                                            <button type="submit" class="btn btn-sm btn-primary"><i
                                                        class="glyphicon glyphicon-search"></i></button>
                                            <button type="submit" class="btn btn-sm btn-default"
                                                    onclick="$('select').val('')">Limpar filtros
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <p><label></label></p>
                                        <a id="pdf" class="btn btn-sm btn-danger"
                                           href="<?= site_url('apontamento_colaboradores/pdf/'); ?>"
                                           title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i>
                                            Exportar PDF</a>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3 col-lg-2">
                                        <label class="control-label">Filtrar status PDI</label>
                                        <select name="pdi" class="form-control input-sm filtro">
                                            <option value="">Todos</option>
                                            <option value="N">Não iniciados</option>
                                            <option value="A">Atrasados</option>
                                            <option value="E">Em andamento</option>
                                            <option value="C">Completos</option>
                                            <option value="X">Cancelados</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-lg-2">
                                        <label class="control-label">Filtrar status vínculo</label>
                                        <select name="status" class="form-control input-sm filtro">
                                            <option value="">Todos</option>
                                            <option value="1">Ativos</option>
                                            <option value="2">Inativos</option>
                                            <option value="3">Em experiência</option>
                                            <option value="4">Em desligamento</option>
                                            <option value="5">Desligados</option>
                                            <option value="6">Afastados (maternidade)</option>
                                            <option value="7">Afastados (aposentadoria)</option>
                                            <option value="8">Afastados (doença)</option>
                                            <option value="9">Afastados (acidente)</option>
                                            <option value="10">Desistiram da vaga</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por departamento</label>
                                        <?php echo form_dropdown('depto', $depto, $depto_atual, 'class="form-control input-sm filtro"'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por área/cliente</label>
                                        <?php echo form_dropdown('area', $area, $area_atual, 'class="form-control input-sm filtro"'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por setor/unidade</label>
                                        <?php echo form_dropdown('setor', $setor, $setor_atual, 'class="form-control input-sm filtro"'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por cargo</label>
                                        <?php echo form_dropdown('cargo', $cargo, '', 'class="form-control input-sm filtro"'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por função</label>
                                        <?php echo form_dropdown('funcao', $funcao, '', 'class="form-control input-sm filtro"'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por contrato</label>
                                        <?php echo form_dropdown('contrato', $contrato, '', 'class="form-control input-sm filtro"'); ?>
                                    </div>
                                </div>
                                <hr>
                                <?php echo form_close('<div class="box-content" id="html-funcionarios"></div>'); ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_contratos" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="float:right;">
                                Fechar
                            </button>
                            <h3 class="modal-title">Gerenciamento de contratos</h3>
                        </div>
                        <div class="modal-body form">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#contrato_visualizacao" aria-controls="contrato_visualizacao" role="tab"
                                       data-toggle="tab">Visualizar</a>
                                </li>
                                <li role="presentation">
                                    <a href="#contrato_cadastro" aria-controls="contrato_cadastro" role="tab"
                                       data-toggle="tab">Cadastrar</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="contrato_visualizacao">
                                    <div class="form-body">
                                        <div class="row form-horizontal">
                                            <div class="col-md-3">
                                                <label class="radio-inline">
                                                    <input type="radio" name="tipo" value="1" class="tipo"> C.V.
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="tipo" value="2" class="tipo"> Contrato
                                                </label>
                                            </div>
                                            <label class="control-label col-md-3">Selecionar C.V. ou Contrato</label>
                                            <div class="col-md-4">
                                                <select id="curriculos" class="form-control">
                                                    <option value="">selecione...</option>
                                                </select>
                                                <select id="contratos" class="form-control"
                                                        style="display:none;">
                                                    <option value="">selecione...</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 text-right">
                                                <button type="button" class="btn btn-danger" id="btnDeleteContrato"
                                                        onclick="delete_contrato();">Excluir
                                                </button>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <iframe id="documento"
                                                        src="https://docs.google.com/gview?embedded=true&url=<?= base_url('arquivos/documentos/colaborador/'); ?>"
                                                        style="width:100%; height:600px; margin:0;"
                                                        frameborder="0"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="contrato_cadastro">
                                    <div id="alert"></div>
                                    <form action="#" id="form_contrato" class="form-horizontal">
                                        <input type="hidden" value="" name="colaborador"/>
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="control-label col-md-2">Tipo</label>
                                                <div class="col-md-3">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="tipo" value="15"> C.V.
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="tipo" value="16"> Contrato
                                                    </label>
                                                </div>
                                                <div class="col-md-7 text-right">
                                                    <button type="button" class="btn btn-success" id="btnSaveContrato"
                                                            onclick="save_contrato();">Salvar
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-2">Descrição</label>
                                                <div class="col-md-9">
                                                    <input name="descricao" placeholder="Descrição" class="form-control"
                                                           type="text">
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Arquivo (.pdf)</label>
                                                <div class="col-md-10">
                                                    <div id="arquivo_documento" class="fileinput input-group"
                                                         data-provides="fileinput">
                                                        <div class="form-control" data-trigger="fileinput">
                                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                            <span class="fileinput-preview fileinput-filename"></span>
                                                        </div>
                                                        <div class="input-group-addon btn btn-default btn-file" name="">
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
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- page end-->
        </section>
    </section>
    <!--main content end-->
<?php require_once APPPATH . 'views/end_js.php'; ?>

    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar funcionários';
            setPdf_atributes();
        });

        $('select.filtro').on('change', function () {
            $('#busca-funcionarios').submit();
        });

        $('#busca-funcionarios').submit(function () {
            ajax_post($(this).attr('action'), $(this).serialize(), $('#' + $(this).data('html')));
            atualizarFiltro(this);
            setPdf_atributes();
            return false;
        }).submit();

        function atualizarFiltro(el) {
            $.ajax({
                'url': '<?php echo site_url('home/atualizar_filtro') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $(el).serialize(),
                'success': function (json) {
                    $('[name="area"]').html($(json.area).html());
                    $('[name="setor"]').html($(json.setor).html());
                    $('[name="cargo"]').html($(json.cargo).html());
                    $('[name="funcao"]').html($(json.funcao).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function gerenciar_contratos(id_usuario) {
            $('#form_contrato')[0].reset();
            $('#modal_contratos ul li:eq(1), #contrato_cadastro').removeClass('active');
            $('#modal_contratos ul li:eq(0), #contrato_visualizacao').addClass('active');
            $('#documento').attr('src', 'https://docs.google.com/gview?embedded=true&url=<?= base_url('arquivos/documentos/colaborador/') ?>');

            $.ajax({
                'url': '<?php echo site_url('ei/colaboradores/gerenciarContratos') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id_usuario': id_usuario},
                'success': function (json) {
                    $('#curriculos').html($(json.curriculos).html());
                    $('#contratos').html($(json.contratos).html());

                    $('.tipo[value="1"], #form_contrato [name="tipo"][value="15"]').prop('checked', true);
                    $('#form_contrato [name="colaborador"]').val(id_usuario);

                    $('#modal_contratos').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        $('.tipo').on('change', function () {
            if (this.value === '1') {
                $('#curriculos').show();
                $('#contratos').hide();
            } else if (this.value === '2') {
                $('#curriculos').hide();
                $('#contratos').show();
            }

            $('#curriculos, #contratos').val('').trigger('change');
        });

        $('#curriculos, #contratos').on('change', function () {
            var url = '<?= base_url('arquivos/documentos/colaborador/') ?>';
            $('#documento').attr('src', 'https://docs.google.com/gview?embedded=true&url=' + url + '/' + this.value);
        });

        function save_contrato() {
            $.ajax({
                'url': '<?php echo site_url('ei/colaboradores/salvarContrato'); ?>',
                'type': 'POST',
                'data': new FormData($('#form_contrato')[0]),
                'dataType': 'json',
                'enctype': 'multipart/form-data',
                'processData': false,
                'contentType': false,
                'cache': false,
                'beforeSend': function () {
                    $('#btnSaveContrato').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else if (json.status) {
                        $('#modal_contratos').modal('hide');
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveContrato').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_contrato() {
            if (confirm('Tem certeza que deseja excluir o arquivo?')) {
                var arquivo = '';
                if ($('.tipo:checked').val() === '1') {
                    arquivo = $('#curriculos').val();
                } else if ($('.tipo:checked').val() === '2') {
                    arquivo = $('#contratos').val();
                }
                $.ajax({
                    'url': '<?php echo site_url('ei/colaboradores/excluirContrato'); ?>',
                    'type': 'POST',
                    'data': {
                        'arquivo': arquivo
                    },
                    'dataType': 'json',
                    'beforeSend': function () {
                        $('#btnDeleteContrato').text('Excluindo...').attr('disabled', true);
                    },
                    'success': function (json) {
                        if (json.erro) {
                            alert(json.erro);
                        } else if (json.status) {
                            $('#modal_contratos').modal('hide');
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    },
                    'complete': function () {
                        $('#btnDeleteContrato').text('Excluir').attr('disabled', false);
                    }
                });
            }
        }


        function setPdf_atributes() {
            var search = '';
            var q = new Array();

            $('.filtro').each(function (i, v) {
                if (v.value.length > 0) {
                    q[i] = v.name + "=" + v.value;
                }
            });

            q = q.filter(function (v) {
                return v.length > 0;
            });
            if (q.length > 0) {
                search = '/q?' + q.join('&');
            }

            $('#pdf').prop('href', "<?= site_url('ei/colaboradores/pdf/'); ?>" + search);
        }
    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>