<?php
require_once "header.php";
?>
    <style>
        .nav > li > a {
            position: relative;
            display: block;
            padding: 10px 8px;
        }

        .btn-primary {
            background-color: #337ab7 !important;
            border-color: #2e6da4 !important;
            color: #fff;
        }

        #table_turnover,
        #table_afastamentos {
            border: 2px solid #ddd;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <?php echo $this->load->view('modal_processos', ['url' => 'gestaoDePessoal']); ?>
                            <i class="fa fa-pencil-square-o"></i> Relatários de Gestão - Gestão de Pessoal
                        </header>
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label">Departamento</label>
                                                <?php echo form_dropdown('id_depto', $deptos, '', 'class="form-control filtro input-sm" onchange="atualizar_filtro(this);" autocomplete="off"'); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label">Cargo/função</label>
                                                <?php echo form_dropdown('id_funcao', $cargosFuncoes, '', 'id="id_funcao" class="form-control filtro input-sm" autocomplete="off"'); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="control-label">Mês</label>
                                                <select name="mes" id="mes" class="form-control filtro">
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
                                            <div class="col-md-1">
                                                <label class="control-label">Ano</label>
                                                <input type="text" name="ano" id="ano" value="<?= date('Y'); ?>"
                                                       class="form-control input-sm text-center filtro"
                                                       autocomplete="off"
                                                       placeholder="aaaa">
                                            </div>
                                            <div class="col-md-9 text-right">
                                                <label>&nbsp;</label><br>
                                                <div class="btn-group" role="group" aria-label="...">
                                                    <button type="button" id="pesquisar" class="btn btn-sm btn-default"
                                                            onclick="reload_table();">
                                                        <i class="glyphicon glyphicon-search"></i> Pesquisar
                                                    </button>
                                                    <a href="<?= site_url('gestaoDePessoal/relatorio'); ?>"
                                                       target="_blank" id="pdf" class="btn btn-sm btn-info">
                                                        <i class="glyphicon glyphicon-print"></i> Imprimir
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <ul class="nav nav-tabs" role="tablist" style="font-size: small; font-weight: bolder;">
                                <li role="presentation" class="active">
                                    <a href="#quadro_colaboradores" aria-controls="quadro_colaboradores" role="tab"
                                       data-toggle="tab">Quadro</a>
                                </li>
                                <li role="presentation">
                                    <a href="#requisicoes_pessoal" aria-controls="requisicoes_pessoal" role="tab"
                                       data-toggle="tab">Requisições de Pessoal</a>
                                </li>
                                <li role="presentation">
                                    <a href="#turnover" aria-controls="turnover" role="tab"
                                       data-toggle="tab">Turnover</a>
                                </li>
                                <li role="presentation">
                                    <a href="#afastamentos" aria-controls="afastamentos" role="tab" data-toggle="tab">Afastamentos</a>
                                </li>
                                <li role="presentation">
                                    <a href="#faltas_atrasos" aria-controls="faltas_atrasos" role="tab"
                                       data-toggle="tab">Faltas e atrasos</a>
                                </li>
                                <li role="presentation">
                                    <a href="#periodo_experiencia" aria-controls="periodo_experiencia" role="tab"
                                       data-toggle="tab">Período
                                        Experiência</a>
                                </li>
                                <li role="presentation">
                                    <a href="#exames_periodicos" aria-controls="exames_periodocos" role="tab"
                                       data-toggle="tab">Exames Periódicos</a>
                                </li>
                                <li role="presentation">
                                    <a href="#treinamentos" aria-controls="treinamentos" role="tab" data-toggle="tab">Treinamentos</a>
                                </li>
                                <li role="presentation">
                                    <a href="#demissoes_desligamentos" aria-controls="demissoes_desligamentos"
                                       role="tab" data-toggle="tab">Demissões/desligamentos</a>
                                </li>
                            </ul>

                            <!-- Css -->
                            <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>"
                                  rel="stylesheet">

                            <!-- Js -->
                            <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
                            <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
                            <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
                            <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="quadro_colaboradores">
                                    <br>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h4 style="color: #111343;"><strong>Consolidado de Quadro de
                                                    Colaboradores</strong>
                                            </h4>
                                        </div>
                                        <div class="col-md-7 text-right">
                                            <!--<button type="button" class="btn btn-default"
                                                    onclick="ler_estrutura_atual()"><i
                                                        class="glyphicon glyphicon-refresh"></i> Ler estrutura + quadro
                                            </button>-->
                                            <button type="button" id="ler_quadro" class="btn btn-info"
                                                    onclick="ler_quadro_atual()">Ler
                                                quadro
                                            </button>
                                            <button type="button" id="salvar_estrutura" class="btn btn-success"
                                                    onclick="salvar_estruturas()"> Salvar
                                            </button>
                                            <button type="button" id="limpar_estrutura" class="btn btn-danger"
                                                    onclick="limpar_estruturas()"> Limpar
                                            </button>
                                        </div>
                                    </div>

                                    <table id="table_quadro_colaboradores" class="table table-bordered table-condensed"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="active">
                                            <th>Departamento (unidade de negócios)</th>
                                            <th class="text-center meses_quadro_colaboradores">Jan</th>
                                            <th class="text-center meses_quadro_colaboradores">Fev</th>
                                            <th class="text-center meses_quadro_colaboradores">Mar</th>
                                            <th class="text-center meses_quadro_colaboradores">Abr</th>
                                            <th class="text-center meses_quadro_colaboradores">Mai</th>
                                            <th class="text-center meses_quadro_colaboradores">Jun</th>
                                            <th class="text-center meses_quadro_colaboradores">Jul</th>
                                            <th class="text-center meses_quadro_colaboradores">Ago</th>
                                            <th class="text-center meses_quadro_colaboradores">Set</th>
                                            <th class="text-center meses_quadro_colaboradores">Out</th>
                                            <th class="text-center meses_quadro_colaboradores">Nov</th>
                                            <th class="text-center meses_quadro_colaboradores">Dez</th>
                                            <th>Média anual</th>
                                        </tr>
                                        <tr class="active">
                                            <th>Total</th>
                                            <th class="text-center total_quadro_colaboradores"></th>
                                            <th class="text-center total_quadro_colaboradores"></th>
                                            <th class="text-center total_quadro_colaboradores"></th>
                                            <th class="text-center total_quadro_colaboradores"></th>
                                            <th class="text-center total_quadro_colaboradores"></th>
                                            <th class="text-center total_quadro_colaboradores"></th>
                                            <th class="text-center total_quadro_colaboradores"></th>
                                            <th class="text-center total_quadro_colaboradores"></th>
                                            <th class="text-center total_quadro_colaboradores"></th>
                                            <th class="text-center total_quadro_colaboradores"></th>
                                            <th class="text-center total_quadro_colaboradores"></th>
                                            <th class="text-center total_quadro_colaboradores"></th>
                                            <th class="text-center total_quadro_colaboradores"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                    <!-- Bootstrap modal -->
                                    <div class="modal fade" id="modal_quadro_colaboradores" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h3 class="modal-title">Editar total colaboradores</h3>
                                                </div>
                                                <div class="modal-body form">
                                                    <form action="#" id="form_quadro_colaboradores"
                                                          class="form-horizontal"
                                                          autocomplete="off">
                                                        <input type="hidden" value="" name="id"/>
                                                        <input type="hidden" value="" name="id_depto"/>
                                                        <input type="hidden" value="" name="mes"/>
                                                        <input type="hidden" value="" name="ano"/>
                                                        <div class="form-body">
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-4"
                                                                       style="margin-top: -13px;">Indicador:</label>
                                                                <div class="col-md-7" style="margin-top: -13px;">
                                                                    <label class="sr-only"></label>
                                                                    <p class="form-control-static">
                                                                        <span id="quadro_colaboradores_indicador"></span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-4"
                                                                       style="margin-top: -13px;">Mês/ano:
                                                                </label>
                                                                <div class="col-md-7" style="margin-top: -13px;">
                                                                    <label class="sr-only"></label>
                                                                    <p class="form-control-static">
                                                                        <span id="quadro_colaboradores_mes_ano"></span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-4">Qtd
                                                                    colaboradores:</label>
                                                                <div class="col-md-4">
                                                                    <input name="total_colaboradores"
                                                                           class="form-control" type="number" value=""
                                                                           step="0" min="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" id="btnSaveQuadroColaboradores"
                                                            onclick="save_quadro_colaboradores()"
                                                            class="btn btn-success">Salvar
                                                    </button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                                        Cancelar
                                                    </button>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                    <!-- End Bootstrap modal -->

                                </div>
                                <div role="tabpanel" class="tab-pane" id="requisicoes_pessoal">
                                    <br>
                                    <h4 style="color: #111343;"><strong>Consolidado de Requisições de Pessoal</strong>
                                    </h4>
                                    <table id="table_requisicoes_pessoal" class="table table-bordered table-condensed"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="active">
                                            <th rowspan="2" style="vertical-align: middle;">Mês</th>
                                            <th colspan="2" class="text-center">Abertas</th>
                                            <th colspan="2" class="text-center">Fechadas</th>
                                            <th colspan="2" class="text-center">Suspensas</th>
                                            <th class="text-center">Canceladas</th>
                                        </tr>
                                        <tr class="active">
                                            <th class="text-center">RPs</th>
                                            <th class="text-center">Vagas</th>
                                            <th class="text-center">RPs</th>
                                            <th class="text-center">Vagas</th>
                                            <th class="text-center">RPs</th>
                                            <th class="text-center">Vagas</th>
                                            <th class="text-center">RPs</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                        <tr class="active">
                                            <th>Total</th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                        </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="turnover">
                                    <br>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h4 style="color: #111343;"><strong>Consolidado de Movimentação de
                                                    Pessoal</strong>
                                            </h4>
                                        </div>
                                        <div class="col-md-7 text-right">
                                            <button type="button" class="btn btn-default" onclick="ler_turnover()">
                                                <i class="glyphicon glyphicon-refresh"></i> Recarregar
                                            </button>
                                            <button type="button" id="ler_turnover" class="btn btn-info"
                                                    onclick="ler_turnover_atual()">Ler
                                                dados
                                            </button>
                                            <button type="button" id="salvar_turnover" class="btn btn-success"
                                                    onclick="salvar_turnover();"> Salvar
                                            </button>

                                            <button type="button" id="limpar_turnover" class="btn btn-danger"
                                                    onclick="limpar_turnover()"> Limpar
                                            </button>
                                        </div>
                                    </div>

                                    <table id="table_turnover" class="table table-bordered table-condensed"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="active">
                                            <th>Indicadores</th>
                                            <th class="meses_turnover">Jan</th>
                                            <th class="meses_turnover">Fev</th>
                                            <th class="meses_turnover">Mar</th>
                                            <th class="meses_turnover">Abr</th>
                                            <th class="meses_turnover">Mai</th>
                                            <th class="meses_turnover">Jun</th>
                                            <th class="meses_turnover">Jul</th>
                                            <th class="meses_turnover">Ago</th>
                                            <th class="meses_turnover">Set</th>
                                            <th class="meses_turnover">Out</th>
                                            <th class="meses_turnover">Nov</th>
                                            <th class="meses_turnover">Dez</th>
                                            <th>Média anual</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                    <!-- Bootstrap modal -->
                                    <div class="modal fade" id="modal_turnover" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h3 class="modal-title">Cadastro quantidades</h3>
                                                </div>
                                                <div class="modal-body form">
                                                    <form action="#" id="form_turnover" class="form-horizontal"
                                                          autocomplete="off">
                                                        <input type="hidden" value="" name="id"/>
                                                        <input type="hidden" value="" name="mes"/>
                                                        <input type="hidden" value="" name="ano"/>
                                                        <div class="form-body">
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-7">Qtd colaboradores
                                                                    ativos</label>
                                                                <div class="col-md-3">
                                                                    <input name="total_colaboradores_ativos"
                                                                           class="form-control" type="number" value=""
                                                                           step="0" min="0">
                                                                </div>
                                                            </div>
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-7">Qtd colaboradores
                                                                    admitidos</label>
                                                                <div class="col-md-3">
                                                                    <input name="total_colaboradores_admitidos"
                                                                           class="form-control" type="number" value=""
                                                                           step="0" min="0">
                                                                </div>
                                                            </div>
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-7">Qtd colaboradores
                                                                    demitidos</label>
                                                                <div class="col-md-3">
                                                                    <input name="total_colaboradores_demitidos"
                                                                           class="form-control" type="number" value=""
                                                                           step="0" min="0">
                                                                </div>
                                                            </div>
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-7">Qtd colaboradores
                                                                    desligados</label>
                                                                <div class="col-md-3">
                                                                    <input name="total_colaboradores_desligados"
                                                                           class="form-control" type="number" value=""
                                                                           step="0" min="0">
                                                                </div>
                                                            </div>
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-7">Qtd demissões +
                                                                    desligamentos < 6 meses</label>
                                                                <div class="col-md-3">
                                                                    <input name="total_demissoes_desligamentos"
                                                                           class="form-control" type="number" value=""
                                                                           step="0" min="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" id="btnSaveTurnover" onclick="save_turnover()"
                                                            class="btn btn-success">Salvar
                                                    </button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                                        Cancelar
                                                    </button>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                    <!-- End Bootstrap modal -->

                                </div>
                                <div role="tabpanel" class="tab-pane" id="afastamentos">
                                    <br>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h4 style="color: #111343;"><strong>Consolidado do Quadro de
                                                    Afastados</strong>
                                            </h4>
                                        </div>
                                        <div class="col-md-7 text-right">
                                            <button type="button" class="btn btn-default" onclick="ler_afastamentos()">
                                                <i class="glyphicon glyphicon-refresh"></i> Recarregar
                                            </button>
                                            <button type="button" id="ler_afastamentos" class="btn btn-info"
                                                    onclick="ler_afastamentos_atual();">Ler
                                                dados
                                            </button>
                                            <button type="button" id="salvar_afastamentos" class="btn btn-success"
                                                    onclick="salvar_afastamentos();"> Salvar
                                            </button>

                                            <button type="button" id="limpar_afastamentos" class="btn btn-danger"
                                                    onclick="limpar_afastamentos()"> Limpar
                                            </button>
                                        </div>
                                    </div>
                                    <table id="table_afastamentos" class="table table-bordered table-condensed"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="active">
                                            <th>Indicadores</th>
                                            <th class="meses_afastamentos">Jan</th>
                                            <th class="meses_afastamentos">Fev</th>
                                            <th class="meses_afastamentos">Mar</th>
                                            <th class="meses_afastamentos">Abr</th>
                                            <th class="meses_afastamentos">Mai</th>
                                            <th class="meses_afastamentos">Jun</th>
                                            <th class="meses_afastamentos">Jul</th>
                                            <th class="meses_afastamentos">Ago</th>
                                            <th class="meses_afastamentos">Set</th>
                                            <th class="meses_afastamentos">Out</th>
                                            <th class="meses_afastamentos">Nov</th>
                                            <th class="meses_afastamentos">Dez</th>
                                            <th>Média anual</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                    <!-- Bootstrap modal -->
                                    <div class="modal fade" id="modal_afastamentos" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h3 class="modal-title">Cadastro quantidades</h3>
                                                </div>
                                                <div class="modal-body form">
                                                    <form action="#" id="form_afastamentos" class="form-horizontal"
                                                          autocomplete="off">
                                                        <input type="hidden" value="" name="id"/>
                                                        <input type="hidden" value="" name="mes"/>
                                                        <input type="hidden" value="" name="ano"/>
                                                        <div class="form-body">
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-5"
                                                                       style="margin-top: -13px;">Mês/ano:
                                                                </label>
                                                                <div class="col-md-6" style="margin-top: -13px;">
                                                                    <label class="sr-only"></label>
                                                                    <p class="form-control-static">
                                                                        <span id="afastamentos_mes_ano"></span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-5">Qtd colaboradores
                                                                    ativos:</label>
                                                                <div class="col-md-3">
                                                                    <input name="total_colaboradores_ativos"
                                                                           class="form-control" type="number" value=""
                                                                           step="0" min="0" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-5">Qtd
                                                                    acidentes:</label>
                                                                <div class="col-md-3">
                                                                    <input name="total_acidentes"
                                                                           class="form-control" type="number" value=""
                                                                           step="0" min="0">
                                                                </div>
                                                            </div>
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-5">Qtd
                                                                    maternidade:</label>
                                                                <div class="col-md-3">
                                                                    <input name="total_maternidade"
                                                                           class="form-control" type="number" value=""
                                                                           step="0" min="0">
                                                                </div>
                                                            </div>
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-5">Qtd
                                                                    aposentadoria:</label>
                                                                <div class="col-md-3">
                                                                    <input name="total_aposentadoria"
                                                                           class="form-control" type="number" value=""
                                                                           step="0" min="0">
                                                                </div>
                                                            </div>
                                                            <div class="row form-group">
                                                                <label class="control-label col-md-5">Qtd
                                                                    doença:</label>
                                                                <div class="col-md-3">
                                                                    <input name="total_doenca"
                                                                           class="form-control" type="number" value=""
                                                                           step="0" min="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" onclick="importar_afastamentos()"
                                                            class="btn btn-info">Ler status atual
                                                    </button>
                                                    <button type="button" id="btnSaveAfastamentos"
                                                            onclick="save_afastamentos()"
                                                            class="btn btn-success">Salvar
                                                    </button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                                        Cancelar
                                                    </button>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                    <!-- End Bootstrap modal -->

                                </div>
                                <div role="tabpanel" class="tab-pane" id="faltas_atrasos">
                                    <br>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h4 style="color: #111343;"><strong>Consolidado de Faltas e Atrasos</strong>
                                            </h4>
                                        </div>
                                        <div class="col-md-8 text-right">
                                            <button type="button" class="btn btn-default"
                                                    onclick="ler_faltas_atrasos()"><i
                                                        class="glyphicon glyphicon-refresh"></i> Recarregar
                                            </button>
                                            <button type="button" id="ler_faltas_atrasos" class="btn btn-info"
                                                    onclick="ler_faltas_atrasos_atual()">Ler
                                                faltas/atrasos
                                            </button>
                                            <button type="button" id="salvar_faltas_atrasos" class="btn btn-success"
                                                    onclick="salvar_faltas_atrasos()"> Salvar
                                            </button>
                                            <button type="button" id="limpar_faltas_atrasos" class="btn btn-danger"
                                                    onclick="limpar_faltas_atrasos()"> Limpar
                                            </button>
                                        </div>
                                    </div>

                                    <table id="table_faltas_atrasos" class="table table-bordered table-condensed"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="active">
                                            <th rowspan="2" class="text-center">Departamento (unidade de negócios)</th>
                                            <th colspan="2" class="text-center">Jan</th>
                                            <th colspan="2" class="text-center">Fev</th>
                                            <th colspan="2" class="text-center">Mar</th>
                                            <th colspan="2" class="text-center">Abr</th>
                                            <th colspan="2" class="text-center">Mai</th>
                                            <th colspan="2" class="text-center">Jun</th>
                                            <th colspan="2" class="text-center">Jul</th>
                                            <th colspan="2" class="text-center">Ago</th>
                                            <th colspan="2" class="text-center">Set</th>
                                            <th colspan="2" class="text-center">Out</th>
                                            <th colspan="2" class="text-center">Nov</th>
                                            <th colspan="2" class="text-center">Dez</th>
                                            <th colspan="2" class="text-center">Média anual</th>
                                        </tr>
                                        <tr class="active">
                                            <th class="meses_faltas">F</th>
                                            <th class="meses_atrasos">A</th>
                                            <th class="meses_faltas">F</th>
                                            <th class="meses_atrasos">A</th>
                                            <th class="meses_faltas">F</th>
                                            <th class="meses_atrasos">A</th>
                                            <th class="meses_faltas">F</th>
                                            <th class="meses_atrasos">A</th>
                                            <th class="meses_faltas">F</th>
                                            <th class="meses_atrasos">A</th>
                                            <th class="meses_faltas">F</th>
                                            <th class="meses_atrasos">A</th>
                                            <th class="meses_faltas">F</th>
                                            <th class="meses_atrasos">A</th>
                                            <th class="meses_faltas">F</th>
                                            <th class="meses_atrasos">A</th>
                                            <th class="meses_faltas">F</th>
                                            <th class="meses_atrasos">A</th>
                                            <th class="meses_faltas">F</th>
                                            <th class="meses_atrasos">A</th>
                                            <th class="meses_faltas">F</th>
                                            <th class="meses_atrasos">A</th>
                                            <th class="meses_faltas">F</th>
                                            <th class="meses_atrasos">A</th>
                                            <th>F</th>
                                            <th>A</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="periodo_experiencia">

                                    <br>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h4 style="color: #111343;"><strong>Consolidado de Período de
                                                    Experiência</strong></h4>
                                        </div>
                                        <div class="col-md-7 text-right">
                                            <button type="button" id="ler_experiencia" class="btn btn-info"
                                                    onclick="ler_experiencia_atual()">Ler dados
                                            </button>
                                            <button type="button" id="salvar_experiencia" class="btn btn-success"
                                                    onclick="salvar_experiencia()"> Salvar
                                            </button>
                                            <button type="button" id="limpar_experiencia" class="btn btn-danger"
                                                    onclick="limpar_experiencia()"> Limpar
                                            </button>
                                        </div>
                                    </div>

                                    <table id="table_periodo_experiencia" class="table table-bordered table-condensed"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="active">
                                            <th>Departamento (unidade de negócios)</th>
                                            <th class="text-center meses_periodo_experiencia">Jan</th>
                                            <th class="text-center meses_periodo_experiencia">Fev</th>
                                            <th class="text-center meses_periodo_experiencia">Mar</th>
                                            <th class="text-center meses_periodo_experiencia">Abr</th>
                                            <th class="text-center meses_periodo_experiencia">Mai</th>
                                            <th class="text-center meses_periodo_experiencia">Jun</th>
                                            <th class="text-center meses_periodo_experiencia">Jul</th>
                                            <th class="text-center meses_periodo_experiencia">Ago</th>
                                            <th class="text-center meses_periodo_experiencia">Set</th>
                                            <th class="text-center meses_periodo_experiencia">Out</th>
                                            <th class="text-center meses_periodo_experiencia">Nov</th>
                                            <th class="text-center meses_periodo_experiencia">Dez</th>
                                            <th>Média anual</th>
                                        </tr>
                                        <tr class="active">
                                            <th>Total</th>
                                            <th class="text-center total_periodo_experiencia"></th>
                                            <th class="text-center total_periodo_experiencia"></th>
                                            <th class="text-center total_periodo_experiencia"></th>
                                            <th class="text-center total_periodo_experiencia"></th>
                                            <th class="text-center total_periodo_experiencia"></th>
                                            <th class="text-center total_periodo_experiencia"></th>
                                            <th class="text-center total_periodo_experiencia"></th>
                                            <th class="text-center total_periodo_experiencia"></th>
                                            <th class="text-center total_periodo_experiencia"></th>
                                            <th class="text-center total_periodo_experiencia"></th>
                                            <th class="text-center total_periodo_experiencia"></th>
                                            <th class="text-center total_periodo_experiencia"></th>
                                            <th class="text-center total_periodo_experiencia"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="exames_periodicos">

                                    <br>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h4 style="color: #111343;"><strong>Consolidado de Exame Periódico</strong>
                                            </h4>
                                        </div>
                                        <div class="col-md-7 text-right">
                                            <button type="button" id="ler_exame_periodico" class="btn btn-info"
                                                    onclick="ler_exame_periodico_atual()">Ler dados
                                            </button>
                                            <button type="button" id="salvar_exame_periodico" class="btn btn-success"
                                                    onclick="salvar_exame_periodico()"> Salvar
                                            </button>
                                            <button type="button" id="limpar_exame_periodico" class="btn btn-danger"
                                                    onclick="limpar_exame_periodico()"> Limpar
                                            </button>
                                        </div>
                                    </div>

                                    <table id="table_exame_periodico" class="table table-bordered table-condensed"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="active">
                                            <th>Departamento (unidade de negócios)</th>
                                            <th class="text-center meses_exame_periodico">Jan</th>
                                            <th class="text-center meses_exame_periodico">Fev</th>
                                            <th class="text-center meses_exame_periodico">Mar</th>
                                            <th class="text-center meses_exame_periodico">Abr</th>
                                            <th class="text-center meses_exame_periodico">Mai</th>
                                            <th class="text-center meses_exame_periodico">Jun</th>
                                            <th class="text-center meses_exame_periodico">Jul</th>
                                            <th class="text-center meses_exame_periodico">Ago</th>
                                            <th class="text-center meses_exame_periodico">Set</th>
                                            <th class="text-center meses_exame_periodico">Out</th>
                                            <th class="text-center meses_exame_periodico">Nov</th>
                                            <th class="text-center meses_exame_periodico">Dez</th>
                                            <th>Média anual</th>
                                        </tr>
                                        <tr class="active">
                                            <th>Total</th>
                                            <th class="text-center total_exame_periodico"></th>
                                            <th class="text-center total_exame_periodico"></th>
                                            <th class="text-center total_exame_periodico"></th>
                                            <th class="text-center total_exame_periodico"></th>
                                            <th class="text-center total_exame_periodico"></th>
                                            <th class="text-center total_exame_periodico"></th>
                                            <th class="text-center total_exame_periodico"></th>
                                            <th class="text-center total_exame_periodico"></th>
                                            <th class="text-center total_exame_periodico"></th>
                                            <th class="text-center total_exame_periodico"></th>
                                            <th class="text-center total_exame_periodico"></th>
                                            <th class="text-center total_exame_periodico"></th>
                                            <th class="text-center total_exame_periodico"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="treinamentos">

                                    <br>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h4 style="color: #111343;"><strong>Consolidado de Quadro de
                                                    Treinamentos</strong></h4>
                                        </div>
                                        <div class="col-md-7 text-right">
                                            <button type="button" id="ler_treinamentos" class="btn btn-info"
                                                    onclick="ler_treinamentos_atual()">Ler dados
                                            </button>
                                            <button type="button" id="salvar_treinamentos" class="btn btn-success"
                                                    onclick="salvar_treinamentos()"> Salvar
                                            </button>
                                            <button type="button" id="limpar_treinamentos" class="btn btn-danger"
                                                    onclick="limpar_treinamentos()"> Limpar
                                            </button>
                                        </div>
                                    </div>

                                    <table id="table_treinamentos" class="table table-bordered table-condensed"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="active">
                                            <th>Departamento (unidade de negócios)</th>
                                            <th class="text-center meses_treinamentos">Jan</th>
                                            <th class="text-center meses_treinamentos">Fev</th>
                                            <th class="text-center meses_treinamentos">Mar</th>
                                            <th class="text-center meses_treinamentos">Abr</th>
                                            <th class="text-center meses_treinamentos">Mai</th>
                                            <th class="text-center meses_treinamentos">Jun</th>
                                            <th class="text-center meses_treinamentos">Jul</th>
                                            <th class="text-center meses_treinamentos">Ago</th>
                                            <th class="text-center meses_treinamentos">Set</th>
                                            <th class="text-center meses_treinamentos">Out</th>
                                            <th class="text-center meses_treinamentos">Nov</th>
                                            <th class="text-center meses_treinamentos">Dez</th>
                                            <th>Média anual</th>
                                        </tr>
                                        <tr class="active">
                                            <th>Total</th>
                                            <th class="text-center total_treinamentos"></th>
                                            <th class="text-center total_treinamentos"></th>
                                            <th class="text-center total_treinamentos"></th>
                                            <th class="text-center total_treinamentos"></th>
                                            <th class="text-center total_treinamentos"></th>
                                            <th class="text-center total_treinamentos"></th>
                                            <th class="text-center total_treinamentos"></th>
                                            <th class="text-center total_treinamentos"></th>
                                            <th class="text-center total_treinamentos"></th>
                                            <th class="text-center total_treinamentos"></th>
                                            <th class="text-center total_treinamentos"></th>
                                            <th class="text-center total_treinamentos"></th>
                                            <th class="text-center total_treinamentos"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="demissoes_desligamentos">

                                    <br>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h4 style="color: #111343;"><strong>Consolidado de Entrevistas de
                                                    Demissões</strong></h4>
                                        </div>
                                        <div class="col-md-7 text-right">
                                            <button type="button" id="ler_demissoes" class="btn btn-info"
                                                    onclick="ler_demissoes_atual()">Ler dados
                                            </button>
                                            <button type="button" id="salvar_demissoes" class="btn btn-success"
                                                    onclick="salvar_demissoes()"> Salvar
                                            </button>
                                            <button type="button" id="limpar_demissoes" class="btn btn-danger"
                                                    onclick="limpar_demissoes()"> Limpar
                                            </button>
                                        </div>
                                    </div>

                                    <table id="table_demissoes_desligamentos"
                                           class="table table-bordered table-condensed"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="active">
                                            <th>Departamento (unidade de negócios)</th>
                                            <th class="text-center meses_demissoes">Jan</th>
                                            <th class="text-center meses_demissoes">Fev</th>
                                            <th class="text-center meses_demissoes">Mar</th>
                                            <th class="text-center meses_demissoes">Abr</th>
                                            <th class="text-center meses_demissoes">Mai</th>
                                            <th class="text-center meses_demissoes">Jun</th>
                                            <th class="text-center meses_demissoes">Jul</th>
                                            <th class="text-center meses_demissoes">Ago</th>
                                            <th class="text-center meses_demissoes">Set</th>
                                            <th class="text-center meses_demissoes">Out</th>
                                            <th class="text-center meses_demissoes">Nov</th>
                                            <th class="text-center meses_demissoes">Dez</th>
                                            <th>Média anual</th>
                                        </tr>
                                        <tr class="active">
                                            <th>Total</th>
                                            <th class="text-center total_demissoes"></th>
                                            <th class="text-center total_demissoes"></th>
                                            <th class="text-center total_demissoes"></th>
                                            <th class="text-center total_demissoes"></th>
                                            <th class="text-center total_demissoes"></th>
                                            <th class="text-center total_demissoes"></th>
                                            <th class="text-center total_demissoes"></th>
                                            <th class="text-center total_demissoes"></th>
                                            <th class="text-center total_demissoes"></th>
                                            <th class="text-center total_demissoes"></th>
                                            <th class="text-center total_demissoes"></th>
                                            <th class="text-center total_demissoes"></th>
                                            <th class="text-center total_demissoes"></th>
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
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->
<?php
require_once "end_js.php";
?>
    <!-- Css -->

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Relatários de Gestão - Gestão de Pessoal';
        });
    </script>


    <script>
        var save_method;
        var table_quadro_colaboradores, table_requisicoes_pessoal, table_turnover, table_afastamentos,
            table_faltas_atrasos, table_periodo_experiencia, table_exame_periodico, table_treinamentos,
            table_demissoes_desligamentos;

        var quadro_atual = false;
        var turnover_atual = false;
        var afastamentos_atual = false;
        var falta_atraso_atual = false;
        var experiencia_atual = false;
        var exame_periodico_atual = false;
        var treinamentos_atual = false;
        var demissoes_atual = false;


        $(document).ready(function () {

            table_quadro_colaboradores = $('#table_quadro_colaboradores').DataTable({
                'processing': true,
                'serverSide': true,
                'lengthChange': false,
                'iDisplayLength': -1,
                'searching': false,
                'ordering': false,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('gestaoDePessoal/ajaxListColaboradores/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('.filtro').serialize();
                        d.quadro_atual = quadro_atual === true ? 1 : 0;
                        return d;
                    },
                    'dataSrc': function (json) {
                        $.each(json.total, function (index, value) {
                            $(table_quadro_colaboradores.columns(index + 1).header(2)).html(value).removeClass('info text-info success text-success');
                        });
                        var coluna = $(table_quadro_colaboradores.columns(parseInt($('#mes').val())).header(2));
                        if (quadro_atual) {
                            coluna.addClass('info text-info');
                        } else {
                            coluna.addClass('success text-success');
                        }

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'width': '100%',
                        'targets': [0]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (parseInt($('#mes').val()) === col) {
                                if (quadro_atual) {
                                    $(td).addClass('info text-info');
                                } else {
                                    $(td).addClass('success text-success');
                                }
                            }
                            if (rowData[col] !== null) {
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        'className': 'text-center',
                        'targets': 'total_quadro_colaboradores'
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] !== null) {
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        'className': 'text-center',
                        'targets': [-1]
                    }
                ]
            });

            table_requisicoes_pessoal = $('#table_requisicoes_pessoal').DataTable({
                'processing': true,
                'serverSide': true,
                'lengthChange': false,
                'searching': false,
                'ordering': false,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('gestaoDePessoal/ajaxListRequisicoes/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('.filtro').serialize();
                        return d;
                    },
                    'dataSrc': function (json) {
                        $.each(json.total, function (index, value) {
                            $(table_requisicoes_pessoal.columns(index + 1).footer()).html(value);
                        });

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'className': 'text-center',
                        'width': '10%',
                        'targets': [1, 2, 3, 4, 5, 6, 7]
                    }
                ]
            });

            table_turnover = $('#table_turnover').DataTable({
                'processing': true,
                'serverSide': true,
                'lengthChange': false,
                'searching': false,
                'ordering': false,
                'info': false,
                'paging': false,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('gestaoDePessoal/ajaxListTurnover/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('.filtro').serialize();
                        d.turnover_atual = turnover_atual === true ? 1 : 0;
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if ([1, 2, 3, 4, 5].indexOf(row) >= 0) {
                                $(td).addClass('active');
                            }
                        },
                        'width': '100%',
                        'targets': [0]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            // if ([0, 6, 7, 8, 9].indexOf(row) >= 0) {
                            //     $(td).addClass('success text-success').css({
                            //         'cursor': 'pointer',
                            //         'vertical-align': 'middle'
                            //     }).on('click', function () {
                            //         edit_turnover(col, table_turnover.context[0].json.ano);
                            //     });
                            // }
                            if (parseInt($('#mes').val()) === col) {
                                if (turnover_atual) {
                                    $(td).addClass('info text-info');
                                } else {
                                    $(td).addClass('success text-success');
                                }
                            }
                            if ([1, 2, 3, 4, 5].indexOf(row) >= 0) {
                                $(td).addClass('active');
                            }
                            if (rowData[col] !== null) {
                                if ([1, 2, 3, 4, 5].indexOf(row) >= 0) {
                                    $(td).html('<strong>' + rowData[col] + '%</strong>');
                                } else {
                                    $(td).html('<strong>' + rowData[col] + '</strong>');
                                }
                            }
                        },
                        'className': 'text-center',
                        'targets': 'meses_turnover'
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if ([1, 2, 3, 4, 5].indexOf(row) >= 0) {
                                $(td).addClass('active');
                            }
                            if (rowData[col] !== null) {
                                if ([1, 2, 3, 4, 5].indexOf(row) >= 0) {
                                    $(td).html('<strong>' + rowData[col] + '%</strong>');
                                } else {
                                    $(td).html('<strong>' + rowData[col] + '</strong>');
                                }
                            }
                        },
                        'className': 'text-center',
                        'targets': [-1]
                    }
                ]
            });

            table_afastamentos = $('#table_afastamentos').DataTable({
                'processing': true,
                'serverSide': true,
                'lengthChange': false,
                'searching': false,
                'ordering': false,
                'info': false,
                'paging': true,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('gestaoDePessoal/ajaxListAfastamentos/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('.filtro').serialize();
                        d.afastamentos_atual = afastamentos_atual === true ? 1 : 0;
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if ([1, 2, 3, 4, 5].indexOf(row) >= 0) {
                                $(td).addClass('active');
                            }
                        },
                        'className': 'text-nowrap',
                        'width': '100%',
                        'targets': [0]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            // if ([7, 8, 9, 10].indexOf(row) >= 0) {
                            //     $(td).addClass('success text-success').css({
                            //         'cursor': 'pointer',
                            //         'vertical-align': 'middle'
                            //     }).on('click', function () {
                            //         edit_afastamentos(col, table_afastamentos.context[0].json.ano);
                            //     });
                            // }
                            if ([1, 2, 3, 4, 5].indexOf(row) >= 0) {
                                $(td).addClass('active');
                            }
                            if (parseInt($('#mes').val()) === col) {
                                if (afastamentos_atual) {
                                    $(td).addClass('info text-info');
                                } else {
                                    $(td).addClass('success text-success');
                                }
                            }
                            if (rowData[col] !== null) {
                                if ([1, 2, 3, 4, 5].indexOf(row) >= 0) {
                                    $(td).html('<strong>' + rowData[col] + '%</strong>');
                                } else {
                                    $(td).html('<strong>' + rowData[col] + '</strong>');
                                }
                            }
                        },
                        'className': 'text-center',
                        'targets': 'meses_afastamentos'
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if ([1, 2, 3, 4, 5].indexOf(row) >= 0) {
                                $(td).addClass('active');
                            }
                            if (rowData[col] !== null) {
                                if ([1, 2, 3, 4, 5].indexOf(row) >= 0) {
                                    $(td).html('<strong>' + rowData[col] + '%</strong>');
                                } else {
                                    $(td).html('<strong>' + rowData[col] + '</strong>');
                                }
                            }
                        },
                        'className': 'text-center',
                        'targets': [-1]
                    }
                ]
            });

            table_faltas_atrasos = $('#table_faltas_atrasos').DataTable({
                'processing': true,
                'serverSide': true,
                'lengthChange': false,
                'iDisplayLength': -1,
                'searching': false,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('gestaoDePessoal/ajaxListFaltasAtrasos/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('.filtro').serialize();
                        d.falta_atraso_atual = falta_atraso_atual === true ? 1 : 0;
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '100%',
                        'targets': [0]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (parseInt($('#mes').val()) === ((col + 1) / 2)) {
                                $(td).addClass('success text-success');
                            }
                            if (rowData[col] !== null) {
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        'className': 'text-center',
                        'orderable': false,
                        'targets': 'meses_faltas'
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (parseInt($('#mes').val()) === (col / 2)) {
                                $(td).addClass('success text-success');
                            }
                            if (rowData[col] !== null) {
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        'className': 'text-center',
                        'orderable': false,
                        'targets': 'meses_atrasos'
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] !== null) {
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        'className': 'text-center',
                        'orderable': false,
                        'targets': [-1, -2]
                    }
                ]
            });

            table_periodo_experiencia = $('#table_periodo_experiencia').DataTable({
                'processing': true,
                'serverSide': true,
                'lengthChange': false,
                'iDisplayLength': -1,
                'searching': false,
                'ordering': false,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('gestaoDePessoal/ajaxListPeriodoExperiencia/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('.filtro').serialize();
                        d.experiencia_atual = experiencia_atual === true ? 1 : 0;
                        return d;
                    },
                    'dataSrc': function (json) {
                        $.each(json.total, function (index, value) {
                            $(table_periodo_experiencia.columns(index + 1).header(2)).html(value).removeClass('info text-info success text-success');
                        });
                        var coluna = $(table_periodo_experiencia.columns(parseInt($('#mes').val())).header(2));
                        if (experiencia_atual) {
                            coluna.addClass('info text-info');
                        } else {
                            coluna.addClass('success text-success');
                        }

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'width': '100%',
                        'targets': [0]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (parseInt($('#mes').val()) === col) {
                                if (experiencia_atual) {
                                    $(td).addClass('info text-info');
                                } else {
                                    $(td).addClass('success text-success');
                                }
                            }
                            if (rowData[col] !== null) {
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        'className': 'text-center',
                        'targets': 'total_periodo_experiencia'
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] !== null) {
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        'className': 'text-center',
                        'targets': [-1]
                    }
                ]
            });

            table_exame_periodico = $('#table_exame_periodico').DataTable({
                'processing': true,
                'serverSide': true,
                'lengthChange': false,
                'iDisplayLength': -1,
                'searching': false,
                'ordering': false,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('gestaoDePessoal/ajaxListExamePeriodico/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('.filtro').serialize();
                        d.exame_periodico_atual = exame_periodico_atual === true ? 1 : 0;
                        return d;
                    },
                    'dataSrc': function (json) {
                        $.each(json.total, function (index, value) {
                            $(table_exame_periodico.columns(index + 1).header(2)).html(value).removeClass('info text-info success text-success');
                        });
                        var coluna = $(table_exame_periodico.columns(parseInt($('#mes').val())).header(2));
                        if (exame_periodico_atual) {
                            coluna.addClass('info text-info');
                        } else {
                            coluna.addClass('success text-success');
                        }

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'width': '100%',
                        'targets': [0]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (parseInt($('#mes').val()) === col) {
                                if (exame_periodico_atual) {
                                    $(td).addClass('info text-info');
                                } else {
                                    $(td).addClass('success text-success');
                                }
                            }
                            if (rowData[col] !== null) {
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        'className': 'text-center',
                        'targets': 'total_exame_periodico'
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] !== null) {
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        'className': 'text-center',
                        'targets': [-1]
                    }
                ]
            });

            table_treinamentos = $('#table_treinamentos').DataTable({
                'processing': true,
                'serverSide': true,
                'lengthChange': false,
                'iDisplayLength': -1,
                'searching': false,
                'ordering': false,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('gestaoDePessoal/ajaxListTreinamentos/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('.filtro').serialize();
                        d.treinamentos_atual = treinamentos_atual === true ? 1 : 0;
                        return d;
                    },
                    'dataSrc': function (json) {
                        $.each(json.total, function (index, value) {
                            $(table_treinamentos.columns(index + 1).header(2)).html(value).removeClass('info text-info success text-success');
                        });
                        var coluna = $(table_treinamentos.columns(parseInt($('#mes').val())).header(2));
                        if (treinamentos_atual) {
                            coluna.addClass('info text-info');
                        } else {
                            coluna.addClass('success text-success');
                        }

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'width': '100%',
                        'targets': [0]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (parseInt($('#mes').val()) === col) {
                                if (treinamentos_atual) {
                                    $(td).addClass('info text-info');
                                } else {
                                    $(td).addClass('success text-success');
                                }
                            }
                            if (rowData[col] !== null) {
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        'className': 'text-center',
                        'targets': 'total_treinamentos'
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] !== null) {
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        'className': 'text-center',
                        'targets': [-1]
                    }
                ]
            });

            table_demissoes_desligamentos = $('#table_demissoes_desligamentos').DataTable({
                'processing': true,
                'serverSide': true,
                'lengthChange': false,
                'iDisplayLength': -1,
                'searching': false,
                'ordering': false,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('gestaoDePessoal/ajaxListDemissoes/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('.filtro').serialize();
                        d.demissoes_atual = demissoes_atual === true ? 1 : 0;
                        return d;
                    },
                    'dataSrc': function (json) {
                        $.each(json.total, function (index, value) {
                            $(table_demissoes_desligamentos.columns(index + 1).header(2)).html(value).removeClass('info text-info success text-success');
                        });
                        var coluna = $(table_demissoes_desligamentos.columns(parseInt($('#mes').val())).header(2));
                        if (demissoes_atual) {
                            coluna.addClass('info text-info');
                        } else {
                            coluna.addClass('success text-success');
                        }

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'width': '100%',
                        'targets': [0]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (parseInt($('#mes').val()) === col) {
                                if (demissoes_atual) {
                                    $(td).addClass('info text-info');
                                } else {
                                    $(td).addClass('success text-success');
                                }
                            }
                            if (rowData[col] !== null) {
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        'className': 'text-center',
                        'targets': 'total_demissoes'
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] !== null) {
                                $(td).html('<strong>' + rowData[col] + '</strong>');
                            }
                        },
                        'className': 'text-center',
                        'targets': [-1]
                    }
                ]
            });

            setPdf_atributes();
        });

        // -------------------------------------------------------------------------

        // Ajusta a largura das colunas dos tabelas do tipo DataTables em uma aba
        $(document).on('shown.bs.tab', function () {
            $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
        });

        function reload_table() {
            ler_estrutura_atual();
            table_requisicoes_pessoal.ajax.reload(null, false);
            ler_faltas_atrasos();
            ler_turnover();
            ler_afastamentos();
            ler_experiencia();
            ler_exame_periodico();
            ler_treinamentos();
            ler_demissoes();
            setPdf_atributes();
        }

        function ler_estrutura_atual() {
            quadro_atual = false;
            $('#salvar_estrutura').prop('disabled', true);
            table_quadro_colaboradores.ajax.reload(null, false);
        }

        function ler_quadro_atual() {
            quadro_atual = true;
            $('#salvar_estrutura').prop('disabled', false);
            table_quadro_colaboradores.ajax.reload(null, false);
        }

        function ler_faltas_atrasos() {
            falta_atraso_atual = false;
            $('#salvar_faltas_atrasos').prop('disabled', true);
            table_faltas_atrasos.ajax.reload(null, false);
        }

        function ler_faltas_atrasos_atual() {
            falta_atraso_atual = true;
            $('#salvar_faltas_atrasos').prop('disabled', false);
            table_faltas_atrasos.ajax.reload(null, false);
        }

        function ler_turnover() {
            turnover_atual = false;
            $('#salvar_turnover').prop('disabled', true);
            table_turnover.ajax.reload(null, false);
        }

        function ler_turnover_atual() {
            turnover_atual = true;
            $('#salvar_turnover').prop('disabled', false);
            table_turnover.ajax.reload(null, false);
        }

        function ler_afastamentos() {
            afastamentos_atual = false;
            $('#salvar_afastamentos').prop('disabled', true);
            table_afastamentos.ajax.reload(null, false);
        }

        function ler_afastamentos_atual() {
            afastamentos_atual = true;
            $('#salvar_afastamentos').prop('disabled', false);
            table_afastamentos.ajax.reload(null, false);
        }

        function ler_experiencia() {
            experiencia_atual = false;
            $('#salvar_experiencia').prop('disabled', true);
            table_periodo_experiencia.ajax.reload(null, false);
        }

        function ler_experiencia_atual() {
            experiencia_atual = true;
            $('#salvar_experiencia').prop('disabled', false);
            table_periodo_experiencia.ajax.reload(null, false);
        }

        function ler_exame_periodico() {
            exame_periodico_atual = false;
            $('#salvar_exame_periodico').prop('disabled', true);
            table_exame_periodico.ajax.reload(null, false);
        }

        function ler_exame_periodico_atual() {
            exame_periodico_atual = true;
            $('#salvar_exame_periodico').prop('disabled', false);
            table_exame_periodico.ajax.reload(null, false);
        }

        function ler_treinamentos() {
            treinamentos_atual = false;
            $('#salvar_treinamentos').prop('disabled', true);
            table_treinamentos.ajax.reload(null, false);
        }

        function ler_treinamentos_atual() {
            treinamentos_atual = true;
            $('#salvar_treinamentos').prop('disabled', false);
            table_treinamentos.ajax.reload(null, false);
        }

        function ler_demissoes() {
            demissoes_atual = false;
            $('#salvar_demissoes').prop('disabled', true);
            table_demissoes_desligamentos.ajax.reload(null, false);
        }

        function ler_demissoes_atual() {
            demissoes_atual = true;
            $('#salvar_demissoes').prop('disabled', false);
            table_demissoes_desligamentos.ajax.reload(null, false);
        }

        $('#mes').on('change', function () {
            $('#ler_quadro').prop('disabled', this.value.length === 0);
            $('#ler_turnover').prop('disabled', this.value.length === 0);
            $('#ler_afastamentos').prop('disabled', this.value.length === 0);
            $('#ler_faltas_atrasos').prop('disabled', this.value.length === 0);
            $('#ler_experiencia').prop('disabled', this.value.length === 0);
            $('#ler_exame_periodico').prop('disabled', this.value.length === 0);
            $('#ler_treinamentos').prop('disabled', this.value.length === 0);
            $('#ler_demissoes').prop('disabled', this.value.length === 0);
            ler_estrutura_atual();
            ler_turnover();
            ler_afastamentos();
            ler_faltas_atrasos();
            ler_experiencia();
            ler_exame_periodico();
            ler_treinamentos();
            ler_demissoes();
        });

        // -------------------------------------------------------------------------

        function edit_quadro_colaboradores(id_depto, mes, ano) {
            $('#form_quadro_colaboradores')[0].reset();
            $.ajax({
                'url': "<?php echo site_url('gestaoDePessoal/ajaxEditEstruturas/') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_depto': id_depto,
                    'mes': mes,
                    'ano': ano
                },
                'success': function (json) {
                    $('#quadro_colaboradores_indicador').text(json.depto);
                    $('#quadro_colaboradores_mes_ano').text(('0' + mes).slice(-2) + '/' + ano);

                    $.each(json, function (key, value) {
                        $('#form_quadro_colaboradores [name="' + key + '"]').val(value);
                    });

                    save_method = (json.id !== null ? 'update' : 'add');
                    $('#modal_quadro_colaboradores').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_turnover(mes, ano) {
            $('#form_turnover')[0].reset();
            $.ajax({
                'url': "<?php echo site_url('gestaoDePessoal/ajaxEdit/') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'mes': mes,
                    'ano': ano
                },
                'success': function (json) {
                    $.each(json, function (key, value) {
                        $('#form_turnover [name="' + key + '"]').val(value);
                    });
                    $('#modal_turnover').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_afastamentos(mes, ano) {
            $('#form_afastamentos')[0].reset();
            $.ajax({
                'url': "<?php echo site_url('gestaoDePessoal/ajaxEdit/') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'mes': mes,
                    'ano': ano
                },
                'success': function (json) {
                    $('#afastamentos_mes_ano').text(('0' + mes).slice(-2) + '/' + ano);
                    $.each(json, function (key, value) {
                        $('#form_afastamentos [name="' + key + '"]').val(value);
                    });
                    $('#modal_afastamentos').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function importar_afastamentos() {
            $.ajax({
                'url': "<?php echo site_url('gestaoDePessoal/ajaxImportarAfastamentos/') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'mes': $('#form_afastamentos [name="mes"]').val(),
                    'ano': $('#form_afastamentos [name="ano"]').val()
                },
                'success': function (json) {
                    $.each(json, function (key, value) {
                        $('#form_afastamentos [name="' + key + '"]').val(value);
                    });
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function salvar_estruturas() {
            $('#salvar_estrutura').text('Salvando...').prop('disabled', true);

            $.ajax({
                'url': '<?php echo site_url('gestaoDePessoal/salvarEstruturas/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('.filtro').serialize(),
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else {
                        alert('Error get data from ajax');
                    }
                    $('#salvar_estrutura').text('Salvar').prop('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#salvar_estrutura').text('Salvar').prop('disabled', false);
                }
            });
        }


        function salvar_turnover() {
            $('#salvar_turnover').text('Salvando...').prop('disabled', true);
            $.ajax({
                'url': "<?php echo site_url('gestaoDePessoal/salvarTurnover/') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': $('.filtro').serialize(),
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else {
                        alert('Error get data from ajax');
                    }
                    $('#salvar_turnover').text('Salvar').prop('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#salvar_turnover').text('Salvar').prop('disabled', false);
                }
            });
        }

        function salvar_afastamentos() {
            $('#salvar_afastamentos').text('Salvando...').prop('disabled', true);
            $.ajax({
                'url': "<?php echo site_url('gestaoDePessoal/salvarAfastamentos/') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': $('.filtro').serialize(),
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else {
                        alert('Error get data from ajax');
                    }
                    $('#salvar_afastamentos').text('Salvar').prop('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#salvar_afastamentos').text('Salvar').prop('disabled', false);
                }
            });
        }

        function salvar_experiencia() {
            $('#salvar_experiencia').text('Salvando...').prop('disabled', true);
            $.ajax({
                'url': "<?php echo site_url('gestaoDePessoal/salvarPeriodoExperiencia/') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': $('.filtro').serialize(),
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else {
                        alert('Error get data from ajax');
                    }
                    $('#salvar_experiencia').text('Salvar').prop('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#salvar_experiencia').text('Salvar').prop('disabled', false);
                }
            });
        }

        function salvar_exame_periodico() {
            $('#salvar_exame_periodico').text('Salvando...').prop('disabled', true);
            $.ajax({
                'url': "<?php echo site_url('gestaoDePessoal/salvarExamePeriodico/') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': $('.filtro').serialize(),
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else {
                        alert('Error get data from ajax');
                    }
                    $('#salvar_exame_periodico').text('Salvar').prop('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#salvar_exame_periodico').text('Salvar').prop('disabled', false);
                }
            });
        }

        function salvar_treinamentos() {
            $('#salvar_treinamentos').text('Salvando...').prop('disabled', true);
            $.ajax({
                'url': "<?php echo site_url('gestaoDePessoal/salvarTreinamentos/') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': $('.filtro').serialize(),
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else {
                        alert('Error get data from ajax');
                    }
                    $('#salvar_treinamentos').text('Salvar').prop('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#salvar_treinamentos').text('Salvar').prop('disabled', false);
                }
            });
        }

        function salvar_demissoes() {
            $('#salvar_demissoes').text('Salvando...').prop('disabled', true);
            $.ajax({
                'url': "<?php echo site_url('gestaoDePessoal/salvarDemissoes/') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': $('.filtro').serialize(),
                'success': function (json) {
                    if (json.status) {
                        reload_table();
                    } else {
                        alert('Error get data from ajax');
                    }
                    $('#salvar_demissoes').text('Salvar').prop('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#salvar_demissoes').text('Salvar').prop('disabled', false);
                }
            });
        }


        function save_quadro_colaboradores() {
            $('#btnSaveQuadroColaboradores').text('Salvando...').prop('disabled', true);

            var url = '<?php echo site_url('gestaoDePessoal/ajaxAddEstruturas/') ?>';
            if (save_method == 'update') {
                url = '<?php echo site_url('gestaoDePessoal/ajaxSaveEstruturas/') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_quadro_colaboradores').serialize(),
                'success': function (json) {
                    if (json.status) {
                        $('#modal_quadro_colaboradores').modal('hide');
                        reload_table();
                    } else {
                        alert('Error get data from ajax');
                    }
                    $('#btnSaveQuadroColaboradores').text('Salvar').prop('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveQuadroColaboradores').text('Salvar').prop('disabled', false);
                }
            });
        }

        function save_turnover() {
            $('#btnSaveTurnover').text('Salvando...').prop('disabled', true);
            $.ajax({
                'url': "<?php echo site_url('gestaoDePessoal/ajaxSave/') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_turnover').serialize(),
                'success': function (json) {
                    if (json.status) {
                        $('#modal_turnover').modal('hide');
                        reload_table();
                    } else {
                        alert('Error get data from ajax');
                    }
                    $('#btnSaveTurnover').text('Salvar').prop('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveTurnover').text('Salvar').prop('disabled', false);
                }
            });
        }

        function save_afastamentos() {
            $('#btnSaveAfastamentos').text('Salvando...').prop('disabled', true);
            $.ajax({
                'url': "<?php echo site_url('gestaoDePessoal/ajaxSave/') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form_afastamentos').serialize(),
                'success': function (json) {
                    if (json.status) {
                        $('#modal_afastamentos').modal('hide');
                        reload_table();
                    } else {
                        alert('Error get data from ajax');
                    }
                    $('#btnSaveAfastamentos').text('Salvar').prop('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveAfastamentos').text('Salvar').prop('disabled', false);
                }
            });
        }

        function limpar_estruturas() {
            if (confirm('Deseja limpar o Quadro de Colaboradores no mês em destaque?')) {
                $('#limpar_estrutura').text('Limpando...').prop('disabled', true);

                $.ajax({
                    'url': '<?php echo site_url('gestaoDePessoal/limparEstruturas/') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': $('.filtro').serialize(),
                    'success': function (json) {
                        if (json.status) {
                            reload_table();
                        } else {
                            alert('Error get data from ajax');
                        }
                        $('#limpar_estrutura').text('Limpar').prop('disabled', false);
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                        $('#limpar_estrutura').text('Limpar').prop('disabled', false);
                    }
                });
            }
        }

        function limpar_turnover() {
            if (confirm('Deseja limpar a Movimentação de Pessoal no mês em destaque?')) {
                $('#limpar_turnover').text('Limpando...').prop('disabled', true);

                $.ajax({
                    'url': '<?php echo site_url('gestaoDePessoal/limparTurnover/') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': $('.filtro').serialize(),
                    'success': function (json) {
                        if (json.status) {
                            reload_table();
                        } else {
                            alert('Error get data from ajax');
                        }
                        $('#limpar_turnover').text('Limpar').prop('disabled', false);
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                        $('#limpar_turnover').text('Limpar').prop('disabled', false);
                    }
                });
            }
        }

        function limpar_afastamentos() {
            if (confirm('Deseja limpar o Quadro de Afastados no mês em destaque?')) {
                $('#limpar_afastamentos').text('Limpando...').prop('disabled', true);

                $.ajax({
                    'url': '<?php echo site_url('gestaoDePessoal/limparAfastamentos/') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': $('.filtro').serialize(),
                    'success': function (json) {
                        if (json.status) {
                            reload_table();
                        } else {
                            alert('Error get data from ajax');
                        }
                        $('#limpar_afastamentos').text('Limpar').prop('disabled', false);
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                        $('#limpar_afastamentos').text('Limpar').prop('disabled', false);
                    }
                });
            }
        }

        function limpar_experiencia() {
            if (confirm('Deseja limpar o Quadro de Período de Experiência no mês em destaque?')) {
                $('#limpar_experiencia').text('Limpando...').prop('disabled', true);

                $.ajax({
                    'url': '<?php echo site_url('gestaoDePessoal/limparPeriodoExperiencia/') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': $('.filtro').serialize(),
                    'success': function (json) {
                        if (json.status) {
                            reload_table();
                        } else {
                            alert('Error get data from ajax');
                        }
                        $('#limpar_experiencia').text('Limpar').prop('disabled', false);
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                        $('#limpar_experiencia').text('Limpar').prop('disabled', false);
                    }
                });
            }
        }

        function limpar_exame_periodico() {
            if (confirm('Deseja limpar o Quadro de Exame Periódico no mês em destaque?')) {
                $('#limpar_exame_periodico').text('Limpando...').prop('disabled', true);

                $.ajax({
                    'url': '<?php echo site_url('gestaoDePessoal/limparExamePeriodico/') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': $('.filtro').serialize(),
                    'success': function (json) {
                        if (json.status) {
                            reload_table();
                        } else {
                            alert('Error get data from ajax');
                        }
                        $('#limpar_exame_periodico').text('Limpar').prop('disabled', false);
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                        $('#limpar_exame_periodico').text('Limpar').prop('disabled', false);
                    }
                });
            }
        }

        function limpar_treinamentos() {
            if (confirm('Deseja limpar o Quadro de Treinamentos no mês em destaque?')) {
                $('#limpar_treinamentos').text('Limpando...').prop('disabled', true);

                $.ajax({
                    'url': '<?php echo site_url('gestaoDePessoal/limparTreinamentos/') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': $('.filtro').serialize(),
                    'success': function (json) {
                        if (json.status) {
                            reload_table();
                        } else {
                            alert('Error get data from ajax');
                        }
                        $('#limpar_treinamentos').text('Limpar').prop('disabled', false);
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                        $('#limpar_treinamentos').text('Limpar').prop('disabled', false);
                    }
                });
            }
        }

        function limpar_demissoes() {
            if (confirm('Deseja limpar o Quadro de Demissoes no mês em destaque?')) {
                $('#limpar_demissoes').text('Limpando...').prop('disabled', true);

                $.ajax({
                    'url': '<?php echo site_url('gestaoDePessoal/limparDemissoes/') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': $('.filtro').serialize(),
                    'success': function (json) {
                        if (json.status) {
                            reload_table();
                        } else {
                            alert('Error get data from ajax');
                        }
                        $('#limpar_demissoes').text('Limpar').prop('disabled', false);
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                        $('#limpar_demissoes').text('Limpar').prop('disabled', false);
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

            $('#pdf').prop('href', "<?= site_url('gestaoDePessoal/relatorio/'); ?>" + search);
        }
    </script>

<?php
require_once "end_html.php";
?>