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
                            <?php $this->load->view('modal_processos', ['url' => 'funcionario/editar']); ?>
                            <i class="fa fa-pencil-square-o"></i> Gerenciar colaborador CLT
                            - <?php echo $row->nome; ?>
                        </header>
                        <div class="panel-body">

                            <ul class="nav nav-tabs" role="tablist" style="font-size: small; font-weight: bolder;">
                                <li role="presentation" class="active">
                                    <a href="#dados" aria-controls="dados" role="tab" data-toggle="tab">Cadastro</a>
                                </li>
                                <li role="presentation">
                                    <a href="#integracao" aria-controls="integracao" role="tab" data-toggle="tab">Prog.
                                        Integração</a>
                                </li>
                                <li role="presentation">
                                    <a href="#exp_periodo" aria-controls="exp_periodo" role="tab" data-toggle="tab">Período
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
                                    <a href="#afastamentos" aria-controls="afastamentos" role="tab" data-toggle="tab">Afastamentos</a>
                                </li>
                                <li role="presentation">
                                    <a href="#exp_desempenho" aria-controls="exp_desempenho" role="tab"
                                       data-toggle="tab">Avaliações</a>
                                </li>
                                <li role="presentation">
                                    <a href="#faltas_atrasos" aria-controls="faltas_atrasos" role="tab"
                                       data-toggle="tab">Faltas e atrasos</a>
                                </li>
                                <li role="presentation">
                                    <a href="#pdis" aria-controls="pdis" role="tab" data-toggle="tab">PDIs</a>
                                </li>
                                <li role="presentation">
                                    <a href="#documentos" aria-controls="documentos" role="tab" data-toggle="tab">Documentos</a>
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
                                <div role="tabpanel" class="tab-pane active" id="dados">

                                    <?php echo form_open('funcionario/alterar/' . $row->id, 'id="form" data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
                                    <div class="row">
                                        <br>
                                        <label class="col-xs-2 control-label">Trocar funcionário</label>
                                        <div class="col-xs-7">
                                            <div class="input-group" id="trocar_funcionario_nome">
                                                <?php echo form_dropdown('', $funcionarios, $row->id, 'id="busca_nome" class="combobox form-control"'); ?>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button"
                                                            onclick="trocar_colaborador(<?= $row->id; ?>)">
                                                        <i class="glyphicon glyphicon-pencil"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            <div class="input-group" id="trocar_funcionario_matricula"
                                                 style="display: none;">
                                                <?php echo form_dropdown('', $matriculas, $row->id, 'id="busca_matricula" class="combobox form-control"'); ?>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button"
                                                            onclick="trocar_colaborador(<?= $row->id; ?>)">
                                                        <i class="glyphicon glyphicon-pencil"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            <span class="" id="help_busca"></span>
                                        </div>
                                        <div class="col-xs-3 text-right">
                                            <button type="submit" name="submit" class="btn btn-success"><i
                                                        class="fa fa-save"></i>
                                                Salvar
                                            </button>
                                            <button class="btn btn-default" onclick="javascript:history.back()"><i
                                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-7 col-xs-offset-2">
                                            <label class="radio-inline">
                                                <input type="radio" name="tipo_colaborador" value="nome" checked="">
                                                Por nome
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="tipo_colaborador" value="matricula"> Por
                                                matrícula
                                            </label>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Nome<span
                                                            class="text-danger">*</span></label>
                                                <div class="col-xs-9 controls">
                                                    <!--                                                    --><?php //echo form_dropdown('funcionario', $funcionarios, $row->nome, 'id="funcionario" class="combobox form-control"'); ?>
                                                    <input name="funcionario" value="<?= $row->nome; ?>" type="text"
                                                           class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Data nascimento</label>
                                                <div class="col-xs-4 controls">
                                                    <input type="text" name="data_nascimento" placeholder="dd/mm/aaaa"
                                                           value="<?= $row->data_nascimento ?>"
                                                           class="form-control text-center date"/>
                                                </div>
                                                <label class="col-xs-1 control-label">Sexo</label>
                                                <div class="col-xs-4 controls">
                                                    <?php echo form_dropdown('sexo', $sexo, $row->sexo, 'class="form-control"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Cargo</label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('cargo', $cargo, $row->id_cargo1, 'class="form-control cargo_funcao"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Função</label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('funcao', $funcao, $row->id_funcao1, 'class="form-control cargo_funcao"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">RG</label>
                                                <div class="col-xs-9 controls">
                                                    <input id="rg" type="text" name="rg" placeholder="RG"
                                                           value="<?php echo $row->rg; ?>" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">CPF</label>
                                                <div class="col-xs-9 controls">
                                                    <input id="cpf" type="text" name="cpf" placeholder="CPF"
                                                           value="<?php echo $row->cpf; ?>" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">CNPJ</label>
                                                <div class="col-xs-9 controls">
                                                    <input id="cnpj" type="text" name="cnpj"
                                                           placeholder="CNPJ"<?= $row->tipo_vinculo === '1' ? ' disabled=""' : ''; ?>
                                                           value="<?php echo $row->cnpj; ?>" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">PIS</label>
                                                <div class="col-xs-9 controls">
                                                    <input id="pis" type="text" name="pis"
                                                           placeholder="PIS"<?= $row->tipo_vinculo !== '1' ? ' disabled=""' : ''; ?>
                                                           value="<?php echo $row->pis; ?>"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Telefone(s)</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="telefone" placeholder="Telefone"
                                                           value="<?php echo $row->telefone; ?>"
                                                           class="form-control tags"
                                                           data-role="tagsinput"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">E-mail<span
                                                            class="text-danger">*</span></label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="email" placeholder="E-mail"
                                                           value="<?php echo $row->email; ?>" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Data de cadastro</label>
                                                <div class="col-xs-4 controls">
                                                    <input type="text" name="datacadastro" id="datacadastro"
                                                           placeholder="dd/mm/aaaa"
                                                           value="<?php echo $row->datacadastro; ?>"
                                                           class="form-control text-center date"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Contrato</label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('contrato', $contrato, $row->contrato, 'class="combobox form-control"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Centro de custo</label>
                                                <div class="col-xs-6 controls">
                                                    <?php echo form_dropdown('centro_custo', $centro_custo, $row->centro_custo, 'class="combobox form-control"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Nome do cartão VT</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="nome_cartao"
                                                           placeholder="Nome do cartão Vale Transporte"
                                                           value="<?php echo $row->nome_cartao; ?>"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Valor Vale Transporte</label>

                                                <div class="col-xs-5 controls">
                                                    <input type="text" name="valor_vt" placeholder="Valor VT"
                                                           value="<?php echo $row->valor_vt; ?>" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Nome banco</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="nome_banco" placeholder="Nome do banco"
                                                           value="<?php echo $row->nome_banco; ?>"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Conta</label>
                                                <div class="col-xs-5 controls">
                                                    <input type="text" name="conta_bancaria"
                                                           placeholder="Conta bancária"
                                                           value="<?php echo $row->conta_bancaria; ?>"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Agência</label>
                                                <div class="col-xs-5 controls">
                                                    <input type="text" name="agencia_bancaria"
                                                           placeholder="Agência bancária"
                                                           value="<?php echo $row->agencia_bancaria; ?>"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Observações gerais
                                                    (histórico)</label>
                                                <div class="col-xs-9 controls">
                                                    <textarea name="observacoes_historico"
                                                              class="form-control"><?= $row->observacoes_historico; ?></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Tipo vínculo</label>
                                                <div class="col-xs-4 controls">
                                                    <?php echo form_dropdown('tipo_vinculo', $tipo_vinculo, $row->tipo_vinculo, 'class="form-control" style="background-color: #ec971f"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Status</label>
                                                <div class="col-xs-6 controls">
                                                    <?php echo form_dropdown('status', $status, $row->status, 'class="form-control"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Departamento</label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('depto', $depto, $row->id_depto1, 'class="form-control estrutura"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Área/cliente</label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('area', $area, $row->id_area1, 'class="form-control estrutura"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Setor/unidade</label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('setor', $setor, $row->id_setor1, 'class="form-control estrutura"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Nome da mãe</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="nome_mae" placeholder="Nome da mãe"
                                                           value="<?= $row->nome_mae ?>" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Nome do pai</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="nome_pai" placeholder="Nome do pai"
                                                           value="<?= $row->nome_pai ?>" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Município</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="text" name="municipio" placeholder="Nome do município"
                                                           value="<?= $row->municipio ?>" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Obs:</label>
                                                <div class="col-xs-9 controls">
                                                    (Caso não queira alterar a senha, deixe os campos abaixo em branco)
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Senha</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="password" name="senha" placeholder="Senha" value=""
                                                           class="form-control" autocomplete="new-password"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Confirmar Senha</label>
                                                <div class="col-xs-9 controls">
                                                    <input type="password" name="confirmarsenha"
                                                           placeholder="Confirmar Senha" value=""
                                                           class="form-control" autocomplete="new-password"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Matrícula</label>
                                                <div class="col-xs-9 controls">
                                                    <input name="matricula" type="text" placeholder="Matrícula"
                                                           value="<?php echo $row->matricula; ?>" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Nível de acesso</label>
                                                <div class="col-xs-6 controls">
                                                    <?php echo form_dropdown('nivel_acesso', $nivel_acesso, $row->nivel_acesso, 'class="form-control"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Data de admissão</label>
                                                <div class="col-xs-4 controls">
                                                    <input type="text" name="data_admissao" id="data_admissao"
                                                           placeholder="dd/mm/aaaa"
                                                           value="<?php echo $row->data_admissao; ?>"
                                                           class="form-control text-center date"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Data de demissão</label>
                                                <div class="col-xs-4 controls">
                                                    <input name="data_demissao" type="text" placeholder="dd/mm/aaaa"
                                                           value="<?php echo $row->data_demissao; ?>"
                                                           class="form-control text-center date"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Tipo de demissão</label>
                                                <div class="col-xs-9 controls">
                                                    <?php echo form_dropdown('tipo_demissao', $tipo_demissao, $row->tipo_demissao, 'class="form-control"'); ?>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-xs-3 control-label">Observações sobre a
                                                    demissão</label>
                                                <div class="col-xs-9 controls">
                                                    <textarea name="observacoes_demissao"
                                                              class="form-control"><?= $row->observacoes_demissao; ?></textarea>
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
                                                            class="fa fa-save"></i> Salvar
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
                                                        Operacional PAPD (Programa de Apoio a Pessoa com Deficiência -
                                                        Barueri)
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[PAPD][]"
                                                                       value="501">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Gerenciar pacientes
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[PAPD][]"
                                                                       value="502">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Gestão Atividades/Deficiências
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[PAPD][]"
                                                                       value="503">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Relatório Totalização Mensal
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[PAPD][]"
                                                                       value="510">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Gerenciar Atendimentos
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox">
                                                        <i class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;Gestão
                                                        Operacional ST (Serviços Terceirizados)
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[ST][]"
                                                                       value="401">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Gestão de Contratos
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[ST][]"
                                                                       value="402">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Totalização Mensal
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
                                                                Apontamentos Diários
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox">
                                                        <i class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;Gestão
                                                        Operacional CD (Cuidadores)
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[CD][]"
                                                                       value="601">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Gestão de Contratos
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[CD][]"
                                                                       value="602">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Totalização Mensal
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
                                                                Apontamentos Diários
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox">
                                                        <i class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;Gestão
                                                        Operacional EI (Educação Inclusiva)
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[EI][]"
                                                                       value="701">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Gestão de Contratos
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="hash_acesso[EI][]"
                                                                       value="702">
                                                                <i class="glyphicon glyphicon-file text-info"></i>
                                                                Totalização Mensal
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
                                                                Apontamentos Diários
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </fieldset>
                                        <?php endif; ?>
                                    </div>
                                    <?php echo form_close(); ?>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="integracao">

                                    <?php echo form_open('funcionario/salvarIntegracao/' . $row->id, 'id="form_integracao" data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
                                    <div class="row">
                                        <div class="col-xs-12 text-right">
                                            <br>
                                            <button type="submit" name="submit" class="btn btn-success"><i
                                                        class="fa fa-save"></i>
                                                Salvar
                                            </button>
                                            <button class="btn btn-default" onclick="javascript:history.back()"><i
                                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                            </button>
                                            <br>
                                            <br>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-xs-2 control-label">Data de início</label>
                                                <div class="col-xs-2 controls">
                                                    <input type="text" name="data_inicio" placeholder="dd/mm/aaaa"
                                                           value="<?php echo $integracao->data_inicio; ?>"
                                                           class="data form-control text-center date"/>
                                                </div>
                                                <label class="col-xs-2 control-label">Data de término</label>
                                                <div class="col-xs-2 controls">
                                                    <input type="text" name="data_termino" placeholder="dd/mm/aaaa"
                                                           value="<?php echo $integracao->data_termino; ?>"
                                                           class="data form-control text-center date"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-xs-2 control-label">Atividades desenvolvidas</label>
                                                <div class="col-xs-10 controls">
                                                    <textarea name="atividades_desenvolvidas" rows="4"
                                                              class="form-control"><?php echo $integracao->atividades_desenvolvidas; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-xs-2 control-label">Realizadores do programa</label>
                                                <div class="col-xs-10 controls">
                                                    <textarea name="realizadores" maxlength="256"
                                                              class="form-control"><?php echo $integracao->realizadores; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-xs-2 control-label">Observações</label>
                                                <div class="col-xs-10 controls">
                                                    <textarea name="observacoes" rows="4"
                                                              class="form-control"><?php echo $integracao->observacoes; ?></textarea>
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
                                                            class="fa fa-save"></i> Salvar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="exp_periodo">

                                    <?php $this->load->view('funcionario_periodo1', $data_avaliado1); ?>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="exames_periodicos">

                                    <?php $this->load->view('funcionario_exame1', $data_exame1); ?>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="treinamentos">

                                    <?php $this->load->view('funcionario_treinamento1', $data_avaliado1); ?>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="afastamentos">

                                    <?php $this->load->view('funcionario_afastamento1', $data_afastamento1); ?>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="exp_desempenho">

                                    <?php $this->load->view('funcionario_desempenho1', $data_avaliado1); ?>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="faltas_atrasos">

                                    <?php $this->load->view('funcionario_faltasAtrasos1', $data_avaliado1); ?>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="pdis">

                                    <?php $this->load->view('funcionario_pdi1', $data_pdi1); ?>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="documentos">

                                    <?php $this->load->view('funcionario_documentos1', $data_documentos1); ?>

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
    <link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.css"); ?>">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar colaborador CLT - <?php echo $row->nome; ?>';
        });
    </script>

    <script src="<?php echo base_url("assets/js/bootstrap-combobox/js/bootstrap-combobox.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>
    <!--    <script src="--><?php //echo base_url('assets/JQuery-Mask/jquery.mask.js') ?><!--"></script>-->

    <script>
        var table;

        $('.tags').tagsInput({width: 'auto', defaultText: 'Telefone', placeholderColor: '#999', delimiter: '/'});
        $('.date').mask('00/00/0000');
        // $('#rg').mask('##00.000.000-0', {reverse: true});
        $('#cpf').mask('000.000.000-00', {reverse: true});
        $('#cnpj').mask('00.000.000/0000-00', {reverse: true});
        $('#pis').mask('00.000.000.000', {reverse: true});

        $(document).ready(function () {

            $('.combobox').combobox();

            var hash_acesso = <?= $row->hash_acesso ?>;
            if (hash_acesso !== null) {
                $.each(hash_acesso, function (i, item) {
                    $.each(item, function (a, value) {
                        $('input[name="hash_acesso[' + i + '][]"][value="' + value + '"]').trigger('click');
                    });
                });
            }

            $('input[name="tipo_colaborador"]').on('change', function () {
                if (this.value === 'nome') {
                    $('#trocar_funcionario_nome').show();
                    $('#trocar_funcionario_matricula').hide();
                } else if (this.value === 'matricula') {
                    $('#trocar_funcionario_matricula').show();
                    $('#trocar_funcionario_nome').hide();
                }
            });

        });

        // Ajusta a largura das colunas dos tabelas do tipo DataTables em uma aba
        $(document).on('shown.bs.tab', function () {
            $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
        });

        $('#busca_nome').on('change', function () {
            $('#busca_matricula').val(this.value).combobox('refresh');
        });
        $('#busca_matricula').on('change', function () {
            $('#busca_nome').val(this.value).combobox('refresh');
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

        function trocar_colaborador(id_atual) {
            $('#help_busca').html('').removeClass('text-danger');
            $.ajax({
                url: "<?php echo site_url('funcionario/verificar') ?>",
                type: "POST",
                data: {
                    busca_nome: $('#busca_nome').val(),
                    busca_matricula: $('#busca_matricula').val()
                },
                dataType: "json",
                success: function (json) {
                    if (json.id === null) {
                        $('#help_busca').addClass('text-danger').html('Selecione um dos colaboradores pré-definidos na lista acima.');
                    } else if (json.id !== id_atual.toString()) {
                        location.href = '<?php echo site_url('funcionario/editar'); ?>/' + json.id;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.statusText === 'OK') {
                        alert(jqXHR.responseText);
                    } else {
                        alert('Erro ao verificar colaborador');
                    }
                }
            });
        }

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