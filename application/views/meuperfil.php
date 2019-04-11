<?php
require_once "header.php";
?>
    <!--main content start-->
    <section id="main-content" class="<?= $this->session->userdata('tipo') === 'cliente' ? 'merge-left' : ''; ?>">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <?php if ($this->session->userdata('tipo') === 'cliente'): ?>
                            <header class="panel-heading">
                                <i class="fa fa-reorder"></i> Meu perfil
                                <button class="btn btn-default btn-sm" onclick="javascript:history.back()"
                                        style="float:right; margin-top: -0.3%;"><i
                                            class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                </button>
                            </header>
                        <?php else: ?>
                            <header class="panel-heading">
                                <i class="fa fa-reorder"></i> Meu perfil
                            </header>
                        <?php endif; ?>
                        <div class="panel-body">
                            <?php echo form_open('home/editarmeuperfil_json', 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Logo</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                            <?php
                                            //                                        $nome_foto = empty($row->foto) or $row->foto == 'avatar.jpg' ? $this->session->userdata('foto') : $row->foto;
                                            $nome_foto = $row->foto;
                                            $logo = base_url('imagens/usuarios/' . $nome_foto);

                                            if (!$row->foto) {
                                                $nome_foto = 'Sem imagem';
                                                $logo = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem";
                                            }
                                            ?>
                                            <img src="<?= $logo; ?>" alt="<?= $nome_foto; ?>">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="width: auto; height: 150px;"></div>
                                        <div><span class="fileinput-filename"></span></div>
                                        <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                            <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                            <input type="file" name="foto" class="default" accept="image/*">
                                        </span>
                                            <a href="#" class="btn btn-default fileinput-exists"
                                               data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                            &emsp;<span class="help-inline"><i>Dimensão máxima permitida (em pixels): 220x160</i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($row->tipo != 'funcionario'): ?>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label">Cabeçalho relatórios</label>
                                    <div class="col-sm-9 col-lg-10 controls">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                                <?php
                                                $nome_foto_descricao = $row->foto_descricao;
                                                $logo_descricao = base_url('imagens/usuarios/' . $nome_foto_descricao);

                                                if (!$row->foto_descricao) {
                                                    $nome_foto_descricao = 'Sem imagem';
                                                    $logo_descricao = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem";
                                                }
                                                ?>
                                                <img src="<?= $logo_descricao; ?>" alt="<?= $nome_foto_descricao; ?>">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="width: auto; height: 150px;"></div>
                                            <div><span class="fileinput-filename"></span></div>
                                            <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                                <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                                <input type="file" name="foto_descricao" class="default"
                                                       accept="image/*">
                                            </span>
                                                <a href="#" class="btn btn-default fileinput-exists"
                                                   data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                                &emsp;<span class="help-inline"><i> Dimensão máxima permitida (em pixels): 1800x160</i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label">Cabeçalho</label>

                                    <div class="col-sm-9 col-lg-10 controls">
                                        <input type="text" name="cabecalho" placeholder="Cabeçalho"
                                               value="<?php echo $row->cabecalho; ?>" class="form-control"/>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Nome</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="text" name="nome" placeholder="Nome" value="<?php echo $row->nome; ?>"
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">E-mail</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="text" name="email" placeholder="E-mail"
                                           value="<?php echo $row->email; ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Obs.:</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <p>(Caso não queira alterar a senha, deixe os campos abaixo em branco)</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Senha antiga</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="password" name="senhaantiga" placeholder="Senha antiga"
                                           class="form-control" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Nova senha</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="password" name="novasenha" placeholder="Nova senha"
                                           class="form-control" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Confirmar nova senha</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="password" name="confirmarnovasenha" placeholder="Confirmar nova senha"
                                           class="form-control" autocomplete="off"/>
                                </div>
                            </div>
                            <?php if ($row->tipo != 'funcionario'): ?>
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
                                <!--<div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Logo</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                            <?php /*                                            $foto = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem";
                              $nome_foto = '';
                              if ($row->foto) {
                              $foto = base_url('imagens/usuarios/' . $row->foto);
                              $nome_foto = $row->nome;
                              }
                             */ ?>
                                            <img src="<? /*= $foto; */ ?>" alt="<? /*= $nome_foto; */ ?>">
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
                                </div>
                            </div>-->
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
                                                   data-dismiss="fileinput"><i class="fa fa-trash"></i>
                                                    Remover</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label"></label>
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
                            <?php endif; ?>
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
                                    <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-ok"></i>
                                        Salvar
                                    </button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
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
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Meu Perfil';
        });
    </script>

    <script src="<?php echo base_url('assets/js//maskedinput/maskedinput.js'); ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>

    <script>
        $(function () {
            $('.cep').mask('99999-999');
            $('input[name=telefone]').mask("(99) 9999-9999?9").focusout(function () {
                var p = $(this).val().replace(/\D/g, '');
                $(this).unmask();
                if (p.length > 10) {
                    $(this).mask("(99) 99999-999?9");
                } else {
                    $(this).mask("(99) 9999-9999?9");
                }
            });

            $('#imagem_fundo_padrao').on('change', function () {
                $('[name="imagem_fundo"]').prop('disabled', $(this).is(':checked'));
            });
        });
    </script>
<?php
require_once "end_html.php";
?>