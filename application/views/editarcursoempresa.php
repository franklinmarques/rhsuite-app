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
                        <i class="icon-reorder"></i> Editar Treinamento na Empresa - <?php echo $row->nome; ?>
                    </header>
                    <div class="panel-body">
                        <?php echo form_open('home/editarcursoempresa_json/' . $row->id, 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Treinamento</label>

                            <div class="col-sm-9 col-lg-9 controls">
                                <select name="curso" class="form-control input-sm">
                                    <option value="">Selecione</option>
                                    <?php
                                    foreach ($cursos->result() as $row_) {
                                        if ($row_->id == $curso_edicao->curso) {
                                            ?>
                                            <option value="<?php echo $row_->id; ?>"<?= ($row_->id == $curso_edicao->curso ? ' selected=""' : ''); ?>><?= $row_->curso; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Data máxima de acesso</label>

                            <div class="col-sm-3 col-lg-4">
                                <input class="form-control form-control-inline input-medium default-date-picker"
                                       size="16" type="text"
                                       value="<?= implode("/", array_reverse(explode("-", $curso_edicao->data_maxima))); ?>"
                                       name="data_maxima"/>
                                <span class="help-block">Selecione a data</span>
                            </div>

                            <label class="col-sm-3 col-lg-3 control-label">Número máximo de colaboradores</label>

                            <div class="col-lg-2 controls">
                                <div id="spinner_colaboradores">
                                    <div class="input-group" style="width:150px;">
                                        <input type="text" class="spinner-input form-control" maxlength="6"
                                               readonly name="colaboradores_maximo"
                                               value="<?= $curso_edicao->colaboradores_maximo; ?>">

                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn btn-default spinner-up">
                                                <i class="fa fa-angle-up"></i>
                                            </button>
                                            <button type="button" class="btn btn-default spinner-down">
                                                <i class="fa fa-angle-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                <button type="submit" name="submit" class="btn btn-primary"><i
                                        class="fa fa-check"></i>
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
require_once "end_js.php";
?>
<!-- Css -->
<link rel="stylesheet" href="<?= base_url('assets/js/bootstrap-datepicker/css/datepicker.css'); ?>"/>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Cadastrar Treinamento na Empresa - <?php echo $row->nome; ?>';
    });
</script>
<script src="<?= base_url('assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>"></script>
<script src="<?= base_url('assets/js/fuelux/js/spinner.min.js'); ?>"></script>
<script>
    //date picker start

    if (top.location !== location) {
        top.location.href = document.location.href;
    }
    $(function () {
        window.prettyPrint && prettyPrint();
        $('.default-date-picker').datepicker({
            format: 'dd/mm/yyyy'
        });
        $('.dpYears').datepicker();
        $('.dpMonths').datepicker();


        var startDate = new Date(2012, 1, 20);
        var endDate = new Date(2012, 1, 25);
        $('.dp4').datepicker()
                .on('changeDate', function (ev) {
                    if (ev.date.valueOf() > endDate.valueOf()) {
                        $('.alert').show().find('strong').text('The start date can not be greater then the end date');
                    } else {
                        $('.alert').hide();
                        startDate = new Date(ev.date);
                        $('#startDate').text($('.dp4').data('date'));
                    }
                    $('.dp4').datepicker('hide');
                });
        $('.dp5').datepicker()
                .on('changeDate', function (ev) {
                    if (ev.date.valueOf() < startDate.valueOf()) {
                        $('.alert').show().find('strong').text('The end date can not be less then the start date');
                    } else {
                        $('.alert').hide();
                        endDate = new Date(ev.date);
                        $('.endDate').text($('.dp5').data('date'));
                    }
                    $('.dp5').datepicker('hide');
                });

        // disabling dates
        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

        var checkin = $('.dpd1').datepicker({
            onRender: function (date) {
                return date.valueOf() < now.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function (ev) {
            if (ev.date.valueOf() > checkout.date.valueOf()) {
                var newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() + 1);
                checkout.setValue(newDate);
            }
            checkin.hide();
            $('.dpd2')[0].focus();
        }).data('datepicker');
        var checkout = $('.dpd2').datepicker({
            onRender: function (date) {
                return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function (ev) {
            checkout.hide();
        }).data('datepicker');
    });

    //date picker end

    //spinner start
    $('#spinner_colaboradores').spinner({value: 0, min: 0});
    //spinner end

</script>
<?php
require_once "end_html.php";
?>