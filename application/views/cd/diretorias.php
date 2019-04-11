<?php
require_once APPPATH . "views/header.php";
?>

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('cd/apontamento') ?>">Apontamentos diários</a></li>
                        <li class="active">Gerenciar diretorias</li>
                    </ol>
                    <button class="btn btn-success" onclick="add_diretoria()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar diretoria
                    </button>
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
                                        <div class="col-md-3">
                                            <label class="control-label">Departamento</label>
                                            <?php echo form_dropdown('busca[depto]', $depto, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">Diretoria de ensino/prefeitura</label>
                                            <?php echo form_dropdown('busca[diretoria]', $diretoria, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">coordenador</label>
                                            <?php echo form_dropdown('busca[coordenador]', $coordenador, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">Contrato</label>
                                            <?php echo form_dropdown('busca[contrato]', $contrato, '', 'class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-1">
                                            <label>&nbsp;</label><br>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
                                                    Limpar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table id="table" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Diretoria de ensino/Prefeitura</th>
                            <th>Contrato</th>
                            <th>Ações</th>
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
                            <h3 class="modal-title">Editar diretoria</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Diretoria de Ensino</label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Nome da Diretoria de Ensino"
                                                   class="form-control" type="text" size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Diretoria de Ensino (alias)</label>
                                        <div class="col-md-9">
                                            <input name="alias"
                                                   placeholder="Nome resumido da Diretoria de Ensino (alias)"
                                                   class="form-control" type="text" size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Departamento</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('depto', $deptos_disponiveis, $cuidadores, 'id="depto" class="estrutura form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Município</label>
                                        <div class="col-md-9">
                                            <input name="municipio" placeholder="Nome da área" id="area"
                                                   class="form-control" type="text" size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Contrato</label>
                                        <div class="col-md-9">
                                            <input name="contrato" placeholder="Nome do contrato" id="contrato"
                                                   class="form-control" type="text" size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Coordenador(a)</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_coordenador', $coordenadores, '', 'id="id_coordenador" class="form-control"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal_unidades" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Gerenciar unidades/setores</h3>
                        </div>
                        <div class="modal-body form">
                            <div class="row">
                                <div class="col-md-2 text-right"><strong>Contrato:</strong></div>
                                <div class="col-md-9">
                                    <span id="unidade_contrato"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right"><strong>Cliente:</strong></div>
                                <div class="col-md-9">
                                    <span id="unidade_cliente"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right"><strong>Departamento:</strong></div>
                                <div class="col-md-9">
                                    <span id="unidade_depto"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right"><strong>Área:</strong></div>
                                <div class="col-md-9">
                                    <span id="unidade_area"></span>
                                </div>
                            </div>
                            <hr style="margin-top: 10px; margin-bottom: 0px;">
                            <form action="#" id="form_unidades" class="form-horizontal">
                                <input type="hidden" value="" name="id_contrato"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <?php echo form_multiselect('setor[]', array(), array(), 'size="10" id="unidades" class="demo2"') ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveUnidades" onclick="save_unidade_ensino()"
                                    class="btn btn-primary">Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal_servicos" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Gerenciar serviços</h3>
                        </div>
                        <div class="modal-body form">
                            <div class="row">
                                <div class="col-md-3 text-right"><strong>Contrato:</strong></div>
                                <div class="col-md-8">
                                    <span id="servicos_contrato"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 text-right"><strong>Cliente:</strong></div>
                                <div class="col-md-8">
                                    <span id="servicos_cliente"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 text-right"><strong>Departamento:</strong></div>
                                <div class="col-md-8">
                                    <span id="servicos_depto"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 text-right"><strong>Área:</strong></div>
                                <div class="col-md-8">
                                    <span id="servicos_area"></span>
                                </div>
                            </div>
                            <hr>
                            <form action="#" id="form_servicos_compartilhados" class="form_servicos form-horizontal"
                                  autocomplete="off">
                                <input type="hidden" name="id_contrato" value="">
                                <div class="row form-group">
                                    <div class="col-md-8 col-md-offset-1">
                                        <h5>Serviços compartilhados</h5>
                                    </div>
                                    <div class="col-md-2">
                                        <h5>Valor (R$)</h5>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <input type="hidden" name="id[]" value="">
                                    <div class="col-md-8 col-md-offset-1">
                                        <input name="descricao[]" placeholder="Serviço 1" class="form-control input-sm"
                                               type="text">
                                    </div>
                                    <div class="col-md-2">
                                        <input name="valor[]" placeholder="Valor"
                                               class="valor form-control input-sm text-right" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <input type="hidden" name="id[]" value="">
                                    <div class="col-md-8 col-md-offset-1">
                                        <input name="descricao[]" placeholder="Serviço 2" class="form-control input-sm"
                                               type="text">
                                    </div>
                                    <div class="col-md-2">
                                        <input name="valor[]" placeholder="Valor"
                                               class="valor form-control input-sm text-right" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <input type="hidden" name="id[]" value="">
                                    <div class="col-md-8 col-md-offset-1">
                                        <input name="descricao[]" placeholder="Serviço 3" class="form-control input-sm"
                                               type="text">
                                    </div>
                                    <div class="col-md-2">
                                        <input name="valor[]" placeholder="Valor"
                                               class="valor form-control input-sm text-right" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <input type="hidden" name="id[]" value="">
                                    <div class="col-md-8 col-md-offset-1">
                                        <input name="descricao[]" placeholder="Serviço 4" class="form-control input-sm"
                                               type="text">
                                    </div>
                                    <div class="col-md-2">
                                        <input name="valor[]" placeholder="Valor"
                                               class="valor form-control input-sm text-right" type="text">
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <form action="#" id="form_servicos_nao_compartilhados" class="form_servicos form-horizontal"
                                  autocomplete="off">
                                <div class="row form-group">
                                    <div class="col-md-8 col-md-offset-1">
                                        <h5>Serviços não compartilhados</h5>
                                    </div>
                                    <div class="col-md-2">
                                        <h5>Valor (R$)</h5>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <input type="hidden" name="id[]" value="">
                                    <div class="col-md-8 col-md-offset-1">
                                        <input name="descricao[]" placeholder="Serviço 1" class="form-control input-sm"
                                               type="text">
                                    </div>
                                    <div class="col-md-2">
                                        <input name="valor[]" placeholder="Valor"
                                               class="valor form-control input-sm text-right" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <input type="hidden" name="id[]" value="">
                                    <div class="col-md-8 col-md-offset-1">
                                        <input name="descricao[]" placeholder="Serviço 2" class="form-control input-sm"
                                               type="text">
                                    </div>
                                    <div class="col-md-2">
                                        <input name="valor[]" placeholder="Valor"
                                               class="valor form-control input-sm text-right" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <input type="hidden" name="id[]" value="">
                                    <div class="col-md-8 col-md-offset-1">
                                        <input name="descricao[]" placeholder="Serviço 3" class="form-control input-sm"
                                               type="text">
                                    </div>
                                    <div class="col-md-2">
                                        <input name="valor[]" placeholder="Valor"
                                               class="valor form-control input-sm text-right" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <input type="hidden" name="id[]" value="">
                                    <div class="col-md-8 col-md-offset-1">
                                        <input name="descricao[]" placeholder="Serviço 4" class="form-control input-sm"
                                               type="text">
                                    </div>
                                    <div class="col-md-2">
                                        <input name="valor[]" placeholder="Valor"
                                               class="valor form-control input-sm text-right" type="text">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveServicos" onclick="save_servicos()"
                                    class="btn btn-primary">Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal_reajuste" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Gerenciar reajuste</h3>
                        </div>
                        <div class="modal-body form">
                            <div class="row">
                                <div class="col-md-3 text-right"><strong>Contrato:</strong></div>
                                <div class="col-md-8">
                                    <span id="reajuste_contrato"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 text-right"><strong>Cliente:</strong></div>
                                <div class="col-md-8">
                                    <span id="reajuste_cliente"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 text-right"><strong>Departamento:</strong></div>
                                <div class="col-md-8">
                                    <span id="reajuste_depto"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 text-right"><strong>Área:</strong></div>
                                <div class="col-md-8">
                                    <span id="reajuste_area"></span>
                                </div>
                            </div>
                            <hr>
                            <form action="#" id="form_reajuste" class="form-horizontal">
                                <input type="hidden" id="id_cliente" name="id_cliente" value="">
                                <div class="form-body">
                                    <div class="row form-group">
                                        <input type="hidden" name="id[]" value="">
                                        <label class="control-label col-md-2"><strong>1º Índice</strong></label>
                                        <label class="control-label col-md-1">Data</label>
                                        <div class="col-md-4">
                                            <input name="data_reajuste[]" placeholder="dd/mm/aaaa"
                                                   class="data_reajuste form-control text-center" type="text">
                                        </div>
                                        <label class="control-label col-md-1">Valor</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input name="valor_indice[]" placeholder="Valor"
                                                       class="form-control text-right" type="number" min="0"
                                                       max="9999999999.999" step="0.001">
                                                <span class="input-group-addon" id="basic-addon1">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin-top: 0px; margin-bottom: 10px;">
                                    <div class="row form-group">
                                        <input type="hidden" name="id[]" value="">
                                        <label class="control-label col-md-2"><strong>2º Índice</strong></label>
                                        <label class="control-label col-md-1">Data</label>
                                        <div class="col-md-4">
                                            <input name="data_reajuste[]" placeholder="dd/mm/aaaa"
                                                   class="data_reajuste form-control text-center" type="text">
                                        </div>
                                        <label class="control-label col-md-1">Valor</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input name="valor_indice[]" placeholder="Valor"
                                                       class="form-control text-right" type="number" min="0"
                                                       max="9999999999.999" step="0.001">
                                                <span class="input-group-addon" id="basic-addon1">%</span>
                                            </div>
                                        </div>
                                    </div>

                                    <hr style="margin-top: 0px; margin-bottom: 10px;">
                                    <div class="row form-group">
                                        <input type="hidden" name="id[]" value="">
                                        <label class="control-label col-md-2"><strong>3º Índice</strong></label>
                                        <label class="control-label col-md-1">Data</label>
                                        <div class="col-md-4">
                                            <input name="data_reajuste[]" placeholder="dd/mm/aaaa"
                                                   class="data_reajuste form-control text-center" type="text">
                                        </div>
                                        <label class="control-label col-md-1">Valor</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input name="valor_indice[]" placeholder="Valor"
                                                       class="form-control text-right" type="number" min="0"
                                                       max="9999999999.999" step="0.001">
                                                <span class="input-group-addon" id="basic-addon1">%</span>
                                            </div>
                                        </div>
                                    </div>

                                    <hr style="margin-top: 0px; margin-bottom: 10px;">
                                    <div class="row form-group">
                                        <input type="hidden" name="id[]" value="">
                                        <label class="control-label col-md-2"><strong>4º Índice</strong></label>
                                        <label class="control-label col-md-1">Data</label>
                                        <div class="col-md-4">
                                            <input name="data_reajuste[]" placeholder="dd/mm/aaaa"
                                                   class="data_reajuste form-control text-center" type="text">
                                        </div>
                                        <label class="control-label col-md-1">Valor</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input name="valor_indice[]" placeholder="Valor"
                                                       class="form-control text-right" type="number" min="0"
                                                       max="9999999999.999" step="0.001">
                                                <span class="input-group-addon" id="basic-addon1">%</span>
                                            </div>
                                        </div>
                                    </div>

                                    <hr style="margin-top: 0px; margin-bottom: 10px;">
                                    <div class="row form-group">
                                        <input type="hidden" name="id[]" value="">
                                        <label class="control-label col-md-2"><strong>5º Índice</strong></label>
                                        <label class="control-label col-md-1">Data</label>
                                        <div class="col-md-4">
                                            <input name="data_reajuste[]" placeholder="dd/mm/aaaa"
                                                   class="data_reajuste form-control text-center" type="text">
                                        </div>
                                        <label class="control-label col-md-1">Valor</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input name="valor_indice[]" placeholder="Valor"
                                                       class="form-control text-right" type="number" min="0"
                                                       max="9999999999.999" step="0.001">
                                                <span class="input-group-addon" id="basic-addon1">%</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveReajuste" onclick="save_reajuste()"
                                    class="btn btn-primary">Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>

<?php require_once APPPATH . "views/end_js.php"; ?>

    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar diretorias';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table;
        var demo2;
        var avaliadores;

        $(document).ready(function () {

            $('.data_reajuste').mask('00/00/0000');
            $('.valor').mask('##.###.##0,00', {reverse: true});

            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 100,
                lengthMenu: [[5, 10, 25, 50, 100, 500], [5, 10, 25, 50, 100, 500]],
                language: {
                    url: '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                ajax: {
                    url: '<?php echo site_url('cd/diretorias/ajax_list/') ?>',
                    type: 'POST',
                    data: function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    }
                },
                columnDefs: [
                    {
                        width: '60%',
                        targets: [0]
                    },
                    {
                        width: '40%',
                        targets: [1]
                    },
                    {
                        className: 'text-nowrap',
                        targets: [-1],
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            demo2 = $('#unidades').bootstrapDualListbox({
                nonSelectedListLabel: 'Unidades disponíveis',
                selectedListLabel: 'Unidades selecionadas',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                filterPlaceHolder: 'Filtrar',
                helperSelectNamePostfix: false,
                selectorMinimalHeight: 132,
                infoText: false
            });

        });

        function atualizarFiltro() {
            $.ajax({
                url: '<?php echo site_url('cd/diretorias/atualizar_filtro/') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: $('#busca').serialize(),
                success: function (data) {
                    $('[name="busca[diretoria]"]').html($(data.diretoria).html());
                    $('[name="busca[coordenador]"]').html($(data.coordenador).html());
                    $('[name="busca[contrato]"]').html($(data.contrato).html());
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        $('#limpa_filtro').on('click', function () {
            var busca = unescape($('#busca').serialize());
            $.each(busca.split('&'), function (index, elem) {
                var vals = elem.split('=');
                $("[name='" + vals[0] + "']").val($("[name='" + vals[0] + "'] option:first").val());
            });
            atualizarFiltro();
        });

        $('.estrutura').on('change', function () {
            atualizar_estrutura();
        });

        function atualizar_estrutura(id_coordenador = '') {
            $.ajax({
                url: '<?php echo site_url('cd/diretorias/ajax_estrutura/') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    depto: $('#depto').val(),
                    id_coordenador: id_coordenador
                },
                success: function (data) {
                    $('[name="id_coordenador"]').html($(data.id_coordenador).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function add_diretoria() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('[name="tipo"] option').prop('disabled', false);
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar nova diretoria');
            $('.combo_nivel1').hide();
        }

        function edit_diretoria(id) {
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                url: '<?php echo site_url('cd/diretorias/ajax_edit/') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (json) {
                    $.each(json, function (key, value) {
                        if (key !== 'id_coordenador') {
                            $('#modal_form [name="' + key + '"]').val(value);
                        }
                    });
                    atualizar_estrutura(json.id_coordenador);

                    $('#modal_form').modal('show');

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_unidade_ensino(id) {
            $('#form_unidades')[0].reset();
            $('#form_unidades input[type="hidden"]').val('');
            $('#form_unidades .form-group').removeClass('has-error');
            $('#form_unidades .help-block').empty();

            $.ajax({
                url: '<?php echo site_url('cd/diretorias/ajax_unidades/') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    $('#unidade_contrato').text(data.cliente.contrato);
                    $('#unidade_cliente').text(data.cliente.nome);
                    $('#unidade_depto').text(data.cliente.depto);
                    $('#unidade_area').text(data.cliente.area);

                    $('#form_unidades [name="id_contrato"]').val(data.id_contrato);
                    $('#form_unidades #unidades').html(data.setores);
                    $('#modal_unidades').modal('show');

                    demo2.bootstrapDualListbox('refresh', true);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_servicos(id) {
            $('.form_servicos')[0].reset();
            $('.form_servicos')[1].reset();
            $('.form_servicos input[type="hidden"]').val('');
            $('.form_servicos .form-group').removeClass('has-error');
            $('.form_servicos .help-block').empty();

            $.ajax({
                url: '<?php echo site_url('cd/diretorias/ajax_servicos/') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    $('#servicos_contrato').text(data.contrato.contrato);
                    $('#servicos_cliente').text(data.contrato.nome);
                    $('#servicos_depto').text(data.contrato.depto);
                    $('#servicos_area').text(data.contrato.area);

                    $('.form_servicos [name="id_contrato"]').val(data.contrato.id);
                    $.each(data.compartilhados, function (i, v) {
                        $('#form_servicos_compartilhados [name="id[]"]:eq(' + i + ')').val(v.id);
                        $('#form_servicos_compartilhados [name="descricao[]"]:eq(' + i + ')').val(v.descricao);
                        $('#form_servicos_compartilhados [name="valor[]"]:eq(' + i + ')').val(v.valor);
                    });
                    $.each(data.nao_compartilhados, function (i, v) {
                        $('#form_servicos_nao_compartilhados [name="id[]"]:eq(' + i + ')').val(v.id);
                        $('#form_servicos_nao_compartilhados [name="descricao[]"]:eq(' + i + ')').val(v.descricao);
                        $('#form_servicos_nao_compartilhados [name="valor[]"]:eq(' + i + ')').val(v.valor);
                    });
                    $('#modal_servicos').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_reajuste(id) {
            $('#form_reajuste')[0].reset();
            $('#form_reajuste input[type="hidden"]').val('');

            $.ajax({
                url: '<?php echo site_url('cd/diretorias/ajax_reajuste/') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    $('#reajuste_contrato').text(data.cliente.contrato);
                    $('#reajuste_cliente').text(data.cliente.nome);
                    $('#reajuste_depto').text(data.cliente.depto);
                    $('#reajuste_area').text(data.cliente.area);

                    $('#id_cliente').val(data.cliente.id);
                    $.each(data.values, function (i, v) {
                        $('#form_reajuste [name="id[]"]:eq(' + i + ')').val(v.id);
                        $('#form_reajuste [name="data_reajuste[]"]:eq(' + i + ')').val(v.data_reajuste);
                        $('#form_reajuste [name="valor_indice[]"]:eq(' + i + ')').val(v.valor_indice);
                    });

                    $('#modal_reajuste').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function reload_table() {
            table.ajax.reload(null, false);
        }

        function save() {
            $('#btnSave').text('Salvando...');
            $('#btnSave').attr('disabled', true);
            var url;

            if (save_method === 'add') {
                url = '<?php echo site_url('cd/diretorias/ajax_add') ?>';
            } else {
                url = '<?php echo site_url('cd/diretorias/ajax_update') ?>';
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: $('#form').serialize(),
                dataType: 'JSON',
                success: function (data) {
                    if (data.status) {
                        $('#modal_form').modal('hide');
                        atualizarFiltro();
                    }

                    $('#btnSave').text('Salvar');
                    $('#btnSave').attr('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar');
                    $('#btnSave').attr('disabled', false);
                }
            });
        }

        function save_unidade_ensino() {
            $('#btnSaveUnidades').text('Salvando...');
            $('#btnSaveUnidades').attr('disabled', true);
            demo2.bootstrapDualListbox('refresh', true);

            $.ajax({
                url: '<?php echo site_url('cd/diretorias/save_unidades') ?>',
                type: 'POST',
                data: $('#form_unidades').serialize(),
                dataType: 'JSON',
                success: function (data) {
                    if (data.status) {
                        $('#modal_unidades').modal('hide');
                    }

                    $('#btnSaveUnidades').text('Salvar');
                    $('#btnSaveUnidades').attr('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveUnidades').text('Salvar');
                    $('#btnSaveUnidades').attr('disabled', false);
                }
            });
        }

        function save_servicos() {
            $('#btnSaveServicos').text('Salvando...');
            $('#btnSaveServicos').attr('disabled', true);

            $.ajax({
                url: '<?php echo site_url('cd/diretorias/save_servicos') ?>',
                type: 'POST',
                data: $('.form_servicos').serialize(),
                dataType: 'JSON',
                success: function (data) {
                    if (data.status) {
                        $('#modal_servicos').modal('hide');
                    }

                    $('#btnSaveServicos').text('Salvar');
                    $('#btnSaveServicos').attr('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveServicos').text('Salvar');
                    $('#btnSaveServicos').attr('disabled', false);
                }
            });
        }

        function save_reajuste() {
            $('#btnSaveReajuste').text('Salvando...');
            $('#btnSaveReajuste').attr('disabled', true);

            $.ajax({
                url: '<?php echo site_url('cd/diretorias/save_reajuste') ?>',
                type: 'POST',
                data: $('#form_reajuste').serialize(),
                dataType: 'JSON',
                success: function (data) {
                    if (data.status) {
                        $('#modal_reajuste').modal('hide');
                    }

                    $('#btnSaveReajuste').text('Salvar');
                    $('#btnSaveReajuste').attr('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveReajuste').text('Salvar');
                    $('#btnSaveReajuste').attr('disabled', false);
                }
            });
        }

        function delete_diretoria(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    url: '<?php echo site_url('cd/diretorias/ajax_delete') ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id},
                    success: function (data) {
                        $('#modal_form').modal('hide');
                        atualizarFiltro();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

    </script>

<?php
require_once APPPATH . "views/end_html.php";
?>