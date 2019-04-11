<?php
require_once APPPATH . "views/header.php";
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
                        <i class="fa fa-file-text-alt"></i> <?php echo $row->titulo; ?>
                        <a class="btn btn-default btn-sm"
                           href="<?php echo site_url('ead/paginas/index/' . $row->curso); ?>"
                           style="float: right; margin-top: -0.6%;">
                            <i class="fa fa-reply"></i> &nbsp;&nbsp; Voltar
                        </a>
                    </header>
                    <div class="panel-body" style="height:100%;">
                        <?php if ($row->modulo == "ckeditor") { ?>
                            <?php echo $row->conteudo; ?>
                        <?php } else if ($row->modulo == "arquivos-pdf") { ?>
                            <iframe src="https://docs.google.com/gview?embedded=true&url=<?php echo base_url('arquivos/pdf/' . $row->pdf); ?>" style="width:100%; height:600px; margin:0;" frameborder="0"></iframe>
                            <?php
                        } else if ($row->modulo == "quiz") {
                            $perguntas = $this->db->query("SELECT * FROM quizperguntas WHERE pagina = ? ORDER BY id ASC", array($row->id));
                            foreach ($perguntas->result() as $row_) {
                                $alternativas = $this->db->query("SELECT * FROM quizalternativas WHERE quiz = ? ORDER BY id ASC", array($row_->id));
                                ?>
                                <div class="well">
                                    <h4><?php echo $row_->pergunta; ?></h4>
                                    <ul class="list-unstyled">
                                        <?php
                                        if ($row_->tipo == 1) {
                                            foreach ($alternativas->result() as $row__) {
                                                ?>
                                                <li>
                                                    <input type="radio" name="pergunta[<?php echo $row_->id; ?>]"
                                                           class="alternativa" value="<?php echo $row__->correta; ?>"
                                                           data-pergunta="<?php echo $row_->id; ?>"/>
                                                           <?php echo $row__->alternativa; ?>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                        <div id="alternativa-correta-<?php echo $row_->id; ?>" class="alert alert-success"
                                             style="display: none;">
                                                 <?php echo $row_->respostacorreta; ?>
                                        </div>
                                        <div id="alternativa-errada-<?php echo $row_->id; ?>" class="alert alert-danger"
                                             style="display: none;">
                                                 <?php echo $row_->respostaerrada; ?>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <a href="#" class="btn btn-success btn-xs dissertativa"
                                               data-pergunta="<?php echo $row_->id; ?>"><i
                                                    class="glyphicon glyphfa fa-eye-open"></i> Visualizar Resposta</a>
                                        </div>
                                        <div id="alternativa-dissertativa-<?php echo $row_->id; ?>"
                                             class="alert alert-success" style="display: none;">
                                                 <?php echo $row_->respostacorreta; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php
                            }
                            ?>
                            app/application/views/
                            <?php
                        } else if ($row->modulo == "atividades") {
                            echo form_open('ead/treinamento/avaliar_atividade/', 'data-aviso="alert" class="ajax-simple" id="respostaAtividades"');
                            $perguntas = $this->db->query("SELECT * FROM atividadesperguntas WHERE pagina = ? ORDER BY id ASC", array($row->id));
                            foreach ($perguntas->result() as $row_) {
                                $alternativas = $this->db->query("SELECT * FROM atividadesalternativas WHERE quiz = ? ORDER BY id ASC", array($row_->id));
                                ?>
                                <div class="well">
                                    <h4><?php echo $row_->pergunta; ?></h4>
                                    <ul class="list-unstyled">
                                        <?php
                                        if ($row_->tipo == 1) {
                                            foreach ($alternativas->result() as $row__) {
                                                ?>
                                                <li>
                                                    <input type="radio" name="pergunta[<?php echo $row_->id; ?>]"
                                                           class="alternativa" value="<?php echo $row__->correta; ?>"
                                                           data-pergunta="<?php echo $row_->id; ?>"/>
                                                           <?php echo $row__->alternativa; ?>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                        <div id="alternativa-correta-<?php echo $row_->id; ?>" class="alert alert-success"
                                             style="display: none;">
                                                 <?php echo $row_->respostacorreta; ?>
                                        </div>
                                        <div id="alternativa-errada-<?php echo $row_->id; ?>" class="alert alert-danger"
                                             style="display: none;">
                                                 <?php echo $row_->respostaerrada; ?>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <a href="#" class="btn btn-success btn-xs dissertativa"
                                               data-pergunta="<?php echo $row_->id; ?>"><i
                                                    class="glyphicon glyphfa fa-eye-open"></i> Visualizar Resposta</a>
                                        </div>
                                        <div id="alternativa-dissertativa-<?php echo $row_->id; ?>"
                                             class="alert alert-success" style="display: none;">
                                                 <?php echo $row_->respostacorreta; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php
                            }
                            ?>
                            <button class="btn btn-success" id="enviaResposta" type="button">Enviar Respostas</button>
                            <input type="hidden" value="<?= $row->curso; ?>" name="curso">
                            <input type="hidden" value="<?= $row->id; ?>" name="pagina">
                            </form>
                            <?php
                        } else if ($row->modulo == "video-youtube") {
                            if (!empty($row->youtube)) {
                                $url_final = $row->youtube;

                                // Ajustar links
                                switch ($row->youtube) {
                                    # Youtube novo
                                    case strpos($row->youtube, 'youtube') > 0:
                                        $url_video = explode('?v=', $row->youtube);
                                        $url_final = "https://www.youtube.com/embed/" . $url_video[1] . "?enablejsapi=1";
                                        break;
                                    # Vimeo
                                    case strpos($row->youtube, 'vimeo') > 0:
                                        $url_video = explode('/', $row->youtube);
                                        $url_final = "https://player.vimeo.com/video/" . $url_video[3];
                                        break;
                                }
                                ?>
                                <div class="col-md-12" style="margin: 0; padding: 0;">
                                    <div class="col-md-8" style="margin: 0; padding: 0;">
                                        <iframe type="text/html" allowfullscreen style="width: 100%; height: 450px;" src="<?php echo $url_final; ?>" frameborder="0"></iframe>
                                    </div>
                                    <div class="col-md-4">
                                        <?php echo $row->conteudo; ?>
                                    </div>
                                </div>
                                <?php
                            } else {
                                $url_arquivo = base_url('arquivos/videos/' . $row->arquivoVideo);
                                ?>
                                <div class="col-md-12">
                                    <div class="col-md-8">
                                        <source src="<?php echo $url_arquivo; ?>" type="video/mp4">
                                    </div>
                                    <div class="col-md-4">
                                        <?php echo $row->conteudo; ?>
                                    </div>
                                </div>
                                <?php
                            }
                        } else if (in_array($row->modulo, array('mapas', 'simuladores', 'aula-digital', 'jogos', 'livros-digitais', 'infograficos', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia'))) {
                            $biblioteca = $this->db->query("SELECT * FROM biblioteca WHERE id = ?", array($row->biblioteca))->row(0);
                            ?>
                            <iframe style="width: 100%; height: 450px;" frameborder="0" src="<?php echo $biblioteca->link; ?>"
                                    onload="javascript:resizeIframe(this);"></iframe>
<?php } ?>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<?php
require_once APPPATH . "views/end_js.php";
?>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Preview - <?php echo $row->titulo; ?>';
    });
</script>
<script>
    function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }
    $(function () {
        /*
         $('.alternativa').click(function () {
         if ($(this).val() === '1') {
         $('#alternativa-correta-' + $(this).data('pergunta')).fadeIn('slow');
         $('#alternativa-errada-' + $(this).data('pergunta')).fadeOut('slow');
         } else {
         $('#alternativa-errada-' + $(this).data('pergunta')).fadeIn('slow');
         $('#alternativa-correta-' + $(this).data('pergunta')).fadeOut('slow');
         }
         
         var campo = document.getElementsByName($(this).attr("name"));
         $(campo).attr('disabled', 'disabled');
         });
         */

        $('.dissertativa').click(function () {
            $('#alternativa-dissertativa-' + $(this).data('pergunta')).fadeIn('slow');

            /*
             var campo = document.getElementsByName($(this).attr("name"));
             $(campo).attr('disabled', 'disabled');
             */
        });
    });

    $('#enviaResposta').click(function () {
        var data = $('#respostaAtividades').serialize();
        var url = '<?= site_url('ead/treinamento/avaliar_atividade/'); ?>';
        var modulo = '<?= $row->modulo; ?>';

        //Verifica se é atividade ou quiz
        if (modulo === 'atividades') {
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'json',
                success: function (data) {
                    if (data === 'Atividade finalizada com sucesso!') {
                        $("#alert").addClass("alert alert-success");
                    } else {
                        $("#alert").addClass("alert alert-danger");
                    }
                    $('#alert').html(data);
                }
            });
        }

        //Executa Loop entre todas as Radio buttons
        $("input:radio").each(function () {
            if ($(this).is(':checked')) {
                if ($(this).val() === '1') {
                    $('#alternativa-correta-' + $(this).data('pergunta')).fadeIn('slow');
                    $('#alternativa-errada-' + $(this).data('pergunta')).fadeOut('slow');
                } else {
                    $('#alternativa-errada-' + $(this).data('pergunta')).fadeIn('slow');
                    $('#alternativa-correta-' + $(this).data('pergunta')).fadeOut('slow');
                }
            }
        });
    });

<?php
//Audio
if (!empty($row->audio)):
    ?>
        $('.nav .top-menu').append('' +
                '<li id="header_notification_bar" class="dropdown"> ' +
                '<a data-toggle="dropdown" class="dropdown-toggle" href="#">' +
                '<i class="fa fa-play"></i>' +
                '<span class="badge bg-warning">1</span>' +
                '</a>' +
                '<ul class="dropdown-menu extended notification">' +
                '<li>' +
                '<p>Player</p>' +
                '</li>' +
                '<li>' +
                '<div class="alert alert-info clearfix">' +
                '<audio id="player" controls="" src="<?= base_url('arquivos/media/' . $row->audio); ?>"></audio>' +
                '</div>' +
                '</li>' +
                '</ul>' +
                '</li>');
    <?php
endif;
?>
</script>
</script>
<?php
require_once APPPATH . "views/end_html.php";
?>
