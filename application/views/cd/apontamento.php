<?php
require_once APPPATH . 'views/header.php';
?>
    <style>
        #table_processing,
        #table_funcionarios_processing,
        #table_cuidadores_processing,
        #table_frequencias_processing {
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
                        <li class="active">Apontamentos Diários</li>
                        <!--                        --><?php //$this->load->view('modal_processos', ['url' => 'cd/apontamento']); ?>
                    </ol>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if ($modo_privilegiado): ?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        <i class="glyphicon glyphicon-list-alt"></i> Gerenciar <span
                                                class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="colaboradores"><i
                                                        class="glyphicon glyphicon-list text-primary"></i> Colaboradores</a>
                                        </li>
                                        <?php if (in_array($this->session->userdata('nivel'), array(0, 7, 8, 9))): ?>
                                            <li><a href="diretorias"><i
                                                            class="glyphicon glyphicon-list text-primary"></i>
                                                    Contratos/Diretorias</a></li>
                                        <?php endif; ?>
                                        <li><a href="escolas"><i class="glyphicon glyphicon-list text-primary"></i>
                                                Escolas</a></li>
                                        <li><a href="alunos"><i class="glyphicon glyphicon-list text-primary"></i>
                                                Alunos</a></li>
                                        <?php if (in_array($this->session->userdata('nivel'), array(0, 7, 8, 9, 10))): ?>
                                            <li><a href="insumos"><i class="glyphicon glyphicon-list text-primary"></i>
                                                    Insumos</a></li>
                                            <li><a href="supervisores"><i
                                                            class="glyphicon glyphicon-list text-primary"></i> Vincular
                                                    supervisores</a></li>
                                        <?php endif; ?>
                                        <li><a href="cuidadores"><i class="glyphicon glyphicon-list text-primary"></i>
                                                Vincular cuidadores</a></li>
                                        <li><a href="eventos"><i class="glyphicon glyphicon-list text-primary"></i>
                                                Relatório de eventos</a></li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 right">
                            <p class="bg-info text-info" style="padding: 5px;">
                                <button id="filtro" style="float: right; margin: 12px 8px 0;" title="Pesquisa avançada"
                                        class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal_filtro">
                                    <i class="glyphicon glyphicon-filter"></i> <span class="hidden-xs">Filtrar</span>
                                </button>
                                <span>
                                <small>&emsp;<strong>Departamento:</strong> <span
                                            id="alerta_depto"><?= empty($depto_atual) ? 'Todos' : $depto_atual ?></span></small><br>
                                <small>&emsp;<strong>Diretoria:</strong> <span
                                            id="alerta_diretoria"><?= empty($diretoria_atual) ? 'Todas' : $diretoria_atual ?></span></small><br>
                                <small>&emsp;<strong>Supervisor:</strong> <span
                                            id="alerta_supervisor"><?= empty($supervisor_atual) ? 'Todos' : $supervisor_atual ?></span></small>
                            </span>
                            </p>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">
                            <span id="mes_ano"><?= ucfirst($mes) . ' ' . date('Y') ?></span>
                            <div style="float:right; margin-top: -0.5%;">
                                <button id="mes_anterior" title="Mês anterior" class="btn btn-primary btn-sm"
                                        onclick="proximo_mes(-1)">
                                    <i class="glyphicon glyphicon-arrow-left"></i> <span class="hidden-xs hidden-sm">Mês anterior</span>
                                </button>
                                <?php if ($modo_privilegiado): ?>
                                    <div class="btn-group">
                                        <button id="btnOpcoesMes" type="button"
                                                class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">Opções do mês <span
                                                    class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="javascript:void();" onclick="add_mes()"><i
                                                            class="glyphicon glyphicon-import text-success"></i> Alocar
                                                    escolas</a></li>
                                            <li id="limparMes" style="display:none;">
                                                <a href="javascript:;" onclick="excluir_mes();">
                                                    <i class="glyphicon glyphicon-erase text-danger"></i> Limpar mês</a>
                                            </li>
                                            <li id="naoLimparMes">
                                                <a href="#"><i
                                                            class="glyphicon glyphicon-erase text-danger"></i> <span
                                                            class="text-muted">Limpar
                                                        mês</span></a>
                                            </li>
                                            <li><a href="#" data-toggle="modal" data-target="#modal_colaborador"><i
                                                            class="glyphicon glyphicon-plus text-info"></i> Alocar
                                                    nova(o) escola/colaborador</a></li>
                                            <li><a href="#" data-toggle="modal" data-target="#modal_matriculados"><i
                                                            class="glyphicon glyphicon-plus text-info"></i> Alocar
                                                    novo(a) aluno(a)</a></li>
                                            <li><a href="<?= site_url('cd/relatorios/index/'); ?>" id="pdf"
                                                   target="_blank"><i class="glyphicon glyphicon-list text-primary"></i>
                                                    Medição funcionários</a></li>
                                            <li><a href="<?= site_url('cd/relatorios/escolas/'); ?>" id="pdfEscolas"
                                                   target="_blank"><i class="glyphicon glyphicon-list text-primary"></i>
                                                    Medição escolas</a></li>
                                            <li><a href="<?= site_url('cd/relatorios/insumos/'); ?>" id="pdfInsumos"
                                                   target="_blank"><i class="glyphicon glyphicon-list text-primary"></i>
                                                    Rel. controle materiais</a></li>
                                            <li><a href="<?= site_url('cd/relatorios/pdfCuidadores/'); ?>"
                                                   id="pdfCuidadores"><i
                                                            class="glyphicon glyphicon-list text-primary"></i>
                                                    Rel. relação escolas</a></li>
                                            <?php if (in_array($this->session->userdata('nivel'), array(7, 8, 9))): ?>
                                                <li><a href="<?= site_url('cd/relatorios/resultados/'); ?>"
                                                       id="pdfResultados"
                                                       target="_blank"><i
                                                                class="glyphicon glyphicon-list text-primary"></i>
                                                        Rel. acomp. individual</a></li>
                                                <li><a href="<?= site_url('cd/relatorios/resultadosDiretorias/'); ?>"
                                                       id="pdfResultadosDiretorias"
                                                       target="_blank"><i
                                                                class="glyphicon glyphicon-list text-primary"></i>
                                                        Rel. acomp. diretoria</a></li>
                                                <li><a href="<?= site_url('cd/relatorios/resultadosConsoliados/'); ?>"
                                                       id="pdfResultadosConsolidados"
                                                       target="_blank"><i
                                                                class="glyphicon glyphicon-list text-primary"></i>
                                                        Rel. acomp. consolidado</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#modal_config"
                                                       id="config"><i
                                                                class="glyphicon glyphicon-info-sign text-info"></i>
                                                        Observações do mês</a></li>
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
                                            data-toggle="tab">Medição Escolas</a></li>
                                <?php if ($modo_privilegiado): ?>
                                    <li role="presentation" style="font-size: 14px; font-weight: bolder"><a
                                                href="#funcionarios" aria-controls="funcionarios" role="tab"
                                                data-toggle="tab">Medição Funcionários</a></li>
                                <?php endif; ?>
                                <li role="presentation" style="font-size: 14px; font-weight: bolder"><a
                                            href="#alunos" aria-controls="alunos" role="tab"
                                            data-toggle="tab">Relação de Escolas</a></li>
                                <li role="presentation" style="font-size: 14px; font-weight: bolder"><a
                                            href="#frequencias" aria-controls="frequencias" role="tab"
                                            data-toggle="tab">Controle de Materiais</a></li>
                            </ul>

                            <div class="tab-content" style="border: 1px solid #ddd; border-top-width: 0;">
                                <div role="tabpanel" class="tab-pane active" id="apontamento">
                                    <table id="table"
                                           class="table table-hover table-striped table_apontamento table-condensed table-bordered"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th rowspan="2">ID</th>
                                            <th rowspan="2" class="warning">Município</th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">Escola</th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">P</th>
                                            <th rowspan="2" class="warning"
                                                style="vertical-align: middle; padding-left: 4px; padding-right: 4px;">
                                                Cuidador(a)
                                            </th>
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
                                </div>

                                <?php if ($modo_privilegiado): ?>
                                    <div role="tabpanel" class="tab-pane" id="funcionarios">
                                        <table id="table_funcionarios"
                                               class="table table-hover table_apontamento table-condensed table-bordered"
                                               cellspacing="0"
                                               width="100%" style="border-radius: 0 !important;">
                                            <thead>
                                            <tr>
                                                <th rowspan="2">ID</th>
                                                <th rowspan="2" class="warning">Município</th>
                                                <th rowspan="2" class="warning">Escola</th>
                                                <th rowspan="2" class="warning" style="vertical-align: middle;">
                                                    Funcionário(a)
                                                </th>
                                                <th rowspan="2">Realocado</th>
                                                <th rowspan="2" class="warning" style="vertical-align: middle;">P</th>
                                                <th colspan="31" class="date-width">Dias</th>
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
                                    </div>
                                <?php endif; ?>

                                <div role="tabpanel" class="tab-pane" id="alunos">
                                    <table id="table_cuidadores"
                                           class="table table-hover table_apontamento table-condensed table-bordered"
                                           cellspacing="0"
                                           width="100%" style="border-radius: 0 !important;">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Municipio - escola</th>
                                            <th class="warning" style="vertical-align: middle;">Cuidador(es)</th>
                                            <th class="warning" style="vertical-align: middle;">Data admissão</th>
                                            <th class="warning" style="vertical-align: middle;">Vale transporte</th>
                                            <th class="warning" style="vertical-align: middle;">Período</th>
                                            <th class="warning" style="vertical-align: middle;">Aluno(a)</th>
                                            <th class="warning">Hipótese diagnóstica</th>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="frequencias">
                                    <table id="table_frequencias"
                                           class="table table-hover table_apontamento table-condensed table-bordered"
                                           cellspacing="0"
                                           width="100%" style="border-radius: 0 !important;">
                                        <thead>
                                        <tr>
                                            <th rowspan="2">ID</th>
                                            <th rowspan="2">Município</th>
                                            <th rowspan="2" class="warning">Escola</th>
                                            <th rowspan="2">Supervisor</th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">P</th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">
                                                Aluno(a)
                                            </th>
                                            <th rowspan="2">ID alocação</th>

                                            <th colspan="31" class="date-width">Dias</th>
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Filtrar por departamento</label>
                                        <?php echo form_dropdown('depto', $depto, $depto_atual, 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Filtrar por diretoria</label>
                                        <?php echo form_dropdown('diretoria', $diretoria, $diretoria_atual, 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Filtrar por supervisor</label>
                                        <?php echo form_dropdown('supervisor', $supervisor, $supervisor_atual, 'class="form-control input-sm"'); ?>
                                    </div>
                                    <div class="col-md-3">
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
                            <button type="button" id="btnSaveBackup" onclick="salvar_ferias()" class="btn btn-success">
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
                                    <label class="control-label col-md-3">Data desligamento</label>
                                    <div class="col-md-4">
                                        <input name="data_desligamento" placeholder="dd/mm/aaaa"
                                               class="form-control text-center" autocomplete="off" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Colaborador(a) substituto(a)</label>
                                    <div class="col-md-8">
                                        <?php echo form_dropdown('id_usuario_sub', $usuarios, '', 'class="form-control"'); ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveSubstituto" onclick="salvar_substituto()"
                                    class="btn btn-success">Salvar
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
                                    <label class="control-label col-md-2" style="margin-top: -13px;">Cuidador(a):<br>Escola:<br>Período:<br>Data:</label>
                                    <div class="col-md-5" style="margin-top: -13px;">
                                        <label class="sr-only"></label>
                                        <p class="form-control-static">
                                            <span id="nome"></span><br>
                                            <span id="escola"></span><br>
                                            <span id="turno"></span><br>
                                            <span id="data"></span>
                                        </p>
                                    </div>
                                    <div class="col-md-5 text-right">
                                        <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
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
                                        <div class="col col-md-2">Tipo de evento</div>
                                        <div class="col col-md-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="FA">
                                                    Falta com atestado próprio
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="FS">
                                                    Falta sem atestado próprio
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="AP" checked>
                                                    Apontamento
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="AF">
                                                    Afastamento
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="SL">
                                                    Sábado letivo
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="DE">
                                                    Desligado
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col col-md-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="FC">
                                                    Feriado escola/cuidador
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="FE">
                                                    Feriado escola
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="ID">
                                                    Intercorrência de diretoria
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="IA">
                                                    Intercorrência de alunos
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="RE">
                                                    Remanejado
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="AD">
                                                    Funcionário admitido
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col col-md-3">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="EM">
                                                    Emenda de feriado
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="IC">
                                                    Intercorrência de cuidadores
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="AT">
                                                    Acidente de trabalho
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="AA">
                                                    Aluno ausente
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="NA">
                                                    Não alocado
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="status" value="PC">
                                                    Posto coberto
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="border-top: 1px solid #b0b0b0;">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Qtde de dias</label>
                                        <div class="col-md-2">
                                            <input name="qtde_dias" class="form-control text-right" type="number"
                                                   min="0" max="31" value="">
                                        </div>
                                        <label class="control-label col-md-2">Apontamento +</label>
                                        <div class="col-md-2">
                                            <input name="apontamento_asc" class="form-control hora text-center"
                                                   type="text" value="" placeholder="hh:mm">
                                        </div>

                                        <label class="control-label col-md-2">Apontamento -</label>
                                        <div class="col-md-2">
                                            <input name="apontamento_desc" class="form-control hora text-center"
                                                   type="text" value="" placeholder="hh:mm">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Saldo</label>
                                        <div class="col-md-2">
                                            <input name="saldo" class="form-control hora text-center" type="text"
                                                   value="00:00" placeholder="hh:mm">
                                        </div>

                                        <label class="control-label col-md-2">Data início afastamento</label>
                                        <div class="col-md-3">
                                            <input name="data_afastamento" class="form-control text-center" type="text"
                                                   value="" placeholder="dd/mm/aaaa">
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Cuidador(a) substituto(a)</label>
                                    <div class="col-md-8">
                                        <?php echo form_dropdown('id_cuidador_sub', array('' => 'selecione...'), '', 'class="form-control"'); ?>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Observações</label>
                                    <div class="col-md-8">
                                        <textarea name="observacoes" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_eventos" role="dialog">
                <div class="modal-dialog modal-sm" style="width: 320px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Edição de eventos</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_eventos" class="form-horizontal">
                                <input type="hidden" value="" name="data"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Data</label>
                                    <div class="col-md-9">
                                        <label class="sr-only"></label>
                                        <p class="form-control-static">
                                            <span id="data_evento"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Status</label>
                                    <div class="col-sm-9">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" value="FC" checked>
                                                Feriado escola/cuidador
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" value="FE" checked>
                                                Feriado escola
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" value="EM">
                                                Emenda de feriado
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" value="PC">
                                                Posto coberto
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveEventos" onclick="save_eventos()"
                                    class="btn btn-success">Replicar
                            </button>
                            <button type="button" id="btnDeleteEventos" onclick="delete_eventos()"
                                    class="btn btn-danger">Limpar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <?php if ($modo_privilegiado): ?>
                <!-- Bootstrap modal -->
                <div class="modal fade" id="modal_funcionarios" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h3 class="modal-title">Editar valor acrescido na totalização</h3>
                            </div>
                            <div class="modal-body form">
                                <form action="#" id="form_funcionarios" class="form-horizontal">
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
                                <button type="button" id="btnSaveFuncionarios" onclick="save_totalizacao()"
                                        class="btn btn-success">Salvar
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            <?php endif; ?>

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_alunos" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar evento de alunos</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_frequencias" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_matriculado"/>
                                <input type="hidden" value="" name="data"/>
                                <label class="control-label col-md-2"
                                       style="margin-top: -13px;">Aluno(a):<br>Data:<br>Período:</label>
                                <div class="row">
                                    <div class="col-md-5" style="margin-top: -13px;">
                                        <label class="sr-only"></label>
                                        <p class="form-control-static">
                                            <span id="nome_aluno"></span><br>
                                            <span id="data_aluno"></span><br>
                                            <span id="turno_aluno"></span><br>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value=""> Aluno presente
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="AF"> Aluno faltou
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="AI"> Aluno inativo
                                        </label>
                                    </div>
                                </div>
                                <br>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Quantidade de insumos utilizados</div>
                                    <div class="panel-body" id="insumos" style="max-height: 200px; overflow-y: auto;">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveFrequencia" onclick="save_aluno()"
                                    class="btn btn-success">Salvar
                            </button>
                            <button type="button" id="btnLimparfrequencia" onclick="limpar_frequencia()"
                                    class="btn btn-danger">Limpar evento
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_colaborador" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Alocar nova(o) escola/colaborador</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_colaborador" class="form-horizontal">
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Diretoria</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('', $id_diretoria, '', 'id="id_diretoria" class="form-control" onchange="atualizarCuidadores();" autocomplete="off"'); ?>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Unidade de ensino</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_escola', $id_escola, '', 'id="id_escola" class="form-control" onchange="atualizarCuidadores();" autocomplete="off"'); ?>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
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
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveColaboradores" onclick="save_colaborador()"
                                    class="btn btn-success">Alocar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_remanejado" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Selecionar status da escola/turno</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_remanejado" class="form-horizontal">
                                <input type="hidden" name="id" value="">
                                <div class="form-body">
                                    <div class="row form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="remanejado" value="" checked>
                                                A contratar/Remanejado
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="remanejado" value="0">
                                                A contratar
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="remanejado" value="1">
                                                Remanejado
                                            </label>
                                        </div>
                                        <br>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="remanejado" value="2">
                                                <strong>Alocar cuidador</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveRemanejado" onclick="save_remanejado()"
                                    class="btn btn-success">Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_alocado" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Alocar cuidador pendente</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_alocado" class="form-horizontal">
                                <div class="row form-group">
                                    <label class="control-label col-md-3" style="margin-top: -13px;">Município: <br>Escola:
                                        <br>Período: </label>
                                    <div class="col-md-8" style="margin-top: -13px;">
                                        <label class="sr-only"></label>
                                        <p class="form-control-static">
                                            <span id="alocado_municipio"></span><br>
                                            <span id="alocado_escola"></span><br>
                                            <span id="alocado_turno"></span><br>
                                        </p>
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="">
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Cuidador</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_vinculado', array('' => 'selecione...'), '', 'class="form-control" autocomplete="off"'); ?>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveAlocado" onclick="save_alocado()"
                                    class="btn btn-success">Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_matriculados" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Alocar novo(a) aluno(a)</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_matriculados" class="form-horizontal">
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Diretoria</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('', $id_diretoria, '', 'id="id_diretoria_matr" class="form-control" onchange="atualizarMatriculados();" autocomplete="off"'); ?>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Unidade de ensino</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_escola', $id_escola, '', 'id="id_escola_matr" class="form-control" onchange="atualizarMatriculados();" autocomplete="off"'); ?>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
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
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Período(s)</label>
                                        <div class="col col-lg-4">
                                            <label class="checkbox-inline">
                                                <input class="turno_matriculados" name="turno[]" value="M"
                                                       type="checkbox"> Manhã
                                            </label>
                                            <label class="checkbox-inline">
                                                <input class="turno_matriculados" name="turno[]" value="T"
                                                       type="checkbox"> Tarde
                                            </label>
                                            <label class="checkbox-inline">
                                                <input class="turno_matriculados" name="turno[]" value="N"
                                                       type="checkbox"> Noite
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveMatriculados" onclick="save_matriculados()"
                                    class="btn btn-success">Alocar
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
                            <h3 class="modal-title">Desalocar escola</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_colaborador_alocado" class="form-horizontal">
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Colaborador(a)</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_vinculado', $usuarios, '', 'class="form-control" autocomplete="off"'); ?>
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
                        <div class="modal-body">
                            <form action="#" id="form_config" class="form-horizontal" autocomplete="off">
                                <input type="hidden" name="id" value="">
                                <input type="hidden" name="id_alocacao" value="">
                                <div class="row form-group" style="margin-bottom: 0px;">
                                    <label class="col-md-7 text-left" style="margin-top: 7px; margin-bottom: 0px;">
                                        <h5><strong>Faltas</strong></h5>
                                    </label>
                                    <div class="col-md-5 text-right">
                                        <button type="button" id="btnBuscarConfig" onclick="buscar_configuracoes()"
                                                class="btn btn-info">Buscar valores
                                        </button>
                                        <button type="button" id="btnSaveConfig" onclick="salvar_configuracoes()"
                                                class="btn btn-success">Salvar
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar
                                        </button>
                                    </div>
                                </div>
                                <hr style="margin-top: 0px;">

                                <div class="row form-group">
                                    <label class="control-label col-md-3">Faltas com atestado</label>
                                    <div class="col-md-2">
                                        <input name="total_faltas_justificadas" class="form-control text-center"
                                               type="number" id="total_faltas_justificadas" placeholder="dd"
                                               step="1" min="1" max="31" value="">
                                    </div>
                                    <label class="control-label col-md-3">Faltas sem atestado</label>
                                    <div class="col-md-2">
                                        <input name="total_faltas" class="form-control text-center"
                                               type="number" id="total_faltas" placeholder="dd"
                                               step="1" min="1" max="31" value="">
                                    </div>
                                </div>
                                <h5><strong>Turnover</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Contratações por substituição</label>
                                    <div class="col-md-2">
                                        <input name="turnover_substituicao" class="form-control text-center"
                                               type="number" value=""
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);">
                                    </div>
                                    <label class="control-label col-md-4">Contratações por aumento quadro</label>
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
                                    <label class="control-label col-md-4">Desligamentos por solicitação</label>
                                    <div class="col-md-2">
                                        <input name="turnover_desligamento_solicitacao" class="form-control text-center"
                                               type="number" value=""
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);">
                                    </div>
                                </div>
                                <h5><strong>Intercorrências</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Intercorrências Diretoria Ensino</label>
                                    <div class="col-md-2">
                                        <input name="intercorrencias_diretoria" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                    <label class="control-label col-md-4">Intercorrências cuidador</label>
                                    <div class="col-md-2">
                                        <input name="intercorrencias_cuidador" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Intercorrências alunos</label>
                                    <div class="col-md-2">
                                        <input name="intercorrencias_alunos" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                    <label class="control-label col-md-4">Acidentes de trabalho</label>
                                    <div class="col-md-2">
                                        <input name="acidentes_trabalho" class="form-control text-center"
                                               type="number" value="">
                                    </div>
                                </div>
                                <h5><strong>Quadro</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Qtde. escolas trabalhadas</label>
                                    <div class="col-md-2">
                                        <input name="total_escolas" class="form-control text-center"
                                               type="number"
                                               step="1" value="">
                                    </div>
                                    <label class="control-label col-md-2">Qtde. alunos assistidos</label>
                                    <div class="col-md-2">
                                        <input name="total_alunos" class="form-control text-center"
                                               type="number"
                                               step="1" value="">
                                    </div>
                                    <label class="control-label col-md-2">Dias letivos</label>
                                    <div class="col-md-2">
                                        <input name="dias_letivos" class="form-control text-center"
                                               type="number" id="dias_letivos" placeholder="dd"
                                               step="1" min="1" max="31" value="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Cuidadores contratados</label>
                                    <div class="col-md-2">
                                        <input name="total_cuidadores" class="form-control text-center" type="number"
                                               step="1" value="">
                                    </div>
                                    <label class="control-label col-md-3">Qtde. cuidadores cobrados</label>
                                    <div class="col-md-2">
                                        <input name="total_cuidadores_cobrados" class="form-control text-center"
                                               type="number"
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);"
                                               step="1" value="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Qtde. cuidadores ativos</label>
                                    <div class="col-md-2">
                                        <input name="total_cuidadores_ativos" class="form-control text-center"
                                               type="number"
                                               step="1" value="">
                                    </div>
                                    <label class="control-label col-md-3">Qtde. cuidadores afastados</label>
                                    <div class="col-md-2">
                                        <input name="total_cuidadores_afastados" class="form-control text-center"
                                               type="number"
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);"
                                               step="1" value="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Qtde. supervisores contratados</label>
                                    <div class="col-md-2">
                                        <input name="total_supervisores" class="form-control text-center"
                                               type="number"
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);"
                                               step="1" value="">
                                    </div>
                                    <label class="control-label col-md-3">Qtde. supervisores cobrados</label>
                                    <div class="col-md-2">
                                        <input name="total_supervisores_cobrados" class="form-control text-center"
                                               type="number"
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);"
                                               step="1" value="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Qtde. supervisores ativos</label>
                                    <div class="col-md-2">
                                        <input name="total_supervisores_ativos" class="form-control text-center"
                                               type="number"
                                               step="1" value="">
                                    </div>
                                    <label class="control-label col-md-3">Qtde. supervisores afastados</label>
                                    <div class="col-md-2">
                                        <input name="total_supervisores_afastados" class="form-control text-center"
                                               type="number"
                                               style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);"
                                               step="1" value="">
                                    </div>
                                </div>
                                <h5><strong>Faturamento</strong></h5>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Faturamento projetado</label>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input name="faturamento_projetado" class="form-control text-center valor"
                                                   type="text" value=""
                                                   style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);">
                                        </div>
                                    </div>
                                    <label class="control-label col-md-2">Faturamento realizado</label>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input name="faturamento_realizado" class="form-control text-center valor"
                                                   type="text" value=""
                                                   style="background-color: rgb(152, 210, 152); color: rgb(60, 118, 61);">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal" id="modal_legenda" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Legenda de eventos</h3>
                        </div>
                        <div class="modal-body">
                            <table>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; background-color: #000; color: #fff">
                                        DE
                                    </td>
                                    <td style="padding-left: 8px;"> Funcionário demitido</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; color: #fff; background-color: #d9534f;">FS
                                    </td>
                                    <td style="padding-left: 8px;"> Falta sem atestado</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; color: #000; background-color: #f0ad4e;">FA
                                    </td>
                                    <td style="padding-left: 8px;"> Falta com atestado</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; background-color: #ff0;">
                                        AF
                                    </td>
                                    <td style="padding-left: 8px;"> Funcionário afastado</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; background-color: #ff0;">
                                        AA
                                    </td>
                                    <td style="padding-left: 8px;"> Aluno ausente</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; background-color: #ff0;">
                                        RE
                                    </td>
                                    <td style="padding-left: 8px;"> Funcionário remanejado</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; background-color: #ff0;">
                                        NA
                                    </td>
                                    <td style="padding-left: 8px;"> Funcionário não-alocado</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; color: #fff; background-color: #5cb85c;">
                                        AD
                                    </td>
                                    <td style="padding-left: 8px;"> Funcionário admitido</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; color: #fff; background-color: #5cb85c;">
                                        SL
                                    </td>
                                    <td style="padding-left: 8px;"> Sábado letivo</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; color: #fff; background-color: #337ab7;">FC
                                    </td>
                                    <td style="padding-left: 8px;"> Feriado escola/cuidador</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; color: #fff; background-color: #337ab7;">FE
                                    </td>
                                    <td style="padding-left: 8px;"> Feriado escola</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; color: #fff; background-color: #337ab7;">EM
                                    </td>
                                    <td style="padding-left: 8px;"> Emenda de feriado</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; color: #fff; background-color: #337ab7;">PC
                                    </td>
                                    <td style="padding-left: 8px;"> Posto coberto</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; box-shadow: inset 0 0 0 1px; -moz-box-shadow: 0 0 0 1px; -webkit-box-shadow: inset 0 0 0 1px;">
                                        ID
                                    </td>
                                    <td style="padding-left: 8px;"> Intercorrência de Diretoria</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; box-shadow: inset 0 0 0 1px; -moz-box-shadow: 0 0 0 1px; -webkit-box-shadow: inset 0 0 0 1px;">
                                        IC
                                    </td>
                                    <td style="padding-left: 8px;"> Intercorrência de Cuidadores</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; box-shadow: inset 0 0 0 1px; -moz-box-shadow: 0 0 0 1px; -webkit-box-shadow: inset 0 0 0 1px;">
                                        IA
                                    </td>
                                    <td style="padding-left: 8px;"> Intercorrência de Alunos</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; box-shadow: inset 0 0 0 1px; -moz-box-shadow: 0 0 0 1px; -webkit-box-shadow: inset 0 0 0 1px;">
                                        AT
                                    </td>
                                    <td style="padding-left: 8px;"> Acidente de trabalho</td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal" id="modal_legenda2" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Legenda de atributos</h3>
                        </div>
                        <div class="modal-body">
                            <table>
                                <tr style="border: 2px solid #fff;">
                                    <td style="padding: 4px; background-color: #f2dede;">&emsp;</td>
                                    <td style="padding-left: 8px;"> Nenhum cuidador ou aluno no turno</td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal" id="modal_legenda3" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Legenda de eventos</h3>
                        </div>
                        <div class="modal-body">
                            <table>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; box-shadow: inset 0 0 0 1px; -moz-box-shadow: 0 0 0 1px; -webkit-box-shadow: inset 0 0 0 1px;">
                                        AF
                                    </td>
                                    <td style="padding-left: 8px;"> Aluno faltou</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td style="padding: 4px; background-color: #5cb85c;">&emsp;</td>
                                    <td style="padding-left: 8px;"> Insumo(s) utilizado(s)</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td style="padding: 4px; background-color: #f2dede;">&emsp;</td>
                                    <td style="padding-left: 8px;"> Nenhum aluno no turno</td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        </section>
    </section>
    <!--main content end-->

<?php
require_once APPPATH . 'views/end_js.php';
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.css"); ?>"/>

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gestão Operacional ST';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/extensions/dataTables.fixedColumns.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/extensions/dataTables.rowGroup.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var table, table_funcionarios, table_cuidadores, table_frequencias;
        var busca;
        var edicaoEvento = true;
        var drawing_table = false;
        var drawing_table_funcionarios = false;
        var drawing_table_cuidadores = false;
        var drawing_table_frequencias = false;

        $('.tags').tagsInput({
            'width': 'auto',
            'defaultText': 'Telefone',
            'placeholderColor': '#999',
            'delimiter': '/'
        });
        $('[name="data_recesso"], [name="data_retorno"], [name="data_desligamento"], [name="data_afastamento"]').mask('00/00/0000');
        $('.hora').mask('00:00');
        $('.valor').mask('##.###.##0,00', {'reverse': true});
        $(function () {
            $('[data-tooltip="tooltip"]').tooltip();
        });

        $(document).ready(function () {
            busca = $('#busca').serialize();
            var url = '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>';

            table = $('#table').DataTable({
                'dom': "<'row'<'col-sm-3'l><'#legenda.col-sm-5'><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                'processing': true,
                'serverSide': true,
                'iDisplayLength': 500,
                'lengthMenu': [[5, 10, 25, 50, 100, 500, 1000], [5, 10, 25, 50, 100, 500, 1000]],
                'orderFixed': [1, 'asc'],
                'rowGroup': {
                    'className': 'active',
                    'startRender': function (rows, group) {
                        return '<strong>Municipio: </strong>' + group;
                    },
                    'dataSrc': 1
                },
                'language': {
                    'url': url
                },
                'ajax': {
                    'url': '<?php echo site_url('cd/apontamento/ajax_list') ?>',
                    'type': 'POST',
                    'timeout': 90000,
                    'data': function (d) {
                        d.busca = busca;
                        return d;
                    },
                    'dataSrc': function (json) {
                        var dt1 = new Date();
                        var dt2 = new Date();
                        dt2.setFullYear(json.calendar.ano, (json.calendar.mes - 1));

                        var semana = 1;
                        var colunasUsuario = 4;
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
                            $('#dias').html('<strong>Dias</strong> (clique em um dia do mês para replicar/limpar feriados, emendas de feriados ou postos descobertos)');
                        } else {
                            $('#dias').html('<strong>Dias</strong>');
                        }
                        if (json.draw === '1') {
                            $("#legenda").html('<button title="Mostrar legenda de eventos" data-toggle="modal" data-target="#modal_legenda" style="margin: 15px 10px 0;" class="btn btn-default btn-sm">' +
                                '<i class="glyphicon glyphicon-exclamation-sign"></i> <span class="hidden-xs"> Mostrar legenda de eventos</span>' +
                                '</button>');
                        }
                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'visible': false,
                        'targets': [0, 1]
                    },
                    {
                        'className': 'text-center',
                        'targets': [3]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col][1] === null) {
                                $(td).css('cursor', 'pointer');
                                $(td).on('click', function () {
                                    if (edicaoEvento === false) {
                                        return false;
                                    }
                                    $('#form_remanejado [name="id"]').val(rowData[0]);
                                    if (rowData[col][2] === '2') {
                                        editar_alocado(rowData[0]);
                                    } else {
                                        switch (rowData[col][2]) {
                                            case null:
                                                $('#form_remanejado [name="remanejado"][value=""]').prop('checked', true);
                                                break;
                                            case '0':
                                                $('#form_remanejado [name="remanejado"][value="0"]').prop('checked', true);
                                                break;
                                            case '1':
                                                $('#form_remanejado [name="remanejado"][value="1"]').prop('checked', true);
                                                break;
                                            case '2':
                                                $('#form_remanejado [name="remanejado"][value="2"]').prop('checked', true);
                                                break;
                                            default:
                                                $('#form_remanejado [name="remanejado"]').prop('checked', false);
                                        }
                                        $('#modal_remanejado').modal('show');
                                    }
                                });
                                $(td).addClass('text-danger text-center text-nowrap').html(rowData[col][0]);
                            } else {
                                $(td).removeClass('text-danger text-center text-nowrap').html(rowData[col][0]);
                            }
                        },
                        'targets': [4]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).css('padding', '8px 1px');
                            if (rowData[col] === null) {
                                $(td).css('background-color', '#dbdbdb').html('');
                            } else {
                                if ($(table.column(col).header()).hasClass('text-danger')) {
                                    $(td).css('background-color', '#e9e9e9');
                                }

                                $(td).css('cursor', 'pointer');
                                if (rowData[col][7] === 'FA') {
                                    $(td).addClass('date-width-warning');
                                } else if (rowData[col][7] === 'FS') {
                                    $(td).addClass('date-width-danger');
                                } else if (['EM', 'FC', 'FE', 'PC'].indexOf(rowData[col][7]) !== -1) {
                                    $(td).addClass('date-width-primary');
                                } else if (rowData[col][7] === 'AD') {
                                    $(td).addClass('date-width-success');
                                } else if (rowData[col][7] === 'DE') {
                                    $(td).css({'background-color': '#000', 'color': '#fff'});
                                } else if (rowData[col][7] === 'SL') {
                                    $(td).addClass('date-width-success');
                                } else if (['AT', 'IA', 'IC', 'ID'].indexOf(rowData[col][7]) !== -1) {
                                    $(td).css({'background-color': '#fff', 'color': '#000'});
                                } else if (rowData[col][0].length > 0) {
                                    $(td).css('background-color', '#ff0');
                                } else if ($(table.column(col).header()).hasClass('text-danger') === false) {
                                    $(td).addClass('date-width-success');
                                }
                                $(td).attr({
                                    'data-toggle': 'modal',
                                    'data-target': '#modal_form',
                                    'data-tooltip': 'tooltip',
                                    'data-placement': 'top',
                                    'title': rowData[4][0] + '\n' + $(table.column(col).header()).attr('title') +
                                        '\nEvento: ' + (rowData[col][7] === 'AA' ? 'Aluno ausente' :
                                            rowData[col][7] === 'FA' ? 'Falta com atestado' :
                                                rowData[col][7] === 'FS' ? 'Falta sem atestado' :
                                                    rowData[col][7] === 'NA' ? 'Funcionário não-alocado' :
                                                        rowData[col][7] === 'RE' ? 'Funcionário remanejado' :
                                                            rowData[col][7] === 'AF' ? 'Funcionário afastado' :
                                                                rowData[col][7] === 'DE' ? 'Funcionário demitido' :
                                                                    rowData[col][7] === 'AD' ? 'Funcionário admitido' :
                                                                        rowData[col][7] === 'EM' ? 'Emenda feriado' :
                                                                            rowData[col][7] === 'PC' ? 'Posto coberto' :
                                                                                rowData[col][7] === 'SL' ? 'Sábado letivo' :
                                                                                    rowData[col][7] === 'AT' ? 'Acidente de trabalho' :
                                                                                        rowData[col][7] === 'IA' ? 'Intercorrência de alunos' :
                                                                                            rowData[col][7] === 'IC' ? 'Intercorrência de cuidadores' :
                                                                                                rowData[col][7] === 'ID' ? 'Intercorrência de diretoria' :
                                                                                                    rowData[col][7] === 'FC' ? 'Feriado escola/cuidador' :
                                                                                                        rowData[col][7] === 'FE' ? 'Feriado escola' : 'Nenhum') +
                                        (rowData[col][7] === 'AP' && rowData[col][3] !== '' && rowData[col][3] !== '00:00' && rowData[col][3] !== undefined ? '\nApontamento positivo: ' + rowData[col][3] : '') +
                                        (rowData[col][7] === 'AP' && rowData[col][4] !== '' && rowData[col][4] !== '00:00' && rowData[col][4] !== undefined ? '\nApontamento negativo: ' + rowData[col][4] : '') +
                                        (rowData[col][1] !== '' && rowData[col][1] !== undefined ? '\nSubstituto(a): ' + rowData[col][8] : '') +
                                        (rowData[col][6] !== '' && rowData[col][6] !== undefined ? '\nObservações: ' + rowData[col][6] : ''),
                                    'data-id': rowData[col][0],
                                    'data-id_alocado': rowData[0],
                                    'data-id_cuidador_sub': rowData[col][1],
                                    'data-qtde_dias': rowData[col][2],
                                    'data-apontamento_asc': rowData[col][3],
                                    'data-apontamento_desc': rowData[col][4],
                                    'data-saldo': rowData[col][5],
                                    'data-observacoes': rowData[col][6],
                                    'data-status': rowData[col][7]
                                });
                                if ($('#alerta_diretoria').html() !== 'Todas') {
                                    $(td).on('click', function () {
                                        if (edicaoEvento === false) {
                                            return false;
                                        }
                                        $('#form')[0].reset();
                                        atualizarColaboradores(rowData[3], $(this).data('id_cuidador_sub'), $(this).data('id_alocado'));
                                        $('[name="id"]').val($(this).data('id'));
                                        $('[name="id_alocado"]').val($(this).data('id_alocado'));
                                        $('[name="data"]').val($(table.column(col).header()).attr('data-dia'));
                                        if ($(this).data('status') !== undefined) {
                                            $('[name="status"][value="' + $(this).data('status') + '"]').prop('checked', 'checked');
                                            $('#modal_form .modal-title').text('Editar evento de apontamento');
                                            $('#btnApagar').show();
                                            selecionar_status($(this).data('status'));
                                            $('[name="qtde_dias"]').val($(this).data('qtde_dias'));
                                            $('[name="apontamento_asc"]').val($(this).prop('data-apontamento_asc'));
                                            $('[name="apontamento_desc"]').val($(this).prop('data-apontamento_desc'));
                                            $('[name="saldo"]').val($(this).data('saldo'));
                                            $('[name="observacoes"]').val($(this).data('observacoes'));
                                        } else {
                                            $('[name="status"][value="FA"]').prop('checked', 'checked');
                                            if ($(table.column(col).header()).attr('data-mes_ano').search('Sábado') === 0) {
                                                $('[name="status"][value="SL"]').prop('disabled', false);
                                            } else {
                                                $('[name="status"][value="SL"]').prop('disabled', true);
                                            }
                                            $('#modal_form .modal-title').text('Criar evento de apontamento');
                                            $('#btnApagar').hide();
                                            selecionar_status('FA');
                                        }
                                        $('#nome').html(rowData[4][0]);
                                        $('#escola').html(rowData[2]);
                                        switch (rowData[3]) {
                                            case 'M':
                                                $('#turno').html('Manhã');
                                                break;
                                            case 'T':
                                                $('#turno').html('Tarde');
                                                break;
                                            case 'N':
                                                $('#turno').html('Noite');
                                                break;
                                            default:
                                                $('#turno').html('Integral');
                                                break;
                                        }
                                        $('#data').html($(table.column(col).header()).attr('data-mes_ano'));
                                    });
                                }
                                $(td).html(rowData[col][7] !== 'AE' ? rowData[col][7] : '');
                            }
                        },
                        'className': 'text-center',
                        'targets': 'date-width',
                        'orderable': false,
                        'searchable': false
                    }
                ],
                'preDrawCallback': function () {
                    drawing_table = true;
                },
                'drawCallback': function () {
                    drawing_table = false;
                    set_edicao_evento();
                },
                'rowsGroup': [1, 2, 3, 4]
            });

            if ('<?= $modo_privilegiado ?>') {
                table_funcionarios = $('#table_funcionarios').DataTable({
                    'dom': "<'row'<'col-sm-3'l><'#legenda2.col-sm-5'><'col-sm-4'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    'processing': true,
                    'serverSide': true,
                    'iDisplayLength': 500,
                    'lengthMenu': [[5, 10, 25, 50, 100, 500], [5, 10, 25, 50, 100, 500]],
                    'order': [[0, 'asc']],
                    'rowGroup': {
                        'className': 'active',
                        'startRender': function (rows, group) {
                            return '<strong>Municipio: </strong>' + group;
                        },
                        'dataSrc': 1
                    },
                    'language': {
                        'url': url
                    },
                    'ajax': {
                        'url': '<?php echo site_url('cd/apontamento/ajax_funcionarios') ?>',
                        'type': 'POST',
                        'timeout': 90000,
                        'data': function (d) {
                            d.busca = busca;
                            return d;
                        },
                        'dataSrc': function (json) {
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
                            for (i = 1; i <= 31; i++) {
                                if (i > 28) {
                                    if (i > json.calendar.qtde_dias) {
                                        table_funcionarios.column(i + 5).visible(false, false);
                                        continue;
                                    } else {
                                        table_funcionarios.column(i + 5).visible(true, false);
                                    }
                                }
                                var colunaFuncionarios = $(table_funcionarios.columns(i + 5).header());
                                colunaFuncionarios.removeClass('text-danger').css('background-color', '');
                                colunaFuncionarios.attr({
                                    'data-dia': json.calendar.ano + '-' + json.calendar.mes + '-' + colunaFuncionarios.text(),
                                    'data-mes_ano': json.calendar.semana[semana] + ', ' + colunaFuncionarios.text() + '/' + json.calendar.mes + '/' + json.calendar.ano,
                                    'title': json.calendar.semana[semana] + ', ' + colunaFuncionarios.text() + ' de ' + json.calendar.mes_ano.replace(' ', ' de ')
                                });
                                if (json.calendar.semana[semana] === 'Sábado' || json.calendar.semana[semana] === 'Domingo') {
                                    colunaFuncionarios.addClass('text-danger').css('background-color', '#dbdbdb');
                                }
                                if ((dt1.getTime() === dt2.getTime()) && dt1.getDate() === i) {
                                    colunaFuncionarios.css('background-color', '#0f0');
                                }
                                if (i % 7 === 0) {
                                    semana = 1;
                                } else {
                                    semana++;
                                }
                            }
                            if (json.draw === '1') {
                                $("#legenda2").html('<button title="Mostrar legenda de eventos" data-toggle="modal" data-target="#modal_legenda" style="margin: 15px 10px 0;" class="btn btn-default btn-sm">' +
                                    '<i class="glyphicon glyphicon-exclamation-sign"></i> <span class="hidden-xs"> Mostrar legenda de eventos</span>' +
                                    '</button>');
                            }
                            return json.data;
                        }
                    },
                    'columnDefs': [
                        {
                            'visible': false,
                            'targets': [0, 1, 4]
                        },
                        {
                            'className': 'text-center',
                            'targets': [5]
                        },
                        {
                            'createdCell': function (td, cellData, rowData, row, col) {
                                if (rowData[4] === null) {
                                    $(td).addClass('text-danger text-center text-nowrap').html(rowData[col]);
                                } else {
                                    $(td).removeClass('text-danger text-center text-nowrap').html(rowData[col]);
                                }
                            },
                            'targets': [3]
                        },
                        {
                            'createdCell': function (td, cellData, rowData, row, col) {
                                $(td).css('padding', '8px 1px');
                                if (rowData[col] === null) {
                                    $(td).css('background-color', '#dbdbdb').html('');
                                } else {
                                    if ($(table_funcionarios.column(col).header()).hasClass('text-danger')) {
                                        $(td).css('background-color', '#e9e9e9');
                                    }

                                    if (rowData[col][7] === 'FA') {
                                        $(td).addClass('date-width-warning');
                                    } else if (rowData[col][7] === 'FS') {
                                        $(td).addClass('date-width-danger');
                                    } else if (['EM', 'FC', 'FE'].indexOf(rowData[col][7]) !== -1) {
                                        $(td).addClass('date-width-primary');
                                    } else if (rowData[col][7] === 'AD') {
                                        $(td).addClass('date-width-success');
                                    } else if (rowData[col][7] === 'DE') {
                                        $(td).css({'background-color': '#000', 'color': '#fff'});
                                    } else if (rowData[col][7] === 'SL') {
                                        $(td).addClass('date-width-success');
                                    } else if (['AT', 'IA', 'IC', 'ID'].indexOf(rowData[col][7]) !== -1) {
                                        $(td).css({'background-color': '#fff', 'color': '#000'});
                                    } else if (rowData[col][7] === 'PC') {
                                        $(td).css({'background-color': '#fff', 'color': '#fff'});
                                    } else if (rowData[col][0].length > 0) {
                                        $(td).css('background-color', '#ff0');
                                    } else if ($(table_funcionarios.column(col).header()).hasClass('text-danger') == false) {
                                        $(td).addClass('date-width-success');
                                    }
                                    $(td).attr({
                                        'data-tooltip': 'tooltip',
                                        'data-placement': 'top',
                                        'title': rowData[1] + '\n' + $(table_funcionarios.column(col).header()).attr('title') +
                                            '\nEvento: ' + (rowData[col][7] === 'AA' ? 'Aluno ausente' :
                                                rowData[col][7] === 'FA' ? 'Falta com atestado' :
                                                    rowData[col][7] === 'FS' ? 'Falta sem atestado' :
                                                        rowData[col][7] === 'NA' ? 'Funcionário não-alocado' :
                                                            rowData[col][7] === 'RE' ? 'Funcionário remanejado' :
                                                                rowData[col][7] === 'AF' ? 'Funcionário afastado' :
                                                                    rowData[col][7] === 'DE' ? 'Funcionário demitido' :
                                                                        rowData[col][7] === 'AD' ? 'Funcionário admitido' :
                                                                            rowData[col][7] === 'EM' ? 'Emenda feriado' :
                                                                                rowData[col][7] === 'PC' ? 'Posto coberto' :
                                                                                    rowData[col][7] === 'SL' ? 'Sábado letivo' :
                                                                                        rowData[col][7] === 'AT' ? 'Acidente de trabalho' :
                                                                                            rowData[col][7] === 'IA' ? 'Intercorrência de alunos' :
                                                                                                rowData[col][7] === 'IC' ? 'Intercorrência de cuidadores' :
                                                                                                    rowData[col][7] === 'ID' ? 'Intercorrência de diretoria' :
                                                                                                        rowData[col][7] === 'FC' ? 'Feriado escola/cuidador' :
                                                                                                            rowData[col][7] === 'FE' ? 'Feriado escola' : 'Nenhum') +
                                            (rowData[col][7] === 'AP' && rowData[col][3] !== '' && rowData[col][3] !== '00:00' && rowData[col][3] !== undefined ? '\nApontamento positivo: ' + rowData[col][3] : '') +
                                            (rowData[col][7] === 'AP' && rowData[col][4] !== '' && rowData[col][4] !== '00:00' && rowData[col][4] !== undefined ? '\nApontamento negativo: ' + rowData[col][4] : '') +
                                            (rowData[col][1] !== '' && rowData[col][1] !== undefined ? '\nSubstituto(a): ' + rowData[col][8] : '') +
                                            (rowData[col][6] !== '' && rowData[col][6] !== undefined ? '\nObservações: ' + rowData[col][6] : '')
                                    });
                                    $(td).html(rowData[col][7] !== 'AE' ? rowData[col][7] : '');
                                }
                            },
                            'className': 'text-center',
                            'orderable': false,
                            'searchable': false,
                            'targets': 'date-width'
                        }
                    ],
                    'preDrawCallback': function () {
                        drawing_table_funcionarios = true;
                    },
                    'drawCallback': function () {
                        drawing_table_funcionarios = false;
                        set_edicao_evento();
                    },
                    'rowsGroup': [1, 2, 3]
                });
            }

            table_cuidadores = $('#table_cuidadores').DataTable({
                'dom': "<'row'<'col-sm-3'l><'#legenda3.col-sm-5'><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                'processing': true,
                'serverSide': true,
                'iDisplayLength': 500,
                'lengthMenu': [[5, 10, 25, 50, 100, 250, 500], [5, 10, 25, 50, 100, 250, 500]],
                'orderFixed': [1, 'asc'],
                'rowGroup': {
                    'className': 'active',
                    'startRender': function (rows, group) {
                        return '<strong>Municipio: </strong>' + group;
                    },
                    'dataSrc': 1
                },
                'language': {
                    'url': url
                },
                'ajax': {
                    'url': '<?php echo site_url('cd/apontamento/ajax_cuidadores') ?>',
                    'type': 'POST',
                    'timeout': 90000,
                    'data': function (d) {
                        d.busca = busca;
                        return d;
                    },
                    'dataSrc': function (json) {
                        if (json.draw === '1') {
                            $("#legenda3").html('<button title="Mostrar legenda de atributos" data-toggle="modal" data-target="#modal_legenda2" style="margin: 15px 10px 0;" class="btn btn-default btn-sm">' +
                                '<i class="glyphicon glyphicon-exclamation-sign"></i> <span class="hidden-xs"> Mostrar legenda de atributos</span>' +
                                '</button>');
                        }
                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'visible': false,
                        'targets': [0, 1]
                    },
                    {
                        'width': '30%',
                        'targets': [2, 6]
                    },
                    {
                        'width': '40%',
                        'targets': [7]
                    },
                    {
                        'className': 'text-center',
                        'targets': [3, 5]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[2] === null) {
                                $(td).addClass('danger');
                            }
                        },
                        'targets': [2, 3, 4]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[6] === null) {
                                $(td).addClass('danger');
                            }
                        },
                        'targets': [6, 7]
                    }
                ],
                'preDrawCallback': function () {
                    drawing_table_cuidadores = true;
                },
                'drawCallback': function () {
                    drawing_table_cuidadores = false;
                    set_edicao_evento();
                },
                'rowsGroup': [1, 2, 3, 4, 5]
            });

            table_frequencias = $('#table_frequencias').DataTable({
                'dom': "<'row'<'col-sm-3'l><'#legenda4.col-sm-5'><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                'processing': true,
                'serverSide': true,
                'iDisplayLength': 500,
                'lengthMenu': [[5, 10, 25, 50, 100, 500], [5, 10, 25, 50, 100, 500]],
                'orderFixed': [1, 'asc'],
                'rowGroup': {
                    'className': 'active',
                    'startRender': function (rows, group) {
                        return '<strong>Municipio: </strong>' + group;
                    },
                    'dataSrc': 1
                },
                'language': {
                    'url': url
                },
                'ajax': {
                    'url': '<?php echo site_url('cd/apontamento/ajax_frequencias') ?>',
                    'type': 'POST',
                    'timeout': 90000,
                    'data': function (d) {
                        d.busca = busca;
                        return d;
                    },
                    'dataSrc': function (json) {
                        var dt1 = new Date();
                        var dt2 = new Date();
                        dt2.setFullYear(json.calendar.ano, (json.calendar.mes - 1));

                        var semana = 1;
                        var colunaAluno = 4;
                        for (i = 1; i <= 31; i++) {
                            if (i > 28) {
                                if (i > json.calendar.qtde_dias) {
                                    table_frequencias.column(i + colunaAluno).visible(false, false);
                                    continue;
                                } else {
                                    table_frequencias.column(i + colunaAluno).visible(true, false);
                                }
                            }
                            var colunaFrequencias = $(table_frequencias.columns(i + colunaAluno + 2).header());
                            colunaFrequencias.removeClass('text-danger').css('background-color', '');
                            colunaFrequencias.attr({
                                'data-dia': json.calendar.ano + '-' + json.calendar.mes + '-' + colunaFrequencias.text(),
                                'data-mes_ano': json.calendar.semana[semana] + ', ' + colunaFrequencias.text() + '/' + json.calendar.mes + '/' + json.calendar.ano,
                                'title': json.calendar.semana[semana] + ', ' + colunaFrequencias.text() + ' de ' + json.calendar.mes_ano.replace(' ', ' de ')
                            });
                            if (json.calendar.semana[semana] === 'Sábado' || json.calendar.semana[semana] === 'Domingo') {
                                colunaFrequencias.addClass('text-danger').css('background-color', '#dbdbdb');
                            }
                            if ((dt1.getTime() === dt2.getTime()) && dt1.getDate() === i) {
                                colunaFrequencias.css('background-color', '#0f0');
                            }
                            if (i % 7 === 0) {
                                semana = 1;
                            } else {
                                semana++;
                            }
                        }
                        if (json.draw === '1') {
                            $("#legenda4").html('<button title="Mostrar legenda de eventos" data-toggle="modal" data-target="#modal_legenda3" style="margin: 15px 10px 0;" class="btn btn-default btn-sm">' +
                                '<i class="glyphicon glyphicon-exclamation-sign"></i> <span class="hidden-xs"> Mostrar legenda de eventos</span>' +
                                '</button>');
                        }
                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'visible': false,
                        'targets': [0, 1, 3, 6]
                    },
                    {
                        'className': 'text-center',
                        'targets': [4]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[5] === null) {
                                $(td).addClass('danger');
                            }
                        },
                        'targets': [5]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[5] === null) {
                                $(td).addClass('danger');
                            } else if (rowData[col] === null) {
                                $(td).css('background-color', '#dbdbdb').html('');
                            } else {
                                $(td).css({'padding': '8px 1px', 'background-color': '#fff', 'color': '#000'});

                                if ($(table_frequencias.column(col).header()).hasClass('text-danger')) {
                                    $(td).css('background-color', '#e9e9e9');
                                }

                                $(td).attr({
                                    'data-toggle': 'modal',
                                    'data-target': '#modal_alunos',
                                    'data-id': rowData[col][0],
                                    'data-status': rowData[col][1] !== undefined ? rowData[col][1] : ''
                                });
                                $(td).on('click', function () {
                                    if (edicaoEvento === false) {
                                        return false;
                                    }
                                    $('#nome_aluno').html(rowData[5]);
                                    $('#turno_aluno').html(rowData[4] === 'M' ? 'Manhã' : (rowData[4] === 'T' ? 'Tarde' : (rowData[4] === 'N' ? 'Noite' : 'Integral')));
                                    $('#data_aluno').html($(table_frequencias.column(col).header()).data('mes_ano'));
                                    $('#form_frequencias [name="id"]').val($(td).data('id'));
                                    $('#form_frequencias [name="id_matriculado"]').val(rowData[0]);
                                    $('#form_frequencias [name="data"]').val($(table_frequencias.column(col).header()).data('dia'));
                                    $('#form_frequencias [name="status"][value="' + $(td).data('status') + '"]').prop('checked', true);


                                    edit_frequencia($(td).data('id'));
                                });

                                if (rowData[col][2] !== undefined) {
                                    if (rowData[col][1].length > 0) {
                                        $(td).html(rowData[col][1]);
                                    } else {
                                        $(td).css({'background-color': '#5cb85c'}).html('');
                                    }
                                } else {
                                    $(td).html('');
                                }
                            }

                        },
                        'className': 'text-center',
                        'orderable': false,
                        'searchable': false,
                        'targets': 'date-width'
                    }
                ],
                'preDrawCallback': function () {
                    drawing_table_frequencias = true;
                },
                'drawCallback': function () {
                    drawing_table_frequencias = false;
                    set_edicao_evento();
                },
                'rowsGroup': [2, 4, 5]
            });

            atualizarColaboradores();
            setPdf_atributes();
        });


        $('#form_frequencias [name="status"]').on('change', function () {
            if ($(this).val().length > 0) {
                $('.qtde_insumos').prop('readonly', true).val(0);
            } else {
                $('.qtde_insumos').prop('readonly', false);
            }
        });


        function atualizarFiltro() {
            var data = $('#busca').serialize();
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/atualizar_filtro') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': data,
                'beforeSend': function () {
                    $('#busca [name="depto"], #busca [name="diretoria"], #busca [name="supervisor"]').prop('disabled', true);
                },
                'success': function (json) {
                    $('#busca [name="diretoria"]').replaceWith(json.diretoria);
                    $('#busca [name="supervisor"]').replaceWith(json.supervisor);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                },
                'complete': function () {
                    $('#busca [name="depto"], #busca [name="diretoria"], #busca [name="supervisor"]').prop('disabled', false);
                }
            });
        }


        function atualizarColaboradores(turno, value) {
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/ajax_cuidadores_sub') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'busca': busca,
                    'turno': turno,
                    'value': value
                },
                'success': function (json) {
                    $('#id_diretoria, #id_diretoria_matr').html($(json.id_diretoria).html());
                    $('#id_escola, #id_escola_matr').html($(json.id_escola).html());
                    $('#form_colaborador [name="id_vinculado"]').html($(json.id_usuario).html());
                    $('#form_colaborador [name="mes"], #form_matriculados [name="mes"]').val($('#busca [name="mes"]').val());
                    $('#form_colaborador [name="ano"], #form_matriculados [name="mes"]').val($('#busca [name="ano"]').val() + 1);
                    $('#form_colaborador_alocado [name="id_vinculado"]').html($(json.id_usuario_alocado).html());
                    $('[name="id_cuidador_sub"]').html($(json.id_cuidador_sub).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function atualizarDetalhes() {
            $.ajax({
                'url': '<?php echo site_url('apontamento/ajax_edit') ?>',
                'type': 'POST',
                'dataType': 'html',
                'success': function (json) {
                    $('#detalhes').replaceWith(json);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function atualizarCuidadores() {
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/ajax_novo_cuidador') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'diretoria': $('#form_colaborador #id_diretoria').val(),
                    'escola': $('#form_colaborador #id_escola').val(),
                    'vinculado': $('#form_colaborador [name="id_vinculado"]').val()
                },
                'success': function (json) {
                    $('#form_colaborador #id_escola').html($(json.id_escola).html());
                    $('#form_colaborador [name="id_vinculado"]').html($(json.id_vinculado).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function atualizarMatriculados() {
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/ajax_novo_matriculado') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'diretoria': $('#form_matriculados #id_diretoria_matr').val(),
                    'escola': $('#form_matriculados #id_escola_matr').val()
                },
                'success': function (json) {
                    $('#form_matriculados #id_escola_matr').html($(json.id_escola).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
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
                'url': '<?php echo site_url('cd/apontamento/ajax_config') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': busca,
                'success': function (json) {
                    $('#form_config [name="id"]').val(json.id);
                    $('#form_config [name="id_alocacao"]').val(json.id_alocacao);
                    $('#form_config [name="mes"]').val(json.mes);
                    $('#form_config [name="ano"]').val(json.ano);
                    $('#form_config [name="total_faltas"]').val(json.total_faltas);
                    $('#form_config [name="total_faltas_justificadas"]').val(json.total_faltas_justificadas);

                    $('#form_config [name="turnover_substituicao"]').val(json.turnover_substituicao);
                    $('#form_config [name="turnover_aumento_quadro"]').val(json.turnover_aumento_quadro);
                    $('#form_config [name="turnover_desligamento_empresa"]').val(json.turnover_desligamento_empresa);
                    $('#form_config [name="turnover_desligamento_solicitacao"]').val(json.turnover_desligamento_solicitacao);

                    $('#form_config [name="intercorrencias_diretoria"]').val(json.intercorrencias_diretoria);
                    $('#form_config [name="intercorrencias_cuidador"]').val(json.intercorrencias_cuidador);
                    $('#form_config [name="intercorrencias_alunos"]').val(json.intercorrencias_alunos);
                    $('#form_config [name="acidentes_trabalho"]').val(json.acidentes_trabalho);

                    $('#form_config [name="total_escolas"]').val(json.total_escolas);
                    $('#form_config [name="total_alunos"]').val(json.total_alunos);
                    $('#form_config [name="dias_letivos"]').val(json.dias_letivos);
                    $('#form_config [name="total_cuidadores"]').val(json.total_cuidadores);
                    $('#form_config [name="total_cuidadores_cobrados"]').val(json.total_cuidadores_cobrados);
                    $('#form_config [name="total_cuidadores_ativos"]').val(json.total_cuidadores_ativos);
                    $('#form_config [name="total_cuidadores_afastados"]').val(json.total_cuidadores_afastados);
                    $('#form_config [name="total_supervisores"]').val(json.total_supervisores);
                    $('#form_config [name="total_supervisores_cobrados"]').val(json.total_supervisores_cobrados);
                    $('#form_config [name="total_supervisores_ativos"]').val(json.total_supervisores_ativos);
                    $('#form_config [name="total_supervisores_afastados"]').val(json.total_supervisores_afastados);

                    $('#form_config [name="faturamento_projetado"]').val(json.faturamento_projetado);
                    $('#form_config [name="faturamento_realizado"]').val(json.faturamento_realizado);

                },
                'error': function (jqXHR, textStatus, errorThrown) {
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
            }
        });

        $('#form_colaborador_alocado [name="id_vinculado"]').on('change', function () {
            $('#btnDeleteColaborador').prop('disabled', this.value.length === 0);
        });

        $('[name="id_alocado_bck"]').on('change', function () {
            if (this.value === '') {
                if ($('[name="status"]').val() === 'FJ' || $('[name="status"]').val() === 'FN') {
                    $('[name="qtde_dias"]').val(1);
                }
            } else {
                $('[name="qtde_dias"]').val(0);
            }
        });

        $('[name="id_usuario"]').on('change', function () {
            $('#copiar_posto').prop('disabled', this.value.length === 0);
        });

        $('#calcular_valor').on('click', function () {
            calcular_valores();
        });

        $('.valor').on('change', function () {
            calcular_valores();
        });


        $('#modal_colaborador').on('show.bs.modal', function () {
            $('#form_colaborador #id_diretoria').val($('#busca [name="diretoria"]').val());
            atualizarCuidadores();
            $('[name="mes"]').val('<?php echo date('m') ?>');
            $('[name="ano"]').val('<?php echo date('Y') ?>');
            $('[name="valor_posto"], [name="valor_dia"], [name="valor_hora"]').val('');
            $('[name="total_dias_mensais"], [name="total_horas_diarias"]').val('');
            $('#copiar_posto').prop('disabled', true);
        });

        $('#modal_matriculados').on('show.bs.modal', function () {
            $('#form_matriculados #id_diretoria_matr').val($('#busca [name="diretoria"]').val());
            atualizarMatriculados();
            $('[name="mes"]').val('<?php echo date('m') ?>');
            $('[name="ano"]').val('<?php echo date('Y') ?>');
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
                'url': '<?php echo site_url('apontamento_postos/ajax_posto') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id_usuario': $('[name="id_usuario"]').val()},
                'success': function (json) {
                    $('[name="total_dias_mensais"]').val(json.total_dias_mensais);
                    $('[name="total_horas_diarias"]').val(json.total_horas_diarias);
                    $('[name="valor_posto"]').val(json.valor_posto);
                    $('[name="valor_dia"]').val(json.valor_dia);
                    $('[name="valor_hora"]').val(json.valor_hora);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function sugestao_detalhe(event) {
            $('[name="detalhes"]').val($(event).text());
        }


        function selecionar_status(value) {
            if (value === 'FA' || value === 'FS' || value === 'FE' || value === 'PD' || value === 'PI' ||
                value === 'ID' || value === 'IC' || value === 'IA' || value === 'AT') {
                $('[name="qtde_dias"]').css({'background-color': '#dff0d8', 'color': '#3c763d'});
                $('.hora').css({'background-color': '', 'color': ''});
            } else if (value === 'AE' || value === 'AJ' || value === 'AN' || value === 'SJ' || value === 'SN') {
                $('[name="qtde_dias"]').css({'background-color': '', 'color': ''});
                $('.hora').css({'background-color': '#dff0d8', 'color': '#3c763d'});
            } else {
                $('[name="qtde_dias"], .hora').css({'background-color': '', 'color': ''});
            }
        }


        function edit_frequencia(id_frequencia) {
            if (id_frequencia === undefined) {
                id_frequencia = '';
            }
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/ajax_edit_frequencia') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_frequencia': id_frequencia
                },
                'success': function (json) {
                    $('#insumos').html(json.qtde_insumos);
                    $('#form_frequencias [name="status"]:checked').trigger('change');
                    if (id_frequencia.length > 0) {
                        $('#btnLimparfrequencia').show();
                    } else {
                        $('#btnLimparfrequencia').hide();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function proximo_mes(value = 1) {
            if ($('#mes_seguinte').hasClass('disabled') && value === 1) {
                return false;
            }
            $('#mes_anterior, #mes_seguinte').prop('disabled', true).hover();
            var dt = new Date($('[name="ano"]').val(), $('[name="mes"]').val() - 1);
            dt.setMonth(dt.getMonth() + (value));
            $('[name="mes"]').val(dt.getMonth() < 9 ? '0' + (dt.getMonth() + 1) : dt.getMonth() + 1);
            $('[name="ano"]').val(dt.getFullYear());

            busca = $('#busca').serialize();
            reload_table(true);
            atualizarColaboradores();
            setPdf_atributes();
            $('#mes_anterior, #mes_seguinte').prop('disabled', false);
        }


        function filtrar() {
            var data_proximo_mes = new Date();
            var data_busca = new Date();
            data_proximo_mes.setDate(1);
            data_proximo_mes.setMonth(data_proximo_mes.getMonth() + 1);
            data_busca.setFullYear($('[name="ano"]').val(), ($('[name="mes"]').val() - 1), 1);

            busca = $('#busca').serialize();
            reload_table();
            setPdf_atributes();
            if (data_proximo_mes.getTime() < data_busca.getTime()) {
                $('[name="mes"]').val(data_proximo_mes.getMonth() + 1);
                $('[name="ano"]').val(data_proximo_mes.getFullYear());
            }
            $('#alerta_depto').text($('[name="depto"] option:selected').html());
            $('#alerta_diretoria').text($('[name="diretoria"] option:selected').html());
            $('#alerta_supervisor').text($('[name="supervisor"] option:selected').html());
        }


        function add_mes() {
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/novo') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': busca,
                'success': function (json) {
                    if (json.erro !== undefined) {
                        alert(json.erro);
                    } else {
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function add_colaborador() {
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento_colaboradores/novo') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': busca,
                'success': function (json) {
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function excluir_mes() {
            if (confirm('Deseja limpar o mês selecionado?')) {
                $.ajax({
                    'url': '<?= site_url('cd/apontamento/ajax_limpar') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': busca,
                    'success': function (json) {
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
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
                'url': '<?php echo site_url('cd/apontamento/ajax_colaboradores/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $('#form_colaborador [name="id"]').val(json.id);
                    $('#form_colaborador [name="area"]').val(json.area);
                    $('#form_colaborador [name="setor"]').val(json.setor);
                    atualizarSetor();
                    $('#form_colaborador [name="contrato"]').val(json.contrato);
                    $('#form_colaborador [name="telefone"]').val(json.telefone);
                    $('#form_colaborador [name="email"]').val(json.email);
                    $('#form_colaborador [name="status"]').val(json.status);
                    $('#colaborador').html(json.nome);
                    $('#modal_colaboradores').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
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
                'url': '<?php echo site_url('avaliacaoexp_avaliados/edit_status') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $('[name="id"]').val(json.id);
                    $('#nome').text(json.nome);
                    $('[name="observacoes"]').val(json.observacoes);
                    $('#modal_form').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function reload_table(reset = false) {
            edicaoEvento = false;
            $('#mes_ano').append('&ensp;(Processando - Aguarde...)').parent().find('button').prop('disabled', true);
            $('#filtro').prop('disabled', true);

            table.ajax.reload(null, reset); //reload datatable ajax
            if ('<?= $modo_privilegiado ?>') {
                table_funcionarios.ajax.reload(null, reset); //reload datatable ajax
            }
            table_cuidadores.ajax.reload(null, reset); //reload datatable ajax
            table_frequencias.ajax.reload(null, reset); //reload datatable ajax
        }


        function set_edicao_evento() {
            var a = drawing_table;
            var b = drawing_table_funcionarios;
            var c = drawing_table_cuidadores;
            var d = drawing_table_frequencias;

            if (a === false && b === false && c === false && d === false) {
                $('#mes_anterior, #btnOpcoesMes, #mes_seguinte, #filtro').prop('disabled', false);
                $('#mes_ano').html().replace('&ensp;(Processando - Aguarde...)', '');
                edicaoEvento = true;
            }
        }


        function buscar_configuracoes() {
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/ajax_editConfig') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': (busca + '&' + $('#form_config').serialize()),
                'beforeSend': function () {
                    $('#btnBuscarConfig').text('Buscando...').attr('disabled', true);
                },
                'success': function (json) {
                    $('#total_faltas').val(json.total_faltas);
                    $('#total_faltas_justificadas').val(json.total_faltas_justificadas);
                    $('#form_config [name="intercorrencias_diretoria"]').val(json.intercorrencias_diretoria);
                    $('#form_config [name="intercorrencias_cuidador"]').val(json.intercorrencias_cuidador);
                    $('#form_config [name="intercorrencias_alunos"]').val(json.intercorrencias_alunos);
                    $('#form_config [name="acidentes_trabalho"]').val(json.acidentes_trabalho);

                    $('#form_config [name="total_escolas"]').val(json.total_escolas);
                    $('#form_config [name="total_alunos"]').val(json.total_alunos);
                    $('#form_config [name="dias_letivos"]').val(json.dias_letivos);
                    $('#form_config [name="total_cuidadores"]').val(json.total_cuidadores);
                    $('#form_config [name="total_cuidadores_ativos"]').val(json.total_cuidadores_ativos);
                    $('#form_config [name="total_supervisores_ativos"]').val(json.total_supervisores_ativos);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                },
                'complete': function () {
                    $('#btnBuscarConfig').text('Buscar valores').attr('disabled', false);
                }
            });
        }


        function salvar_configuracoes() {
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/ajax_saveConfig') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': (busca + '&' + $('#form_config').serialize()),
                'beforeSend': function () {
                    $('#btnSaveConfig').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    $('#modal_config').modal('hide');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                },
                'complete': function () {
                    $('#btnSaveConfig').text('Salvar').attr('disabled', false);
                }
            });
        }


        function save_remanejado() {
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/ajax_saveRemanejado') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_remanejado').serialize(),
                'beforeSend': function () {
                    $('#btnSaveRemanejado').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    $('#modal_remanejado').modal('hide');
                    table.ajax.reload(null, false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                },
                'complete': function () {
                    $('#btnSaveRemanejado').text('Salvar').attr('disabled', false);
                }
            });
        }


        function editar_alocado(id_alocado) {
            $.ajax({
                'url': "<?php echo site_url('cd/apontamento/ajax_editAlocado') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id_alocado},
                'success': function (json) {
                    $('#alocado_municipio').html(json.municipio);
                    $('#alocado_escola').html(json.escola);
                    $('#alocado_turno').html(json.turno);

                    $('#form_alocado [name="id"]').val(json.id);
                    $('#form_alocado [name="id_vinculado"]').html($(json.id_vinculado).html());
                    $('#modal_alocado').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function save_alocado() {
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/ajax_saveAlocado') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_alocado').serialize(),
                'beforeSend': function () {
                    $('#btnSaveAlocado').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.erro !== undefined) {
                        alert(json.erro);
                    } else {
                        $('#modal_alocado').modal('hide');
                        table.ajax.reload(null, false);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                },
                'complete': function () {
                    $('#btnSaveAlocado').text('Salvar').attr('disabled', false);
                }
            });
        }

        function salvar_ferias() {
            $.ajax({
                'url': '<?php echo site_url('apontamento/ajax_ferias') ?>',
                'type': 'POST',
                'data': $('#form_backup').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveBackup').text('Salvando...');
                    $('#btnSaveBackup, #btnLimparBackup').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_backup').modal('hide');
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.statusText === 'OK') {
                        alert(jqXHR.responseText);
                    } else {
                        alert('Erro ao enviar formulário');
                    }
                },
                'complete': function () {
                    $('#btnSaveBackup').text('Salvar');
                    $('#btnSaveBackup, #btnLimparBackup').attr('disabled', false);
                }
            });
        }


        function salvar_substituto() {
            $.ajax({
                'url': '<?php echo site_url('apontamento/ajax_substituto') ?>',
                'type': 'POST',
                'data': $('#form_substituto').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveSubstituto').text('Salvando...');
                    $('#btnSaveSubstituto, #btnLimparSubstituto').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_substituto').modal('hide');
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.statusText === 'OK') {
                        alert(jqXHR.responseText);
                    } else {
                        alert('Erro ao enviar formulário');
                    }
                },
                'complete': function () {
                    $('#btnSaveSubstituto').text('Salvar');
                    $('#btnSaveSubstituto, #btnLimparSubstituto').attr('disabled', false);
                }
            });
        }


        function salvar_backup2() {
            $.ajax({
                'url': '<?php echo site_url('apontamento/ajax_backup2') ?>',
                'type': 'POST',
                'data': $('#form_backup2').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveBackup2').text('Salvando...');
                    $('#btnSaveBackup2, #btnLimparBackup2').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_backup2').modal('hide');
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.statusText === 'OK') {
                        alert(jqXHR.responseText);
                    } else {
                        alert('Erro ao enviar formulário');
                    }
                },
                'complete': function () {
                    $('#btnSaveBackup2').text('Salvar');
                    $('#btnSaveBackup2, #btnLimparBackup2').attr('disabled', false);
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
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/ajax_save') ?>',
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSave').text('Salvando...');
                    $('#btnSave, #btnApagar').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSave').text('Salvar');
                    $('#btnSave, #btnApagar').attr('disabled', false);
                }
            });
        }


        function save_eventos() {
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/ajaxSaveEventos') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'busca': busca,
                    'eventos': $('#form_eventos').serialize()
                },
                'beforeSend': function () {
                    $('#btnSaveEventos').text('Replicando...');
                    $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', true);
                },
                'success': function (json) {
                    $('#modal_eventos').modal('hide');
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                },
                'complete': function () {
                    $('#btnSaveEventos').text('Replicar');
                    $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', false);
                }
            });
        }


        function delete_eventos() {
            if (confirm('Deseja limpar os eventos desta data?')) {
                $.ajax({
                    'url': '<?php echo site_url('cd/apontamento/ajaxDeleteEventos') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {
                        'busca': busca,
                        'eventos': $('#form_eventos').serialize()
                    },
                    'beforeSend': function () {
                        $('#btnDeleteEventos').text('Limpando...');
                        $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', true);
                    },
                    'success': function (json) {
                        $('#modal_eventos').modal('hide');
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    },
                    'complete': function () {
                        $('#btnDeleteEventos').text('Limpar');
                        $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', false);
                    }
                });
            }
        }


        if ('<?= $modo_privilegiado ?>') {
            function save_totalizacao() {
                $.ajax({
                    'url': '<?php echo site_url('apontamento_totalizacao/ajax_save') ?>',
                    'type': 'POST',
                    'data': $('#form_totalizacao').serialize(),
                    'dataType': 'json',
                    'beforeSend': function () {
                        $('#btnSaveTotalizacao').text('Salvando...').attr('disabled', true);
                    },
                    'success': function (json) {
                        if (json.status) {
                            $('#modal_totalizacao').modal('hide');
                            reload_table();
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    },
                    'complete': function () {
                        $('#btnSaveTotalizacao').text('Salvar').attr('disabled', false);
                    }
                });
            }
        }

        function save_aluno() {
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/ajax_save_frequencia') ?>',
                'type': 'POST',
                'data': $('#form_frequencias').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveFrequencia').text('Salvando...');
                    $('#btnSaveFrequencia, #btnLimparfrequencia').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_alunos').modal('hide');
                        table_frequencias.ajax.reload(null, false);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveFrequencia').text('Salvar');
                    $('#btnSaveFrequencia, #btnLimparfrequencia').attr('disabled', false);
                }
            });
        }


        function save_colaborador() {
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/ajax_save_cuidador') ?>',
                'type': 'POST',
                'data': {
                    'id_escola': $('#form_colaborador [name="id_escola"]').val(),
                    'mes': $('#form_colaborador [name="mes"]').val(),
                    'ano': $('#form_colaborador [name="ano"]').val()
                },
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveColaborador').text('Alocando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_colaborador').modal('hide');
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveColaborador').text('Alocar').attr('disabled', false);
                }
            });
        }


        function save_matriculados() {
            $.ajax({
                'url': '<?php echo site_url('cd/apontamento/ajax_save_matriculados') ?>',
                'type': 'POST',
                'data': $('#form_matriculados').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveMatriculados').text('Alocando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_matriculados').modal('hide');
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveMatriculados').text('Alocar').attr('disabled', false);
                }
            });
        }


        function limpar_frequencia() {
            if (confirm('Deseja limpar o evento?')) {
                $.ajax({
                    'url': '<?php echo site_url('cd/apontamento/ajax_limpar_frequencia') ?>',
                    'type': 'POST',
                    'data': {
                        'id': $('#form_frequencias [name="id"]').val()
                    },
                    'dataType': 'json',
                    'beforeSend': function () {
                        $('#btnLimparfrequencia').text('Limpando evento...').attr('disabled', true);
                    },
                    'success': function (json) {
                        if (json.status) {
                            $('#modal_alunos').modal('hide');
                            reload_table();
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    },
                    'complete': function () {
                        $('#btnLimparfrequencia').text('Limpar evento').attr('disabled', false);
                    }
                });
            }
        }


        function delete_colaborador() {
            if (confirm('Deseja remover a alocação do colaborador selecionado?')) {
                $.ajax({
                    'url': '<?php echo site_url('cd/apontamento/ajax_delete_cuidador') ?>',
                    'type': 'POST',
                    'data': {
                        'busca': busca,
                        'id_vinculado': $('#form_colaborador_alocado [name="id_vinculado"]').val()
                    },
                    'dataType': 'json',
                    'beforeSend': function () {
                        $('#btnDeleteColaborador').text('Desalocando...').attr('disabled', true);
                    },
                    'success': function (json) {
                        if (json.status) {
                            $('#modal_colaborador_alocado').modal('hide');
                            reload_table();
                        } else if (json.erro) {
                            alert(json.erro);
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    },
                    'complete': function () {
                        $('#btnDeleteColaborador').text('Desalocar').attr('disabled', false);
                    }
                });
            }
        }


        function apagar() {
            if (confirm('Deseja limpar o evento selecionado?')) {
                $.ajax({
                    'url': '<?php echo site_url('cd/apontamento/ajax_delete') ?>',
                    'type': 'POST',
                    'data': {
                        'id': $('[name="id"]').val()
                    },
                    'dataType': 'json',
                    'beforeSend': function () {
                        $('#btnApagar').text('Limpando...').attr('disabled', true);
                        $('#btnSave').attr('disabled', true);
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
                        alert(textStatus ? textStatus : 'Error adding / update data');
                    },
                    'complete': function () {
                        $('#btnApagar').text('Limpar evento').attr('disabled', false);
                        $('#btnSave').attr('disabled', false);
                    }
                });
            }
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

            q = q.filter(function (v) {
                return v.length > 0;
            });
            if (q.length > 0) {
                search = '/q?' + q.join('&');
            }

            $('#pdf').prop('href', "<?= site_url('cd/relatorios/index/'); ?>" + search);
            $('#pdfEscolas').prop('href', "<?= site_url('cd/relatorios/escolas/'); ?>" + search);
            $('#pdfInsumos').prop('href', "<?= site_url('cd/relatorios/insumos/'); ?>" + search);
            $('#pdfResultados').prop('href', "<?= site_url('cd/relatorios/resultados/'); ?>" + search);
            $('#pdfResultadosDiretorias').prop('href', "<?= site_url('cd/relatorios/resultadosDiretorias/'); ?>" + search);
            if ($('[name="diretoria"]').val().length > 0) {
                $('#pdfResultadosDiretorias').removeAttr('onclick').css('color', '#333');
            } else {
                $('#pdfResultadosDiretorias').attr('onclick', 'return false').css('color', '#888');
            }
            $('#pdfResultadosConsolidados').prop('href', "<?= site_url('cd/relatorios/resultadosConsolidados/'); ?>" + search);
            $('#pdfCuidadores').prop('href', "<?= site_url('cd/relatorios/pdfCuidadores/'); ?>" + search);

            if ($('[name="supervisor"]').val().length > 0) {
                $('#pdf, #pdfEscolas, #pdfInsumos').removeAttr('onclick').css('color', '#333');
            } else {
                $('#pdf, #pdfEscolas, #pdfInsumos').attr('onclick', 'return false').css('color', '#888');
            }
            if ($('[name="diretoria"]').val().length > 0 && $('[name="supervisor"]').val().length > 0) {
                $('#config, #pdfResultados').removeAttr('onclick').css('color', '#333');
                $('#limparMes').show();
                $('#naoLimparMes').hide();
            } else {
                $('#config, #pdfResultados').attr('onclick', 'return false').css('color', '#888');
                $('#limparMes').hide();
                $('#naoLimparMes').show();
            }
        }

    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>