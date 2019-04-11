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
                        <i class="fa fa-reorder"></i> Cadastrar Treinamento para Funcionário - <?php echo $row->nome; ?>
                        <span class="tools pull-right">
                            <a class="btn btn-default btn-sm"
                               href="<?php echo site_url('home/cursosfuncionario/' . $row->id); ?>" style="color: #FFF; margin-top: -5%;">
                                <i class="fa fa-reply"></i> &nbsp;&nbsp; Voltar
                            </a>
                        </span>
                    </header>
                    <div class="panel-body">
                        <?php echo form_open('home/novocursofuncionario_json/' . $row->id, 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Nome treinamento</label>
                            <div class="col-sm-9 col-lg-8 controls">
                                <select name="curso" class="form-control">
                                    <option value="">Selecione</option>
                                    <?php foreach ($cursos->result() as $row_) { ?>
                                        <option value="<?php echo $row_->id; ?>"><?php echo $row_->curso; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-lg-2 control-label">Período de realização do treinamento</label>
                            <div class='col-sm-9 col-lg-10'>
                                <div class="form-inline form-group" style="padding-left: 15px;">
                                    <label for="data_inicio" style='font-weight: normal'> De </label>
                                    <input type="text" class="form-control text-center" name="data_inicio" id="data_inicio" placeholder="dd/mm/aaaa" style="width: 200px;">
                                    <label for="data_maxima" style='font-weight: normal'> até </label>
                                    <input type="text" class="form-control text-center" name="data_maxima" id="data_maxima" placeholder="dd/mm/aaaa" style="width: 200px;">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Nota mínima para emissão de certificado</label>
                            <div class="col-sm-2 col-md-3 col-lg-2">
                                <div class="input-group">
                                    <input name="nota_aprovacao" id="nota_aprovacao" size="3" min='0' max='100' class="form-control text-right" type="number">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-check"></i> Cadastrar</button>
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
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Cadastrar Treinamento para Funcionário - <?php echo $row->nome; ?>';
    });
</script>

<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

<script>
    $('#data_inicio, #data_maxima').mask('00/00/0000');
</script>

<?php
require_once "end_html.php";
?>
