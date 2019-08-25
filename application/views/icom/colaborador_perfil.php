<?php
require_once "header.php";
?>
    <style>
        .jstree-defaulto {
            color: #31708f;
        }

        .jstree-warning {
            color: #f0ad4e;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-pencil-square-o"></i> Edição Rápida de Funcionário
                            - <?php echo $row->nome; ?>
                        </header>
                        <div class="panel-body">
                            <?php echo form_open('apontamento_colaboradores/save_perfil/' . $row->id, 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                            <div class="text-right">
                                <button class="btn btn-default" onclick="javascript:history.back()"><i
                                            class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                </button>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Logo</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">

                                        <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                            <?php
                                            $nome_foto = $row->foto;
                                            $logo = base_url('imagens/usuarios/' . $nome_foto);

                                            if (!$logo) {
                                                $nome_foto = 'Sem imagem';
                                                $logo = "https://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem";
                                            }
                                            ?>
                                            <img src="<?= $logo; ?>" alt="<?= $row->nome; ?>">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="width: auto; height: 150px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label">Área</label>
                                    <div class="col-sm-9 col-lg-10 controls">
                                        <?php echo form_dropdown('area', $area, $row->area, 'class="combobox form-control"'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label">Setor</label>
                                    <div class="col-sm-9 col-lg-10 controls">
                                        <?php echo form_dropdown('setor', $setor, $row->setor, 'class="combobox form-control"'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label">Contrato</label>
                                    <div class="col-sm-9 col-lg-10 controls">
                                        <?php echo form_dropdown('contrato', $contrato, $row->contrato, 'class="combobox form-control"'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label">Status</label>
                                    <div class="col-sm-4 col-lg-3 controls">
                                        <?php echo form_dropdown('status', $status, $row->status, 'class="form-control"'); ?>
                                    </div>
                                    <label class="col-sm-3 col-lg-2 control-label">Data de admissão</label>
                                    <div class="col-sm-3 col-lg-2 controls">
                                        <input type="text" name="data_admissao" id="data_admissao"
                                               placeholder="dd/mm/aaaa" value="<?php echo $row->data_admissao; ?>"
                                               class="form-control text-center"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label">Saldo dos apontamentos
                                        realizados</label>
                                    <div class="col-sm-4 col-lg-3 controls">
                                        <input name="saldo_apontamentos" id="saldo_apontamentos"
                                               class="hora form-control text-center"
                                               value="<?= $row->saldo_apontamentos ?>" placeholder="hhh:mm"
                                               autocomplete="off"
                                               type="text">
                                    </div>
                                </div>
                                <div id="box-progresso" style="display: none;">
                                    <div class="form-group">
                                        <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>
                                        <div class="col-sm-9 col-lg-10 controls">
                                            <div id="progresso" class="progress progress-mini pbar">
                                                <div class="progress-bar progress-bar-success ui-progressbar-value"
                                                     style="width:0%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                        <button type="submit" name="submit" class="btn btn-primary"><i
                                                    class="fa fa-save"></i> Salvar
                                        </button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
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
require_once "end_js.php";
?>
    <!-- Css -->
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-combobox/css/bootstrap-combobox.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.css"); ?>"/>

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Editar Funcionário - <?php echo $row->nome; ?>';
        });
    </script>

    <script src="<?php echo base_url("assets/js/bootstrap-combobox/js/bootstrap-combobox.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

    <script>
        $('.tags').tagsInput({width: 'auto', defaultText: 'Telefone', placeholderColor: '#999', delimiter: '/'});
        $('#data_admissao').mask('00/00/0000');
        $('.hora').mask('Z#00:00', {
            translation: {
                'Z': {pattern: /-/, optional: true},
                '#': {pattern: /\d/, optional: true}
            },
            reverse: true
        });

        $(document).ready(function () {
            $('.combobox').combobox();

            var hash_acesso = <?= $row->hash_acesso ?>;
            $.each(hash_acesso, function (i, item) {
                $.each(item, function (a, value) {
                    $('input[name="hash_acesso[' + i + '][]"][value="' + value + '"]').trigger('click');
                });
            });
        });

        $('#tree input[type="checkbox"]').change(function (e) {

            var checked = $(this).prop("checked"),
                container = $(this).parent(),
                siblings = container.siblings();

            container.find('input[type="checkbox"]').prop({
                indeterminate: false,
                checked: checked
            });

            function checkSiblings(el) {

                var parent = el.parent().parent(),
                    all = true;

                el.siblings().each(function () {
                    return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
                });

                if (all && checked) {

                    parent.children('input[type="checkbox"]').prop({
                        indeterminate: false,
                        checked: checked
                    });

                    checkSiblings(parent);

                } else if (all && !checked) {

                    parent.children('input[type="checkbox"]').prop("checked", checked);
                    parent.children('input[type="checkbox"]').prop("indeterminate", (parent.find('input[type="checkbox"]:checked').length > 0));
                    checkSiblings(parent);

                } else {

                    el.parents("li").children('input[type="checkbox"]').prop({
                        indeterminate: true,
                        checked: false
                    });

                }

            }

            checkSiblings(container);
        });
    </script>

<?php
require_once "end_html.php";
?>