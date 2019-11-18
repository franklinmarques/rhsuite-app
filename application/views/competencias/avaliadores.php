<?php
require_once APPPATH . 'views/header.php';
?>
<style>
    .btn-success {
        background-color: #5cb85c;
        border-color: #4cae4c;
        color: #fff;
    }

    .btn-primary {
        background-color: #337ab7 !important;
        border-color: #2e6da4 !important;
        color: #fff;
    }

    .btn-info {
        color: #fff;
        background-color: #5bc0de;
        border-color: #46b8da;
    }

    .btn-warning {
        color: #fff;
        background-color: #f0ad4e;
        border-color: #eea236;
    }

    .btn-danger {
        color: #fff;
        background-color: #d9534f;
        border-color: #d43f3a;
    }

    .text-nowrap {
        white-space: nowrap;
    }
</style>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <form id="demoform" action="" method="post">
                    <input type="hidden" name="id_competencia" id="id_competencia" value="<?= $id_competencia ?>"/>
                    <input type="hidden" name="id" id="id" value="<?= $id ?>"/>

                    <div class="row">
                        <div class="col col-sm-7">
                            <div class="tab-pane active" role="tabpanel" id="step1">
                                <label>Colaborador a ser avaliado *</label>
                                <select name="id_usuario" id="avaliado" class="form-control">
                                    <option value="">selecione...</option>
                                    <?php foreach ($comboAvaliado as $row): ?>
                                        <option value="<?= $row->id ?>"<?= $row->cargo && $row->funcao ? '' : ' class="text-danger"' ?><?= $row->id == $avaliado ? ' selected' : '' ?>><?= $row->nome ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="help-block"><i>* Os colaboradores em vermelho não possuem cargo ou função
                                        definidos.</i></small>
                            </div>
                        </div>
                        <div class="col-sm-5 text-right">
                            <br>
                            <a id="associar" class="btn btn-primary disabled" href="#" role="button">Editar colaborador
                                selecionado</a>
                            <!--<button type="button" id="salvar" class="btn btn-primary">Associar avaliado ao cargo-função</button>-->
                            <button type="button" id="salvar" class="btn btn-success">Salvar</button>
                            <button class="btn btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por departamento</label>
                                        <?php echo form_dropdown('depto', $depto, '', 'class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por área</label>
                                        <?php echo form_dropdown('area', $area, '', 'class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por setor</label>
                                        <?php echo form_dropdown('setor', $setor, '', 'class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label>&nbsp;</label><br>
                                        <div class="btn-group" role="group" aria-label="...">
                                            <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
                                                Limpar filtros
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="step2">
                        <?php echo form_multiselect('id_usuario_avaliadores[]', $duallistAvaliadores, $avaliadores, 'size="10" id="id_usuario_avaliadores" class="avaliadores"') ?>
                    </div>
                </form>
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
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-snippets/bootstrap-snippets.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Gerenciar colaboradores';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
<script src="<?php echo base_url('assets/bootstrap-snippets/bootstrap-snippets.js') ?>"></script>

<script>
    var avaliadores, selecionados, demo2;

    $(document).ready(function () {
        avaliadores = $('#id_usuario_avaliadores').val();

        verificar_cargo($('#avaliado').val());
        demo2 = $('.avaliadores').bootstrapDualListbox({
            'nonSelectedListLabel': 'Colaboradores disponíveis',
            'selectedListLabel': 'Colaboradores selecionados',
            'preserveSelectionOnMove': 'moved',
            'moveOnSelect': false,
            'helperSelectNamePostfix': false,
            'filterPlaceHolder': 'Filtrar',
            'infoText': false
        });

    });


    $('#salvar').click(function () {
        var dados = {
            'id': $('#id').val(),
            'id_competencia': $('#id_competencia').val(),
            'avaliado': $('#avaliado').val(),
            'id_usuario_avaliadores': $('#id_usuario_avaliadores').val(),
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        };

        $.ajax({
            'url': '<?= site_url('competencias/avaliados/ajax_save') ?>',
            'type': 'POST',
            'data': dados,
            'dataType': 'json',
            'timeout': 9000,
            'success': function (data) {
                //console.log(data);
                history.go(-1);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert(textStatus + ' ' + jqXHR.status + ': ' + (jqXHR.status === 0 ? 'Disconnected' : errorThrown));
                history.go(-1);
            }
        });

        return false;

    });

    function verificar_cargo(event) {
        var val = $(event).val();

        if ($('#avaliado option[value="' + val + '"]').hasClass('text-danger')) {
            $('#associar').removeClass('disabled').prop('href', '<?php echo site_url('home/editarfuncionario') ?>/' + val);
            $('#salvar').prop('disabled', true);
        } else {
            $('#associar').addClass('disabled', true).prop('href', '#');
            $('#salvar').prop('disabled', false);
        }
    }

    $('#avaliado').on('change', function () {
        verificar_cargo(this);
    });

    $('#id_usuario_avaliadores').on('change', function () {
//        var size = demo2.find(":selected").size();

//        if (size > 3) {
//            demo2.find(":selected").each(function (ind, sel) {
//                if (ind > 4) {
//                    $(this).prop("selected", false);
//                }
//            });
//
//        }
        avaliadores = $(this).val();
    });

    $('.filtro').on('change', function () {
        filtra_colaboradores();
    });

    $('#limpa_filtro').on('click', function () {
        $('.filtro').val('');
        filtra_colaboradores();
    });

    function filtra_colaboradores() {
        $.ajax({
            'url': '<?php echo site_url('competencias/avaliados/ajax_edit') ?>',
            'type': 'POST',
            'dataType': 'json',
            'timeout': 9000,
            'data': {
                'depto': $('[name="depto"]').val(),
                'area': $('[name="area"]').val(),
                'setor': $('[name="setor"]').val(),
                'selecionados': avaliadores
            },
            'success': function (json) {
                $('[name="area"]').html(json.area);
                $('[name="setor"]').html(json.setor);
                $('#id_usuario_avaliadores').html(json.avaliadores).val(avaliadores);

                demo2.bootstrapDualListbox('refresh', true);
            }
        });
    }

</script>

<?php
require_once APPPATH . 'views/end_html.php';
?>
