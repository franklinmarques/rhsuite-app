<?php
require_once "header.php";
?>
    <style>
        .jstree-defaulto {
            color: #31708f;
        }

        .jstree-warning {
            color: #f0ad4e;
        }

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
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <?php $this->load->view('modal_processos', ['url' => 'funcionario/novo']); ?>
                            <i class="fa fa-user-plus"></i> Cadastrar novo colaborador CLT
                        </header>
                        <div class="panel-body">

                            <ul class="nav nav-tabs" role="tablist" style="font-size: small; font-weight: bolder;">
                                <li role="presentation" class="active">
                                    <a href="#dados" aria-controls="dados" role="tab" data-toggle="tab">Cadastro</a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">Prog.
                                        Integração</a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">Período
                                        Experiência</a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">Exames Periódicos</a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">Treinamentos</a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">Afastamentos</a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">Avaliações</a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">Faltas e atrasos</a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">PDIs</a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">Documentos</a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">Contratos</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="dados">

                                    <?php echo form_open('funcionario/cadastrar', 'id="form" data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
                                    <div class="row">
                                        <br>
                                        <label class="col-xs-3 control-label">Total de colaboradores</label>
                                        <div class="col-xs-7">
                                            <label class="visible-xs"></label>
                                            <?php if ($qtde_max_colaboradores === 'sem limite' or $qtde_colaboradores < $qtde_max_colaboradores): ?>
                                            <p class="bg-info text-info" style="padding: 5px;">
                                                <?php else: ?>
                                            <p class="bg-danger text-danger" style="padding: 5px;">
                                                <?php endif; ?>
                                                <small>Já cadastrados: <strong><?= $qtde_colaboradores; ?></strong>
                                                </small>
                                                <br>
                                                <small>Permitidos para esta licença:
                                                    <strong><?= $qtde_max_colaboradores; ?></strong></small>
                                            </p>
                                            <br>
                                        </div>
                                        <div class="col-xs-2 text-right">
                                            <button type="submit" name="submit" class="btn btn-success"><i
                                                        class="fa fa-save"></i>
                                                Cadastrar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Nome <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('funcionario', $funcionarios, '', 'class="combobox form-control"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Data nascimento</label>
                                                <div class="col-xs-4 controls">
                                                    <input type="text" name="data_nascimento" placeholder="dd/mm/aaaa"
                                                           value="" class="form-control text-center date"/>
                                                </div>
                                                <label class="col-xs-1 control-label">Sexo</label>
                                                <div class="col-xs-4 controls">
                                                    <select name="sexo" class="form-control">
                                                        <option value="">selecione...</option>
                                                        <option value="M">Masculino</option>
                                                        <option value="F">Feminino</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Cargo</label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('cargo', $cargo, '', 'class="form-control cargo_funcao"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Função</label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('funcao', $funcao, '', 'class="form-control cargo_funcao"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">RG</label>
                                                <div class="col-xs-9 controls">
                                                    <input id="rg" type="text" name="rg" placeholder="RG" value=""
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">CPF</label>
                                                <div class="col-xs-9 controls">
                                                    <input id="cpf" type="text" name="cpf" placeholder="CPF" value=""
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">CNPJ</label>
                                                <div class="col-xs-9 controls">
                                                    <input id="cnpj" type="text" name="cnpj" placeholder="CNPJ" value=""
                                                           class="form-control" disabled=""/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">PIS</label>
                                                <div class="col-xs-9 controls">
                                                    <input id="pis" type="text" name="pis" placeholder="PIS" value=""
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Telefone(s)</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="telefone" placeholder="Telefone" value=""
                                                           class="form-control tags" data-role="tagsinput"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">E-mail <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="email" placeholder="E-mail" value=""
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Senha <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-xs-9 controls">
                                                    <input type="password" name="senha" placeholder="Senha" value=""
                                                           class="form-control" autocomplete="new-password"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Confirmar Senha <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-xs-9 controls">
                                                    <input type="password" name="confirmarsenha"
                                                           placeholder="Confirmar Senha" value=""
                                                           class="form-control" autocomplete="new-password"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Contrato</label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('contrato', $contrato, '', 'class="combobox form-control"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Centro de custo</label>
                                                <div class="col-xs-6 controls">
                                                    <?php echo form_dropdown('centro_custo', $centro_custo, '', 'class="combobox form-control"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Nome do cartão VT</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="nome_cartao"
                                                           placeholder="Nome do cartão Vale Transporte"
                                                           value="" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Observações gerais
                                                    (histórico)</label>
                                                <div class="col-xs-9 controls">
                                                    <textarea name="observacoes_historico"
                                                              class="form-control"></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Tipo vínculo</label>
                                                <div class="col-xs-4 controls">
                                                    <select name="tipo_vinculo" class="form-control"
                                                            style="background-color: #ec971f">
                                                        <option value="1">CLT</option>
                                                        <option value="2">MEI</option>
                                                        <option value="3">PJ</option>
                                                        <option value="4">Autônomo</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Status</label>
                                                <div class="col-xs-6 controls">
                                                    <select name="status" class="form-control">
                                                        <option value="1">Ativo</option>
                                                        <option value="2">Inativo</option>
                                                        <option value="3">Em experiência</option>
                                                        <option value="4">Em desligamento</option>
                                                        <option value="5">Desligado</option>
                                                        <option value="6">Afastado (maternidade)</option>
                                                        <option value="7">Afastado (aposentadoria)</option>
                                                        <option value="8">Afastado (doença)</option>
                                                        <option value="9">Afastado (acidente)</option>
                                                        <option value="10">Desistiu da vaga</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Departamento</label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('depto', $depto, '', 'class="form-control estrutura"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Área/cliente</label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('area', $area, '', 'class="form-control estrutura"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Setor/unidade</label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('setor', $setor, '', 'class="form-control estrutura"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Nome da mãe</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="nome_mae" placeholder="Nome da mãe"
                                                           value="" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Nome do pai</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="nome_pai" placeholder="Nome do pai"
                                                           value="" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Município</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="municipio" placeholder="Nome do município"
                                                           value="" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Matrícula</label>
                                                <div class="col-xs-9 controls">
                                                    <input name="matricula" type="text" placeholder="Matrícula" value=""
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Nível de acesso</label>
                                                <div class="col-xs-6 controls">
                                                    <select name="nivel_acesso" class="form-control">
                                                        <option value="1">Administrador</option>
                                                        <option value="7">Presidente</option>
                                                        <option value="18">Diretor</option>
                                                        <option value="8">Gerente</option>
                                                        <option value="9">Coordenador</option>
                                                        <option value="15">Representante</option>
                                                        <option value="10">Supervisor</option>
                                                        <option value="19">Supervisor requisitante</option>
                                                        <option value="11">Encarregado</option>
                                                        <option value="12">Líder</option>
                                                        <option value="4">Colaborador CLT</option>
                                                        <option value="16">Colaborador MEI</option>
                                                        <option value="14">Colaborador PJ</option>
                                                        <option value="13">Cuidador Comunitário</option>
                                                        <option value="3">Gestor</option>
                                                        <option value="2">Multiplicador</option>
                                                        <option value="6">Selecionador</option>
                                                        <option value="5">Cliente</option>
                                                        <option value="17">Vistoriador</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Data de admissão</label>
                                                <div class="col-xs-4 controls">
                                                    <input type="text" name="data_admissao" id="data_admissao"
                                                           placeholder="dd/mm/aaaa" value=""
                                                           class="form-control text-center date"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Nome banco</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="nome_banco" placeholder="Nome do banco"
                                                           value="" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Conta</label>
                                                <div class="col-xs-5 controls">
                                                    <input type="text" name="conta_bancaria"
                                                           placeholder="Conta bancária"
                                                           value="" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Agência</label>
                                                <div class="col-xs-5 controls">
                                                    <input type="text" name="agencia_bancaria"
                                                           placeholder="Agência bancária"
                                                           value="" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Valor Vale Transporte</label>

                                                <div class="col-xs-5 controls">
                                                    <input type="text" name="valor_vt" placeholder="Valor VT"
                                                           value="" class="form-control"/>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div id="box-progresso" style="display: none;">
                                            <div class="form-group">
                                                <label class="col-xs-2 control-label">&nbsp;</label>
                                                <div class="col-xs-10 controls">
                                                    <div id="progresso" class="progress progress-mini pbar">
                                                        <div class="progress-bar progress-bar-success ui-progressbar-value"
                                                             style="width:0%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-10 col-xs-offset-2">
                                                <button type="submit" name="submit" class="btn btn-success"><i
                                                            class="fa fa-save"></i> Cadastrar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <?php if ($this->session->userdata('empresa') === '78'): ?>
                                            <fieldset>
                                                <legend>
                                                    <small>Sistema de gerenciamento de acesso à funcionalidades da
                                                        Plataforma
                                                    </small>
                                                </legend>
                                                <ul id="tree">
                                                    <li>
                                                        <input type="checkbox">
                                                        <i class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;Gestão
                                                        Operacional PAPD
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[PAPD][]"
                                                                       value="501">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Gerenciar
                                                                pacientes
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[PAPD][]"
                                                                       value="502">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Gestão
                                                                Atividades/Deficiências
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[PAPD][]"
                                                                       value="503">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Relatório
                                                                Totalização
                                                                Mensal
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[PAPD][]"
                                                                       value="510">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Gerenciar
                                                                Atendimentos
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox">
                                                        <i class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;Gestão
                                                        Operacional ST
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[ST][]"
                                                                       value="401">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Gestão de
                                                                Contratos
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[ST][]"
                                                                       value="402">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Totalização
                                                                Mensal
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[ST][]"
                                                                       value="403">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Relatórios
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[ST][]"
                                                                       value="410">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Apontamentos
                                                                Diários
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox">
                                                        <i class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;Gestão
                                                        Operacional CD
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[CD][]"
                                                                       value="601">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Gestão de
                                                                Contratos
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[CD][]"
                                                                       value="602">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Totalização
                                                                Mensal
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[CD][]"
                                                                       value="603">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Relatórios
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[CD][]"
                                                                       value="610">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Apontamentos
                                                                Diários
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox">
                                                        <i class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;Gestão
                                                        Operacional EI
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[EI][]"
                                                                       value="701">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Gestão de
                                                                Contratos
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[EI][]"
                                                                       value="702">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Totalização
                                                                Mensal
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[EI][]"
                                                                       value="703">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Relatórios
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[EI][]"
                                                                       value="710">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Apontamentos
                                                                Diários
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </fieldset>
                                        <?php endif; ?>
                                    </div>
                                    <?php echo form_close(); ?>

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
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-combobox/css/bootstrap-combobox.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.css"); ?>"/>

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Cadastrar  novo colaborador CLT';
        });
    </script>

    <script src="<?php echo base_url("assets/js/bootstrap-combobox/js/bootstrap-combobox.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

    <script>
        var table;

        $('.tags').tagsInput({width: 'auto', defaultText: 'Telefone', placeholderColor: '#999', delimiter: '/'});
        $('.date').mask('00/00/0000');
        // $('#rg').mask('00.000.000-0', {reverse: true});
        $('#cpf').mask('000.000.000-00', {reverse: true});
        $('#cnpj').mask('00.000.000/0000-00', {reverse: true});
        $('#pis').mask('00.000.000.000', {reverse: true});

        $(document).ready(function () {
            $('.combobox').combobox();
        });


        $('select[name="nivel_acesso"]').on('change', function () {
            if (['2', '4', '5', '6', '12', '14', '15', '16', '17'].indexOf(this.value) > 0) {
                $('#tree input[type="checkbox"]').prop('checked', false).trigger('change');
            }
        });


        $('#tree input[type="checkbox"]').change(function (e) {

            var checked = $(this).prop("checked"),
                container = $(this).parent(),
                siblings = container.siblings();

            container.find('input[type="checkbox"]').prop({
                indeterminate: false,
                checked: checked
            });

            function checkSiblings(el) {

                var parent = el.parent().parent(),
                    all = true;

                el.siblings().each(function () {
                    return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
                });

                if (all && checked) {

                    parent.children('input[type="checkbox"]').prop({
                        indeterminate: false,
                        checked: checked
                    });

                    checkSiblings(parent);

                } else if (all && !checked) {

                    parent.children('input[type="checkbox"]').prop("checked", checked);
                    parent.children('input[type="checkbox"]').prop("indeterminate", (parent.find('input[type="checkbox"]:checked').length > 0));
                    checkSiblings(parent);

                } else {

                    el.parents("li").children('input[type="checkbox"]').prop({
                        indeterminate: true,
                        checked: false
                    });

                }

            }

            checkSiblings(container);
        });

        $('.estrutura').on('change', function () {
            $.ajax({
                url: '<?php echo site_url('funcionario/atualizarEstrutura/') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    depto: $('#form [name="depto"]').val(),
                    area: $('#form [name="area"]').val(),
                    setor: $('#form [name="setor"]').val()
                },
                success: function (json) {
                    $('#form [name="area"]').html($(json.area).html());
                    $('#form [name="setor"]').html($(json.setor).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });

        $('.cargo_funcao').on('change', function () {
            $.ajax({
                url: '<?php echo site_url('funcionario/atualizarCargoFuncao/') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    cargo: $('#form [name="cargo"]').val(),
                    funcao: $('#form [name="funcao"]').val()
                },
                success: function (json) {
                    $('#form [name="funcao"]').html($(json.funcao).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });

        $('[name="tipo_vinculo"]').on('change', function () {
            if (this.value === '1') {
                $('#cnpj').prop('disabled', true);
                // $('#cpf, #pis').prop('disabled', false);
                $('#pis').prop('disabled', false);
            } else {
                $('#cnpj').prop('disabled', false);
                // $('#cpf, #pis').prop('disabled', true);
                $('#pis').prop('disabled', true);
            }
        });

        $('[name="status"]').on('change', function () {
            var email = $('[name="email"]').val();
            if (this.value === '5') {
                if (email.length > 0) {
                    $('[name="email"]').val('d' + email);
                }
            } else {
                $('[name="email"]').val(email.replace(/^d/g, ''));
            }
        });
    </script>

<?php
require_once "end_html.php";
?>