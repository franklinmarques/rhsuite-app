<?php require_once 'header.php'; ?>

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <?php if ($modulo): ?>
                            <li class="active">Gerenciar Requisições de Pessoal - <?= $modulo; ?></li>
                        <?php else: ?>
                            <li class="active">Gerenciar Requisições de Pessoal</li>
                        <?php endif; ?>
                    </ol>
                    <button class="btn btn-info" onclick="add_requisicao()">
                        <i class="glyphicon glyphicon-plus"></i> Nova requisição de pessoal
                    </button>
                    <!--                    <a class="btn btn-primary" href="-->
                    <? //= site_url('requisicaoPessoal_vagas'); ?><!--">Relatório - RPs x-->
                    <!--                        Status</a>-->
                    <?php if (!($id_depto === '5' or $depto === 'Cuidadores')): ?>
                        <a class="btn btn-primary" href="<?= site_url('requisicaoPessoal_consolidado'); ?>">Consolidado
                            geral</a>
                    <?php endif ?>
                    <br/>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por status</label>
                                        <select id="status" class="form-control filtro input-sm"
                                                autocomplete="off">
                                            <option value="">Todas</option>
                                            <option value="A,G,P" selected>Aguardando aprovação + Ativas + Fechadas
                                                parcialmente
                                            </option>
                                            <option value="A">Ativas</option>
                                            <option value="S">Suspensas</option>
                                            <option value="C">Canceladas</option>
                                            <option value="G">Aguardando aprovação</option>
                                            <option value="F">Fechadas</option>
                                            <option value="P">Fechadas parcialmente</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por estágio</label>
                                        <select id="estagio" class="form-control filtro input-sm"
                                                autocomplete="off">
                                            <option value="">Todos</option>
                                            <option value="1">01/10 - Alinhando perfil</option>
                                            <option value="2">02/10 - Divulgando vagas</option>
                                            <option value="3">03/10 - Triando currículos</option>
                                            <option value="4">04/10 - Convocando candidatos</option>
                                            <option value="5">05/10 - Entrevistando candidatos</option>
                                            <option value="6">06/10 - Elaborando pareceres</option>
                                            <option value="7">07/10 - Aguardando gestor</option>
                                            <option value="8">08/10 - Entrevista solicitante</option>
                                            <option value="9">09/10 - Exame adissional</option>
                                            <option value="10">10/10 - Entrega documentos</option>
                                            <option value="11">Faturamento</option>
                                            <option value="12">Processo finalizado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por município</label>
                                        <?php echo form_dropdown('', $municipios, '', 'id="municipio" class="form-control filtro input-sm" autocomplete="off"'); ?>
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
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por departamento</label>
                                        <?php echo form_dropdown('', ['' => 'Todos'] + $deptos, '', 'id="depto" class="form-control filtro input-sm" autocomplete="off"'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por cargo</label>
                                        <?php echo form_dropdown('', ['' => 'Todos'] + $cargos, '', 'id="cargo" class="form-control filtro input-sm" autocomplete="off"'); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Data início</label>
                                        <input type="text" id="data_inicio"
                                               class="form-control input-sm text-center filtro data"
                                               placeholder="dd/mm/aaaa" autocomplete="off">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Data término</label>
                                        <input type="text" id="data_termino"
                                               class="form-control input-sm text-center filtro data"
                                               placeholder="dd/mm/aaaa" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="table" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Req.</th>
                            <th>Abertura</th>
                            <th>Status</th>
                            <th>Estágio</th>
                            <th>Selecionador(a)</th>
                            <th>Cargo da vaga</th>
                            <th>Total vagas</th>
                            <th>Cargo/função</th>
                            <th>Depto./Área/requisitante - Empresa/requisitante</th>
                            <th>Qtde. vagas</th>
                            <th>Previsão início</th>
                            <th>Cargo/função</th>
                            <th>Qtd vgs. abertas</th>
                            <th>Qtd vgs. preenchidas</th>
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
                            <h3 class="modal-title">Editar requisição de pessoal</h3>
                        </div>
                        <div class="modal-body form">
                            <h3 class="text-danger"><strong>Campos marcados com "*" são obrigatórios</strong></h3>
                            <form action="#" id="form" class="form-horizontal">
                                <!--                                <input type="hidden" value="" name="id"/>-->
                                <input type="hidden" name="numero" value="">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="row form-group">
                                                <label class="control-label col-md-3">N&ordm; da requisição</label>
                                                <div class="col-md-3">
                                                    <input name="id" class="form-control text-right" type="text"
                                                           readonly="">
                                                </div>
                                                <label class="control-label col-md-1 text-danger text-nowrap"
                                                       style="padding-left: 5px;"><strong>Perfil
                                                        *</strong></label>
                                                <div class="col-md-5">
                                                    <select name="requisicao_confidencial" class="form-control">
                                                        <option value="">selecione...</option>
                                                        <option value="0">Não confidencial</option>
                                                        <option value="1">Confidencial</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 text-right">
                                            <?php if ($aprovadores): ?>
                                                <button type="button" id="btnAprovarContratacao2"
                                                        onclick="aprovar_contratacao()"
                                                        class="btn btn-success" disabled="">Aprovar contratação
                                                </button>
                                            <?php endif; ?>
                                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
                                                Salvar
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                    <!--                                    --><?php //if ($usuario['depto'] == 'Gestão de Pessoas'): ?>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Selecionador(a)</label>
                                        <div class="col-md-6">
                                            <input name="selecionador" class="form-control" type="text"
                                                   placeholder="Nome do(a) selecionador(a)">
                                        </div>
                                        <label class="control-label col-md-1">SPA</label>
                                        <div class="col-md-2">
                                            <input name="spa" class="form-control" type="number" size="6">
                                        </div>
                                    </div>
                                    <!--                                    --><?php //endif; ?>

                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Tipo de vaga <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-2">
                                            <select name="tipo_vaga" class="form-control">
                                                <option value="I">Interna</option>
                                                <option value="E">Externa</option>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-2">Data abertura <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-2">
                                            <input name="data_abertura" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" type="text">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="vagas_deficiente" value="1" type="checkbox"> Vagas para
                                                    deficientes
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($tipo != 'funcionario' or $aprovadores): ?>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Status <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-md-4">
                                                <select name="status" class="form-control">
                                                    <option value="A">Ativa</option>
                                                    <option value="S">Suspensa</option>
                                                    <option value="C">Cancelada</option>
                                                    <option value="G">Aguardando aprovação</option>
                                                    <option value="F">Fechada</option>
                                                    <option value="P">Fechada parcialmente</option>
                                                </select>
                                            </div>
                                            <label class="control-label col-md-1" style="padding-right: 6px;">Estágio
                                                <span class="text-danger">*</span></label>
                                            <div class="col-md-4">
                                                <select name="estagio" class="form-control">
                                                    <option value="1">01/10 - Alinhando perfil</option>
                                                    <option value="2">02/10 - Divulgando vagas</option>
                                                    <option value="3">03/10 - Triando currículos</option>
                                                    <option value="4">04/10 - Convocando candidatos</option>
                                                    <option value="5">05/10 - Entrevistando candidatos</option>
                                                    <option value="6">06/10 - Elaborando pareceres</option>
                                                    <option value="7">07/10 - Aguardando gestor</option>
                                                    <option value="8">08/10 - Entrevista solicitante</option>
                                                    <option value="9">09/10 - Exame adissional</option>
                                                    <option value="10">10/10 - Entrega documentos</option>
                                                    <option value="11">Faturamento</option>
                                                    <option value="12">Processo finalizado</option>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($id_depto === '7' or $depto === 'Gestão de Pessoas' or $tipo === 'empresa'): ?>
                                        <div class="row form-group gestao_pessoas">
                                            <label class="control-label col-md-2">Data fechamento</label>
                                            <div class="col-md-2">
                                                <input name="data_fechamento" placeholder="dd/mm/aaaa"
                                                       class="form-control text-center data" type="text">
                                            </div>
                                            <label class="control-label col-md-3">Data solicitação exame médico</label>
                                            <div class="col-md-2">
                                                <input name="data_solicitacao_exame"
                                                       class="form-control text-center data"
                                                       type="text" placeholder="dd/mm/aaaa">
                                            </div>
                                        </div>
                                        <div class="row form-group gestao_pessoas">
                                            <label class="control-label col-md-2">Data suspensão</label>
                                            <div class="col-md-2">
                                                <input name="data_suspensao" placeholder="dd/mm/aaaa"
                                                       class="form-control text-center data" type="text">
                                            </div>
                                            <label class="control-label col-md-3">Data cancelamento</label>
                                            <div class="col-md-2">
                                                <input name="data_cancelamento" class="form-control text-center data"
                                                       type="text" placeholder="dd/mm/aaaa">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Departamento <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_depto', $deptos, "$id_depto", 'id="depto" class="form-control estrutura"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Área <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_area', $areas, '', 'id="area" class="form-control estrutura"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Setor <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_setor', $setores, '', 'id="setor" class="form-control estrutura"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Requisitante <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-9 requisitante" id="externo">
                                            <?php echo form_dropdown('requisitante_externo', $requisitantes, '', 'class="form-control"'); ?>
                                        </div>
                                        <div class="col-md-9 requisitante" id="interno">
                                            <?php echo form_dropdown('requisitante_interno', $requisitantes, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5><strong>Dados do contrato e centro de custo</strong></h5>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">N&ordm; contrato</label>
                                        <div class="col-md-3">
                                            <input name="numero_contrato" placeholder="Número do contrato"
                                                   class="form-control" type="text">
                                        </div>
                                        <label class="control-label col-md-3">Regime contratação <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-2">
                                            <select name="regime_contratacao" class="form-control">
                                                <option value="1">CLT</option>
                                                <option value="2">MEI</option>
                                                <option value="3">PJ</option>
                                                <option value="4">Estágio</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Centro de custo <span class="text-danger">*</span></label>
                                        <div class="col-md-3">
                                            <input name="centro_custo" placeholder="Centro de custo"
                                                   class="form-control" type="text">
                                            <span class="text-primary">Nome/número</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5><strong>Dados da vaga</strong></h5>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Cargo</label>
                                        <div class="col-md-9 cargo" id="cargo_externo">
                                            <input name="cargo_externo" class="form-control" type="text"
                                                   placeholder="Digite o nome do cargo (externo)">
                                        </div>
                                        <div class="col-md-9 cargo" id="cargo_interno">
                                            <?php echo form_dropdown('id_cargo', $cargos, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Função</label>
                                        <div class="col-md-9 funcao" id="funcao_externa">
                                            <input name="funcao_externa" class="form-control" type="text"
                                                   placeholder="Digite o nome da função (externa)">
                                        </div>
                                        <div class="col-md-9 funcao" id="funcao_interna">
                                            <?php echo form_dropdown('id_funcao', $funcoes, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group cargo_funcao_alternativo">
                                        <label class="control-label col-md-2">Cargo/Função</label>
                                        <div class="col-md-9 funcao">
                                            <input name="cargo_funcao_alternativo" class="form-control" type="text"
                                                   placeholder="Digite o nome do cargo/função não cadastrado">
                                            <p class="text-danger"><strong>Utilize o campo acima caso a requisição se
                                                    tratar de um novo Cargo/Função não cadastrado.</strong></p>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Quantidade de vagas <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-3">
                                            <input name="numero_vagas" class="form-control" type="number" min="0"
                                                   step="1">
                                        </div>
                                        <label class="control-label col-md-3">Justificativa da contratação <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-3">
                                            <select name="justificativa_contratacao" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="S">Substituição</option>
                                                <option value="T">Transferência</option>
                                                <option value="A">Aumento de quadro</option>
                                                <option value="P">Temporário</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Colaborador(a) a ser substituído(a)<span
                                                    class="text-danger" id="substituto"> *</span></label>
                                        <div class="col-md-9">
                                            <textarea name="colaborador_substituto" class="form-control"
                                                      rows="1"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Selecione responsável pela aprovação <span
                                                    class="text-primary" id="aprovado_por"
                                                    style="display:none;">*</span></label>
                                        <div class="col-md-9">
                                            <select name="aprovado_por" class="form-control">
                                                <option value="">selecione...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Data de aprovação</label>
                                        <div class="col-md-2">
                                            <input name="data_aprovacao" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" type="text" readonly>
                                        </div>
                                        <?php if ($aprovadores): ?>
                                            <div class="col-md-2">
                                                <button type="button" id="btnAprovarContratacao"
                                                        onclick="aprovar_contratacao()"
                                                        class="btn btn-success" disabled="">Aprovar contratação
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-3 col-md-offset-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="possui_indicacao" value="1" type="checkbox"
                                                           id="possui_indicacao"> A vaga possui indicação?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-9 col-md-offset-2">
                                            <label class="text-danger"><strong>Indicações para preenchimento de vagas
                                                    devem ser realizadas com bastante critério e consciência;
                                                    preferencialmente quando os indicados são conhecidos e apresentam
                                                    real potencial para desempenhar a função exigida pela vaga.<br>
                                                    No campo "Responsável pela indicação" preencha os seguintes dados:
                                                    Nome da pessoa responsável pela indicação, Empresa, Cargo, Telefone
                                                    de contato.</strong></label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Colaboradores indicados<span
                                                    class="text-danger label_indicacao"> *</span></label>
                                        <div class="col-md-9">
                                            <textarea name="colaboradores_indicados"
                                                      class="form-control possui_indicacao" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Responsável pela indicação<span
                                                    class="text-danger label_indicacao"> *</span></label>
                                        <div class="col-md-9">
                                            <textarea name="indicador_responsavel" class="form-control possui_indicacao"
                                                      rows="2" maxlength="255"></textarea>
                                        </div>
                                    </div>

                                    <div id="dados_cuidadores" style="display: none;">
                                        <hr>
                                        <h5><strong>Candidatos departamento Cuidadores - dados complementares</strong>
                                        </h5>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Nome do pai<span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-md-9">
                                                <input name="nome_pai" class="form-control" type="text"
                                                       placeholder="Nome do pai">
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Nome da mãe<span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-md-9">
                                                <input name="nome_mae" class="form-control" type="text"
                                                       placeholder="Nome da mãe">
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Data nascimento<span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-md-2">
                                                <input name="data_nascimento" class="form-control text-center data"
                                                       type="text" placeholder="dd/mm/aaaa">
                                            </div>
                                            <label class="control-label col-md-1">RG<span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-md-2">
                                                <input name="rg" class="form-control rg" type="text">
                                            </div>
                                            <label class="control-label col-md-2">Data emissão RG<span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-md-2">
                                                <input name="rg_data_emissao" class="form-control text-center data"
                                                       type="text" placeholder="dd/mm/aaaa">
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2 text-nowrap">Órgão emissor RG<span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-md-2">
                                                <input name="rg_orgao_emissor" class="form-control" type="text">
                                            </div>
                                            <label class="control-label col-md-1">CPF<span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-md-2" style="width: 160px;">
                                                <input name="cpf" class="form-control cpf" type="text">
                                            </div>
                                            <label class="control-label col-md-1">PIS<span
                                                        class="text-danger"> *</span></label>
                                            <div class="col-md-2" style="width: 160px;">
                                                <input name="pis" class="form-control pis" type="text">
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-10 col-md-offset-1">
                                                <label><strong>Para situações com dois ou mais colaboradores, cadastre
                                                        os dados de um colaboraor nos campos acima e utilize o campo
                                                        abaixo para cadastrar os dados dos demais
                                                        colaboradores.</strong></label>
                                                <textarea name="departamento_informacoes"
                                                          class="form-control" rows="18"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <h5><strong>Benefícios</strong></h5>
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="row" style="margin-bottom: 15px;">
                                                <label class="control-label col-md-3">Vale transporte</label>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="checkbox" name="vale_transporte" value="1"
                                                                   class="beneficio">
                                                        </span>
                                                        <input name="valor_vale_transporte" type="text"
                                                               class="form-control text-right valor_beneficio">
                                                    </div>
                                                </div>
                                                <label class="control-label col-md-3">Vale alimentação</label>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="checkbox" name="vale_alimentacao" value="1"
                                                           class="beneficio">
                                                </span>
                                                        <input name="valor_vale_alimentacao" type="text"
                                                               class="form-control text-right valor_beneficio">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: 15px;">
                                                <label class="control-label col-md-3">Vale refeição</label>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="checkbox" name="vale_refeicao" value="1"
                                                                   class="beneficio">
                                                        </span>
                                                        <input name="valor_vale_refeicao" type="text"
                                                               class="form-control text-right valor_beneficio">
                                                    </div>
                                                </div>
                                                <label class="control-label col-md-3">Cesta básica</label>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="checkbox" name="cesta_basica" value="1"
                                                                   class="beneficio">
                                                        </span>
                                                        <input name="valor_cesta_basica" type="text"
                                                               class="form-control text-right valor_beneficio">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: 15px;">
                                                <label class="control-label col-md-3">Assistência médica</label>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="checkbox" name="assistencia_medica" value="1"
                                                                   class="beneficio">
                                                        </span>
                                                        <input name="valor_assistencia_medica" type="text"
                                                               class="form-control text-right valor_beneficio">
                                                    </div>
                                                </div>
                                                <label class="control-label col-md-3">Plano odontológico</label>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="checkbox" name="plano_odontologico" value="1"
                                                                   class="beneficio">
                                                        </span>
                                                        <input name="valor_plano_odontologico" type="text"
                                                               class="form-control text-right valor_beneficio">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: 15px;">
                                                <label class="control-label col-md-3">Participação em resultados</label>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="checkbox" name="participacao_resultados"
                                                                   value="1" class="beneficio">
                                                        </span>
                                                        <input name="valor_participacao_resultados" type="text"
                                                               class="form-control text-right valor_beneficio">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Local de trabalho <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <input name="local_trabalho" class="form-control" type="text">
                                            <span class="text-primary">Ex.: DE-Suzano / 009/2014 ou EMTU - 015/2011</span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Município<span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <input name="municipio" class="form-control" type="text"
                                                   placeholder="Digite o nome do município">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Horário de trabalho (dias da semana e
                                            horários) <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <textarea name="horario_trabalho" class="form-control"
                                                      cols="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Previsão início <span class="text-danger">*</span></label>
                                        <div class="col-md-2">
                                            <input name="previsao_inicio" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data"
                                                   type="text">
                                        </div>
                                        <label class="control-label col-md-3">Remuneração mensal <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1">R$</span>
                                                <input name="remuneracao_mensal" class="form-control text-right valor"
                                                       type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Exames necessários</label>
                                        <div class="col-md-5">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="exame_clinico" value="1"> Clínico
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="audiometria" value="1"> Audiometria
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="laudo_cotas" value="1"> Laudo de cotas
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Outros exames</label>
                                        <div class="col-md-9">
                                            <input name="exame_outros" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Perfil geral<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-8">
                                            <textarea name="perfil_geral" class="form-control"
                                                      cols="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Competências técnicas necessárias<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-8">
                                            <textarea name="competencias_tecnicas" class="form-control"
                                                      cols="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Competências comportamentais
                                            necessárias<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-8">
                                            <textarea name="competencias_comportamentais" class="form-control"
                                                      cols="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Atividades e Responsabilidades associadas
                                            ao cargo-função <span class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <textarea name="atividades_associadas" class="form-control"
                                                      cols="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Observações</label>
                                        <div class="col-md-8">
                                            <textarea name="observacoes" class="form-control" cols="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal_aprovados" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Candidatos aprovados</h3>
                        </div>
                        <div class="modal-body form">
                            <table id="table_aprovados" class="table table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th colspan="7" class="text-center">
                                        <h4><strong>Datas do Processo Seletivo</strong></h4>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Candidatos</th>
                                    <th>Abertura da vaga</th>
                                    <th>Aprovação aumento de quadro</th>
                                    <th>Entrevista seleção</th>
                                    <th>Entrevista requisitante</th>
                                    <th>Fechamento</th>
                                    <th>Admissão</th>
                                </tr>
                                <tr id="qtde_dias">
                                    <th colspan="2">Qtde. dias</th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal_email" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Ativar contratação</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_email" class="form-horizontal" autocomplete="off">
                                <input type="hidden" name="id" value="">
                                <input type="hidden" name="aprovados" value="1">
                                <div class="row form-group">
                                    <div class="col-md-10 col-md-offset-1">
                                        <label>E-mail destinatário<span
                                                    class="text-danger"> *</span></label>
                                        <?php echo form_dropdown('emails', ['' => 'selecione...'], '', 'class="form-control"'); ?>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-10 col-md-offset-1">
                                        <label>Texto do corpo de e-mail<span
                                                    class="text-danger"> *</span></label>
                                        <textarea name="mensagem" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-10 col-md-offset-1">
                                        <label>Dados dos candidatos aprovados<span
                                                    class="text-danger"> *</span></label>
                                        <textarea name="dados_candidatos" class="form-control"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnEnviarEmail" onclick="enviar_email();" class="btn btn-warning">
                                Enviar e-mail
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>

<?php require_once 'end_js.php'; ?>

    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Requisições de Pessoal';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

    <script>

        var save_method;
        var table, table_aprovados;
        var tipo_empresa = <?php echo $tipo == 'empresa' or $aprovadores ? 'true' : 'false'; ?>;
        var nivel = <?php echo $nivel == 6 ? 'true' : 'false'; ?>;
        var id_requisicao;

        $(document).ready(function () {

            $('.data').mask('00/00/0000');
            $('.rg').mask('00.000.000-0');
            $('.cpf').mask('000.000.000-00');
            $('.pis').mask('00.000.000.000');
            $('.valor').mask('##.###.##0,00', {reverse: true});
            $('.valor_beneficio').mask('##.##0,00', {reverse: true});

            table = $('#table').DataTable({
                dom: "<'row'<'col-sm-4'l><'#dt_filtro.col-sm-4'><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                iDisplayLength: -1,
                lengthMenu: [[5, 10, 25, 50, 100, 500, -1], [5, 10, 25, 50, 100, 500, 'Todos']],
                order: [[0, 'desc']],
                language: {
                    url: '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                ajax: {
                    url: '<?php echo site_url('requisicaoPessoal/ajax_list/') ?>',
                    type: 'POST',
                    data: function (d) {
                        d.status = $('#status').val();
                        d.estagio = $('#estagio').val();
                        d.municipio = $('#municipio').val();
                        d.depto = $('#depto').val();
                        d.cargo = $('#cargo').val();
                        d.data_inicio = $('#data_inicio').val();
                        d.data_termino = $('#data_termino').val();

                        return d;
                    },
                    'dataSrc': function (json) {
                        if (json.draw === '1') {
                            if (tipo_empresa) {
                                $("#dt_filtro").html('<br>Mostrar:&ensp;<label class="checkbox-inline">' +
                                    '<input type="checkbox" onchange="toggle_vis(3)"> Estágio' +
                                    '</label>' +
                                    '<label class="checkbox-inline">' +
                                    '<input type="checkbox" onchange="toggle_vis(11)"> Cargo/função' +
                                    '</label>');
                            } else {
                                $("#dt_filtro").html('<br>Mostrar:&ensp;<label class="checkbox-inline">' +
                                    '<input type="checkbox" onchange="toggle_vis(3)"> Estágio' +
                                    '</label>');
                            }
                        }
                        return json.data;
                    }
                },
                columnDefs: [
                    {
                        visible: false,
                        targets: tipo_empresa ? [3, 7, 9, 11, 12, 13] : [2, 3, 9, 11]
                    },
                    {
                        createdCell: function (td, cellData, rowData, row, col) {
                            if (rowData[2] === 'Aguardando aprovação' && rowData[15] === 'A' && rowData[16] === null) {
                                $(td).css({'background-color': '#f00', 'color': '#fff'});
                            }
                        },
                        searchable: false,
                        targets: [2]
                    },
                    {
                        visible: (nivel === true ? false : true),
                        targets: [12, 13]
                    },
                    {
                        createdCell: function (td, cellData, rowData, row, col) {
                            if (rowData[col] === null) {
                                $(td).css('background-color', '#ff0');
                            }
                        },
                        targets: [4]
                    },
                    {
                        width: '20%',
                        targets: [3, 10]
                    },
                    {
                        width: '30%',
                        targets: [4, 8]
                    },
                    {
                        searchable: false,
                        targets: [1, 3, 9, 10, 11, 12, 13, 14]
                    },
                    {
                        className: 'text-nowrap',
                        targets: [-1],
                        orderable: false
                    }
                ]
            });

            table_aprovados = $('#table_aprovados').DataTable({
                ordering: false,
                searching: false,
                paging: false,
                language: {
                    url: '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                ajax: {
                    url: '<?php echo site_url('requisicaoPessoal/ajax_listAprovados/') ?>',
                    type: 'POST',
                    data: function (d) {
                        d.id = id_requisicao;
                        return d;
                    },
                    'dataSrc': function (json) {
                        $.each(json.total_dias, function (index, value) {
                            $(table_aprovados.columns(index + 2).header(2)).html(value);
                        });

                        return json.data;
                    }
                },
                columnDefs: [
                    {
                        width: '100%',
                        targets: [0]
                    },
                    {
                        className: 'text-center',
                        targets: [1, 2, 3, 4, 5]
                    }
                ]
            });

        });

        function toggle_vis(e) {
            var column = table.column(e);
            column.visible(!column.visible());
        }

        $('[name="tipo_vaga"]').on('change', function () {
            if (this.value === 'I') {
                $('[name="requisitante_externo"], [name="cargo_externo"], [name="funcao_externa"]').val('');
                $('#externo, #cargo_externo, #funcao_externa').hide();
                $('#interno, #cargo_interno, #funcao_interna, .cargo_funcao_alternativo').show();
                // $('.estrutura, [name="justificativa_contratacao"]').prop('disabled', false);
            } else if (this.value === 'E') {
                $('[name="requisitante_interno"], [name="cargo_interno"], [name="funcao_interna"], [name="cargo_funcao_alternativo"]').val('');
                $('#interno, #cargo_interno, #funcao_interna, .cargo_funcao_alternativo').hide();
                $('#externo, #cargo_externo, #funcao_externa').show();
                // $('.estrutura, [name="justificativa_contratacao"]').val('').prop('disabled', true);
            }
        });

        $('[name="status"]').on('change', function () {
            // $('#form [name="data_fechamento"]').prop('disabled', this.value !== 'F');
            // $('#form input[name="data_suspensao"], #form input[name="data_cancelamento"]').prop('disabled', true);
            if (this.value === 'F') {
                if ('<?= $id_depto ?>' === '7' || '<?= $depto ?>' === 'Gestão de Pessoas' || '<?= $tipo ?>' === 'empresa') {
                    if ($('[name="data_fechamento"]').val().length === 0) {
                        $('[name="data_fechamento"]').val(moment().format('DD/MM/YYYY'));
                    }
                }
                $('[name="estagio"]').val('12');
            } else if (this.value === 'A') {
                $('#btnAprovarContratacao, #btnAprovarContratacao2').hide();
                // $('[name="aprovado_por"], [name="data_aprovacao"]').prop('disabled', true);
            } else if (this.value === 'S') {
                $('#form input[name="data_suspensao"]').prop('disabled', false);
            } else if (this.value === 'C') {
                $('#form input[name="data_cancelamento"]').prop('disabled', false);
            } else {
                $('#btnAprovarContratacao, #btnAprovarContratacao2').show().prop('disabled', this.value !== 'G');
                // $('[name="aprovado_por"], [name="data_aprovacao"]').prop('disabled', false);
            }
        });


        $('.estrutura').on('change', function () {
            atualizarEstrutura();
            if (this.id === 'depto') {
                if (this.value === '5' && $('#possui_indicacao').is(':checked')) { // Cuidadores e Possui indicação
                    $('#dados_cuidadores').show();

                } else {
                    // if (this.value === '7') {
                    //     $('.gestao_pessoas').show();
                    // } else {
                    //     $('.gestao_pessoas').hide();
                    // }
                    $('#dados_cuidadores').hide();
                    $('#dados_cuidadores input').val('');
                }
            }
        });

        $('[name="id_cargo"]').on('change', function () {
            atualizarFuncao();
        });

        $('[name="justificativa_contratacao"]').on('change', function () {
            if (this.value === 'S') {
                $('#substituto').show();
                $('#substituto').parent('label').css('font-weight', 'bold').addClass('text-danger');
                $('[name="colaborador_substituto"]').prop('disabled', false);
            } else {
                $('[name="colaborador_substituto"]').prop('disabled', true);
                $('#substituto').parent('label').css('font-weight', 'normal').removeClass('text-danger');
                $('#substituto').hide();
            }
            // if (save_method === 'add' && $('[name="status"]').val() === 'G') {
            //     $('[name="status"]').val('A');
            // }
            if (this.value === 'A') {
                $('#aprovado_por').show();
                $('#aprovado_por').parent('label').css('font-weight', 'bold').addClass('text-primary');
                ;
                $('[name="aprovado_por"], [name="data_aprovacao"]').prop('disabled', false);
                $('[name="aprovado_por"]').prop('disabled', false);
                if (($('[name="aprovado_por"]').val() === '<?= $idUsuario; ?>' || '<?= $idUsuario; ?>' === '') && $('[name="data_aprovacao"]').val() === '') {
                    $('#btnAprovarContratacao, #btnAprovarContratacao2').show().prop('disabled', false);
                    $('[name="data_aprovacao"]').prop('disabled', false);
                } else {
                    $('#btnAprovarContratacao, #btnAprovarContratacao2').hide();
                    $('[name="data_aprovacao"]').prop('disabled', true);
                }
                if (save_method === 'add') {
                    $('[name="status"]').val('G');
                }
            } else {
                $('#aprovado_por').parent('label').css('font-weight', 'normal').removeClass('text-primary');
                $('#aprovado_por').hide();
                if (save_method === 'add') {
                    $('[name="status"]').val('A');
                }
                $('[name="aprovado_por"], [name="data_aprovacao"]').prop('disabled', true);
                $('[name="aprovado_por"]').prop('disabled', true);
                $('#btnAprovarContratacao, #btnAprovarContratacao2').hide();
            }
        });

        $('#possui_indicacao').on('change', function () {
            if (this.checked) {
                $('.possui_indicacao').prop('disabled', false);
                $('.label_indicacao').show();

                if ($('#depto').val() === '5') { // Cuidadores e Possui indicação
                    $('#dados_cuidadores').show();
                } else {
                    $('#dados_cuidadores').hide();
                    $('#dados_cuidadores input').val('');
                }
            } else {
                $('.possui_indicacao').prop('disabled', true);
                $('.label_indicacao').hide();

                $('#dados_cuidadores').hide();
                $('#dados_cuidadores input').val('');
            }
        });

        $('.beneficio').on('change', function () {
            if (this.checked === true) {
                $(this).parent()
            }
        });

        $('.filtro').on('change', function () {
            reload_table();
        });

        $('#limpa_filtro').on('click', function () {
            $('.filtro').val('');
            reload_table();
        });

        function aprovar_contratacao() {
            $('[name="status"]').val('A');
            $('[name="data_aprovacao"]').val(moment().format('DD/MM/YYYY'));
            if (confirm('Deseja aprovar a requisição?')) {
                save();
            }
        }

        function atualizarEstrutura() {
            $.ajax({
                'url': '<?php echo site_url('requisicaoPessoal/atualizarEstrutura/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('.estrutura, [name="tipo_vaga"], [name="requisitante_interno"], [name="requisitante_externo"]').serialize(),
                'success': function (json) {
                    $('#depto').html($(json.depto).html());
                    $('#area').html($(json.area).html());
                    $('#setor').html($(json.setor).html());
                    $('[name="requisitante_interno"]').html($(json.requisitante_interno).html());
                    $('[name="requisitante_externo"]').html($(json.requisitante_externo).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function atualizarFuncao() {
            $.ajax({
                'url': '<?php echo site_url('requisicaoPessoal/atualizarFuncao/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('[name="id_cargo"], [name="id_funcao"]').serialize(),
                'success': function (json) {
                    $('[name="id_funcao"]').html($(json.funcao).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function atualizarMunicipio() {
            $.ajax({
                'url': '<?php echo site_url('requisicaoPessoal/atualizarMunicipio/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'municipio': $('#municipio').val()
                },
                'success': function (json) {
                    $('#municipio').html($(json.municipios).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function add_requisicao() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $('[name="tipo_vaga"] option[value="E"]').hide();

            $.ajax({
                'url': '<?php echo site_url('requisicaoPessoal/ajax_nextId/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'success': function (json) {
                    $('[name="id"]').val(json.id);
                    $('[name="data_abertura"]').val(json.data_abertura);
                    $('[name="departamento_informacoes"]').val(json.departamento_informacoes);

                    $('#externo, #cargo_externo, #funcao_externa').hide();
                    $('#interno, #cargo_interno, #funcao_interna, .cargo_funcao_alternativo').show();
                    $('[name="selecionador"], [name="spa"]').prop('disabled', true);
                    $('.estrutura').prop('disabled', false);
                    $('#dados_cuidadores').hide();
                    $('#dados_cuidadores input').val('');
                    $('[name="aprovado_por"]').html($(json.aprovado_por).html());
                    $('#btnAprovarContratacao, #btnAprovarContratacao2').hide();
                    $('.estrutura, [name="requisitante_interno"], [name="requisitante_externo"], [name="id_cargo"], [name="id_funcao"]').val('');
                    $('.estrutura, [name="justificativa_contratacao"], #possui_indicacao').trigger('change');

                    atualizarEstrutura();
                    atualizarFuncao();
                    $('#modal_form').modal('show');
                    $('.modal-title').text('Adicionar requisição de pessoal');
                    $('.combo_nivel1').hide();

                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_requisicao(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $('[name="tipo_vaga"] option[value="E"]').show();

            $.ajax({
                'url': '<?php echo site_url('requisicaoPessoal/ajax_edit/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    var input = json.input;
                    var data = json.data;

                    $('[name="selecionador"], [name="spa"]').prop('disabled', false);
                    $.each(data, function (key, value) {
                        if ($('[name="' + key + '"]').is(':checkbox') === false) {
                            $('[name="' + key + '"]').val(value);
                        } else {
                            $('[name="' + key + '"]').prop('checked', value === '1');
                        }
                    });

                    $('#depto').html($(input.depto).html()).trigger('change');
                    $('#area').html($(input.area).html());
                    $('#setor').html($(input.setor).html());
                    $('[name="id_funcao"]').html($(input.funcao).html());
                    $('[name="requisitante_interno"]').html($(input.requisitante_interno).html());
                    $('[name="requisitante_externo"]').html($(input.requisitante_externo).html());
                    $('[name="requisitante_interno"]').val(data.requisitante_interno);
                    $('[name="requisitante_externo"]').val(data.requisitante_externo);
                    $('[name="status"]').trigger('change');
                    // if (data.tipo_vaga === 'I') {
                    // } else if (data.tipo_vaga === 'E') {
                    // }
                    $('[name="aprovado_por"]').html($(input.aprovado_por).html());

                    $('.estrutura, [name="justificativa_contratacao"], #possui_indicacao').trigger('change');

                    if (data.status === 'G' && data.justificativa_contratacao === 'A') {
                        if ((data.aprovado_por === '<?= $idUsuario; ?>' || '<?= $idUsuario; ?>' === '') && (data.data_aprovacao === '' || data.data_aprovacao === null)) {
                            $('#btnAprovarContratacao, #btnAprovarContratacao2').show().prop('disabled', false);
                        } else {
                            $('#btnAprovarContratacao, #btnAprovarContratacao2').hide();
                        }
                    } else {
                        $('#btnAprovarContratacao, #btnAprovarContratacao2').hide();
                    }

                    if (data.tipo_vaga === 'I') {
                        $('#externo, #cargo_externo, #funcao_externa').hide();
                        $('#interno, #cargo_interno, #funcao_interna, .cargo_funcao_alternativo').show();
                        // $('.estrutura').prop('disabled', false);
                    } else if (data.tipo_vaga === 'E') {
                        $('#interno, #cargo_interno, #funcao_interna, .cargo_funcao_alternativo').hide();
                        $('#externo, #cargo_externo, #funcao_externa').show();
                        // $('.estrutura').val('').prop('disabled', true);
                    }

                    $('#modal_form').modal('show');

                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function mostrar_aprovados(id) {
            id_requisicao = id;
            table_aprovados.ajax.reload(function () {
                $('#modal_aprovados').modal('show')
            });
        }

        function edit_email(id) {
            $('#form')[0].reset();
            $.ajax({
                'url': '<?php echo site_url('requisicaoPessoal/ativarContratacao') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $('#form_email [name="emails"]').html($(json.emails).html());
                    $('#form_email [name="id"]').val(id);
                    $('#form_email [name="mensagem"]').val(json.mensagem);
                    $('#form_email [name="dados_candidatos"]').val(json.dados);

                    $('#modal_email').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function reload_table() {
            table.ajax.reload(null, false);
        }

        function save() {
            $('#btnSave').text('Salvando...').attr('disabled', true);
            var url;

            if (save_method === 'add') {
                url = '<?php echo site_url('requisicaoPessoal/ajax_add') ?>';
            } else {
                url = '<?php echo site_url('requisicaoPessoal/ajax_update') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        atualizarMunicipio();
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSave').text('Salvar').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar').attr('disabled', false); //set button enable
                }
            });
        }

        function enviar_email() {
            $('#btnEnviarEmail').text('Enviando e-mail...').attr('disabled', true);

            $.ajax({
                'url': '<?php echo site_url('requisicaoPessoal/enviarEmail') ?>',
                'type': 'POST',
                'data': $('#form_email').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    // if (json.status) {
                    //     $('#modal_email').modal('hide');
                    // }
                    $('#btnEnviarEmail').text('Enviar e-mail').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnEnviarEmail').text('Enviar e-mail').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_requisicao(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('requisicaoPessoal/ajax_delete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
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

    </script>

<?php require_once 'end_html.php'; ?>