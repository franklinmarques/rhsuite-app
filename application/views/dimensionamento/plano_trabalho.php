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
                        <li class="active">Gerenciar Plano de Produção</li>
                    </ol>
                    <div class="row">
                        <div class="col-md-1">
                            <label for="processo">Dia</label>
                            <input id="dia" class="form-control text-center dia" type="text"
                                   placeholder="dd" onchange="reload_table();" autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <label for="atividade">Mês</label>
                            <select id="mes" class="form-control" onchange="reload_table();" autocomplete="off">
                                <option value="">Todos</option>
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
                            <label for="etapa">Ano</label>
                            <input id="ano" class="form-control text-center ano" type="text"
                                   placeholder="aaaa" onchange="reload_table();" autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <label for="etapa">Status</label>
                            <select id="status" class="form-control" onchange="reload_table();" autocomplete="off">
                                <option value="">Todos</option>
                                <option value="A">Aberto</option>
                                <option value="E">Encerrado</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div id="esconder_itens" class="form-inline">
                        <button id="btnAdd" class="btn btn-info" onclick="add_plano_trabalho()"><i
                                    class="glyphicon glyphicon-plus"></i> Plano de trabalho
                        </button>
                    </div>
                    <hr>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th colspan="5" class="text-center">Plano de trabalho</th>
                            <th colspan="2" class="text-center">Jobs</th>
                            <th colspan="6" class="text-center">Programas (horários)</th>
                        </tr>
                        <tr>
                            <th>Identificação</th>
                            <th>Status</th>
                            <th>Data início</th>
                            <th>Data término</th>
                            <th>Ações</th>
                            <th>Nome</th>
                            <th>Ações</th>
                            <th>Equipe</th>
                            <th>Início projetado</th>
                            <th>Término projetado</th>
                            <th>Início real</th>
                            <th>Término real</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- page end-->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Incluir plano de trabalho</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Nome plano</label>
                                        <div class="col-md-8">
                                            <input name="nome" class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Data início</label>
                                        <div class="col-md-3">
                                            <input name="data_inicio" class="form-control text-center date" type="text"
                                                   placeholder="dd/mm/aaaa">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-1">Status</label>
                                        <div class="col-md-4">
                                            <select name="status" class="form-control">
                                                <option value="A">Aberto</option>
                                                <option value="E">Encerrado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Data término</label>
                                        <div class="col-md-3">
                                            <input name="data_termino" class="form-control text-center date"
                                                   type="text" placeholder="dd/mm/aaaa">
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="plano_diario" value="1"> Plano diário
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btnSave" onclick="save()">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_job" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Incluir job</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form_job" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_plano_trabalho"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Nome job</label>
                                        <div class="col-md-8">
                                            <input name="nome" class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Data início</label>
                                        <div class="col-md-3">
                                            <input name="data_inicio" class="form-control text-center date" type="text"
                                                   placeholder="dd/mm/aaaa">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-3">Horário inicio</label>
                                        <div class="col-md-3">
                                            <input name="horario_inicio" class="form-control text-center hora"
                                                   type="text" placeholder="hh:mm">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Data término</label>
                                        <div class="col-md-3">
                                            <input name="data_termino" class="form-control text-center date"
                                                   type="text" placeholder="dd/mm/aaaa">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-3">Horário término</label>
                                        <div class="col-md-3">
                                            <input name="horario_termino" class="form-control text-center hora"
                                                   type="text" placeholder="hh:mm">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Status</label>
                                        <div class="col-md-4">
                                            <select name="status" class="form-control">
                                                <option value="A">Aberto</option>
                                                <option value="E">Encerrado</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="plano_diario" value="1"> Plano diário
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btnSaveJob" onclick="save_job()">Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_programa" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Incluir programa</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <button class="btn btn-default" type="button" data-toggle="collapse"
                                    data-target="#opcoes_estrutura" aria-expanded="false"
                                    aria-controls="opcoes_estrutura"><i class="fa fa-search"></i> Mostrar opções de
                                Estrutura
                            </button>
                            <button class="btn btn-default" type="button" data-toggle="collapse"
                                    data-target="#opcoes_crono_analise" aria-expanded="false"
                                    aria-controls="opcoes_crono_analise"><i class="fa fa-search"></i> Mostrar opções de
                                CronoAnálise
                            </button>
                            <button class="btn btn-default" type="button" data-toggle="collapse"
                                    data-target="#opcoes_medicao" aria-expanded="false"
                                    aria-controls="opcoes_medicao"><i class="fa fa-search"></i> Mostrar opções de
                                Medição
                            </button>
                            <form action="#" id="form_medicao" autocomplete="off">
                                <div class="collapse" id="opcoes_estrutura">
                                    <br>
                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="depto">Departamento</label>
                                                <?php echo form_dropdown('depto', $depto, '', 'id="depto" class="form-control input-sm" onchange="filtrar_medicao();"'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="area">Area</label>
                                                <?php echo form_dropdown('area', ['' => 'Todas'], '', 'id="area" class="form-control input-sm" onchange="filtrar_medicao();"'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="setor">Setor</label>
                                                <?php echo form_dropdown('setor', ['' => 'Todos'], '', 'id="setor" class="form-control input-sm" onchange="filtrar_medicao();"'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="collapse" id="opcoes_crono_analise">
                                    <br>

                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="processo">Processo</label>
                                                <?php echo form_dropdown('processo', $processos, '', 'id="processo" class="form-control input-sm" onchange="filtrar_medicao();"'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="atividade">Atividade</label>
                                                <?php echo form_dropdown('atividade', ['' => 'selecione...'], '', 'id="atividade" class="form-control input-sm" onchange="filtrar_medicao();"'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="etapa">Etapa</label>
                                                <?php echo form_dropdown('etapa', ['' => 'Todas'], '', 'id="etapa" class="form-control input-sm" onchange="filtrar_medicao();"'); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="tipo_item">Volume</label>
                                                <?php echo form_dropdown('tipo_item', ['' => 'Todos'], '', 'id="tipo_item" class="form-control input-sm" onchange="filtrar_medicao();"'); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="complexidade">Grau de complexidade</label>
                                                <?php echo form_dropdown('complexidade', ['' => 'Todos'], '', 'id="complexidade" class="form-control input-sm" onchange="filtrar_medicao();"'); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="peso_item">Peso/massa</label>
                                                <?php echo form_dropdown('peso_item', ['' => 'Todos'], '', 'id="peso_item" class="form-control input-sm" onchange="filtrar_medicao();"'); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="crono_analise">Crono Análise</label>
                                                <?php echo form_dropdown('crono_analise', ['' => 'selecione...'], '', 'id="crono_analise" class="form-control input-sm" onchange="filtrar_medicao();"'); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="tipo" value="E"
                                                               onchange="filtrar_por_tipo(this);" checked> Equipes
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="tipo" value="C"
                                                               onchange="filtrar_por_tipo(this);"> Colaboradores
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div id="equipes">
                                                    <label for="equipe">Equipes</label>
                                                    <?php echo form_multiselect('equipe[]', [], [], 'id="equipe" class="form-control input-sm" onchange="reload_table_medicoes();"'); ?>
                                                </div>
                                                <div id="colaboradores" style="display: none;">
                                                    <label for="colaborador">Colaborador(a)</label>
                                                    <?php echo form_multiselect('colaborador[]', [], [], 'id="colaborador" class="form-control input-sm" onchange="reload_table_medicoes();"'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="collapse" id="opcoes_medicao">
                                <br>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="tipo_item">Volume trabalho</label>
                                        <input type="text" id="volume_trabalho" class="form-control valor"
                                               autocomplete="off">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="complexidade">IndProdução a ser utilizado</label>
                                        <select id="ind_producao" class="form-control" autocomplete="off">
                                            <option value="2" selected>Médio</option>
                                            <option value="1">Menor</option>
                                            <option value="3">Maior</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <br>
                                        <button type="button" class="btn btn-info" onclick="reload_table_medicoes();">
                                            Calcular
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="complexidade">Horário início programado</label>
                                        <input type="text" id="horario_inicio_programado"
                                               class="form-control text-center hora" autocomplete="off">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="complexidade">IndMãoObra a ser utilizado</label>
                                        <select id="ind_mao_obra" class="form-control" autocomplete="off">
                                            <option value="2" selected>Média</option>
                                            <option value="1">Menor</option>
                                            <option value="3">Maior</option>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <span class="text-primary"><strong>Use as Opções de Estrutura e de CronoAnálise para filtrar os dados desta lista.</strong></span>
                                <table id="table_medicoes" class="table table-bordered table-condensed"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">Ação</th>
                                        <th rowspan="2">Equipe/Colaborador(a)</th>
                                        <th class="text-center">Ind.Produção</th>
                                        <th class="text-center">Ind.MãoObra</th>
                                        <th rowspan="2" class="text-center">Volume trabalho</th>
                                        <th rowspan="2" class="text-center">H.horas necessárias</th>
                                        <th rowspan="2" class="text-center">Início estimado</th>
                                        <th rowspan="2" class="text-center">Término estimado</th>
                                        <th rowspan="2" class="text-center">Início real</th>
                                        <th rowspan="2" class="text-center">Término real</th>
                                    </tr>
                                    <tr>
                                        <th>Menor</th>
                                        <th>Menor</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="form-body">
                                <hr>
                                <form action="#" id="form_programa" class="form-horizontal" autocomplete="off">
                                    <input type="hidden" name="id" value="">
                                    <input type="hidden" name="id_job" value="">
                                    <input type="hidden" name="id_executor" value="">
                                    <input type="hidden" name="unidades" value="">
                                    <input type="hidden" name="mao_obra" value="">
                                    <h4>Dados da programação do job</h4>
                                    <div class="row form-group">
                                        <label id="label_executor" class="control-label col-md-3 text-nowrap">Equipe
                                            alocada para o job</label>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" id="nome_executor"
                                                   placeholder="Selecione uma das opções de medição acima" readonly>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <button type="button" class="btn btn-success" id="btnSavePrograma"
                                                    onclick="save_programa()">Salvar
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group">
                                        <div class="col-md-3">
                                            <label class="control-label">Ind.Produção a ser calculado</label>
                                            <select name="tipo_valor" class="form-control">
                                                <option value="P">Menor</option>
                                                <option value="M">Médio</option>
                                                <option value="G">Maior</option>
                                                <option value="">Personalizado</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo_valor_menor" class="control-label">Menor</label>
                                            <input id="tipo_valor_menor" class="form-control" type="text" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo_valor_medio" class="control-label">Médio</label>
                                            <input id="tipo_valor_medio" class="form-control" type="text" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo_valor_maior" class="control-label">Maior</label>
                                            <input id="tipo_valor_maior" class="form-control" type="text" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo_valor_personalizado"
                                                   class="control-label">Personalizado</label>
                                            <input id="tipo_valor_personalizado" class="form-control valor" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Volume de trabalho</label>
                                        <div class="col-md-2">
                                            <input name="volume_trabalho" class="form-control valor" type="text">
                                        </div>
                                        <label class="control-label col-md-3">Carga horária necessária</label>
                                        <div class="col-md-2">
                                            <input name="carga_horaria_necessaria" class="form-control valor"
                                                   type="text">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-info" id="btnCalcularValorMedicao"
                                                    onclick="calcular_valor_medicao()">Calcular
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group">
                                        <div class="col-md-3">
                                            <label class="control-label">Ind.MãoObra a ser calculado</label>
                                            <select name="tipo_mao_obra" class="form-control">
                                                <option value="P">Menor</option>
                                                <option value="M">Média</option>
                                                <option value="G">Maior</option>
                                                <option value="">Personalizado</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo_mao_obra_menor" class="control-label">Menor</label>
                                            <input id="tipo_mao_obra_menor" class="form-control" type="text" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo_mao_obra_medio" class="control-label">Média</label>
                                            <input id="tipo_mao_obra_medio" class="form-control" type="text" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo_mao_obra_maior" class="control-label">Maior</label>
                                            <input id="tipo_mao_obra_maior" class="form-control" type="text" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo_mao_obra_personalizado"
                                                   class="control-label">Personalizado</label>
                                            <input id="tipo_mao_obra_personalizado" class="form-control valor"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Qtde. horas disponíveis</label>
                                        <div class="col-md-2">
                                            <input name="qtde_horas_disponiveis" class="form-control valor" type="text">
                                        </div>
                                        <label class="control-label col-md-3">Qtde. recursos necessários</label>
                                        <div class="col-md-2">
                                            <input name="qtde_recursos_necessarios" class="form-control valor"
                                                   type="text">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-info" id="btnCalcularMaoObraMedicao"
                                                    onclick="calcular_mao_obra_medicao()">Calcular
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Horario de início projetado</label>
                                        <div class="col-md-2">
                                            <input name="horario_inicio_projetado" class="form-control text-center hora"
                                                   type="text"
                                                   placeholder="hh:mm">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-3">Horário de término projetado</label>
                                        <div class="col-md-2">
                                            <input name="horario_termino_projetado"
                                                   class="form-control text-center hora"
                                                   type="text" placeholder="hh:mm">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
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

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Plano de Produção';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

    <script>

        var save_method;
        var table, table_medicoes;
        var tipo = 'E';

        $('.date').mask('00/00/0000');
        $('.hora').mask('00:00');
        $('.dia').mask('00');
        $('.ano').mask('0000');
        $('.valor').mask('#####0,000', {'reverse': true});


        $(document).ready(function () {

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'order': [],
                'ajax': {
                    'url': '<?php echo site_url('dimensionamento/planoTrabalho/ajaxList') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.dia = $('#dia').val();
                        d.mes = $('#mes').val();
                        d.ano = $('#ano').val();
                        d.status = $('#status').val();

                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '34%',
                        'targets': [0]
                    },
                    {
                        'width': '33%',
                        'targets': [5, 7]
                    },
                    {
                        'className': 'text-center',
                        'searchable': false,
                        'targets': [1, 2, 3, 8, 9, 10, 11]
                    },
                    {
                        'className': 'text-center text-nowrap',
                        'targets': [4, 6, 12],
                        'orderable': false,
                        'searchable': false
                    }
                ],
                'rowsGroup': [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                'preDrawCallback': function () {
                    // $('.filtro, #btnAdd').prop('disabled', true);
                },
                'drawCallback': function () {
                    $('.filtro').prop('disabled', false);
                    // $('#btnAdd').prop('disabled', $('#etapa').val().length === 0 || $('#executor').val().length === 0 || $('#crono_analise').val().length === 0);
                }
            });


            table_medicoes = $('#table_medicoes').DataTable({
                'info': false,
                'lengthChange': false,
                'searching': false,
                'paging': false,
                'processing': true,
                'serverSide': true,
                'order': [['1', 'asc']],
                'ajax': {
                    'url': '<?php echo site_url('dimensionamento/planoTrabalho/ajaxListMedicoes') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.id = $('[name="id_medicao"]:checked').val();
                        d.busca = $('#form_medicao').serialize();
                        d.id_job = $('#form_programa [name="id_job"]').val();
                        d.volume_trabalho = $('#volume_trabalho').val();
                        d.horario_inicio_programado = $('#horario_inicio_programado').val();
                        d.ind_producao = $('#ind_producao').val();
                        d.ind_mao_obra = $('#ind_mao_obra').val();

                        return d;
                    },
                    'dataSrc': function (json) {
                        var ind_producao = 'Ind.Produção'
                        var ind_mao_obra = 'Ind.MãoObra'
                        if (json.base_tempo && json.unidade_producao) {
                            ind_producao = 'Ind.Produção (' + json.unidade_producao + '/H.' + json.base_tempo + ')';
                            ind_mao_obra = 'Ind.MãoObra (' + 'H.' + json.base_tempo + '/' + json.unidade_producao + ')';
                        }

                        $('#table_medicoes thead tr:eq(0) th:eq(2)').html(ind_producao);
                        $('#table_medicoes thead tr:eq(0) th:eq(3)').html(ind_mao_obra);

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var radio = '<input type="radio" ' +
                                'name="id" ' +
                                'data-id="' + rowData[0] + '" ' +
                                'data-nome="' + rowData[1] + '" ' +
                                'data-valor_menor="' + rowData[2] + '" ' +
                                'data-valor_medio="' + rowData[3] + '" ' +
                                'data-valor_maior="' + rowData[4] + '" ' +
                                'data-mao_obra_menor="' + rowData[5] + '" ' +
                                'data-mao_obra_medio="' + rowData[6] + '" ' +
                                'data-mao_obra_maior="' + rowData[7] + '" ' +
                                'onchange="selecionar_medicao(this);">';
                            $(td).html('<div class="radio"><label>' + radio + '</label></div>');
                        },
                        'className': 'text-center',
                        'orderable': false,
                        'targets': [0]
                    },
                    {
                        'width': '100%',
                        'targets': [1]
                    },
                    {
                        'className': 'text-center',
                        'targets': [2, 3, 4, 5, 6]
                    }
                ]
            });

        });


        $('#esconder_itens input.toggle-vis').on('change', function (e) {
            var value = parseInt($(this).val());
            var column = table.column(value);
            column.visible(!column.visible());
        });


        $('#form_programa [name="tipo_valor"], #tipo_valor_personalizado').on('change', function () {
            selecionar_tipo_valor();
        });

        $('#form_programa [name="tipo_mao_obra"], #tipo_mao_obra_personalizado').on('change', function () {
            selecionar_tipo_mao_obra();
        });


        function selecionar_medicao(elem) {
            $('#form_programa')[0].reset();
            var data = $(elem).data();
            $('#form_programa [name="id_executor"]').val(data.id);
            $('#nome_executor').val(data.nome);
            $('#tipo_valor_menor').val(data.valor_menor);
            $('#tipo_valor_medio').val(data.valor_medio);
            $('#tipo_valor_maior').val(data.valor_maior);
            $('#tipo_mao_obra_menor').val(data.mao_obra_menor);
            $('#tipo_mao_obra_medio').val(data.mao_obra_medio);
            $('#tipo_mao_obra_maior').val(data.mao_obra_maior);
            $('#tipo_valor_personalizado').val('');

            selecionar_tipo_valor();
            selecionar_tipo_mao_obra();
        }


        function selecionar_tipo_valor() {
            var tipo_valor = $('#form_programa [name="tipo_valor"]').val();
            var unidade = '';
            switch (tipo_valor) {
                case 'P':
                    unidade = $('#tipo_valor_menor').val();
                    break;
                case 'M':
                    unidade = $('#tipo_valor_medio').val();
                    break;
                case 'G':
                    unidade = $('#tipo_valor_maior').val();
                    break;
                case '':
                    unidade = $('#tipo_valor_personalizado').val();
            }

            $('#form_programa [name="unidades"]').val(unidade);
        }


        function selecionar_tipo_mao_obra() {
            var tipo_mao_obra = $('#form_programa [name="tipo_mao_obra"]').val();
            var mao_obra = '';
            switch (tipo_mao_obra) {
                case 'P':
                    mao_obra = $('#tipo_mao_obra_menor').val();
                    break;
                case 'M':
                    mao_obra = $('#tipo_mao_obra_medio').val();
                    break;
                case 'G':
                    mao_obra = $('#tipo_mao_obra_maior').val();
                    break;
                case '':
                    mao_obra = $('#tipo_mao_obra_personalizado').val();
            }

            $('#form_programa [name="mao_obra"]').val(mao_obra);
        }


        function calcular_valor_medicao() {
            var volume_trabaho = $('#form_programa [name="volume_trabalho"]').val();
            var unidades = $('#form_programa [name="unidades"]').val();
            var carga_horaria = '';

            if (unidades.length > 0 && volume_trabaho.length > 0) {
                volume_trabaho = parseFloat(volume_trabaho.replace(',', '.'));
                unidades = parseFloat(unidades.replace(',', '.'));
                carga_horaria = (volume_trabaho * unidades).toString().replace('.', ',')
            }

            $('#form_programa [name="carga_horaria_necessaria"]').val(carga_horaria);
        }


        function calcular_mao_obra_medicao() {
            var horas_disponiveis = $('#form_programa [name="qtde_horas_disponiveis"]').val();
            var mao_obra = $('#form_programa [name="mao_obra"]').val();
            var qtde_recursos = '';

            if (mao_obra.length > 0 && horas_disponiveis.length > 0) {
                horas_disponiveis = parseFloat(horas_disponiveis.replace(',', '.'));
                mao_obra = parseFloat(mao_obra.replace(',', '.'));
                qtde_recursos = (horas_disponiveis * mao_obra).toString().replace('.', ',')
            }

            $('#form_programa [name="qtde_recursos_necessarios"]').val(qtde_recursos);
        }


        function filtrar_por_tipo(elem) {
            tipo = elem.value;
            if (tipo === 'E') {
                $('#equipes').show();
                $('#colaboradores').hide();
                $('#label_executor').text('Equipe alocada para o job');
            } else if (tipo === 'C') {
                $('#equipes').hide();
                $('#colaboradores').show();
                $('#label_executor').text('Colaborador(a) alocado(a) ao job');
            }
            reload_table_medicoes();
        }


        function filtrar_medicao() {
            var form = $('#form_medicao').serialize();
            $.ajax({
                'url': '<?php echo site_url('dimensionamento/planoTrabalho/filtrarMedicao') ?>',
                'type': 'POST',
                'data': form,
                'dataType': 'json',
                'beforeSend': function () {
                    $('#form_medicao select, #btnSavePrograma, #btnCalcularValorMedicao, #btnCalcularMaoObraMedicao').prop('disabled', true);
                },
                'success': function (json) {
                    $.each(json, function (id, element) {
                        $('#' + id).html($(element).html());
                    });

                    $('#form_medicao select').prop('disabled', false);
                    reload_table_medicoes();
                },
                'complete': function () {
                    $('#form_medicao select, #btnSavePrograma, #btnCalcularValorMedicao, #btnCalcularMaoObraMedicao').prop('disabled', false);
                }
            });
        }


        function add_plano_trabalho() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form [name="id"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar plano de trabalho');
            $('.combo_nivel1').hide();
        }


        function add_job(id_plano_trabalho) {
            save_method = 'add';
            $('#form_job')[0].reset();
            $('#form_job [name="id"]').val('');
            $('#form_job [name="id_plano_trabalho"]').val(id_plano_trabalho);
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $('#modal_job').modal('show');
            $('.modal-title').text('Adicionar job');
            $('.combo_nivel1').hide();
        }


        function add_programa(id_job) {
            save_method = 'add';
            $('#form_programa')[0].reset();
            $('#form_programa [name="id"]').val('');
            $('#form_programa [name="id_job"]').val(id_job);
            $('#form_programa [name="id_executor"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            selecionar_tipo_valor();
            selecionar_tipo_mao_obra();
            $('#modal_programa').modal('show');
            $('.modal-title').text('Adicionar programa');
            $('.combo_nivel1').hide();
        }


        function edit_plano_trabalho(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('dimensionamento/planoTrabalho/ajaxEdit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }
                    $.each(json, function (key, value) {
                        $('#form [name="' + key + '"]').val(value);
                    });

                    $('#nome_executor').text(json.executor);
                    $('#nome_processo').text(json.processo);
                    $('#nome_atividade').text(json.atividade);
                    $('#nome_etapa').text(json.etapa);
                    $('#nome_crono_analise').text(json.crono_analise);


                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar plano de trabalho');

                }
            });
        }


        function edit_job(id) {
            save_method = 'update';
            $('#form_job')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('dimensionamento/planoTrabalho/ajaxEditJob') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }
                    $.each(json, function (key, value) {
                        $('#form_job [name="' + key + '"]').val(value);
                    });

                    $('#nome_executor').text(json.executor);
                    $('#nome_processo').text(json.processo);
                    $('#nome_atividade').text(json.atividade);
                    $('#nome_etapa').text(json.etapa);
                    $('#nome_crono_analise').text(json.crono_analise);

                    $('#modal_job').modal('show');
                    $('.modal-title').text('Editar job');

                }
            });
        }


        function edit_programa(id) {
            save_method = 'update';
            $('#form_programa')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('dimensionamento/planoTrabalho/ajaxEditPrograma') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $('#form_medicao [name="tipo"][value="' + json.tipo + '"]').prop('checked', true);
                    if (json.tipo === 'E') {
                        $('#equipes').show();
                        $('#colaboradores').hide();
                        $('#label_executor').text('Equipe alocada para o job');
                    } else if (json.tipo === 'C') {
                        $('#equipes').hide();
                        $('#colaboradores').show();
                        $('#label_executor').text('Colaborador(a) alocado(a) ao job');
                    }

                    $.each(json, function (key, value) {
                        $('#form_programa [name="' + key + '"]').val(value);
                    });

                    $('#nome_executor').val(json.nome_executor);
                    $('#tipo_valor_menor').val(json.valor_min_calculado);
                    $('#tipo_valor_medio').val(json.valor_medio_calculado);
                    $('#tipo_valor_maior').val(json.valor_max_calculado);
                    if (json.tipo_valor === null || json.tipo_valor === '') {
                        $('#tipo_valor_personalizado').val(json.unidades);
                    }

                    $('#tipo_mao_obra_menor').val(json.mao_obra_min_calculada);
                    $('#tipo_mao_obra_medio').val(json.mao_obra_media_calculada);
                    $('#tipo_mao_obra_maior').val(json.mao_obra_max_calculada);
                    if (json.tipo_mao_obra === null || json.tipo_mao_obra === '') {
                        $('#tipo_mao_obra_personalizado').val(json.mao_obra);
                    }

                    selecionar_tipo_valor();
                    selecionar_tipo_mao_obra();

                    $('#modal_programa').modal('show');
                    $('.modal-title').text('Editar programa');

                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false);
        }


        function reload_table_medicoes() {
            table_medicoes.ajax.reload(null, false);
        }


        function save() {
            var url;
            if (save_method === 'add') {
                url = '<?php echo site_url('dimensionamento/planoTrabalho/ajaxAdd') ?>';
            } else {
                url = '<?php echo site_url('dimensionamento/planoTrabalho/ajaxUpdate') ?>';
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
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'complete': function () {
                    $('#btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }


        function save_job() {
            var url;
            if (save_method === 'add') {
                url = '<?php echo site_url('dimensionamento/planoTrabalho/ajaxAddJob') ?>';
            } else {
                url = '<?php echo site_url('dimensionamento/planoTrabalho/ajaxUpdateJob') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form_job').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveJob').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_job').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'complete': function () {
                    $('#btnSaveJob').text('Salvar').attr('disabled', false);
                }
            });
        }


        function save_programa() {
            var url;
            if (save_method === 'add') {
                url = '<?php echo site_url('dimensionamento/planoTrabalho/ajaxAddPrograma') ?>';
            } else {
                url = '<?php echo site_url('dimensionamento/planoTrabalho/ajaxUpdatePrograma') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form_programa').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSavePrograma').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_programa').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'complete': function () {
                    $('#btnSavePrograma').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_plano_trabalho(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('dimensionamento/planoTrabalho/ajaxDelete') ?>',
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


        function delete_job(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('dimensionamento/planoTrabalho/ajaxDeleteJob') ?>',
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


        function delete_programa(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('dimensionamento/planoTrabalho/ajaxDeletePrograma') ?>',
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


    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>
