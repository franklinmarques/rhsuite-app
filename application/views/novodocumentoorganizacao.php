<?php
require_once "header.php";
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
                        <i class="fa fa-file-o"></i> Cadastrar Documento - Organização
                    </header>
                    <div class="panel-body">
                        <?php echo form_open('documento/documentoOrganizacao_db', 'data-aviso="alert" class="form-horizontal ajax-upload" enctype="multipart/form-data"'); ?>
                        <div class="form-group last">
                            <label class="col-sm-3 control-label">Tipo</label>

                            <div class="col-lg-7 controls">
                                <select class="form-control" name="tipo" id="tipo">
                                    <?php
                                    foreach ($tipos as $tipo) {
                                        ?>
                                        <option value="<?= $tipo->id; ?>"><?= $tipo->descricao; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Descrição</label>

                            <div class="col-lg-7 controls">
                                <input type="text" name="descricao" placeholder="Descrição" value=""
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Arquivo</label>

                            <div class="col-lg-7 controls">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Selecionar arquivo</span>
                                        <span class="fileinput-exists">Alterar</span>
                                        <input type="file" name="arquivo" accept=".pdf"/>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                </div>

                                <p class="help-block">Formato permitido: .pdf (tamanho máximo: 100 Mb)</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3">
                                <button type="submit" name="submit" class="btn btn-primary"><i
                                        class="fa fa-save"></i>
                                    &nbsp;Cadastrar
                                </button>
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
require_once "end_js.php";
?>
<!-- Css -->
<link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Adicionar documento';
    });
</script>

<script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
<?php
require_once "end_html.php";
?>