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
                                           class="form-control" autocomplete="new-password"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Nova senha</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="password" name="novasenha" placeholder="Nova senha"
                                           class="form-control" autocomplete="new-password"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Confirmar nova senha</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="password" name="confirmarnovasenha" placeholder="Confirmar nova senha"
                                           class="form-control" autocomplete="new-password"/>
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
                                    <label class="col-lg-2 control-label">Fundo da tela inicial</label>
                                    <div class="col col-lg-3">
                                        <?php echo form_dropdown('tipo_tela_inicial', $fundo_tela_inicial, $row->tipo_tela_inicial, 'id="tipo_tela_inicial" class="form-control input-sm"'); ?>
                                    </div>
                                </div>

                                <div class="form-group" id="upload_imagem"
                                     style="display: <?= $row->tipo_tela_inicial === '3' ? 'block' : 'none' ?>;">
                                    <label class="col-sm-3 col-lg-2 control-label">Imagem de fundo</label>
                                    <div class="col-sm-9 col-lg-10 controls">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                                <?php
                                                $nome_imagem_fundo = 'Sem imagem';
                                                $imagem_fundo = "http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem";
                                                if ($row->imagem_fundo) {
                                                    $nome_imagem_fundo = $row->imagem_fundo;
                                                    $imagem_fundo = base_url('imagens/usuarios/' . $row->imagem_fundo);
                                                }
                                                ?>
                                                <img src="<?= $imagem_fundo; ?>" alt="<?= $nome_imagem_fundo; ?>">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="width: auto; height: 150px;"></div>
                                            <div><span class="fileinput-filename"></span></div>
                                            <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                                <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                                <input type="file" name="imagem_fundo" class="default"
                                                       accept="image/png,image/jpeg,image/jpg">
                                            </span>
                                                <a href="#" class="btn btn-default fileinput-exists"
                                                   data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group" id="upload_video"
                                     style="display: <?= $row->tipo_tela_inicial === '4' ? 'block' : 'none' ?>;">
                                    <label class="col-sm-3 col-lg-2 control-label">Vídeo de fundo (.mp4)</label>
                                    <div class="col-sm-9 col-lg-10 controls">
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <div class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">Selecionar vídeo</span>
                                                <span class="fileinput-exists">Alterar</span>
                                                <input type="file" name="video_fundo" accept="video/mp4"/>
                                            </div>
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                               data-dismiss="fileinput">Remover</a>
                                        </div>
                                        <?php if ($row->video_fundo): ?>
                                            <i class="help-block">Vídeo selecionado: <?= $row->video_fundo; ?></i>
                                        <?php endif; ?>
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

            $('#tipo_tela_inicial').on('change', function () {
                $('#upload_imagem, #upload_video').hide();
                if (this.value === '3') {
                    $('#upload_imagem').show();
                } else if (this.value === '4') {
                    $('#upload_video').show();
                } else {

                }
            });
        });
    </script>
<?php
require_once "end_html.php";
?>