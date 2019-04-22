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
                        <li class="active">Apontamentos Diários</li>
                    </ol>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <i class="glyphicon glyphicon-list-alt"></i> Gerenciar <span
                                            class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="colaboradores"><i
                                                    class="glyphicon glyphicon-list-alt text-primary"></i>
                                            Colaboradores</a></li>
                                    <?php if (in_array($this->session->userdata('nivel'), array(0, 4, 7, 8, 9))): ?>
                                        <li><a href="diretorias"><i
                                                        class="glyphicon glyphicon-list-alt text-primary"></i>
                                                Clientes/Diretorias</a></li>
                                    <?php endif; ?>
                                    <li><a href="escolas"><i class="glyphicon glyphicon-list-alt text-primary"></i>
                                            Unidades de ensino</a></li>
                                    <?php if (in_array($this->session->userdata('nivel'), array(0, 4, 7, 8, 9, 10))): ?>
                                        <li><a href="supervisores"><i
                                                        class="glyphicon glyphicon-list-alt text-primary"></i>
                                                Vincular supervisores</a></li>
                                    <?php endif; ?>
                                    <li><a href="cursosDisciplinas"><i
                                                    class="glyphicon glyphicon-list-alt text-primary"></i>
                                            Cursos/Disciplinas</a></li>
                                    <li><a href="alunos"><i class="glyphicon glyphicon-list-alt text-primary"></i>
                                            Gerenciar alunos</a></li>
                                    <?php if (in_array($this->session->userdata('nivel'), array(0, 4, 7, 8, 9, 10))): ?>
                                        <li><a href="insumos"><i
                                                        class="glyphicon glyphicon-list-alt text-primary"></i>
                                                Gerenciar insumos</a></li>
                                    <?php endif; ?>
                                    <li><a href="ordemServico"><i
                                                    class="glyphicon glyphicon-list-alt text-primary"></i> Ordens de
                                            Serviço</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="javascript:;" onclick="preparar_os();"><i
                                                    class="glyphicon glyphicon-plus text-info"></i> Iniciar
                                            semestre</a></li>
                                    <li><a href="javascript:;" onclick="preparar_os_individual();"><i
                                                    class="glyphicon glyphicon-plus text-info"></i> O.S.
                                            individual</a></li>
                                    <li><a href="javascript:;" onclick="preparar_exclusao_os();"><i
                                                    class="glyphicon glyphicon-minus text-danger"></i> Limpar
                                            semestre</a>
                                    </li>
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
                                            id="alerta_depto"><?= empty($depto_atual) ? 'Todos' : $depto_atual ?></span></small><br>
                                <small>&emsp;<strong>Cliente:</strong> <span
                                            id="alerta_diretoria"><?= empty($diretoria_atual) ? 'Todos' : $diretoria_atual ?></span></small><br>
                                <small>&emsp;<strong>Supervisor:</strong> <span
                                            id="alerta_supervisor"><?= empty($supervisor_atual) ? 'Todos' : $supervisor_atual ?></span></small><br>
                                <small>&emsp;<strong>Semestre:</strong> <span
                                            id="alerta_semestre"><?= (date('n') > 7 ? 2 : 1) . '&ordm;' ?></span></small>
                            </span>
                            </p>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">
                            <span id="mes_ano"><?= ucfirst($mes) . ' ' . date('Y') ?></span>
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
                                        <li><a href="javascript:void(0)" onclick="mapaCarregamentoDeOS();"><i
                                                        class="glyphicon glyphicon-list text-info"></i>
                                                Rel. Mapa de Carregamento de O.S.</a></li>
                                        <li><a href="javascript:void(0)" onclick="medicao();"><i
                                                        class="glyphicon glyphicon-list text-info"></i> Relatório de
                                                Medição Mensal</a></li>
                                        <li><a href="javascript:void(0)" onclick="pagamentoPrestadores();"><i
                                                        class="glyphicon glyphicon-list text-info"></i> Relatório
                                                consolidado de Pagamento</a></li>
                                        <li><a href="javascript:void(0)" onclick="faturamentoConsolidado();"><i
                                                        class="glyphicon glyphicon-list text-info"></i> Relatório
                                                consolidado de Faturamento</a></li>
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
                                            data-toggle="tab">Apontamento Frequência</a></li>
                                <li role="presentation" style="font-size: 13px; font-weight: bolder"><a
                                            href="#faturamento" aria-controls="faturamento" role="tab"
                                            data-toggle="tab">Faturamento + Pagamento prestadores</a></li>
                                <li role="presentation" style="font-size: 13px; font-weight: bolder"><a
                                            href="#controle_materiais" aria-controls="controle_materiais" role="tab"
                                            data-toggle="tab">Controle de Materiais</a></li>
                                <li role="presentation" style="font-size: 13px; font-weight: bolder"><a
                                            href="#visitas" aria-controls="visitas" role="tab"
                                            data-toggle="tab">Mapa de visitação</a></li>
                            </ul>

                            <div class="tab-content" style="border: 1px solid #ddd; border-top-width: 0;">
                                <div role="tabpanel" class="tab-pane active" id="apontamento">
                                    <table id="table"
                                           class="table table-hover table-striped table_apontamento table-condensed table-bordered"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th rowspan="2">ID</th>
                                            <th rowspan="2" class="warning">Município/escola</th>
                                            <th rowspan="2" class="warning total_funcionarios"
                                                style="vertical-align: middle;">
                                                Funcionário(a)
                                            </th>
                                            <th rowspan="2" class="warning total_alunos" nowrap
                                                style="vertical-align: middle; padding-left: 4px; padding-right: 4px;">
                                                Aluno(s) - Horário início
                                            </th>
                                            <td colspan="31" class="date-width" id="dias"><strong>Dias</strong>
                                            </td>
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
                                <div role="tabpanel" class="tab-pane" id="faturamento">
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-7">&emsp;
                                            <button type="button" class="btn btn-sm btn-success"
                                                    id="btnFecharMes" onclick="fechar_mes();">1 - Fechar mês
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success"
                                                    id="btnTotalizarMes" onclick="totalizar_mes();">2 - Totalizar mês
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success" id="btnSalvarMes">3 -
                                                Salvar mês
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                    id="btnRelatorioFaturamento"
                                                    onclick="planilha_faturamento_consolidado();">4 - Relatório
                                                faturamento
                                            </button>
                                        </div>
                                        <div class=" col-sm-5 text-right">
                                            <button type="button" class="btn btn-sm btn-success"
                                                    id="btnRecalcularIngresso" onclick="recalcular_ingresso();">
                                                Recalcular datas início reais
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success"
                                                    id="btnRecalcularRecesso" onclick="recalcular_recesso();">
                                                Recalcular datas término reais
                                            </button>
                                        </div>
                                    </div>
                                    <table id="table_faturamento"
                                           class="table table-hover table_apontamento table-condensed table-bordered"
                                           cellspacing="0"
                                           width="100%" style="border-radius: 0 !important;">
                                        <thead>
                                        <tr>
                                            <th rowspan="2">Município/funcionário(a)</th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">Dia semana
                                            </th>
                                            <th rowspan="2" class="warning text-center" style="vertical-align: middle;">
                                                Entrada
                                                - saída
                                            </th>
                                            <th rowspan="2" class="warning text-center" style="vertical-align: middle;">
                                                Qtde.
                                                horas
                                            </th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">
                                                Profissional
                                            </th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">Função
                                            </th>
                                            <th colspan="6" class="text-center">Profissional principal</th>
                                            <th colspan="6" class="text-center">Profissional substituto(a) 1
                                            </th>
                                            <th colspan="6" class="text-center">Profissional substituto(a) 2
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Qtde. dias</th>
                                            <th>Desc. Fatur.</th>
                                            <th>Qtde. horas</th>
                                            <th>Total horas</th>
                                            <th>Desc. Pres.</th>
                                            <th>Pgto. Pserv.</th>

                                            <th>Qtde. dias</th>
                                            <th>Desc. Fatur.</th>
                                            <th>Qtde. horas</th>
                                            <th>Total horas</th>
                                            <th>Desc. Pres.</th>
                                            <th>Pgto. Pserv.</th>

                                            <th>Qtde. dias</th>
                                            <th>Desc. Fatur.</th>
                                            <th>Qtde. horas</th>
                                            <th>Total horas</th>
                                            <th>Desc. Pres.</th>
                                            <th>Pgto. Pserv.</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="controle_materiais">
                                    <table id="table_controle_materiais"
                                           class="table table-hover table_apontamento table-condensed table-bordered"
                                           cellspacing="0"
                                           width="100%" style="border-radius: 0 !important;">
                                        <thead>
                                        <tr>
                                            <th rowspan="2">Município</th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">Aluno(a)
                                            </th>
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

                                <div role="tabpane" class="tab-pane" id="visitas">
                                    <table id="table_visitas"
                                           class="table table-hover table-condensed table-bordered" cellspacing="0"
                                           width="100%" style="border-radius: 0 !important;">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" class="warning">Município</th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">Unidade</th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">ID</th>
                                            <th colspan="14" class="text-center">Mapa de Visitas do semestre</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center nome_mes1"><?= $semestre[0]; ?></th>
                                            <th class="text-center nome_mes2"><?= $semestre[1]; ?></th>
                                            <th class="text-center nome_mes3"><?= $semestre[2]; ?></th>
                                            <th class="text-center nome_mes4"><?= $semestre[3]; ?></th>
                                            <th class="text-center nome_mes5"><?= $semestre[4]; ?></th>
                                            <th class="text-center nome_mes6"><?= $semestre[5]; ?></th>
                                            <th class="text-center nome_mes7"><?= $semestre[6]; ?></th>
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
                                        <label class="control-label">Filtrar por cliente</label>
                                        <?php echo form_dropdown('diretoria', $diretoria, $diretoria_atual, 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Filtrar por supervisor</label>
                                        <?php echo form_dropdown('supervisor', $supervisor, $supervisor_atual, 'class="form-control input-sm"'); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Mês</label>
                                        <?php echo form_dropdown('mes', $meses, date('m'), 'class="form-control input-sm" onchange="atualizarSemestre();"'); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Ano</label>
                                        <input name="ano" type="number" value="<?= date('Y') ?>" size="4"
                                               class="form-control input-sm" placeholder="aaaa">
                                    </div>
                                    <div class="col-md-2" id="busca_semestre">
                                        <label class="control-label">Semestre</label>
                                        <div>
                                            <label class="radio-inline">
                                                <?php echo form_radio('semestre', '1', date('n') <= 7, 'id="semestre1"'); ?>
                                                1&ordm;
                                            </label>
                                            <label class="radio-inline">
                                                <?php echo form_radio('semestre', '2', date('n') > 7, 'id="semestre2"'); ?>
                                                2&ordm;
                                            </label>
                                        </div>
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
            <div class="modal fade" id="modal_os" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Iniciar semestre</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_os" class="form-horizontal" autocomplete="off">
                                <input type="hidden" name="depto" value="">
                                <input type="hidden" name="diretoria" value="">
                                <input type="hidden" name="supervisor" value="">
                                <input type="hidden" name="ano" value="">
                                <input type="hidden" name="semestre" value="">
                                <input type="hidden" name="mes" value="">
                                <div class="row form-group">
                                    <div class="col-md-11 col-md-offset-1">
                                        <label class="radio-inline">
                                            <input type="radio" name="possui_mapa_visitacao" value="1" checked>
                                            Iniciar Apontamentos + Mapa de Visitação
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="possui_mapa_visitacao" value="0"> Iniciar
                                            somente Apontamentos
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col-md-10 col-md-offset-1"
                                         style="border: 2px solid #ccc; padding-top:14px; margin-bottom:12px;">
                                        <div class="row form-group">
                                            <div class="col-md-5">
                                                <label class="radio-inline">
                                                    <input type="radio" class="iniciar_os" value="1" checked> Iniciar
                                                    todas as O. S.
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" class="iniciar_os" value="2"> Iniciar por escola
                                                </label>
                                            </div>
                                            <label class="control-label col-md-3">Ordem de serviço</label>
                                            <div class="col-md-4">
                                                <?php echo form_dropdown('ordem_servico', ['' => 'selecione...'], '', 'class="form-control" onchange="filtrar_os_escolas();"'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <?php echo form_multiselect('escolas[]', [], [], 'id="os_escolas" class="demo1" size="8"'); ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveOS" onclick="iniciar_semestre()" class="btn btn-success"
                                    data-dismiss="modal">Alocar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_os_individual" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Alocar Ordem de Serviço Individual</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_os_individual" class="form-horizontal" autocomplete="off">
                                <input type="hidden" name="depto" value="">
                                <input type="hidden" name="diretoria" value="">
                                <input type="hidden" name="supervisor" value="">
                                <input type="hidden" name="ano" value="">
                                <input type="hidden" name="semestre" value="">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Ordem de serviço</label>
                                    <div class="col-md-8">
                                        <?php echo form_dropdown('ordem_servico', array('' => 'selecione...'), '', 'class="form-control"'); ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveOSIndividual" onclick="salvar_os_individual()"
                                    class="btn btn-success"
                                    data-dismiss="modal">Alocar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_os_exclusao" role="dialog">
                <div class="modal-dialog" style="width:340px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Limpar semestre</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_os_exclusao" class="form-horizontal" autocomplete="off">
                                <input type="hidden" name="depto" value="">
                                <input type="hidden" name="diretoria" value="">
                                <input type="hidden" name="supervisor" value="">
                                <input type="hidden" name="ano" value="">
                                <input type="hidden" name="semestre" value="">
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="possui_mapa_visitacao" value="1" checked>
                                                Limpar Apontamentos + Mapa de Visitação
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="possui_mapa_visitacao" value="0"> Limpar
                                                somente Apontamentos
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnLimparOS" onclick="limpar_semestre()" class="btn btn-danger"
                                    data-dismiss="modal">Limpar
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
                                <input type="hidden" value="" name="periodo"/>
                                <div class="row form-group" style="margin-bottom: 0px;">
                                    <label class="control-label col-md-2"
                                           style="margin-top: -13px; font-weight: bolder;">Ordem de serviço:<br>Município:<br>Escola:<br>Data:</label>
                                    <div class="col-md-6" style="margin-top: -13px;">
                                        <label class="sr-only"></label>
                                        <p class="form-control-static">
                                            <span id="ordem_servico"></span><br>
                                            <span id="municipio"></span><br>
                                            <span id="escola"></span><br>
                                            <span id="data"></span>
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
                                            Salvar
                                        </button>
                                        <button type="button" id="btnApagar" onclick="apagar()" class="btn btn-danger">
                                            Excluir
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                        </button>
                                    </div>
                                </div>
                                <hr style="margin-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Tipo de evento</label>
                                    <div class="col col-md-8">
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="FA"> Falta
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="PV"> Posto vago
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="AT"> Atraso
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="SA"> Saída antecipada
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="FE"> Feriado
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="EM"> Emenda feriado
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="AF"> Aluno ausente
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="EU"> Evento Unidade
                                        </label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Funcionário(a) principal</label>
                                    <div class="col-md-6">
                                        <select name="id_usuario" class="form-control">
                                            <option value="">selecione...</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-2">Desconto/acréscimo</label>
                                    <div class="col-md-2" style="width: 14%;">
                                        <input name="desconto" class="form-control desconto text-center"
                                               type="text" value="" placeholder="hh:mm">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Funcionário(a) Subst. 1</label>
                                    <div class="col-md-6">
                                        <select name="id_alocado_sub1" class="form-control">
                                            <option value="">selecione...</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-2">Desconto/acréscimo</label>
                                    <div class="col-md-2" style="width: 14%;">
                                        <input name="desconto_sub1" class="form-control desconto text-center"
                                               type="text" value="" placeholder="hh:mm">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Funcionário(a) Subst. 2</label>
                                    <div class="col-md-6">
                                        <select name="id_alocado_sub2" class="form-control">
                                            <option value="">selecione...</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-2">Desconto/acréscimo</label>
                                    <div class="col-md-2" style="width: 14%;">
                                        <input name="desconto_sub2" class="form-control desconto text-center"
                                               type="text" value="" placeholder="hh:mm">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Ocorrências cuidador(a)</label>
                                    <div class="col-md-8">
                                        <textarea name="ocorrencia_cuidador" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Ocorrências aluno(s)</label>
                                    <div class="col-md-8">
                                        <textarea name="ocorrencia_aluno" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Ocorrências professor(es)</label>
                                    <div class="col-md-8">
                                        <textarea name="ocorrencia_professor" class="form-control" rows="2"></textarea>
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
                                                <input type="radio" name="status" value="FE" checked>
                                                Feriado
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" value="EM">
                                                Emenda de feriado
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

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_cuidador" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar funcionário(a) alocado(a)</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_cuidador" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Funcionário(a)</label>
                                    <div class="col-md-8">
                                        <label class="sr-only"></label>
                                        <p class="form-control-static">
                                            <span id="cuidador_antigo"></span>
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Cargo/função</label>
                                    <div class="col-sm-8">
                                        <select id="cuidador_funcao" class="form-control" onchange="filtrar_cuidador()">
                                            <option value="">Todos</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Município</label>
                                    <div class="col-sm-8">
                                        <select id="cuidador_municipio" class="form-control"
                                                onchange="filtrar_cuidador()">
                                            <option value="">Todos</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Mudar para <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="id_cuidador" class="form-control">
                                            <option value="">selecione...</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveCuidador" onclick="save_cuidador()"
                                    class="btn btn-success">Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_faturamento" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar desconto mensal</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_faturamento" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="mes"/>
                                <input type="hidden" value="" name="substituto"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-4">Desconto mensal</label>
                                    <div class="col-md-3">
                                        <input name="desconto" type="text" value="" class="form-control text-right">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveFaturamento" onclick="save_faturamento()"
                                    class="btn btn-success">Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_substituto" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Cadastro de colaborador substituto</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_substituto" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="mes"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-3"
                                           style="margin-top: -13px; font-weight: bolder;">Cuidador(a) original:<br>Município:<br>Escola:<br>Mês/ano:<br>Dia
                                        semana/horário:</label>
                                    <div class="col-md-6" style="margin-top: -13px;">
                                        <label class="sr-only"></label>
                                        <p class="form-control-static">
                                            <span id="nome_sub"></span><br>
                                            <span id="municipio_sub"></span><br>
                                            <span id="escola_sub"></span><br>
                                            <span id="mes_ano_sub"></span><br>
                                            <span id="horario_semana_sub"></span>
                                        </p>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" id="btnSaveSubstituto" onclick="save_substituto()"
                                                class="btn btn-success">Salvar
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                        </button>
                                    </div>
                                </div>
                                <fieldset>
                                    <legend>
                                        <small>Profissional substituto 1</small>
                                    </legend>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Filtrar município</label>
                                        <div class="col-md-4">
                                            <select id="municipio_sub1" class="form-control">
                                                <option value="">Todos</option>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-1">Tipo</label>
                                        <div class="col-md-4">
                                            <select name="funcao_sub1" class="form-control">
                                                <option value="">selecione...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Profissional</label>
                                        <div class="col-md-4">
                                            <select name="id_cuidador_sub1" class="form-control">
                                                <option value="">selecione...</option>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-2" style="width: 12%;">Data início</label>
                                        <div class="col-md-2">
                                            <input name="data_substituicao1" type="text" value=""
                                                   class="form-control text-center data" placeholder="dd/mm/aaaa">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" id="btnSaveSubstituto" onclick="limpar_substituto(1)"
                                                    class="btn btn-danger">Limpar substituto
                                            </button>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>
                                        <small>Profissional substituto 2</small>
                                    </legend>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Filtrar município</label>
                                        <div class="col-md-4">
                                            <select id="municipio_sub2" class="form-control">
                                                <option value="">Todos</option>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-1">Tipo</label>
                                        <div class="col-md-4">
                                            <select name="funcao_sub2" class="form-control">
                                                <option value="">selecione...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Profissional</label>
                                        <div class="col-md-4">
                                            <select name="id_cuidador_sub2" class="form-control">
                                                <option value="">selecione...</option>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-2" style="width: 12%;">Data início</label>
                                        <div class="col-md-2">
                                            <input name="data_substituicao2" type="text" value=""
                                                   class="form-control text-center data" placeholder="dd/mm/aaaa">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" id="btnSaveSubstituto" onclick="limpar_substituto(2)"
                                                    class="btn btn-danger">Limpar substituto
                                            </button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_totalizacao" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Planilha de Faturamento</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_totalizacao" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_alocacao"/>
                                <input type="hidden" value="" name="id_escola"/>
                                <input type="hidden" value="" name="cargo"/>
                                <input type="hidden" value="" name="funcao"/>
                                <input type="hidden" value="" name="mes"/>
                                <input type="hidden" value="" name="substituto"/>
                                <input type="hidden" value="" name="temp_id_alocado"/>
                                <input type="hidden" value="" name="temp_periodo"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Data de aprovação</label>
                                    <div class="col-md-2">
                                        <input name="data_aprovacao" type="text" value=""
                                               class="form-control text-center data">
                                    </div>
                                    <div class="col-md-8 text-right">
                                        <button type="button" id="btnSaveTotalizacao" onclick="save_totalizacao()"
                                                class="btn btn-success">Salvar
                                        </button>
                                        <button type="button" id="btnRecuperarTotalizacao"
                                                onclick="recuperar_totalizacao()"
                                                class="btn btn-info">Recuperar e validar base
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                        </button>
                                    </div>
                                </div>
                                <hr style="margin-top: 0px;">
                                <div id="planilha_faturamento" class="row">

                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_faturamento_consolidado" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Planilha de faturamento consolidado</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_faturamento_consolidado" class="form-horizontal">
                                <div class="row form-group">
                                    <label class="control-label col-md-1">Supervisor</label>
                                    <div class="col-md-5">
                                        <?php echo form_dropdown('id_supervisor', $supervisor, '', 'class="form-control input-sm" onchange="filtrar_faturamento_consolidado(this)"'); ?>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" id="btnSaveFaturamentoConsolidado"
                                                onclick="save_faturamento_consolidado()"
                                                class="btn btn-success">Salvar
                                        </button>
                                        <button type="button" id="btnRecuperarFaturamentoConsolidado"
                                                onclick="recuperar_faturamento_consolidado()"
                                                class="btn btn-info">Recuperar e validar base
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                        </button>
                                    </div>
                                </div>
                                <hr style="margin-top: 0px;">
                                <div id="planilha_faturamento_consolidado" class="row">

                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_data_real_totalizacao" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Cadastrar data</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_data_real_totalizacao" class="form-horizontal">
                                <input type="hidden" value="" name="id_alocado"/>
                                <input type="hidden" value="" name="periodo"/>
                                <input type="hidden" value="" name="fechamento"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-7" id="data_real_totalizacao">Data
                                        projetada</label>
                                    <div class="col-md-3">
                                        <input name="data_real_totalizacao" type="text"
                                               class="form-control text-center data">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveDataRealTotalizacao" onclick="save_data_real_totalizacao()"
                                    class="btn btn-success">Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_ajuste_mensal" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar ajuste mensal</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_ajuste_mensal" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="mes"/>
                                <input type="hidden" value="" name="substituto"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-5">Ajuste mensal</label>
                                    <div class="col-md-4">
                                        <input name="horas_descontadas" type="text" value=""
                                               class="form-control hora_descontada">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveAjusteMensal" onclick="save_ajuste_mensal()"
                                    class="btn btn-success">Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_pagamento_prestador" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Planilha de pagamento de Prestador de Serviços</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_pagamento_prestador" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="mes"/>
                                <input type="hidden" value="" name="id_horario"/>
                                <input type="hidden" value="" name="substituto"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">N&ordm; nota fiscal</label>
                                    <div class="col-md-2">
                                        <input name="numero_nota_fiscal" type="text" value=""
                                               class="form-control">
                                    </div>
                                    <div class="col-md-2 text-nowrap">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="pagamento_proporcional" value="1">
                                                Pagamento proporcional
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" id="btnSavePagamentoPrestador"
                                                onclick="save_pagamento_prestador()"
                                                class="btn btn-success">Salvar
                                        </button>
                                        <button type="button" id="btnRecuperarPagamentoPrestador"
                                                onclick="recuperar_pagamento_prestador()"
                                                class="btn btn-info">Recuperar e validar base
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                        </button>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Valor extra</label>
                                    <div class="col-md-2">
                                        <input name="valor_extra_1" type="text" value=""
                                               class="form-control text-right valor">
                                    </div>
                                    <label class="control-label col-md-1">Justificativa</label>
                                    <div class="col-md-5">
                                        <input name="justificativa_1" type="text" value=""
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Valor extra</label>
                                    <div class="col-md-2">
                                        <input name="valor_extra_2" type="text" value=""
                                               class="form-control text-right valor">
                                    </div>
                                    <label class="control-label col-md-1">Justificativa</label>
                                    <div class="col-md-5">
                                        <input name="justificativa_2" type="text" value=""
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Data de liberação de pagamento</label>
                                    <div class="col-md-2">
                                        <input name="data_liberacao_pagamento" type="text" value=""
                                               class="form-control text-center data">
                                    </div>
                                    <!--<div class="col-md-2">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="horas_mensais_custo" data-total_horas=""
                                                       data-valor_total_custo="" data-valor_total=""
                                                       onclick="calcular_horas_faturadas(this);"> Utilizar horas
                                                mensais
                                            </label>
                                        </div>
                                        Horas mensais: <span id="horas_mensais_custo"></span>
                                    </div>-->
                                    <label class="control-label col-md-1 text-nowrap">Data início</label>
                                    <div class="col-md-2">
                                        <input name="data_inicio_contrato" type="text" value=""
                                               class="form-control text-center data">
                                    </div>
                                    <label class="control-label col-md-1 text-nowrap">Data término</label>
                                    <div class="col-md-2">
                                        <input name="data_termino_contrato" type="text" value=""
                                               class="form-control text-center data">
                                    </div>
                                </div>
                                <hr style="margin-top: 0px;">
                                <div id="planilha_pagamento_prestador" class="row">

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
                                        style="padding: 4px; color: #000; background-color: #f0ad4e;">FA
                                    </td>
                                    <td style="padding-left: 8px;"> Falta</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; color: #000; background-color: #f0ad4e;">FA
                                    </td>
                                    <td style="padding-left: 8px;"> Posto vago</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; background-color: #ff0;">
                                        AT
                                    </td>
                                    <td style="padding-left: 8px;"> Atraso</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; background-color: #ff0;">
                                        SA
                                    </td>
                                    <td style="padding-left: 8px;"> Saída antecipada</td>
                                </tr>
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
                                        style="padding: 4px; box-shadow: inset 0 0 0 1px; -moz-box-shadow: 0 0 0 1px; -webkit-box-shadow: inset 0 0 0 1px;">
                                        AF
                                    </td>
                                    <td style="padding-left: 8px;"> Aluno ausente</td>
                                </tr>
                                <tr style="border: 2px solid #fff;">
                                    <td class="text-center"
                                        style="padding: 4px; box-shadow: inset 0 0 0 1px; -moz-box-shadow: 0 0 0 1px; -webkit-box-shadow: inset 0 0 0 1px;">
                                        EU
                                    </td>
                                    <td style="padding-left: 8px;"> Evento Unidade</td>
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

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_controle_materiais" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar evento de alunos</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_controle_materiais" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_matriculado"/>
                                <!--<input type="hidden" value="" name="escola"/>
                                <input type="hidden" value="" name="turno"/>
                                <input type="hidden" value="" name="supervisor"/>
                                <input type="hidden" value="" name="id_aluno"/>-->
                                <input type="hidden" value="" name="data"/>
                                <div class="row">
                                    <label class="control-label col-md-4" style="font-weight: bold; margin-top: -13px;">Aluno(a):<br>Escola:<br>Município:<br>Ordem
                                        de Serviço:<br>Data:</label>
                                    <div class="col-md-7" style="margin-top: -13px;">
                                        <label class="sr-only"></label>
                                        <p class="form-control-static">
                                            <span id="nome_aluno"></span><br>
                                            <span id="escola_aluno"></span><br>
                                            <span id="municipio_aluno"></span><br>
                                            <span id="os_aluno"></span><br>
                                            <span id="data_aluno"></span><br>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="" checked> Aluno presente
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
                            <button type="button" id="btnSaveControleMateriais" onclick="save_controle_materiais()"
                                    class="btn btn-success">Salvar
                            </button>
                            <button type="button" id="btnApagarControleMateriais" onclick="delete_controle_materiais()"
                                    class="btn btn-danger">Excluir
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_visitas" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Relatório de visitas</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_visitas" class="form-horizontal">
                                <input type="hidden" value="" name="id_mapa_unidade"/>
                                <input type="hidden" value="" id="visita_mes"/>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Lista de visitas</label>
                                    <div class="col-md-6">
                                        <select name="id" class="form-control" id="id_visita">
                                            <option value="">-- Nova visita --</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 text-right text-nowrap">
                                        <button type="button" class="btn btn-success" id="btnSaveVisitas"
                                                onclick="save_visita();">
                                            Salvar
                                        </button>
                                        <button type="button" class="btn btn-danger" id="btnLimparVisitas"
                                                onclick="limpar_visita();">
                                            Excluir
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                        </button>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Data visita <span class="text-danger">*</span></label>
                                    <div class="col-md-2">
                                        <input name="data_visita" type="text" value=""
                                               class="form-control text-center data" placeholder="dd/mm/aaaa">
                                    </div>
                                    <label class="control-label col-md-2">Data visita anterior</label>
                                    <div class="col-md-2">
                                        <input name="data_visita_anterior" type="text" value=""
                                               class="form-control text-center data" placeholder="dd/mm/aaaa">
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Supervisor visitante<span
                                                class="text-danger text-nowrap"> *</span></label>
                                    <div class="col-md-4">
                                        <?php echo form_dropdown('id_supervisor_visitante', $supervisorVisitante, $supervisor_atual, 'class="form-control"'); ?>
                                    </div>
                                    <label class="control-label col-md-1">Cliente<span class="text-danger text-nowrap"> *</span></label>
                                    <div class="col-md-4">
                                        <?php echo form_dropdown('cliente', ['' => 'selecione...'], '', 'onchange="atualizarFiltrosVisitas()" class="form-control"'); ?>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Município <span
                                                class="text-danger">*</span></label>
                                    <div class="col-md-4">
                                        <?php echo form_dropdown('municipio', ['' => 'selecione...'], '', 'onchange="atualizarFiltrosVisitas()" class="form-control"'); ?>
                                    </div>
                                    <label class="control-label col-md-1">Unidade visitada<span
                                                class="text-danger text-nowrap"> *</span></label>
                                    <div class="col-md-4">
                                        <?php echo form_dropdown('unidade_visitada', ['' => 'selecione...'], '', 'onchange="atualizarFiltrosVisitas()" class="form-control"'); ?>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Prestadores de serviços tratados <span
                                                class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input name="prestadores_servicos_tratados" type="text" value=""
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Coordenador responsável <span
                                                class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input name="coordenador_responsavel" type="text" value="" class="form-control">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Motivos da visita - Ítens tratados <span
                                                class="text-danger">*</span></label>
                                    <div class="col-md-4">
                                        <select name="motivo_visita" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">Visita de rotina</option>
                                            <option value="2">Visita programada</option>
                                            <option value="3">Solicitação da unidade</option>
                                            <option value="4">Solicitação de materiais</option>
                                            <option value="5">Processo seletivo</option>
                                            <option value="6">Ocorrência com aluno</option>
                                            <option value="7">Ocorrência com funcionário</option>
                                            <option value="8">Ocorrência na escola</option>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-3">Gastos com materiais pelo funcionário (R$)
                                        <span class="text-danger">*</span></label>
                                    <div class="col-md-2">
                                        <input type="text" name="gastos_materiais" class="form-control valor">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Sumário da visita (horário de início e término
                                        / atividades desenvolvidas)<span
                                                class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <textarea name="sumario_visita" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Observações gerais da visita <span
                                                class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <textarea name="observacoes" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </form>
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
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/extensions/dataTables.fixedColumns.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/extensions/dataTables.rowGroup.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

    <script>

        var table, table_faturamento, table_controle_materiais, table_visitas;
        var busca, save_method, demo1;
        var edicaoEvento = true;

        $('.tags').tagsInput({
            'width': 'auto',
            'defaultText': 'Telefone',
            'placeholderColor': '#999',
            'delimiter': '/'
        });
        $('.data').mask('00/00/0000');
        $('.hora').mask('00:00');
        $('.hora_descontada').mask('Z00:00', {
            'translation': {
                'Z': {
                    'pattern': /[-|]/,
                    'optional': true
                }
            }
        });
        $('.valor').mask('##.###.##0,00', {reverse: true});
        $('.desconto').mask('00:00', {
            'onChange': function (desconto, e, field, options) {
                var status = $('#form [name="status"]:checked').val();
                var mask = '00:00';
                if (status.indexOf(['FA', 'PV', 'AT', 'SA', 'AN', 'EU']) >= 0) {
                    var mask = '-00:00';
                }
                $('.desconto').mask(mask, options);
            }
        });

        $('.iniciar_os').on('change', function () {
            $('.iniciar_os').prop('checked', false);
            $(this).prop('checked', true);
            $('#form_os [name="ordem_servico"]').prop('disabled', this.value === '1');
            $('#form_os .bootstrap-duallistbox-container').find('*').prop('disabled', this.value === '1');
        });

        $('#form [name="status"]').on('change', function () {
            $('.desconto').prop('disabled', this.value === 'PV' || this.value === 'FE' || this.value === 'EM');
            corrigir_desconto(this);
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


        $(document).ready(function () {
            busca = $('#busca').serialize();
            var language = "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>";

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
                        return group;
                    },
                    'dataSrc': 1
                },
                'language': {
                    'url': language,
                    'searchPlaceholder': 'Município/escola/cuidador'
                },
                'ajax': {
                    'url': '<?php echo site_url('ei/apontamento/ajaxListEventos') ?>',
                    'type': 'POST',
                    'timeout': 90000,
                    'data': function (d) {
                        d.busca = busca;
                        return d;
                    },
                    'dataSrc': function (json) {
                        $('#mes_ano').html(json.calendar.mes_ano[0].toUpperCase() + json.calendar.mes_ano.slice(1));
                        // $('.total_funcionarios').text('Funcionário(a) [' + json.totalFuncionarios + ']');
                        // $('.total_alunos').text('Aluno(s) [' + json.totalAlunos + ']');

                        var dt1 = new Date();
                        var dt2 = new Date();
                        dt2.setFullYear(json.calendar.ano, (json.calendar.mes - 1));

                        var semana = 1;
                        var colunasUsuario = 3;
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
                        'visible': false,
                        'targets': [0, 1]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).css({
                                'cursor': 'pointer'
                            }).on('click', function () {
                                edit_cuidador(rowData[0]);
                            });
                        },
                        'width': '50%',
                        'targets': [2]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] === null) {
                                $(td).css('background-color', '#ff0');
                            }
                        },
                        'width': '50%',
                        'targets': [3]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            switch (rowData[col]['status']) {
                                case 'FA':
                                case 'PV':
                                    $(td).css({'color': '#000', 'background-color': '#f0ad4e'});
                                    break;
                                case 'AT':
                                case 'SA':
                                    $(td).css({'color': '#000', 'background-color': '#ff0'});
                                    break;
                                case 'FE':
                                case 'EM':
                                    $(td).css({'color': '#fff', 'background-color': '#337ab7'});
                                    break;
                                case 'AF':
                                case 'AF':
                                    $(td).css({'color': '#000', 'background-color': '#fff'});
                                    break;
                            }
                            $(td).popover({
                                'container': 'body',
                                'placement': 'auto bottom',
                                'trigger': 'hover',
                                'content': function () {
                                    if (rowData[col].length === 0) {
                                        return '<span style="color: #aaa;">Vazio</span>';
                                    } else {
                                        return '<strong>Status:</strong> ' + rowData[col]['tipo'] + '<br>' +
                                            '<strong>Desconto:</strong> ' + rowData[col]['desconto'] + ' h';
                                    }
                                },
                                'html': true
                            });
                            $(td).addClass('evento').css({
                                'cursor': 'pointer',
                                'vertical-align': 'middle'
                            }).on('click', function () {
                                $(td).popover('hide');
                                edit_evento(rowData[0], table.column(col).header().dataset.dia, rowData[35]);
                            });
                            $(td).html(rowData[col]['status']);
                        },
                        'className': 'text-center',
                        'orderable': false,
                        'searchable': false,
                        'targets': 'date-width'
                    }
                ],
                'rowsGroup': [1, 2, 3]
            });

            table_faturamento = $('#table_faturamento').DataTable({
                'dom': "<'row'<'col-sm-3'l><'col-sm-4'><'col-sm-5'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                'processing': true,
                'serverSide': true,
                'iDisplayLength': 500,
                'lengthMenu': [[5, 10, 25, 50, 100, 500, 1000], [5, 10, 25, 50, 100, 500, 1000]],
                'ordering': false,
                'orderFixed': [1, 'asc'],
                'rowGroup': {
                    'className': 'active',
                    'startRender': function (rows, group) {
                        return group;
                    },
                    'dataSrc': 0
                },
                'language': {
                    'url': language,
                    'searchPlaceholder': 'Município/escola/cuidador/aluno'
                },
                'ajax': {
                    'url': '<?php echo site_url('ei/apontamento/ajaxListFaturamento') ?>',
                    'type': 'POST',
                    'timeout': 90000,
                    'data': function (d) {
                        d.busca = busca;
                        return d;
                    },
                    'dataSrc': function (json) {
                        $('#btnFecharMes, #btnTotalizarMes, #btnSalvarMes, #btnRelatorioFaturamento, .btnFecharMes, .btnTotalizarMes').prop('disabled', true);
                        switch (true) {
                            case json.totalizacaoMes:
                                $('#btnSalvarMes, #btnRelatorioFaturamento').prop('disabled', false);
                            case json.fechamentoMes:
                                $('#btnTotalizarMes, .btnTotalizarMes').prop('disabled', false);
                            case json.data.length > 0:
                                $('#btnFecharMes, .btnFecharMes').prop('disabled', false);
                        }

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'visible': false,
                        'targets': [0]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] !== null) {
                                $(td).addClass('desconto_mes').css('cursor', 'pointer').on('click', function () {
                                    edit_faturamento(rowData[24], table_faturamento.context[0].json.mes, null); // id, desconto
                                });
                            }
                        },
                        'className': 'text-center',
                        'targets': [7]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] !== null) {
                                $(td).addClass('desconto_mes').css('cursor', 'pointer').on('click', function () {
                                    edit_faturamento(rowData[24], table_faturamento.context[0].json.mes, 1); // id, desconto sub 1
                                });
                            }
                        },
                        'className': 'text-center',
                        'targets': [13]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] !== null) {
                                $(td).addClass('desconto_mes').css('cursor', 'pointer').on('click', function () {
                                    edit_faturamento(rowData[24], table_faturamento.context[0].json.mes, 2); // id, desconto sub 2
                                });
                            }
                        },
                        'className': 'text-center',
                        'targets': [19]
                    },
                    {
                        'className': 'text-center',
                        'targets': [3, 6, 8, 12, 14, 18, 20]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if ((rowData[27] !== null && rowData[29] <= table_faturamento.context[0].json.mes) || (rowData[30] !== null && rowData[32] <= table_faturamento.context[0].json.mes)) {
                                $(td).css({'background-color': '#ff0', 'color': '#000'});
                                // $(td).html(rowData[61] < ((col + 4) / 8) ? rowData[60] : rowData[col]);
                            } else {
                                // $(td).html(rowData[col]);
                            }
                            $(td).css('cursor', 'pointer').on('click', function () {
                                edit_substituto(rowData[24], table_faturamento.context[0].json.mes);
                            });

                        },
                        'orderable': true,
                        'targets': [4]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] === null || rowData[col] === '') {
                                $(td).css({'background-color': '#ff0', 'color': '#000'});
                            }
                        },
                        'orderable': true,
                        'targets': [5]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).addClass('total_horas_mes');
                            if (rowData[col] !== null) {
                                if (rowData[26] !== null) {
                                    $(td).css({'background-color': '#5cb85c', 'color': '#fff'});
                                } else {
                                    $(td).css({'background-color': '#ff0', 'color': '#000'});
                                }
                                $(td).css('cursor', 'pointer').on('click', function () {
                                    edit_totalizacao(rowData[25], table_faturamento.context[0].json.mes, rowData[34], null);
                                });
                            }
                            $(td).html(rowData[col]);
                        },
                        'className': 'text-center total_horas',
                        'targets': [9]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).addClass('total_horas_mes');
                            if (rowData[col] !== null) {
                                if (rowData[26] !== null) {
                                    $(td).css({'background-color': '#5cb85c', 'color': '#fff'});
                                } else {
                                    $(td).css({'background-color': '#ff0', 'color': '#000'});
                                }
                                $(td).css('cursor', 'pointer').on('click', function () {
                                    edit_totalizacao(rowData[25], table_faturamento.context[0].json.mes, rowData[34], 1);
                                });
                            }
                            $(td).html(rowData[col]);
                        },
                        'className': 'text-center total_horas',
                        'targets': [15]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).addClass('total_horas_mes');
                            if (rowData[col] !== null) {
                                if (rowData[26] !== null) {
                                    $(td).css({'background-color': '#5cb85c', 'color': '#fff'});
                                } else {
                                    $(td).css({'background-color': '#ff0', 'color': '#000'});
                                }
                                $(td).css('cursor', 'pointer').on('click', function () {
                                    edit_totalizacao(rowData[25], table_faturamento.context[0].json.mes, rowData[34], 2);
                                });
                            }
                            $(td).html(rowData[col]);
                        },
                        'className': 'text-center total_horas',
                        'targets': [21]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).addClass('total_horas_mes');
                            if (rowData[col] !== null || rowData[26] !== null) {
                                if (rowData[col] !== null) {
                                    if (rowData[col].indexOf('-') === 0) {
                                        $(td).css({'background-color': '#f00', 'color': '#000'});
                                    } else if (rowData[col].length > 0) {
                                        $(td).css({'background-color': '#5cb85c', 'color': '#fff'});
                                    }
                                } else if (rowData[col] === null && rowData[26] !== null) {
                                    $(td).css({'background-color': '#ff0', 'color': '#000'});
                                }
                                $(td).css('cursor', 'pointer').on('click', function () {
                                    edit_ajuste_mensal(rowData[25], table_faturamento.context[0].json.mes, rowData[34], 0);
                                });
                            }
                            $(td).html(rowData[col] !== null ? rowData[col] : '00:00');
                        },
                        'className': 'text-center total_horas',
                        'targets': [10]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).addClass('total_horas_mes');
                            if (rowData[col] !== null || rowData[26] !== null) {
                                if (rowData[col] !== null) {
                                    if (rowData[col].indexOf('-') === 0) {
                                        $(td).css({'background-color': '#f00', 'color': '#000'});
                                    } else if (rowData[col].length > 0) {
                                        $(td).css({'background-color': '#5cb85c', 'color': '#fff'});
                                    }
                                } else if (rowData[col] === null && rowData[26] !== null) {
                                    $(td).css({'background-color': '#ff0', 'color': '#000'});
                                }
                                $(td).css('cursor', 'pointer').on('click', function () {
                                    edit_ajuste_mensal(rowData[25], table_faturamento.context[0].json.mes, rowData[34], 1);
                                });
                            }
                            $(td).html(rowData[col] !== null ? rowData[col] : '00:00');
                        },
                        'className': 'text-center total_horas',
                        'targets': [16]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).addClass('total_horas_mes');
                            if (rowData[col] !== null || rowData[26] !== null) {
                                if (rowData[col] !== null) {
                                    if (rowData[col].indexOf('-') === 0) {
                                        $(td).css({'background-color': '#f00', 'color': '#000'});
                                    } else if (rowData[col].length > 0) {
                                        $(td).css({'background-color': '#5cb85c', 'color': '#fff'});
                                    }
                                } else if (rowData[col] === null && rowData[26] !== null) {
                                    $(td).css({'background-color': '#ff0', 'color': '#000'});
                                }
                                $(td).css('cursor', 'pointer').on('click', function () {
                                    edit_ajuste_mensal(rowData[25], table_faturamento.context[0].json.mes, rowData[34], 2);
                                });
                            }
                            $(td).html(rowData[col] !== null ? rowData[col] : '00:00');
                        },
                        'className': 'text-center total_horas',
                        'targets': [22]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).addClass('total_horas_mes');
                            if (rowData[col] !== null || rowData[26] !== null || rowData[9] !== null || rowData[10] !== null) {
                                if (rowData[col] !== null) {
                                    $(td).css({'background-color': '#5cb85c', 'color': '#fff'});
                                    rowData[col] = 'Pago';
                                } else if (rowData[col] === null && (rowData[26] !== null || rowData[9] !== null || rowData[10] !== null)) {
                                    $(td).css({'background-color': '#ff0', 'color': '#000'});
                                    rowData[col] = 'Pagar';
                                }

                            } else if (!(rowData[9] !== null || rowData[10] !== null)) {
                                $(td).css({'background-color': '#ff0', 'color': '#000'});
                                rowData[col] = 'Pagar';
                            }
                            $(td).css('cursor', 'pointer').on('click', function () {
                                edit_pagamento_prestador(rowData[24], table_faturamento.context[0].json.mes, null);
                            });
                            $(td).html(rowData[col]);
                        },
                        'className': 'text-center total_horas',
                        'targets': [11]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).addClass('total_horas_mes');
                            if (rowData[col] !== null || rowData[26] !== null && rowData[27] || rowData[15] !== null || rowData[16] !== null) {
                                if (rowData[col] !== null) {
                                    $(td).css({'background-color': '#5cb85c', 'color': '#fff'});
                                    rowData[col] = 'Pago';
                                } else if (rowData[col] === null && (rowData[26] !== null || rowData[15] !== null || rowData[16] !== null)) {
                                    $(td).css({'background-color': '#ff0', 'color': '#000'});
                                    rowData[col] = 'Pagar';
                                }

                            } else if (rowData[15] !== null || rowData[16] !== null) {
                                $(td).css({'background-color': '#ff0', 'color': '#000'});
                                rowData[col] = 'Pagar';
                            }
                            $(td).css('cursor', 'pointer').on('click', function () {
                                edit_pagamento_prestador(rowData[24], table_faturamento.context[0].json.mes, 1);
                            });
                            $(td).html(rowData[col]);
                        },
                        'className': 'text-center total_horas',
                        'targets': [17]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).addClass('total_horas_mes');
                            if (rowData[col] !== null || rowData[26] !== null && rowData[30] || rowData[21] !== null || rowData[22] !== null) {
                                if (rowData[col] !== null) {
                                    $(td).css({'background-color': '#5cb85c', 'color': '#fff'});
                                    rowData[col] = 'Pago';
                                } else if (rowData[col] === null && (rowData[26] !== null || rowData[21] !== null || rowData[22] !== null)) {
                                    $(td).css({'background-color': '#ff0', 'color': '#000'});
                                    rowData[col] = 'Pagar';
                                }

                            } else if (rowData[21] !== null || rowData[22] !== null) {
                                $(td).css({'background-color': '#ff0', 'color': '#000'});
                                rowData[col] = 'Pagar';
                            }
                            $(td).css('cursor', 'pointer').on('click', function () {
                                edit_pagamento_prestador(rowData[24], table_faturamento.context[0].json.mes, 2);
                            });
                            $(td).html(rowData[col]);
                        },
                        'className': 'text-center total_horas',
                        'targets': [23]
                    },
                    {
                        'orderable': false,
                        'targets': [1]
                    },
                    {
                        'className': 'text-center text-nowrap',
                        'targets': [2]
                    }
                ], 'rowsGroup': [0, '.total_horas']
            });

            table_controle_materiais = $('#table_controle_materiais').DataTable({
                'processing': true,
                'serverSide': true,
                'iDisplayLength': 500,
                'lengthMenu': [[5, 10, 25, 50, 100, 500, 1000], [5, 10, 25, 50, 100, 500, 1000]],
                'orderFixed': [0, 'asc'],
                'rowGroup': {
                    'className': 'active',
                    'startRender': function (rows, group) {
                        return group;
                    },
                    'dataSrc': 0
                },
                'language': {
                    'url': language
                },
                'ajax': {
                    'url': '<?php echo site_url('ei/apontamento/ajaxListControleMateriais') ?>',
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
                                    table_controle_materiais.column(i + colunasUsuario).visible(false, false);
                                    continue;
                                } else {
                                    table_controle_materiais.column(i + colunasUsuario).visible(true, false);
                                }
                            }
                            var coluna = $(table_controle_materiais.columns(i + colunasUsuario).header());
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

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'visible': false,
                        'targets': [0]
                    },
                    {
                        'width': '100%',
                        'targets': [1]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (parseInt(rowData[col]['insumos']) > 0) {
                                $(td).css({'color': '#fff', 'background-color': '#5cb85c'});
                            }
                            $(td).popover({
                                'container': 'body',
                                'placement': 'auto bottom',
                                'trigger': 'hover',
                                'content': function () {
                                    if (rowData[col]['status'] === undefined) {
                                        return '<span style="color: #aaa;">Vazio</span>';
                                    } else {
                                        return '<strong>Status:</strong> ' + rowData[col]['tipo'] + '<br>' +
                                            '<strong>Total de insumos:</strong> ' + rowData[col]['insumos'];
                                    }
                                },
                                'html': true
                            });
                            $(td).addClass('evento').css({
                                'cursor': 'pointer',
                                'vertical-align': 'middle'
                            }).on('click', function () {
                                $(td).popover('hide');
                                edit_controle_materiais(rowData[33], col);
                            });
                            $(td).html(rowData[col]['status']);
                        },
                        'className': 'text-center',
                        'orderable': false,
                        'searchable': false,
                        'targets': 'date-width'
                    }
                ]
            });

            table_visitas = $('#table_visitas').DataTable({
                'processing': true,
                'serverSide': true,
                'iDisplayLength': 50,
                'language': {
                    'url': language
                },
                'rowGroup': {
                    'className': 'active',
                    'startRender': function (rows, group) {
                        return '<strong>Município: </strong>' + group;
                    },
                    'dataSrc': 0
                },
                'ajax': {
                    'url': '<?php echo site_url('ei/apontamento/ajaxListVisitas') ?>',
                    'type': 'POST',
                    'timeout': 90000,
                    'data': function (d) {
                        d.busca = busca;
                        return d;
                    },
                    'dataSrc': function (json) {
                        $('#table_visitas .nome_mes1').text(json.semestre[0]).attr('data-mes', json.meses[0]);
                        $('#table_visitas .nome_mes2').text(json.semestre[1]).attr('data-mes', json.meses[1]);
                        $('#table_visitas .nome_mes3').text(json.semestre[2]).attr('data-mes', json.meses[2]);
                        $('#table_visitas .nome_mes4').text(json.semestre[3]).attr('data-mes', json.meses[3]);
                        $('#table_visitas .nome_mes5').text(json.semestre[4]).attr('data-mes', json.meses[4]);
                        $('#table_visitas .nome_mes6').text(json.semestre[5]).attr('data-mes', json.meses[5]);
                        $('#table_visitas .nome_mes7').text(json.semestre[6]).attr('data-mes', json.meses[6]);

                        if (table_visitas.context[0].json.semestre.length === 5) {
                            table_visitas.column(8).visible(false);
                            table_visitas.column(9).visible(false);
                        } else {
                            table_visitas.column(8).visible(true);
                            table_visitas.column(9).visible(true);
                        }

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'visible': false,
                        'targets': [0]
                    },
                    {
                        'width': '40%',
                        'targets': [1]
                    },
                    {
                        'visible': false,
                        'targets': [2]
                    },
                    {
                        'width': '10%',
                        'targets': [3, 4, 5, 6, 7, 8, 9]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).css({
                                'cursor': 'pointer'
                            });
                            $(td).addClass('total_horas_mes');
                            if (rowData[col] !== null) {
                                if ((rowData[col + 7]) !== null && (rowData[col + 7]) !== '0') {
                                    $(td).css({
                                        'background-color': '#c9302c',
                                        'color': '#fff'
                                    }).html(moment(rowData[col + 14]).format('DD/MM/YYYY'));
                                } else if (rowData[col + 21] > 0) {
                                    $(td).css({
                                        'background-color': '#f0ad4e',
                                        'color': '#fff'
                                    }).html(moment(rowData[col + 14]).format('DD/MM/YYYY'));
                                } else {
                                    $(td).css({
                                        'background-color': '#5cb85c',
                                        'color': '#fff'
                                    }).html(moment(rowData[col + 14]).format('DD/MM/YYYY'));
                                }
                            }
                            $(td).on('click', function () {
                                gerenciar_visitas(rowData[2], table_visitas.column(col).header().dataset.mes);
                            });
                        },
                        'className': 'text-center',
                        'orderable': false,
                        'searchable': false,
                        'targets': [3, 4, 5, 6, 7, 8, 9]
                    }
                ]
            });

            $('input.toggle-vis').on('change', function (e) {
                ocultar_mes($(this).val());
            });

            $('input.toggle-sub').on('change', function (e) {
                ocultar_substitutos($(this).val());
            });

            atualizarSemestre();
        });

        function ocultar_mes(value) {
            var col = parseInt(value);
            for (var i = 0; i < 20; i++) {
                var column = table_faturamento.column(col + i);
                column.visible(!column.visible());
            }
        }


        function corrigir_desconto(elem) {
            var status = elem.value;
            var desconto = $('#form [name="desconto"]').val();
            var desconto_sub1 = $('#form [name="desconto_sub1"]').val();
            var desconto_sub2 = $('#form [name="desconto_sub2"]').val();
            $('#form [name="desconto"]').val('');
            $('#form [name="desconto_sub1"]').val('');
            $('#form [name="desconto_sub2"]').val('');

            if ((status === 'FA' || status === 'PV' || status === 'AT' || status === 'SA' || status === 'AN') && desconto.length > 0) {
                if (moment.duration(desconto, 'HH:mm').asSeconds() > 0) {
                    desconto = '-' + desconto;
                }
            } else {
                if (desconto.indexOf('-') === 0) {
                    desconto = desconto.replace('-', '');
                }
            }
            if ((status === 'FA' || status === 'PV' || status === 'AT' || status === 'SA' || status === 'AN') && desconto_sub1.length > 0) {
                if (moment.duration(desconto_sub1, 'HH:mm').asSeconds() > 0) {
                    desconto_sub1 = '-' + desconto_sub1;
                }
            } else {
                if (desconto_sub1.indexOf('-') === 0) {
                    desconto_sub1 = desconto_sub1.replace('-', '');
                }
            }
            if ((status === 'FA' || status === 'PV' || status === 'AT' || status === 'SA' || status === 'AN') && desconto_sub2.length > 0) {
                if (moment.duration(desconto_sub2, 'HH:mm').asSeconds() > 0) {
                    desconto_sub2 = '-' + desconto_sub2;
                }
            } else {
                if (desconto_sub2.indexOf('-') === 0) {
                    desconto_sub2 = desconto_sub2.replace('-', '');
                }
            }
            $('#form [name="desconto"]').val(desconto);
            $('#form [name="desconto_sub1"]').val(desconto_sub1);
            $('#form [name="desconto_sub2"]').val(desconto_sub2);
            $('#form [name="replicar_feriado"]').prop('disabled', status !== 'EM' && status !== 'FE');
        }


        function atualizarSemestre(semestre) {
            var mes = $('#busca [name="mes"]').val();
            mes = parseInt(mes);
            if (mes === 7) {
                $('#busca_semestre').show();
                if (semestre === 1) {
                    $('#semestre1').prop('checked', true);
                } else if (semestre === (-1)) {
                    $('#semestre2').prop('checked', true);
                }
            } else {
                if (mes < 7) {
                    $('#semestre1').prop('checked', true);
                } else {
                    $('#semestre2').prop('checked', true);
                }
                $('#busca_semestre').hide();
            }
        }


        function atualizarFiltro() {
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/atualizarFiltro/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize(),
                'beforeSend': function () {
                    $('#busca [name="depto"]').prop('disabled', true);
                    $('#busca [name="diretoria"]').prop('disabled', true);
                    $('#busca [name="supervisor"]').prop('disabled', true);
                },
                'success': function (json) {
                    $('#busca [name="depto"]').prop('disabled', false);
                    $('#busca [name="diretoria"]').replaceWith(json.diretoria).prop;
                    $('#busca [name="supervisor"]').replaceWith(json.supervisor);
                    $('#form_visitas [name="supervisor_visitante"]').replaceWith(json.supervisor_visitante);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#busca [name="depto"]').prop('disabled', false);
                    $('#busca [name="diretoria"]').prop('disabled', false);
                    $('#busca [name="supervisor"]').prop('disabled', false);
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


        $('#municipio_sub1').on('change', function () {
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/atualizarSubstituto/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'municipio': this.value,
                    'id_usuario': $('#form_substituto [name="id_cuidador_sub1"]').val()
                },
                'success': function (json) {
                    $('#form_substituto [name="id_cuidador_sub1"]').html($(json.usuario).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });

        $('#municipio_sub2').on('change', function () {
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/atualizarSubstituto/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'municipio': this.value,
                    'id_usuario': $('#form_substituto [name="id_cuidador_sub2"]').val()
                },
                'success': function (json) {
                    $('#form_substituto [name="id_cuidador_sub2"]').html($(json.usuario).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });


        $('#form_pagamento_prestador [name="data_inicio_contrato"], #form_pagamento_prestador [name="data_termino_contrato"]').on('change', function () {
            var mes = $('#busca [name="mes"]').val();
            var mes_contrato = moment(this.value, 'DD/MM/YYY').format('MM');
            if (mes === mes_contrato) {
                $(this).css({'background-color': '#ff0', 'color': '#000'});
            } else {
                $(this).css({'background-color': '#fff', 'color': '#000'});
            }
        });

        $('#form_controle_materiais [name="status"]').on('change', function () {
            if ($(this).val().length > 0) {
                $('.qtde_insumos').prop('readonly', true).val(0);
            } else {
                $('.qtde_insumos').prop('readonly', false);
            }
        });

        function calcular_horas_faturadas(elem) {
            if ($(elem).is(':checked')) {
                $('[name="horas_mensais_custo"]').data({
                    'total_horas': $('[name="total_horas_faturadas"]').val(),
                    'valor_total': $('[name="valor_total"]').val()
                })
                $('[name="total_horas_faturadas"]').val(elem.value);
                $('[name="valor_total"]').val($(elem).data('valor_total_custo'));
                $('#periodo tbody tr td:last').text($(elem).data('valor_total_custo'));
            } else {
                $('[name="total_horas_faturadas"]').val($(elem).data('total_horas'));
                $('[name="valor_total"]').val($(elem).data('valor_total'));
                $('#periodo tbody tr td:last').text($(elem).data('valor_total'));
            }
        }


        function mes_anterior() {
            $('#mes_anterior, #mes_seguinte').prop('disabled', true).hover();
            var dt = moment({
                'year': $('#busca [name="ano"]').val(),
                'month': $('#busca [name="mes"]').val() - 1,
                'day': 1
            });

            $('#busca [name="ano"]').val(dt.year());

            if ($('#busca [name="mes"]').val() === '07' && $('#semestre2').is(':checked')) {
                $('#semestre1').prop('checked', true);
                $('#busca [name="mes"]').val(moment(dt).format('MM'));
                atualizarSemestre();
            } else {
                dt.subtract(1, 'month');
                $('#busca [name="mes"]').val(moment(dt).format('MM'));
                atualizarSemestre(-1);
            }

            $('#alerta_semestre').html($('#busca [name="semestre"]:checked').val() + '&ordm;');
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

            $('#busca [name="ano"]').val(dt.year());

            if ($('#busca [name="mes"]').val() === '07' && $('#semestre1').is(':checked')) {
                $('#semestre2').prop('checked', true);
                $('#busca [name="mes"]').val(moment(dt).format('MM'));
                atualizarSemestre();
            } else {
                dt.add(1, 'month');
                $('#busca [name="mes"]').val(moment(dt).format('MM'));
                atualizarSemestre(1);
            }

            $('#alerta_semestre').html($('#busca [name="semestre"]:checked').val() + '&ordm;');
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

            atualizarSemestre();
            busca = $('#busca').serialize();
            reload_table();
            if (moment(data_proximo_mes).isBefore(data_busca)) {
                $('[name="mes"]').val(moment(data_proximo_mes).format('MM'));
                $('[name="ano"]').val(data_proximo_mes.year());
            }
            $('#alerta_depto').text($('#busca [name="depto"] option:selected').html());
            $('#alerta_diretoria').text($('#busca [name="diretoria"] option:selected').html());
            $('#alerta_supervisor').text($('#busca [name="supervisor"] option:selected').html());
            $('#alerta_semestre').html($('#busca [name="semestre"]:checked').val() + '&ordm;');
        }

        function habilitar_meses() {
            $('input.toggle-vis').prop({'disabled': false, 'checked': false});
        }


        function preparar_os() {
            if ($('#busca [name="depto"]').val() === '' || $('#busca [name="diretoria"]').val() === '' || $('#busca [name="supervisor"]').val() === '') {
                alert('Para iniciar o semestre, ajuste os filtros de Departamento, Cliente e Supervisor.');
                return false;
            }
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/prepararOS/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': busca,
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }
                    $('#form_os [name="depto"]').val($('#busca [name="depto"]').val());
                    $('#form_os [name="diretoria"]').val($('#busca [name="diretoria"]').val());
                    $('#form_os [name="supervisor"]').val($('#busca [name="supervisor"]').val());
                    $('#form_os [name="ano"]').val($('#busca [name="ano"]').val());
                    $('#form_os [name="semestre"]').val($('#busca [name="semestre"]:checked').val());
                    $('#form_os [name="mes"]').val($('#busca [name="mes"]').val());

                    $('#form_os [name="ordem_servico"]').html($(json.ordem_servico).html());
                    $('#os_escolas').html($(json.escolas).html());
                    demo1.bootstrapDualListbox('refresh', true);

                    $('#form_os [name="possui_mapa_visitacao"][value="1"]').prop('checked', true);
                    $('#form_os .iniciar_os[value="1"]').prop('checked', true).trigger('change');
                    $('#modal_os').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function filtrar_os_escolas() {
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/filtrarOSEscolas/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_os').serialize(),
                'success': function (json) {
                    $('#os_escolas').html($(json.escolas).html());
                    demo1.bootstrapDualListbox('refresh', true);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function iniciar_semestre() {
            $('#btnSaveOS').text('Alocando...').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/iniciarSemestre/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_os').serialize(),
                'success': function (json) {
                    if (json.erro !== undefined) {
                        alert(json.erro);
                    } else {
                        $('#modal_os').modal('hide');
                        reload_table();
                    }
                    $('#btnSaveOS').text('Alocar').attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveOS').text('Alocar').attr('disabled', false);
                }
            });
        }


        function preparar_os_individual() {
            if ($('#busca [name="depto"]').val() === '' || $('#busca [name="diretoria"]').val() === '' || $('#busca [name="supervisor"]').val() === '') {
                alert('Para alocar OS individual, ajuste os filtros de Departamento, Cliente e Supervisor.');
                return false;
            }
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/prepararOSIndividual/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': busca,
                'success': function (json) {
                    $('#form_os_individual [name="depto"]').val(json.depto);
                    $('#form_os_individual [name="diretoria"]').val(json.diretoria);
                    $('#form_os_individual [name="supervisor"]').val(json.supervisor);
                    $('#form_os_individual [name="ano"]').val(json.ano);
                    $('#form_os_individual [name="semestre"]').val(json.semestre);

                    $('#form_os_individual [name="ordem_servico"]').html($(json.ordem_servico).html());
                    $('#modal_os_individual').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function preparar_exclusao_os() {
            if ($('#busca [name="depto"]').val() === '' || $('#busca [name="diretoria"]').val() === '' || $('#busca [name="supervisor"]').val() === '') {
                alert('Para limpar os semestre, ajuste os filtros de Departamento, Cliente e Supervisor.');
                return false;
            }
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/prepararOSIndividual/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': busca,
                'success': function (json) {
                    $('#form_os_exclusao [name="depto"]').val(json.depto);
                    $('#form_os_exclusao [name="diretoria"]').val(json.diretoria);
                    $('#form_os_exclusao [name="supervisor"]').val(json.supervisor);
                    $('#form_os_exclusao [name="ano"]').val(json.ano);
                    $('#form_os_exclusao [name="semestre"]').val(json.semestre);

                    $('#form_os_exclusao [name="possui_mapa_visitacao"][value="1"]').prop('checked', true);
                    $('#modal_os_exclusao').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function limpar_semestre() {
            if ($('#busca [name="depto"]').val() === '' || $('#busca [name="diretoria"]').val() === '' || $('#busca [name="supervisor"]').val() === '') {
                alert('Para limpar o semestre, ajuste os filtros de Departamento, Cliente e Supervisor.');
                return false;
            }
            if (confirm('Deseja limpar o semestre do mês corrente?')) {
                $('#btnLimparOS').text('Limpando...').attr('disabled', true);
                $.ajax({
                    'url': '<?= site_url('ei/apontamento/limparSemestre/') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': $('#form_os_exclusao').serialize(),
                    'success': function (json) {
                        if (json.erro !== undefined) {
                            alert(json.erro);
                        } else {
                            $('#modal_os_exclusao').modal('hide');
                            reload_table();
                        }
                        $('#btnLimparOS').text('Limpar').attr('disabled', false);
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                        $('#btnLimparOS').text('Limpar').attr('disabled', false);
                    }
                });
            }
        }


        function edit_cuidador(id) {
            $('#form_cuidador')[0].reset();
            $.ajax({
                'url': '<?= site_url('ei/apontamento/ajaxEditCuidador/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_alocado': id
                },
                'success': function (json) {
                    $('#form_cuidador [name="id"]').val(id);
                    $('#cuidador_antigo').html(json.cuidador_antigo);
                    $('#cuidador_funcao').html($(json.cargo_funcao).html());
                    $('#cuidador_municipio').html($(json.municipio).html());
                    $('#form_cuidador [name="id_cuidador"]').html($(json.id_cuidador).html());

                    $('#modal_cuidador').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function filtrar_cuidador() {
            $.ajax({
                'url': '<?= site_url('ei/apontamento/ajaxFiltrarCuidador/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id': $('#form_cuidador [name="id"]').val(),
                    'cargo_funcao': $('#cuidador_funcao').val(),
                    'municipio': $('#cuidador_municipio').val()
                },
                'success': function (json) {
                    $('#form_cuidador [name="id_cuidador"]').html($(json.id_cuidador).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_evento(id, date, periodo) {
            $('#form')[0].reset();
            $('#form [name="id"], #form [name="id_aloacao"]').val('');
            $.ajax({
                'url': '<?= site_url('ei/apontamento/ajaxEdit/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_alocado': id,
                    'data': date,
                    'periodo': periodo
                },
                'success': function (json) {
                    $('#ordem_servico').html(json.ordem_servico);
                    $('#municipio').html(json.municipio);
                    $('#escola').html(json.escola);
                    $('#data').html(json.data);
                    $('#form [name="id_usuario"]').html($(json.id_usuarios).html());
                    $('#form [name="id_alocado_sub1"]').html($(json.id_alocado_sub1).html());
                    $('#form [name="id_alocado_sub2"]').html($(json.id_alocado_sub2).html());
                    if (json.id) {
                        $('#form [name="id"]').val(json.id);
                        $('#btnApagar').show();
                        $('#modal_form .modal-title').text('Editar evento de apontamento');
                    } else {
                        $('#btnApagar').hide();
                        $('#modal_form .modal-title').text('Adicionar evento de apontamento');
                    }

                    $('#form [name="id_alocado"]').val(json.id_alocado);
                    $('#form [name="data"]').val(date);
                    $('#form [name="periodo"]').val(periodo);
                    $('#form [name="desconto"]').val(json.desconto);
                    $('#form [name="desconto_sub1"]').val(json.desconto_sub1);
                    $('#form [name="desconto_sub2"]').val(json.desconto_sub2);
                    $('#form [name="status"][value="' + json.status + '"]').prop('checked', true).trigger('change');
                    $('#form [name="replicar_feriado"]').prop('disabled', json.status !== 'FE' && json.status !== 'EM');
                    $('#form [name="ocorrencia_cuidador"]').val(json.ocorrencia_cuidador);
                    $('#form [name="ocorrencia_aluno"]').val(json.ocorrencia_aluno);

                    $('#modal_form').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_faturamento(id_faturamento, mes, substituto) {
            $('#form_faturamento')[0].reset();
            $('#form_faturamento [name="id"], #form [name="id_aloacao"]').val('');
            $.ajax({
                'url': '<?= site_url('ei/apontamento/ajaxEditFaturamento/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_faturamento': id_faturamento,
                    'mes': mes,
                    'substituto': substituto
                },
                'success': function (json) {
                    $('#form_faturamento [name="id"]').val(json.id);
                    $('#form_faturamento [name="mes"]').val(json.mes);
                    $('#form_faturamento [name="substituto"]').val(substituto);
                    $('#form_faturamento [name="desconto"]').val(json.desconto);

                    $('#modal_faturamento').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_data_real_totalizacao(id_alocado, periodo, fechamento) {
            $('#form_data_real_totalizacao')[0].reset();
            $.ajax({
                'url': '<?= site_url('ei/apontamento/ajaxEditDataRealTotalizacao/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_alocado': id_alocado,
                    'periodo': periodo
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        $('#form_data_real_totalizacao [name="id_alocado"]').val(id_alocado);
                        $('#form_data_real_totalizacao [name="periodo"]').val(periodo);
                        $('#form_data_real_totalizacao [name="fechamento"]').val(fechamento);

                        if (fechamento) {
                            $('#modal_data_real_totalizacao .modal-title').text('Editar data de término do semestre');
                            $('#data_real_totalizacao').html('Data de término real do semestre');
                            $('#form_data_real_totalizacao [name="data_real_totalizacao"]').val(json.data_termino_real);
                        } else {
                            $('#modal_data_real_totalizacao .modal-title').text('Editar data de início do semestre');
                            $('#data_real_totalizacao').html('Data de início real do semestre');
                            $('#form_data_real_totalizacao [name="data_real_totalizacao"]').val(json.data_inicio_real);
                        }

                        $('#modal_data_real_totalizacao').modal('show');
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_substituto(id_horario, mes) {
            $('#form_substituto')[0].reset();
            $('#form_substituto [name="id"], #form [name="id_aloacao"]').val('');
            $.ajax({
                'url': '<?= site_url('ei/apontamento/ajaxEditSubstituto/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_horario': id_horario,
                    'mes': mes
                },
                'success': function (json) {
                    $('#nome_sub').html(json.cuidador);
                    $('#municipio_sub').html(json.municipio);
                    $('#escola_sub').html(json.escola);
                    $('#mes_ano_sub').html(json.mes_ano);
                    $('#horario_semana_sub').html(json.horario_semana);

                    $('#form_substituto [name="id"]').val(json.id);
                    $('#form_substituto [name="mes"]').val(mes);
                    $('#municipio_sub1').html($(json.municipio_sub1).html());
                    $('#form_substituto [name="id_cuidador_sub1"]').html($(json.id_cuidador_sub1).html());
                    $('#form_substituto [name="funcao_sub1"]').html($(json.funcao_sub1).html());
                    $('#form_substituto [name="data_substituicao1"]').val(json.data_substituicao1);

                    $('#municipio_sub2').html($(json.municipio_sub1).html());
                    $('#form_substituto [name="id_cuidador_sub2"]').html($(json.id_cuidador_sub2).html());
                    $('#form_substituto [name="funcao_sub2"]').html($(json.funcao_sub2).html());
                    $('#form_substituto [name="data_substituicao2"]').val(json.data_substituicao2);

                    $('#modal_substituto').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_totalizacao(id_alocado, mes, periodo, substituto) {
            $('#form_totalizacao')[0].reset();
            $('#form_totalizacao [name="id"], #form [name="id_aloacao"]').val('');
            $.ajax({
                'url': '<?= site_url('ei/apontamento/ajaxEditTotalizacao/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_alocado': id_alocado,
                    'mes': mes,
                    'periodo': periodo,
                    'substituto': substituto
                },
                'success': function (json) {
                    $('#form_totalizacao').show();
                    $('#nome_cuidador').html(json.cuidador);
                    $('#nome_escola').html(json.escola);
                    $('#nome_ordem_servico').html(json.ordem_servico);
                    $('#nomes_alunos').html(json.alunos);
                    $('#horarios').html(json.horarios);
                    $('#totalizacao_tabela').html(json.totalizacao);
                    $('#assinatura_aupervisor').html(json.supervisor);

                    $('#form_totalizacao [name="id"]').val(json.id);
                    $('#form_totalizacao [name="temp_id_alocado"]').val(id_alocado);
                    $('#form_totalizacao [name="temp_periodo"]').val(periodo);
                    $('#form_totalizacao [name="id_alocacao"]').val(json.id_alocacao);
                    $('#form_totalizacao [name="id_escola"]').val(json.id_escola);
                    $('#form_totalizacao [name="cargo"]').val(json.cargo);
                    $('#form_totalizacao [name="funcao"]').val(json.funcao);
                    $('#form_totalizacao [name="mes"]').val(json.mes);
                    $('#form_totalizacao [name="substituto"]').val(substituto);
                    $('#form_totalizacao [name="data_aprovacao"]').val(json.data_aprovacao);
                    $('#form_totalizacao [name="data_impressao"]').val(json.data_impressao);

                    $('#planilha_faturamento').html(json.planilha_faturamento);

                    $('#modal_totalizacao').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function planilha_faturamento_consolidado() {
            $.ajax({
                'url': '<?= site_url('ei/apontamento/faturamentoConsolidado/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize(),
                'success': function (json) {
                    $('#form_faturamento_consolidado [name="id_supervisor"]').val('');
                    $('#planilha_faturamento_consolidado').html(json.planilha_faturamento_consolidado);

                    $('#modal_faturamento_consolidado').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_ajuste_mensal(id_alocado, mes, periodo, substituto) {
            $('#form_ajuste_mensal')[0].reset();
            $('#form_ajuste_mensal [name="id"], #form_ajuste_mensal [name="mes"]').val('');
            $.ajax({
                'url': '<?= site_url('ei/apontamento/ajaxEditAjusteMensal/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_alocado': id_alocado,
                    'mes': mes,
                    'periodo': periodo,
                    'substituto': substituto,
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }
                    $('#form_ajuste_mensal [name="id"]').val(json.id);
                    $('#form_ajuste_mensal [name="mes"]').val(json.mes);
                    $('#form_ajuste_mensal [name="substituto"]').val(substituto);
                    $('#form_ajuste_mensal [name="horas_descontadas"]').val(json.horas_descontadas);

                    $('#modal_ajuste_mensal').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_pagamento_prestador(id_horario, mes, substituto) {
            $('#form_pagamento_prestador')[0].reset();
            $('#form_pagamento_prestador [name="id"], #form [name="id_aloacao"]').val('');
            $.ajax({
                'url': '<?= site_url('ei/apontamento/ajaxEditPagamentoPrestador/') ?>',
                'type': 'POST',
                'dataType': 'JSON',
                'data': {
                    'id_horario': id_horario,
                    'mes': mes,
                    'substituto': substituto
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $('#form_pagamento_prestador [name="id"]').val(json.id);
                    $('#form_pagamento_prestador [name="mes"]').val(mes);
                    $('#form_pagamento_prestador [name="id_horario"]').val(id_horario);
                    $('#form_pagamento_prestador [name="substituto"]').val(substituto);
                    $('#form_pagamento_prestador [name="numero_nota_fiscal"]').val(json.numero_nota_fiscal);
                    $('#form_pagamento_prestador [name="pagamento_proporcional"]').prop('checked', json.pagamento_proporcional > 0);
                    $('#form_pagamento_prestador [name="data_liberacao_pagamento"]').val(json.data_liberacao_pagto);
                    $('#form_pagamento_prestador [name="valor_extra_1"]').val(json.valor_extra_1);
                    $('#form_pagamento_prestador [name="valor_extra_2"]').val(json.valor_extra_2);
                    $('#form_pagamento_prestador [name="justificativa_1"]').val(json.justificativa_1);
                    $('#form_pagamento_prestador [name="justificativa_2"]').val(json.justificativa_2);
                    // $('#form_pagamento_prestador [name="horas_mensais_custo"]').val(json.horas_mensais_custo);
                    $('#form_pagamento_prestador [name="data_inicio_contrato"]').val(json.data_inicio_contrato).trigger('change');
                    $('#form_pagamento_prestador [name="data_termino_contrato"]').val(json.data_termino_contrato).trigger('change');
                    // $('#horas_mensais_custo').text(json.horas_mensais_completa);
                    // $('#form_pagamento_prestador [name="horas_mensais_custo"]').data('valor_total_custo', json.valor_total);

                    $('#planilha_pagamento_prestador').html(json.planilha_pagamento_prestador);

                    $('#modal_pagamento_prestador').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_controle_materiais(id_matriculado, col) {
            var date = table_controle_materiais.column(col).header().dataset.dia
            if (id_matriculado === undefined) {
                id_matriculado = '';
            }
            $('#form_controle_materiais')[0].reset();

            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxEditControleMateriais/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_matriculado': id_matriculado,
                    'date': date
                },
                'success': function (json) {
                    $('#municipio_aluno').html(json.municipio);
                    $('#escola_aluno').html(json.escola);
                    $('#os_aluno').html(json.ordem_servico);
                    $('#nome_aluno').html(json.aluno);
                    $('#data_aluno').html(table_controle_materiais.column(col).header().title);

                    $('#insumos').html(json.qtde_insumos);
                    $('#form_controle_materiais [name="id"]').val(json.id_frequencia);
                    $('#form_controle_materiais [name="id_matriculado"]').val(id_matriculado);
                    $('#form_controle_materiais [name="data"]').val(date);
                    $('#form_controle_materiais [name="status"][value="' + json.status + '"]').prop('checked', json.status !== null).trigger('change');
                    $('#btnApagarControleMateriais').attr('disabled', id_matriculado.length === 0);
                    if (json.id_frequencia) {
                        $('#form_controle_materiais [name="id"]').val(json.id_frequencia);
                        $('#btnApagarControleMateriais').show();
                        $('#modal_controle_materiais .modal-title').text('Editar evento de aluno(a)');
                    } else {
                        $('#btnApagarControleMateriais').hide();
                        $('#modal_controle_materiais .modal-title').text('Adicionar evento de aluno(a)');
                    }
                    $('#modal_controle_materiais').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function gerenciar_visitas(id_mapa_unidade, id_mes) {
            $('#form_visitas')[0].reset();
            $('#form_visitas input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxVisitas/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'async': false,
                'data': {
                    'id_mapa_unidade': id_mapa_unidade,
                    'id_mes': id_mes
                },
                'success': function (json) {
                    $('#form_visitas [name="id"]').html($(json.id).html());
                    $('#visita_mes').val(id_mes);
                    $('#form_visitas [name="id_mapa_unidade"]').val(json.id_mapa_unidade);
                    $('#form_visitas [name="data_visita"]').val(json.data_visita);
                    $('#form_visitas [name="data_visita_anterior"]').val(json.data_visita_anterior);
                    $('#form_visitas [name="id_supervisor_visitante"]').html($(json.supervisor_visitante).html());
                    $('#form_visitas [name="cliente"]').html($(json.cliente).html());
                    $('#form_visitas [name="municipio"]').html($(json.municipio).html());
                    $('#form_visitas [name="unidade_visitada"]').html($(json.unidade_visitada).html());
                    $('#form_visitas [name="prestadores_servicos_tratados"]').val(json.prestadores_servicos_tratados);
                    $('#form_visitas [name="coordenador_responsavel"]').val(json.coordenador_responsavel);
                    $('#form_visitas [name="motivo_visita"]').val(json.motivo_visita);
                    $('#form_visitas [name="gastos_materiais"]').val(json.gastos_materiais);
                    $('#form_visitas [name="sumario_visita"]').val(json.sumario_visita);
                    $('#form_visitas [name="observacoes"]').val(json.observacoes);

                    if (json.id_selecionado) {
                        save_method = 'update';
                        $('#modal_visitas .modal-title').text('Editar relatório de visita');
                        $('#btnLimparVisitas').show();
                    } else {
                        save_method = 'add';
                        $('#modal_visitas .modal-title').text('Adicionar relatório de visita');
                        $('#btnLimparVisitas').hide();
                    }
                    $('#modal_visitas').modal('show');
                    $('.combo_nivel1').hide();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        $('#id_visita').on('change', function () {
            edit_visita(this.value);
        });

        function edit_visita(id) {
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxEditVisita/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'async': false,
                'data': {
                    'id': id,
                    'id_mes': $('#visita_mes').val(),
                    'id_mapa_unidade': $('#form_visitas [name="id_mapa_unidade"]').val()
                },
                'success': function (json) {
                    $('#form_visitas [name="data_visita"]').val(json.data_visita);
                    $('#form_visitas [name="data_visita_anterior"]').val(json.data_visita_anterior);
                    $('#form_visitas [name="id_supervisor_visitante"]').html($(json.supervisor_visitante).html());
                    $('#form_visitas [name="cliente"]').html($(json.cliente).html());
                    $('#form_visitas [name="municipio"]').html($(json.municipio).html());
                    $('#form_visitas [name="unidade_visitada"]').html($(json.unidade_visitada).html());
                    $('#form_visitas [name="prestadores_servicos_tratados"]').val(json.prestadores_servicos_tratados);
                    $('#form_visitas [name="coordenador_responsavel"]').val(json.coordenador_responsavel);
                    $('#form_visitas [name="motivo_visita"]').val(json.motivo_visita);
                    $('#form_visitas [name="gastos_materiais"]').val(json.gastos_materiais);
                    $('#form_visitas [name="sumario_visita"]').val(json.sumario_visita);
                    $('#form_visitas [name="observacoes"]').val(json.observacoes);

                    if (json.id) {
                        save_method = 'update';
                        $('#modal_visitas .modal-title').text('Editar relatório de visita');
                        $('#btnLimparVisitas').show();
                    } else {
                        save_method = 'add';
                        $('#modal_visitas .modal-title').text('Adicionar relatório de visita');
                        $('#btnLimparVisitas').hide();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function atualizarFiltrosVisitas() {
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/atualizarFiltrosVisitas/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id': $('#form_visitas [name="id"]').val(),
                    'cliente': $('#form_visitas [name="cliente"]').val(),
                    'municipio': $('#form_visitas [name="municipio"]').val(),
                    'unidade_visitada': $('#form_visitas [name="unidade_visitada"]').val(),
                    'mes': $('#busca [name="mes"]').val(),
                    'ano': $('#busca [name="ano"]').val()
                },
                'success': function (json) {
                    $('#form_visitas [name="cliente"]').html($(json.cliente).html());
                    $('#form_visitas [name="municipio"]').html($(json.municipio).html());
                    $('#form_visitas [name="unidade_visitada"]').html($(json.unidade_visitada).html());
                    $('#form_visitas [name="prestadores_servicos_tratados"]').val(json.prestadores_servicos_tratados);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function salvar_os_individual() {
            $('#btnSaveOSIndividual').text('Salvando...').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/adicionarOSIndividual') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_os_individual').serialize(),
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    }
                    if (json.status) {
                        $('#modal_os_individual').modal('hide');
                        reload_table();
                    }
                    $('#btnSaveOSIndividual').text('Salvar').attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveOSIndividual').text('Salvar').attr('disabled', false);
                }
            });
        }

        function save() {
            $('#btnSave').text('Salvando...');
            $('#btnSave, #btnApagar').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxSave') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form').serialize(),
                'success': function (json) {
                    $('#modal_form').modal('hide');
                    $('#btnSave').text('Salvar');
                    $('#btnSave, #btnApagar').attr('disabled', false);
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSave').text('Salvar');
                    $('#btnSave, #btnApagar').attr('disabled', false);
                }
            });
        }

        function save_eventos() {
            $('#btnSaveEventos').text('Replicando...');
            $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxSaveEventos') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'busca': busca,
                    'eventos': $('#form_eventos').serialize()
                },
                'success': function (json) {
                    $('#modal_eventos').modal('hide');
                    $('#btnSaveEventos').text('Replicar');
                    $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', false);
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveEventos').text('Replicar');
                    $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', false);
                }
            });
        }

        function save_cuidador() {
            $('#btnSaveCuidador').text('Salvando...').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxSaveCuidador') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_cuidador').serialize(),
                'success': function (json) {
                    $('#modal_cuidador').modal('hide');
                    $('#btnSaveCuidador').text('Salvar').attr('disabled', false);
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveCuidador').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_eventos() {
            if (confirm('Deseja limpar os eventos desta data?')) {
                $('#btnDeleteEventos').text('Limpando...');
                $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', true);
                $.ajax({
                    'url': '<?php echo site_url('ei/apontamento/ajaxDeleteEventos') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {
                        'busca': busca,
                        'eventos': $('#form_eventos').serialize()
                    },
                    'success': function (json) {
                        $('#modal_eventos').modal('hide');
                        $('#btnDeleteEventos').text('Limpar');
                        $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', false);
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                        $('#btnDeleteEventos').text('Limpar');
                        $('#btnSaveEventos, #btnDeleteEventos').attr('disabled', false);
                    }
                });
            }
        }

        function save_faturamento() {
            $('#btnSaveFaturamento').text('Salvando...').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxSaveFaturamento') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_faturamento').serialize(),
                'success': function (json) {
                    $('#modal_faturamento').modal('hide');
                    $('#btnSaveFaturamento').text('Salvar').attr('disabled', false);
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveFaturamento').text('Salvar').attr('disabled', false);
                }
            });
        }

        function save_data_real_totalizacao() {
            $('#btnSaveDataRealTotalizacao').text('Salvando...').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxSaveDataRealTotalizacao') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_data_real_totalizacao').serialize(),
                'success': function (json) {
                    $('#btnSaveDataRealTotalizacao').text('Salvar').attr('disabled', false);
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        $('#modal_data_real_totalizacao').modal('hide');

                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveDataRealTotalizacao').text('Salvar').attr('disabled', false);
                }
            });
        }


        function save_faturamento_consolidado() {
            $('#btnSaveFaturamentoConsolidado').text('Salvando...').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxSaveFaturamentoConsolidado') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca, #form_faturamento_consolidado').serialize(),
                'success': function (json) {
                    $('#modal_faturamento_consolidado').modal('hide');
                    $('#btnSaveFaturamentoConsolidado').text('Salvar').attr('disabled', false);
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveFaturamentoConsolidado').text('Salvar').attr('disabled', false);
                }
            });
        }


        function filtrar_faturamento_consolidado(elem) {
            $(elem).attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/faturamentoConsolidado') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize() + '&supervisor_filtrado=' + elem.value,
                'success': function (json) {
                    $('#planilha_faturamento_consolidado').html(json.planilha_faturamento_consolidado);
                    $(elem).attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $(elem).attr('disabled', false);
                }
            });
        }


        function recuperar_faturamento_consolidado() {
            $('#btnRecuperarFaturamentoConsolidado').text('Recuperando e validando base...').attr('disabled', true);
            var id_supervisor = $('#form_faturamento_consolidado [name="id_supervisor"]').val();
            $('#form_faturamento_consolidado [name="id_supervisor"]').attr('disabled', true);

            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/recuperarFaturamentoConsolidado') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize() + '&supervisor_filtrado=' + id_supervisor,
                'success': function (json) {
                    $('#planilha_faturamento_consolidado').html(json.planilha_faturamento_consolidado);
                    $('#btnRecuperarFaturamentoConsolidado').text('Recuperar e validar base').attr('disabled', false);
                    $('#form_faturamento_consolidado [name="id_supervisor"]').attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnRecuperarFaturamentoConsolidado').text('Recuperar e validar base').attr('disabled', false);
                    $('#form_faturamento_consolidado [name="id_supervisor"]').attr('disabled', false);
                }
            });
        }

        function save_substituto() {
            $('#btnSaveSubstituto').text('Salvando...').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxSaveSubstituto') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_substituto').serialize(),
                'success': function (json) {
                    $('#modal_substituto').modal('hide');
                    $('#btnSaveSubstituto').text('Salvar').attr('disabled', false);
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveSubstituto').text('Salvar').attr('disabled', false);
                }
            });
        }

        function limpar_substituto(elem) {
            if (elem !== 1 && elem !== 2) {
                return false;
            }
            $('#form_substituto [name="id_cuidador_sub' + elem + '"]').val('');
            $('#form_substituto [name="funcao_sub' + elem + '"]').val('');
            $('#form_substituto [name="data_substituicao' + elem + '"]').val('');
            save_substituto();
        }

        function save_totalizacao() {
            $('#btnSaveTotalizacao').text('Salvando...').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxSaveTotalizacao') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_totalizacao').serialize(),
                'success': function (json) {
                    $('#modal_totalizacao').modal('hide');
                    $('#btnSaveTotalizacao').text('Salvar').attr('disabled', false);
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveTotalizacao').text('Salvar').attr('disabled', false);
                }
            });
        }

        //function recuperar_totalizacao() {
        //    var id_alocado = $('#form_totalizacao [name="id_alocado"]').val();
        //    var periodo = $('#form_totalizacao [name="periodo"]').val();
        //
        //    $('#btnRecuperarTotalizacao').text('Recuperando e validando base...').attr('disabled', true);
        //    $.ajax({
        //        'url': '<?php //echo site_url('ei/apontamento/totalizarMes') ?>//',
        //        'type': 'POST',
        //        'dataType': 'json',
        //        'data': $('#busca').serialize() + '&id_alocado=' + id_alocado + '&periodo=' + periodo,
        //        'success': function (json) {
        //            $('#planilha_faturamento').html(json.planilha_faturamento);
        //            $('#btnRecuperarTotalizacao').text('Recuperar e validar base').attr('disabled', false);
        //            $('#modal_totalizacao').modal('hide');
        //        },
        //        'error': function (jqXHR, textStatus, errorThrown) {
        //            alert('Error get data from ajax');
        //            $('#btnRecuperarTotalizacao').text('Recuperar e validar base').attr('disabled', false);
        //        }
        //    });
        //}

        function recuperar_totalizacao() {
            $('#btnRecuperarTotalizacao').text('Recuperando e validando base...').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxRecuperarTotalizacao') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_alocado': $('#form_totalizacao [name="temp_id_alocado"]').val(),
                    'mes': $('#form_totalizacao [name="mes"]').val(),
                    'periodo': $('#form_totalizacao [name="temp_periodo"]').val(),
                    'substituto': $('#form_totalizacao [name="substituto"]').val()
                },
                'success': function (json) {
                    $('#planilha_faturamento').html(json.planilha_faturamento);
                    $('#btnRecuperarTotalizacao').text('Recuperar e validar base').attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnRecuperarTotalizacao').text('Recuperar e validar base').attr('disabled', false);
                }
            });
        }

        function save_ajuste_mensal() {
            $('#btnSaveAjusteMensal').text('Salvando...').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxSaveAjusteMensal') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_ajuste_mensal').serialize(),
                'success': function (json) {
                    $('#modal_ajuste_mensal').modal('hide');
                    $('#btnSaveAjusteMensal').text('Salvar').attr('disabled', false);
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveAjusteMensal').text('Salvar').attr('disabled', false);
                }
            });
        }

        function save_pagamento_prestador() {
            $('#btnSavePagamentoPrestador').text('Salvando...').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxSavePagamentoPrestador') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_pagamento_prestador').serialize(),
                'success': function (json) {
                    $('#modal_pagamento_prestador').modal('hide');
                    $('#btnSavePagamentoPrestador').text('Salvar').attr('disabled', false);
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSavePagamentoPrestador').text('Salvar').attr('disabled', false);
                }
            });
        }


        function recuperar_pagamento_prestador() {
            $('#btnRecuperarPagamentoPrestador').text('Recuperando e validando base...').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxRecuperarPagamentoPrestador') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_horario': $('#form_pagamento_prestador [name="id_horario"]').val(),
                    'mes': $('#form_pagamento_prestador [name="mes"]').val(),
                    'substituto': $('#form_pagamento_prestador [name="substituto"]').val()
                },
                'success': function (json) {
                    $('#planilha_pagamento_prestador').html(json.planilha_pagamento_prestador);
                    $('#btnRecuperarPagamentoPrestador').text('Recuperar e validar base').attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnRecuperarPagamentoPrestador').text('Recuperar e validar base').attr('disabled', false);
                }
            });
        }

        function save_controle_materiais() {
            $('#btnSaveControleMateriais').text('Salvando...'); //change button text
            $('#btnSaveControleMateriais, #btnApagarControleMateriais').attr('disabled', true); //set button disable

            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxSaveControleMateriais') ?>',
                'type': 'POST',
                'data': $('#form_controle_materiais').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) {
                        $('#modal_controle_materiais').modal('hide');
                        table_controle_materiais.ajax.reload(null, false);
                    }

                    $('#btnSaveControleMateriais').text('Salvar');
                    $('#btnSaveControleMateriais, #btnApagarControleMateriais').attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveControleMateriais').text('Salvar');
                    $('#btnSaveControleMateriais, #btnApagarControleMateriais').attr('disabled', false);
                }
            });
        }

        function save_visita() {
            $('#btnSaveVisitas').text('Salvando...');
            $('#btnSaveVisitas, #btnLimparVisitas').attr('disabled', true);
            var url;

            if (save_method === 'add') {
                url = '<?php echo site_url('ei/apontamento/ajaxAddVisita') ?>';
            } else {
                url = '<?php echo site_url('ei/apontamento/ajaxUpdateVisita') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form_visitas').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) {
                        $('#modal_visitas').modal('hide');
                        reload_table();
                    }

                    $('#btnSaveVisitas').text('Salvar');
                    $('#btnSaveVisitas, #btnLimparVisitas').attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveVisitas').text('Salvar');
                    $('#btnSaveVisitas, #btnLimparVisitas').attr('disabled', false);
                }
            });
        }

        function limpar_visita() {
            if (confirm('Deseja limpar o evento?')) {
                $.ajax({
                    'url': '<?php echo site_url('ei/apontamento/ajaxDeleteVisita') ?>/',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {
                        'id': $('#form_visitas [name="id"]').val()
                    },
                    'success': function (json) {
                        //if success reload ajax table
                        $('#modal_visitas').modal('hide');
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

        function delete_controle_materiais() {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('ei/apontamento/ajaxDeleteControleMateriais') ?>/',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {
                        'id': $('#form_controle_materiais [name="id"]').val()
                    },
                    'success': function (json) {
                        //if success reload ajax table
                        $('#modal_controle_materiais').modal('hide');
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

        function delete_visita(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('ei/apontamento/ajaxDeleteVisita') ?>/',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (data) {
                        $('#modal_visitas').modal('hide');
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }


        function apagar() {
            $('#btnApagar').text('Excluindo...');
            $('#btnSave, #btnApagar').attr('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/ajaxDelete') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id': $('#form [name="id"]').val()
                },
                'success': function (data) {
                    $('#modal_form').modal('hide');
                    $('#btnApagar').text('Excluir');
                    $('#btnSave, #btnApagar').attr('disabled', false);
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnApagar').text('Excluir');
                    $('#btnSave, #btnApagar').attr('disabled', false);
                }
            });
        }


        function fechar_mes(id_alocado = '', periodo = '') {
            if ($('#busca [name="depto"]').val() === '' || $('#busca [name="diretoria"]').val() === '' || $('#busca [name="supervisor"]').val() === '') {
                alert('Para fechar o mês, ajuste os filtros de Departamento, Cliente e Supervisor.');
                return false;
            }
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/fecharMes') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize() + '&id_alocado=' + id_alocado + '&periodo=' + periodo,
                'success': function (data) {
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function fechar_semestre(id_alocado = '', periodo = '') {
            if ($('#busca [name="depto"]').val() === '' || $('#busca [name="diretoria"]').val() === '' || $('#busca [name="supervisor"]').val() === '') {
                alert('Para recalcular qtde. dias letivos no semestre, ajuste os filtros de Departamento, Cliente e Supervisor.');
                return false;
            }
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/fecharSemestre') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize() + '&id_alocado=' + id_alocado + '&periodo=' + periodo,
                'success': function (data) {
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function totalizar_mes(id_alocado = '', periodo = '') {
            if ($('#busca [name="depto"]').val() === '' || $('#busca [name="diretoria"]').val() === '' || $('#busca [name="supervisor"]').val() === '') {
                alert('Para totalizar o mês, ajuste os filtros de Departamento, Cliente e Supervisor.');
                return false;
            }
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/totalizarMes') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize() + '&id_alocado=' + id_alocado + '&periodo=' + periodo,
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    }
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function recalcular_ingresso() {
            if ($('#busca [name="depto"]').val() === '' || $('#busca [name="diretoria"]').val() === '' || $('#busca [name="supervisor"]').val() === '') {
                alert('Para recalcular a data de início do semestre, ajuste os filtros de Departamento, Cliente e Supervisor.');
                return false;
            }
            $('#btnRecalcularIngresso').attr('disabled', true).text('Recalculando datas início reais...');
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/recalcularIngresso') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize(),
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        table_faturamento.ajax.reload(null, false);
                    }
                    $('#btnRecalcularIngresso').attr('disabled', false).text('Recalcular datas início reais');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnRecalcularIngresso').attr('disabled', false).text('Recalcular datas início reais');
                }
            });
        }


        function recalcular_recesso() {
            if ($('#busca [name="depto"]').val() === '' || $('#busca [name="diretoria"]').val() === '' || $('#busca [name="supervisor"]').val() === '') {
                alert('Para recalcular a data de término do semestre, ajuste os filtros de Departamento, Cliente e Supervisor.');
                return false;
            }
            $('#btnRecalcularRecesso').attr('disabled', true).text('Recalculando datas término reais...');
            $.ajax({
                'url': '<?php echo site_url('ei/apontamento/recalcularRecesso') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize(),
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        table_faturamento.ajax.reload(null, false);
                    }
                    $('#btnRecalcularRecesso').attr('disabled', false).text('Recalcular datas término reais');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnRecalcularRecesso').attr('disabled', false).text('Recalcular datas término reais');
                }
            });
        }


        function mapaCarregamentoDeOS() {
            if ($('#busca [name="depto"]').val() === '' || $('#busca [name="diretoria"]').val() === '' || $('#busca [name="supervisor"]').val() === '') {
                alert('Para gerar o relatório, ajuste os filtros de Departamento, Cliente e Supervisor.');
                return false;
            }
            window.location.href = '<?= site_url('ei/relatorios/pdfMapaCarregamento'); ?>/q?' + $('#busca').serialize();
        }


        function medicao() {
            if ($('#busca [name="mes"]').val() === '' || $('#busca [name="ano"]').val() === '' || $('#busca [name="semestre"]').val() === '') {
                alert('Para gerar o relatório, ajuste os filtros de Mês, Ano e Semestre.');
                return false;
            }

            var q = new Array();
            q.push("mes=" + $('#busca [name="mes"]').val());
            q.push("ano=" + $('#busca [name="ano"]').val());
            q.push("semestre=" + $('#busca [name="semestre"]').val());

            window.open('<?php echo site_url('ei/relatorios/medicao'); ?>/q?' + q.join('&'), '_blank');
        }


        function pagamentoPrestadores() {
            if ($('#busca [name="depto"]').val() === '' || $('#busca [name="diretoria"]').val() === '' || $('#busca [name="supervisor"]').val() === '') {
                alert('Para gerar o relatório, ajuste os filtros de Departamento, Cliente e Supervisor.');
                return false;
            }
            window.open('<?php echo site_url('ei/relatorios/pagamentoPrestadores'); ?>/q?' + $('#busca').serialize(), '_blank');
        }


        function faturamentoConsolidado() {
            if ($('#busca [name="depto"]').val() === '' || $('#busca [name="diretoria"]').val() === '' || $('#busca [name="supervisor"]').val() === '') {
                alert('Para gerar o relatório, ajuste os filtros de Departamento, Cliente e Supervisor.');
                return false;
            }
            window.open('<?php echo site_url('ei/relatorios/faturamentoConsolidado'); ?>/q?' + $('#busca').serialize(), '_blank');
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
            table.ajax.reload(stmt, reset);
            table_faturamento.ajax.reload(stmt, reset);
            table_controle_materiais.ajax.reload(stmt, reset);
            table_visitas.ajax.reload(stmt, reset);
        }

    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>