<?php
require_once APPPATH . "views/header.php";
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <section class="panel">
                    <header class="panel-heading">
                        <i class="fa fa-plus-square"></i> Novo Item
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="alert"></div>
                                <?php echo form_open('home/novabiblioteca_json', 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                                <div class="box">
                                    <div class="box-title">
                                        <h3></h3>
                                    </div>
                                    <div class="box-content">
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Título</label>
                                            <div class="col-sm-9 col-lg-10 controls">
                                                <input type="text" name="titulo" placeholder="Título" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Tipo</label>
                                            <div class="col-sm-9 col-lg-10 controls">
                                                <select name="tipo" class="form-control input-sm">
                                                    <option value="">Selecione</option>
                                                    <option value="1">Aula Digital</option>
                                                    <option value="2">Jogos</option>
                                                    <option value="3">Livros digitais</option>
                                                    <option value="4">Experimentos</option>
                                                    <option value="5">Softwares</option>
                                                    <option value="6">Áudios</option>
                                                    <option value="7">Links Externos</option>
                                                    <option value="8">Multimídia</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Categoria</label>
                                            <div class="col-sm-9 col-lg-10 controls">
                                                <select name="categoria" class="form-control input-sm">
                                                    <option value="">Selecione</option>
                                                    <?php foreach ($categoria->result() as $row_) { ?>
                                                        <option value="<?php echo $row_->id; ?>"><?php echo $row_->curso; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Descrição</label>
                                            <div class="col-sm-9 col-lg-10 controls">
                                                <textarea name="descricao" placeholder="Descrição" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Link</label>
                                            <div class="col-sm-9 col-lg-10 controls">
                                                <input type="text" name="link" placeholder="Link" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Disciplina</label>
                                            <div class="col-sm-9 col-lg-10 controls">
                                                <input type="text" name="disciplina" placeholder="Disciplina" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Ano/Série</label>
                                            <div class="col-sm-9 col-lg-10 controls">
                                                <input type="text" name="anoserie" placeholder="Ano/Série" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Tema Curricular</label>
                                            <div class="col-sm-9 col-lg-10 controls">
                                                <input type="text" name="temacurricular" placeholder="Tema Curricular" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Uso</label>
                                            <div class="col-sm-9 col-lg-10 controls">
                                                <input type="text" name="uso" placeholder="Uso" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Licença</label>
                                            <div class="col-sm-9 col-lg-10 controls">
                                                <input type="text" name="licenca" placeholder="Licença" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Produzido Por</label>
                                            <div class="col-sm-9 col-lg-10 controls">
                                                <input type="text" name="produzidopor" placeholder="Produzido Por" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Tags</label>
                                            <div class="col-sm-9 col-lg-10 controls">
                                                <input type="text" name="tags" placeholder="Tags" value="" class="form-control" />
                                                <span class="help-inline">Separe as TAGS por "vírgulas"</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                                <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save"></i> Cadastrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
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
require_once APPPATH . "views/end_js.php";
?>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Novo Item';
    });
</script>
<?php
require_once APPPATH . "views/end_html.php";
?>