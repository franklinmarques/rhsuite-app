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
                        <i class="fa fa-file-text-alt"></i> <?php echo $row->titulo; ?>
                        <a class="btn btn-default btn-sm"
                           href="<?php echo base_url('home/paginascurso/' . $row->curso); ?>"
                           style="float: right; margin-top: -0.6%;">
                            <i class="fa fa-reply"></i> &nbsp;&nbsp; Voltar
                        </a>
                    </header>
                    <div class="panel-body">
                        <?php if ($row->modulo == "ckeditor") { ?>
                            <?php echo $row->conteudo; ?>
                        <?php } else if ($row->modulo == "arquivos-pdf") { ?>
                            <object data="<?php echo base_url('arquivos/pdf/' . $row->pdf); ?>" width="100%"
                                    height="500" type="application/pdf">
                                <embed src="<?php echo base_url('arquivos/pdf/' . $row->pdf); ?>"
                                       type="application/pdf"/>
                            </object>
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
                            echo form_open('atividades/avaliaAtividade/', 'data-aviso="alert" class="ajax-simple" id="respostaAtividades"');
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
                            $url_final = $row->youtube;

                            // Ajustar links
                            switch ($row->youtube) {
                                # Youtube novo
                                case strpos($row->youtube, 'youtube') > 0:
                                    $url_video = explode('?v=', $row->youtube);
                                    $url_final = "http://www.youtube.com/embed/" . $url_video[1];
                                    break;
                                # Vimeo
                                case strpos($row->youtube, 'vimeo') > 0:
                                    $url_video = explode('/', $row->youtube);
                                    $url_final = "http://player.vimeo.com/video/" . $url_video[3];
                                    break;
                            }
                            ?>
                            <table width="100%" border="0">
                                <tr>
                                    <td width="50%" align="center">
                                        <iframe allowfullscreen width="555" height="450" src="<?php echo $url_final; ?>"></iframe>
                                    </td>
                                    <td width="50%" valign="top" style="padding: 10px;">
                                        <?php echo $row->conteudo; ?>
                                    </td>
                                </tr>
                            </table>
                        <?php
                        } else if (in_array($row->modulo, array('mapas', 'simuladores', 'aula-digital', 'jogos', 'livros-digitais', 'infograficos', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia'))) {
                            $biblioteca = $this->db->query("SELECT * FROM biblioteca WHERE id = ?", array($row->biblioteca))->row(0);
                            ?>
                            <iframe width="100%" height="500" frameborder="0" src="<?php echo $biblioteca->link; ?>"
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
require_once "end_js.php";
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
        var url = '<?= base_url('atividades/avaliaAtividade/'); ?>';
        var modulo = '<?=$row->modulo;?>';

        //Verifica se é atividade ou quiz
        if (modulo == 'atividades') {
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'json',
                success: function (data) {
                    if (data == 'Atividade finalizada com sucesso!') {
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
require_once "end_html.php";
?>
