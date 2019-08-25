<?php
require_once APPPATH . 'views/header.php';
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
                        <i class="fa fa-pencil-square-o"></i> Editar Item - <?php echo $row->titulo; ?>
                    </header>
                    <div class="panel-body">
                        <?php echo form_open('home/editarbiblioteca_json/' . $row->id, 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Título</label>
                            <div class="col-sm-9 col-lg-10 controls">
                                <input type="text" name="titulo" placeholder="Título" value="<?php echo $row->titulo; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Tipo</label>
                            <div class="col-sm-9 col-lg-10 controls">
                                <select name="tipo" class="form-control input-sm">
                                    <option value="">Selecione</option>
                                    <option value="1"<?= ($row->tipo == '1' ? ' selected="selected"' : ''); ?>>Aula Digital</option>
                                    <option value="2"<?= ($row->tipo == '2' ? ' selected="selected"' : ''); ?>>Jogos</option>
                                    <option value="3"<?= ($row->tipo == '3' ? ' selected="selected"' : ''); ?>>Livros digitais</option>
                                    <option value="4"<?= ($row->tipo == '4' ? ' selected="selected"' : ''); ?>>Experimentos</option>
                                    <option value="5"<?= ($row->tipo == '5' ? ' selected="selected"' : ''); ?>>Softwares</option>
                                    <option value="6"<?= ($row->tipo == '6' ? ' selected="selected"' : ''); ?>>Áudios</option>
                                    <option value="7"<?= ($row->tipo == '7' ? ' selected="selected"' : ''); ?>>Links Externos</option>
                                    <option value="8"<?= ($row->tipo == '8' ? ' selected="selected"' : ''); ?>>Multimídia</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Categoria</label>
                            <div class="col-sm-9 col-lg-10 controls">
                                <select name="categoria" class="form-control input-sm">
                                    <option value="">Selecione</option>
                                    <?php foreach ($categoria->result() as $row_) { ?>
                                        <option value="<?= $row_->id; ?>" <?= ($row->categoria == $row_->id ? "selected=\"selected\"" : ''); ?>><?= $row_->curso; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Descrição</label>
                            <div class="col-sm-9 col-lg-10 controls">
                                <textarea name="descricao" placeholder="Descrição" class="form-control" rows="3"><?php echo $row->descricao; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Link</label>
                            <div class="col-sm-9 col-lg-10 controls">
                                <input type="text" name="link" placeholder="Link" value="<?php echo $row->link; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Disciplina</label>
                            <div class="col-sm-9 col-lg-10 controls">
                                <input type="text" name="disciplina" placeholder="Disciplina" value="<?php echo $row->disciplina; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Ano/Série</label>
                            <div class="col-sm-9 col-lg-10 controls">
                                <input type="text" name="anoserie" placeholder="Ano/Série" value="<?php echo $row->anoserie; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Tema Curricular</label>
                            <div class="col-sm-9 col-lg-10 controls">
                                <input type="text" name="temacurricular" placeholder="Tema Curricular" value="<?php echo $row->temacurricular; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Uso</label>
                            <div class="col-sm-9 col-lg-10 controls">
                                <input type="text" name="uso" placeholder="Uso" value="<?php echo $row->uso; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Licença</label>
                            <div class="col-sm-9 col-lg-10 controls">
                                <input type="text" name="licenca" placeholder="Licença" value="<?php echo $row->licenca; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Produzido Por</label>
                            <div class="col-sm-9 col-lg-10 controls">
                                <input type="text" name="produzidopor" placeholder="Produzido Por" value="<?php echo $row->produzidopor; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Tags</label>
                            <div class="col-sm-9 col-lg-10 controls">
                                <input type="text" name="tags" placeholder="Tags" value="<?php echo $row->tags; ?>" class="form-control" />
                                <span class="help-inline">Separe as TAGS por "vírgulas"</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                <button type="submit" name="submit" class="btn btn-primary"><i class="icon-ok"></i> Editar</button>
                            </div>
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
require_once APPPATH . 'views/end_js.php';
?>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Editar Item - <?php echo $row->titulo; ?>';
    });
</script>
<?php
require_once APPPATH . 'views/end_html.php';
?>