<?php require_once 'header.php'; ?>

    <style>
        div.dataTables_wrapper div.dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200px;
            font-weight: bold;
            margin-left: -100px;
            margin-top: -26px;
            text-align: center;
            padding: 1em 0;
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
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <?php if ($nome_requisicao): ?>
                            <li><a href="<?= site_url('requisicaoPessoal') ?>">Gerenciar Requisições de Pessoal</a></li>
                            <li class="active"><?= $nome_requisicao ?></li>
                        <?php else: ?>
                            <li class="active">Gestão de cargos de processos seletivos</li>
                        <?php endif; ?>
                    </ol>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th><h4 class="text-primary"><strong>Dados da requisição
                                        n&ordm;: <?= $requisicao ?>
                                    </strong></h4></th>
                            <th><strong>Tempo para data limite (dias): <span
                                            class="<?= $dias_restantes < 0 ? 'text-danger' : ($dias_restantes > 0 ? 'text-success' : ''); ?>"><?= $dias_restantes ?></span></strong>
                            </th>
                            <th><strong>Número de
                                    vagas/contratados: <?= $numero_vagas . '/' . $numero_contratados ?></strong>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><strong>Data de abertura:</strong> <?= $data_abertura ?></td>
                            <td colspan="2"><strong>Requisitante:</strong> <?= $nome_requisitante ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Previsão de início:</strong> <?= $previsao_inicio ?></td>
                            <td colspan="2"><strong>Cargo/função:</strong> <?= $cargo_funcao ?></td>
                        </tr>
                        <tr>
                            <td><strong>Data de aprovação:</strong> <?= $data_aprovacao ?></td>
                            <td><strong>Tempo de aprovação (dias): <span
                                            class="<?= $tempo_aprovacao < 0 ? 'text-danger' : ($tempo_aprovacao > 0 ? 'text-success' : ''); ?>"><?= $tempo_aprovacao ?></span></strong>
                            </td>
                            <td><strong>Aprovado(a) por:</strong> <?= $aprovado_por ?></td>
                        </tr>
                        <tr>
                            <td><strong>SPA:</strong> <?= $spa ?></td>
                            <td colspan="2"><strong>Local de trabalho:</strong> <?= $local_trabalho ?></td>
                        </tr>
                        </tbody>
                    </table>


                    <div id="ocultar_colunas" class="form-inline">
                        <label>Ocultar as seguintes colunas: &nbsp;</label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="2" autocomplete="off"> Telefone
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="3" autocomplete="off"> E-mail
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="4" autocomplete="off"> Deficiência
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="5" autocomplete="off"> Fonte
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="6" autocomplete="off"> Status
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="11" autocomplete="off"> Pesquisa
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="17" autocomplete="off"> Testes Online
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="19" autocomplete="off"> Testes Presenciais
                        </label>
                        <label style="padding-left: 80px;">Filtrar por status: &nbsp;</label>
                        <select id="status" class="form-control input-sm" onchange="reload_table();">
                            <option value="">Todos</option>
                            <option value="A">Agendado</option>
                            <option value="P">Em processo</option>
                            <option value="A,P">Agendado + Em processo</option>
                            <option value="F">Fora do perfil</option>
                            <option value="N">Não atende ou recado</option>
                            <option value="S">Sem interesse</option>
                            <option value="I">Telefone errado ou inexistente</option>
                        </select>
                    </div>


                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr class="success">
                            <th colspan="7" class="text-center">Triagem</th>
                            <th colspan="4" class="text-center">Entrevista</th>
                            <th colspan="4" class="text-center">Pesquisa</th>
                            <th colspan="2" class="text-center">Contratação</th>
                            <th colspan="4" class="text-center">Seleção</th>
                        </tr>
                        <tr>
                            <th rowspan="2" class="text-center" style="vertical-align: middle;">Ação</th>
                            <th colspan="6">
                                <div class="form-inline">
                                    <button class="btn btn-sm btn-info" title="Adicionar candidato"
                                            onclick="add_candidato(<?= $requisicao ?>)"><i
                                                class="glyphicon glyphicon-plus"></i> Candidato
                                    </button>
                                    <label class="text-center" style="width:calc(100% - 100px);">Candidato</label>
                                </div>
                            </th>
                            <th colspan="2" class="text-center">Seleção</th>
                            <th colspan="2" class="text-center">Requisitante</th>
                            <th class="text-center">Antecedentes Criminais</th>
                            <th class="text-center">Restrições Financeiras</th>
                            <th colspan="2" class="text-center">Exame Médico Admissional</th>
                            <th rowspan="2" class="text-center" style="vertical-align: middle;">Contratar candidato</th>
                            <th rowspan="2" class="text-center" style="vertical-align: middle;">Data admissão</th>

                            <th colspan="2" class="text-center">Testes online</th>
                            <th colspan="2" class="text-center">Testes presenciais</th>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                            <th>Deficiência</th>
                            <th>Fonte</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Resultado</th>
                            <th>Data</th>
                            <th>Resultado</th>
                            <th>Resultado</th>
                            <th>Resultado</th>
                            <th>Data</th>
                            <th>Resultado</th>
                            <th>Nota</th>
                            <th>Ação</th>
                            <th>Nota</th>
                            <th>Ação</th>
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
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulario de cargo/função</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $requisicao ?>" name="id_recrutamento"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Nome do cargo/função</label>
                                        <div class="col-md-9">
                                            <input name="cargo" id="cargo" placeholder="Nome do cargo/função"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_candidato" role="dialog">
                <div class="modal-dialog modal-lg" style="width: 98%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <!--                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span-->
                            <!--                                        aria-hidden="true">&times;</span></button>-->
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="float:right;">
                                Fechar
                            </button>
                            <h4><strong>Selecione abaixo a origem dos candidatos para o processo de triagem</strong>
                            </h4>
                        </div>
                        <div class="modal-body form">
                            <ul class="nav nav-tabs" role="tablist"
                                style="font-size: small; font-weight: bolder;">
                                <li role="presentation" class="active">
                                    <a href="#novo_candidato" aria-controls="novo_candidato" role="tab"
                                       data-toggle="tab">Novo candidato</a>
                                </li>
                                <li role="presentation">
                                    <a href="#banco_novo" aria-controls="banco_novo" role="tab"
                                       data-toggle="tab">Banco Candidatos Cadastrados</a>
                                </li>
                                <li role="presentation">
                                    <a href="#banco_google" aria-controls="banco_google" role="tab"
                                       data-toggle="tab">Lista de candidatos potenciais</a>
                                </li>
                                <li role="presentation">
                                    <a href="#banco_interessados" aria-controls="banco_interessados" role="tab"
                                       data-toggle="tab">Lista de interessados na vaga</a>
                                </li>
                            </ul>
                            <br>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="novo_candidato">
                                    <form action="#" id="form_novo_candidato" class="form-horizontal"
                                          autocomplete="off">
                                        <input type="hidden" value="<?= $requisicao ?>" name="id_requisicao"/>
                                        <div class="row">
                                            <div class="col-sm-12 text-right">
                                                <button type="button" id="btnSaveNovoCandidato"
                                                        onclick="salvar_novo_candidato()"
                                                        class="btn btn-success">Salvar e incluir
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-body" style="padding-top: 0px;">
                                            <div class="row form-group">
                                                <div class="col-md-12">
                                                    <label class="control-label">Novo candidato a ser incluso no
                                                        processo seletivo<span class="text-danger"> *</span></label>
                                                    <input type="text" name="nome"
                                                           placeholder="Nome completo do novo candidato"
                                                           value="" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-md-6">
                                                    <label class="control-label">E-mail</label>
                                                    <input type="text" name="email" autocomplete="off"
                                                           placeholder="E-mail do novo candidato"
                                                           value="" class="form-control"/>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="control-label">Senha</label>
                                                    <input type="text" name="senha" autocomplete="new-password"
                                                           placeholder="Senha do novo candidato" value=""
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-md-4">
                                                    <label class="control-label">Telefone<span
                                                                class="text-danger"> *</span></label>
                                                    <input type="text" name="telefone"
                                                           placeholder="Telefone do novo candidato"
                                                           value="" class="form-control"/>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="control-label">Deficiência<span
                                                                class="text-danger"> *</span></label>
                                                    <select name="deficiencia" class="form-control">
                                                        <option value="">selecione...</option>
                                                        <option value="0">Sem deficiência</option>
                                                        <option value="7">Amputado (membro inferior)</option>
                                                        <option value="8">Amputado (membro superior)</option>
                                                        <option value="10">Auditiva (oralizado)</option>
                                                        <option value="2">Auditiva (surdo)</option>
                                                        <option value="17">Auditiva (unilateral)</option>
                                                        <option value="11">Cadeirante</option>
                                                        <option value="1">Física</option>
                                                        <option value="4">Intelectual</option>
                                                        <option value="5">Múltipla</option>
                                                        <option value="12">Não enquadra na lei</option>
                                                        <option value="13">Nanismo</option>
                                                        <option value="3">Visual (cego)</option>
                                                        <option value="14">Visual (monocular e subnormal)
                                                        </option>
                                                        <option value="15">Visual (monocular)</option>
                                                        <option value="16">Visual (subnormal)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="control-label">Selecione origem do
                                                        candidato<span class="text-danger"> *</span></label>
                                                    <select id="fonte_contratacao" name="fonte_contratacao"
                                                            class="form-control">
                                                        <option value="">selecione...</option>
                                                        <option value="BNE">BNE</option>
                                                        <option value="PAT">PAT</option>
                                                        <option value="Catho">Catho</option>
                                                        <option value="Curriculum">Curriculum</option>
                                                        <option value="Deficiente Online">Deficiente Online
                                                        </option>
                                                        <option value="Elancers">Elancers</option>
                                                        <option value="Email">Email</option>
                                                        <option value="Empregos">Empregos</option>
                                                        <option value="Facebook">Facebook</option>
                                                        <option value="Ihunter">Ihunter</option>
                                                        <option value="Indeed">Indeed</option>
                                                        <option value="Indicação">Indicação</option>
                                                        <option value="Infojobs">Infojobs</option>
                                                        <option value="Jornal">Jornal</option>
                                                        <option value="Linkedin">Linkedin</option>
                                                        <option value="Manager">Manager</option>
                                                        <option value="Outros">Outros</option>
                                                        <option value="PADEF/CAT">PADEF/CAT</option>
                                                        <option value="Selur">Selur</option>
                                                        <option value="SPA">SPA</option>
                                                        <option value="Trampos">Trampos</option>
                                                        <option value="Vagas">Vagas</option>
                                                        <option value="-1">outros...</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="banco_novo">
                                    <form action="#" id="form_banco_novo" class="form-horizontal">
                                        <div class="form-body" style="padding-top: 0px;">
                                            <input type="hidden" value="" name="id"/>
                                            <input type="hidden" value="<?= $requisicao ?>" name="id_requisicao"/>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel-group m-bot20" id="accordion">
                                                        <div class="well well-sm">
                                                            <div class="">
                                                                <a class="accordion-toggle" data-toggle="collapse"
                                                                   data-parent="#accordion" href="#collapseOne"
                                                                   style="height: 1px;">
                                                                    <span style="padding-left: 40%; font-weight: bold;"><i
                                                                                class="glyphicon glyphicon-search"></i>&ensp;Para realizar pesquisa avançada clique aqui</span>
                                                                </a>
                                                            </div>
                                                            <div id="collapseOne" class="panel-collapse collapse">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="col-md-2">
                                                                            <label class="control-label">Estado</label>
                                                                            <?php echo form_dropdown('estado', array(), '', 'id="estado" class="form-control filtro input-sm"'); ?>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="control-label">Cidade</label>
                                                                            <?php echo form_dropdown('cidade', array(), '', 'id="cidade" class="form-control filtro input-sm"'); ?>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <label>&nbsp;</label><br>
                                                                            <button type="button" id="limpa_filtro"
                                                                                    class="btn btn-default">
                                                                                Limpar
                                                                            </button>
                                                                        </div>
                                                                        <div class="col-md-3 text-right">
                                                                            <label>&nbsp;</label><br>
                                                                            <button type="button" id="btnSaveBancoNovo"
                                                                                    onclick="table_ame.ajax.reload(null, false);"
                                                                                    class="btn btn-info">Pesquisar
                                                                            </button>
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <label class="control-label">Bairro</label>
                                                                            <?php echo form_dropdown('bairro', array(), '', 'id="bairro" class="form-control filtro input-sm"'); ?>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <label class="control-label">Deficiência</label>
                                                                            <?php echo form_dropdown('deficiencia', array(), '', 'id="deficiencia" class="form-control filtro input-sm"'); ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-5">
                                                                            <label class="control-label">Escolaridade</label>
                                                                            <?php echo form_dropdown('escolaridade', $escolaridade, '', 'id="escolaridade" class="form-control filtro input-sm"'); ?>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label class="control-label">Sexo</label>
                                                                            <select id="sexo" name="sexo"
                                                                                    class="form-control filtro input-sm">
                                                                                <option value="">Todos</option>
                                                                                <option value="M">Masculino</option>
                                                                                <option value="F">Feminino</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <label class="control-label">Cargo/função
                                                                                processos
                                                                                passados</label>
                                                                            <select id="cargo_funcao"
                                                                                    name="cargo_funcao"
                                                                                    class="form-control filtro input-sm">
                                                                                <option value="">Todos</option>
                                                                                <option value="A">Selecionado</option>
                                                                                <option value="C">Contratado</option>
                                                                                <option value="S">Stand by</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-5">
                                                                            <label class="control-label">Resultado
                                                                                entrevista
                                                                                seleção</label>
                                                                            <select id="resultado_selecao"
                                                                                    name="resultado_selecao"
                                                                                    class="form-control filtro input-sm">
                                                                                <option value="">Todos</option>
                                                                                <option value="A">Selecionado</option>
                                                                                <option value="X">Aprovado</option>
                                                                                <option value="S">Stand by</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <label class="control-label">Resultado
                                                                                entrevista
                                                                                cliente</label>
                                                                            <select id="resultado_representante"
                                                                                    name="reultado_representante"
                                                                                    class="form-control filtro input-sm">
                                                                                <option value="">Todos</option>
                                                                                <option value="A">Selecionado</option>
                                                                                <option value="C">Contratado</option>
                                                                                <option value="S">Stand by</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="table-responsive">
                                                <div class="form-inline">
                                                    <label class="control-label">Ocultar as colunas abaixo:
                                                        &nbsp;</label>
<!--                                                    <label class="checkbox-inline">-->
<!--                                                        <input type="checkbox" class="toggle-vis" value="1"-->
<!--                                                               autocomplete="off"> Cliente-->
<!--                                                    </label>-->
<!--                                                    <label class="checkbox-inline">-->
<!--                                                        <input type="checkbox" class="toggle-vis" value="1"-->
<!--                                                               autocomplete="off"> Cargo-->
<!--                                                    </label>-->
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="1"
                                                               autocomplete="off"> Cidade
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="3"
                                                               autocomplete="off"> Deficiência
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="4"
                                                               autocomplete="off"> Telefone
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="5"
                                                               autocomplete="off"> E-mail
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="6"
                                                               autocomplete="off"> Fonte
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="7"
                                                               autocomplete="off"> Status
                                                    </label>
<!--                                                    <label class="checkbox-inline">-->
<!--                                                        <input type="checkbox" class="toggle-vis" value="10"-->
<!--                                                               autocomplete="off"> Data entrevista RH-->
<!--                                                    </label>-->
<!--                                                    <label class="checkbox-inline">-->
<!--                                                        <input type="checkbox" class="toggle-vis" value="12"-->
<!--                                                               autocomplete="off"> Data entrevista cliente-->
<!--                                                    </label>-->
<!--                                                    <label class="checkbox-inline">-->
<!--                                                        <input type="checkbox" class="toggle-vis" value="14"-->
<!--                                                               autocomplete="off"> Observações-->
<!--                                                    </label>-->
                                                </div>
                                                <hr style="margin-top: 10px; margin-bottom: 0px;">
                                                <table id="table_ame" class="table table-striped table-condensed"
                                                       cellspacing="0" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>Ação</th>
<!--                                                        <th>Cliente</th>-->
<!--                                                        <th>Cargo</th>-->
                                                        <th>Cidade</th>
                                                        <th>Nome do candidato</th>
                                                        <th>Deficiência</th>
                                                        <th>Telefone</th>
                                                        <th>E-mail</th>
                                                        <th>Fonte</th>
                                                        <th>Status</th>
<!--                                                        <th>Data entrevista RH</th>-->
<!--                                                        <th>Resultado entrevista RH</th>-->
<!--                                                        <th>Data entrevista cliente</th>-->
<!--                                                        <th>Resultado entrevista cliente</th>-->
<!--                                                        <th>Observações</th>-->
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--                                            <div class="row form-group">-->
                                            <!--                                                <div class="col-md-12">-->
                                            <!--                                                    --><?php //echo form_multiselect('id_usuario[]', array(), array(), 'id="id_usuario" class="form-control demo1"'); ?>
                                            <!--                                                </div>-->
                                            <!--                                            </div>-->
                                        </div>
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="banco_google">
                                    <form action="#" id="form_banco_google" class="form-horizontal">
                                        <div class="form-body" style="padding-top: 0px;">
                                            <input type="hidden" value="<?= $requisicao ?>" name="id_requisicao"/>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel-group m-bot20" id="accordion2">
                                                        <div class="well well-sm">
                                                            <div class="">
                                                                <a class="accordion-toggle" data-toggle="collapse"
                                                                   data-parent="#accordion2" href="#collapseTwo"
                                                                   style="height: 1px;">
                                                                    <span style="padding-left: 40%; font-weight: bold;"><i
                                                                                class="glyphicon glyphicon-search"></i>&ensp;Para realizar pesquisa avançada clique aqui</span>
                                                                </a>
                                                            </div>
                                                            <div id="collapseTwo" class="panel-collapse collapse">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label class="control-label">Cliente</label>
                                                                            <?php echo form_dropdown('cliente', array(), '', 'id="cliente_google" class="form-control filtro_google input-sm"'); ?>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="control-label">Cidade</label>
                                                                            <?php echo form_dropdown('cidade', array(), '', 'id="cidade_google" class="form-control filtro_google input-sm"'); ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label class="control-label">Cargo/função
                                                                                processos
                                                                                passados</label>
                                                                            <select name="cargo"
                                                                                    id="cargo_funcao_google"
                                                                                    class="form-control filtro_google input-sm">
                                                                                <option value="">Todos</option>
                                                                                <option value="A">Selecionado</option>
                                                                                <option value="C">Contratado</option>
                                                                                <option value="S">Stand by</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label class="control-label">Deficiência</label>
                                                                            <?php echo form_dropdown('deficiencia', array(), '', 'id="deficiencia_google" class="form-control filtro_google input-sm"'); ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <label class="control-label">Resultado
                                                                                entrevista
                                                                                seleção</label>
                                                                            <select name="resultado_entrevista_rh"
                                                                                    id="resultado_selecao_google"
                                                                                    class="form-control filtro_google input-sm">
                                                                                <option value="">Todos</option>
                                                                                <option value="A">Selecionado</option>
                                                                                <option value="X">Aprovado</option>
                                                                                <option value="S">Stand by</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label class="control-label">Resultado
                                                                                entrevista
                                                                                cliente</label>
                                                                            <select name="resultado_entrevista_cliente"
                                                                                    id="resultado_representante_google"
                                                                                    class="form-control filtro_google input-sm">
                                                                                <option value="">Todos</option>
                                                                                <option value="A">Selecionado</option>
                                                                                <option value="C">Contratado</option>
                                                                                <option value="S">Stand by</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <label>&nbsp;</label><br>
                                                                            <button type="button"
                                                                                    id="limpa_filtro_google"
                                                                                    class="btn btn-default">Limpar
                                                                            </button>
                                                                        </div>
                                                                        <div class="col-md-3 text-right">
                                                                            <label>&nbsp;</label><br>
                                                                            <button type="button"
                                                                                    id="btnSaveBancoGoogle"
                                                                                    onclick="table_google.ajax.reload(null, false);"
                                                                                    class="btn btn-info">pesquisar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="table-responsive">
                                                <div class="form-inline">
                                                    <label class="control-label">Ocultar as colunas abaixo:
                                                        &nbsp;</label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="1"
                                                               autocomplete="off"> Cliente
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="2"
                                                               autocomplete="off"> Cargo
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="3"
                                                               autocomplete="off"> Cidade
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="5"
                                                               autocomplete="off"> Deficiência
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="6"
                                                               autocomplete="off"> Telefone
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="7"
                                                               autocomplete="off"> E-mail
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="8"
                                                               autocomplete="off"> Fonte
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="9"
                                                               autocomplete="off"> Status
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="10"
                                                               autocomplete="off"> Data entrevista RH
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="12"
                                                               autocomplete="off"> Data entrevista cliente
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="14"
                                                               autocomplete="off"> Observações
                                                    </label>
                                                </div>
                                                <hr style="margin-top: 10px; margin-bottom: 0px;">
                                                <table id="table_google" class="table table-striped table-condensed"
                                                       cellspacing="0" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>Ação</th>
                                                        <th>Cliente</th>
                                                        <th>Cargo</th>
                                                        <th>Cidade</th>
                                                        <th>Nome do candidato</th>
                                                        <th>Deficiência</th>
                                                        <th>Telefone</th>
                                                        <th>E-mail</th>
                                                        <th>Fonte</th>
                                                        <th>Status</th>
                                                        <th>Data entrevista RH</th>
                                                        <th>Resultado entrevista RH</th>
                                                        <th>Data entrevista cliente</th>
                                                        <th>Resultado entrevista cliente</th>
                                                        <th>Observações</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="banco_interessados">
                                    <form action="#" id="form_banco_interessados" class="form-horizontal">
                                        <div class="form-body" style="padding-top: 0px;">
                                            <input type="hidden" value="" name="id"/>
                                            <input type="hidden" value="<?= $requisicao ?>" name="id_requisicao"/>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel-group m-bot20" id="accordion">
                                                        <div class="well well-sm">
                                                            <div class="">
                                                                <a class="accordion-toggle" data-toggle="collapse"
                                                                   data-parent="#accordion" href="#collapseThree"
                                                                   style="height: 1px;">
                                                                    <span style="padding-left: 40%; font-weight: bold;"><i
                                                                                class="glyphicon glyphicon-search"></i>&ensp;Para realizar pesquisa avançada clique aqui</span>
                                                                </a>
                                                            </div>
                                                            <div id="collapseThree" class="panel-collapse collapse">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="col-md-2">
                                                                            <label class="control-label">Estado</label>
                                                                            <?php echo form_dropdown('estado', array(), '', 'id="estado" class="form-control filtro input-sm"'); ?>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="control-label">Cidade</label>
                                                                            <?php echo form_dropdown('cidade', array(), '', 'id="cidade" class="form-control filtro input-sm"'); ?>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <label>&nbsp;</label><br>
                                                                            <button type="button"
                                                                                    id="limpa_filtro_interessados"
                                                                                    class="btn btn-default">
                                                                                Limpar
                                                                            </button>
                                                                        </div>
                                                                        <div class="col-md-3 text-right">
                                                                            <label>&nbsp;</label><br>
                                                                            <button type="button"
                                                                                    id="btnSaveBancoInteressados"
                                                                                    onclick="table_interessados.ajax.reload(null, false);"
                                                                                    class="btn btn-info">Pesquisar
                                                                            </button>
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <label class="control-label">Bairro</label>
                                                                            <?php echo form_dropdown('bairro', array(), '', 'id="bairro" class="form-control filtro input-sm"'); ?>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <label class="control-label">Deficiência</label>
                                                                            <?php echo form_dropdown('deficiencia', array(), '', 'id="deficiencia" class="form-control filtro input-sm"'); ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-5">
                                                                            <label class="control-label">Escolaridade</label>
                                                                            <?php echo form_dropdown('escolaridade', $escolaridade, '', 'id="escolaridade" class="form-control filtro input-sm"'); ?>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label class="control-label">Sexo</label>
                                                                            <select id="sexo" name="sexo"
                                                                                    class="form-control filtro input-sm">
                                                                                <option value="">Todos</option>
                                                                                <option value="M">Masculino</option>
                                                                                <option value="F">Feminino</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <label class="control-label">Cargo/função
                                                                                processos
                                                                                passados</label>
                                                                            <select id="cargo_funcao"
                                                                                    name="cargo_funcao"
                                                                                    class="form-control filtro input-sm">
                                                                                <option value="">Todos</option>
                                                                                <option value="A">Selecionado</option>
                                                                                <option value="C">Contratado</option>
                                                                                <option value="S">Stand by</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-5">
                                                                            <label class="control-label">Resultado
                                                                                entrevista
                                                                                seleção</label>
                                                                            <select id="resultado_selecao"
                                                                                    name="resultado_selecao"
                                                                                    class="form-control filtro input-sm">
                                                                                <option value="">Todos</option>
                                                                                <option value="A">Selecionado</option>
                                                                                <option value="X">Aprovado</option>
                                                                                <option value="S">Stand by</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <label class="control-label">Resultado
                                                                                entrevista
                                                                                cliente</label>
                                                                            <select id="resultado_representante"
                                                                                    name="reultado_representante"
                                                                                    class="form-control filtro input-sm">
                                                                                <option value="">Todos</option>
                                                                                <option value="A">Selecionado</option>
                                                                                <option value="C">Contratado</option>
                                                                                <option value="S">Stand by</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="table-responsive">
                                                <div class="form-inline">
                                                    <label class="control-label">Ocultar as colunas abaixo:
                                                        &nbsp;</label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="1"
                                                               autocomplete="off"> Cliente
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="2"
                                                               autocomplete="off"> Cargo
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="3"
                                                               autocomplete="off"> Cidade
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="5"
                                                               autocomplete="off"> Deficiência
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="6"
                                                               autocomplete="off"> Telefone
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="7"
                                                               autocomplete="off"> E-mail
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="8"
                                                               autocomplete="off"> Fonte
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="9"
                                                               autocomplete="off"> Status
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="10"
                                                               autocomplete="off"> Data entrevista RH
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="12"
                                                               autocomplete="off"> Data entrevista cliente
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="toggle-vis" value="14"
                                                               autocomplete="off"> Observações
                                                    </label>
                                                </div>
                                                <hr style="margin-top: 10px; margin-bottom: 0px;">
                                                <table id="table_interessados"
                                                       class="table table-striped table-condensed"
                                                       cellspacing="0" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>Ação</th>
                                                        <th>Cliente</th>
                                                        <th>Cargo</th>
                                                        <th>Cidade</th>
                                                        <th>Nome do candidato</th>
                                                        <th>Deficiência</th>
                                                        <th>Telefone</th>
                                                        <th>E-mail</th>
                                                        <th>Fonte</th>
                                                        <th>Status</th>
                                                        <th>Data entrevista RH</th>
                                                        <th>Resultado entrevista RH</th>
                                                        <th>Data entrevista cliente</th>
                                                        <th>Resultado entrevista cliente</th>
                                                        <th>Observações</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div class="modal fade" id="modal_observacoes" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Edição de observacoes</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_observacoes" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Candidato(a):</label>
                                        <div class="col-md-9">
                                            <p class="nome_candidato form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Observações:</label>
                                        <div class="col-md-8">
                                            <textarea name="observacoes" class="form-control" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveObservacoes" onclick="salvar_observacoes()"
                                    class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div class="modal fade" id="modal_status" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Edição de status</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_status" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Candidato(a):</label>
                                        <div class="col-md-9">
                                            <p class="nome_candidato form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Status:</label>
                                        <div class="col-md-8">
                                            <select name="status" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="A">Agendado</option>
                                                <option value="P">Em processo</option>
                                                <option value="F">Fora do perfil</option>
                                                <option value="N">Não atende ou recado</option>
                                                <option value="S">Sem interesse</option>
                                                <option value="I">Telefone errado ou inexistente</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveStatus" onclick="salvar_status()"
                                    class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_selecao" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Edição de entrevista de seleção</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_selecao" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Candidato(a):</label>
                                        <div class="col-md-9">
                                            <p class="nome_candidato form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Data:</label>
                                        <div class="col-md-3">
                                            <input name="data_selecao" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" autocomplete="off"
                                                   type="text">
                                        </div>
                                        <label class="control-label col-md-2">Hora:</label>
                                        <div class="col-md-2">
                                            <input name="hora_selecao" placeholder="hh:mm"
                                                   class="form-control text-center hora" autocomplete="off"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Resultado:</label>
                                        <div class="col-md-4">
                                            <select name="resultado_selecao" class="form-control input-sm">
                                                <option value="">selecione...</option>
                                                <option value="A">Selecionado</option>
                                                <option value="D">Desistiu</option>
                                                <option value="N">Não compareceu</option>
                                                <option value="X">Aprovado</option>
                                                <option value="R">Reprovado</option>
                                                <option value="S">Stand by</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveSelecao" onclick="salvar_selecao()"
                                    class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_requisitante" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Edição de entrevista de requisitante</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_requisitante" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Candidato(a):</label>
                                        <div class="col-md-9">
                                            <p class="nome_candidato form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Data:</label>
                                        <div class="col-md-3">
                                            <input name="data_requisitante" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" autocomplete="off"
                                                   type="text">
                                        </div>
                                        <label class="control-label col-md-2">Hora:</label>
                                        <div class="col-md-2">
                                            <input name="hora_requisitante" placeholder="hh:mm"
                                                   class="form-control text-center hora" autocomplete="off"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Resultado:</label>
                                        <div class="col-md-4">
                                            <select name="resultado_requisitante" class="form-control input-sm">
                                                <option value="">selecione...</option>
                                                <option value="A">Selecionado</option>
                                                <option value="C">Aprovado</option>
                                                <option value="D">Desistiu</option>
                                                <option value="N">Não compareceu</option>
                                                <option value="R">Reprovado</option>
                                                <option value="S">Stand by</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveRequisitante" onclick="salvar_requisitante()"
                                    class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_antecedentes" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Edição de antecedentes criminais</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_antecedentes" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Candidato(a):</label>
                                        <div class="col-md-7">
                                            <p class="nome_candidato form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Resultado:</label>
                                        <div class="col-md-4">
                                            <select name="antecedentes_criminais" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="0">Nada consta</option>
                                                <option value="1">Antecedentes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveAntecedentes" onclick="salvar_antecedentes()"
                                    class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_restricoes" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Edição de restrições financeirtas</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_restricoes" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Candidato(a):</label>
                                        <div class="col-md-7">
                                            <p class="nome_candidato form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Restrições financeiras:</label>
                                        <div class="col-md-4">
                                            <select name="restricoes_financeiras" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="0">Sem restrições</option>
                                                <option value="1">Com restrições</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveRestricoes" onclick="salvar_restricoes()"
                                    class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_exame_admissional" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Edição de exame admissional</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_exame_admissional" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Candidato(a):</label>
                                        <div class="col-md-7">
                                            <p class="nome_candidato form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Data do exame:</label>
                                        <div class="col-md-3">
                                            <input name="data_exame_admissional" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" autocomplete="off"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Resultado:</label>
                                        <div class="col-md-4">
                                            <select name="resultado_exame_admissional" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="1">Apto</option>
                                                <option value="0">Não apto</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveExameAdmissional" onclick="salvar_exame_admissional()"
                                    class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_admissao" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Edição de data de admissão</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_admissao" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Candidato(a):</label>
                                        <div class="col-md-7">
                                            <p class="nome_candidato form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Data de admissão:</label>
                                        <div class="col-md-3">
                                            <input name="data_admissao" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" autocomplete="off"
                                                   type="text">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveAdmissao" onclick="salvar_admissao()"
                                    class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

        </section>
    </section>
    <!--main content end-->

<?php require_once 'end_js.php'; ?>

    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Avaliações por período de experiência - ';
        });</script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>
        var save_method;
        var table, table_google, table_interessados;
        var demo1;
        var candidato;


        $(document).ready(function () {
            $('.data').mask('00/00/0000');
            $('[name="idade"]').mask('000');
            $('.hora').mask('00:00');
            $('.valor').mask('##0,0', {'reverse': true});

            //datatables
            table_ame = $('#table_ame').DataTable({
                'processing': true,
                'serverSide': true,
                'ordering': false,
                'iDisplayLength': 50,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>',
                    'searchPlaceholder': 'Nome do candidato'
                },
                'ajax': {
                    'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajaxListAme/' . $requisicao) ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#form_banco_novo').serialize();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'className': 'text-nowrap',
                        'targets': [0]
                    }
                ]
            });

            table_google = $('#table_google').DataTable({
                'processing': true,
                'serverSide': true,
                'ordering': false,
                'iDisplayLength': 50,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>',
                    'searchPlaceholder': 'Nome do candidato'
                },
                'ajax': {
                    'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajaxListBanco/' . $requisicao) ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#form_banco_google').serialize();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'className': 'text-nowrap',
                        'targets': [0]
                    }
                ]
            });

            table_interessados = $('#table_interessados').DataTable({
                'processing': true,
                'serverSide': true,
                'ordering': false,
                'iDisplayLength': 50,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>',
                    'searchPlaceholder': 'Nome do candidato'
                },
                'ajax': {
                    'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajaxListInteressados/' . $requisicao) ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#form_banco_interessados').serialize();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'className': 'text-nowrap',
                        'targets': [0]
                    }
                ]
            });

            table = $('#table').DataTable({
                'info': false,
                'processing': true,
                'serverSide': true,
                'lengthChange': false,
                'searching': false,
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_listCandidatos/' . $requisicao) ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.status = $('#status').val();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'className': 'text-nowrap',
                        'targets': [0]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[23] !== null) {
                                $(td).css('cursor', 'pointer');
                                $(td).attr('title', 'Ver cadastro de ' + $(td).text());
                                $(td).on('click', function () {
                                    location.href = $(cellData).prop('href');
                                });
                            }
                        },
                        'targets': [1]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).css('cursor', 'pointer');
                            $(td).attr('title', 'mailto: ' + $(td).text());
                            $(td).on('click', function () {
                                location.href = $(cellData).prop('href');
                            });
                        },
                        'targets': [3]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] === null) {
                                $(td).css('background-color', '#ff0');
                            }
                            $(td).css('cursor', 'pointer');
                            $(td).on('click', function () {
                                $('#form_status .nome_candidato').html(rowData[24]);
                                $('#form_status [name="id"]').val(rowData[21]);
                                $('#form_status [name="status"]').val(rowData[22]);
                                $('#modal_status').modal('show');
                            });
                        },
                        'targets': [6]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] === null) {
                                $(td).css('background-color', '#ff0');
                            }
                            $(td).css('cursor', 'pointer');
                            $(td).on('click', function () {
                                $('#form_selecao .nome_candidato').html(rowData[24]);
                                $('#form_selecao [name="id"]').val(rowData[21]);
                                var data_selecao = rowData[7] !== null ? rowData[7].split(' ')[0] : '';
                                var hora_selecao = rowData[7] !== null ? rowData[7].split(' ')[1] : '';
                                $('#form_selecao [name="data_selecao"]').val(data_selecao !== undefined ? data_selecao : '');
                                $('#form_selecao [name="hora_selecao"]').val(hora_selecao !== undefined ? hora_selecao : '');
                                $('#form_selecao [name="resultado_selecao"]').val($('#form_selecao [name="resultado_selecao"] option:contains(' + rowData[8] + ')').val());
                                $('#modal_selecao').modal('show');
                            });
                        },
                        'targets': [7, 8]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] === null) {
                                $(td).css('background-color', '#ff0');
                            }
                            $(td).css('cursor', 'pointer');
                            $(td).on('click', function () {
                                $('#form_requisitante .nome_candidato').html(rowData[24]);
                                $('#form_requisitante [name="id"]').val(rowData[21]);
                                var data_requisitante = rowData[9] !== null ? rowData[9].split(' ')[0] : '';
                                var hora_requisitante = rowData[9] !== null ? rowData[9].split(' ')[1] : '';
                                $('#form_requisitante [name="data_requisitante"]').val(data_requisitante !== undefined ? data_requisitante : '');
                                $('#form_requisitante [name="hora_requisitante"]').val(hora_requisitante !== undefined ? hora_requisitante : '');
                                $('#form_requisitante [name="resultado_requisitante"]').val($('#form_requisitante [name="resultado_requisitante"] option:contains(' + rowData[10] + ')').val());
                                $('#modal_requisitante').modal('show');
                            });
                        },
                        'targets': [9, 10]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var antecedentes = '';
                            if (rowData[col] === null) {
                                $(td).css('background-color', '#ff0');
                            } else if (rowData[11] === 'Nada consta') {
                                antecedentes = 0;
                            } else if (rowData[11] === 'Antecedentes') {
                                $(td).css({'background-color': '#f00', 'color': '#fff'});
                                antecedentes = 1;
                            }
                            $(td).css('cursor', 'pointer');
                            $(td).on('click', function () {
                                $('#form_antecedentes .nome_candidato').html(rowData[24]);
                                $('#form_antecedentes [name="id"]').val(rowData[21]);

                                $('#form_antecedentes [name="antecedentes_criminais"]').val(antecedentes);
                                $('#modal_antecedentes').modal('show');
                            });
                        },
                        'targets': [11]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var restricoes = '';
                            if (rowData[col] === null) {
                                $(td).css('background-color', '#ff0');
                            } else if (rowData[12] === 'Sem restrições') {
                                restricoes = 0;
                            } else if (rowData[12] === 'Com restrições') {
                                $(td).css({'background-color': '#f00', 'color': '#fff'});
                                restricoes = 1;
                            }
                            $(td).css('cursor', 'pointer');
                            $(td).on('click', function () {
                                $('#form_restricoes .nome_candidato').html(rowData[24]);
                                $('#form_restricoes [name="id"]').val(rowData[21]);

                                $('#form_restricoes [name="restricoes_financeiras"]').val(restricoes);
                                $('#modal_restricoes').modal('show');
                            });
                        },
                        'targets': [12]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var exameAdmissional = '';
                            if (rowData[col] === null) {
                                $(td).css('background-color', '#ff0');
                            } else if (rowData[14] === 'Não apto') {
                                $(td).css('background-color', '#f00');
                                exameAdmissional = 0;
                            } else if (rowData[14] === 'Apto') {
                                exameAdmissional = 1;
                            }
                            $(td).css('cursor', 'pointer');
                            $(td).on('click', function () {
                                $('#form_exame_admissional .nome_candidato').html(rowData[24]);
                                $('#form_exame_admissional [name="id"]').val(rowData[21]);
                                $('#form_exame_admissional [name="data_exame_admissional"]').val(rowData[13]);
                                $('#form_exame_admissional [name="resultado_exame_admissional"]').val(exameAdmissional);
                                $('#modal_exame_admissional').modal('show');
                            });
                        },
                        'targets': [13, 14]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] === null) {
                                $(td).css('background-color', '#ff0');
                            }
                            $(td).css('cursor', 'pointer');
                            $(td).on('click', function () {
                                $('#form_admissao .nome_candidato').html(rowData[24]);
                                $('#form_admissao [name="id"]').val(rowData[21]);
                                $('#form_admissao [name="data_admissao"]').val(rowData[16]);
                                $('#modal_admissao').modal('show');
                            });
                        },
                        'targets': [16]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] === null) {
                                $(td).css('background-color', '#ff0');
                            }
                        },
                        'targets': [4, 6, 7, 13]
                    },
                    {
                        'className': 'text-center',
                        'targets': [17, 19]
                    },
                    {
                        'orderable': false,
                        'className': 'text-center',
                        'targets': [0, 15, 18, 20]
                    },
                    {
                        'width': '100%',
                        'targets': [1]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [18, 20],
                        'orderable': false,
                        'searchable': false
                    }
                ]
            });

            demo1 = $('.demo1').bootstrapDualListbox({
                'nonSelectedListLabel': 'Candidatos disponíveis no banco de candidatos',
                'selectedListLabel': 'Candidatos requisitados ao processo seletivo <span class="text-danger">*</span>',
                'preserveSelectionOnMove': 'moved',
                'moveOnSelect': false,
                'filterPlaceHolder': 'Filtrar',
                'helperSelectNamePostfix': false,
                'selectorMinimalHeight': 132,
                'infoText': false
            });

            $('#ocultar_colunas input.toggle-vis').on('change', function (e) {
                var value = parseInt($(this).val());
                var column = table.column(value);
                column.visible(!column.visible());
                if (value === 11) {
                    var column = table.column(value + 1);
                    var column2 = table.column(value + 2);
                    var column3 = table.column(value + 3);
                    column.visible(!column.visible());
                    column2.visible(!column2.visible());
                    column3.visible(!column3.visible());
                } else if (value === 17 || value === 19) {
                    var column = table.column(value + 1);
                    column.visible(!column.visible());
                }
            });

            $('#banco_novo input.toggle-vis').on('change', function (e) {
                var column = table_ame.column($(this).val());
                column.visible(!column.visible());
            });

            $('#banco_google input.toggle-vis').on('change', function (e) {
                var column = table_google.column($(this).val());
                column.visible(!column.visible());
            });

            $('#banco_interessados input.toggle-vis').on('change', function (e) {
                var column = table_interessados.column($(this).val());
                column.visible(!column.visible());
            });
        });


        $('#id_usuario').on('change', function () {
            candidato = $(this).val();
        });

        $('.filtro').on('change', function () {
            filtra_candidatos_banco();
        });

        $('.filtro_google').on('change', function () {
            filtra_candidatos_google();
        });

        $('#limpa_filtro').on('click', function () {
            $('.filtro').val('');
            filtra_candidatos_banco();
        });

        $('#limpa_filtro_google').on('click', function () {
            $('.filtro_google').val('');
            filtra_candidatos_google();
        });


        function filtra_candidatos_banco() {
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_candidatos') ?>',
                'type': 'POST',
                'dataType': 'json',
                'async': true,
                'data': {
                    'id_requisicao': $('[name="id_requisicao"]').val(),
                    'estado': $('#estado').val(),
                    'cidade': $('#cidade').val(),
                    'cliente': null,
                    'bairro': $('#bairro').val(),
                    'deficiencia': $('#deficiencia').val(),
                    'escolaridade': $('#escolaridade').val(),
                    'sexo': $('#sexo').val(),
                    'cargo_funcao': $('#cargo_funcao').val(),
                    'resultado_selecao': $('#resultado_selecao').val(),
                    'resultado_representante': $('#resultado_representante').val()
                },
                'success': function (json) {
                    $('#estado').html($(json.estados).html());
                    $('#cidade').html($(json.cidades).html());
                    $('#bairro').html($(json.bairros).html());
                    $('#deficiencia').html($(json.deficiencias).html());
                    $('#fonte_contratacao').html($(json.fonte_contratacao).html());
                    $('#escolaridade').html($(json.escolaridade).html());
                    $('#cargo_funcao').html($(json.cargo_funcao).html());
                    $('#id_usuario').html($(json.candidatos).html());
                    demo1.bootstrapDualListbox('refresh', true);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function filtra_candidatos_google() {
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_bancos') ?>',
                'type': 'POST',
                'dataType': 'json',
                'async': true,
                'data': {
                    'id_requisicao': $('[name="id_requisicao"]').val(),
                    'cidade': $('#cidade_google').val(),
                    'cliente': $('#cliente_google').val(),
                    'cargo': $('#cargo_funcao_google').val(),
                    'deficiencia': $('#deficiencia_google').val(),
                    'resultado_entrevista_rh': $('#resultado_selecao_google').val(),
                    'resultado_entrevista_cliente': $('#resultado_representante_google').val(),
                },
                'success': function (json) {
                    $('#cliente_google').html($(json.clientes).html());
                    $('#cidade_google').html($(json.cidades).html());
                    $('#deficiencia_google').html($(json.deficiencias).html());
                    $('#cargo_funcao_google').html($(json.cargos).html());
                    $('#resultado_selecao_google').html($(json.resultados_rh).html());
                    $('#resultado_representante_google').html($(json.resultados_cli).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function add_cargo() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_recrutamento"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('#modal_form .modal-title').text('Adicionar cargo/função'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function add_candidato(id) {
            $('#form_novo_candidato, #form_banco_novo, #form_banco_santander, #form_banco_google')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('[name="id_requisicao"]').val(id);
            $('#limpa_filtro').trigger('click');
            filtra_candidatos_banco();
            $('#limpa_filtro_google').trigger('click');
            table_google.ajax.reload(null, false);
            $('#form_novo_candidato input[name="email"]').val('').css('background-color', '#ffffff');
            $('#form_novo_candidato input[name="senha"]').val('').css('background-color', '#ffffff');
            $('#modal_candidato').modal('show'); // show bootstrap modal
            $('.combo_nivel1').hide();
        }

        function edit_observacoes(nome_candidato, id_candidato, observacoes) {
            $('#form_observacoes .nome_candidato').html(nome_candidato);
            $('#form_observacoes [name="id"]').val(id_candidato);
            $('#form_observacoes [name="observacoes"]').val(observacoes);
            $('#modal_observacoes').modal('show');
        }

        function edit_cargo(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_recrutamento"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_edit') ?>' + id,
                'type': 'GET',
                'dataType': 'json',
                'success': function (data) {
                    $('[name="id"]').val(data.id);
                    $('[name="id_recrutamento"]').val(data.id_recrutamento);
                    $('[name="cargo"]').val(data.cargo);

                    $('#modal_form').modal('show');
                    $('#modal_form .modal-title').text('Editar cargo/função'); // Set title to Bootstrap modal title
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
            table_ame.ajax.reload(null, false);
            table_google.ajax.reload(null, false);
            table_interessados.ajax.reload(null, false);
        }

        function save() {
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url;
            if (save_method === 'add') {
                url = "<?php echo site_url('recrutamentoPresencial_cargos/ajax_add') ?>";
            } else {
                url = "<?php echo site_url('recrutamentoPresencial_cargos/ajax_update') ?>";
            }

            // ajax adding data to database
            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }

                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }

        function salvar_novo_candidato() {
            $('#btnSaveNovoCandidato').text('Salvando...').attr('disabled', true);

            // ajax adding data to database
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_addCandidatoNovo') ?>',
                'type': 'POST',
                'data': $('#form_novo_candidato').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_candidato').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveNovoCandidato').text('Salvar e incluir').attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveNovoCandidato').text('Salvar e incluir').attr('disabled', false);
                }
            });
        }

        function salvar_banco_novo(id) {
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_addCandidato') ?>',
                'type': 'POST',
                'data': {
                    'id': id,
                    'id_requisicao': $('#form_banco_novo [name="id_requisicao"]').val()
                },
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveBancoNovo').text('Salvar').attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveBancoNovo').text('Salvar').attr('disabled', false);
                }
            });
        }

        function salvar_banco_google(id) {
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_addBanco') ?>',
                'type': 'POST',
                'data': {
                    'id': id,
                    'id_requisicao': $('#form_banco_google [name="id_requisicao"]').val()
                },
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveStatus').text('Salvar'); //change button text
                    $('#btnSaveStatus').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveStatus').text('Salvar'); //change button text
                    $('#btnSaveStatus').attr('disabled', false); //set button enable
                }
            });
        }

        function salvar_interessado(id) {
            $('#btnSaveBancoInteressados').text('Salvando...').attr('disabled', true); //set button disable
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_addInteressado') ?>',
                'type': 'POST',
                'data': {
                    'id': id
                },
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveBancoInteressados').text('Salvar'); //change button text
                    $('#btnSaveBancoInteressados').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveBancoInteressados').text('Salvar'); //change button text
                    $('#btnSaveBancoInteressados').attr('disabled', false); //set button enable
                }
            });
        }

        function salvar_observacoes() {
            $('#btnSaveObservacoes').text('Salvando...'); //change button text
            $('#btnSaveObservacoes').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_updateCandidato') ?>',
                'type': 'POST',
                'data': $('#form_observacoes').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_observacoes').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveObservacoes').text('Salvar'); //change button text
                    $('#btnSaveObservacoes').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveObservacoes').text('Salvar'); //change button text
                    $('#btnSaveObservacoes').attr('disabled', false); //set button enable
                }
            });
        }

        function salvar_status() {
            $('#btnSaveStatus').text('Salvando...'); //change button text
            $('#btnSaveStatus').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_updateCandidato') ?>',
                'type': 'POST',
                'data': $('#form_status').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_status').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveStatus').text('Salvar'); //change button text
                    $('#btnSaveStatus').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveStatus').text('Salvar'); //change button text
                    $('#btnSaveStatus').attr('disabled', false); //set button enable
                }
            });
        }

        function salvar_selecao() {
            $('#btnSaveSelecao').text('Salvando...'); //change button text
            $('#btnSaveSelecao').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_updateCandidato') ?>',
                'type': 'POST',
                'data': $('#form_selecao').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_selecao').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveSelecao').text('Salvar'); //change button text
                    $('#btnSaveSelecao').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveSelecao').text('Salvar'); //change button text
                    $('#btnSaveSelecao').attr('disabled', false); //set button enable
                }
            });
        }

        function salvar_requisitante() {
            $('#btnSaveRequisitante').text('Salvando...'); //change button text
            $('#btnSaveRequisitante').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_updateCandidato') ?>',
                'type': 'POST',
                'data': $('#form_requisitante').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_requisitante').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveRequisitante').text('Salvar'); //change button text
                    $('#btnSaveRequisitante').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveRequisitante').text('Salvar'); //change button text
                    $('#btnSaveRequisitante').attr('disabled', false); //set button enable
                }
            });
        }

        function salvar_antecedentes() {
            $('#btnSaveAntecedentes').text('Salvando...'); //change button text
            $('#btnSaveAntecedentes').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_updateCandidato') ?>',
                'type': 'POST',
                'data': $('#form_antecedentes').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_antecedentes').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveAntecedentes').text('Salvar'); //change button text
                    $('#btnSaveAntecedentes').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveAntecedentes').text('Salvar'); //change button text
                    $('#btnSaveAntecedentes').attr('disabled', false); //set button enable
                }
            });
        }

        function salvar_restricoes() {
            $('#btnSaveRestricoes').text('Salvando...'); //change button text
            $('#btnSaveRestricoes').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_updateCandidato') ?>',
                'type': 'POST',
                'data': $('#form_restricoes').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_restricoes').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveRestricoes').text('Salvar'); //change button text
                    $('#btnSaveRestricoes').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveRestricoes').text('Salvar'); //change button text
                    $('#btnSaveRestricoes').attr('disabled', false); //set button enable
                }
            });
        }

        function salvar_exame_admissional() {
            $('#btnSaveExameAdmissional').text('Salvando...'); //change button text
            $('#btnSaveExameAdmissional').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_updateCandidato') ?>',
                'type': 'POST',
                'data': $('#form_exame_admissional').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_exame_admissional').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveExameAdmissional').text('Salvar'); //change button text
                    $('#btnSaveExameAdmissional').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveExameAdmissional').text('Salvar'); //change button text
                    $('#btnSaveExameAdmissional').attr('disabled', false); //set button enable
                }
            });
        }

        function salvar_admissao() {
            $('#btnSaveAdmissao').text('Salvando...'); //change button text
            $('#btnSaveAdmissao').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_updateCandidato') ?>',
                'type': 'POST',
                'data': $('#form_admissao').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_admissao').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveAdmissao').text('Salvar'); //change button text
                    $('#btnSaveAdmissao').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveAdmissao').text('Salvar'); //change button text
                    $('#btnSaveAdmissao').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_cargo(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_delete') ?>/' + id,
                    'type': 'POST',
                    'dataType': 'json',
                    'success': function (data) {
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

        function delete_candidato(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    'url': '<?php echo site_url('recrutamentoPresencial_cargos/ajax_deleteCandidato') ?>/' + id,
                    'type': 'POST',
                    'dataType': 'json',
                    'success': function (data) {
                        //if success reload ajax table
                        $('#modal_candidato').modal('hide');
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

        function forcar_aprovacao_candidato(id_candidato) {
            if (confirm('Candidato não apto pelo exame médico e/ou com antecedentes criminais.\nDeseja contratar assim mesmo?')) {
                aprovar_candidato(id_candidato);
            }
        }

        function aprovar_candidato(id_candidato) {
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/aprovarCandidato') ?>/' + id_candidato,
                'type': 'POST',
                'dataType': 'json',
                'success': function () {
                    alert('Candidato(a) contratado(a)');
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
        }

        function desaprovar_candidato(id_candidato) {
            $.ajax({
                'url': '<?php echo site_url('recrutamentoPresencial_cargos/desaprovarCandidato') ?>/' + id_candidato,
                'type': 'POST',
                'dataType': 'json',
                'success': function () {
                    alert('Candidato(a) não-contratado(a)');
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
        }

        function documentos(id_candidato) {
            var logado = <?php echo $this->session->userdata('logado') ? 'true' : 'false'; ?>;
            if (logado) {
                window.open("<?php echo site_url('requisicaoPessoal_documentos/candidato'); ?>/" + id_candidato, 'Alunos', 'STATUS=NO, TOOLBAR=NO, LOCATION=NO, DIRECTORIES=NO, RESISABLE=NO, SCROLLBARS=YES, TOP=80, LEFT=210, WIDTH=1050, HEIGHT=560');
            } else {
                window.open("<?php echo site_url('home/sair'); ?>");
            }
        }

        function detalhes_candidato(id) {
            var logado = <?php echo $this->session->userdata('logado') ? 'true' : 'false'; ?>;
            if (logado) {
                window.open("<?php echo site_url('recrutamento_candidatos/visualizarPerfil'); ?>/" + id, 'Candidato', 'TITLEBAR=NO,LOCATION=NO,STATUS=NO, TOOLBAR=NO, LOCATION=NO, DIRECTORIES=NO, RESISABLE=NO, SCROLLBARS=YES, TOP=80, LEFT=180, WIDTH=1130, HEIGHT=560');
            } else {
                window.open("<?php echo site_url('home/sair'); ?>");
            }
        }

        function historico_candidato(id) {
            var logado = <?php echo $this->session->userdata('logado') ? 'true' : 'false'; ?>;
            if (logado) {
                window.open("<?php echo site_url('recrutamento_candidatos/visualizarHistorico'); ?>/" + id, 'Candidato', 'TITLEBAR=NO,LOCATION=NO,STATUS=NO, TOOLBAR=NO, LOCATION=NO, DIRECTORIES=NO, RESISABLE=NO, SCROLLBARS=YES, TOP=80, LEFT=180, WIDTH=1130, HEIGHT=560');
            } else {
                window.open("<?php echo site_url('home/sair'); ?>");
            }
        }
    </script>

<?php require_once 'end_html.php'; ?>