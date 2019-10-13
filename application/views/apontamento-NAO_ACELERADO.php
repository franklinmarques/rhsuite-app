<?php
require_once "header.php";
?>
    <style>
        #table_processing,
        #table_totalizacao_processing,
        #table_colaboradores_processing,
        #table_apontamento_consolidado_processing,
        #table_totalizacao_consolidada_processing {
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
            white-space: normal;
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

        .table_apontamento > tbody > tr > td.colaborador-success {
            color: #fff;
            background-color: #5cb85c !important;
        }

        .table_apontamento > tbody > tr > td.date-width-success {
            color: #5cb85c;
            font-weight: bolder;
        }

        .table_apontamento > tbody > tr > td.colaborador-success:hover {
            background-color: #47a447 !important;
        }

        .table_apontamento > tbody > tr > td.date-width-success:hover {
            color: #47a447 !important;
            font-weight: bolder;
        }

        .table_apontamento > tbody > tr > td.colaborador-primary,
        .table_apontamento > tbody > tr > td.date-width-primary {
            color: #fff;
            background-color: #027EEA !important;
            font-weight: bolder;
        }

        .table_apontamento > tbody > tr > td.colaborador-primary:hover,
        .table_apontamento > tbody > tr > td.date-width-primary:hover {
            background-color: #007EEB;
        }

        .table_apontamento > tbody > tr > td.colaborador-disabled,
        .table_apontamento > tbody > tr > td.date-width-disabled {
            color: #fff;
            background-color: #5C679A !important;
        }

        .table_apontamento > tbody > tr > td.colaborador-disabled:hover,
        .table_apontamento > tbody > tr > td.date-width-disabled:hover {
            background-color: #576192;
        }

        .table_apontamento > tbody > tr > td.date-width-warning {
            /*color: #fff;
            background-color: #f0ad4e !important; */
            color: #f0ad4e;
            font-weight: bolder;
        }

        .table_apontamento > tbody > tr > td.date-width-warning:hover {
            /*background-color: #ed9c28 !important;*/
            color: #ed9c28 !important;
            font-weight: bolder;
        }

        .table_apontamento > tbody > tr > td.date-width-danger {
            /*color: #fff;
            background-color: #d9534f !important;*/
            color: #d9534f;
            font-weight: bolder;
        }

        .table_apontamento > tbody > tr > td.date-width-danger:hover {
            /*background-color: #d2322d !important;*/
            color: #d2322d !important;
            font-weight: bolder;
        }

        .table_apontamento > tbody > tr > td.date-width-disabled {
            color: #fff;
            background-color: #8866bb !important;
        }

        .table_apontamento > tbody > tr > td.date-width-disabled:hover {
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
                        <li class="active">Apontamentos Diários</li>
                    </ol>
                    <div class="row">
                        <div class="col-md-6">
                            <!--                            <button type="button" class="btn btn-primary" title="Configurações" data-toggle="modal" data-target="#modal_config">
                                                            <i class="glyphicon glyphicon-cog"></i> Configurações
                                                        </button>-->
                            <div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <i class="glyphicon glyphicon-list-alt"></i> Gerenciar <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if ($this->session->userdata('nivel') != 11): ?>
                                        <li><a href="apontamento_colaboradores">Colaboradores</a></li>
                                    <?php endif; ?>
                                    <?php if ($modo_privilegiado): ?>
                                        <li><a href="apontamento_contratos">Contratos</a></li>
                                        <li><a href="apontamento_postos">Postos</a></li>
                                        <li><a href="apontamento_detalhes">Detalhes de eventos</a></li>
                                    <?php endif; ?>
                                    <li><a href="apontamento_eventos">Relatório de eventos</a></li>
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
                                            id="alerta_depto"><?= empty($depto_atual) ? 'Todos' : $depto_atual ?></span></small><br>
                                <small>&emsp;<strong>Área/cliente:</strong> <span
                                            id="alerta_area"><?= empty($area_atual) ? 'Todas' : $area_atual ?></span></small><br>
                                <small>&emsp;<strong>Setor/unidade:</strong> <span
                                            id="alerta_setor"><?= empty($setor_atual) ? 'Todos' : $setor_atual ?></span></small>
                            </span>
                            </p>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">
                            <span id="mes_ano"><?= ucfirst($mes) . ' ' . date('Y') ?></span>
                            <div style="float:right; margin-top: -0.5%;">
                                <button title="Mês anterior" class="btn btn-primary btn-sm" onclick="proximo_mes(-1)">
                                    <i class="glyphicon glyphicon-arrow-left"></i> <span class="hidden-xs hidden-sm">Mês anterior</span>
                                </button>
                                <?php if ($this->session->userdata('nivel') != 11): ?>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Opções do mês <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="javascript:void();" onclick="add_mes()"><i
                                                            class="glyphicon glyphicon-import text-success"></i> Alocar
                                                    colaboradores</a></li>
                                            <li><a href="javascript:void();" onclick="excluir_mes()"><i
                                                            class="glyphicon glyphicon-erase text-danger"></i> Limpar
                                                    mês</a></li>
                                            <li><a href="#" data-toggle="modal" data-target="#modal_colaborador"><i
                                                            class="glyphicon glyphicon-plus text-success"></i> Alocar
                                                    novo colaborador</a></li>
                                            <li><a href="#" data-toggle="modal"
                                                   data-target="#modal_colaborador_alocado"><i
                                                            class="glyphicon glyphicon-minus text-danger"></i> Desalocar
                                                    colaborador</a></li>
                                            <?php if ($modo_privilegiado or $this->session->userdata('nivel') == 10): ?>
                                                <li><a href="<?= site_url('apontamento_backups/index'); ?>" id="bck"
                                                       target="_blank"><i
                                                                class="glyphicon glyphicon-list text-info"></i>
                                                        Relatório Gestão Backup</a></li>
                                                <li><a href="<?= site_url('apontamento_relatorios/index'); ?>" id="pdf"
                                                       target="_blank"><i
                                                                class="glyphicon glyphicon-list text-info"></i>
                                                        Relatório de Medição</a></li>
                                                <li><a href="<?= site_url('apontamento_financas'); ?>" id="financas"
                                                       target="_blank"><i
                                                                class="glyphicon glyphicon-list text-info"></i>
                                                        Relatório de Gestão Individual</a></li>
                                                <li><a href="<?= site_url('apontamento_gestao_consolidada'); ?>"
                                                       id="gestao_consolidada"
                                                       target="_blank"><i
                                                                class="glyphicon glyphicon-list text-info"></i>
                                                        Relatório de Gestão Consolidada</a></li>
                                            <?php endif; ?>
                                            <?php if ($modo_privilegiado || $this->session->userdata('nivel') == 10): ?>
                                                <li>
                                                    <a href="<?= site_url('apontamento_relatorios/atividades_mensais'); ?>"
                                                       id="atividades_mensais"
                                                       target="_blank"><i
                                                                class="glyphicon glyphicon-list text-info"></i>
                                                        Relatório de Atividade Mensal IPESP</a></li>
                                            <?php endif; ?>
                                            <?php if ($modo_privilegiado): ?>
                                                <li><a href="#" data-toggle="modal" data-target="#modal_config"
                                                       id="config">
                                                        <i class="glyphicon glyphicon-info-sign text-info"></i>
                                                        Observações do mês</a></li>
                                            <?php endif; ?>
                                            <?php if ($modo_privilegiado || $this->session->userdata('nivel') == 10): ?>
                                                <li><a href="#" data-toggle="modal" data-target="#modal_config_ipesp"
                                                       id="config_ipesp">
                                                        <i class="glyphicon glyphicon-info-sign text-info"></i>
                                                        Observações do mês IPESP</a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                <button title="Mês seguinte" id="mes_seguinte" class="btn btn-primary btn-sm"
                                        onclick="proximo_mes(1)">
                                    <span class="hidden-xs hidden-sm">Mês seguinte</span> <i
                                            class="glyphicon glyphicon-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="panel-body">

                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" style="font-size: 14px; font-weight: bolder" class="active"><a
                                            href="#apontamento" aria-controls="apontamento" role="tab"
                                            data-toggle="tab">Apontamento</a></li>
                                <?php if ($modo_privilegiado): ?>
                                    <li role="presentation" style="font-size: 14px; font-weight: bolder"><a
                                                href="#totalizacao" aria-controls="totalizacao" role="tab"
                                                data-toggle="tab">Totalização</a></li>
                                <?php endif; ?>
                                <li role="presentation" style="font-size: 14px; font-weight: bolder"><a
                                            href="#colaboradores" aria-controls="colaboradores" role="tab"
                                            data-toggle="tab">Status dos colaboradores</a></li>
                                <li role="presentation" style="font-size: 14px; font-weight: bolder; display: none;"><a
                                            href="#apontamento_consolidado" aria-controls="apontamento_consolidado"
                                            role="tab"
                                            data-toggle="tab">Apontamento consolidado</a></li>
                                <li role="presentation" style="font-size: 14px; font-weight: bolder; display: none;"><a
                                            href="#totalizacao_consolidada" aria-controls="totalizacao_consolidada"
                                            role="tab"
                                            data-toggle="tab">Totalização consolidada</a></li>
                            </ul>

                            <div class="tab-content" style="border: 1px solid #ddd; border-top-width: 0;">
                                <div role="tabpanel" class="tab-pane active" id="apontamento">
                                    <table id="table"
                                           class="table table-hover table_apontamento table-condensed table-bordered"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">
                                                Colaborador(a)
                                            </th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">
                                                Colaborador(a) substituito(a)
                                            </th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">
                                                Saldo
                                            </th>
                                            <!--<th rowspan="2" class="warning" style="vertical-align: middle;">Subs</th>-->
                                            <th colspan="31" class="date-width">Dias</th>
                                            <th colspan="2" class="warning text-center"
                                                style="padding-left: 4px; padding-right: 4px;">Faltas/atrasos
                                            </th>
                                        </tr>
                                        <tr>
                                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                                <?php if (date('N', mktime(0, 0, 0, date('m'), $i, date('Y'))) < 6): ?>
                                                    <th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                                                <?php else: ?>
                                                    <th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <th class="warning text-center"
                                                style="padding-left: 4px; padding-right: 4px;">Dias
                                            </th>
                                            <th class="warning text-center"
                                                style="padding-left: 4px; padding-right: 4px;">Horas
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                                <?php if ($modo_privilegiado): ?>
                                    <div role="tabpanel" class="tab-pane" id="totalizacao">
                                        <table id="table_totalizacao"
                                               class="table table-hover table-condensed table-bordered" cellspacing="0"
                                               width="100%" style="border-radius: 0 !important;">
                                            <thead>
                                            <tr>
                                                <th rowspan="2" class="warning" style="vertical-align: middle;">
                                                    Colaborador(a)
                                                </th>
                                                <th colspan="4" class="warning text-center"
                                                    style="padding-left: 4px; padding-right: 4px;">Faltas/atrasos
                                                </th>
                                                <th colspan="1" class="warning text-center"
                                                    style="padding-left: 4px; padding-right: 4px;">Posto
                                                </th>
                                                <th colspan="4" class="warning text-center"
                                                    style="padding-left: 4px; padding-right: 4px;">Valores (R$)
                                                </th>
                                                <th colspan="1" class="warning text-center text-nowrap"
                                                    style="padding-left: 4px; padding-right: 4px;">Total (<span
                                                            id="total_percentual">0,00</span>)
                                                </th>
                                                <th colspan="3" class="warning text-center"
                                                    style="padding-left: 4px; padding-right: 4px;">Acréscimo
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="warning text-center">Dias</th>
                                                <th class="warning text-center">%</th>
                                                <th class="warning text-center">Horas</th>
                                                <th class="warning text-center">%</th>
                                                <th class="warning text-center"><span
                                                            id="total_posto">0,00</span></th>
                                                <th class="warning text-center">Conversor dia</th>
                                                <th class="warning text-center">Glosa dia</th>
                                                <th class="warning text-center">Conversor hora</th>
                                                <th class="warning text-center">Glosa hora</th>
                                                <th class="warning text-center"><span
                                                            id="total_devido">0,00</span></th>
                                                <th class="warning text-center"><span
                                                            id="dias_acrescidos">Dias (%)</span></th>
                                                <th class="warning text-center"><span
                                                            id="horas_acrescidas">Horas (%)</span></th>
                                                <th class="warning text-center"><span id="total_acrescido">Total</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>

                                <div role="tabpane2" class="tab-pane" id="colaboradores">
                                    <table id="table_colaboradores"
                                           class="table table-hover table-condensed table-bordered" cellspacing="0"
                                           width="100%" style="border-radius: 0 !important;">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">
                                                Colaborador(a)
                                            </th>
                                            <th colspan="3" class="warning text-center">Estrutura</th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">Função</th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">Ações</th>
                                        </tr>
                                        <tr>
                                            <th class="warning text-center">Departamento</th>
                                            <th class="warning text-center">Área/Cliente</th>
                                            <th class="warning text-center">Setor/Unidade</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                                <div role="tabpanel3" class="tab-pane" id="apontamento_consolidado">
                                    <table id="table_apontamento_consolidado"
                                           class="table table_apontamento table-hover table-condensed table-bordered"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">
                                                Colaborador(a)
                                            </th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">
                                                Colaborador(a) substituito(a)
                                            </th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">
                                                Saldo
                                            </th>
                                            <!--<th rowspan="2" class="warning" style="vertical-align: middle;">Subs</th>-->
                                            <th colspan="31" class="date-width">Dias</th>
                                            <th colspan="2" class="warning text-center"
                                                style="padding-left: 4px; padding-right: 4px;">Faltas/atrasos
                                            </th>
                                        </tr>
                                        <tr>
                                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                                <?php if (date('N', mktime(0, 0, 0, date('m'), $i, date('Y'))) < 6): ?>
                                                    <th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                                                <?php else: ?>
                                                    <th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <th class="warning text-center"
                                                style="padding-left: 4px; padding-right: 4px;">Dias
                                            </th>
                                            <th class="warning text-center"
                                                style="padding-left: 4px; padding-right: 4px;">Horas
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                                <?php if ($modo_privilegiado): ?>
                                    <div role="tabpanel" class="tab-pane" id="totalizacao_consolidada">
                                        <table id="table_totalizacao_consolidada"
                                               class="table table-hover table-condensed table-bordered" cellspacing="0"
                                               width="100%" style="border-radius: 0 !important;">
                                            <thead>
                                            <tr>
                                                <th rowspan="2" class="warning" style="vertical-align: middle;">
                                                    Colaborador(a)
                                                </th>
                                                <th colspan="4" class="warning text-center"
                                                    style="padding-left: 4px; padding-right: 4px;">Faltas/atrasos
                                                </th>
                                                <th colspan="1" class="warning text-center"
                                                    style="padding-left: 4px; padding-right: 4px;">Posto
                                                </th>
                                                <th colspan="4" class="warning text-center"
                                                    style="padding-left: 4px; padding-right: 4px;">Valores (R$)
                                                </th>
                                                <th colspan="1" class="warning text-center text-nowrap"
                                                    style="padding-left: 4px; padding-right: 4px;">Total (<span
                                                            id="total_percentual_consolidado">0,00</span>)
                                                </th>
                                                <th colspan="3" class="warning text-center"
                                                    style="padding-left: 4px; padding-right: 4px;">Acréscimo
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="warning text-center">Dias</th>
                                                <th class="warning text-center">%</th>
                                                <th class="warning text-center">Horas</th>
                                                <th class="warning text-center">%</th>
                                                <th class="warning text-center"><span
                                                            id="total_posto_consolidado">0,00</span></th>
                                                <th class="warning text-center">Conversor dia</th>
                                                <th class="warning text-center">Glosa dia</th>
                                                <th class="warning text-center">Conversor hora</th>
                                                <th class="warning text-center">Glosa hora</th>
                                                <th class="warning text-center"><span
                                                            id="total_devido_consolidado">0,00</span></th>
                                                <th class="warning text-center"><span
                                                            id="dias_acrescidos_consolidados">Dias (%)</span></th>
                                                <th class="warning text-center"><span
                                                            id="horas_acrescidas_consolidadas">Horas (%)</span></th>
                                                <th class="warning text-center"><span id="total_acrescido_consolidado">Total</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
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
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por departamento</label>
                                        <?php echo form_dropdown('depto', $depto, $depto_atual, 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por área/cliente</label>
                                        <?php echo form_dropdown('area', $area, $area_atual, 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por setor/unidade</label>
                                        <?php echo form_dropdown('setor', $setor, $setor_atual, 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por cargo</label>
                                        <?php echo form_dropdown('cargo', $cargo, '', 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por função</label>
                                        <?php echo form_dropdown('funcao', $funcao, '', 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
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
                            <button type="button" id="btnSaveFiltro" onclick="filtrar()" class="btn btn-primary"
                                    data-dismiss="modal">OK
                            </button>
                            <button type="button" id="limpar" class="btn btn-default">Limpar filtros</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_backup" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar colaborador(a)</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_backup" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Colaborador(a):</label>
                                    <div class="col-md-9">
                                        <label class="sr-only" style="margin-top: 7px;"></label>
                                        <p class="form-control-static">
                                            <span id="nome_usuario_alocado"></span>
                                        </p>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Tipo de evento</label>
                                    <div class="col-md-5">
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo_bck" id="inlineRadio1" value="F" checked="">
                                            Férias
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo_bck" id="inlineRadio2" value="A"> Afastamento
                                        </label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Período</label>
                                    <div class="col-md-9 form-inline">
                                        De <input name="data_recesso" placeholder="dd/mm/aaaa"
                                                  class="form-control text-center" style="width: 150px;" maxlength="10"
                                                  autocomplete="off" type="text">
                                        até <input name="data_retorno" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center" style="width: 150px;" maxlength="10"
                                                   autocomplete="off" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Colaborador(a) backup</label>
                                    <div class="col-md-8">
                                        <?php echo form_dropdown('id_usuario_bck', $usuarios, '', 'class="form-control"'); ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveBackup" onclick="salvar_ferias()" class="btn btn-primary">
                                Salvar
                            </button>
                            <button type="button" id="btnLimparBackup" onclick="limpar_ferias()" class="btn btn-danger">
                                Limpar evento
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_substituto" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar colaborador(a) substituto(a)</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_substituto" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Colaborador(a):</label>
                                    <div class="col-md-9">
                                        <label class="sr-only" style="margin-top: 7px;"></label>
                                        <p class="form-control-static">
                                            <span id="nome_usuario_desalocado"></span>
                                        </p>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Colaborador(a) substituto(a)</label>
                                    <div class="col-md-8">
                                        <?php echo form_dropdown('id_usuario_sub', $usuarios, '', 'class="form-control"'); ?>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Data de início das atividades</label>
                                    <div class="col-md-4">
                                        <input name="data_desligamento" placeholder="dd/mm/aaaa"
                                               class="form-control text-center" autocomplete="off" type="text">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveSubstituto" onclick="salvar_substituto()"
                                    class="btn btn-primary">Salvar
                            </button>
                            <button type="button" id="btnLimparSubstituto" onclick="limpar_substituto()"
                                    class="btn btn-danger">Limpar evento
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
                            <h3 class="modal-title">Editar evento de apontamento</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_alocado"/>
                                <input type="hidden" value="" name="data"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-2" style="margin-top: -13px;">Colaborador(a):<br>Data:</label>
                                    <div class="col-md-5" style="margin-top: -13px;">
                                        <label class="sr-only"></label>
                                        <p class="form-control-static">
                                            <span id="nome"></span><br>
                                            <span id="data"></span>
                                        </p>
                                    </div>
                                    <div class="col-md-5 text-right">
                                        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">
                                            Salvar
                                        </button>
                                        <button type="button" id="btnApagar" onclick="apagar()" class="btn btn-danger">
                                            Limpar evento
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                        </button>
                                    </div>
                                </div>
                                <hr style="margin-top: 0px;">
                                <div class="form-body" style="padding-top: 0;">
                                    <div class="row">
                                        <h5 style="margin-top: 0;">Tipo de evento</h5>
                                        <div class="col col-md-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="FJ" checked>
                                                    Falta com atestado próprio
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="FN">
                                                    Falta sem atestado
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="FR">
                                                    Feriado
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col col-md-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="AJ">
                                                    Atraso com atestado próprio
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="AN">
                                                    Atraso sem atestado
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="AE">
                                                    Apontamento extra
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col col-md-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="SJ">
                                                    Saída antec. com atestado próprio
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="SN">
                                                    Saída antecipada sem atestado
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col col-md-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="PD">
                                                    Posto descoberto
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="PI">
                                                    Posto desativado
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="border-top: 1px solid #b0b0b0;">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Desconto folha</label>
                                        <div class="col-md-2">
                                            <input name="hora_glosa" class="hora form-control text-center" type="text"
                                                   value="" placeholder="hh:mm">
                                        </div>
                                        <label class="control-label col-md-2">Apontamento +</label>
                                        <div class="col-md-2">
                                            <input name="apontamento_extra" class="hora form-control text-center"
                                                   value="" placeholder="hh:mm" maxlength="5" autocomplete="off"
                                                   type="text">
                                        </div>
                                        <label class="control-label col-md-2">Apontamento -</label>
                                        <div class="col-md-2">
                                            <input name="apontamento_desc" class="hora form-control text-center"
                                                   value="" placeholder="hh:mm" maxlength="5" autocomplete="off"
                                                   type="text">
                                        </div>
                                    </div>
                                    <hr style="border-top: 1px solid #b0b0b0;">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Glosa horas</label>
                                        <div class="col-md-2">
                                            <input name="hora_atraso" class="hora form-control text-center" type="text"
                                                   value="" placeholder="hh:mm">
                                        </div>
                                        <label class="control-label col-md-2">Horário entrada</label>
                                        <div class="col-md-2">
                                            <input name="hora_entrada" class="hora form-control text-center" value=""
                                                   placeholder="hh:mm" maxlength="5" autocomplete="off" type="text">
                                        </div>
                                        <label class="control-label col-md-2">Horário intervalo</label>
                                        <div class="col-md-2">
                                            <input name="hora_intervalo" class="hora form-control text-center" value=""
                                                   placeholder="hh:mm" maxlength="5" autocomplete="off" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Glosa dias</label>
                                        <div class="col-md-2">
                                            <input name="qtde_dias" class="form-control text-right" type="number"
                                                   min="0" max="1" value="">
                                        </div>
                                        <label class="control-label col-md-2">Horário retorno</label>
                                        <div class="col-md-2">
                                            <input name="hora_retorno" class="hora form-control text-center" value=""
                                                   placeholder="hh:mm" maxlength="5" autocomplete="off" type="text">
                                        </div>
                                        <label class="control-label col-md-2">Horário saída</label>
                                        <div class="col-md-2">
                                            <input name="hora_saida" class="hora form-control text-center" value=""
                                                   placeholder="hh:mm" maxlength="5" autocomplete="off" type="text">
                                        </div>
                                    </div>
                                    <hr style="border-top: 1px solid #b0b0b0;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row form-group">
                                                <label class="control-label col-md-2">Backup</label>
                                                <div class="col-md-10">
                                                    <?php echo form_dropdown('id_alocado_bck', $usuarios, '', 'class="form-control"'); ?>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="control-label col-md-2">Detalhes</label>
                                                <div class="col-md-10">
                                                    <?php echo form_dropdown('detalhes', $detalhes, '', 'class="form-control"'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row form-group">
                                                <label class="control-label col-md-3">Observações gerais</label>
                                                <div class="col-md-9">
                                                <textarea name="observacoes" class="form-control"
                                                          rows="2"></textarea>
                                                    <span class="help-block">* Evite usar caracteres especiais</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <?php if ($modo_privilegiado): ?>
                <!-- Bootstrap modal -->
                <div class="modal fade" id="modal_totalizacao" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h3 class="modal-title">Editar valor acrescido na totalização</h3>
                            </div>
                            <div class="modal-body form">
                                <form action="#" id="form_totalizacao" class="form-horizontal">
                                    <input type="hidden" value="" name="id"/>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Colaborador(a):</label>
                                        <div class="col-md-8">
                                            <label class="sr-only" style="margin-top: 7px;"></label>
                                            <p class="form-control-static">
                                                <span id="nome_totalizacao"></span>
                                            </p>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Acréscimo dias (%)</label>
                                        <div class="col-md-3">
                                            <input name="dias_acrescidos" class="form-control text-right" type="number"
                                                   min="-99999999.99" max="99999999.99" step="0.01" value="">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Acréscimo horas (%)</label>
                                        <div class="col-md-3">
                                            <input name="horas_acrescidas" class="form-control text-right" type="number"
                                                   min="-99999999.99" max="99999999.99" step="0.01" value="">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Acréscimo total</label>
                                        <div class="col-md-4">
                                            <input name="total_acrescido" class="form-control text-right" type="number"
                                                   min="-99999999.99" max="99999999.99" step="0.01" value="">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="btnSaveTotalizacao" onclick="save_totalizacao()"
                                        class="btn btn-primary">Salvar
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            <?php endif; ?>

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_colaborador" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Alocar novo colaborador</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_colaborador" class="form-horizontal">
                                <input type="hidden" value="<?= $id_alocacao ?>" name="id"/>
                                <input type="hidden" value="" name="mes"/>
                                <input type="hidden" value="" name="ano"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Colaborador(a)</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_usuario', $usuarios, '', 'class="form-control"'); ?>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <!-- <div class="row form-group">
                                        <label class="control-label col-md-2">Mês/ano</label>
                                        <div class="col-md-3">
                                            <select name="mes" id="mes" class="form-control">
                                                <option value="01">Janeiro</option>
                                                <option value="02">Fevereiro</option>
                                                <option value="03">Março</option>
                                                <option value="04">Abril</option>
                                                <option value="05">Maio</option>
                                                <option value="06">Junho</option>
                                                <option value="07">Julho</option>
                                                <option value="08">Agosto</option>
                                                <option value="09">Setembro</option>
                                                <option value="10">Outubro</option>
                                                <option value="11">Novembro</option>
                                                <option value="12">Dezembro</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input name="ano" id="ano" placeholder="aaaa"
                                                   class="form-control text-right" maxlength="4" type="number">
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" id="copiar_posto" class="btn btn-info"
                                                    onclick="get_posto_anterior();">Selecionar último posto cadastrado
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Matrícula</label>
                                        <div class="col-md-3">
                                            <input name="matricula" type="text" class="form-control">
                                        </div>
                                        <label class="control-label col-md-1">Login</label>
                                        <div class="col-md-3">
                                            <input name="login" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Valor posto</label>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1">R$</span>
                                                <input name="valor_posto" type="number" step="0.01"
                                                       class="valor form-control text-right"
                                                       aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" id="calcular_valor" class="btn btn-info">Calcular
                                                valores
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Qtde. dias</label>
                                        <div class="col-md-6 form-inline">
                                            <input name="total_dias_mensais" type="number" step="1"
                                                   style="width: 100px;" class="valor form-control text-right">
                                            &emsp;Valor
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1">R$</span>
                                                <input name="valor_dia" type="number" step="0.01" style="width: 120px;"
                                                       class="form-control text-right" aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                        <label class="control-label col-md-2">Horário entrada</label>
                                        <div class="col-md-2">
                                            <input name="horario_entrada" type="text"
                                                   class="hora form-control text-center">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Qtde. horas</label>
                                        <div class="col-md-6 form-inline">
                                            <input name="total_horas_diarias" type="number" step="1"
                                                   style="width: 100px;" class="valor form-control text-right">
                                            &emsp;Valor
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1">R$</span>
                                                <input name="valor_hora" type="number" step="0.01" style="width: 120px;"
                                                       class="form-control text-right" aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                        <label class="control-label col-md-2">Horário saída</label>
                                        <div class="col-md-2">
                                            <input name="horario_saida" type="text"
                                                   class="hora form-control text-center">
                                        </div>
                                    </div> -->
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveColaboradores" onclick="save_colaborador()"
                                    class="btn btn-primary">Alocar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_colaborador_alocado" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Desalocar colaborador</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_colaborador_alocado" class="form-horizontal">
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Colaborador(a)</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id', $usuarios, '', 'class="form-control" autocomplete="off"'); ?>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnDeleteColaborador" onclick="delete_colaborador()"
                                    class="btn btn-danger" disabled>Desalocar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_config" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Observações do mês</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_config" class="form-horizontal" autocomplete="off">
                                <div class="row form-group" style="margin-bottom: 0px;">
                                    <label class="col-md-3 text-left" style="margin-top: 7px; margin-bottom: 0px;">
                                        <h5><strong>Lançamentos mês quebrado</strong></h5>
                                    </label>
                                    <div class="col-md-6">
                                        <div class="checkbox">
                                            <label>
                                                <input id="bloquear_mes" type="checkbox"">Congelar mês (eventos,
                                                valores, etc)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" id="btnSaveConfig" onclick="salvar_configuracoes()"
                                                class="btn btn-primary">Salvar
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar
                                        </button>
                                    </div>
                                </div>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Dia de fechamento mensal</label>
                                    <div class="col-md-2">
                                        <input name="dia_fechamento" class="form-control text-center"
                                               type="number" id="dia_fechamento" placeholder="dd"
                                               step="1" min="1" max="31" value=""
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);">
                                    </div>
                                    <label class="control-label col-md-3">Total geral de faltas</label>
                                    <div class="col-md-2">
                                        <input name="total_faltas" class="form-control text-center"
                                               type="number" id="total_faltas" placeholder="dd"
                                               step="0.01" min="1" max="31" value="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Total dias cobertos</label>
                                    <div class="col-md-2">
                                        <input name="total_dias_cobertos" class="form-control text-center"
                                               type="number" id="total_dias_cobertos" placeholder="dd"
                                               step="0.01" min="1" max="31" value="">
                                    </div>
                                    <label class="control-label col-md-3">Total dias não-cobertos</label>
                                    <div class="col-md-2">
                                        <input name="total_dias_descobertos" class="form-control text-center"
                                               type="number" id="total_dias_descobertos" placeholder="dd"
                                               step="0.01" min="1" max="31" value="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Valor projetado (R$)</label>
                                    <div class="col-md-3">
                                        <input name="valor_projetado" class="form-control text-center" type="number"
                                               min="0" step="0.01" value="">
                                    </div>
                                    <label class="control-label col-md-2">Valor realizado (R$)</label>
                                    <div class="col-md-3">
                                        <input name="valor_realizado" class="form-control text-center" type="number"
                                               min="0" step="0.01" value="">
                                    </div>
                                </div>
                                <h5><strong>Lançamentos gerais</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Qtde. colaboradores potenciais</label>
                                    <div class="col-md-2">
                                        <input name="qtde_alocados_potenciais" class="form-control text-center"
                                               type="number"
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);"
                                               step="0.01" value="">
                                    </div>
                                    <label class="control-label col-md-3">Qtde. colaboradores ativos</label>
                                    <div class="col-md-2">
                                        <input name="qtde_alocados_ativos" class="form-control text-center"
                                               type="number" step="0.01" value="">
                                    </div>
                                    <label class="control-label col-md-2">N&ordm; contrato</label>
                                    <div class="col-md-2">
                                        <input name="contrato" class="form-control" type="text" value=""
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Observações</label>
                                    <div class="col-md-8">
                                        <textarea name="observacoes" class="form-control" rows="1"
                                                  style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);"></textarea>
                                    </div>
                                </div>
                                <h5><strong>Turnover</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Admissões para reposição</label>
                                    <div class="col-md-2">
                                        <input name="turnover_reposicao" class="form-control text-center"
                                               type="number" value=""
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);">
                                    </div>
                                    <label class="control-label col-md-4">Admissões aumento quadro</label>
                                    <div class="col-md-2">
                                        <input name="turnover_aumento_quadro" class="form-control text-center"
                                               type="number" value=""
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Desligamentos AME</label>
                                    <div class="col-md-2">
                                        <input name="turnover_desligamento_empresa" class="form-control text-center"
                                               type="number" value=""
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);">
                                    </div>
                                    <label class="control-label col-md-4">Desligamentos colaboradores</label>
                                    <div class="col-md-2">
                                        <input name="turnover_desligamento_colaborador" class="form-control text-center"
                                               type="number" value=""
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);">
                                    </div>
                                </div>
                                <div id="ipesp">
                                    <h5><strong>Lançamentos Ipesp</strong></h5>
                                    <hr style="margin-top: 0px; margin-bottom: 0px;">
                                    <div class="row form-group">
                                        <div class="col-md-9 col-md-offset-1">
                                            <h5>Serviço não compartilhado/Valor Adicional</h5>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-1">Item</label>
                                        <div class="col-md-6">
                                            <input name="descricao_servico" placeholder="Item"
                                                   class="form-control input-sm"
                                                   type="text"
                                                   style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);">
                                        </div>
                                        <label class="control-label col-md-1">Valor</label>
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">R$</span>
                                            <input name="valor_servico" type="text"
                                                   style="width:142px; background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);"
                                                   class="valor form-control text-right"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_config_ipesp" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Observações do mês para IPESP/Teleatendimento</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_config_ipesp" class="form-horizontal" autocomplete="off">
                                <input type="hidden" name="id" value="">
                                <input type="hidden" name="id_alocacao" value="">
                                <div class="row form-group" style="margin-bottom: 0px;">
                                    <label class="col-md-8 text-left" style="margin-top: 7px; margin-bottom: 0px;">
                                        <h5><strong></strong></h5>
                                    </label>
                                    <div class="col-md-4 text-right">
                                        <button type="button" id="btnSaveConfigIpesp"
                                                onclick="salvar_configuracoes_ipesp()"
                                                class="btn btn-primary">Salvar
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar
                                        </button>
                                    </div>
                                </div>
                                <h5><strong>Quantidade de colaboradores</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Contratados</label>
                                    <div class="col-md-2">
                                        <input name="total_colaboradores_contratados" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                    <label class="control-label col-md-2">Ativos</label>
                                    <div class="col-md-2">
                                        <input name="total_colaboradores_ativos" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                </div>
                                <h5><strong>Visitas periódicas</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Projetadas</label>
                                    <div class="col-md-2">
                                        <input name="visitas_projetadas" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                    <label class="control-label col-md-2">Realizadas</label>
                                    <div class="col-md-2">
                                        <input name="visitas_realizadas" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Realização</label>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input name="visitas_porcentagem" class="form-control text-center"
                                                   type="number" value="">
                                            <span class="input-group-addon" id="basic-addon1">%</span>
                                        </div>
                                    </div>
                                    <label class="control-label col-md-2">Qtde. horas</label>
                                    <div class="col-md-2">
                                        <input name="visitas_total_horas" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                </div>
                                <h5><strong>Balanço financeiro</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Valor projetado</label>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">R$</span>
                                            <input name="balanco_valor_projetado" type="text" style="width: 142px;"
                                                   class="valor form-control text-right"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                    <label class="control-label col-md-2">Valor realizado</label>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">R$</span>
                                            <input name="balanco_glosas" type="text" style="width: 142px;"
                                                   class="valor form-control text-right"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Valor glosa</label>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">R$</span>
                                            <input name="balanco_valor_glosa" type="text" style="width: 142px;"
                                                   class="valor form-control text-right"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                    <label class="control-label col-md-2">Realizado</label>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">%</span>
                                            <input name="balanco_porcentagem"
                                                   class="form-control text-center porcentagem"
                                                   type="text" value="">
                                        </div>
                                    </div>
                                </div>
                                <h5><strong>Turnover</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Admissões</label>
                                    <div class="col-md-2">
                                        <input name="turnover_admissoes" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                    <label class="control-label col-md-2">Demissões</label>
                                    <div class="col-md-2">
                                        <input name="turnover_demissoes" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                    <label class="control-label col-md-2">Desligamentos</label>
                                    <div class="col-md-2">
                                        <input name="turnover_desligamentos" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                </div>

                                <h5><strong>Atendimentos</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Total mês</label>
                                    <div class="col-md-2">
                                        <input name="atendimentos_total_mes" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                    <label class="control-label col-md-2">Média dia</label>
                                    <div class="col-md-2">
                                        <input name="atendimentos_media_diaria" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                </div>
                                <h5><strong>Pendências</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Total informada</label>
                                    <div class="col-md-2">
                                        <input name="pendencias_total_informada" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                    <label class="control-label col-md-3">Aguardando tratativa</label>
                                    <div class="col-md-2">
                                        <input name="pendencias_aguardando_tratativa" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Parcialmente resolvidas</label>
                                    <div class="col-md-2">
                                        <input name="pendencias_parcialmente_resolvidas"
                                               class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                    <label class="control-label col-md-3">Resolvidas</label>
                                    <div class="col-md-2">
                                        <input name="pendencias_resolvidas" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-8">Qtde. atendimentos X Qtde. pendências</label>
                                    <div class="col-md-2">
                                        <input name="pendencias_resolvidas_atendimentos"
                                               class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                </div>

                                <h5><strong>Monitoria</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Media equipe (0 a 100)</label>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input name="monitoria_media_equipe" class="form-control text-center"
                                                   type="number" value="" min="0" max="100">
                                            <span class="input-group-addon" id="basic-addon1">%</span>
                                        </div>
                                    </div>
                                </div>
                                <h5><strong>Indicadores operacionais</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-1">TMA</label>
                                    <div class="col-md-2">
                                        <input name="indicadores_operacionais_tma"
                                               class="form-control text-center"
                                               type="text" value="">
                                    </div>
                                    <label class="control-label col-md-1">TME</label>
                                    <div class="col-md-2">
                                        <input name="indicadores_operacionais_tme"
                                               class="form-control text-center"
                                               type="text" value="">
                                    </div>
                                    <label class="control-label col-md-2">Ociosidade</label>
                                    <div class="col-md-2">
                                        <input name="indicadores_operacionais_ociosidade"
                                               class="form-control text-center"
                                               type="text" value="">
                                    </div>
                                </div>
                                <h5><strong>Índices de satisfação</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <div class="col-md-3">
                                        <h5>Avaliação atendimento</h5>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Qtde. de respostas</label>
                                            <div class="col-md-3">
                                                <input name="avaliacoes_atendimento"
                                                       class="form-control input-sm"
                                                       type="text">
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Ótimo</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">%</span>
                                                    <input name="avaliacoes_atendimento_otimos"
                                                           class="form-control input-sm"
                                                           type="text">
                                                </div>
                                            </div>
                                            <label class="control-label col-md-1">Bom</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">%</span>
                                                    <input name="avaliacoes_atendimento_bons"
                                                           class="form-control input-sm"
                                                           type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Regular</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">%</span>
                                                    <input name="avaliacoes_atendimento_regulares"
                                                           class="form-control input-sm"
                                                           type="text">
                                                </div>
                                            </div>
                                            <label class="control-label col-md-1">Ruim</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">%</span>
                                                    <input name="avaliacoes_atendimento_ruins"
                                                           class="form-control input-sm"
                                                           type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-3">
                                        <h5>Solicitações atendidas</h5>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Qtde. de respostas</label>
                                            <div class="col-md-3">
                                                <input name="solicitacoes"
                                                       class="form-control input-sm"
                                                       type="text">
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Sim</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">%</span>
                                                    <input name="solicitacoes_atendidas"
                                                           class="form-control input-sm"
                                                           type="text">
                                                </div>
                                            </div>
                                            <label class="control-label col-md-1">Não</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">%</span>
                                                    <input name="solicitacoes_nao_atendidas"
                                                           class="form-control input-sm"
                                                           type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h5><strong>Observações</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <div class="col-md-8 col-md-offset-2">
                                        <textarea name="observacoes" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal" id="modal_legenda" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Legenda de eventos</h3>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <table>
                                        <tr style="border: 2px solid #fff;">
                                            <td class="text-center"
                                                style="padding: 4px; font-weight: bold;">FJ
                                            </td>
                                            <td style="padding-left: 8px;"> Falta com atestado próprio</td>
                                        </tr>
                                        <tr style="border: 2px solid #fff;">
                                            <td class="text-center"
                                                style="padding: 4px; font-weight: bold;">FN
                                            </td>
                                            <td style="padding-left: 8px;"> Falta sem atestado</td>
                                        </tr>
                                        <tr style="border: 2px solid #fff;">
                                            <td class="text-center"
                                                style="padding: 4px; font-weight: bold;">PD
                                            </td>
                                            <td style="padding-left: 8px;"> Posto descoberto</td>
                                        </tr>
                                        <tr style="border: 2px solid #fff;">
                                            <td class="text-center"
                                                style="padding: 4px; font-weight: bold;">PI
                                            </td>
                                            <td style="padding-left: 8px;"> Posto descontinuado</td>
                                        </tr>
                                        <tr style="border: 2px solid #fff;">
                                            <td class="text-center"
                                                style="padding: 4px; font-weight: bold;">SJ
                                            </td>
                                            <td style="padding-left: 8px;"> Saída antecipada com atestado próprio</td>
                                        </tr>
                                        <tr style="border: 2px solid #fff;">
                                            <td class="text-center"
                                                style="padding: 4px; font-weight: bold;">SN
                                            </td>
                                            <td style="padding-left: 8px;"> Saída antecipada sem atestado</td>
                                        </tr>
                                        <tr style="border: 2px solid #fff;">
                                            <td class="text-center"
                                                style="padding: 4px; font-weight: bold;">AJ
                                            </td>
                                            <td style="padding-left: 8px;"> Atraso com atestado próprio</td>
                                        </tr>
                                        <tr style="border: 2px solid #fff;">
                                            <td class="text-center"
                                                style="padding: 4px; font-weight: bold;">AN
                                            </td>
                                            <td style="padding: 5px;"> Atraso sem atestado</td>
                                        </tr>
                                        <tr style="border: 2px solid #fff;">
                                            <td class="text-center"
                                                style="padding: 4px; font-weight: bold;">AP
                                            </td>
                                            <td style="padding: 5px;"> Apontamento extra / Desconto de apontamento</td>
                                        </tr>
                                        <tr style="border: 2px solid #fff;">
                                            <td class="text-center"
                                                style="padding: 4px; color: #fff; background-color: #337ab7; font-weight: bold;">
                                                FR
                                            </td>
                                            <td style="padding-left: 8px;"> Feriado</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-xs-6">
                                    <p style="text-indent: 17px;">
                                        Os documentos aceitos como atestados são:
                                    </p>
                                    <ul>
                                        <li>Atestado médico;</li>
                                        <li>Atestado odontológico;</li>
                                        <li>Notificação judicial;</li>
                                        <li>Notificação de órgãos oficiais;</li>
                                    </ul>
                                    <p style="text-indent: 17px;">
                                        Os atestados dos profissionais da área da saúde devem ser nominais ao
                                        colaborador, devendo condicionalmente conter assinatura e carimbo do
                                        profissional responsável.
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <table>
                                <tr style="border: 2px solid #fff;">
                                    <td style="padding: 4px; background-color: #d9534f;">&emsp;</td>
                                    <td style="padding-left: 8px;" width="50%"> Eventos geradores de glosa<br>Apontamentos
                                        negativos
                                    </td>
                                    <td style="padding: 4px; background-color: #5cb85c;">&emsp;</td>
                                    <td style="padding-left: 8px;" width="50%"> Eventos não geradores de glosa<br>Apontamentos
                                        positivos
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div class="modal" id="modal_finalizado" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Este mês está finalizado</h3>
                        </div>
                        <div class="modal-body">
                            <p>Os apontamentos não podem mais ser realizados. Caso seja realmente necessário algum
                                ajuste de apontamento, contate o supervisor ou coordenador do departamento.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>
    <!--main content end-->

<?php
require_once "end_js.php";
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.css"); ?>"/>

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gestão Operacional ST';
        });</script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/extensions/dataTables.fixedColumns.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

    <script>

        var table, table_totalizacao, table_colaboradores, table_apontamento_consolidado, table_totalizacao_consolidada;
        var busca;
        var edicaoEvento = true;
        var nivel_usuario = '<?= $this->session->userdata('nivel'); ?>';
        var data_fechamento = moment('<?= date('5/m/Y') ?>', 'DD/MM/YYYY').subtract(1, 'months');
        var mesBloqueado = false;

        $('.tags').tagsInput({width: 'auto', defaultText: 'Telefone', placeholderColor: '#999', delimiter: '/'});
        $('[name="data_recesso"], [name="data_retorno"], [name="data_desligamento"]').mask('00/00/0000');
        $('.hora').mask('00:00');
        $('.valor').mask('##.###.##0,00', {reverse: true});
        $('.porcentagem').mask('#00,0', {reverse: true});
        $(function () {
            $('[data-tooltip="tooltip"]').tooltip();
        });

        moment.locale('pt-br');

        $(document).ready(function () {
            busca = $('#busca').serialize();
            var url = "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>";

            table = $('#table').DataTable({
                dom: "<'row'<'col-sm-3'l><'#legenda.col-sm-5'><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                "processing": true,
                "serverSide": true,
                "iDisplayLength": 25,
                "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "order": [[0, 'asc']],
                "language": {
                    "url": url
                },
                "ajax": {
                    "url": "<?php echo site_url('apontamento/ajax_list') ?>",
                    "type": "POST",
                    timeout: 90000,
                    data: function (d) {
                        d.busca = busca;
                        return d;
                    },
                    "dataSrc": function (json) {
                        mesBloqueado = json.calendar.mes_bloqueado;
                        $('[name="mes"]').val(json.calendar.mes);
                        $('[name="ano"]').val(json.calendar.ano);
                        $('#mes_ano').html(json.calendar.mes_ano[0].toUpperCase() + json.calendar.mes_ano.slice(1));
                        var dt1 = new Date();
                        var dt2 = new Date();
                        dt2.setFullYear(json.calendar.ano, (json.calendar.mes - 1));
                        if (dt1.getTime() < dt2.getTime()) {
                            $('#mes_seguinte').addClass('disabled').parent().css('cursor', 'not-allowed');
                        } else {
                            $('#mes_seguinte').removeClass('disabled').parent().css('cursor', '');
                        }

                        var semana = 1;
                        var colunasUsuario = 2;
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
                        }
                        if (json.draw === '1') {
                            $("#legenda").html('<button title="Mostrar legenda de eventos" data-toggle="modal" data-target="#modal_legenda" style="margin: 15px 10px 0;" class="btn btn-default btn-sm">' +
                                '<i class="glyphicon glyphicon-exclamation-sign"></i> <span class="hidden-xs"> Mostrar legenda de eventos</span>' +
                                '</button>');
                        }
                        return json.data;
                    }
                },
                "columnDefs": [
                    {
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if (!mesBloqueado) {
                                $(td).css('cursor', 'pointer');
                            } else {
                                $(td).css('cursor', 'not-allowed');
                            }
                            if (rowData[col][2] !== null || rowData[col][3] !== null) {
                                if (rowData[col][5] === 'A') {
                                    $(td).addClass('colaborador-disabled');
                                } else if (rowData[col][5] === 'F') {
                                    $(td).addClass('colaborador-primary');
                                }
                                $(td).removeClass('warning');
                                $(td).attr({
                                    'title': (rowData[col][2] !== null ? 'Data de saída: ' + rowData[col][2] + '\n' : '') +
                                        (rowData[col][3] !== null ? 'Data de retorno: ' + rowData[col][3] + '\n' : '') +
                                        (rowData[col][4] !== '' && rowData[col][4] !== undefined ? 'Colaborador backup: ' + $('[name="id_usuario_bck"] option[value="' + rowData[col][4] + '"]').text() : '')
                                });
                            } else {
                                $(td).addClass('warning');
                                $(td).removeClass('colaborador-success');
                            }
                            $(td).attr({
                                'data-toggle': 'modal',
                                'data-target': '#modal_backup',
                                'data-id': rowData[col][0],
                                'data-nome': rowData[col][1],
                                'data-recesso': rowData[col][2],
                                'data-retorno': rowData[col][3],
                                'data-id_usuario_bck': rowData[col][4],
                                'data-tipo': rowData[col][5]
                            });
                            $(td).on('click', function () {
                                if (edicaoEvento === false || mesBloqueado === true) {
                                    return false;
                                }
                                $('#form_backup [name="id"]').val($(this).data('id'));
                                if ($(this).data('tipo') !== undefined) {
                                    $('#form_backup [name="tipo_bck"][value="' + $(this).data('tipo') + '"]').prop('checked', true);
                                    $('#btnLimparBackup').prop('disabled', false);
                                } else {
                                    $('#form_backup [name="tipo_bck"][value="F"]').prop('checked', true);
                                    $('#btnLimparBackup').prop('disabled', true);
                                }
                                $('#form_backup [name="data_recesso"]').val($(this).data('recesso'));
                                $('#form_backup [name="data_retorno"]').val($(this).data('retorno'));
                                $('#form_backup [name="id_usuario_bck"]').val($(this).data('id_usuario_bck'));
                                $('#nome_usuario_alocado').html(rowData[0][1]);
                                $('#data').html($(table.column(col).header()).attr('title'));
                            });
                            $(td).html(rowData[col][1]);
                        },
                        width: 'auto',
                        "targets": [0]
                    },
                    {
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if (!mesBloqueado) {
                                $(td).css('cursor', 'pointer');
                            } else {
                                $(td).css('cursor', 'not-allowed');
                            }
                            if (rowData[col][2] !== null || rowData[col][4]) {
                                $(td).addClass('colaborador-disabled text-center');
                                $(td).removeClass('warning');
                                $(td).attr({
                                    'title': (rowData[col][2] !== null ? 'Data de início das atividades: ' + rowData[col][2] + '\n' : '') +
                                        (rowData[col][3] === null || rowData[col][3] === undefined ? '' : 'Colaborador substituto: ' + $('[name="id_usuario_sub"] option[value="' + (rowData[col][3] > 0 ? rowData[col][3] : '') + '"]').text())
                                });
                                $(td).html(rowData[col][1] !== null ? rowData[col][1] : 'AL');
                            } else {
                                $(td).addClass('warning');
                                $(td).removeClass('colaborador-success');
                                $(td).html('');
                            }
                            $(td).attr({
                                'data-toggle': 'modal',
                                'data-target': '#modal_substituto',
                                'data-id': rowData[col][0],
                                'data-nome': rowData[col][1],
                                'data-desligamento': rowData[col][2],
                                'data-id_usuario_sub': rowData[col][3]
                            });
                            $(td).on('click', function () {
                                if (edicaoEvento === false || mesBloqueado === true) {
                                    return false;
                                }
                                $('#form_substituto [name="id"]').val($(this).data('id'));
                                $('#form_substituto [name="data_desligamento"]').val($(this).data('desligamento'));
                                $('#form_substituto [name="id_usuario_sub"]').val($(this).data('id_usuario_sub'));
                                $('#nome_usuario_desalocado').html(rowData[0][1]);
                                if ($(this).data('desligamento') !== undefined) {
                                    $('#btnLimparSubstituto').prop('disabled', false);
                                } else {
                                    $('#btnLimparSubstituto').prop('disabled', true);
                                }
                            });
                        },
                        "targets": [1]
                    },
                    {
                        "createdCell": function (td, cellData, rowData, row, col) {
                            $(td).removeClass('text-success text-danger');
                            if (rowData[col] !== null && rowData[col].indexOf('-') === 0) {
                                $(td).addClass('text-danger');
                            } else if (rowData[col] !== null && rowData[col] !== '0:00' && rowData[col].indexOf('-') === -1) {
                                $(td).addClass('text-success');
                            }
                            $(td).html('<strong>' + rowData[col] + '</strong>')
                        },
                        className: 'text-center warning',
                        targets: [2]
                    },
                    {
                        "createdCell": function (td, cellData, rowData, row, col) {
                            var data_dia = $(table.column(col).header()).attr('data-dia');
                            var data_recesso = moment(rowData[0][2], 'DD/MM/YYYY').subtract(1, 'days');
                            var data_retorno = moment(rowData[0][3], 'DD/MM/YYYY').add(1, 'days');
                            var data_desligamento = moment(rowData[1][2], 'DD/MM/YYYY');
                            var evento_fechado = (nivel_usuario === 'encarregado' && moment(data_dia).isSameOrBefore(data_fechamento));

                            $(td).css('padding', '8px 1px');
                            if (rowData[col] === null || rowData[col][0] === undefined) {
                                $(td).css('background-color', '#dbdbdb').html('');
                            } else {
                                if ($(table.column(col).header()).hasClass('text-danger')) {
                                    $(td).css('background-color', '#e9e9e9');
                                    /*} else if (evento_fechado) {
                                        $(td).css('background-color', '#f0f0f0');*/
                                }

                                if (mesBloqueado) {
                                    $(td).css('cursor', 'not-allowed');
                                    /*} else if (!evento_fechado) {
                                        $(td).css('cursor', 'pointer');*/
                                }


                                if (moment(data_dia).isSameOrAfter(data_desligamento)) {
                                    $(td).css('border', '1px solid #B5BBD9');
                                } else if (moment(data_dia).isBetween(data_recesso, data_retorno)) {
                                    $(td).css('border', '1px solid #B2CEE6');
                                }

                                if (moment(data_dia).isSameOrAfter(data_desligamento)) {
                                    if (rowData[1][3] === null) {
                                        $(td).css({'background-color': '#dbdbdb', 'font-weight': 'bolder'}).html('');
                                    } else {
                                        $(td).css({'background-color': '#C9CDE1', 'font-weight': 'bolder'});
                                    }
                                } else if (moment(data_dia).isBetween(data_recesso, data_retorno)) {
                                    if (rowData[0][4] === null) {
                                        $(td).css({'background-color': '#dbdbdb', 'font-weight': 'bolder'}).html('');
                                    } else {
                                        $(td).css({'background-color': '#CCE2F4', 'font-weight': 'bolder'});
                                    }
                                } else if (rowData[col][9] === 'AE') {
                                    $(td).removeClass('date-width-success, date-width-danger').css('font-weight', 'bolder');
                                    if (rowData[col][18] < 0) {
                                        $(td).addClass('date-width-danger');
                                    } else if (rowData[col][18] > 0) {
                                        $(td).addClass('date-width-success');
                                    }
                                } else if (((rowData[col][9] === 'FJ' && rowData[col][1].length > 0) || ((rowData[col][9] === 'AJ' || rowData[col][9] === 'SJ') && rowData[col][2].length > 0)) && rowData[col][10].length === 0) {
                                    $(td).addClass('date-width-danger');
                                } else if (((rowData[col][9] === 'FN' && rowData[col][1].length > 0) || ((rowData[col][9] === 'AN' || rowData[col][9] === 'SN') && rowData[col][2].length > 0)) && rowData[col][10].length === 0) {
                                    $(td).addClass('date-width-danger');
                                } else if (rowData[col][9] === 'FR') {
                                    $(td).addClass('date-width-primary');
                                } else if (rowData[col][9] === 'PD' || rowData[col][9] === 'PI') {
                                    $(td).addClass('date-width-danger');
                                } else if (rowData[col][1] === '' || rowData[col][2] === '') {
                                    $(td).addClass('date-width-success');
                                } else if (rowData[col][0].length > 0) {
                                    $(td).css('background-color', '#ff0');
                                }
                                $(td).attr({
                                    'data-toggle': 'modal',
                                    'data-target': '#modal_form',
                                    'data-tooltip': 'tooltip',
                                    'data-placement': 'top',
                                    'title':
                                        (moment(data_dia).isSameOrAfter(data_desligamento) && rowData[1][3] ? 'Colaborador(a) substituto(a): ' + rowData[1][1] + '\nColaborador(a) principal: ' :
                                            (moment(data_dia).isBetween(data_recesso, data_retorno) && rowData[0][4] ? 'Colaborador(a) backup: ' + rowData[0][6] + '\nColaborador(a) principal: ' : '')) +
                                        rowData[0][1] + '\n' + $(table.column(col).header()).attr('title') +
                                        '\nEvento: ' + (rowData[col][9] === 'AJ' ? 'Atraso com atestado próprio' :
                                        rowData[col][9] === 'AN' ? 'Atraso sem atestado' :
                                            rowData[col][9] === 'FJ' ? 'Falta com atestado próprio' :
                                                rowData[col][9] === 'FN' ? 'Falta sem atestado' :
                                                    rowData[col][9] === 'SJ' ? 'Saída antecipada com atestado próprio' :
                                                        rowData[col][9] === 'SN' ? 'Saída antecipada sem atestado' :
                                                            rowData[col][9] === 'PD' ? 'Posto descoberto' :
                                                                rowData[col][9] === 'PI' ? 'Posto descontinuado' :
                                                                    rowData[col][9] === 'FR' ? 'Feriado' :
                                                                        moment(data_dia).isSameOrAfter(data_desligamento) && rowData[1][3] ? 'Desligamento' :
                                                                            moment(data_dia).isBetween(data_recesso, data_retorno) && rowData[0][4] ? 'Férias' : 'Ok') +
                                        (rowData[col][2] !== '' && rowData[col][2] !== undefined ? '\nHoras devedoras: ' + rowData[col][2] : '') +
                                        (rowData[col][16] !== '' && rowData[col][16] !== undefined ? '\nApontamento extra: ' + rowData[col][16] : '') +
                                        (rowData[col][17] !== '' && rowData[col][17] !== undefined ? '\nDesconto de apontamento: ' + rowData[col][17] : '') +
                                        (rowData[col][7] !== '' && rowData[col][7] !== undefined ? '\nDetalhes: ' + rowData[col][7] : '') +
                                        (rowData[col][14] !== '' && rowData[col][14] !== undefined ? '\nBackup nº 1: ' + rowData[col][14] : '') +
                                        (rowData[col][15] !== '' && rowData[col][15] !== undefined ? '\nBackup nº 2: ' + rowData[col][15] : '') +
                                        (rowData[col][8] !== '' && rowData[col][8] !== undefined ? '\nObservações: ' + rowData[col][8] : ''),
                                    'data-id_alocado': rowData[col][1],
                                    'data-text': rowData[col][5],
                                    'data-calendar': rowData[col][2],
                                    'data-id': rowData[col][0],
                                    'data-qtde_dias': rowData[col][1],
                                    'data-hora_atraso': rowData[col][2],
                                    'data-hora_entrada': rowData[col][3],
                                    'data-hora_intervalo': rowData[col][4],
                                    'data-hora_retorno': rowData[col][5],
                                    'data-hora_saida': rowData[col][6],
                                    'data-detalhes': rowData[col][7],
                                    'data-observacoes': rowData[col][8],
                                    'data-status': rowData[col][9],
                                    'data-id_alocado_bck': rowData[col][10],
                                    'data-id_alocado_bck2': rowData[col][11],
                                    'data-hora_glosa': rowData[col][12],
                                    'data-id_detalhes': rowData[col][13],
                                    'data-apontamento_extra': rowData[col][16],
                                    'data-apontamento_desc': rowData[col][17]
                                });
                                if (moment(data_dia).isSameOrAfter(data_desligamento) && rowData[1][3]) {
                                    $(td).attr({
                                        //'data-id_alocado': rowData[1][4]
                                        'data-id_alocado': rowData[0][0]
                                    });
                                } else if (moment(data_dia).isBetween(data_recesso, data_retorno) && rowData[0][4]) {
                                    $(td).attr({
                                        //'data-id_alocado': rowData[0][7]
                                        'data-id_alocado': rowData[0][0]
                                    });
                                } else {
                                    $(td).attr({
                                        'data-id_alocado': rowData[0][0]
                                    });
                                }
                                $(td).on('click', function () {
                                    if (mesBloqueado === true) {
                                        return false;
                                    }
                                    /*if (evento_fechado) {
                                        $('#modal_finalizado').modal('show');
                                        return false;
                                    }*/
                                    atualizarDetalhes();
                                    $('[name="id_alocado"]').val($(this).data('id_alocado'));
                                    $('[name="data"]').val(data_dia);
                                    $('[name="id"]').val($(this).data('id'));
                                    $('[name="qtde_dias"]').val($(this).data('qtde_dias'));
                                    $('[name="hora_atraso"]').val($(this).data('hora_atraso'));
                                    $('[name="apontamento_extra"]').val($(this).data('apontamento_extra'));
                                    $('[name="apontamento_desc"]').val($(this).data('apontamento_desc'));
                                    $('[name="hora_glosa"]').val($(this).data('hora_glosa'));
                                    $('[name="hora_entrada"]').val($(this).data('hora_entrada'));
                                    $('[name="hora_intervalo"]').val($(this).data('hora_intervalo'));
                                    $('[name="hora_retorno"]').val($(this).data('hora_retorno'));
                                    $('[name="hora_saida"]').val($(this).data('hora_saida'));
                                    $('[name="detalhes"]').val($(this).data('id_detalhes'));
                                    $('[name="observacoes"]').val($(this).data('observacoes'));
                                    if ($(this).data('status') !== undefined) {
                                        $('[name="status"][value="' + $(this).data('status') + '"]').prop('checked', 'checked');
                                        $('#modal_form .modal-title').text('Editar evento de apontamento');
                                        $('#btnApagar').prop('disabled', false);
                                        selecionar_status($(this).data('status'));
                                    } else {
                                        $('[name="status"][value="FJ"]').prop('checked', 'checked');
                                        $('#modal_form .modal-title').text('Criar evento de apontamento');
                                        $('#btnApagar').prop('disabled', true);
                                        selecionar_status('FJ');
                                    }
                                    $('[name="id_alocado_bck"]').val($(this).data('id_alocado_bck'));
                                    $('[name="id_alocado_bck2"]').val($(this).data('id_alocado_bck2'));
                                    $('#nome').html(rowData[0][1]);
                                    $('#data').html($(table.column(col).header()).attr('data-mes_ano'));
                                });
                                $(td).html(rowData[col][9] !== 'AE' ? rowData[col][9] : 'AP');
                            }
                        },
                        "className": 'text-center',
                        "targets": 'date-width',
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "createdCell": function (td, cellData, rowData, row, col) {
                            $(td).removeClass('text-success text-danger');
                            if (rowData[col] !== null) {
                                $(td).addClass('text-danger');
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        className: "warning text-right",
                        "targets": [-1, -2],
                        "orderable": false,
                        "searchable": false
                    }
                ]
            });

            if ('<?= $modo_privilegiado ?>') {
                table_totalizacao = $('#table_totalizacao').DataTable({
                    dom: "<'row'<'col-sm-3'l><'#calculo.col-sm-5'><'col-sm-4'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    "processing": true,
                    "serverSide": true,
                    "iDisplayLength": 25,
                    "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                    "order": [[0, 'asc']],
                    "language": {
                        "url": url
                    },
                    "ajax": {
                        "url": "<?php echo site_url('apontamento_totalizacao/ajax_list') ?>",
                        "type": "POST",
                        timeout: 90000,
                        data: function (d) {
                            d.busca = busca;
                            d.calculo_totalizacao = $('[name="calculo_totalizacao"]:checked').val();
                            if (d.calculo_totalizacao === undefined) {
                                d.calculo_totalizacao = '1';
                            }
                            return d;
                        },
                        "dataSrc": function (json) {
                            mesBloqueado = json.calendar.mes_bloqueado;
                            if (json.total === '0,00' || json.total === json.total_posto) {
                                $('#total_devido').removeClass('text-danger');
                            } else {
                                $('#total_devido').addClass('text-danger');
                            }
                            $('#total_posto').html(json.total_posto);
                            $('#total_devido').html(json.total);
                            $('#total_percentual').html(json.total_percentual + '%');
                            if (json.draw === '1') {
                                $("#calculo").css({'padding': '16px 0 0 32px', 'text-align': 'left'});
                                $("#calculo").append('<div class="radio"><label><?= form_radio('calculo_totalizacao" onclick="calcular_totalizacao()', '1', true) ?> Cálculo por dia/hora</label></div> &emsp;');
                                $("#calculo").append('<div class="radio"><label><?= form_radio('calculo_totalizacao" onclick="calcular_totalizacao()', '2', false) ?> Cálculo por percentual</label></div>');
                            }
                            return json.data;
                        }
                    },
                    "columnDefs": [
                        {
                            className: "warning",
                            "targets": [0]
                        },
                        {
                            "createdCell": function (td, cellData, rowData, row, col) {
                                if (rowData[col] !== null) {
                                    $(td).addClass('text-danger');
                                    $(td).html('<strong>' + rowData[col] + '</strong>');
                                }
                            },
                            className: "text-center",
                            "targets": [1, 2, 3, 4],
                            "searchable": false
                        },
                        {
                            "createdCell": function (td, cellData, rowData, row, col) {
                                if (rowData[col] !== null) {
                                    if (rowData[col] < rowData[3]) {
                                        $(td).addClass('text-danger');
                                    }
                                    $(td).html('<strong>' + rowData[col] + '</strong>');
                                }
                            },
                            className: "text-center",
                            "targets": [10],
                            "searchable": false
                        },
                        {
                            "createdCell": function (td, cellData, rowData, row, col) {
                                if (rowData[col] !== null) {
                                    $(td).html('<strong>' + rowData[col] + '</strong>');
                                }
                            },
                            className: "text-center",
                            "targets": [3, 4, 5, 6, 7, 8, 9],
                            "searchable": false
                        },
                        {
                            "createdCell": function (td, cellData, rowData, row, col) {
                                if (!mesBloqueado) {
                                    $(td).css('cursor', 'pointer');
                                } else {
                                    $(td).css('cursor', 'not-allowed');
                                }
                                $(td).attr({
                                    'data-toggle': 'modal',
                                    'data-target': '#modal_totalizacao',
                                    'data-id': rowData[14],
                                    'data-dias': rowData[11],
                                    'data-horas': rowData[12],
                                    'data-total': rowData[13]
                                });
                                $(td).on('click', function () {
                                    if (mesBloqueado === true) {
                                        return false;
                                    }
                                    if (edicaoEvento === false) {
                                        return false;
                                    }
                                    $('#form_totalizacao [name="id"]').val($(this).data('id'));
                                    $('#form_totalizacao [name="dias_acrescidos"]').val($(this).data('dias'));
                                    $('#form_totalizacao [name="horas_acrescidas"]').val($(this).data('horas'));
                                    $('#form_totalizacao [name="total_acrescido"]').val($(this).data('total'));
                                    $('#nome_totalizacao').html(rowData[0]);
                                });
                                if (rowData[13] !== null) {
                                    if (rowData[6] < rowData[3]) {
                                        $(td).addClass('text-danger');
                                    }
                                }
                                if (rowData[col] !== null) {
                                    $(td).html('<strong>' + rowData[col].replace('.', ',') + '</strong>');
                                }
                            },
                            className: "text-center",
                            width: 'auto',
                            "targets": [11, 12, 13]
                        }
                    ]
                });
            } else {
                $('#pdf').attr('onclick', 'return false').css('color', '#888');
            }

            table_colaboradores = $('#table_colaboradores').DataTable({
                "processing": true,
                "serverSide": true,
                "iDisplayLength": 25,
                "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "order": [[0, 'asc']],
                "language": {
                    "url": url
                },
                "ajax": {
                    "url": "<?php echo site_url('apontamento_colaboradores/ajax_list') ?>",
                    "type": "POST",
                    timeout: 90000,
                    data: function (d) {
                        d.busca = busca;
                        return d;
                    }
                },
                "columnDefs": [
                    {
                        className: "warning",
                        width: "20%",
                        "targets": [0]
                    },
                    {
                        className: "text-nowrap",
                        width: "auto",
                        "targets": [-1],
                        "orderable": false,
                        "searchable": false
                    }
                ]
            });

            table_apontamento_consolidado = $('#table_apontamento_consolidado').DataTable({
                dom: "<'row'<'col-sm-3'l><'#legenda.col-sm-5'><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                "processing": true,
                "serverSide": true,
                "iDisplayLength": 25,
                "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                "order": [[0, 'asc']],
                "language": {
                    "url": url
                },
                "ajax": {
                    "url": "<?php echo site_url('apontamento/ajax_list') ?>",
                    "type": "POST",
                    timeout: 90000,
                    data: function (d) {
                        d.busca = busca;
                        d.dia_fechamento = $('#dia_fechamento').val();
                        return d;
                    },
                    "dataSrc": function (json) {
                        $('[name="mes"]').val(json.calendar.mes);
                        $('[name="ano"]').val(json.calendar.ano);
                        $('#mes_ano').html(json.calendar.mes_ano[0].toUpperCase() + json.calendar.mes_ano.slice(1));
                        var dt1 = new Date();
                        var dt2 = new Date();
                        dt2.setFullYear(json.calendar.ano, (json.calendar.mes - 1));
                        if (dt1.getTime() < dt2.getTime()) {
                            $('#mes_seguinte').addClass('disabled').parent().css('cursor', 'not-allowed');
                        } else {
                            $('#mes_seguinte').removeClass('disabled').parent().css('cursor', '');
                        }

                        var semana = 1;
                        var colunasUsuario = 2;
                        for (i = 1; i <= 31; i++) {
                            if (i > 28) {
                                if (i > json.calendar.qtde_dias) {
                                    table_apontamento_consolidado.column(i + colunasUsuario).visible(false, false);
                                    continue;
                                } else {
                                    table_apontamento_consolidado.column(i + colunasUsuario).visible(true, false);
                                }
                            }
                            var coluna = $(table_apontamento_consolidado.columns(i + colunasUsuario).header());
                            coluna.text(json.calendar.dias[i]);
                            coluna.removeClass('text-danger').css('background-color', '');
                            if (json.calendar.semana[semana] === 'Sábado' || json.calendar.semana[semana] === 'Domingo') {
                                coluna.addClass('text-danger').css('background-color', '#dbdbdb');
                            }

                            if (json.calendar.dias[i] > json.calendar.dias[json.calendar.qtde_dias]) {
                                coluna.attr({
                                    'data-dia': json.calendar.ano_anterior + '-' + json.calendar.mes_anterior + '-' + coluna.text(),
                                    'data-mes_ano': json.calendar.semana[semana] + ', ' + coluna.text() + '/' + json.calendar.mes_anterior + '/' + json.calendar.ano_anterior,
                                    'title': json.calendar.semana[semana] + ', ' + coluna.text() + ' de ' + json.calendar.mes_ano_anterior.replace(' ', ' de ')
                                });

                                if (dt1.getFullYear() === parseInt(json.calendar.ano_anterior) && dt1.getMonth() === parseInt(json.calendar.mes_anterior - 1) && dt1.getDate() === parseInt(json.calendar.dias[i])) {
                                    coluna.css('background-color', '#0f0');
                                }
                            } else {
                                coluna.attr({
                                    'data-dia': json.calendar.ano + '-' + json.calendar.mes + '-' + coluna.text(),
                                    'data-mes_ano': json.calendar.semana[semana] + ', ' + coluna.text() + '/' + json.calendar.mes + '/' + json.calendar.ano,
                                    'title': json.calendar.semana[semana] + ', ' + coluna.text() + ' de ' + json.calendar.mes_ano.replace(' ', ' de ')
                                });

                                if (dt1.getFullYear() === parseInt(json.calendar.ano) && dt1.getMonth() === parseInt(json.calendar.mes - 1) && dt1.getDate() === parseInt(json.calendar.dias[i])) {
                                    coluna.css('background-color', '#0f0');
                                }
                            }

                            if (i % 7 === 0) {
                                semana = 1;
                            } else {
                                semana++;
                            }
                        }
                        if (json.draw === '1') {
                            $("#legenda").html('<button title="Mostrar legenda de eventos" data-toggle="modal" data-target="#modal_legenda" style="margin: 15px 10px 0;" class="btn btn-default btn-sm">' +
                                '<i class="glyphicon glyphicon-exclamation-sign"></i> <span class="hidden-xs"> Mostrar legenda de eventos</span>' +
                                '</button>');
                        }
                        return json.data;
                    }
                },
                "columnDefs": [
                    {
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if (rowData[col][2] !== null || rowData[col][3] !== null) {
                                if (rowData[col][5] === 'A') {
                                    $(td).addClass('colaborador-disabled');
                                } else if (rowData[col][5] === 'F') {
                                    $(td).addClass('colaborador-primary');
                                }
                                $(td).removeClass('warning');
                                $(td).attr({
                                    'title': (rowData[col][2] !== null ? 'Data de saída: ' + rowData[col][2] + '\n' : '') +
                                        (rowData[col][3] !== null ? 'Data de retorno: ' + rowData[col][3] + '\n' : '') +
                                        (rowData[col][4] !== '' && rowData[col][4] !== undefined ? 'Colaborador backup: ' + $('[name="id_usuario_bck"] option[value="' + rowData[col][4] + '"]').text() : '')
                                });
                            } else {
                                $(td).addClass('warning');
                                $(td).removeClass('colaborador-success');
                            }
                            $(td).attr({
                                'data-id': rowData[col][0],
                                'data-nome': rowData[col][1],
                                'data-recesso': rowData[col][2],
                                'data-retorno': rowData[col][3],
                                'data-id_usuario_bck': rowData[col][4],
                                'data-tipo': rowData[col][5]
                            });
                            $(td).html(rowData[col][1]);
                        },
                        width: 'auto',
                        "targets": [0]
                    },
                    {
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if (rowData[col][2] !== null || rowData[col][4]) {
                                $(td).addClass('colaborador-disabled text-center');
                                $(td).removeClass('warning');
                                $(td).attr({
                                    'title': (rowData[col][2] !== null ? 'Data de início das atividades: ' + rowData[col][2] + '\n' : '') +
                                        (rowData[col][3] === null || rowData[col][3] === undefined ? '' : 'Colaborador substituto: ' + $('[name="id_usuario_sub"] option[value="' + (rowData[col][3] > 0 ? rowData[col][3] : '') + '"]').text())
                                });
                                $(td).html(rowData[col][1] !== null ? rowData[col][1] : 'AL');
                            } else {
                                $(td).addClass('warning');
                                $(td).removeClass('colaborador-success');
                                $(td).html('');
                            }
                            $(td).attr({
                                'data-id': rowData[col][0],
                                'data-nome': rowData[col][1],
                                'data-desligamento': rowData[col][2],
                                'data-id_usuario_sub': rowData[col][3]
                            });
                        },
                        "targets": [1]
                    },
                    {
                        "createdCell": function (td, cellData, rowData, row, col) {
                            $(td).removeClass('text-success text-danger');
                            if (rowData[col] !== null && rowData[col].indexOf('-') === 0) {
                                $(td).addClass('text-danger');
                            } else if (rowData[col] !== null && rowData[col] !== '0:00' && rowData[col].indexOf('-') === -1) {
                                $(td).addClass('text-success');
                            }
                            $(td).html('<strong>' + rowData[col] + '</strong>')
                        },
                        className: 'text-center warning',
                        targets: [2]
                    },
                    {
                        "createdCell": function (td, cellData, rowData, row, col) {
                            var data_dia = $(table_apontamento_consolidado.column(col).header()).attr('data-dia');
                            var data_recesso = moment(rowData[0][2], 'DD/MM/YYYY').subtract(1, 'days');
                            var data_retorno = moment(rowData[0][3], 'DD/MM/YYYY').add(1, 'days');
                            var data_desligamento = moment(rowData[1][2], 'DD/MM/YYYY');

                            $(td).css('padding', '8px 1px');
                            if (rowData[col] === null || rowData[col][0] === undefined) {
                                $(td).css('background-color', '#dbdbdb').html('');
                            } else {
                                if ($(table_apontamento_consolidado.column(col).header()).hasClass('text-danger')) {
                                    $(td).css('background-color', '#e9e9e9');
                                }

                                if (moment(data_dia).isSameOrAfter(data_desligamento)) {
                                    $(td).css('border', '1px solid #B5BBD9');
                                } else if (moment(data_dia).isBetween(data_recesso, data_retorno)) {
                                    $(td).css('border', '1px solid #B2CEE6');
                                }

                                if (moment(data_dia).isSameOrAfter(data_desligamento)) {
                                    if (rowData[1][3] === null) {
                                        $(td).css({'background-color': '#dbdbdb', 'font-weight': 'bolder'}).html('');
                                    } else {
                                        $(td).css({'background-color': '#C9CDE1', 'font-weight': 'bolder'});
                                    }
                                } else if (moment(data_dia).isBetween(data_recesso, data_retorno)) {
                                    if (rowData[0][4] === null) {
                                        $(td).css({'background-color': '#dbdbdb', 'font-weight': 'bolder'}).html('');
                                    } else {
                                        $(td).css({'background-color': '#CCE2F4', 'font-weight': 'bolder'});
                                    }
                                } else if (rowData[col][9] === 'AE') {
                                    $(td).removeClass('date-width-success, date-width-danger').css('font-weight', 'bolder');
                                    if (rowData[col][18] < 0) {
                                        $(td).addClass('date-width-danger');
                                    } else if (rowData[col][18] > 0) {
                                        $(td).addClass('date-width-success');
                                    }
                                } else if (((rowData[col][9] === 'FJ' && rowData[col][1].length > 0) || ((rowData[col][9] === 'AJ' || rowData[col][9] === 'SJ') && rowData[col][2].length > 0)) && rowData[col][10].length === 0) {
                                    $(td).addClass('date-width-danger');
                                } else if (((rowData[col][9] === 'FN' && rowData[col][1].length > 0) || ((rowData[col][9] === 'AN' || rowData[col][9] === 'SN') && rowData[col][2].length > 0)) && rowData[col][10].length === 0) {
                                    $(td).addClass('date-width-danger');
                                } else if (rowData[col][9] === 'FR') {
                                    $(td).addClass('date-width-primary');
                                } else if (rowData[col][9] === 'PD' || rowData[col][9] === 'PI') {
                                    $(td).addClass('date-width-danger');
                                } else if (rowData[col][1] === '' || rowData[col][2] === '') {
                                    $(td).addClass('date-width-success');
                                } else if (rowData[col][0].length > 0) {
                                    $(td).css('background-color', '#ff0');
                                }
                                $(td).attr({
                                    'data-tooltip': 'tooltip',
                                    'data-placement': 'top',
                                    'title':
                                        (moment(data_dia).isSameOrAfter(data_desligamento) && rowData[1][3] ? 'Colaborador(a) substituto(a): ' + rowData[1][1] + '\nColaborador(a) principal: ' :
                                            (moment(data_dia).isBetween(data_recesso, data_retorno) && rowData[0][4] ? 'Colaborador(a) backup: ' + rowData[0][6] + '\nColaborador(a) principal: ' : '')) +
                                        rowData[0][1] + '\n' + $(table_apontamento_consolidado.column(col).header()).attr('title') +
                                        '\nEvento: ' + (rowData[col][9] === 'AJ' ? 'Atraso com atestado próprio' :
                                        rowData[col][9] === 'AN' ? 'Atraso sem atestado' :
                                            rowData[col][9] === 'FJ' ? 'Falta com atestado próprio' :
                                                rowData[col][9] === 'FN' ? 'Falta sem atestado' :
                                                    rowData[col][9] === 'SJ' ? 'Saída antecipada com atestado próprio' :
                                                        rowData[col][9] === 'SN' ? 'Saída antecipada sem atestado' :
                                                            rowData[col][9] === 'PD' ? 'Posto descoberto' :
                                                                rowData[col][9] === 'PI' ? 'Posto descontinuado' :
                                                                    rowData[col][9] === 'FR' ? 'Feriado' :
                                                                        moment(data_dia).isSameOrAfter(data_desligamento) && rowData[1][3] ? 'Desligamento' :
                                                                            moment(data_dia).isBetween(data_recesso, data_retorno) && rowData[0][4] ? 'Férias' : 'Ok') +
                                        (rowData[col][2] !== '' && rowData[col][2] !== undefined ? '\nHoras devedoras: ' + rowData[col][2] : '') +
                                        (rowData[col][16] !== '' && rowData[col][16] !== undefined ? '\nApontamento extra: ' + rowData[col][16] : '') +
                                        (rowData[col][17] !== '' && rowData[col][17] !== undefined ? '\nDesconto de apontamento: ' + rowData[col][17] : '') +
                                        (rowData[col][7] !== '' && rowData[col][7] !== undefined ? '\nDetalhes: ' + rowData[col][7] : '') +
                                        (rowData[col][14] !== '' && rowData[col][14] !== undefined ? '\nBackup nº 1: ' + rowData[col][14] : '') +
                                        (rowData[col][15] !== '' && rowData[col][15] !== undefined ? '\nBackup nº 2: ' + rowData[col][15] : '') +
                                        (rowData[col][8] !== '' && rowData[col][8] !== undefined ? '\nObservações: ' + rowData[col][8] : ''),
                                    'data-id_alocado': rowData[col][1],
                                    'data-text': rowData[col][5],
                                    'data-calendar': rowData[col][2],
                                    'data-id': rowData[col][0],
                                    'data-qtde_dias': rowData[col][1],
                                    'data-hora_atraso': rowData[col][2],
                                    'data-hora_entrada': rowData[col][3],
                                    'data-hora_intervalo': rowData[col][4],
                                    'data-hora_retorno': rowData[col][5],
                                    'data-hora_saida': rowData[col][6],
                                    'data-detalhes': rowData[col][7],
                                    'data-observacoes': rowData[col][8],
                                    'data-status': rowData[col][9],
                                    'data-id_alocado_bck': rowData[col][10],
                                    'data-id_alocado_bck2': rowData[col][11],
                                    'data-hora_glosa': rowData[col][12],
                                    'data-id_detalhes': rowData[col][13],
                                    'data-apontamento_extra': rowData[col][16],
                                    'data-apontamento_desc': rowData[col][17]
                                });
                                if (moment(data_dia).isSameOrAfter(data_desligamento) && rowData[1][3]) {
                                    $(td).attr({
                                        //'data-id_alocado': rowData[1][4]
                                        'data-id_alocado': rowData[0][0]
                                    });
                                } else if (moment(data_dia).isBetween(data_recesso, data_retorno) && rowData[0][4]) {
                                    $(td).attr({
                                        //'data-id_alocado': rowData[0][7]
                                        'data-id_alocado': rowData[0][0]
                                    });
                                } else {
                                    $(td).attr({
                                        'data-id_alocado': rowData[0][0]
                                    });
                                }
                                $(td).html(rowData[col][9] !== 'AE' ? rowData[col][9] : 'AP');
                            }
                        },
                        "className": 'text-center',
                        "targets": 'date-width',
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "createdCell": function (td, cellData, rowData, row, col) {
                            $(td).removeClass('text-success text-danger');
                            if (rowData[col] !== null) {
                                $(td).addClass('text-danger');
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        className: "warning text-right",
                        "targets": [-1, -2],
                        "orderable": false,
                        "searchable": false
                    }
                ]
            });

            if ('<?= $modo_privilegiado ?>') {
                table_totalizacao_consolidada = $('#table_totalizacao_consolidada').DataTable({
                    dom: "<'row'<'col-sm-3'l><'#calculo_consolidado.col-sm-5'><'col-sm-4'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    "processing": true,
                    "serverSide": true,
                    "iDisplayLength": 25,
                    "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                    "order": [[0, 'asc']],
                    "language": {
                        "url": url
                    },
                    "ajax": {
                        "url": "<?php echo site_url('apontamento_totalizacao/ajax_list') ?>",
                        "type": "POST",
                        timeout: 90000,
                        data: function (d) {
                            d.busca = busca;
                            d.dia_fechamento = $('#dia_fechamento').val();
                            d.calculo_totalizacao = $('[name="calculo_totalizacao_consolidado"]:checked').val();
                            if (d.calculo_totalizacao === undefined) {
                                d.calculo_totalizacao = '1';
                            }
                            return d;
                        },
                        "dataSrc": function (json) {
                            if (json.total === '0,00' || json.total === json.total_posto) {
                                $('#total_devido_consolidado').removeClass('text-danger');
                            } else {
                                $('#total_devido_consolidado').addClass('text-danger');
                            }
                            $('#total_posto_consolidado').html(json.total_posto);
                            $('#total_devido_consolidado').html(json.total);
                            $('#total_percentual_consolidado').html(json.total_percentual + '%');
                            if (json.draw === '1') {
                                $("#calculo_consolidado").css({'padding': '16px 0 0 32px', 'text-align': 'left'});
                                $("#calculo_consolidado").append('<div class="radio"><label><?= form_radio('calculo_totalizacao_consolidado" onclick="calcular_totalizacao_consolidada()', '1', true) ?> Cálculo por dia/hora</label></div> &emsp;');
                                $("#calculo_consolidado").append('<div class="radio"><label><?= form_radio('calculo_totalizacao_consolidado" onclick="calcular_totalizacao_consolidada()', '2', false) ?> Cálculo por percentual</label></div>');
                            }
                            return json.data;
                        }
                    },
                    "columnDefs": [
                        {
                            className: "warning",
                            "targets": [0]
                        },
                        {
                            "createdCell": function (td, cellData, rowData, row, col) {
                                if (rowData[col] !== null) {
                                    $(td).addClass('text-danger');
                                    $(td).html('<strong>' + rowData[col] + '</strong>');
                                }
                            },
                            className: "text-center",
                            "targets": [1, 2, 3, 4],
                            "searchable": false
                        },
                        {
                            "createdCell": function (td, cellData, rowData, row, col) {
                                if (rowData[col] !== null) {
                                    if (rowData[col] < rowData[3]) {
                                        $(td).addClass('text-danger');
                                    }
                                    $(td).html('<strong>' + rowData[col] + '</strong>');
                                }
                            },
                            className: "text-center",
                            "targets": [10],
                            "searchable": false
                        },
                        {
                            "createdCell": function (td, cellData, rowData, row, col) {
                                if (rowData[col] !== null) {
                                    $(td).html('<strong>' + rowData[col] + '</strong>');
                                }
                            },
                            className: "text-center",
                            "targets": [3, 4, 5, 6, 7, 8, 9],
                            "searchable": false
                        },
                        {
                            "createdCell": function (td, cellData, rowData, row, col) {
                                $(td).css('cursor', 'pointer');
                                $(td).attr({
                                    'data-id': rowData[14],
                                    'data-dias': rowData[11],
                                    'data-horas': rowData[12],
                                    'data-total': rowData[13]
                                });
                                if (rowData[13] !== null) {
                                    if (rowData[6] < rowData[3]) {
                                        $(td).addClass('text-danger');
                                    }
                                }
                                if (rowData[col] !== null) {
                                    $(td).html('<strong>' + rowData[col].replace('.', ',') + '</strong>');
                                }
                            },
                            className: "text-center",
                            width: 'auto',
                            "targets": [11, 12, 13]
                        }
                    ]
                });
            }

            atualizarColaboradores();
            setPdf_atributes();

        });

        function atualizarFiltro() {
            $.ajax({
                url: "<?php echo site_url('apontamento/atualizar_filtro/') ?>",
                type: "POST",
                dataType: "JSON",
                data: $('#busca').serialize(),
                success: function (data) {
                    $('[name="area"]').replaceWith(data.area);
                    $('[name="setor"]').replaceWith(data.setor);
                    $('[name="cargo"]').replaceWith(data.cargo);
                    $('[name="funcao"]').replaceWith(data.funcao);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function atualizarColaboradores() {
            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_colaboradores/') ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    busca: busca
                },
                success: function (data) {
                    $('#form_colaborador [name="id"]').val(data.id);
                    $('#form_colaborador [name="id_usuario"]').html($(data.id_usuario).html());
                    $('#form_colaborador_alocado [name="id"]').html($(data.id_usuario_alocado).html());
                    $('#form_config [name="dia_fechamento"]').val(data.dia_fechamento);
                    $('[name="id_usuario_bck"]').html($(data.id_usuario_bck).html());
                    $('[name="id_usuario_sub"]').html($(data.id_usuario_sub).html());
                    $('[name="id_alocado_bck"]').html($(data.id_alocado_bck).html());
                    // Usado somente para a área "Ipesp"
                    if (data.ipesp === null) {
                        $('#ipesp').hide();
                    } else {
                        $('#ipesp').show();
                    }
                    // Usado somente para o setor "Teleatendimento" da área "Ipesp"
                    // if (data.teleatendimento === null) {
                    //     $('#config_ipesp, #atividades_mensais').parent('li').css('display', 'none');
                    // } else {
                    //     $('#config_ipesp, #atividades_mensais').parent('li').css('display', 'block');
                    // }

                    if (data.dia_fechamento > 0) {
                        $('.nav-tabs li:eq(3), .nav-tabs li:eq(4)').show();
                    } else {
                        $('.nav-tabs li:eq(3), .nav-tabs li:eq(4)').hide();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function atualizarDetalhes() {
            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_edit/') ?>",
                type: "POST",
                dataType: "html",
                success: function (data) {
                    $('#detalhes').replaceWith(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function atualizarSetor() {
            $.ajax({
                url: "<?php echo site_url('apontamento_colaboradores/ajax_setores/') ?>",
                type: "POST",
                dataType: "html",
                data: {
                    area: $('#form_colaborador [name="area"]').val(),
                    setor: $('#form_colaborador [name="setor"]').val()
                },
                success: function (data) {
                    $('#form_colaborador [name="setor"]').html($(data).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function calcular_totalizacao() {
            table_totalizacao.ajax.reload();
            setPdf_atributes();
        }

        function calcular_totalizacao_consolidada() {
            table_totalizacao_consolidada.ajax.reload();
            setPdf_atributes();
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

            atualizarFiltro();
        });

        $('#modal_form').on('show.bs.modal', function (event) {
            var event = $(event.relatedTarget);
            $('[name="dado1"]').val(event.data('status'));
            $('[name="dado2"]').val(event.data('text'));
        });

        $('#modal_config').on('show.bs.modal', function (event) {
            $('#form_config')[0].reset();
            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_config') ?>",
                type: "POST",
                dataType: "JSON",
                data: busca,
                success: function (json) {
                    $('#modal_config .modal-title').html('Observações do mês - ' + json.mes + '/' + json.ano);

                    $('#form_config [name="mes"]').val(json.mes);
                    $('#form_config [name="ano"]').val(json.ano);
                    $('#form_config [name="valor_projetado"]').val(json.valor_projetado);
                    $('#form_config [name="valor_realizado"]').val(json.valor_realizado);
                    $('#form_config [name="total_faltas"]').val(json.total_faltas);
                    $('#form_config [name="total_dias_cobertos"]').val(json.total_dias_cobertos);
                    $('#form_config [name="total_dias_descobertos"]').val(json.total_dias_descobertos);
                    $('#form_config [name="dia_fechamento"]').val(json.dia_fechamento);
                    $('#form_config [name="qtde_alocados_potenciais"]').val(json.qtde_alocados_potenciais);
                    $('#form_config [name="qtde_alocados_ativos"]').val(json.qtde_alocados_ativos);
                    $('#form_config [name="contrato"]').val(json.contrato);
                    $('#form_config [name="observacoes"]').val(json.observacoes);
                    $('#form_config [name="descricao_servico"]').val(json.descricao_servico);
                    $('#form_config [name="valor_servico"]').val(json.valor_servico);

                    $('#form_config [name="turnover_reposicao"]').val(json.turnover_reposicao);
                    $('#form_config [name="turnover_aumento_quadro"]').val(json.turnover_aumento_quadro);
                    $('#form_config [name="turnover_desligamento_empresa"]').val(json.turnover_desligamento_empresa);
                    $('#form_config [name="turnover_desligamento_colaborador"]').val(json.turnover_desligamento_colaborador);

                    if (json.mes_bloqueado) {
                        $('#bloquear_mes').prop('checked', true);
                        $('#btnSaveConfig').prop('disabled', true);
                    } else {
                        $('#btnSaveConfig').prop('disabled', false);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });

        $('#form_config_ipesp [name="balanco_valor_projetado"], #form_config_ipesp [name="balanco_valor_glosa"]').on('change', function () {
            var valor_projetado = parseFloat($('#form_config_ipesp [name="balanco_valor_projetado"]').val().replace('.', '').replace(',', '.'));
            var valor_glosa = parseFloat($('#form_config_ipesp [name="balanco_valor_glosa"]').val().replace('.', '').replace(',', '.'));
            var glosa = valor_projetado - valor_glosa;
            var porcentagem = glosa * 100 / (valor_projetado < 1 ? 1 : valor_projetado);
            $('#form_config_ipesp [name="balanco_glosas"]').val(glosa.toFixed(2).replace('.', ','));
            $('#form_config_ipesp [name="balanco_porcentagem"]').val(porcentagem.toFixed(1).replace('.', ','));
        });

        $('#modal_config_ipesp').on('show.bs.modal', function (event) {
            $('#form_config_ipesp')[0].reset();
            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_config_ipesp') ?>",
                type: "POST",
                dataType: "JSON",
                data: busca,
                success: function (json) {
                    $('#modal_config_ipesp .modal-title').html('Observações do mês para IPESP/Teleatendimento - ' + json.mes + '/' + json.ano);

                    $('#form_config_ipesp [name="id"]').val(json.id);
                    $('#form_config_ipesp [name="id_alocacao"]').val(json.id_alocacao);

                    $('#form_config_ipesp [name="total_colaboradores_contratados"]').val(json.total_colaboradores_contratados);
                    $('#form_config_ipesp [name="total_colaboradores_ativos"]').val(json.total_colaboradores_ativos);

                    $('#form_config_ipesp [name="visitas_projetadas"]').val(json.visitas_projetadas);
                    $('#form_config_ipesp [name="visitas_realizadas"]').val(json.visitas_realizadas);
                    $('#form_config_ipesp [name="visitas_porcentagem"]').val(json.visitas_porcentagem);
                    $('#form_config_ipesp [name="visitas_total_horas"]').val(json.visitas_total_horas);

                    $('#form_config_ipesp [name="balanco_valor_projetado"]').val(json.balanco_valor_projetado);
                    $('#form_config_ipesp [name="balanco_glosas"]').val(json.balanco_glosas);
                    $('#form_config_ipesp [name="balanco_valor_glosa"]').val(json.balanco_valor_glosa);
                    $('#form_config_ipesp [name="balanco_porcentagem"]').val(json.balanco_porcentagem);

                    $('#form_config_ipesp [name="turnover_admissoes"]').val(json.turnover_admissoes);
                    $('#form_config_ipesp [name="turnover_demissoes"]').val(json.turnover_demissoes);
                    $('#form_config_ipesp [name="turnover_desligamentos"]').val(json.turnover_desligamentos);

                    $('#form_config_ipesp [name="atendimentos_total_mes"]').val(json.atendimentos_total_mes);
                    $('#form_config_ipesp [name="atendimentos_media_diaria"]').val(json.atendimentos_media_diaria);

                    $('#form_config_ipesp [name="pendencias_total_informada"]').val(json.pendencias_total_informada);
                    $('#form_config_ipesp [name="pendencias_aguardando_tratativa"]').val(json.pendencias_aguardando_tratativa);
                    $('#form_config_ipesp [name="pendencias_parcialmente_resolvidas"]').val(json.pendencias_parcialmente_resolvidas);
                    $('#form_config_ipesp [name="pendencias_resolvidas"]').val(json.pendencias_resolvidas);
                    $('#form_config_ipesp [name="pendencias_resolvidas_atendimentos"]').val(json.pendencias_resolvidas_atendimentos);

                    $('#form_config_ipesp [name="monitoria_media_equipe"]').val(json.monitoria_media_equipe);

                    $('#form_config_ipesp [name="indicadores_operacionais_tma"]').val(json.indicadores_operacionais_tma);
                    $('#form_config_ipesp [name="indicadores_operacionais_tme"]').val(json.indicadores_operacionais_tme);
                    $('#form_config_ipesp [name="indicadores_operacionais_ociosidade"]').val(json.indicadores_operacionais_ociosidade);

                    $('#form_config_ipesp [name="avaliacoes_atendimento"]').val(json.avaliacoes_atendimento);
                    $('#form_config_ipesp [name="avaliacoes_atendimento_otimos"]').val(json.avaliacoes_atendimento_otimos);
                    $('#form_config_ipesp [name="avaliacoes_atendimento_bons"]').val(json.avaliacoes_atendimento_bons);
                    $('#form_config_ipesp [name="avaliacoes_atendimento_regulares"]').val(json.avaliacoes_atendimento_regulares);
                    $('#form_config_ipesp [name="avaliacoes_atendimento_ruins"]').val(json.avaliacoes_atendimento_ruins);

                    $('#form_config_ipesp [name="solicitacoes"]').val(json.solicitacoes);
                    $('#form_config_ipesp [name="solicitacoes_atendidas"]').val(json.solicitacoes_atendidas);
                    $('#form_config_ipesp [name="solicitacoes_nao_atendidas"]').val(json.solicitacoes_nao_atendidas);

                    $('#form_config_ipesp [name="observacoes"]').val(json.observacoes);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });

        $('[name="status"]').on('change', function () {
            var status = $(this).val();
            selecionar_status(status);
            if (status === 'FJ' || status === 'FN') {
                if ($('[name="id_alocado_bck"]').val() === '' && $('[name="id_alocado_bck2"]').val() === '') {
                    if ($('[name="qtde_dias"]').val() > 0 === false) {
                        $('[name="qtde_dias"]').val(1);
                    }
                } else {
                    $('[name="qtde_dias"]').val(0);
                }
                $('.hora:not([name="hora_glosa"])').val('');
            } else {
                $('[name="qtde_dias"]').val('');
            }
        });

        $('#form_colaborador [name="area"]').on('change', function () {
            atualizarSetor();
        });

        $('#form_colaborador_alocado [name="id"]').on('change', function () {
            $('#btnDeleteColaborador').prop('disabled', this.value.length === 0);
        });

        $('[name="id_alocado_bck"]').on('change', function () {
            if (this.value === '') {
                if ($('[name="status"]').val() === 'FJ' || $('[name="status"]').val() === 'FN') {
                    $('[name="qtde_dias"]').val(1);
                }
            } else {
                $('[name="qtde_dias"], [name="hora_atraso"]').val('');
            }
        });

        $('[name="id_usuario"]').on('change', function () {
            $('#copiar_posto').prop('disabled', this.value.length === 0);
        });

        $('#calcular_valor').on('click', function () {
            calcular_valores();
        });

        $('#form_colaborador .valor').on('change', function () {
            calcular_valores();
        });

        $('#modal_colaborador').on('show.bs.modal', function () {
            $('#modal_colaborador [name="mes"]').val($('#busca [name="mes"]').val());
            $('#modal_colaborador [name="ano"]').val($('#busca [name="ano"]').val());
            $('[name="valor_posto"], [name="valor_dia"], [name="valor_hora"]').val('');
            $('[name="total_dias_mensais"], [name="total_horas_diarias"]').val('');
            $('#copiar_posto').prop('disabled', true);
        });

        function calcular_valores() {
            var valor = $('[name="valor_posto"]').val();
            var dias = $('[name="total_dias_mensais"]').val();
            var horas = $('[name="total_horas_diarias"]').val();

            var valor_dia = 0;
            var valor_hora = 0;

            if (valor.length > 0) {
                if (dias.length > 0) {
                    valor_dia = (valor / dias);
                }
                if (horas.length > 0) {
                    valor_hora = (valor / horas);
                }
            }
            $('[name="valor_dia"]').val(valor_dia.toFixed(2));
            $('[name="valor_hora"]').val(valor_hora.toFixed(2));
        }

        function get_posto_anterior() {
            $.ajax({
                url: "<?php echo site_url('apontamento_postos/ajax_posto/') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id_usuario: $('[name="id_usuario"]').val()},
                success: function (data) {
                    $('[name="total_dias_mensais"]').val(data.total_dias_mensais);
                    $('[name="total_horas_diarias"]').val(data.total_horas_diarias);
                    $('[name="valor_posto"]').val(data.valor_posto);
                    $('[name="valor_dia"]').val(data.valor_dia);
                    $('[name="valor_hora"]').val(data.valor_hora);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function sugestao_detalhe(event) {
            $('[name="detalhes"]').val($(event).text());
        }

        function selecionar_status(value) {
            $('.hora, [name="qtde_dias"]').css({
                'background-color': '#98d298',
                'color': '#3c763d'
            }).prop('readonly', false);
            $('[name="id_alocado_bck"]').css({
                'background-color': '#98d298',
                'color': '#3c763d'
            }).find('option:not(:selected)').prop('disabled', false);
            $('[name="detalhes"], #form_config [name="observacoes"]').css({
                'background-color': '#337ab7',
                'color': '#fff'
            }).find('option:not(:selected)').prop('disabled', false);


            switch (value) {
                case 'FJ':
                case 'PD':
                    $('.hora').val('').css({'background-color': '#fff', 'color': ''}).prop('readonly', true);
                    break;
                case 'FN':
                    $('.hora:not([name="hora_glosa"])').val('').css({
                        'background-color': '#fff',
                        'color': ''
                    }).prop('readonly', true);
                    break;
                case 'FR':
                    $('.hora, [name="qtde_dias"]').val('').css({
                        'background-color': '#fff',
                        'color': '#000'
                    }).prop('readonly', true);
                    $('[name="id_alocado_bck"], [name="detalhes"]').css({
                        'background-color': '#fff',
                        'color': '#000'
                    }).val('').find('option:not(:selected)').prop('disabled', true);
                    break;
                case 'AJ':
                case 'SJ':
                    $('.hora:not([name="hora_atraso"]), [name="qtde_dias"]').css({
                        'background-color': '#fff',
                        'color': ''
                    }).val('').prop('readonly', true);
                    break;
                case 'AN':
                case 'SN':
                    $('.hora:not([name="hora_atraso"], [name="hora_glosa"]), [name="qtde_dias"]').css({
                        'background-color': '#fff',
                        'color': ''
                    }).val('').prop('readonly', true);
                    break;
                case 'AE':
                    $('.hora:not([name="apontamento_extra"], [name="apontamento_desc"]), [name="qtde_dias"]').css({
                        'background-color': '#fff',
                        'color': ''
                    }).val('').prop('readonly', true);
                    $('[name="id_alocado_bck"]').css({
                        'background-color': '#fff',
                        'color': '#000'
                    }).val('').find('option:not(:selected)').prop('disabled', true);
                    break;
                /*case 'PD':
                    $('.hora:not([name="hora_glosa"])').css({
                        'background-color': '#fff',
                        'color': ''
                    }).val('').prop('readonly', true);
                    $('[name="id_alocado_bck"]').css({
                        'background-color': '#fff',
                        'color': '#000'
                    }).val('').find('option:not(:selected)').prop('disabled', true);
                    break;*/
                case 'PI':
                    $('.hora').css({'background-color': '#fff', 'color': ''}).val('').prop('readonly', true);
                    $('[name="id_alocado_bck"]').css({
                        'background-color': '#fff',
                        'color': '#000'
                    }).val('').find('option:not(:selected)').prop('disabled', true);
                    break;
                default:
                    $('[name="qtde_dias"], .hora').css({'background-color': '', 'color': ''});
            }
        }

        function proximo_mes(value = 1) {
            if ($('#mes_seguinte').hasClass('disabled') && value === 1) {
                return false;
            }

            //        var queryStr_busca = busca.split('&');
            //        var arr_busca = {};
            //        $(queryStr_busca).each(function (i) {
//            var param = queryStr_busca[i].split('=');
//            arr_busca[param[0]] = escape(param[1]);
//        });

//        var dt = new Date(arr_busca.ano, arr_busca.mes - 1);
            var dt = new Date($('[name="ano"]').val(), $('[name="mes"]').val() - 1);
            dt.setMonth(dt.getMonth() + (value));
            $('[name="mes"]').val(dt.getMonth() < 9 ? '0' + (dt.getMonth() + 1) : dt.getMonth() + 1);
            $('[name="ano"]').val(dt.getFullYear());
//        arr_busca.mes = (dt.getMonth() < 9 ? '0' + (dt.getMonth() + 1) : dt.getMonth() + 1);
//        arr_busca.ano = dt.getFullYear();

//        busca = $.param(arr_busca);
            busca = $('#busca').serialize();
            atualizarColaboradores();
            reload_table(true);
            setPdf_atributes();
        }

        function filtrar() {
            var data_proximo_mes = new Date();
            var data_busca = new Date();
            data_proximo_mes.setDate(1);
            data_proximo_mes.setMonth(data_proximo_mes.getMonth() + 1);
            data_busca.setFullYear($('[name="ano"]').val(), ($('[name="mes"]').val() - 1), 1);
            if (data_proximo_mes.getTime() < data_busca.getTime()) {
                $('[name="mes"]').val(data_proximo_mes.getMonth() + 1);
                $('[name="ano"]').val(data_proximo_mes.getFullYear());
            }

            busca = $('#busca').serialize();
            atualizarColaboradores();
            reload_table(true);
            $('#alerta_depto').text($('[name="depto"] option:selected').html());
            $('#alerta_area').text($('[name="area"] option:selected').html());
            $('#alerta_setor').text($('[name="setor"] option:selected').html());
            setPdf_atributes();
        }

        function add_mes() {
            $.ajax({
                url: "<?php echo site_url('apontamento/novo/') ?>",
                type: "POST",
                dataType: "JSON",
                data: busca,
                success: function (data) {
                    atualizarColaboradores();
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function add_colaborador() {
            $.ajax({
                url: "<?php echo site_url('apontamento_colaboradores/novo/') ?>",
                type: "POST",
                dataType: "JSON",
                data: busca,
                success: function (data) {
                    atualizarColaboradores();
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function excluir_mes() {
            if (confirm('Deseja limpar o mês selecionado?')) {

                $.ajax({
                    url: "<?php echo site_url('apontamento/ajax_limpar/') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: busca,
                    success: function (data) {
                        atualizarColaboradores();
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
            }
        }

        function edit_colaboradores(id) {
            $('#form_colaborador')[0].reset(); // reset form on modals
            $('#form_colaborador input[type="hidden"]').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            $.ajax({
                url: "<?php echo site_url('apontamento_colaboradores/ajax_edit/') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data) {
                    $('#form_colaborador [name="id"]').val(data.id);
                    $('#form_colaborador [name="area"]').val(data.area);
                    $('#form_colaborador [name="setor"]').val(data.setor);
                    atualizarSetor();
                    $('#form_colaborador [name="contrato"]').val(data.contrato);
                    $('#form_colaborador [name="telefone"]').val(data.telefone);
                    $('#form_colaborador [name="email"]').val(data.email);
                    $('#form_colaborador [name="status"]').val(data.status);
                    $('#colaborador').html(data.nome);
                    $('#modal_colaboradores').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_status(id) {
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_avaliado"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp_avaliados/edit_status/') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data) {
                    $('[name="id"]').val(data.id);
                    $('#nome').text(data.nome);
                    $('[name="observacoes"]').val(data.observacoes);
                    $('#modal_form').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function reload_table(reset = false) {
            edicaoEvento = false;
            $('#mes_ano').append('&ensp;(Processando - Aguarde...)');
            var count = 0;
            var stmt = function (json) {
                count = count + 1;
                if (count === 5) {
                    edicaoEvento = true;
                }
            };
            table.ajax.reload(stmt, reset); //reload datatable ajax
            if ('<?= $modo_privilegiado ?>') {
                table_totalizacao.ajax.reload(stmt, reset); //reload datatable ajax
                table_totalizacao_consolidada.ajax.reload(stmt, reset); //reload datatable ajax
            } else {
                count = count + 2;
            }
            table_colaboradores.ajax.reload(stmt, reset); //reload datatable ajax
            table_apontamento_consolidado.ajax.reload(stmt, reset); //reload datatable ajax
        }

        function salvar_configuracoes() {
            $('#btnSaveConfig').text('Salvando...').attr('disabled', true);
            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_saveConfig') ?>",
                type: "POST",
                dataType: "JSON",
                data: (busca + '&' + $('#form_config').serialize()),
                success: function (data) {
                    $('#modal_config').modal('hide');
                    $('#btnSaveConfig').text('Salvar').attr('disabled', false);
                    table_apontamento_consolidado.ajax.reload(null, true)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveConfig').text('Salvar').attr('disabled', false);
                }
            });
        }

        function salvar_configuracoes_ipesp() {
            $('#btnSaveConfigIpesp').text('Salvando...').attr('disabled', true);
            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_saveConfig_ipesp') ?>",
                type: "POST",
                dataType: "JSON",
                data: (busca + '&' + $('#form_config_ipesp').serialize()),
                success: function (data) {
                    $('#modal_config_ipesp').modal('hide');
                    $('#btnSaveConfigIpesp').text('Salvar').attr('disabled', false);
                    table_apontamento_consolidado.ajax.reload(null, true)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveConfigIpesp').text('Salvar').attr('disabled', false);
                }
            });
        }

        function salvar_ferias() {
            $('#btnSaveBackup').text('Salvando...'); //change button text
            $('#btnSaveBackup, #btnLimparBackup').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_ferias') ?>",
                type: "POST",
                data: $('#form_backup').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_backup').modal('hide');
                        reload_table();
                    }

                    $('#btnSaveBackup').text('Salvar'); //change button text
                    $('#btnSaveBackup, #btnLimparBackup').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.statusText === 'OK') {
                        alert(jqXHR.responseText);
                    } else {
                        alert('Erro ao enviar formulário');
                    }

                    $('#btnSaveBackup').text('Salvar'); //change button text
                    $('#btnSaveBackup, #btnLimparBackup').attr('disabled', false); //set button enable
                }
            });
        }

        function salvar_substituto() {
            $('#btnSaveSubstituto').text('Salvando...'); //change button text
            $('#btnSaveSubstituto, #btnLimparSubstituto').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_substituto') ?>",
                type: "POST",
                data: $('#form_substituto').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_substituto').modal('hide');
                        reload_table();
                    }

                    $('#btnSaveSubstituto').text('Salvar'); //change button text
                    $('#btnSaveSubstituto, #btnLimparSubstituto').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.statusText === 'OK') {
                        alert(jqXHR.responseText);
                    } else {
                        alert('Erro ao enviar formulário');
                    }

                    $('#btnSaveSubstituto').text('Salvar'); //change button text
                    $('#btnSaveSubstituto, #btnLimparSubstituto').attr('disabled', false); //set button enable
                }
            });
        }

        function salvar_backup2() {
            $('#btnSaveBackup2').text('Salvando...'); //change button text
            $('#btnSaveBackup2, #btnLimparBackup2').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_backup2') ?>",
                type: "POST",
                data: $('#form_backup2').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_backup2').modal('hide');
                        reload_table();
                    }

                    $('#btnSaveBackup2').text('Salvar'); //change button text
                    $('#btnSaveBackup2, #btnLimparBackup2').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.statusText === 'OK') {
                        alert(jqXHR.responseText);
                    } else {
                        alert('Erro ao enviar formulário');
                    }

                    $('#btnSaveBackup2').text('Salvar'); //change button text
                    $('#btnSaveBackup2, #btnLimparBackup2').attr('disabled', false); //set button enable
                }
            });
        }

        function limpar_ferias() {
            if (confirm('Deseja limpar o evento?')) {
                $('#form_backup')[0].reset();
                salvar_ferias();
            }
        }

        function limpar_substituto() {
            if (confirm('Deseja limpar o evento?')) {
                $('#form_substituto')[0].reset();
                salvar_substituto();
            }
        }

        function limpar_backup2() {
            if (confirm('Deseja limpar o conteúdo?')) {
                $('#form_backup2')[0].reset();
                salvar_backup2();
            }
        }

        function save() {
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave, #btnApagar').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_save') ?>",
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }

                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave, #btnApagar').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave, #btnApagar').attr('disabled', false); //set button enable
                }
            });
        }

        if ('<?= $modo_privilegiado ?>') {
            function save_totalizacao() {
                $('#btnSaveTotalizacao').text('Salvando...'); //change button text
                $('#btnSaveTotalizacao').attr('disabled', true); //set button disable

                // ajax adding data to database
                $.ajax({
                    url: "<?php echo site_url('apontamento_totalizacao/ajax_save') ?>",
                    type: "POST",
                    data: $('#form_totalizacao').serialize(),
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status) //if success close modal and reload ajax table
                        {
                            $('#modal_totalizacao').modal('hide');
                            reload_table();
                        }

                        $('#btnSaveTotalizacao').text('Salvar'); //change button text
                        $('#btnSaveTotalizacao').attr('disabled', false); //set button enable
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                        $('#btnSaveTotalizacao').text('Salvar'); //change button text
                        $('#btnSaveTotalizacao').attr('disabled', false); //set button enable
                    }
                });
            }
        }

        function save_colaborador() {
            $('#btnSaveColaborador').text('Alocando...'); //change button text
            $('#btnSaveColaborador').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('apontamento_colaboradores/ajax_save') ?>",
                type: "POST",
                data: $('#form_colaborador').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_colaborador').modal('hide');
                        reload_table();
                        atualizarColaboradores();
                    }

                    $('#btnSaveColaborador').text('Alocar'); //change button text
                    $('#btnSaveColaborador').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                    if (textStatus !== null) {
                        alert(textStatus);
                    } else {
                        alert('Error adding / update data');
                    }
                    $('#btnSaveColaborador').text('Alocar'); //change button text
                    $('#btnSaveColaborador').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_colaborador() {
            if (confirm('Deseja remover a alocação do colaborador selecionado?')) {
                $('#btnDeleteColaborador').text('Desalocando...'); //change button text
                $('#btnDeleteColaborador').attr('disabled', true); //set button disable

                // ajax adding data to database
                $.ajax({
                    url: "<?php echo site_url('apontamento_colaboradores/ajax_delete') ?>",
                    type: "POST",
                    data: $('#form_colaborador_alocado').serialize(),
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status) //if success close modal and reload ajax table
                        {
                            $('#modal_colaborador_alocado').modal('hide');
                            reload_table();
                            atualizarColaboradores();
                        }

                        $('#btnDeleteColaborador').text('Desalocar'); //change button text
                        $('#btnDeleteColaborador').prop('disabled', true); //set button enable
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                        $('#btnDeleteColaborador').text('Desalocar'); //change button text
                        $('#btnDeleteColaborador').attr('disabled', false); //set button enable
                    }
                });
            }
        }

        function apagar() {
            if (confirm('Deseja limpar o evento selecionado?')) {

                $('#btnApagar').text('Limpando...'); //change button text
                $('#btnApagar').attr('disabled', true); //set button disable
                $('#btnSave').attr('disabled', true); //set button disable

                // ajax adding data to database
                $.ajax({
                    url: "<?php echo site_url('apontamento/ajax_delete') ?>",
                    type: "POST",
                    data: {
                        id: $('[name="id"]').val()
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status) //if success close modal and reload ajax table
                        {
                            $('#modal_form').modal('hide');
                            reload_table();
                        }

                        $('#btnApagar').text('Limpar evento'); //change button text
                        $('#btnApagar').attr('disabled', false); //set button enable
                        $('#btnSave').attr('disabled', false); //set button enable
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(textStatus);
                        alert('Error adding / update data');
                        $('#btnApagar').text('Limpar evento'); //change button text
                        $('#btnApagar').attr('disabled', false); //set button enable
                        $('#btnSave').attr('disabled', false); //set button enable
                    }
                });
            }
        }

        $('#bloquear_mes').on('change', function () {
            console.log(2);
            $.ajax({
                url: "<?php echo site_url('apontamento/bloquearMes') ?>",
                type: "POST",
                data: busca + '&mes_bloqueado=' + ($('#bloquear_mes').is(':checked') ? '1' : ''),
                dataType: "json",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        if ($('#bloquear_mes').is(':checked')) {
                            $('#btnSaveConfig').prop('disabled', true);
                        } else {
                            $('#btnSaveConfig').prop('disabled', false);
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(textStatus);
                }
            });
        });

        function bloquear_mes() {
            console.log(1);
            $.ajax({
                url: "<?php echo site_url('apontamento/bloquearMes') ?>",
                type: "POST",
                data: $(busca + ($('#bloquear_mes').is(':checked') ? '&mes_bloqueado=1' : '')).serialize(),
                dataType: "json",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        if ($('#bloquear_mes').is(':checked')) {
                            $('#btnSaveConfig').addClass('disabled');
                        } else {
                            $('#btnSaveConfig').addClass('disabled');
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(textStatus);
                }
            });
        }

        function setPdf_atributes() {
            var search = '';
            var q = new Array();
            $('#busca select').each(function (i, v) {
                if (v.value.length > 0 && (v.value !== 'Todos' || v.value !== 'Todas')) {
                    q[i] = v.name + "=" + v.value;
                }
            });
            q.push("ano=" + $('[name="ano"]').val());
            q.push("calculo_totalizacao=" + $('[name="calculo_totalizacao"]:checked').val());

            q = q.filter(function (v) {
                return v.length > 0;
            });
            if (q.length > 0) {
                search = '/q?' + q.join('&');
            }

            $('#pdf').prop('href', "<?= site_url('apontamento_relatorios/index'); ?>" + search);
            $('#bck').prop('href', "<?= site_url('apontamento_backups/index'); ?>" + search);
            $('#financas').prop('href', "<?= site_url('apontamento_financas/index'); ?>" + search);
            $('#gestao_consolidada').prop('href', "<?= site_url('apontamento_gestao_consolidada/index'); ?>" + search);
            $('#atividades_mensais').prop('href', "<?= site_url('apontamento_relatorios/atividades_mensais'); ?>" + search);

            if ($('[name="area"]').val().length) {
                $('#bck, #gestao_consolidada').removeAttr('onclick').css('color', '#333');
            } else {
                $('#bck, #gestao_consolidada').attr('onclick', 'return false').css('color', '#888');
            }

            if ($('[name="setor"]').val().length) {
                $('#pdf, #financas, #config').removeAttr('onclick').css('color', '#333');
                $('#config').attr({'data-toggle': 'modal', 'data-target': '#modal_config'});
            } else {
                $('#pdf, #financas, #config').attr('onclick', 'return false').css('color', '#888');
                $('#config').removeAttr('data-toggle');
                $('#config').removeAttr('data-target');
            }

            if ($('[name="setor"]').val() === 'Teleatendimento') {
                $('#config_ipesp, #atividades_mensais').parent('li').css('display', 'block');
            } else {
                $('#config_ipesp, #atividades_mensais').parent('li').css('display', 'none');
            }
        }

    </script>

<?php
require_once "end_html.php";
?>