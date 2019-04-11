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
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por vaga</label>
                                        <select id="tipo_vaga" class="form-control filtro input-sm"
                                                autocomplete="off">
                                            <option value="">Todas</option>
                                            <option value="I">Interna</option>
                                            <option value="E">Externa</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por status</label>
                                        <select id="status" class="form-control filtro input-sm"
                                                autocomplete="off">
                                            <option value="">Todas</option>
                                            <option value="A">Ativas</option>
                                            <option value="S">Suspensas</option>
                                            <option value="C">Canceladas</option>
                                            <option value="F">Fechadas</option>
                                            <option value="P">Fechadas parcialmente</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por estágio</label>
                                        <select id="estagio" class="form-control filtro input-sm"
                                                autocomplete="off">
                                            <option value="">Todos</option>
                                            <option value="1">Alinhando perfil</option>
                                            <option value="2">Divulgando vagas</option>
                                            <option value="3">Tirando currículos</option>
                                            <option value="4">Convocando candidatos</option>
                                            <option value="5">Entrevistando candidatos</option>
                                            <option value="6">Elaborando pareceres</option>
                                            <option value="7">Aguardando gestor</option>
                                            <option value="8">Entrevista solicitante</option>
                                            <option value="9">Exame adissional</option>
                                            <option value="10">Entrega documentos</option>
                                            <option value="11">Faturamento</option>
                                            <option value="12">Processo finalizado</option>
                                        </select>
                                    </div>
                                    <!--<div class="col-md-3">
                                        <label>&nbsp;</label><br>
                                        <?php /*echo form_dropdown('contrato', $contratos, '', 'id="contrato" class="form-control filtro input-sm"'); */ ?>
                                    </div>-->
                                    <div class="col-md-2">
                                        <label>&nbsp;</label><br>
                                        <div class="btn-group" role="group" aria-label="...">
                                            <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
                                                Limpar filtros
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por departamento</label>
                                        <?php echo form_dropdown('', $deptos, '', 'id="id_depto" class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por área</label>
                                        <?php echo form_dropdown('', $areas, '', 'id="id_area" class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por setor</label>
                                        <?php echo form_dropdown('', $setores, '', 'id="id_setor" class="form-control filtro input-sm"'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por cargo</label>
                                        <?php echo form_dropdown('', $cargos, '', 'id="id_cargo" class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por função</label>
                                        <?php echo form_dropdown('', $funcoes, '', 'id="id_funcao" class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <!--<div class="col-md-4">
                                        <label class="control-label">Filtrar por requisitante externo</label>
                                        <?php /*echo form_dropdown('requisitante_externo', $requisitantes, '', 'id="requisitantes" class="form-control filtro input-sm"'); */ ?>
                                    </div>-->
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
                            <th>Cargo/função</th>
                            <th>Depto/área/setor</th>
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
                            <form action="#" id="form" class="form-horizontal">
                                <!--                                <input type="hidden" value="" name="id"/>-->
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
                                    <hr>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Tipo de vaga</label>
                                        <div class="col-md-2">
                                            <select name="tipo_vaga" class="form-control">
                                                <option value="I">Interna</option>
                                                <option value="E">Externa</option>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-2">Data de abertura</label>
                                        <div class="col-md-2">
                                            <input name="data_abertura" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data"
                                                   type="text">
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input name="vagas_deficiente" value="" type="checkbox"> Vagas para
                                                deficientes
                                            </label>
                                        </div>
                                    </div>
                                    <?php if ($tipo != 'funcionario'): ?>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Status</label>
                                            <div class="col-md-2">
                                                <select name="status" class="form-control">
                                                    <option value="A">Ativa</option>
                                                    <option value="S">Suspensa</option>
                                                    <option value="C">Cancelada</option>
                                                    <option value="F">Fechada</option>
                                                    <option value="P">Fechada parcialmente</option>
                                                </select>
                                            </div>
                                            <label class="control-label col-md-1">Estágio</label>
                                            <div class="col-md-4">
                                                <select name="estagio" class="form-control">
                                                    <option value="1">Alinhando perfil</option>
                                                    <option value="2">Divulgando vagas</option>
                                                    <option value="3">Tirando currículos</option>
                                                    <option value="4">Convocando candidatos</option>
                                                    <option value="5">Entrevistando candidatos</option>
                                                    <option value="6">Elaborando pareceres</option>
                                                    <option value="7">Aguardando gestor</option>
                                                    <option value="8">Entrevista solicitante</option>
                                                    <option value="9">Exame adissional</option>
                                                    <option value="10">Entrega documentos</option>
                                                    <option value="11">Faturamento</option>
                                                    <option value="12">Processo finalizado</option>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Departamento</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_depto', $deptos, '', 'id="depto" class="form-control estrutura"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Área</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_area', $areas, '', 'id="area" class="form-control estrutura"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Setor</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_setor', $setores, '', 'id="setor" class="form-control estrutura"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Requisitante</label>
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
                                        <label class="control-label col-md-2">N&ordm; contrato <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-3">
                                            <input name="numero_contrato" placeholder="Número do contrato"
                                                   class="form-control" type="text">
                                        </div>
                                        <label class="control-label col-md-3">Regime contratação</label>
                                        <div class="col-md-2">
                                            <select name="regime_contratacao" class="form-control">
                                                <option value="1">CLT</option>
                                                <option value="2">MEI</option>
                                                <option value="3">PJ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Centro de custo <span class="text-danger">*</span></label>
                                        <div class="col-md-3">
                                            <input name="centro_custo" placeholder="Centro de custo"
                                                   class="form-control"
                                                   type="text">
                                        </div>
                                    </div>
                                    <hr>
                                    <h5>Dados da vaga</h5>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Cargo</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_cargo', $cargos, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Função</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_funcao', $funcoes, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Quantidade de vagas</label>
                                        <div class="col-md-3">
                                            <input name="numero_vagas" class="form-control" type="number" min="0"
                                                   step="1">
                                        </div>
                                        <label class="control-label col-md-3">Justificativa da contratação</label>
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
                                        <label class="control-label col-md-2">Colaborador(a) Substituto(a)<span
                                                    class="text-danger" id="substituto"> *</span></label>
                                        <div class="col-md-9">
                                            <input name="colaborador_substituto" class="form-control" type="text"
                                                   placeholder="Digite o nome do colaborador substituto">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Benefícios</label>
                                        <div class="col-md-9">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="vale_refeicao" value="1"> Vale alimentação
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="vale_transporte" value="1"> Vale transporte
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="vale_refeicao" value="1"> Vale refeição
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="assistencia_medica" value="1"> Assistência
                                                médica
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="plano_odontologico" value="1"> Plano
                                                odontológico
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="cesta_basica" value="1"> Cesta básica
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="participacao_resultados" value="1">
                                                Participação em resultados
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Remuneração mensal</label>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1">R$</span>
                                                <input name="remuneracao_mensal" class="form-control text-right valor"
                                                       type="text">
                                            </div>
                                        </div>
                                        <label class="control-label col-md-1">Horário trabalho</label>
                                        <div class="col-md-2">
                                            <input name="horario_trabalho" class="form-control" type="text">
                                        </div>
                                        <label class="control-label col-md-1">Previsão início</label>
                                        <div class="col-md-2">
                                            <input name="previsao_inicio" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Local de trabalho</label>
                                        <div class="col-md-9">
                                            <input name="local_trabalho" class="form-control" type="text">
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
                                        <label class="control-label col-md-3">Perfil geral</label>
                                        <div class="col-md-8">
                                            <textarea name="perfil_geral" class="form-control"
                                                      cols="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Competências técnicas necessárias</label>
                                        <div class="col-md-8">
                                            <textarea name="competencias_tecnicas" class="form-control"
                                                      cols="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Competências comportamentais
                                            necessárias</label>
                                        <div class="col-md-8">
                                            <textarea name="competencias_comportamentais" class="form-control"
                                                      cols="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Atividaes e Responsabilidades associadas
                                            ao cargo-função</label>
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
                                    <th>Data aprovação</th>
                                    <th>Data contratação</th>
                                    <th>Deficiência</th>
                                    <th>CID</th>
                                    <th>Cota</th>
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

    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Requisições de Pessoal';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table, table_aprovados;
        var tipo_empresa = <?php echo $tipo == 'empresa' ? 'true' : 'false'; ?>;

        $(document).ready(function () {

            $('.data').mask('00/00/0000');
            $('.valor').mask('##.###.##0,00', {reverse: true});

            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: -1,
                lengthMenu: [[5, 10, 25, 50, 100, 500, -1], [5, 10, 25, 50, 100, 500, 'Todos']],
                language: {
                    url: '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                ajax: {
                    url: '<?php echo site_url('requisicaoPessoal/ajax_list/') ?>',
                    type: 'POST',
                    data: function (d) {
                        d.tipo_vaga = $('#tipo_vaga').val();
                        d.status = $('#status').val();
                        d.estagio = $('#estagio').val();
                        d.id_depto = $('#id_depto').val();
                        d.id_area = $('#id_area').val();
                        d.id_setor = $('#id_setor').val();
                        d.id_cargo = $('#id_cargo').val();
                        d.id_funcao = $('#id_funcao').val();

                        return d;
                    }
                },
                columnDefs: [
                    {
                        visible: false,
                        targets: tipo_empresa ? [4, 6, 9, 10] : [2, 6, 8]
                    },
                    {
                        width: '20%',
                        targets: [3, 7]
                    },
                    {
                        width: '25%',
                        targets: [4]
                    },
                    {
                        width: '35%',
                        targets: [5]
                    },
                    {
                        searchable: false,
                        targets: [1, 2, 3, 6, 7, 8, 9, 10, 11]
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
                }
            });

        });

        $('[name="tipo_vaga"]').on('change', function () {
            if (this.value === 'I') {
                $('#externo').show();
                $('#interno').hide();
                $('.estrutura').prop('disabled', false);
            } else if (this.value === 'E') {
                $('#interno').show();
                $('#externo').hide();
                $('.estrutura').val('').prop('disabled', true);
            }
        });

        $('.estrutura').on('change', function () {
            atualizarEstrutura();
        });

        $('[name="id_cargo"]').on('change', function () {
            atualizarFuncao();
        });

        $('[name="justificativa_contratacao"]').on('change', function () {
            if (this.value === 'S') {
                $('#substituto').show();
                $('[name="colaborador_substituto"]').prop('disabled', false);
            } else {
                $('[name="colaborador_substituto"]').prop('disabled', true);
                $('#substituto').hide();
            }
            /*if (this.value === 'A') {
                $('[name="aprovado_por"], [name="data_aprovacao"').prop('disabled', false);
            } else {
                $('[name="aprovado_por"], [name="data_aprovacao"').prop('disabled', true);
            }*/
        });

        $('.filtro').on('change', function () {
            reload_table();
        });

        $('#limpa_filtro').on('click', function () {
            $('.filtro').val('');
            reload_table();
        });

        function atualizarEstrutura() {
            $.ajax({
                url: "<?php echo site_url('requisicaoPessoal/atualizarEstrutura/') ?>",
                type: "POST",
                dataType: "JSON",
                data: $('.estrutura, [name="requisitante_interno"]').serialize(),
                success: function (json) {
                    $('#area').html($(json.area).html());
                    $('#setor').html($(json.setor).html());
                    $('[name="requisitante_interno"]').html($(json.requisitante).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function atualizarFuncao() {
            $.ajax({
                url: "<?php echo site_url('requisicaoPessoal/atualizarFuncao/') ?>",
                type: "POST",
                dataType: "JSON",
                data: $('[name="id_cargo"], [name="id_funcao"]').serialize(),
                success: function (json) {
                    $('[name="id_funcao"]').html($(json.funcao).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
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

            $.ajax({
                url: '<?php echo site_url('requisicaoPessoal/ajax_nextId/') ?>',
                type: 'POST',
                dataType: 'JSON',
                success: function (json) {
                    $('[name="id"]').val(json.id);

                    $('#externo').show();
                    $('#interno').hide();
                    $('.estrutura').prop('disabled', false);
                    $('[name="justificativa_contratacao"]').trigger('change');
                    atualizarEstrutura();
                    atualizarFuncao();
                    $('#modal_form').modal('show');
                    $('.modal-title').text('Adicionar requisição de pessoal');
                    $('.combo_nivel1').hide();

                },
                error: function (jqXHR, textStatus, errorThrown) {
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

            $.ajax({
                url: '<?php echo site_url('requisicaoPessoal/ajax_edit/') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (json) {
                    var input = json.input;
                    var data = json.data;

                    $('#area').html($(input.area).html());
                    $('#setor').html($(input.setor).html());
                    $('[name="id_funcao"]').html($(input.funcao).html());
                    $('[name="requisitante_interno"]').html($(input.requisitante).html());

                    $.each(data, function (key, value) {
                        if ($('[name="' + key + '"]').is(':checkbox') === false) {
                            $('[name="' + key + '"]').val(value);
                        } else {
                            $('[name="' + key + '"]').prop('checked', value === '1');
                        }
                    });
                    $('[name="justificativa_contratacao"]').trigger('change');

                    if (data.tipo_vaga === 'I') {
                        $('#externo').show();
                        $('#interno').hide();
                        $('.estrutura').prop('disabled', false);
                    } else if (data.tipo_vaga === 'E') {
                        $('#interno').show();
                        $('#externo').hide();
                        $('.estrutura').val('').prop('disabled', true);
                    }

                    $('#modal_form').modal('show');

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function mostrar_aprovados() {
            $('#modal_aprovados').modal('show');
        }

        function reload_table() {
            table.ajax.reload(null, false);
        }

        function save() {
            $('#btnSave').text('Salvando...');
            $('#btnSave').attr('disabled', true);
            var url;

            if (save_method === 'add') {
                url = '<?php echo site_url('requisicaoPessoal/ajax_add') ?>';
            } else {
                url = '<?php echo site_url('requisicaoPessoal/ajax_update') ?>';
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: $('#form').serialize(),
                dataType: 'JSON',
                success: function (data) {
                    if (data.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }

                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_requisicao(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    url: '<?php echo site_url('requisicaoPessoal/ajax_delete') ?>/',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id},
                    success: function (data) {
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

    </script>

<?php require_once 'end_html.php'; ?>