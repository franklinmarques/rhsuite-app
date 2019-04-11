<?php
require_once "header.php";
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        <i class="glyphicon glyphicon-edit"></i>
                        Fale Conosco
                    </header>
                    <div class="panel-body">
                        <div id="alert"></div>
                        <?php echo form_open('contato/enviarMensagem', 'data-aviso="alert" class="form-horizontal ajax-upload" id="form-email"'); ?>
                        <div class="compose-mail">
                            <div class="form-group">
                                <label for="subject" class="">Assunto:</label>
                                <input type="text" tabindex="1" id="subject" name="assunto" class="form-control">
                            </div>

                            <div class="compose-editor">
                                <textarea class="form-control" rows="9" name="mensagem" id="tagLine"></textarea>
                            </div>
                            <div class="compose-btn ">
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
            return 'Ao sair dessa paǵina você irá descartar essa mensagem.';
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
                items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', /*'CreateDiv',*/ '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']
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