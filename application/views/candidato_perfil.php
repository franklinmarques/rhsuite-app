<?php require_once 'header.php'; ?>

    <section id="main-content" class="merge-left">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edição de cadastro do candidato</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="alert"></div>

                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <button type="button" class="btn btn-success btnSave" onclick="save();"><i
                                                        class="fa fa-save"></i> Salvar
                                            </button>
                                            <button class="btn btn-default" onclick="javascript:history.back()"><i
                                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                            </button>
                                        </div>
                                    </div>

                                    <ul class="nav nav-tabs" role="tablist"
                                        style="font-size: 15px; font-weight: bolder;">
                                        <li role="presentation" class="active">
                                            <a href="#dados_cadastrais" aria-controls="dados_cadastrais" role="tab"
                                               data-toggle="tab">Passo
                                                1 (dados cadastrais)</a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#formacao" aria-controls="formacao" role="tab" data-toggle="tab">Passo
                                                2
                                                (formação)</a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#historico_profissional" aria-controls="historico_profissional"
                                               role="tab"
                                               data-toggle="tab">Passo 3 (histórico profissional)</a>
                                        </li>
                                    </ul>
                                    <br/>
                                    <br/>

                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="dados_cadastrais">
                                            <div class="row">
                                                <div class="col col-md-12">
                                                    <?php echo form_open('#', 'id="form_candidato" class="form-horizontal autocomplete="off"'); ?>
                                                    <fieldset>
                                                        <legend>Campos obrigatórios</legend>
                                                        <div class="form-group last">
                                                            <label class="col-sm-2 control-label">Foto</label>
                                                            <div class="col-lg-7 controls">
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
                                                                <input type="password" name="senha" placeholder="Senha"
                                                                       value=""
                                                                       max="32"
                                                                       class="form-control" autocomplete="new-password"/>
                                                            </div>
                                                            <label class="col-sm-1 control-label">Confirmar
                                                                senha</label>
                                                            <div class="col-lg-4 controls">
                                                                <input type="password" name="confirmar_senha"
                                                                       placeholder="Confirmar senha"
                                                                       value="" max="32" class="form-control"
                                                                       autocomplete="new-password"/>
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
                                                                    <option value="C">Candidato</option>
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
                                            <?php echo form_open('#', 'id="form_formacao" class="form-horizontal autocomplete="off"'); ?>
                                            <div class="form-group last">
                                                <label class="col-sm-2 control-label">Nível de escolaridade</label>
                                                <div class="col-lg-4 controls">
                                                    <?php echo form_dropdown('escolaridade', $escolaridades, $candidato->escolaridade, 'id="escolaridade" class="form-control"'); ?>
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
                                                    <div class="col-sm-4">
                                                        <input type="text" name="instituicao[0]"
                                                               placeholder="Nome da instituição de ensino"
                                                               value="<?= $formacao[0]->instituicao; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                                    <div class="col-sm-2 controls">
                                                        <input type="number" name="ano_conclusao[0]" placeholder="aaaa"
                                                               value="<?= $formacao[0]->ano_conclusao; ?>"
                                                               class="form-control text-right" size="4" min="0"/>
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
                                                    <div class="col-sm-4 controls">
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
                                                    <div class="col-sm-4">
                                                        <input type="text" name="instituicao[1]"
                                                               placeholder="Nome da instituição de ensino"
                                                               value="<?= $formacao[1]->instituicao; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                                    <div class="col-sm-2 controls">
                                                        <input type="number" name="ano_conclusao[1]" placeholder="aaaa"
                                                               value="<?= $formacao[1]->ano_conclusao; ?>"
                                                               class="form-control text-right" size="4" min="0"/>
                                                    </div>
                                                </div>
                                                <hr>
                                                <input type="hidden" name="id[2]" value="<?= $formacao[2]->id; ?>">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Curso 2</label>
                                                    <div class="col-sm-4 controls">
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
                                                    <div class="col-sm-4">
                                                        <input type="text" name="instituicao[2]"
                                                               placeholder="Nome da instituição de ensino"
                                                               value="<?= $formacao[2]->instituicao; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                                    <div class="col-sm-2 controls">
                                                        <input type="number" name="ano_conclusao[2]" placeholder="aaaa"
                                                               value="<?= $formacao[2]->ano_conclusao; ?>"
                                                               class="form-control text-right" size="4" min="0"/>
                                                    </div>
                                                </div>
                                                <hr>
                                                <input type="hidden" name="id[3]" value="<?= $formacao[3]->id; ?>">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Curso 1</label>
                                                    <div class="col-sm-4 controls">
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
                                                    <div class="col-sm-4">
                                                        <input type="text" name="instituicao[3]"
                                                               placeholder="Nome da instituição de ensino"
                                                               value="<?= $formacao[3]->instituicao; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                                    <div class="col-sm-2 controls">
                                                        <input type="number" name="ano_conclusao[3]" placeholder="aaaa"
                                                               value="<?= $formacao[3]->ano_conclusao; ?>"
                                                               class="form-control text-right" size="4" min="0"/>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <legend>Graduação</legend>
                                                <input type="hidden" name="id[4]" value="<?= $formacao[4]->id; ?>">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Curso 1</label>
                                                    <div class="col-sm-4 controls">
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
                                                    <div class="col-sm-4">
                                                        <input type="text" name="instituicao[4]"
                                                               placeholder="Nome da instituição de ensino"
                                                               value="<?= $formacao[4]->instituicao; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                                    <div class="col-sm-2 controls">
                                                        <input type="number" name="ano_conclusao[4]" placeholder="aaaa"
                                                               value="<?= $formacao[4]->ano_conclusao; ?>"
                                                               class="form-control text-right" size="4" min="0"/>
                                                    </div>
                                                </div>
                                                <hr>
                                                <input type="hidden" name="id[5]" value="<?= $formacao[5]->id; ?>">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Curso 2</label>
                                                    <div class="col-sm-4 controls">
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
                                                    <div class="col-sm-4">
                                                        <input type="text" name="instituicao[5]"
                                                               placeholder="Nome da instituição de ensino"
                                                               value="<?= $formacao[5]->instituicao; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                                    <div class="col-sm-2 controls">
                                                        <input type="number" name="ano_conclusao[5]" placeholder="aaaa"
                                                               value="<?= $formacao[5]->ano_conclusao; ?>"
                                                               class="form-control text-right" size="4" min="0"/>
                                                    </div>
                                                </div>
                                                <hr>
                                                <input type="hidden" name="id[6]" value="<?= $formacao[6]->id; ?>">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Curso 3</label>
                                                    <div class="col-sm-4 controls">
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
                                                    <div class="col-sm-4">
                                                        <input type="text" name="instituicao[6]"
                                                               placeholder="Nome da instituição de ensino"
                                                               value="<?= $formacao[6]->instituicao; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                                    <div class="col-sm-2 controls">
                                                        <input type="number" name="ano_conclusao[6]" placeholder="aaaa"
                                                               value="<?= $formacao[6]->ano_conclusao; ?>"
                                                               class="form-control text-right" size="4" min="0"/>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <legend>Pós-Graduação</legend>
                                                <input type="hidden" name="id[7]" value="<?= $formacao[7]->id; ?>">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Curso 1</label>
                                                    <div class="col-sm-4 controls">
                                                        <input type="text" name="curso[7]"
                                                               placeholder="Nome do curso de formação"
                                                               value="<?= $formacao[7]->curso; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                                    <div class="col-sm-2 controls">
                                                        <input type="number" name="ano_conclusao[7]" placeholder="aaaa"
                                                               value="<?= $formacao[7]->ano_conclusao; ?>"
                                                               class="form-control text-right" size="4" min="0"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Instituição</label>
                                                    <div class="col-sm-4">
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
                                                    <div class="col-sm-4 controls">
                                                        <input type="text" name="curso[8]"
                                                               placeholder="Nome do curso de formação"
                                                               value="<?= $formacao[8]->curso; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                                    <div class="col-sm-2 controls">
                                                        <input type="number" name="ano_conclusao[8]" placeholder="aaaa"
                                                               value="<?= $formacao[8]->ano_conclusao; ?>"
                                                               class="form-control text-right" size="4" min="0"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Instituição</label>
                                                    <div class="col-sm-4">
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
                                                    <div class="col-sm-4 controls">
                                                        <input type="text" name="curso[9]"
                                                               placeholder="Nome do curso de formação"
                                                               value="<?= $formacao[9]->curso; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                                    <div class="col-sm-2 controls">
                                                        <input type="number" name="ano_conclusao[9]" placeholder="aaaa"
                                                               value="<?= $formacao[9]->ano_conclusao; ?>"
                                                               class="form-control text-right" size="4" min="0"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Instituição</label>
                                                    <div class="col-sm-4">
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
                                                    <div class="col-sm-4 controls">
                                                        <input type="text" name="curso[10]"
                                                               placeholder="Nome do curso de formação"
                                                               value="<?= $formacao[10]->curso; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                                    <div class="col-sm-2 controls">
                                                        <input type="number" name="ano_conclusao[10]" placeholder="aaaa"
                                                               value="<?= $formacao[10]->ano_conclusao; ?>"
                                                               class="form-control text-right" size="4" min="0"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Instituição</label>
                                                    <div class="col-sm-4">
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
                                                    <div class="col-sm-4 controls">
                                                        <input type="text" name="curso[11]"
                                                               placeholder="Nome do curso de formação"
                                                               value="<?= $formacao[11]->curso; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                                    <div class="col-sm-2 controls">
                                                        <input type="number" name="ano_conclusao[11]" placeholder="aaaa"
                                                               value="<?= $formacao[11]->ano_conclusao; ?>"
                                                               class="form-control text-right" size="4" min="0"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Instituição</label>
                                                    <div class="col-sm-4">
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
                                                    <div class="col-sm-4 controls">
                                                        <input type="text" name="curso[12]"
                                                               placeholder="Nome do curso de formação"
                                                               value="<?= $formacao[12]->curso; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                                    <div class="col-sm-2 controls">
                                                        <input type="number" name="ano_conclusao[12]" placeholder="aaaa"
                                                               value="<?= $formacao[12]->ano_conclusao; ?>"
                                                               class="form-control text-right" size="4" min="0"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Instituição</label>
                                                    <div class="col-sm-4">
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
                                            <?php echo form_open('#', 'id="form_historico_profissional" class="form-horizontal autocomplete="off"'); ?>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <i class="text-primary"><strong>*</strong> O grupo cujo nome da
                                                        empresa
                                                        estiver em
                                                        branco será removido do cadastro.</i>
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
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-success btnSave" onclick="save();"><i
                                                class="fa fa-save"></i>
                                        Salvar
                                    </button>
                                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </section>
    </section>
    <!--main content end-->

<?php require_once 'end_js.php'; ?>

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Edição de cadastro do candidato';
        });
    </script>

    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.css"); ?>"/>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

    <script src="<?php echo base_url("assets/js/bootstrap-combobox/js/bootstrap-combobox.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>


    <script>
        var table;

        $('.tags').tagsInput({width: 'auto', defaultText: 'Telefone', placeholderColor: '#999', delimiter: '/'});
        $('.date').mask('00/00/0000');
        $('.ano').mask('0000');
        $('.valor').mask('##.###.##0,00', {reverse: true});
        $('#rg').mask('00.000.000-0', {reverse: true});
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


        // $('#escolaridade').on('change', function () {
        //     var nivel = parseInt(this.value);
        //     switch(nivel){
        //         case 0:
        //             $('#form_formacao [name="id_escolaridade[0]"]').val(nivel);
        //     }
        // });


        function save() {
            $('.btnSave').text('Salvando...').attr('disabled', true);

            $.ajax({
                'url': '<?php echo site_url('candidatoVagas/salvarPerfil') ?>',
                'type': 'POST',
                'data': {
                    'candidato': $('#form_candidato').serialize(),
                    'formacao': $('#form_formacao').serialize(),
                    'historico_profissional': $('#form_historico_profissional').serialize()
                },
                'dataType': 'json',
                'success': function (json) {
                    $('html, body').animate({scrollTop: 0}, 1500);
                    if (json.status) {
                        $('#alert').html('<div class="alert alert-success">Cadastro realizado com sucesso</div>').hide().fadeIn('slow', function () {
                            window.location = '<?= site_url('candidatoVagas'); ?>';
                        });
                    } else {
                        $('#alert').html('<div class="alert alert-danger">' + json.erro + '</div>').hide().fadeIn('slow');
                        $('.btnSave').text('Salvar').attr('disabled', false);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('.btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }


    </script>

<?php require_once 'end_html.php'; ?>