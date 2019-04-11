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
                    <br/>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por status</label>
                                        <select id="status" class="form-control filtro input-sm" autocomplete="off">
                                            <option value="">Todas</option>
                                            <option value="A">Ativas</option>
                                            <option value="S">Suspensas</option>
                                            <option value="C">Canceladas</option>
                                            <option value="F">Fechadas</option>
                                            <option value="P">Fechadas parcialmente</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por estágio</label>
                                        <select id="estagio" class="form-control filtro input-sm" autocomplete="off">
                                            <option value="">Todos</option>
                                            <option value="1">01/10 - Alinhando perfil</option>
                                            <option value="2">02/10 - Divulgando vagas</option>
                                            <option value="3">03/10 - Tirando currículos</option>
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
                                    <div class="col-md-2">
                                        <label>&nbsp;</label><br>
                                        <div class="btn-group" role="group" aria-label="...">
                                            <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
                                                Limpar filtros
                                            </button>
                                        </div>
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
                            <th>Cargo/função</th>
                            <th>Área/setor/requisitante - Empresa/requisitante</th>
                            <th>Qtde. vagas</th>
                            <th>Previsão início</th>
                            <th>Tipo vaga</th>
                            <th>Qtd abertas</th>
                            <th>Qtd fechadas</th>
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
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row form-group">
                                                <label class="control-label col-md-3">N&ordm; requisição</label>
                                                <div class="col-md-4">
                                                    <input name="id" class="form-control text-right" type="text"
                                                           readonly="">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="control-label col-md-3">Nome da requisição</label>
                                                <div class="col-md-9">
                                                    <input name="numero" class="form-control" type="text"
                                                           placeholder="Nome da requisição de pessoal">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
                                                Salvar
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
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
                                    <hr>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Tipo de vaga <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-2">
                                            <select name="tipo_vaga" class="form-control">
                                                <option value="I">Interna</option>
                                                <option value="E">Externa</option>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-2">Data de abertura <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-2">
                                            <input name="data_abertura" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data"
                                                   type="text">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="vagas_deficiente" value="" type="checkbox"> Vagas para
                                                    deficientes
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($tipo != 'funcionario'): ?>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Status <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-md-2">
                                                <select name="status" class="form-control">
                                                    <option value="A">Ativa</option>
                                                    <option value="S">Suspensa</option>
                                                    <option value="C">Cancelada</option>
                                                    <option value="F">Fechada</option>
                                                    <option value="P">Fechada parcialmente</option>
                                                </select>
                                            </div>
                                            <label class="control-label col-md-1">Estágio <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-md-4">
                                                <select name="estagio" class="form-control">
                                                    <option value="1">01/10 - Alinhando perfil</option>
                                                    <option value="2">02/10 - Divulgando vagas</option>
                                                    <option value="3">03/10 - Tirando currículos</option>
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
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Departamento <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_depto', $deptos, '', 'id="depto" class="form-control estrutura"'); ?>
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
                                        <div class="col-md-9 requisitante" id="interno">
                                            <input name="requisitante_externo" class="form-control" type="text"
                                                   placeholder="Digite o nome do requisitante (externo)">
                                        </div>
                                        <div class="col-md-9 requisitante" id="externo">
                                            <?php echo form_dropdown('requisitante_interno', $requisitantes, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5>Dados do contrato e centro de custo</h5>
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
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Centro de custo</label>
                                        <div class="col-md-3">
                                            <input name="centro_custo" placeholder="Centro de custo"
                                                   class="form-control"
                                                   type="text">
                                        </div>
                                    </div>
                                    <hr>
                                    <h5>Dados da vaga</h5>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Cargo <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_cargo', $cargos, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Função <span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_funcao', $funcoes, '', 'class="form-control"'); ?>
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
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Colaborador(a) a ser substituído(a)<span
                                                    class="text-danger" id="substituto"> *</span></label>
                                        <div class="col-md-9">
                                            <input name="colaborador_substituto" class="form-control" type="text"
                                                   placeholder="Digite o nome do colaborador substituto">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Requisicao aprovada por</label>
                                        <div class="col-md-5">
                                            <input name="aprovado_por" class="form-control" type="text"
                                                   placeholder="Digite o nome do aprovador">
                                        </div>
                                        <label class="control-label col-md-2">Data de aprovação</label>
                                        <div class="col-md-2">
                                            <input name="data_aprovacao" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" type="text">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="control-label col-md-2">Benefícios</label>
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
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Local de trabalho <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <input name="local_trabalho" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Horário de trabalho <span
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
                                    <th>Candidato</th>
                                    <th>Data abertura</th>
                                    <th>Data aprovação</th>
                                    <th>Entrevista seleção</th>
                                    <th>Entrevista requisitante</th>
                                    <th>Data contratação</th>
                                    <th>Deficiência</th>
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

        </section>
    </section>

<?php require_once 'end_js.php'; ?>

    <script>
        var save_method;
        var table, table_aprovados;
        var tipo_empresa = <?php echo $tipo == 'empresa' ? 'true' : 'false'; ?>;
        var id_requisicao;

        function add_requisicao() {
            $('#modal_form').modal('show');
        }
    </script>

<?php require_once 'end_html.php'; ?>