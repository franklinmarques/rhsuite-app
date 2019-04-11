<?php
require_once APPPATH . "views/header.php";
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <section class="panel">
                    <header class="panel-heading">
                        <i class="fa fa-plus-square"></i> Criar um novo treinamento
                    </header>
                    <div class="panel-body">
                        <?php echo form_open('ead/cursos/ajax_add', 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Nome do treinamento</label>

                            <div class="col-lg-7 controls">
                                <input type="text" name="nome" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Programa do treinamento</label>

                            <div class="col-lg-7 controls">
                                <textarea name="descricao" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-3 control-label">Categoria do treinamento</label>

                            <div class="col-sm-7">
                                <input type="text" name="categoria" id="categoria" class="form-control" onkeydown="autoComplete('#categoria', 'ajax_categorias')"/>
                                <span class="help-block">Selecione uma categoria ou digite uma nova, exemplo: Comportamental, Técnico Genérico, Técnico Específico, etc.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-3 control-label">Área de conhecimento</label>

                            <div class="col-sm-7">
                                <input type="text" name="area_conhecimento" id="area_conhecimento" class="form-control" onkeydown="autoComplete('#area_conhecimento', 'ajax_areaConhecimento')"/>
                                <span class="help-block">Selecione uma área de conhecimento ou digite uma nova, exemplo: Administração, Desenvolvimento de Pessoas, Engenharia, Financeiro, Contábil, Recursos Humanos, etc.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Tipo de treinamento</label>

                            <div class="col col-lg-9">
                                <?php if ($this->session->userdata('tipo') == 'empresa') : ?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="publico" value="1"> Público
                                    </label>
                                <?php endif; ?>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="gratuito" value="1"> Gratuito
                                </label>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Carga horária - duração (horas)</label>

                            <div class="col-lg-2 controls">
                                <input type="number" name="horas_duracao" class="form-control"/>
                            </div>

                        </div>                        
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Progressão do treinamento</label>

                            <div class="col col-lg-9">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="progressao_linear" value="" checked=""> 
                                        Não-linear (pode avançar páginas e finalizá-las mais tarde)
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="progressao_linear" value="1"> 
                                        Linear (finaliza uma página automaticamente ao avançar para a próxima)
                                    </label>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Objetivos do treinamento</label>

                            <div class="col-lg-7 controls">
                                <textarea name="objetivos" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Competências técnicas genericas</label>

                            <div class="col-lg-7 controls">
                                <input id="competencias_genericas" name="competencias_genericas" type="text" class="tags"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Competências técnicas específicas</label>

                            <div class="col-lg-7 controls">
                                <input id="competencias_especificas" data-role="tagsinput" name="competencias_especificas" type="text" class="tags"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Competências comportamentais</label>

                            <div class="col-lg-7 controls">
                                <input id="competencias_comportamentais" data-role="tagsinput" name="competencias_comportamentais" type="text" class="tags"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Nome do consultor</label>

                            <div class="col-lg-7 controls">
                                <input type="text" class="form-control" name="consultor"/>
                            </div>
                        </div>
                        <div class="form-group last">
                            <label class="col-sm-3 control-label">Foto do consultor</label>

                            <div class="col-lg-7 controls">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                        <img src="https://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem" alt=""/>
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="width: auto; height: 150px;"></div>
                                    <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                            <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                            <input type="file" name="foto_consultor" class="default" accept="image/*"/>
                                        </span>
                                        <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Currículo do consultor</label>

                            <div class="col-lg-7 controls">
                                <textarea name="curriculo" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group last">
                            <label class="col-sm-3 control-label">Foto do treinamento</label>

                            <div class="col-lg-7 controls">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                        <img src="https://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem" alt=""/>
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="width: auto; height: 150px;"></div>
                                    <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                            <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                            <input type="file" name="foto_treinamento" class="default" accept="image/*"/>
                                        </span>
                                        <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Pré-requisitos</label>

                            <div class="col-lg-7 controls">
                                <input type="text" class="form-control" name="pre_requisitos"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3">
                                <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                    &nbsp;Cadastrar
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
require_once APPPATH . "views/end_js.php";
?>
<!-- Css -->
<link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">
<link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.css"); ?>"/>

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Adicionar Treinamento';
    });
</script>

<script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>

<script>

    $('.tags').tagsInput({width: 'auto', defaultText: ''});

    function autoComplete(campo, funcao) {
        $(campo).autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: '<?= site_url('ead/cursos'); ?>/' + funcao,
                    dataType: "json",
                    data: {
                        termo: request.term
                    },
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {
                                label: item,
                                value: item
                            }
                        }));
                    }
                });
            },
            autoFocus: true,
            minLength: 0
        });
    }

</script>
<?php
require_once APPPATH . "views/end_html.php";
?>

