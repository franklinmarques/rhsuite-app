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
                        <i class="fa fa-reorder"></i> Editar Treinamento para Funcionário - <?php echo $row->nome; ?>
                        <span class="tools pull-right">
                            <a class="btn btn-default btn-sm"
                               href="<?php echo site_url('ead/cursos_funcionario/index/' . $row->id_usuario); ?>"
                               style="color: #FFF; margin-top: -5%;">
                                <i class="fa fa-reply"></i> &nbsp;&nbsp; Voltar
                            </a>
                        </span>
                    </header>
                    <div class="panel-body">
                        <?php echo form_open('ead/cursos_funcionario/ajax_update/' . $row->id_usuario, 'data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
                        <input type="hidden" name="id" value="<?= $row->id ?>">
                        <div class="form-group">
                            <label class="control-label col-md-2">Tipo treinamento</label>
                            <div class="col-md-3">
                                <label class="radio-inline">
                                    <input type="radio" name="tipo_treinamento"
                                           value="E"<?= $row->tipo_treinamento != 'P' ? ' checked=""' : ''; ?>> EAD
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="tipo_treinamento"
                                           value="P"<?= $row->tipo_treinamento == 'P' ? ' checked=""' : ''; ?>>
                                    Presencial
                                </label>
                            </div>
                            <label class="control-label col-md-2">Local treinamento</label>
                            <div class="col-md-3">
                                <label class="radio-inline">
                                    <input type="radio" name="local_treinamento" value="I"
                                           class="input_presencial"<?= $row->tipo_treinamento != 'P' ? ' disabled=""' : ($row->local_treinamento == 'I' ? ' checked=""' : ''); ?>>
                                    Interno
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="local_treinamento" value="E"
                                           class="input_presencial"<?= $row->tipo_treinamento != 'P' ? ' disabled=""' : ($row->local_treinamento == 'E' ? ' checked=""' : ''); ?>>
                                    Externo
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Nome treinamento</label>
                            <div class="col-sm-9 col-lg-8 controls">
                                <div class="presencial"<?= $row->tipo_treinamento != 'P' ? ' style="display: none;"' : ''; ?>>
                                    <input name="nome" placeholder="Nome de treinamento presencial" class="form-control"
                                           type="text" value="<?= $row->nome_curso; ?>">
                                </div>
                                <div class="ead"<?= $row->tipo_treinamento == 'P' ? ' style="display: none;"' : ''; ?>>
                                    <?php echo form_dropdown('id_curso', $cursos, $row->id_curso, 'class="form-control"'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-lg-2 control-label">Período realização</label>
                            <div class='col-sm-9 col-lg-10'>
                                <div class="form-inline form-group" style="padding-left: 15px;">
                                    <label for="data_inicio" style='font-weight: normal'> De </label>
                                    <input type="text" class="form-control text-center" name="data_inicio"
                                           id="data_inicio" placeholder="dd/mm/aaaa" value="<?= $row->data_inicio ?>"
                                           style="width: 200px;">
                                    <label for="data_maxima" style='font-weight: normal'> até </label>
                                    <input type="text" class="form-control text-center" name="data_maxima"
                                           id="data_maxima" placeholder="dd/mm/aaaa" value="<?= $row->data_maxima ?>"
                                           style="width: 200px;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Nota mínima para emitir certificado</label>
                            <div class="col-sm-4 col-md-3 col-lg-2">
                                <div class="input-group">
                                    <input name="nota_aprovacao" id="nota_aprovacao" value="<?= $row->nota_aprovacao ?>"
                                           size="3" min='0' max='100' class="form-control text-right" type="number">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                            <label class="col-sm-2 col-lg-1 control-label">Carga horária</label>
                            <div class="col-md-2">
                                <input class="hora form-control text-center" placeholder="hh:mm"
                                       name="carga_horaria_presencial" type="text"
                                       value="<?= $row->carga_horaria_presencial ?>">
                            </div>
                            <label class="col-sm-2 col-lg-1 control-label">Avaliação presencial</label>
                            <div class="col-md-2">
                                <input class="data form-control text-center input_presencial"
                                       name="avaliacao_presencial"
                                       type="number"
                                       value="<?= $row->avaliacao_presencial ?>" min="0" max="100">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Fornecedor/palestrante</label>
                            <div class="col-sm-9 col-lg-8 controls">
                                <input name="nome_fornecedor" placeholder="Nome do fornecedor"
                                       class="form-control input_presencial" type="text"
                                       value="<?= $row->nome_fornecedor ?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-check"></i>
                                    Salvar
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
require_once APPPATH . 'views/end_js.php';
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

    $('[name="tipo_treinamento"]').on('change', function () {
        if (this.value === 'P') {
            $('.ead').hide();
            $('.presencial').show();
            $('.input_presencial').prop('disabled', false);
        } else if (this.value === 'E') {
            $('.presencial').hide();
            $('.ead').show();
            $('.input_presencial').prop('disabled', true);
        }
    });
</script>

<?php
require_once APPPATH . 'views/end_html.php';
?>
