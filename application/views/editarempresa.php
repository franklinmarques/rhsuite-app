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
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-pencil-square-o"></i> Editar Empresa - <?php echo $row->nome; ?>
                        </header>
                        <div class="panel-body">
                            <?php echo form_open('home/editarempresa_json/' . $row->id, 'data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Empresa</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="text" name="empresa" placeholder="Empresa"
                                           value="<?php echo $row->nome; ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">E-mail</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="text" name="email" placeholder="E-mail"
                                           value="<?php echo $row->email; ?>"
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Obs:</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    (Caso não queira alterar a senha, deixe os campos abaixo em branco)
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Senha</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="password" name="senha" placeholder="Senha" value=""
                                           class="form-control" autocomplete="new-password"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Confirmar Senha</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="password" name="confirmarsenha" placeholder="Confirmar Senha" value=""
                                           class="form-control" autocomplete="new-password"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Situação</label>
                                <div class="col-sm-3 col-lg-2 controls">
                                    <select name="status" class="form-control input-sm">
                                        <option value="1"<?= ($row->status == "1" ? ' selected="selected"' : ''); ?>>
                                            Ativo
                                        </option>
                                        <option value="0"<?= ($row->status == "0" ? ' selected="selected"' : ''); ?>>
                                            Bloqueado
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Qtde. máxima colaboradores</label>
                                <div class="col-sm-3 col-lg-2 controls">
                                    <input type="number" name="max_colaboradores" placeholder="Sem limite"
                                           value="<?= $row->max_colaboradores; ?>" min="1" class="form-control"/>
                                </div>
                                <div class="col-sm-6 text-primary" style="padding: 8px;">
                                    <i>Qtde. atual de colaboradores: <strong><?= $total_colaboradores; ?></strong></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Logo</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                            <?php
                                            $foto = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem";
                                            $nome_foto = 'Sem imagem';
                                            if ($row->foto) {
                                                $foto = base_url('imagens/usuarios/' . $row->foto);
                                                $nome_foto = $row->nome;
                                            }
                                            ?>
                                            <img src="<?= $foto; ?>" alt="<?= $nome_foto; ?>">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="width: auto; height: 150px;"></div>
                                        <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar Imagem</span>
                                            <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                            <input type="file" name="logo" class="default" accept="image/*"/>
                                        </span>
                                            <a href="#" class="btn btn-danger fileinput-exists"
                                               data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                        </div>
                                    </div>
                                    <span class="help-inline"><i>Dimensão máxima permitida (em pixels): 220x160</i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Foto descritiva</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                            <?php
                                            $foto_descricao = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem";
                                            $nome_foto_descricao = 'Sem imagem';
                                            if ($row->foto_descricao) {
                                                $foto_descricao = base_url('imagens/usuarios/' . $row->foto_descricao);
                                                $nome_foto_descricao = $row->nome;
                                            }
                                            ?>
                                            <img src="<?= $foto_descricao; ?>" alt="<?= $nome_foto_descricao; ?>">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="width: auto; height: 150px;"></div>
                                        <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar Imagem</span>
                                            <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                            <input type="file" name="logo_descricao" class="default" accept="image/*"/>
                                        </span>
                                            <a href="#" class="btn btn-danger fileinput-exists"
                                               data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                        </div>
                                    </div>
                                    <span class="help-inline"><i>Dimensão máxima permitida (em pixels): 1800x160</i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Imagem Inicial</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                            <?php
                                            $imagem_inicial = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem";
                                            $nome_imagem_inicial = '';
                                            if ($row->imagem_inicial) {
                                                $imagem_inicial = base_url('imagens/usuarios/' . $row->imagem_inicial);
                                                $nome_imagem_inicial = $row->nome;
                                            }
                                            ?>
                                            <img src="<?= $imagem_inicial; ?>" alt="<?= $nome_imagem_inicial; ?>">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="width: auto; height: 150px;"></div>
                                        <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar Imagem</span>
                                            <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                            <input type="file" name="tela-inicial" class="default" accept="image/*"/>
                                        </span>
                                            <a href="#" class="btn btn-danger fileinput-exists"
                                               data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Imagem de fundo</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                            <?php
                                            $imagem_fundo = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem";
                                            $nome_imagem_fundo = '';
                                            if ($row->imagem_fundo) {
                                                $imagem_fundo = base_url('imagens/usuarios/' . $row->imagem_fundo);
                                                $nome_imagem_fundo = $row->nome;
                                            }
                                            ?>
                                            <img src="<?= $imagem_fundo; ?>" alt="<?= $nome_imagem_fundo; ?>">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="width: auto; height: 150px;"></div>
                                        <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar Imagem</span>
                                            <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                            <input type="file" name="imagem_fundo" class="default"
                                                   accept="image/png,image/jpeg,image/jpg"/>
                                        </span>
                                            <a href="#" class="btn btn-danger fileinput-exists"
                                               data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label"></label>
                                <div class="col col-lg-9">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="imagem_fundo_padrao" name="imagem_fundo_padrao"
                                               value="1" autocomplete="off"> Usar imagem de
                                        fundo padrão
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Assinatura Digital</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                            <?php
                                            $assinatura_digital = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem";
                                            $nome_assinatura_digital = '';
                                            if ($row->assinatura_digital) {
                                                $assinatura_digital = base_url('imagens/usuarios/' . $row->assinatura_digital);
                                                $nome_assinatura_digital = $row->nome;
                                            }
                                            ?>
                                            <img src="<?= $assinatura_digital; ?>"
                                                 alt="<?= $nome_assinatura_digital; ?>">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="width: auto; height: 150px;"></div>
                                        <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar Imagem</span>
                                            <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                            <input type="file" name="assinatura-digital" class="default"
                                                   accept="image/*"/>
                                        </span>
                                            <a href="#" class="btn btn-danger fileinput-exists"
                                               data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">URL</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="text" name="url" placeholder="URL" value="<?php echo $row->url; ?>"
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label"></label>
                                <div class="col col-lg-9">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="visualizacao_pilula_conhecimento"
                                               name="visualizacao_pilula_conhecimento"
                                               value="1"<?php echo($row->visualizacao_pilula_conhecimento ? ' checked' : ''); ?>>
                                        Mostrar Pílulas de Conhecimento na tela inicial
                                    </label>
                                </div>
                            </div>
                            <div id="box-progresso" style="display: none;">
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>

                                    <div class="col-sm-9 col-lg-10 controls">
                                        <div id="progresso" class="progress progress-mini pbar">
                                            <div class="progress-bar progress-bar-success ui-progressbar-value"
                                                 style="width:0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                    <button type="submit" name="submit" class="btn btn-primary"><i class="icon-ok"></i>
                                        Editar
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <fieldset>
                                    <legend>
                                        <small>Sistema de gerenciamento de acesso à funcionalidades da Plataforma
                                        </small>
                                    </legend>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[PD]" value="11"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Lista de Pendências
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[SA]" value="23"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Scheduler - Atividades
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[GP]" value="12"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Gestão Operacional GP
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[EO]" value="24"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Estrutura Organizacional
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[JD]" value="13"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Job Descriptor
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[PS]" value="14"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Gestão Processos Seletivos
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[PC]" value="15"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Programas de Capacitação
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[DO]" value="16"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Gestão de Documentos
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[AS]" value="17"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Ferramentas de Assessment
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[DE]" value="18"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Gestão de Desempenho
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[PE]" value="19"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Gestão de Pesquisas
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[OS]" value="20"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Ordens de Serviço
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[GC]" value="26"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Gestão Comercial
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[FA]" value="21"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Gestão de Facilities
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[PL]" value="22"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Gestão da Plataforma
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="hash_acesso[RG]" value="25"><i
                                                    class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;
                                            Relatórios de Gestão
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                            </form>
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
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">
    <!-- Js -->

    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Editar Empresa - <?php echo $row->nome; ?>';

            var hash_acesso = <?= $row->hash_acesso ?>;
            $.each(hash_acesso, function (i, value) {
                $('input[name="hash_acesso[' + i + ']"][value="' + value + '"]').trigger('click');
            });
        });

        /*$('#tree input[type="checkbox"]').change(function (e) {

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
        });*/

        $('#imagem_fundo_padrao').on('change', function () {
            $('[name="imagem_fundo"]').prop('disabled', $(this).is(':checked'));
        });
    </script>
<?php
require_once "end_html.php";
?>