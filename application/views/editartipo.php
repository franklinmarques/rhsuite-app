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
                        <i class="fa fa-file-o"></i> Cadastrar Tipo - Colaborador
                    </header>
                    <div class="panel-body">
                        <?php echo form_open('tipo/editar_db/' . $row->id, 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                        <div class="form-group last">
                            <label class="col-sm-3 control-label">Categoria</label>

                            <div class="col-lg-7 controls">
                                <select class="form-control" name="categoria" id="categoria">
                                    <option value="1"<?= ($row->categoria == 1 ? ' selected="selected"' : ''); ?>>Colaborador</option>
                                    <option value="2"<?= ($row->categoria == 2 ? ' selected="selected"' : ''); ?>>Organização</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Descrição</label>

                            <div class="col-lg-7 controls">
                                <input type="text" name="descricao" placeholder="Descrição" value="<?= $row->descricao; ?>"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3">
                                <button type="submit" name="submit" class="btn btn-primary"><i
                                        class="fa fa-save"></i>
                                    &nbsp;Salvar
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
        document.title = 'CORPORATE RH - LMS - Adicionar Funcionário';
    });
</script>

<script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
<?php
require_once "end_html.php";
?>