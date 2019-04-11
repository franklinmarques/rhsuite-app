<?php
require_once "header.php";
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

        tr.group, tr.group:hover {
            background-color: #ddd !important;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('pesquisa_modelos') ?>">Modelos de Pesquisa</a></li>
                        <li class="active">Instruções para pesquisa de <?= $nome ?></li>
                    </ol>
                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn btn-info disabled" data-toggle="modal" data-target="#modal-audio">
                                <i class="fa fa-microphone"></i> Gravar áudio
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-success" onclick="save()"><i class="fa fa-save"></i> Salvar</button>
                            <button class="btn btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div id="alert"></div>
                        <div class="col-xs-12">
                            <?php echo form_open('pesquisa_modelos/salvar_instrucoes', 'data-aviso="alert" class="form-horizontal ajax-upload" id="form"'); ?>
                            <input type="hidden" name="id" value="<?= $modelo ?>"/>
                            <div id="box-ckeditor">
                                <label>Digite ou copie no espaço de edição abaixo o texto que servirá de referência para
                                    este teste</label>
                                <textarea name="instrucoes" id="instrucoes" class="form-control"
                                          rows="1"><?= $instrucoes; ?></textarea>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- page end-->

            <div class="modal fade" id="modal-audio" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Gravação de áudio</h4>
                        </div>
                        <div class="modal-body">
                            <audio id="audio" controls style="width: 100%;"
                                   src="<?= (isset($row->audio) ? base_url("arquivos/media/") : ''); ?>"></audio>
                            <hr/>
                            <div id="time">
                                <span id="stopwatch">00:00:00</span>
                            </div>

                            <br/>

                            <div id="buttons">
                                <button id="record-audio" class="btn btn-primary">Gravar</button>
                                <button type="button" onclick="window.location.reload();" class="btn btn-info">
                                    Limpar
                                </button>
                                <button id="stop-recording-audio" class="btn btn-success" disabled>
                                    Salvar
                                </button>
                            </div>

                            <div id="container-audio" style="padding:1em 2em; font-weight: bolder;">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss='modal' id='fechaModal'>Fechar
                            </button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-video" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Gravação de vídeo</h4>
                        </div>
                        <div class="modal-body">
                            <video id="previewVideo" controls
                                   style="border: 1px solid #000; height: 240px; width: 100%;">
                                <source id="previewAudio"
                                        src="<?= (isset($row->video) ? base_url("arquivos/media/$row->video") : ''); ?>">
                            </video>
                            <hr/>
                            <button id="recordVideo" class="btn btn-primary">
                                Gravar
                            </button>
                            <button id="stopVideo" class="btn btn-success" disabled>
                                Salvar
                            </button>
                            <button type="button" onclick="window.location.reload();" class="btn btn-info">
                                Limpar
                            </button>

                            <div id="containerVideo" style="padding:1em 2em; font-weight: bolder;"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss='modal' id='fechaModal'>Fechar
                            </button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

        </section>
    </section>
    <!--main content end-->

<?php
require_once "end_js.php";
?>
    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Instruções para pesquisa de <?= $nome ?>';
        });
    </script>
    <script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/gravar/RecordRTC.js'); ?>"></script>

    <script>
        CKEDITOR.replace('instrucoes', {
            height: '600',
            filebrowserBrowseUrl: '<?= base_url('browser/browse.php'); ?>'
        });

        function save() {
            $("#instrucoes").val(CKEDITOR.instances.instrucoes.getData());
            $('#form').submit();
        }


    </script>
<?php
require_once "end_html.php";
?>