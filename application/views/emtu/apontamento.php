<?php require_once APPPATH . 'views/header.php'; ?>

    <style>
        #table_processing,
        #table_funcionarios_processing,
        #table_cuidadores_processing,
        #table_controle_materiais_processing,
        #table_colaboradores_processing {
            position: fixed;
            font-weight: bold;
            left: 56%;
            color: #c9302c;
            font-size: 16px;
        }

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

        .date-width {
            padding-right: 3px !important;
            padding-left: 3px !important;
            text-align: center;
            vertical-align: middle !important;
            white-space: normal;
        }

        .evento, .desconto_mes, .total_horas_mes {
            border-radius: 1px;
            border-width: 2px !important;
            border-style: outset solid solid outset !important;
        }

        .DTFC_RightWrapper {
            display: none;
        }

        .table_apontamento > tbody > tr > td {
            background-color: #fff;
        }

        .table_apontamento > tbody > tr > td:hover {
            background-color: #e8e8e8;
        }

        .table_apontamento > tbody > tr > td.colaborador-success,
        .table_apontamento > tbody > tr > td.date-width-success {
            color: #fff;
            background-color: #5cb85c !important;
        }

        .table_apontamento > tbody > tr > td.colaborador-success:hover,
        .table_apontamento > tbody > tr > td.date-width-success:hover {
            background-color: #47a447 !important;
        }

        .table_apontamento > tbody > tr > td.colaborador-primary,
        .table_apontamento > tbody > tr > td.date-width-primary {
            color: #fff;
            background-color: #337ab7 !important;
        }

        .table_apontamento > tbody > tr > td.colaborador-primary:hover,
        .table_apontamento > tbody > tr > td.date-width-primary:hover {
            background-color: #23527c;
        }

        .table_apontamento > tbody > tr > td.colaborador-info,
        .table_apontamento > tbody > tr > td.date-width-info {
            color: #fff;
            background-color: #5bc0de !important;
        }

        .table_apontamento > tbody > tr > td.colaborador-info:hover,
        .table_apontamento > tbody > tr > td.date-width-info:hover {
            background-color: #23527c;
        }

        .table_apontamento > tbody > tr > td.date-width-warning {
            /*color: #fff;*/
            background-color: #f0ad4e !important;
        }

        .table_apontamento > tbody > tr > td.date-width-warning:hover {
            background-color: #ed9c28 !important;
        }

        .table_apontamento > tbody > tr > td.date-width-danger {
            color: #fff;
            background-color: #d9534f !important;
        }

        .table_apontamento > tbody > tr > td.date-width-danger:hover {
            background-color: #d2322d !important;
        }

        #insumos .table tr td:first-child, insumos .table tr td:last-child {
            width: 50%;
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
                        <li class="active">EMTU - Apontamentos Diários</li>
                        <?php $this->load->view('modal_processos', ['url' => 'apontamento']); ?>
                    </ol>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <i class="glyphicon glyphicon-list-alt"></i> Gerenciar <span
                                            class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if ($this->session->userdata('nivel') != 11): ?>
                                        <li><a href="<?= site_url('colaboradores'); ?>"><i
                                                        class="glyphicon glyphicon-list text-primary"></i> Colaboradores</a>
                                        </li>
                                    <?php endif; ?>
                                    <li><a href="eventos"><i class="glyphicon glyphicon-list-alt text-primary"></i>
                                            Relatório de eventos</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6 right">
                            <p class="bg-info text-info" style="padding: 5px;">
                                <button style="float: right; margin: 12px 8px 0;" title="Pesquisa avançada"
                                        class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal_filtro">
                                    <i class="glyphicon glyphicon-filter"></i> <span class="hidden-xs">Filtrar</span>
                                </button>
                                <span>
                                <small>&emsp;<strong>Departamento:</strong> <span
                                            id="alerta_depto"><?= empty($depto_atual) ? 'Todos' : $deptos[$depto_atual] ?></span></small><br>
                                <small>&emsp;<strong>Área:</strong> <span
                                            id="alerta_area"><?= empty($area_atual) ? 'Todos' : $areas[$area_atual] ?></span></small><br>
                                <small>&emsp;<strong>Setor:</strong> <span
                                            id="alerta_setor"><?= empty($setor_atual) ? 'Todos' : $setor_atual ?></span></small><br>
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">
                            <span id="mes_ano"><?= $meses[date('m')] . ' ' . date('Y') ?></span>
                            <div style="float:right; margin-top: -0.5%;">
                                <button id="mes_anterior" title="Mês anterior" class="btn btn-info btn-sm"
                                        onclick="mes_anterior()">
                                    <i class="glyphicon glyphicon-arrow-left"></i> <span class="hidden-xs hidden-sm">Mês anterior</span>
                                </button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Opções do mês <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="javascript:void(0);" onclick="add_mes()"><i
                                                        class="glyphicon glyphicon-import text-success"></i> Alocar
                                                mês</a></li>
                                        <li><a href="javascript:void(0);" onclick="excluir_mes()"><i
                                                        class="glyphicon glyphicon-erase text-danger"></i> Limpar
                                                mês</a></li>
                                        <li><a href="#" data-toggle="modal" data-target="#modal_colaborador"><i
                                                        class="glyphicon glyphicon-plus text-info"></i> Alocar
                                                novo(a) colaborador(a)</a></li>
                                    </ul>
                                </div>
                                <button title="Mês seguinte" id="mes_seguinte" class="btn btn-info btn-sm"
                                        onclick="mes_seguinte()">
                                    <span class="hidden-xs hidden-sm">Mês seguinte</span> <i
                                            class="glyphicon glyphicon-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="panel-body">

                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" style="font-size: 13px; font-weight: bolder" class="active"><a
                                            href="#apontamento" aria-controls="apontamento" role="tab"
                                            data-toggle="tab">Apontamentos</a></li>
                                <li role="presentation" style="font-size: 13px; font-weight: bolder"><a
                                            href="#apontamento_consolidado" aria-controls="apontamento_consolidado"
                                            role="tab"
                                            data-toggle="tab">Apontamentos consolidados</a></li>
                            </ul>

                            <div class="tab-content" style="border: 1px solid #ddd; border-top-width: 0;">
                                <div role="tabpanel" class="tab-pane active" id="apontamento">
                                    <br>
                                    <table id="table"
                                           class="table table-hover table-striped table-condensed table-bordered"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" class="warning text-center">Colaborador(a)</th>
                                            <th rowspan="2" class="warning">Saldo</th>
                                            <td colspan="31" class="date-width" id="dias"><strong>Dias</strong></td>
                                        </tr>
                                        <tr>
                                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                                <?php if (date('N', mktime(0, 0, 0, date('m'), $i, date('Y'))) < 6): ?>
                                                    <th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                                                <?php else: ?>
                                                    <th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <br>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="apontamento_consolidado">
                                    <br>
                                    <table id="table_consolidados"
                                           class="table table-hover table-striped table-condensed table-bordered"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" class="warning text-center">Colaborador(a)</th>
                                            <th rowspan="2" class="warning">Req./Rev.</th>
                                            <td colspan="31" class="date-width" id="dias"><strong>Dias</strong></td>
                                            <th rowspan="2" class="warning">Total</th>
                                        </tr>
                                        <tr>
                                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                                <?php if (date('N', mktime(0, 0, 0, date('m'), $i, date('Y'))) < 6): ?>
                                                    <th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                                                <?php else: ?>
                                                    <th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <br>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- page end-->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_filtro" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Pesquisa avançada</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="busca" class="form-horizontal" autocomplete="off">
                                <input type="hidden" name="id_empresa" value="<?= $empresa ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Filtrar por departamento</label>
                                        <?php echo form_dropdown('id_depto', $deptos, $depto_atual, 'onchange="filtrar_alocacao()" class="form-control input-sm"'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Filtrar por área</label>
                                        <?php echo form_dropdown('id_area', $areas, $area_atual, 'onchange="filtrar_alocacao();" class="form-control input-sm"'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Filtrar por setor</label>
                                        <?php echo form_dropdown('id_setor', $setores, $setor_atual, 'class="form-control input-sm"'); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Mês</label>
                                        <?php echo form_dropdown('mes', $meses, date('m'), 'class="form-control input-sm"'); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Ano</label>
                                        <input name="ano" type="number" value="<?= date('Y') ?>" size="4"
                                               class="form-control input-sm" placeholder="aaaa">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveFiltro" onclick="filtrar()" class="btn btn-info"
                                    data-dismiss="modal">OK
                            </button>
                            <button type="button" id="limpar" class="btn btn-default">Limpar filtros</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Relatório de eventos</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_alocado"/>
                                <input type="hidden" value="" name="data"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-2"><strong>Colaborador(a):<br>Data:</strong></label>
                                    <div class="col-md-6">
                                        <p id="colaborador_data" class="form-control-static"></p>
                                    </div>
                                    <div class="col-sm-4 text-right text-nowrap">
                                        <button type="button" class="btn btn-success" id="btnSaveEvento"
                                                onclick="save_evento();"> Salvar
                                        </button>
                                        <button type="button" class="btn btn-danger" id="btnLimparEvento"
                                                onclick="delete_evento();"> Excluir
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Tipo de evento <span
                                                class="text-danger">*</span></label>
                                    <div class="col col-md-3">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_evento" value="FJ" checked>
                                                Falta com atestado próprio
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_evento" value="FN">
                                                Falta sem atestado
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_evento" value="FC">
                                                Falta combinada
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_evento" value="BH">
                                                Banco de horas
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col col-md-3">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_evento" value="AJ">
                                                Atraso com atestado próprio
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_evento" value="AS">
                                                Atraso sem atestado
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_evento" value="SJ">
                                                Saída antec. com atestado próprio
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_evento" value="SN">
                                                Saída antecipada sem atestado
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col col-md-4">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_evento" value="CO">
                                                Compensação (Trabalho dia de folga - MEI)
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_evento" value="EA">
                                                Entrada antecipada
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_evento" value="SP">
                                                Saída pós-horário
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_evento" value="HE">
                                                Hora extra (Trabalho dia de feriado - CLT)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Horário entrada</label>
                                    <div class="col-md-2">
                                        <input name="horario_entrada" type="text" value=""
                                               class="form-control text-center hora" placeholder="hh:mm">
                                    </div>
                                    <label class="control-label col-md-2">Horário saída intervalo</label>
                                    <div class="col-md-2">
                                        <input name="horario_intervalo" type="text" value=""
                                               class="form-control text-center hora" placeholder="hh:mm">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Horário retorno intervalo</label>
                                    <div class="col-md-2">
                                        <input name="horario_retorno" type="text" value=""
                                               class="form-control text-center hora" placeholder="hh:mm">
                                    </div>
                                    <label class="control-label col-md-2">Horário saída</label>
                                    <div class="col-md-2">
                                        <input name="horario_saida" type="text" value=""
                                               class="form-control text-center hora" placeholder="hh:mm">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Banco de horas</label>
                                    <div class="col-md-2">
                                        <input name="banco_horas" type="text" value=""
                                               class="form-control text-center hora" placeholder="hh:mm">
                                    </div>
                                    <label class="control-label col-md-2">Observações</label>
                                    <div class="col-md-5">
                                        <textarea name="observacoes" class="form-control"
                                                  rows="3"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_avaliado" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Avaliação de performance</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_avaliado" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-2"><strong>Colaborador(a):<br>Mês/ano:</strong></label>
                                    <div class="col-md-7">
                                        <p id="form_avaliado_dados" class="form-control-static"></p>
                                    </div>
                                    <div class="col-sm-3 text-right text-nowrap">
                                        <button type="button" class="btn btn-success" id="btnSaveAvaliado"
                                                onclick="save_avaliacao_performance();"> Salvar
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <label class="control-label col-md-2"><strong>Legenda:</strong></label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"><?= $nivel_performance; ?>.</p>
                                    </div>
                                </div>
                                <br>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Comprometimento</label>
                                    <div class="col-md-2">
                                        <select name="comprometimento" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1">Simpatia</label>
                                    <div class="col-md-2">
                                        <select name="simpatia" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1">Script</label>
                                    <div class="col-md-2">
                                        <select name="script" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Pontualidade</label>
                                    <div class="col-md-2">
                                        <select name="pontualidade" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1">Empatia</label>
                                    <div class="col-md-2">
                                        <select name="empatia" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1">Postura</label>
                                    <div class="col-md-2">
                                        <select name="postura" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Ferramenta</label>
                                    <div class="col-md-2">
                                        <select name="ferramenta" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1">Tradutório</label>
                                    <div class="col-md-2">
                                        <select name="tradutorio" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1">Linguístico</label>
                                    <div class="col-md-2">
                                        <select name="linguistico" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Neutralidade</label>
                                    <div class="col-md-2">
                                        <select name="neutralidade" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1">Discrição</label>
                                    <div class="col-md-2">
                                        <select name="discricao" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1">Fidelidade</label>
                                    <div class="col-md-2">
                                        <select name="fidelidade" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Extra 1</label>
                                    <div class="col-md-2">
                                        <select name="extra_1" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1">Extra 2</label>
                                    <div class="col-md-2">
                                        <select name="extra_2" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1">Extra 3</label>
                                    <div class="col-md-2">
                                        <select name="extra_3" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_banco_horas" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Ajuste de Banco de Horas</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_banco_horas" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id_usuario"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-5"><strong>Colaborador(a):<br>Banco de horas na
                                            data atual:</strong></label>
                                    <div class="col-md-6">
                                        <p id="form_banco_horas_dados" class="form-control-static"></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Banco de horas</label>
                                    <div class="col-md-3">
                                        <input type="text" name="banco_horas" class="form-control text-center horas"
                                               placeholder="hhh:mm">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btnSaveBancoHoras"
                                    onclick="save_banco_horas();">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_posto" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar posto</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_posto" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Departamento</label>
                                    <div class="col-md-8">
                                        <select name="id_depto" class="form-control posto_estrutura"
                                                onchange="filtrar_posto_estrutura(this)">
                                            <option value="">selecione...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Área</label>
                                    <div class="col-md-8">
                                        <select name="id_area" class="form-control posto_estrutura"
                                                onchange="filtrar_posto_estrutura(this)">
                                            <option value="">selecione...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Setor</label>
                                    <div class="col-md-8">
                                        <select name="id_setor" class="form-control posto_estrutura"
                                                onchange="filtrar_posto_estrutura(this)">
                                            <option value="">selecione...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Colaborador(a)</label>
                                    <div class="col-md-8">
                                        <select name="id_usuario" class="form-control posto_estrutura"
                                                onchange="filtrar_alocado();">
                                            <option value="">selecione...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Função</label>
                                    <div class="col-md-8">
                                        <select name="id_funcao" class="form-control posto_estrutura">
                                            <option value="">selecione...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Matrícula</label>
                                    <div class="col-md-4">
                                        <input type="text" name="matricula" class="form-control">
                                    </div>
                                    <label class="control-label col-md-2">CLT/MEI</label>
                                    <div class="col-md-2">
                                        <select name="categoria" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="CLT">CLT</option>
                                            <option value="MEI">MEI</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <h5>Colaborador MEI</h5>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Valor hora colaborador</label>
                                    <div class="col-md-3" style="width: 200px;">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">R$</span>
                                            <input name="valor_hora_mei" type="text" class="form-control valor mei"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-2">Qtde. horas/mês</label>
                                    <div class="col-md-2" style="width: 120px;">
                                        <input name="qtde_horas_mei" type="text"
                                               class="form-control text-center hora mei"
                                               placeholder="hh:mm">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-2">Qtde. horas/dia</label>
                                    <div class="col-md-2" style="width: 120px;">
                                        <input name="qtde_horas_dia_mei" type="text"
                                               class="form-control text-center hora mei"
                                               placeholder="hh:mm">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <hr>
                                <h5>Colaborador CLT</h5>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Valor remuneração mensal</label>
                                    <div class="col-md-3" style="width: 200px;">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">R$</span>
                                            <input name="valor_mes_clt" type="text" class="form-control valor clt"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-2">Qtde. horas/mês</label>
                                    <div class="col-md-2" style="width: 120px;">
                                        <input name="qtde_meses_clt" type="text"
                                               class="form-control text-center hora clt"
                                               placeholder="hh:mm">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-2">Qtde. horas/dia</label>
                                    <div class="col-md-2" style="width: 120px;">
                                        <input name="qtde_horas_dia_clt" type="text"
                                               class="form-control text-center hora clt"
                                               placeholder="hh:mm">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Horário entrada</label>
                                    <div class="col-md-2">
                                        <input name="horario_entrada" type="text" value=""
                                               class="form-control text-center hora" placeholder="hh:mm">
                                    </div>
                                    <label class="control-label col-md-3">Horário saída intervalo</label>
                                    <div class="col-md-2">
                                        <input name="horario_intervalo" type="text" value=""
                                               class="form-control text-center hora" placeholder="hh:mm">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Horário entrada intervalo</label>
                                    <div class="col-md-2">
                                        <input name="horario_retorno" type="text" value=""
                                               class="form-control text-center hora" placeholder="hh:mm">
                                    </div>
                                    <label class="control-label col-md-3">Horário saída</label>
                                    <div class="col-md-2">
                                        <input name="horario_saida" type="text" value=""
                                               class="form-control text-center hora" placeholder="hh:mm">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btnSavePosto" onclick="save_posto();">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_alocados" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Alocar novo(s) colaborador(es)</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_alocados" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id_alocacao"/>
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <?php echo form_multiselect('id_usuario[]', [], [], 'id="alocados" class="demo1" size="8"'); ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btnSaveAlocados"
                                    onclick="save_novo_alocado();"> Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        </section>
    </section>
    <!--main content end-->

<?php require_once APPPATH . 'views/end_js.php'; ?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gestão Operacional EMTU';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

    <script>

        var table, table_consolidados;
        var busca, save_method, demo1;
        var edicaoEvento = true;

        $('.data').mask('00/00/0000');
        $('.hora').mask('00:00');
        $('.horas').mask('#00:00', {'reverse': true});
        $('.valor').mask('##.###.##0,00', {'reverse': true});


        $(document).ready(function () {
            busca = $('#busca').serialize();
            var language = "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>";


            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'order': [0, 'asc'],
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, 500, -1], [5, 10, 25, 50, 100, 500, 'Todos']],
                'language': {
                    'url': language
                },
                'ajax': {
                    'url': '<?php echo site_url('emtu/apontamento/listarEventos') ?>',
                    'type': 'POST',
                    'timeout': 90000,
                    'data': function (d) {
                        d.busca = busca;
                        return d;
                    },
                    'dataSrc': function (json) {
                        $('#mes_ano').html(json.calendar.mes_ano[0].toUpperCase() + json.calendar.mes_ano.slice(1));

                        var dt1 = new Date();
                        var dt2 = new Date();
                        dt2.setFullYear(json.calendar.ano, (json.calendar.mes - 1));

                        var semana = 1;
                        var colunasUsuario = 1;
                        for (i = 1; i <= 31; i++) {
                            if (i > 28) {
                                if (i > json.calendar.qtde_dias) {
                                    table.column(i + colunasUsuario).visible(false, false);
                                    continue;
                                } else {
                                    table.column(i + colunasUsuario).visible(true, false);
                                }
                            }
                            var coluna = $(table.columns(i + colunasUsuario).header());
                            coluna.removeClass('text-danger').css('background-color', '');
                            coluna.attr({
                                'data-dia': json.calendar.ano + '-' + json.calendar.mes + '-' + coluna.text(),
                                'data-mes_ano': json.calendar.semana[semana] + ', ' + coluna.text() + '/' + json.calendar.mes + '/' + json.calendar.ano,
                                'title': json.calendar.semana[semana] + ', ' + coluna.text() + ' de ' + json.calendar.mes_ano.replace(' ', ' de ')
                            });
                            if (json.calendar.semana[semana] === 'Sábado' || json.calendar.semana[semana] === 'Domingo') {
                                coluna.addClass('text-danger').css('background-color', '#dbdbdb');
                            }
                            if ((dt1.getTime() === dt2.getTime()) && dt1.getDate() === i) {
                                coluna.css('background-color', '#0f0');
                            }
                            if (i % 7 === 0) {
                                semana = 1;
                            } else {
                                semana++;
                            }
                            if (json.data.length > 0) {
                                coluna.css('cursor', 'pointer').on('click', function () {
                                    $('#data_evento').html(this.dataset.mes_ano);
                                    $('#form_eventos [name="data"]').val(this.dataset.dia);
                                    $('#modal_eventos').modal('show');
                                });
                            }
                        }
                        if (json.data.length > 0) {
                            $('#dias').html('<strong>Dias</strong> (clique em um dia do mês para replicar/limpar feriados ou emendas de feriados)');
                        } else {
                            $('#dias').html('<strong>Dias</strong>');
                        }
                        if (json.draw === 1) {
                            $("#legenda").html('<button title="Mostrar legenda de eventos" data-toggle="modal" data-target="#modal_legenda" style="margin: 15px 10px 0;" class="btn btn-default btn-sm">' +
                                '<i class="glyphicon glyphicon-exclamation-sign"></i> <span class="hidden-xs"> Mostrar legenda de eventos</span>' +
                                '</button>');
                        }
                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).addClass('evento').css({
                                'cursor': 'pointer',
                                'vertical-align': 'middle'
                            }).on('click', function () {
                                edit_avaliacao_performance(rowData[33]);
                            });
                            $(td).html(rowData[col]);
                        },
                        'width': '100%',
                        'targets': [1]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).addClass('evento').css({
                                'cursor': 'pointer',
                                'vertical-align': 'middle'
                            }).on('click', function () {
                                edit_banco_horas(rowData[33]);
                            });
                            $(td).html(rowData[col]);
                        },
                        'className': 'text-center text-nowrap',
                        'targets': [4]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col]) {
                                $(td).css({'color': '#fff', 'background-color': '#47a447'});
                            } else if ($(table.column(col).header()).hasClass('text-danger')) {
                                $(td).css('background-color', '#e9e9e9');
                            }

                            $(td).popover({
                                'container': 'body',
                                'placement': 'auto bottom',
                                'trigger': 'hover',
                                'content': function () {
                                    if (rowData[col].length === 0) {
                                        return '<span style="color: #aaa;">Vazio</span>';
                                    } else {
                                        return '<strong>Eventos cadastrados:</strong> ' + rowData[col]['tipo_evento'];
                                    }
                                },
                                'html': true
                            });
                            $(td).addClass('evento').css({
                                'cursor': 'pointer',
                                'vertical-align': 'middle'
                            }).on('click', function () {
                                $(td).popover('hide');
                                edit_evento(rowData[33], col - 2);
                            });
                            $(td).html(rowData[col]['tipo_evento']);
                        },
                        'className': 'text-center',
                        'orderable': false,
                        'searchable': false,
                        'targets': 'date-width'
                    }
                ]
            });

            table_consolidados = $('#table_consolidados').DataTable({
                'processing': true,
                'serverSide': true,
                'order': [0, 'asc'],
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, 500, -1], [5, 10, 25, 50, 100, 500, 'Todos']],
                'language': {
                    'url': language
                },
                'ajax': {
                    'url': '<?php echo site_url('emtu/apontamento/listarEventosConsolidados') ?>',
                    'type': 'POST',
                    'timeout': 90000,
                    'data': function (d) {
                        d.busca = busca;
                        return d;
                    },
                    'dataSrc': function (json) {
                        $('#mes_ano').html(json.calendar.mes_ano[0].toUpperCase() + json.calendar.mes_ano.slice(1));

                        var dt1 = new Date();
                        var dt2 = new Date();
                        dt2.setFullYear(json.calendar.ano, (json.calendar.mes - 1));

                        var semana = 1;
                        var colunasUsuario = 1;
                        for (i = 1; i <= 31; i++) {
                            if (i > 28) {
                                if (i > json.calendar.qtde_dias) {
                                    table_consolidados.column(i + colunasUsuario).visible(false, false);
                                    continue;
                                } else {
                                    table_consolidados.column(i + colunasUsuario).visible(true, false);
                                }
                            }
                            var coluna = $(table_consolidados.columns(i + colunasUsuario).header());
                            coluna.removeClass('text-danger').css('background-color', '');
                            coluna.attr({
                                'data-dia': json.calendar.ano + '-' + json.calendar.mes + '-' + coluna.text(),
                                'data-mes_ano': json.calendar.semana[semana] + ', ' + coluna.text() + '/' + json.calendar.mes + '/' + json.calendar.ano,
                                'title': json.calendar.semana[semana] + ', ' + coluna.text() + ' de ' + json.calendar.mes_ano.replace(' ', ' de ')
                            });
                            if (json.calendar.semana[semana] === 'Sábado' || json.calendar.semana[semana] === 'Domingo') {
                                coluna.addClass('text-danger').css('background-color', '#dbdbdb');
                            }
                            if ((dt1.getTime() === dt2.getTime()) && dt1.getDate() === i) {
                                coluna.css('background-color', '#0f0');
                            }
                            if (i % 7 === 0) {
                                semana = 1;
                            } else {
                                semana++;
                            }
                            if (json.data.length > 0) {
                                coluna.css('cursor', 'pointer').on('click', function () {
                                    $('#data_evento').html(this.dataset.mes_ano);
                                    $('#form_eventos [name="data"]').val(this.dataset.dia);
                                    $('#modal_eventos').modal('show');
                                });
                            }
                        }
                        if (json.data.length > 0) {
                            $('#dias').html('<strong>Dias</strong> (clique em um dia do mês para replicar/limpar feriados ou emendas de feriados)');
                        } else {
                            $('#dias').html('<strong>Dias</strong>');
                        }
                        if (json.draw === 1) {
                            $("#legenda").html('<button title="Mostrar legenda de eventos" data-toggle="modal" data-target="#modal_legenda" style="margin: 15px 10px 0;" class="btn btn-default btn-sm">' +
                                '<i class="glyphicon glyphicon-exclamation-sign"></i> <span class="hidden-xs"> Mostrar legenda de eventos</span>' +
                                '</button>');
                        }
                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).addClass('evento').css({
                                'cursor': 'pointer',
                                'vertical-align': 'middle'
                            }).on('click', function () {
                                edit_avaliacao_performance(rowData[33]);
                            });
                            $(td).html(rowData[col]);
                        },
                        'width': '100%',
                        'targets': [1]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).addClass('evento').css({
                                'cursor': 'pointer',
                                'vertical-align': 'middle'
                            }).on('click', function () {
                                edit_banco_horas(rowData[33]);
                            });
                            $(td).html(rowData[col]);
                        },
                        'className': 'text-center text-nowrap',
                        'targets': [4]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col]) {
                                $(td).css({'color': '#fff', 'background-color': '#47a447'});
                            } else if ($(table_consolidados.column(col).header()).hasClass('text-danger')) {
                                $(td).css('background-color', '#e9e9e9');
                            }

                            $(td).popover({
                                'container': 'body',
                                'placement': 'auto bottom',
                                'trigger': 'hover',
                                'content': function () {
                                    if (rowData[col].length === 0) {
                                        return '<span style="color: #aaa;">Vazio</span>';
                                    } else {
                                        return '<strong>Eventos cadastrados:</strong> ' + rowData[col]['tipo_evento'];
                                    }
                                },
                                'html': true
                            });
                            $(td).addClass('evento').css({
                                'cursor': 'pointer',
                                'vertical-align': 'middle'
                            }).on('click', function () {
                                $(td).popover('hide');
                                edit_evento(rowData[33], col - 2);
                            });
                            $(td).html(rowData[col]['tipo_evento']);
                        },
                        'className': 'text-center',
                        'orderable': false,
                        'searchable': false,
                        'targets': 'date-width'
                    }
                ]
            });

            demo1 = $('.demo1').bootstrapDualListbox({
                'nonSelectedListLabel': 'Colaboradores disponíveis',
                'selectedListLabel': 'Colaboradores selecionados',
                'preserveSelectionOnMove': 'moved',
                'moveOnSelect': false,
                'filterPlaceHolder': 'Filtrar',
                'helperSelectNamePostfix': false,
                'selectorMinimalHeight': 132,
                'infoText': false
            });

        });


        $('#form_posto [name="categoria"]').on('change', function () {
            $('#form_posto .clt, #form_posto .mei').prop('disabled', true);
            if (this.value === 'MEI') {
                $('#form_posto .mei').prop('disabled', false);
                $('#form_posto .clt').val('');
            } else if (this.value === 'CLT') {
                $('#form_posto .clt').prop('disabled', false);
                $('#form_posto .mei').val('');
            }
        });


        function filtrar_alocacao() {
            $.ajax({
                'url': '<?php echo site_url('apontamento/filtrarAlocacao/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize(),
                'beforeSend': function () {
                    $('#busca [name="id_depto"]').prop('disabled', true);
                    $('#busca [name="id_area"]').prop('disabled', true);
                    $('#busca [name="id_setor"]').prop('disabled', true);
                },
                'success': function (json) {
                    $('#busca [name="id_depto"]').prop('disabled', false);
                    $('#busca [name="id_area"]').html($(json.areas).html()).prop;
                    $('#busca [name="id_setor"]').html($(json.setores).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                },
                'complete': function () {
                    $('#busca [name="id_depto"]').prop('disabled', false);
                    $('#busca [name="id_area"]').prop('disabled', false);
                    $('#busca [name="id_setor"]').prop('disabled', false);
                }
            });
        }


        function filtrar_posto_estrutura() {
            $.ajax({
                'url': '<?php echo site_url('postos/montarEstrutura') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_posto .posto_estrutura').serialize(),
                'beforeSend': function () {
                    $('.posto_estrutura').prop('disabled', true);
                },
                'success': function (json) {
                    $('#form_posto [name="id_area"]').html($(json.areas).html());
                    $('#form_posto [name="id_setor"]').html($(json.setores).html());
                    $('#form_posto [name="id_usuario"]').html($(json.usuarios).html());
                    $('#form_posto [name="id_funcao"]').html($(json.funcoes).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                },
                'complete': function () {
                    $('.posto_estrutura').prop('disabled', false);
                }
            });
        }


        function filtrar_alocado() {
            $.ajax({
                'url': '<?php echo site_url('postos/editarColaboradorAlocado') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_posto .posto_estrutura, #busca [name="mes"], #busca [name="ano"]').serialize(),
                'beforeSend': function () {
                    $('.posto_estrutura').prop('disabled', true);
                },
                'success': function (json) {
                    $('#form_posto [name="valor_hora_mei"]').val('');
                    $('#form_posto [name="valor_mes_clt"]').val('');
                    $('#form_posto [name="qtde_horas_mei"]').val('');
                    $('#form_posto [name="qtde_meses_clt"]').val('');
                    $('#form_posto [name="horario_entrada"]').val('');
                    $('#form_posto [name="horario_intervalo"]').val('');
                    $('#form_posto [name="horario_retorno"]').val('');
                    $('#form_posto [name="horario_saida"]').val('');
                    $.each(json, function (key, value) {
                        $('#form_posto [name="' + key + '"]').val(value);
                    });

                    $('#form_posto [name="categoria"]').trigger('change');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                },
                'complete': function () {
                    $('.posto_estrutura').prop('disabled', false);
                }
            });
        }


        $('#limpar').on('click', function () {
            $.each(busca.split('&'), function (index, elem) {
                var vals = elem.split('=');
                if (vals[0] === 'mes' || vals[0] === 'ano') {
                    $("[name='" + vals[0] + "']").val(vals[1]);
                } else {
                    $("[name='" + vals[0] + "']").val($("[name='" + vals[0] + "'] option:first").val());
                }
            });

            filtrar_alocacao();
        });


        function mes_anterior() {
            $('#mes_anterior, #mes_seguinte').prop('disabled', true).hover();

            var dt = moment({
                'year': $('#busca [name="ano"]').val(),
                'month': $('#busca [name="mes"]').val() - 1,
                'day': 1
            });

            dt.subtract(1, 'month');

            $('#busca [name="mes"]').val(moment(dt).format('MM'));
            $('#busca [name="ano"]').val(dt.year());

            busca = $('#busca').serialize();
            reload_table(true);
            $('#mes_anterior, #mes_seguinte').prop('disabled', false);
        }


        function mes_seguinte() {
            if ($('#mes_seguinte').hasClass('disabled')) {
                return false;
            }

            $('#mes_anterior, #mes_seguinte').prop('disabled', true).hover();

            var dt = moment({
                'year': $('#busca [name="ano"]').val(),
                'month': $('#busca [name="mes"]').val() - 1,
                'day': 1
            });

            dt.add(1, 'month');

            $('#busca [name="mes"]').val(moment(dt).format('MM'));
            $('#busca [name="ano"]').val(dt.year());

            busca = $('#busca').serialize();
            reload_table(true);
            $('#mes_anterior, #mes_seguinte').prop('disabled', false);
        }


        function filtrar() {
            var data_busca = {
                'year': $('#busca [name="ano"]').val(),
                'month': $('#busca [name="mes"]').val() - 1,
                'day': 1
            };
            var data_proximo_mes = moment(data_busca).add(1, 'month');

            busca = $('#busca').serialize();
            reload_table();
            if (moment(data_proximo_mes).isBefore(data_busca)) {
                $('[name="mes"]').val(moment(data_proximo_mes).format('MM'));
                $('[name="ano"]').val(data_proximo_mes.year());
            }
            $('#alerta_depto').text($('#busca [name="id_depto"] option:selected').html());
            $('#alerta_area').text($('#busca [name="id_area"] option:selected').html());
            $('#alerta_setor').text($('#busca [name="id_setor"] option:selected').html());
        }


        function alocacao_filtrada() {
            return busca.split('&').every(function (e) {
                return e.indexOf('=') < (e.length - 1);
            });
        }


        function iniciar_mes() {
            if (alocacao_filtrada() === false) {
                alert('Para iniciar o mês, ajuste os filtros de Departamento, Área e Setor.');
                return false;
            }

            $.ajax({
                'url': '<?php echo site_url('apontamento/alocarNovoMes') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': busca,
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        alert('Mês alocado com sucesso.');
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function limpar_mes() {
            if (alocacao_filtrada() === false) {
                alert('Para limpar o mês, ajuste os filtros de Departamento, Área e Setor.');
                return false;
            }

            if (confirm('Deseja limpar o mês?')) {
                $.ajax({
                    'url': '<?php echo site_url('apontamento/desalocarMes') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': busca,
                    'success': function (json) {
                        if (json.erro) {
                            alert(json.erro);
                        } else {
                            alert('Mês desalocado com sucesso.');
                            reload_table();
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
            }
        }


        function edit_avaliacao_performance(id_alocado) {
            $.ajax({
                'url': '<?php echo site_url('apontamento/editarAvaliacaoPerformance') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id_alocado': id_alocado},
                'beforeSend': function () {
                    $('#form_avaliado')[0].reset();
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $.each(json, function (key, value) {
                        if ($('#form_avaliado [name="' + key + '"]').prop('type') === 'radio') {
                            $('#form_avaliado [name="' + key + '"][value="' + value + '"]').prop('checked', value !== null);
                        } else {
                            $('#form_avaliado [name="' + key + '"]').val(value);
                        }
                    });

                    $('#form_avaliado_dados').html(json.dados);

                    $('#modal_avaliado').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_banco_horas(id_alocado) {
            $.ajax({
                'url': '<?php echo site_url('apontamento/editarBancoHoras') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id_alocado': id_alocado},
                'beforeSend': function () {
                    $('#form_banco_horas')[0].reset();
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $.each(json, function (key, value) {
                        if ($('#form_banco_horas [name="' + key + '"]').prop('type') === 'radio') {
                            $('#form_banco_horas [name="' + key + '"][value="' + value + '"]').prop('checked', value !== null);
                        } else {
                            $('#form_banco_horas [name="' + key + '"]').val(value);
                        }
                    });

                    $('#form_banco_horas_dados').html(json.dados);

                    $('#modal_banco_horas').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_evento(id_alocado, dia) {
            $.ajax({
                'url': '<?php echo site_url('apontamento/editarEvento') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_alocado': id_alocado,
                    'data': moment({
                        'year': $('#busca [name="ano"]').val(),
                        'month': $('#busca [name="mes"]').val() - 1,
                        'day': dia
                    }).format('YYYY-MM-DD')
                },
                'beforeSend': function () {
                    $('#form')[0].reset();
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $.each(json, function (key, value) {
                        if ($('#form [name="' + key + '"]').prop('type') === 'radio') {
                            $('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value !== null);
                        } else {
                            $('#form [name="' + key + '"]').val(value);
                        }
                    });

                    if (json.id) {
                        save_method = 'update';
                        $('#modal_form .modal-title').text('Editar evento operacional');
                        $('#btnLimparEvento').show();
                    } else {
                        save_method = 'add';
                        $('#modal_form .modal-title').text('Adicionar evento operacional');
                        $('#btnLimparEvento').hide();
                    }

                    $('#colaborador_data').html(json.colaborador_data);

                    $('#modal_form').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_posto() {
            $('#form_posto')[0].reset();

            $.ajax({
                'url': '<?php echo site_url('apontamento/editarPosto') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': busca,
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $.each(json, function (key, value) {
                        $('#form_posto [name="' + key + '"]').val(value);
                    });

                    $('#form_posto [name="id_depto"]').html($(json.deptos).html());
                    $('#form_posto [name="id_area"]').html($(json.areas).html());
                    $('#form_posto [name="id_setor"]').html($(json.setores).html());


                    $('#form_posto [name="categoria"]').trigger('change');

                    $('#modal_posto').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function preparar_novo_alocado() {
            if ($('#busca [name="id_depto"]').val() === '' || $('#busca [name="id_area"]').val() === '' ||
                $('#busca [name="id_setor"]').val() === '' || $('#busca [name="ano"]').val() === '') {
                alert('Para alocar um novo cloadorador, ajuste os filtros de Departamento, Área, Setor, Mês e Ano.');
                return false;
            }

            $('#form_alocados')[0].reset();

            $.ajax({
                'url': '<?php echo site_url('apontamento/prepararNovoAlocado') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': busca,
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $('#form_alocados [name="id_alocacao"]').val(json.id_alocacao);
                    $('#alocados').html($(json.id_usuario).html());

                    demo1.bootstrapDualListbox('refresh', true);

                    $('#modal_alocados').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function save_avaliacao_performance() {
            $.ajax({
                'url': '<?php echo site_url('apontamento/salvarAvaliacaoPerformance') ?>',
                'type': 'POST',
                'data': $('#form_avaliado').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveAvaliado').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_avaliado').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveAvaliado').text('Salvar').attr('disabled', false);
                }
            });
        }


        function save_banco_horas() {
            $.ajax({
                'url': '<?php echo site_url('apontamento/salvarBancoHoras') ?>',
                'type': 'POST',
                'data': $('#form_banco_horas').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveBancoHoras').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_banco_horas').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveBancoHoras').text('Salvar').attr('disabled', false);
                }
            });
        }


        function save_evento() {
            $.ajax({
                'url': '<?php echo site_url('apontamento/salvarEvento') ?>',
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveEvento').text('Salvando...');
                    $('#btnSaveEvento, #btnLimparEvento').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveEvento').text('Salvar');
                    $('#btnSaveEvento, #btnLimparEvento').attr('disabled', false);
                }
            });
        }


        function save_posto() {
            $.ajax({
                'url': '<?php echo site_url('postos/salvar') ?>',
                'type': 'POST',
                'data': $('#form_posto').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSavePosto').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_posto').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSavePosto').text('Salvar').attr('disabled', false);
                }
            });
        }


        function save_novo_alocado() {
            $.ajax({
                'url': '<?php echo site_url('apontamento/salvarNovoAlocado') ?>',
                'type': 'POST',
                'data': $('#form_alocados').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveAlocados').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_alocados').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveAlocados').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_evento() {
            if (confirm('Deseja limpar o evento?')) {
                $.ajax({
                    'url': '<?php echo site_url('apontamento/excluirEvento') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {
                        'id': $('#form [name="id"]').val()
                    },
                    'beforeSend': function () {
                        $('#btnLimparEvento').text('Excluindo...');
                        $('#btnLimparEvento, #btnSaveEvento').attr('disabled', true);
                    },
                    'success': function (json) {
                        if (json.status) {
                            $('#modal_form').modal('hide');
                            reload_table();
                        } else if (json.erro) {
                            alert(json.erro);
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    },
                    'complete': function () {
                        $('#btnLimparEvento').text('Excluir');
                        $('#btnLimparEvento, #btnSaveEvento').attr('disabled', false);
                    }
                });
            }
        }


        function delete_alocado(id) {
            if (confirm('Deseja desalocado o(a) colaborador(a)?')) {
                $.ajax({
                    'url': '<?php echo site_url('apontamento/excluirAlocado') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'beforeSend': function () {
                        $('#table tr td button.danger').attr('disabled', true);
                    },
                    'success': function (json) {
                        if (json.status) {
                            reload_table();
                        } else if (json.erro) {
                            alert(json.erro);
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    },
                    'complete': function () {
                        $('#table tr td button.danger').attr('disabled', false);
                    }
                });
            }
        }


        function reload_table(reset = false) {
            edicaoEvento = false;
            $('#mes_ano').append('&ensp;(Processando - Aguarde...)');
            var count = 0;
            var stmt = function (json) {
                count = count + 1;
                if (count === 3) {
                    edicaoEvento = true;
                }
            };
            table.ajax.reload(stmt, reset);
            table_totalizacao.ajax.reload(stmt, reset);
            table_avaliacao_performance.ajax.reload(stmt, reset);
            // table_totalizacao.ajax.reload(stmt, reset);
            // table_colaboradores.ajax.reload(stmt, reset);
        }


        function imprimir_totalizacao() {
            if ($('#busca [name="id_depto"]').val() === '' || $('#busca [name="id_area"]').val() === '' ||
                $('#busca [name="id_setor"]').val() === '' || $('#busca [name="ano"]').val() === '') {
                alert('Para gerar o relatório, ajuste os filtros de Departamento, Área, Setor, Mês e Ano.');
                return false;
            }

            var q = new Array();
            q.push("id_depto=" + $('#busca [name="id_depto"]').val());
            q.push("id_area=" + $('#busca [name="id_area"]').val());
            q.push("id_setor=" + $('#busca [name="id_setor"]').val());
            q.push("mes=" + $('#busca [name="mes"]').val());
            q.push("ano=" + $('#busca [name="ano"]').val());

            window.open('<?php echo site_url('apontamento/pdfTotalizacao'); ?>/q?' + q.join('&'), '_blank');
        }


        function imprimir_avaliacao_performance() {
            if ($('#busca [name="id_depto"]').val() === '' || $('#busca [name="id_area"]').val() === '' ||
                $('#busca [name="id_setor"]').val() === '' || $('#busca [name="ano"]').val() === '') {
                alert('Para gerar o relatório, ajuste os filtros de Departamento, Área, Setor, Mês e Ano.');
                return false;
            }

            var q = new Array();
            q.push("id_depto=" + $('#busca [name="id_depto"]').val());
            q.push("id_area=" + $('#busca [name="id_area"]').val());
            q.push("id_setor=" + $('#busca [name="id_setor"]').val());
            q.push("mes=" + $('#busca [name="mes"]').val());
            q.push("ano=" + $('#busca [name="ano"]').val());

            window.open('<?php echo site_url('apontamento/pdfAvaliacaoPerformance'); ?>/q?' + q.join('&'), '_blank');
        }

    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>