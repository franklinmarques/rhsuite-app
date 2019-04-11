<?php
require_once "header.php";
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <div class="row">
            <div class="col-sm-3" style="margin: 0; padding-right: 1%;">
                <section class="panel">
                    <div class="panel-body" style="padding-bottom: 0px;">
                        <a href="<?= site_url('email/novo/'); ?>" class="btn btn-compose">
                            Nova Mensagem
                        </a>
                        <ul class="nav nav-pills nav-stacked mail-nav">
                            <li><a href="<?php echo site_url('email/entrada'); ?>"> <i
                                        class="fa fa-inbox"></i> Caixa de Entrada<span
                                        class="label label-danger pull-right inbox-notification nao-lidas"></span></a>
                            </li>
                            <li><a href="<?php echo site_url('email/saida'); ?>"> <i
                                        class="fa fa-send"></i>
                                    Caixa de Saída</a></li>
                        </ul>
                    </div>
                </section>
            </div>
            <div class="col-sm-9">
                <section class="panel">
                    <header class="panel-heading wht-bg">
                        <h4 class="gen-case"> Nova Mensagem</h4>
                    </header>
                    <div class="panel-body">
                        <div id="alert"></div>
                        <?php echo form_open('email/enviar', 'data-aviso="alert" class="form-horizontal ajax-upload" id="form-email"'); ?>
                        <div class="compose-mail">
                            <div class="form-group">
                                <label for="to" class="">Para:</label>
                                <select id="to" name="destinatario" class="populate col-sm-7" tabindex="1"
                                        style="padding-left: 0 !important;">
                                    <option value="0">Selecione...</option>
                                    <?= $option; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="subject" class="">Assunto:</label>
                                <input type="text" tabindex="1" id="subject" name="titulo" class="form-control">
                            </div>

                            <div class="compose-editor">
                                <textarea class="form-control" rows="9" name="mensagem" id="tagLine"></textarea>

                                <?php
                                /*
                                  <div class="form-group file">
                                  <input type="file" name="anexo" class="form-control"/>

                                  <p class="help-block">Arquivo de até 2MB</p>
                                  </div>
                                 */
                                ?>
                            </div>
                            <div class="compose-btn">
                                <button class="btn btn-primary btn-sm Enviar" type="button"><i class="fa fa-check"></i>
                                    Enviar
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->

        <div id="script-emails" style="display: none !important;"></div>
    </section>
</section>
<!--main content end-->
<?php
require_once "end_js.php";
?>
<!-- Css -->
<!-- Multi-Select css styles-->
<link rel="stylesheet" href="<?php echo base_url('assets/js/jquery-multi-select/css/multi-select.css'); ?>"/>
<link rel="stylesheet" href="<?php echo base_url('assets/js/select2/select2.css'); ?>"/>

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Nova Mensagem';
    });
</script>

<!-- Select -->
<script src="<?php echo base_url('assets/js/jquery-multi-select/js/jquery.multi-select.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery-multi-select/js/jquery.quicksearch.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/select2/select2.js'); ?>"></script>

<!-- CKEditor -->
<script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>

<script>
    var status = 0;

    /* Multi-Select */
    $("#to").select2();
    $("#cc").select2();
    $("#bcc").select2();

    $('.Enviar').click(function () {
        status = 1;
        CKupdate();
        window.setTimeout(submitForm, 1000);
    });

    function submitForm() {
        $('#form-email').submit();
    }

    function CKupdate() {
        for (instance in CKEDITOR.instances)
            CKEDITOR.instances[instance].updateElement();
    }

    /* Confirma saída da página */
    $(window).bind('beforeunload', function () {
        if (status === 0) {
            return 'Ao sair dessa página você irá descartar essa mensagem.';
        }
    });

    CKEDITOR.replace('tagLine', {
        toolbar: [
            {name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']},
            {name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker', 'Scayt']},
            /*
             {
             name: 'forms',
             items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField']
             },
             */
            '/',
            {
                name: 'basicstyles',
                items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']
            },
            {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']
            },
            /*
             {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
             {
             name: 'insert',
             items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']
             },
             {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
             */
            {
                name: 'insert',
                items: ['HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak']
            },
            '/',
            {name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
            {name: 'tools', items: ['Maximize', 'ShowBlocks', '-', 'About']}
        ]
    }
    );
</script>
<?php
require_once "end_html.php";
?>