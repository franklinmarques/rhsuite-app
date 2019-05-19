<?php require_once 'header.php'; ?>

<style>

    .btn-primary {
        background-color: #337ab7 !important;
        border-color: #2e6da4 !important;
        color: #fff;
    }

    label.control-label {
        font-weight: bold;
    }

    ul.nav-pills li a {
        border: 1px solid #ccc;
        font-size: 12px;
        font-weight: bold;
    }

    ul.nav-pills li.active a {
        border-color: #2e6da4;
    }

    ul.nav-pills li.disable a {
        color: #777;
        text-decoration: none;
        background-color: transparent;
        cursor: not-allowed;
    }

    ul.nav-pills li.disable.active a {
        background-color: #78a6ce;
        border: 1px solid #77a5cd;
        color: #fdfdfd;
        border-radius: 4px;
    }

</style>

<section id="main-content" class="merge-left">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <a class="btn btn-primary" href="<?= site_url('home/sair'); ?>" style="float:right;"><i
                            class="fa fa-power-off"></i>
                    Desconectar</a>

                <ul class="nav nav-tabs" role="tablist"
                    style="font-size: 15px; font-weight: bolder;">
                    <li role="presentation" class="active">
                        <a href="#ver_vagas" aria-controls="ver_vagas" role="tab" data-toggle="tab">Vagas</a>
                    </li>
                    <li role="presentation">
                        <a href="#meu_cadastro" aria-controls="meu_cadastro" role="tab" data-toggle="tab">Meu
                            cadastro</a>
                    </li>
                    <li role="presentation">
                        <a href="#meus_testes" aria-controls="meus_testes" role="tab" data-toggle="tab">Meus testes
                            seletivos</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="ver_vagas">

                        <div class="page-header text-primary">
                            <h3><strong>Caro candidato, seja bem-vindo ao nosso painel de vagas!</strong></h3>
                        </div>
                        <h5 class="text-primary">Caso algumas das vagas seja de seu interesse, basta acionar o botão
                            "Candidatar-se!" que você será automaticamente incluído no processo seletivo da mesma.</h5>

                        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th nowrap>Código vaga</th>
                                <th>Ações</th>
                                <th>Abertura</th>
                                <th>Função</th>
                                <th>Qtde.</th>
                                <th>Cidade</th>
                                <th>Bairro</th>
                                <th>Remuneração (R$)</th>
                                <th>Tipo vínculo</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="meu_cadastro">

                        <div class="page-header text-primary">
                            <h3><strong>Caro candidato, utilize os botões abaixo para atualizar os seus dados
                                    cadastrais.</strong></h3>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <ul class="nav nav-pills" role="tablist">
                                    <li role="presentation" class="active" style="margin: 0 5px;">
                                        <a href="#dados_cadastrais" aria-controls="dados_cadastrais" role="tab"
                                           data-toggle="pill">1. Dados cadastrais</a>
                                    </li>
                                    <li role="presentation" style="margin: 0 5px;">
                                        <a href="#formacao" aria-controls="formacao" role="tab" data-toggle="pill">2.
                                            Formação</a>
                                    </li>
                                    <li role="presentation" style="margin: 0 5px;">
                                        <a href="#historico_profissional" aria-controls="historico_profissional"
                                           role="tab"
                                           data-toggle="pill">3. Histórico profissional</a>
                                    </li>
                                    <li role="presentation" style="margin: 0 5px;">
                                        <a href="#curriculo" aria-controls="curriculo" role="tab" data-toggle="pill">4.
                                            Currículo</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <hr>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="dados_cadastrais">
                                <div class="row">
                                    <div class="col col-md-12">
                                        <?php echo form_open_multipart('candidatoVagas/salvarDadosCadastrais', 'id="form_candidato" data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
                                        <fieldset>
                                            <legend>Campos obrigatórios</legend>
                                            <div class="form-group last">
                                                <label class="col-sm-2 control-label">Foto</label>
                                                <div class="col-sm-7 controls">
                                                    <div class="fileinput fileinput-new"
                                                         data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail"
                                                             style="width: auto; height: 150px;">
                                                            <?php if (empty($candidato->foto)): ?>
                                                                <img src="<?= base_url('imagens/usuarios/Sem+imagem.png') ?>"
                                                                     alt="Sem imagem"/>
                                                            <?php else: ?>
                                                                <img src="<?= base_url('imagens/usuarios/' . $candidato->foto) ?>"
                                                                     alt="<?= $candidato->foto ?>"/>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                                             style="width: auto; height: 150px;"></div>
                                                        <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                            <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                            <input type="file" name="foto" class="default" accept="image/*"/>
                                        </span>
                                                            <a href="#"
                                                               class="btn btn-default fileinput-exists"
                                                               data-dismiss="fileinput"><i
                                                                        class="fa fa-trash"></i> Remover</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 text-right">
                                                    <button type="submit" class="btn btn-success btnSave"
                                                            id="dados_cadastrais_btn">
                                                        <i class="fa fa-save"></i> Salvar dados cadastrais
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Nome candidato</label>
                                                <div class="col-lg-7 controls">
                                                    <input type="text" name="nome"
                                                           placeholder="Nome do candidato"
                                                           value="<?= $candidato->nome; ?>"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Data
                                                    nascimento</label>
                                                <div class="col-sm-2">
                                                    <input type="text" name="data_nascimento"
                                                           placeholder="dd/mm/aaaa"
                                                           value="<?= $candidato->data_nascimento_de; ?>"
                                                           class="form-control text-center date"/>
                                                </div>
                                                <label class="col-sm-1 control-label">Sexo</label>
                                                <div class="col-sm-2">
                                                    <?php echo form_dropdown('sexo', $sexos, $candidato->sexo, 'class="form-control"'); ?>
                                                </div>
                                                <label class="col-sm-1 control-label text-nowrap">Estado
                                                    civil</label>
                                                <div class="col-sm-3">
                                                    <?php echo form_dropdown('estado_civil', $estados_civis, $candidato->estado_civil, 'class="form-control"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Telefone</label>
                                                <div class="col-lg-4 controls">
                                                    <input type="text" name="telefone"
                                                           placeholder="Telefone"
                                                           value="<?= $candidato->telefone; ?>"
                                                           class="form-control"/>
                                                </div>
                                                <label class="col-sm-1 control-label">E-mail</label>
                                                <div class="col-lg-4 controls">
                                                    <input type="email" name="email" placeholder="E-mail"
                                                           value="<?= $candidato->email; ?>"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Senha</label>
                                                <div class="col-lg-4 controls">
                                                    <input type="password" name="senha" placeholder="Nova senha"
                                                           value=""
                                                           max="32"
                                                           class="form-control" autocomplete="off"/>
                                                </div>
                                                <label class="col-sm-1 control-label">Confirmar
                                                    senha</label>
                                                <div class="col-lg-4 controls">
                                                    <input type="password" name="confirmar_senha"
                                                           placeholder="Confirmar senha"
                                                           value="" max="32" class="form-control"
                                                           autocomplete="off"/>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <fieldset>
                                            <legend>Campos complementares</legend>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Nome da mãe</label>
                                                <div class="col-lg-7 controls">
                                                    <input type="text" name="nome_mae"
                                                           placeholder="Nome da mãe do(a) candidato(a)"
                                                           value="<?= $candidato->nome_mae; ?>"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Nome do pai</label>
                                                <div class="col-lg-7 controls">
                                                    <input type="text" name="nome_pai"
                                                           placeholder="Nome do pai do(a) candidato(a)"
                                                           value="<?= $candidato->nome_pai; ?>"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Nível de
                                                    acesso</label>
                                                <div class="col-sm-3 col-lg-2 controls">
                                                    <select name="nivel_acesso" class="form-control">
                                                        <option value="E">Candidato</option>
                                                    </select>
                                                </div>
                                                <label class="col-sm-1 control-label">CPF</label>
                                                <div class="col-lg-2 controls">
                                                    <input type="text" name="cpf" id="cpf" placeholder="CPF"
                                                           value="<?= $candidato->cpf; ?>"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">RG</label>
                                                <div class="col-lg-2 controls">
                                                    <input type="text" name="rg" id="rg" placeholder="RG"
                                                           value="<?= $candidato->rg; ?>"
                                                           class="form-control"/>
                                                </div>
                                                <label class="col-sm-1 control-label">PIS</label>
                                                <div class="col-lg-2 controls">
                                                    <input type="text" name="pis" id="pis" placeholder="PIS"
                                                           value="<?= $candidato->pis; ?>"
                                                           class="form-control"/>
                                                </div>
                                                <label class="col-sm-1 control-label">CEP</label>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <input type="text" name="cep" id="cep"
                                                               placeholder="CEP"
                                                               value="<?= $candidato->cep; ?>"
                                                               class="form-control"/>
                                                        <span class="input-group-btn">
                                            <button class="btn btn-info" id="consultar_cep" type="button"><i
                                                        class="glyphicon glyphicon-search"></i> Consultar CEP</button>
                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Logradouro</label>
                                                <div class="col-lg-6 controls">
                                                    <input type="text" name="logradouro" id="logradouro"
                                                           placeholder="Logradouro"
                                                           value="<?= $candidato->logradouro; ?>"
                                                           class="form-control"/>
                                                </div>
                                                <label class="col-sm-1 control-label">Número</label>
                                                <div class="col-lg-2 controls">
                                                    <input type="number" name="numero"
                                                           value="<?= $candidato->numero; ?>"
                                                           class="form-control text-right"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Complemento</label>
                                                <div class="col-lg-4 controls">
                                                    <input type="text" name="complemento" id="complemento"
                                                           placeholder="Complemento"
                                                           value="<?= $candidato->complemento; ?>"
                                                           class="form-control"/>
                                                </div>
                                                <label class="col-sm-1 control-label">Bairro</label>
                                                <div class="col-lg-4 controls">
                                                    <input type="text" name="bairro" id="bairro"
                                                           placeholder="Bairro"
                                                           value="<?= $candidato->bairro; ?>"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Estado</label>
                                                <div class="col-sm-2 controls">
                                                    <?php echo form_dropdown('estado', $estados, $candidato->estado, 'id="estado" class="form-control filtro"'); ?>
                                                </div>
                                                <label class="col-sm-1 control-label">Cidade </label>
                                                <div class="col-lg-6 controls">
                                                    <?php echo form_dropdown('cidade', $cidades, $candidato->cidade, 'id="cidade" class="form-control filtro"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Escolaridade</label>
                                                <div class="col-sm-4 col-lg-3 controls">
                                                    <?php echo form_dropdown('escolaridade', $escolaridades, $candidato->escolaridade, 'id="escolaridade" class="form-control"'); ?>
                                                </div>
                                                <label class="col-sm-1 control-label">Deficiência</label>
                                                <div class="col-sm-4 col-lg-3 controls">
                                                    <?php echo form_dropdown('deficiencia', $deficiencias, $candidato->deficiencia, 'id="deficiencia" class="form-control"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Fonte
                                                    contratação</label>
                                                <div class="col-sm-5 col-lg-4 controls">
                                                    <?php echo form_dropdown('fonte_contratacao', $fontesContratacao, $candidato->fonte_contratacao, 'class="form-control"'); ?>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="formacao">
                                <?php echo form_open('candidatoVagas/salvarFormacoes', 'id="form_formacao" data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
                                <div class="form-group last">
                                    <label class="col-sm-2 control-label">Nível de escolaridade</label>
                                    <div class="col-sm-4 controls">
                                        <?php echo form_dropdown('escolaridade', $escolaridades, $candidato->escolaridade, 'id="escolaridade" class="form-control"'); ?>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <button type="submit" class="btn btn-success btnSave" id="formacao_btn">
                                            <i class="fa fa-save"></i> Salvar formação
                                        </button>
                                    </div>
                                </div>
                                <br>
                                <fieldset>
                                    <legend>Ensino Fundamental</legend>
                                    <input type="hidden" name="id[0]" value="<?= $formacao[0]->id; ?>">
                                    <input type="hidden" name="id_escolaridade[0]"
                                           value="<?= $formacao[0]->id_escolaridade; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituição</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instituicao[0]"
                                                   placeholder="Nome da instituição de ensino"
                                                   value="<?= $formacao[0]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Ano de conclusão</label>
                                        <div class="col-sm-1 controls">
                                            <input type="text" name="ano_conclusao[0]" placeholder="aaaa"
                                                   value="<?= $formacao[0]->ano_conclusao; ?>"
                                                   class="form-control text-center ano">
                                        </div>
                                        <div class="col-sm-2 controls">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="concluido[0]" type="checkbox"
                                                           value="1" <?= $formacao[0]->concluido ? 'checked' : ''; ?>>Completo
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>Ensino Médio</legend>
                                    <input type="hidden" name="id[1]" value="<?= $formacao[1]->id; ?>">
                                    <input type="hidden" name="id_escolaridade[1]"
                                           value="<?= $formacao[1]->id_escolaridade; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Curso 1</label>
                                        <div class="col-sm-5 controls">
                                            <input type="text" name="curso[1]"
                                                   placeholder="Nome do curso de formação"
                                                   value="<?= $formacao[1]->curso; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-1 control-label">Tipo</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo[1]"
                                                   value="N" <?= $formacao[1]->tipo == 'N' ? 'checked' : ''; ?>>
                                            Normal
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo[1]"
                                                   value="T" <?= $formacao[1]->tipo == 'T' ? 'checked' : ''; ?>>
                                            Técnico
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituição</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instituicao[1]"
                                                   placeholder="Nome da instituição de ensino"
                                                   value="<?= $formacao[1]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Ano de conclusão</label>
                                        <div class="col-sm-1 controls">
                                            <input type="text" name="ano_conclusao[1]" placeholder="aaaa"
                                                   value="<?= $formacao[1]->ano_conclusao; ?>"
                                                   class="form-control text-center ano">
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[2]" value="<?= $formacao[2]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Curso 2</label>
                                        <div class="col-sm-5 controls">
                                            <input type="text" name="curso[2]"
                                                   placeholder="Nome do curso de formação"
                                                   value="<?= $formacao[2]->curso; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-1 control-label">Tipo</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo[2]"
                                                   value="N" <?= $formacao[2]->tipo == 'N' ? 'checked' : ''; ?>>
                                            Normal
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo[2]"
                                                   value="T" <?= $formacao[2]->tipo == 'T' ? 'checked' : ''; ?>>
                                            Técnico
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituição</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instituicao[2]"
                                                   placeholder="Nome da instituição de ensino"
                                                   value="<?= $formacao[2]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Ano de conclusão</label>
                                        <div class="col-sm-1 controls">
                                            <input type="text" name="ano_conclusao[2]" placeholder="aaaa"
                                                   value="<?= $formacao[2]->ano_conclusao; ?>"
                                                   class="form-control text-center ano">
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[3]" value="<?= $formacao[3]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Curso 1</label>
                                        <div class="col-sm-5 controls">
                                            <input type="text" name="curso[3]"
                                                   placeholder="Nome do curso de formação"
                                                   value="<?= $formacao[3]->curso; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-1 control-label">Tipo</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo[3]"
                                                   value="N" <?= $formacao[3]->tipo == 'N' ? 'checked' : ''; ?>>
                                            Normal
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo[3]"
                                                   value="T"<?= $formacao[3]->tipo == 'T' ? ' checked' : ''; ?>>
                                            Técnico
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituição</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instituicao[3]"
                                                   placeholder="Nome da instituição de ensino"
                                                   value="<?= $formacao[3]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Ano de conclusão</label>
                                        <div class="col-sm-1 controls">
                                            <input type="text" name="ano_conclusao[3]" placeholder="aaaa"
                                                   value="<?= $formacao[3]->ano_conclusao; ?>"
                                                   class="form-control text-center ano">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>Graduação</legend>
                                    <input type="hidden" name="id[4]" value="<?= $formacao[4]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Curso 1</label>
                                        <div class="col-sm-5 controls">
                                            <input type="text" name="curso[4]"
                                                   placeholder="Nome do curso de formação"
                                                   value="<?= $formacao[4]->curso; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-1 control-label">Tipo</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo[4]"
                                                   value="B"<?= $formacao[4]->tipo == 'B' ? ' checked' : ''; ?>>
                                            Bacharel
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo[4]"
                                                   value="T"<?= $formacao[4]->tipo == 'T' ? ' checked' : ''; ?>>
                                            Tecnólogo
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituição</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instituicao[4]"
                                                   placeholder="Nome da instituição de ensino"
                                                   value="<?= $formacao[4]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Ano de conclusão</label>
                                        <div class="col-sm-1 controls">
                                            <input type="text" name="ano_conclusao[4]" placeholder="aaaa"
                                                   value="<?= $formacao[4]->ano_conclusao; ?>"
                                                   class="form-control text-center ano">
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[5]" value="<?= $formacao[5]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Curso 2</label>
                                        <div class="col-sm-5 controls">
                                            <input type="text" name="curso[5]"
                                                   placeholder="Nome do curso de formação"
                                                   value="<?= $formacao[5]->curso; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-1 control-label">Tipo</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo[5]"
                                                   value="B"<?= $formacao[5]->tipo == 'B' ? ' checked' : ''; ?>>
                                            Bacharel
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo[5]"
                                                   value="T"<?= $formacao[5]->tipo == 'T' ? ' checked' : ''; ?>>
                                            Tecnólogo
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituição</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instituicao[5]"
                                                   placeholder="Nome da instituição de ensino"
                                                   value="<?= $formacao[5]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Ano de conclusão</label>
                                        <div class="col-sm-1 controls">
                                            <input type="text" name="ano_conclusao[5]" placeholder="aaaa"
                                                   value="<?= $formacao[5]->ano_conclusao; ?>"
                                                   class="form-control text-center ano">
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[6]" value="<?= $formacao[6]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Curso 3</label>
                                        <div class="col-sm-5 controls">
                                            <input type="text" name="curso[6]"
                                                   placeholder="Nome do curso de formação"
                                                   value="<?= $formacao[6]->curso; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-1 control-label">Tipo</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo[6]"
                                                   value="B"<?= $formacao[6]->tipo == 'B' ? ' checked' : ''; ?>>
                                            Bacharel
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo[6]"
                                                   value="T"<?= $formacao[6]->tipo == 'T' ? ' checked' : ''; ?>>
                                            Tecnólogo
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituição</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instituicao[6]"
                                                   placeholder="Nome da instituição de ensino"
                                                   value="<?= $formacao[6]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Ano de conclusão</label>
                                        <div class="col-sm-1 controls">
                                            <input type="text" name="ano_conclusao[6]" placeholder="aaaa"
                                                   value="<?= $formacao[6]->ano_conclusao; ?>"
                                                   class="form-control text-center ano">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>Pós-Graduação</legend>
                                    <input type="hidden" name="id[7]" value="<?= $formacao[7]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Curso 1</label>
                                        <div class="col-sm-5 controls">
                                            <input type="text" name="curso[7]"
                                                   placeholder="Nome do curso de formação"
                                                   value="<?= $formacao[7]->curso; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Ano de conclusão</label>
                                        <div class="col-sm-1 controls">
                                            <input type="text" name="ano_conclusao[7]" placeholder="aaaa"
                                                   value="<?= $formacao[7]->ano_conclusao; ?>"
                                                   class="form-control text-center ano">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituição</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instituicao[7]"
                                                   placeholder="Nome da instituição de ensino"
                                                   value="<?= $formacao[7]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[8]" value="<?= $formacao[7]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Curso 2</label>
                                        <div class="col-sm-5 controls">
                                            <input type="text" name="curso[8]"
                                                   placeholder="Nome do curso de formação"
                                                   value="<?= $formacao[8]->curso; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Ano de conclusão</label>
                                        <div class="col-sm-1 controls">
                                            <input type="text" name="ano_conclusao[8]" placeholder="aaaa"
                                                   value="<?= $formacao[8]->ano_conclusao; ?>"
                                                   class="form-control text-center ano">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituição</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instituicao[8]"
                                                   placeholder="Nome da instituição de ensino"
                                                   value="<?= $formacao[8]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[9]" value="<?= $formacao[9]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Curso 3</label>
                                        <div class="col-sm-5 controls">
                                            <input type="text" name="curso[9]"
                                                   placeholder="Nome do curso de formação"
                                                   value="<?= $formacao[9]->curso; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Ano de conclusão</label>
                                        <div class="col-sm-1 controls">
                                            <input type="text" name="ano_conclusao[9]" placeholder="aaaa"
                                                   value="<?= $formacao[9]->ano_conclusao; ?>"
                                                   class="form-control text-center ano">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituição</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instituicao[9]"
                                                   placeholder="Nome da instituição de ensino"
                                                   value="<?= $formacao[9]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>Mestrado</legend>
                                    <input type="hidden" name="id[10]" value="<?= $formacao[10]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Curso 1</label>
                                        <div class="col-sm-5 controls">
                                            <input type="text" name="curso[10]"
                                                   placeholder="Nome do curso de formação"
                                                   value="<?= $formacao[10]->curso; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Ano de conclusão</label>
                                        <div class="col-sm-1 controls">
                                            <input type="text" name="ano_conclusao[10]" placeholder="aaaa"
                                                   value="<?= $formacao[10]->ano_conclusao; ?>"
                                                   class="form-control text-center ano">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituição</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instituicao[10]"
                                                   placeholder="Nome da instituição de ensino"
                                                   value="<?= $formacao[10]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[11]" value="<?= $formacao[11]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Curso 2</label>
                                        <div class="col-sm-5 controls">
                                            <input type="text" name="curso[11]"
                                                   placeholder="Nome do curso de formação"
                                                   value="<?= $formacao[11]->curso; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Ano de conclusão</label>
                                        <div class="col-sm-1 controls">
                                            <input type="text" name="ano_conclusao[11]" placeholder="aaaa"
                                                   value="<?= $formacao[11]->ano_conclusao; ?>"
                                                   class="form-control text-center ano">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituição</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instituicao[11]"
                                                   placeholder="Nome da instituição de ensino"
                                                   value="<?= $formacao[11]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[12]" value="<?= $formacao[12]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Curso 3</label>
                                        <div class="col-sm-5 controls">
                                            <input type="text" name="curso[12]"
                                                   placeholder="Nome do curso de formação"
                                                   value="<?= $formacao[12]->curso; ?>"
                                                   class="form-control"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Ano de conclusão</label>
                                        <div class="col-sm-1 controls">
                                            <input type="text" name="ano_conclusao[12]" placeholder="aaaa"
                                                   value="<?= $formacao[12]->ano_conclusao; ?>"
                                                   class="form-control text-center ano">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituição</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="instituicao[12]"
                                                   placeholder="Nome da instituição de ensino"
                                                   value="<?= $formacao[12]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                </fieldset>

                                <?php echo form_close(); ?>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="historico_profissional">
                                <?php echo form_open('candidatoVagas/salvarHistoricoProfissional', 'id="form_historico_profissional" data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <i class="text-primary"><strong>*</strong> O grupo cujo nome da
                                            empresa
                                            estiver em
                                            branco será removido do cadastro.</i>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <button type="submit" class="btn btn-success btnSave"
                                                id="historico_profissional_btn">
                                            <i class="fa fa-save"></i> Salvar histórico profissional
                                        </button>
                                    </div>
                                </div>
                                <fieldset>
                                    <legend>Experiência profissional</legend>
                                    <input type="hidden" name="id[0]"
                                           value="<?= $historicoProfissional[0]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Empresa 1<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="instituicao[0]"
                                                   placeholder="Nome da empresa"
                                                   value="<?= $historicoProfissional[0]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Data de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_entrada[0]"
                                                   placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[0]->data_entrada; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Data de saída</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_saida[0]" placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[0]->data_saida; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_entrada[0]"
                                                   placeholder="Nome do cargo de entrada"
                                                   value="<?= $historicoProfissional[0]->cargo_entrada; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de saída</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_saida[0]"
                                                   placeholder="Nome do cargo de saída"
                                                   value="<?= $historicoProfissional[0]->cargo_saida; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Salário de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_entrada[0]"
                                                       value="<?= $historicoProfissional[0]->salario_entrada; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                        <label class="col-sm-2 control-label">Salário de saída</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_saida[0]"
                                                       value="<?= $historicoProfissional[0]->salario_saida; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Razão da saída</label>
                                        <div class="col-sm-9">
                                <textarea name="motivo_saida[0]" class="form-control" rows="1"
                                          maxlength="255"><?= $historicoProfissional[0]->motivo_saida; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Realizações</label>
                                        <div class="col-sm-9">
                                                    <textarea name="realizacoes[0]" class="form-control"
                                                              rows="3"><?= $historicoProfissional[0]->realizacoes; ?></textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[1]"
                                           value="<?= $historicoProfissional[1]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Empresa 2<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="instituicao[1]"
                                                   placeholder="Nome da empresa"
                                                   value="<?= $historicoProfissional[1]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Data de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_entrada[1]"
                                                   placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[1]->data_entrada; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Data de saída</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_saida[1]" placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[1]->data_saida; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_entrada[1]"
                                                   placeholder="Nome do cargo de entrada"
                                                   value="<?= $historicoProfissional[1]->cargo_entrada; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de saída</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_saida[1]"
                                                   placeholder="Nome do cargo de saída"
                                                   value="<?= $historicoProfissional[1]->cargo_saida; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Salário de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_entrada[1]"
                                                       value="<?= $historicoProfissional[1]->salario_entrada; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                        <label class="col-sm-2 control-label">Salário de saída</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_saida[1]"
                                                       value="<?= $historicoProfissional[1]->salario_saida; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Razão da saída</label>
                                        <div class="col-sm-9">
                                <textarea name="motivo_saida[1]" class="form-control" rows="1"
                                          maxlength="255"><?= $historicoProfissional[1]->motivo_saida; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Realizações</label>
                                        <div class="col-sm-9">
                                                    <textarea name="realizacoes[1]" class="form-control"
                                                              rows="3"><?= $historicoProfissional[1]->realizacoes; ?></textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[2]"
                                           value="<?= $historicoProfissional[2]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Empresa 3<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="instituicao[2]"
                                                   placeholder="Nome da empresa"
                                                   value="<?= $historicoProfissional[2]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Data de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_entrada[2]"
                                                   placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[2]->data_entrada; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Data de saída</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_saida[2]" placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[2]->data_saida; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_entrada[2]"
                                                   placeholder="Nome do cargo de entrada"
                                                   value="<?= $historicoProfissional[2]->cargo_entrada; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de saída</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_saida[2]"
                                                   placeholder="Nome do cargo de saída"
                                                   value="<?= $historicoProfissional[2]->cargo_saida; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Salário de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_entrada[2]"
                                                       value="<?= $historicoProfissional[2]->salario_entrada; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                        <label class="col-sm-2 control-label">Salário de saída</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_saida[2]"
                                                       value="<?= $historicoProfissional[2]->salario_saida; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Razão da saída</label>
                                        <div class="col-sm-9">
                                <textarea name="motivo_saida[2]" class="form-control" rows="1"
                                          maxlength="255"><?= $historicoProfissional[2]->motivo_saida; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Realizações</label>
                                        <div class="col-sm-9">
                                                    <textarea name="realizacoes[2]" class="form-control"
                                                              rows="3"><?= $historicoProfissional[2]->realizacoes; ?></textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[3]"
                                           value="<?= $historicoProfissional[3]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Empresa 4<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="instituicao[3]"
                                                   placeholder="Nome da empresa"
                                                   value="<?= $historicoProfissional[3]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Data de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_entrada[3]"
                                                   placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[3]->data_entrada; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Data de saída</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_saida[3]" placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[3]->data_saida; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_entrada[3]"
                                                   placeholder="Nome do cargo de entrada"
                                                   value="<?= $historicoProfissional[3]->cargo_entrada; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de saída</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_saida[3]"
                                                   placeholder="Nome do cargo de saída"
                                                   value="<?= $historicoProfissional[3]->cargo_saida; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Salário de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_entrada[3]"
                                                       value="<?= $historicoProfissional[3]->salario_entrada; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                        <label class="col-sm-2 control-label">Salário de saída</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_saida[3]"
                                                       value="<?= $historicoProfissional[3]->salario_saida; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Razão da saída</label>
                                        <div class="col-sm-9">
                                <textarea name="motivo_saida[3]" class="form-control" rows="1"
                                          maxlength="255"><?= $historicoProfissional[3]->motivo_saida; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Realizações</label>
                                        <div class="col-sm-9">
                                                    <textarea name="realizacoes[3]" class="form-control"
                                                              rows="3"><?= $historicoProfissional[3]->realizacoes; ?></textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[4]"
                                           value="<?= $historicoProfissional[4]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Empresa 5<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="instituicao[4]"
                                                   placeholder="Nome da empresa"
                                                   value="<?= $historicoProfissional[4]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Data de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_entrada[4]"
                                                   placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[4]->data_entrada; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Data de saída</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_saida[4]" placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[4]->data_saida; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_entrada[4]"
                                                   placeholder="Nome do cargo de entrada"
                                                   value="<?= $historicoProfissional[4]->cargo_entrada; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de saída</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_saida[4]"
                                                   placeholder="Nome do cargo de saída"
                                                   value="<?= $historicoProfissional[4]->cargo_saida; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Salário de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_entrada[4]"
                                                       value="<?= $historicoProfissional[4]->salario_entrada; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                        <label class="col-sm-2 control-label">Salário de saída</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_saida[4]"
                                                       value="<?= $historicoProfissional[4]->salario_saida; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Razão da saída</label>
                                        <div class="col-sm-9">
                                <textarea name="motivo_saida[4]" class="form-control" rows="1"
                                          maxlength="255"><?= $historicoProfissional[4]->motivo_saida; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Realizações</label>
                                        <div class="col-sm-9">
                                                    <textarea name="realizacoes[4]" class="form-control"
                                                              rows="3"><?= $historicoProfissional[4]->realizacoes; ?></textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[5]"
                                           value="<?= $historicoProfissional[5]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Empresa 6<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="instituicao[5]"
                                                   placeholder="Nome da empresa"
                                                   value="<?= $historicoProfissional[5]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Data de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_entrada[5]"
                                                   placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[5]->data_entrada; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Data de saída</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_saida[5]" placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[5]->data_saida; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_entrada[5]"
                                                   placeholder="Nome do cargo de entrada"
                                                   value="<?= $historicoProfissional[5]->cargo_entrada; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de saída</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_saida[5]"
                                                   placeholder="Nome do cargo de saída"
                                                   value="<?= $historicoProfissional[5]->cargo_saida; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Salário de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_entrada[5]"
                                                       value="<?= $historicoProfissional[5]->salario_entrada; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                        <label class="col-sm-2 control-label">Salário de saída</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_saida[5]"
                                                       value="<?= $historicoProfissional[5]->salario_saida; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Razão da saída</label>
                                        <div class="col-sm-9">
                                <textarea name="motivo_saida[5]" class="form-control" rows="1"
                                          maxlength="255"><?= $historicoProfissional[5]->motivo_saida; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Realizações</label>
                                        <div class="col-sm-9">
                                                    <textarea name="realizacoes[5]" class="form-control"
                                                              rows="3"><?= $historicoProfissional[5]->realizacoes; ?></textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="id[6]"
                                           value="<?= $historicoProfissional[6]->id; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Empresa 7<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="instituicao[6]"
                                                   placeholder="Nome da empresa"
                                                   value="<?= $historicoProfissional[6]->instituicao; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Data de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_entrada[6]"
                                                   placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[6]->data_entrada; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                        <label class="col-sm-2 control-label">Data de saída</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="data_saida[6]" placeholder="dd/mm/aaaa"
                                                   value="<?= $historicoProfissional[6]->data_saida; ?>"
                                                   class="form-control text-center date"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_entrada[6]"
                                                   placeholder="Nome do cargo de entrada"
                                                   value="<?= $historicoProfissional[6]->cargo_entrada; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Cargo de saída</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cargo_saida[6]"
                                                   placeholder="Nome do cargo de saída"
                                                   value="<?= $historicoProfissional[6]->cargo_saida; ?>"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Salário de entrada<span
                                                    class="text-primary"> *</span></label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_entrada[6]"
                                                       value="<?= $historicoProfissional[6]->salario_entrada; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                        <label class="col-sm-2 control-label">Salário de saída</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="salario_saida[6]"
                                                       value="<?= $historicoProfissional[6]->salario_saida; ?>"
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Razão da saída</label>
                                        <div class="col-sm-9">
                                <textarea name="motivo_saida[6]" class="form-control" rows="1"
                                          maxlength="255"><?= $historicoProfissional[6]->motivo_saida; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Realizações</label>
                                        <div class="col-sm-9">
                                                    <textarea name="realizacoes[6]" class="form-control"
                                                              rows="3"><?= $historicoProfissional[6]->realizacoes; ?></textarea>
                                        </div>
                                    </div>
                                </fieldset>

                                <?php echo form_close(); ?>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="curriculo">
                                <div class="row">
                                    <div class="col col-md-12">
                                        <h5 class="text-primary">Para anexar seu currículo digital (*.pdf), selecione o
                                            mesmo e acione o botão "Importar".</h5>
                                        <?php echo form_open_multipart('candidatoVagas/salvarCurriculo', 'id="form_curriculo" data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
                                        <div class="form-group last">
                                            <label class="col-sm-3 col-lg-2 control-label">Arquivo .pdf</label>
                                            <div class="col-sm-7 col-lg-7 controls">
                                                <div class="fileinput fileinput-new input-group"
                                                     data-provides="fileinput">
                                                    <div class="form-control" data-trigger="fileinput">
                                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                        <span class="fileinput-filename"></span>
                                                    </div>
                                                    <div class="input-group-addon btn btn-default btn-file">
                                                        <span class="fileinput-new">Selecionar arquivo</span>
                                                        <span class="fileinput-exists">Alterar</span>
                                                        <input type="file" name="arquivo_curriculo" accept=".pdf">
                                                    </div>
                                                    <a href="#"
                                                       class="input-group-addon btn btn-default fileinput-exists"
                                                       data-dismiss="fileinput">Remover</a>
                                                </div>
                                                <span class="help-block"><?= $candidato->arquivo_curriculo; ?></span>
                                            </div>
                                            <div class="col-sm-2 col-lg-3 text-right">
                                                <button type="submit" name="submit" class="btn btn-success">
                                                    <i class="fa fa-upload"></i> Importar
                                                </button>
                                                <button class="btn btn-default" onclick="javascript:history.back()"><i
                                                            class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                                </button>
                                            </div>
                                        </div>
                                        <?php echo form_close(); ?>
                                        <?php if ($candidato->arquivo_curriculo): ?>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <iframe src="https://docs.google.com/gview?embedded=true&url=<?php echo base_url('arquivos/curriculos/' . convert_accented_characters($candidato->arquivo_curriculo)); ?>"
                                                            width="100%" height="450px" frameborder="0"
                                                            allowfullscreen></iframe>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div role="tabpanel" class="tab-pane" id="meus_testes">

                        <div class="page-header text-primary">
                            <h3><strong>Caro candidato, seja bem-vindo ao nosso painel de testes!</strong></h3>
                        </div>
                        <!--<h5 class="text-primary">Caso algumas das vagas seja de seu interesse, basta acionar o botão
                            "Candidatar-se!" que você será automaticamente incluído no processo seletivo da mesma.</h5>-->

                        <table id="table_testes" class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Ações</th>
                                <th nowrap>Código vaga</th>
                                <th nowrap>N&ordm; requisição</th>
                                <th>Função</th>
                                <th>Teste</th>
                                <th>Data início</th>
                                <th>Data término</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                        <h3 class="modal-title">Formulario de evento de apontamento</h3>
                    </div>
                    <div class="modal-body form">
                        <div id="alert"></div>
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                            <input type="hidden" value="" name="id"/>
                            <div class="form-body">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Código evento</label>
                                    <div class="col-md-5">
                                        <input name="codigo" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Nome evento</label>
                                    <div class="col-md-9">
                                        <input name="nome" placeholder="Digite o nome do evento" class="form-control"
                                               type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_vaga" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn btn-success" data-dismiss="modal" style="float:right;">Fechar
                        </button>
                        <h3 class="modal-title">Detalhes da vaga</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" class="form-horizontal">
                            <div class="row">
                                <label class="control-label col-md-3">Código da vaga</label>
                                <div class="col-md-1">
                                    <p id="codigo" class="form-control-static"></p>
                                </div>
                                <label class="control-label col-md-2">Cargo/Função</label>
                                <div class="col-md-5">
                                    <p id="cargo_funcao" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Perfil profissional desejado</label>
                                <div class="col-md-8">
                                    <p id="perfil_profissional_desejado" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Data de abertura</label>
                                <div class="col-md-1">
                                    <p id="data_abertura" class="form-control-static"></p>
                                </div>
                                <label class="control-label col-md-3">Quantidade de vagas</label>
                                <div class="col-md-1">
                                    <p id="quantidade" class="form-control-static"></p>
                                </div>
                                <label class="control-label col-md-2">Tipo de vínculo</label>
                                <div class="col-md-1">
                                    <p id="tipo_vinculo" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Cidade da vaga</label>
                                <div class="col-md-3">
                                    <p id="cidade_vaga" class="form-control-static"></p>
                                </div>
                                <label class="control-label col-md-2">Bairro da vaga</label>
                                <div class="col-md-3">
                                    <p id="bairro_vaga" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Remuneração (R$)</label>
                                <div class="col-md-8">
                                    <p id="remuneracao" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Benefícios</label>
                                <div class="col-md-8">
                                    <p id="beneficios" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Horário de trabalho</label>
                                <div class="col-md-8">
                                    <p id="horario_trabalho" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Formação mínima</label>
                                <div class="col-md-8">
                                    <p id="formacao_minima" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Contato do selecionador</label>
                                <div class="col-md-8">
                                    <p id="contato_selecionador" class="form-control-static"></p>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Fechar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Bootstrap modal -->
        <div class="modal fade center" id="modal_teste" role="dialog">
            <div class="modal-dialog" style="max-width: 95%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Atenção</h3>
                    </div>
                    <div class="modal-body">
                        <p id="em_execucao" class="text-danger" style="text-indent: 20px;">
                            ESTE TESTE JÁ ESTÁ EM EXECUÇÃO!
                        </p>
                        <p style="font-size: 15px; text-indent: 20px;">
                            O tempo estimado para este teste é <span id="tempo_duracao"></span>.
                            Você terá apenas <strong>1 (uma) tentativa</strong> para a realização do mesmo.
                            Após concluí-lo, não será possível acessá-lo novamente.
                        </p>
                        <br>
                        <!--<p style="text-indent: 20px;">
                            Em caso de problemas, entre em contato com o administrador da plataforma.
                        </p>-->
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="" name="id_teste"/>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnIniciar_Teste" onclick="iniciar_teste()"
                                class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i> Iniciar teste
                        </button>
                        <button type="button" id="btnContinuar_Teste" onclick="iniciar_teste()"
                                class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i> Continuar teste
                        </button>

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </section>
</section>
<!--main content end-->

<?php require_once 'end_js.php'; ?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">
<link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.css"); ?>"/>
<!--script for this page-->

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

<script>

    var table, table_testes;
    var is_mobile = <?= $this->agent->is_mobile() ? 'true' : 'false'; ?>;

    $('.tags').tagsInput({width: 'auto', defaultText: 'Telefone', placeholderColor: '#999', delimiter: '/'});
    $('.date').mask('00/00/0000');
    $('.ano').mask('0000');
    $('.valor').mask('##.###.##0,00', {reverse: true});
    $('#rg').mask('00.000.000-0', {reverse: true});
    $('#cpf').mask('000.000.000-00', {reverse: true});
    $('#cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('#pis').mask('00.000.000.000', {reverse: true});


    $(document).ready(function () {

        table = $('#table').DataTable({
            'processing': true,
            'serverSide': true,
            'lengthChange': false,
            'searching': false,
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('candidatoVagas/ajaxList/') ?>',
                'type': 'POST'
            },
            'columnDefs': [
                {
                    'className': 'text-center',
                    'targets': [2, 4, 7, 8]
                },
                {
                    'width': '34%',
                    'targets': [3]
                },
                {
                    'width': '33%',
                    'targets': [5, 6]
                },
                {
                    'className': 'text-center text-nowrap',
                    'orderable': false,
                    'searchable': false,
                    'targets': [1]
                }
            ]
        });


        table_testes = $('#table_testes').DataTable({
            'info': false,
            'processing': true,
            'serverSide': true,
            'lengthChange': false,
            'searching': false,
            'order': [],
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('candidatoVagas/ajaxListTestes') ?>/',
                'type': 'POST'
            },
            'columnDefs': [
                {
                    'className': 'text-center text-nowrap',
                    'orderable': false,
                    'targets': [0]
                },
                {
                    'className': 'text-center',
                    'targets': [1, 2, 5, 6]
                },
                {
                    'width': '50%',
                    'targets': [3, 4]
                }
            ]
        });

    });

    // Ajusta a largura das colunas dos tabelas do tipo DataTables em uma aba
    $(document).on('shown.bs.tab', function () {
        $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
    });


    function reload_table() {
        table.ajax.reload(null, false);
    }


    function visualizar_vaga(codigo) {
        $.ajax({
            'url': '<?php echo site_url('vagas/visualizarDetalhes/') ?>',
            'type': 'GET',
            'dataType': 'json',
            'data': {'codigo': codigo},
            'success': function (json) {

                $.each(json, function (key, value) {
                    $('#' + key).html(value);
                });

                $('#modal_vaga').modal('show');

            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function candidatar(codigo_vaga) {
        $.ajax({
            'url': '<?php echo site_url('candidatoVagas/candidatar') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {'codigo_vaga': codigo_vaga},
            'success': function () {
                reload_table();
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function descandidatar(id_candidatura) {
        $.ajax({
            'url': '<?php echo site_url('candidatoVagas/descandidatar') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {'id_candidatura': id_candidatura},
            'success': function () {
                reload_table();
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
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


    $('#consultar_cep').on('click', function () {
        var cep = $('#cep').val();
        if (cep.length > 0) {
            $.ajax({
                'url': '<?php echo site_url('vagas/consultarCEP') ?>/',
                'type': 'GET',
                'dataType': 'json',
                'data': {
                    'cep': cep
                },
                'beforeSend': function () {
                    $('#consultar_cep').html('<i class="glyphicon glyphicon-search"></i> Consultando...');
                },
                'success': function (json) {
                    if (json.erro === undefined) {
                        $('#logradouro').val(json.logradouro);
                        $('#complemento').val(json.complemento);
                        $('#bairro').val(json.bairro);
                        $('#estado').val(json.estado);
                        $('#numero').val(json.numero);
                        $('#cidade').html(json.cidade);
                    } else {
                        alert('CEP não encontrado.');
                        $('.filtro').val();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            }).done(function () {
                $('#consultar_cep').html('<i class="glyphicon glyphicon-search"></i> Consultar CEP');
            });
        }
    });


    $('#estado').on('change', function () {
        $.ajax({
            'url': '<?php echo site_url('candidatoVagas/ajax_cidades') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'estado': $(this).val()
            },
            'beforeSend': function (jqXHR, settings) {
                $('#cidade').prop('disabled', true);
            },
            'success': function (data, textStatus, jqXHR) {
                $('#cidade').html(data.cidades);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            },
            'complete': function (jqXHR, textStatus) {
                $('#cidade').prop('disabled', false);
            }
        });
    });


    function verificar_teste(id) {
        $.ajax({
            url: "<?php echo site_url('recrutamentoPresencial_testes/verificar_teste') ?>/" + id,
            type: "POST",
            dataType: "JSON",
            success: function (json) {
                id_teste = id;
                modeloTeste = json.tipo;
                switch (json.tipo) {
                    case 'M':
                        modeloTeste = 'matematica';
                        break;
                    case 'R':
                        modeloTeste = 'raciocinio_logico';
                        break;
                    case 'P':
                        modeloTeste = 'portugues';
                        break;
                    case 'L':
                        modeloTeste = 'lideranca';
                        break;
                    case 'C':
                        modeloTeste = 'perfil_personalidade';
                        break;
                    case 'D':
                        modeloTeste = 'digitacao';
                        break;
                    case 'I':
                        modeloTeste = 'interpretacao';
                        break;
                    case 'E':
                        modeloTeste = 'entrevista';
                }
                if (json.data_acesso === null) {
                    $('#em_execucao, #btnContinuar_Teste').hide();
                    $('#btnIniciar_Teste').show();
                } else {
                    $('#em_execucao, #btnContinuar_Teste').show();
                    $('#btnIniciar_Teste').hide();
                }
                if (json.tempo_duracao.length > 0) {
                    $('#tempo_duracao').html('de <strong>' + json.tempo_duracao + ' minutos</strong>');
                } else {
                    $('#tempo_duracao').html('até o dia <strong>' + json.data_termino + '</strong>');
                }

                $('#modal_teste').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error getting data');
            }
        });
    }

    function iniciar_teste() {
        setTimeout(function () {
            $('#modal_teste').modal('hide');
            table.ajax.reload(null, false);
        }, 1);

        //window.open("<?php //echo site_url('candidatoTestes/'); ?>///" + modeloTeste + '/' + id_teste, '_blank');
        window.open("<?php echo site_url('recrutamentoPresencial_testes/'); ?>/" + modeloTeste + '/' + id_teste, '_blank');
    }


</script>

<?php require_once 'end_html.php'; ?>
