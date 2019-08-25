<?php
require_once APPPATH . 'views/header.php';
?>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-pencil-square-o"></i> Editar um Treinamento - <?php echo $row->nome ?>
                            <span class="tools pull-right">
                            <a class="btn btn-default btn-sm" href="<?php echo site_url('ead/cursos'); ?>"
                               style="color: #FFF; margin-top: -5%;">
                                <i class="fa fa-reply"></i> &nbsp;&nbsp; Voltar
                            </a>
                        </span>
                        </header>
                        <div class="panel-body">
                            <?php echo form_open('ead/cursos/ajax_update', 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Nome do Treinamento</label>

                                <div class="col-lg-7 controls">
                                    <input type="text" name="nome" value="<?= $row->nome ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Programa do Treinamento</label>

                                <div class="col-lg-7 controls">
                                    <textarea name="descricao" class="form-control"
                                              rows="3"><?= $row->descricao ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-3 control-label">Categoria do Treinamento</label>

                                <div class="col-sm-7">
                                    <input type="text" name="categoria" id="categoria" class="form-control"
                                           value="<?= $row->categoria ?>"
                                           onkeydown="autoComplete('#categoria', 'ajax_categorias')"/>
                                    <span class="help-block">Selecione uma categoria ou digite uma nova, exemplo: Comportamental, Técnico Genérico, Técnico Específico, etc.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-3 control-label">Área de conhecimento</label>

                                <div class="col-sm-7">
                                    <input type="text" name="area_conhecimento" id="area_conhecimento"
                                           class="form-control" value="<?= $row->area_conhecimento ?>"
                                           onkeydown="autoComplete('#area_conhecimento', 'ajax_areaConhecimento')"/>
                                    <span class="help-block">Selecione uma área de conhecimento ou digite uma nova, exemplo: Administração, Desenvolvimento de Pessoas, Engenharia, Financeiro, Contábil, Recursos Humanos, etc.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Tipo de treinamento</label>

                                <div class="col col-lg-9">
                                    <?php if ($this->session->userdata('tipo') == 'empresa') : ?>
                                        <label class="checkbox-inline">
                                            <input type="checkbox"
                                                   name="publico"<?= ($row->publico == 1 ? ' checked' : ''); ?>
                                                   value="1"> Público
                                        </label>
                                    <?php endif; ?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox"
                                               name="gratuito"<?= ($row->gratuito == 1 ? ' checked' : ''); ?> value="1">
                                        Gratuito
                                    </label>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Carga horária - duração (horas)</label>

                                <div class="col-lg-2 controls">
                                    <input type="number" value="<?= $row->horas_duracao ?>" name="horas_duracao"
                                           class="form-control"/>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Progressão do treinamento</label>

                                <div class="col col-lg-9">
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   value=""<?= ($row->progressao_linear == '1' ? '' : ' checked'); ?>
                                                   name="progressao_linear">
                                            Não-linear (pode avançar páginas e finalizá-las mais tarde)
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   value="1"<?= ($row->progressao_linear == '1' ? ' checked' : ''); ?>
                                                   name="progressao_linear">
                                            Linear (finaliza uma página automaticamente ao avançar para a próxima)
                                        </label>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Objetivos do treinamento</label>

                                <div class="col-lg-7 controls">
                                <textarea name="objetivos" rows="5"
                                          class="form-control"><?= $row->objetivos ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Competências Técnicas Genericas</label>

                                <div class="col-lg-7 controls">
                                    <input id="competencias_genericas" data-role="tagsinput"
                                           name="competencias_genericas" type="text"
                                           class="tags" value="<?= $row->competencias_genericas ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Competências Técnicas Específicas</label>

                                <div class="col-lg-7 controls">
                                    <input id="competencias_especificas" data-role="tagsinput"
                                           name="competencias_especificas" type="text"
                                           class="tags" value="<?= $row->competencias_especificas ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Competências Comportamentais</label>

                                <div class="col-lg-7 controls">
                                    <input id="competencias_comportamentais" data-role="tagsinput"
                                           name="competencias_comportamentais" type="text"
                                           class="tags" value="<?= $row->competencias_comportamentais ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Nome do Consultor</label>

                                <div class="col-lg-7 controls">
                                    <input type="text" class="form-control" value="<?= $row->consultor ?>"
                                           name="consultor"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Foto do Consultor</label>

                                <div class="col-lg-3 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                            <?php
                                            $foto_consultor = base_url('imagens/usuarios/' . $row->foto_consultor);
                                            if (!$row->foto_consultor) {
                                                $foto_consultor = "https://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem";
                                            }
                                            ?>
                                            <img src="<?= $foto_consultor; ?>" alt="">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="width: auto; height: 150px;"></div>
                                        <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar Imagem</span>
                                            <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                            <input type="file" name="foto_consultor" class="default" accept="image/*"/>
                                        </span>
                                            <a href="#" class="btn btn-danger fileinput-exists"
                                               data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Currículo do Consultor</label>

                                <div class="col-lg-7 controls">
                                    <textarea name="curriculo" rows="5"
                                              class="form-control"><?= $row->curriculo ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Foto do Treinamento</label>

                                <div class="col-lg-3 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                            <?php
                                            $foto_treinamento = base_url('imagens/usuarios/' . $row->foto_treinamento);
                                            if (!$row->foto_treinamento) {
                                                $foto_treinamento = "https://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem";
                                            }
                                            ?>
                                            <img src="<?= $foto_treinamento; ?>" alt="">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="width: auto; height: 150px;"></div>
                                        <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar Imagem</span>
                                            <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                            <input type="file" name="foto_treinamento" class="default"
                                                   accept="image/*"/>
                                        </span>
                                            <a href="#" class="btn btn-danger fileinput-exists"
                                               data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Pré-requisitos</label>

                                <div class="col-lg-7 controls">
                                    <input type="text" class="form-control" value="<?= $row->pre_requisitos ?>"
                                           name="pre_requisitos"/>
                                </div>
                            </div>
                            <?php if ($this->session->userdata('tipo') == 'administrador'): ?>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Proprietário</label>

                                    <div class="col-lg-7 controls">
                                        <select id="id_empresa" name="id_empresa" class="form-control">
                                            <?php foreach ($usuarios->result() AS $usuario) : ?>
                                                <option value="<?= $usuario->id; ?>"<?= ($row->id_empresa == $usuario->id ? ' selected=""' : '') ?>>
                                                    <?= $usuario->nome; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-3">
                                    <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-ok"></i>
                                        Salvar
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="<?= base64_encode($row->id) ?>">
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
require_once APPPATH . 'views/end_js.php';
?>
    <!-- Css -->
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.css"); ?>"/>
    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Editar Treinamento - <?php echo $row->nome; ?>';
        });
    </script>

    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>

    <script>
        $('.tags').tagsInput({'width': 'auto', 'defaultText': ''});

        function autoComplete(campo, funcao) {
            $(campo).autocomplete({
                'source': function (request, response) {
                    $.ajax({
                        'url': '<?= site_url('ead/cursos'); ?>/' + funcao,
                        'dataType': 'json',
                        'data': {
                            'termo': request.term
                        },
                        'success': function (json) {
                            response($.map(json, function (item) {
                                return {
                                    'label': item,
                                    'value': item
                                };
                            }));
                        }
                    });
                },
                'autoFocus': true,
                'minLength': 0
            });
        }
    </script>
<?php
require_once APPPATH . 'views/end_html.php';
?>