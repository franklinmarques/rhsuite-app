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
                        <i class="fa fa-pencil-square-o"></i> <strong>Editar Página do Treinamento - <?php echo $curso->curso; ?>
                            - <?php echo $row->titulo; ?></strong></h3>
                        <a class="btn btn-default btn-sm"
                           href="<?php echo base_url('home/paginascurso/' . $row->curso); ?>" style="float: right; margin-top: -0.5%;">
                            <i class="fa fa-reply"></i> &nbsp;&nbsp; Voltar
                        </a>
                    </header>
                    <div class="panel-body">
                        <?php echo form_open('home/editarpaginacurso_json/' . $row->id, 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                        <table class="table table-responsive" style="border-bottom: 1px solid #DDD;">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="col-sm-3 col-lg-3 control-label">Recursos de Edição</label>

                                                <div class="col-sm-9 col-lg-9 controls">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="modulo"
                                                               value="ckeditor" <?php if ($row->modulo == "ckeditor") echo "checked=\"checked\""; ?> />
                                                        <i class="fa fa-file-zip-o"></i> Objetos (Texto, Figura, Tabela, Flash, Links)
                                                    </label>
                                                    <br/>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="modulo"
                                                               value="arquivos-pdf" <?php if ($row->modulo == "arquivos-pdf") echo "checked=\"checked\""; ?> />
                                                        <i class="fa fa-file-word-o"></i> Arquivos (Word, PorwerPoint, Bloco de Notas, PDF)
                                                    </label>
                                                    <br/>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="modulo"
                                                               value="quiz" <?php if ($row->modulo == "quiz") echo "checked=\"checked\""; ?> />
                                                        <i class="fa fa-question-circle"></i> Quick Quiz
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="modulo"
                                                               value="atividades" <?php if ($row->modulo == "atividades") echo "checked=\"checked\""; ?> />
                                                        <i class="fa fa-pencil"></i> Quiz Atividades
                                                    </label>
                                                    <br/>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="modulo"
                                                               value="video-youtube" <?php if ($row->modulo == "video-youtube") echo "checked=\"checked\""; ?> />
                                                        <i class="fa fa-file-video-o"></i> Vídeos e Links (Youtube, Vimeo, SlideShare, Dailymotion, URLs)
                                                    </label>
                                                    <!--
                                                                    <label class="radio-inline">
                                                                        <input type="radio" name="modulo" value="links-externos"
                                                                               data-tipo="7" <?php if ($row->modulo == "links-externos") echo "checked=\"checked\""; ?> /> Links
                                                                        Links (Youtube, Vimeo, SlideShare, Outros)
                                                                    </label>
                                                    -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <a class="btn btn-sm btn-info" style="float: right;" data-toggle="modal"
                                               data-target="#modal-audio">
                                                Gravar Áudio &nbsp;
                                                <i class="fa fa-microphone"></i>
                                            </a>

                                            <!-- Campo com o nome do arquivo de áudio -->
                                            <input id="arquivo_audio" name="arquivo_audio" type="hidden"
                                                   value="<?php if (!empty($row->audio)) echo $row->audio; ?>" readonly>

                                            <!--
                                            <a class="btn btn-sm btn-default" style="float: right; margin-right: 3%;" data-toggle="modal"
                                               data-target="#modal-video">
                                                Gravar Vídeo &nbsp;
                                                <i class="fa fa-camera"></i>
                                            </a>
                        
                                            <!-- Campo com o nome do arquivo de vídeo -->
                                            <input id="arquivo_video" name="arquivo_video" type="hidden"
                                                   value="<?php if (!empty($row->video)) echo $row->video; ?>" readonly>
                                        </div>

                                        <!--
                                        <div class="col-md-8">
                                            <table class="table table-bordered table-responsive">
                                                <tbody>
                                                <tr>
                                                    <th>
                                                        <label
                                                            style="display:inline-block;padding-top: 9px;margin-top: 0;margin-bottom: 0;">
                                                            <strong>Tipo Unidade :</strong>
                                                        </label>
                                                    </th>
                                                    <td style="padding-bottom: 15px">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="tipo_unidade">Em tempo de treinamento
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="tipo_unidade">
                                                            Programada para: &nbsp;
                                                            <input type="number" maxlength="3"
                                                                   style="width: 50px;border:1px solid #e5e5e5;">
                                                            &nbsp; dias apos o termino do treinamento
                                                        </label>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Título</label>

                            <div class="col-sm-6 col-lg-7 controls">
                                <input type="text" name="titulo" placeholder="Título" value="<?php echo $row->titulo; ?>"
                                       class="form-control"/>
                            </div>
                            <div class="col-sm-3 col-lg-3 controls">
                                <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-ok"></i> Salvar</button>
                            </div>
                        </div>
                        <div id="box-ckeditor" <?php if ($row->modulo != "ckeditor") echo "style=\"display: none;\""; ?>>
                            <div class="form-group">
                                <div class="col-sm-12 col-lg-12 controls">
                                    <textarea name="conteudo" id="conteudo" class="form-control"
                                              rows="3"><?php if ($row->modulo == "ckeditor") echo $row->conteudo; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div id="box-arquivos-pdf" <?php if ($row->modulo != "arquivos-pdf") echo "style=\"display: none;\""; ?>>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Arquivo</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="file" name="arquivo" class="form-control"/>
                                    <span
                                        class="help-inline"><?php if (!empty($row->pdf)) echo "<a href='" . base_url('arquivos/pdf/' . $row->pdf) . "' target='_blank'>{$row->pdf}</a>"; ?></span>
                                </div>
                            </div>
                        </div>
                        <div id="box-quiz" <?php if ($row->modulo != "quiz") echo "style=\"display: none;\""; ?>>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <a href="#" id="adicionar-pergunta" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Adicionar
                                        Pergunta</a>
                                </div>
                            </div>
                            <div id="perguntas">
                                <?php
                                foreach ($quizperguntas->result() as $pergunta) {
                                    $quizalternativas = $this->db->query("SELECT * FROM quizalternativas WHERE quiz = ? ORDER BY id ASC", array($pergunta->id));
                                    ?>
                                    <div id="box-<?php echo $pergunta->id; ?>"
                                         style="margin: 10px 0; padding: 10px; background-color: #b6d1f2;">
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">
                                                Pergunta
                                                <i class="glyphicon glyphicon-remove-circle remove-pergunta-<?php echo $pergunta->id; ?>"
                                                   title="Remover" style="margin-left:2px; color:red;"></i>
                                            </label>

                                            <div class="col-sm-9 col-lg-10 controls">
                                                <textarea name="perguntas[<?php echo $pergunta->id; ?>]" class="form-control"
                                                          rows="3"><?php echo $pergunta->pergunta; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Questão</label>

                                            <div class="col-sm-9 col-lg-10 controls">
                                                <label class="radio-inline">
                                                    <input type="radio" name="tipoquestao[<?php echo $pergunta->id; ?>]"
                                                           data-pergunta="<?php echo $pergunta->id; ?>"
                                                           value="1" <?php if ($pergunta->tipo == 1) echo "checked=\"checked\""; ?> />
                                                    Alternativa
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="tipoquestao[<?php echo $pergunta->id; ?>]"
                                                           data-pergunta="<?php echo $pergunta->id; ?>"
                                                           value="2" <?php if ($pergunta->tipo == 2) echo "checked=\"checked\""; ?> />
                                                    Dissertativa
                                                </label>
                                            </div>
                                        </div>
                                        <div
                                            id="box-alternativa-<?php echo $pergunta->id; ?>" <?php if ($pergunta->tipo != 1) echo "style=\"display:none;\""; ?> >
                                            <div class="form-group">
                                                <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>

                                                <div class="col-sm-9 col-lg-10 controls">
                                                    <a href="#" id="adicionar-alternativa-<?php echo $pergunta->id; ?>"
                                                       class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Adicionar Alternativa</a>
                                                </div>
                                            </div>
                                            <div id="alternativas-<?php echo $pergunta->id; ?>">
                                                <?php
                                                foreach ($quizalternativas->result() as $alternativa) {
                                                    ?>
                                                    <div class="form-group">
                                                        <label class="col-sm-5 col-lg-4 control-label">
                                                            Alternativa
                                                            <i class="glyphicon glyphicon-remove-circle remove-alternativa-<?php echo $alternativa->id; ?>"
                                                               title="Remover" style="margin-left:2px; color:red;"></i>
                                                        </label>

                                                        <div class="col-sm-7 col-lg-8 controls">
                                                            <input type="text"
                                                                   name="alternativas[<?php echo $pergunta->id; ?>][<?php echo $alternativa->id; ?>]"
                                                                   placeholder="Alternativa"
                                                                   value="<?php echo $alternativa->alternativa; ?>"
                                                                   class="form-control"/>
                                                            <span class="help-inline" style="color: #000;"><input type="checkbox"
                                                                                                                  name="corretas[<?php echo $alternativa->id; ?>]" <?php if ($alternativa->correta) echo "checked=\"checked\""; ?>
                                                                                                                  value="1"/> Alternativa correta</span>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">
                                                Feedback para resposta correta
                                            </label>

                                            <div class="col-sm-9 col-lg-10 controls">
                                                <textarea name="respostacorreta[<?php echo $pergunta->id; ?>]" class="form-control"
                                                          rows="3"><?php echo $pergunta->respostacorreta; ?></textarea>
                                            </div>
                                        </div>
                                        <div
                                            id="box-resposta-errada-<?php echo $pergunta->id; ?>" <?php if ($pergunta->tipo != 1) echo "style=\"display:none;\""; ?>>
                                            <div class="form-group">
                                                <label class="col-sm-3 col-lg-2 control-label">
                                                    Feedback para resposta errada
                                                </label>

                                                <div class="col-sm-9 col-lg-10 controls">
                                                    <textarea name="respostaerrada[<?php echo $pergunta->id; ?>]" class="form-control"
                                                              rows="3"><?php echo $pergunta->respostaerrada; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div id="box-atividades" <?php if ($row->modulo != "atividades") echo "style=\"display: none;\""; ?>>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <a href="#" id="adicionar-pergunta-atividade" class="btn btn-success btn-xs"><i class="fa fa-plus"></i>
                                        Adicionar Pergunta</a>
                                </div>
                            </div>
                            <div id="perguntas-atividades">
                                <?php
                                foreach ($atividadesperguntas->result() as $pergunta) {
                                    $quizalternativas = $this->db->query("SELECT * FROM atividadesalternativas WHERE quiz = ? ORDER BY id ASC", array($pergunta->id));
                                    ?>
                                    <div id="box-<?php echo $pergunta->id; ?>"
                                         style="margin: 10px 0; padding: 10px; background-color: #b6d1f2;">
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">
                                                Pergunta
                                                <i class="glyphicon glyphicon-remove-circle remove-pergunta-<?php echo $pergunta->id; ?>"
                                                   title="Remover" style="margin-left:2px; color:red;"></i>
                                            </label>

                                            <div class="col-sm-9 col-lg-10 controls">
                                                <textarea name="perguntas[<?php echo $pergunta->id; ?>]" class="form-control"
                                                          rows="3"><?php echo $pergunta->pergunta; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">Questão</label>

                                            <div class="col-sm-9 col-lg-10 controls">
                                                <label class="radio-inline">
                                                    <input type="radio" name="tipoquestao[<?php echo $pergunta->id; ?>]"
                                                           data-pergunta="<?php echo $pergunta->id; ?>"
                                                           value="1" <?php if ($pergunta->tipo == 1) echo "checked=\"checked\""; ?> />
                                                    Alternativa
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="tipoquestao[<?php echo $pergunta->id; ?>]"
                                                           data-pergunta="<?php echo $pergunta->id; ?>"
                                                           value="2" <?php if ($pergunta->tipo == 2) echo "checked=\"checked\""; ?> />
                                                    Dissertativa
                                                </label>
                                            </div>
                                        </div>
                                        <div
                                            id="box-alternativa-<?php echo $pergunta->id; ?>" <?php if ($pergunta->tipo != 1) echo "style=\"display:none;\""; ?> >
                                            <div class="form-group">
                                                <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>

                                                <div class="col-sm-9 col-lg-10 controls">
                                                    <a href="#" id="adicionar-alternativa-<?php echo $pergunta->id; ?>"
                                                       class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Adicionar Alternativa</a>
                                                </div>
                                            </div>
                                            <div id="alternativas-<?php echo $pergunta->id; ?>">
                                                <?php
                                                foreach ($quizalternativas->result() as $alternativa) {
                                                    ?>
                                                    <div class="form-group">
                                                        <label class="col-sm-5 col-lg-4 control-label">
                                                            Alternativa
                                                            <i class="glyphicon glyphicon-remove-circle remove-alternativa-<?php echo $alternativa->id; ?>"
                                                               title="Remover" style="margin-left:2px; color:red;"></i>
                                                        </label>

                                                        <div class="col-sm-7 col-lg-8 controls">
                                                            <input type="text"
                                                                   name="alternativas[<?php echo $pergunta->id; ?>][<?php echo $alternativa->id; ?>]"
                                                                   placeholder="Alternativa"
                                                                   value="<?php echo $alternativa->alternativa; ?>"
                                                                   class="form-control"/>
                                                            <span class="help-inline" style="color: #000;"><input type="checkbox"
                                                                                                                  name="corretas[<?php echo $alternativa->id; ?>]" <?php if ($alternativa->correta) echo "checked=\"checked\""; ?>
                                                                                                                  value="1"/> Alternativa correta</span>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 control-label">
                                                Feedback para resposta correta
                                            </label>

                                            <div class="col-sm-9 col-lg-10 controls">
                                                <textarea name="respostacorreta[<?php echo $pergunta->id; ?>]" class="form-control"
                                                          rows="3"><?php echo $pergunta->respostacorreta; ?></textarea>
                                            </div>
                                        </div>
                                        <div
                                            id="box-resposta-errada-<?php echo $pergunta->id; ?>" <?php if ($pergunta->tipo != 1) echo "style=\"display:none;\""; ?>>
                                            <div class="form-group">
                                                <label class="col-sm-3 col-lg-2 control-label">
                                                    Feedback para resposta errada
                                                </label>

                                                <div class="col-sm-9 col-lg-10 controls">
                                                    <textarea name="respostaerrada[<?php echo $pergunta->id; ?>]" class="form-control"
                                                              rows="3"><?php echo $pergunta->respostaerrada; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div id="box-video-youtube" <?php if ($row->modulo != "video-youtube") echo "style=\"display: none;\""; ?>>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Endereço da URL</label>

                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="text" name="videoyoutube" placeholder="Endereço da URL"
                                           value="<?php if (!empty($row->youtube)) echo $row->youtube; ?>"
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 col-lg-12 controls">
                                    <textarea name="descricaoyoutube" id="descricaoyoutube" class="form-control"
                                              rows="3"><?php if ($row->modulo == "video-youtube") echo $row->conteudo; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div
                            id="box-biblioteca" <?php if (!in_array($row->modulo, array('mapas', 'simuladores', 'aula-digital', 'jogos', 'livros-digitais', 'infograficos', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia'))) echo "style=\"display: none;\""; ?>>
                            <div class="form-group">
                                <label class="col-sm-2 col-lg-2 control-label">Categoria</label>

                                <div class="col-sm-2 col-lg-2 controls">
                                    <select name="categoriabiblioteca" class="form-control input-sm">
                                        <option value="">Todas</option>
                                        <?php foreach ($categoria->result() as $row_) { ?>
                                            <option
                                                value="<?php echo $row_->id; ?>" <?php if ($row->categoriabiblioteca == $row_->id) echo "selected=\"selected\""; ?>><?php echo $row_->curso; ?></option>
                                            <?php } ?>
                                    </select>
                                </div>
                                <label class="col-sm-1 col-lg-1 control-label">Título</label>

                                <div class="col-sm-2 col-lg-2 controls">
                                    <input type="text" name="titulobiblioteca" placeholder="Título"
                                           value="<?php echo $row->titulobiblioteca; ?>" class="form-control input-sm"/>
                                </div>
                                <label class="col-sm-1 col-lg-1 control-label">Tags</label>

                                <div class="col-sm-2 col-lg-2 controls">
                                    <input type="text" name="tagsbiblioteca" placeholder="Tags" value="<?php echo $row->tagsbiblioteca; ?>"
                                           class="form-control input-sm"/>
                                </div>
                                <div class="col-sm-2 col-lg-2">
                                    <a href="#" id="busca-biblioteca" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></a>
                                </div>
                            </div>
                            <div id="html-biblioteca"></div>
                        </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>

        <div class="modal" id="modal-audio">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Gravação de Áudio</h4>
                    </div>
                    <div class="modal-body">
                        <audio id="audio" controls style="width: 100%;"
                               src="<?php if (!empty($row->audio)) echo $row->audio; ?>"></audio>
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
                        <button type="button" class="btn btn-danger" data-dismiss='modal' id='fechaModal'>Fechar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <div class="modal" id="modal-video">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Gravação de Vídeo</h4>
                    </div>
                    <div class="modal-body">
                        <video id="previewVideo" controls
                               style="border: 1px solid #000; height: 240px; width: 100%;">
                            <source id="previewAudio" src="<?php if (!empty($row->video)) echo $row->video; ?>">
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
                        <button type="button" class="btn btn-danger" data-dismiss='modal' id='fechaModal'>Fechar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

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
        document.title = 'CORPORATE RH - LMS - Editar Página do Treinamento - <?php echo $curso->curso; ?> - <?php echo $row->titulo; ?>';
    });
</script>

<script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/gravar/RecordRTC.js'); ?>"></script>
<script src="<?php echo base_url("assets/js/ajax.form.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/ajax.upload.js"); ?>"></script>

<script src="<?php echo base_url("assets/js/timer/jquery.timer.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/timer/timer.js"); ?>"></script>

<script>
    (function () {
        var params = {},
                r = /([^&=]+)=?([^&]*)/g;

        function d(s) {
            return decodeURIComponent(s.replace(/\+/g, ' '));
        }

        var match, search = window.location.search;
        while (match = r.exec(search.substring(1)))
            params[d(match[1])] = d(match[2]);

        window.params = params;
    })();
</script>
<script>
    /********************** Áudio **********************/
//Define Navegador
    var isFirefox = !!navigator.mozGetUserMedia;

    //Div armazena o áudio
    var container = document.getElementById('container-audio');

    //Variáveis de Áudio
    var strong = null;
    var progress = null;
    var audioStream;
    var recorder;
    var recordAudio = document.getElementById('record-audio');
    var stopRecordingAudio = document.getElementById('stop-recording-audio');
    var audio = document.querySelector('audio');
    var audioConstraints = {
        audio: true,
        video: false
    };

    //Variáveis de Vídeo
    var recordVD = document.getElementById('recordVideo');
    var stopVD = document.getElementById('stopVideo');
    var recordVideo = document.getElementById('record-video');
    var previewVD = document.getElementById('previewVideo');
    var containerVD = document.getElementById('containerVideo');

    //Função do botão gravar
    recordAudio.onclick = function () {
        if (this.innerHTML === 'Gravar') {
            if (!audioStream)
                navigator.getUserMedia(audioConstraints, function (stream) {
                    if (window.IsChrome)
                        stream = new window.MediaStream(stream.getAudioTracks());
                    audioStream = stream;

                    // "audio" is a default type
                    recorder = window.RecordRTC(stream, {
                        type: 'audio',
                        bufferSize: typeof params.bufferSize == 'undefined' ? 16384 : params.bufferSize,
                        sampleRate: typeof params.sampleRate == 'undefined' ? 44100 : params.sampleRate,
                        leftChannel: params.leftChannel || false,
                        disableLogs: params.disableLogs || false
                    });
                    recorder.startRecording();
                    Example1.Timer.toggle();
                }, function () {
                });
            else {
                audio.src = URL.createObjectURL(audioStream);
                audio.muted = true;
                audio.play();
                if (recorder)
                    recorder.startRecording();
                Example1.Timer.toggle();
            }

            window.isAudio = true;
            stopRecordingAudio.disabled = false;

            this.innerHTML = 'Pausar';
        } else {

            if (!recorder)
                return;

            if (this.innerHTML === 'Pausar') {
                this.innerHTML = 'Retomar';
                recorder.pauseRecording();
                Example1.Timer.toggle();
                return;
            }

            this.innerHTML = 'Pausar';
            recorder.resumeRecording();
            Example1.Timer.toggle();
        }
    };

    //Função do botão stop
    stopRecordingAudio.onclick = function () {
        this.disabled = true;
        recordAudio.disabled = false;
        audio.src = '';

        fileName = Math.round(Math.random() * 99999999) + 99999999;

        if (!isFirefox) {
            recorder.stopRecording(function () {
                PostBlob(recorder.getBlob(), 'audio', fileName + '.wav');
            });
        } else {
            recorder.stopRecording(function () {
                PostBlob(recorder.getBlob(), 'audio', fileName + '.wav');
            });
        }

        //Zerar timer e mudar nome do botão
        Example1.Timer.stop();
        recordAudio.innerHTML = 'Gravar';
        Example1.resetStopwatch();
    };

    //Função para salvar o áudio
    function PostBlob(blob, fileType, fileName) {

        // FormData
        var formData = new FormData();
        formData.append(fileType + '-filename', fileName);
        formData.append(fileType + '-blob', blob);

        // progress-bar
        //var hr = document.createElement('hr');
        //container.appendChild(hr);
        if (strong == null) {
            strong = document.createElement('strong');
            strong.id = 'percentage';
        }
        strong.innerHTML = fileType + ' upload progresso: ';

        container.appendChild(strong);
        if (progress == null) {
            progress = document.createElement('progress');
        }

        container.appendChild(progress);

        // POST the Blob using XHR2
        xhr('<?= base_url('/gravacao/save_audio') ?>', formData, progress, percentage, function (fileURL) {
            //container.appendChild(document.createElement('hr'));
            var mediaElement = document.createElement(fileType);

            var source = document.createElement('source');
            var href = location.href.substr(0, location.href.lastIndexOf('/') + 1);
            audio.src = fileURL;
            audio.autoplay = false;

            source.src = fileURL;

            //Verifica se não retornou erro
            if (!fileURL.match('Error')) {
                $("#arquivo_audio").val(fileName);
            }

            if (fileType == 'video')
                source.type = 'video/webm; codecs="vp8, vorbis"';
            if (fileType == 'audio')
                source.type = !!navigator.mozGetUserMedia ? 'audio/ogg' : 'audio/wav';

            /*
             mediaElement.appendChild(source);
                 
             mediaElement.controls = true;
             container.appendChild(mediaElement);
             mediaElement.autoplay = false;
             mediaElement.play();
                 
             progress.parentNode.removeChild(progress);
             strong.parentNode.removeChild(strong);
             hr.parentNode.removeChild(hr);
             */
        });
    }

    //Função de Upload
    function xhr(url, data, progress, percentage, callback) {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                callback(request.responseText);
            }
        };

        if (url.indexOf('RecordRTC-to-PHP/delete.php') == -1) {
            request.upload.onloadstart = function () {
                percentage.innerHTML = 'Upload: Iniciado...';
            };

            request.upload.onprogress = function (event) {
                progress.max = event.total;
                progress.value = event.loaded;
                percentage.innerHTML = 'Upload: Progresso ' + Math.round(event.loaded / event.total * 100) + "%";
            };

            request.upload.onload = function () {
                percentage.innerHTML = 'Arquivo Salvo!  ';
            };
        }

        request.open('POST', url);
        request.send(data);
    }

    /********************** Vídeo **********************/
    function PostBlobVideo(audioBlob, videoBlob, fileName) {
        var formData = new FormData();
        formData.append('filename', fileName);
        formData.append('audio-blob', audioBlob);
        formData.append('video-blob', videoBlob);
        xhrVD('<?= base_url('/gravacao/save_video') ?>', formData, function (ffmpeg_output) {
            containerVD.innerHTML = ffmpeg_output.replace(/\\n/g, '<br />');
            previewVD.src = '<?= base_url('/arquivos/media/'); ?>/' + fileName + '-merged.webm';

            //Salvar arquivo no input
            $("#arquivo_video").val('<?= base_url('/arquivos/media/'); ?>/' + fileName + '-merged.webm');

            previewVD.muted = false;
        });
    }

    var recordAudio, recordVideo;
    recordVD.onclick = function () {
        recordVD.disabled = true;
        !window.stream && navigator.getUserMedia({
            audio: true,
            video: true
        }, function (stream) {
            window.stream = stream;
            onstream();
        }, function (error) {
            alert(JSON.stringify(error, null, '\t'));
        });

        window.stream && onstream();

        function onstream() {
            previewVD.src = window.URL.createObjectURL(stream);
            previewVD.play();
            previewVD.muted = true;

            recordAudio = RecordRTC(stream, {
                // bufferSize: 16384,
                onAudioProcessStarted: function () {
                    if (!isFirefox) {
                        recordVideo.startRecording();
                    }
                }
            });

            recordVideo = RecordRTC(stream, {
                type: 'video'
            });

            recordAudio.startRecording();

            stopVD.disabled = false;
        }
    };

    var fileName;
    stopVD.onclick = function () {
        containerVD.innerHTML = 'Upload: Enviando arquivos...';

        recordVD.disabled = false;
        stopVD.disabled = true;

        previewVD.src = '';
        previewVD.poster = 'ajax-loader.gif';

        fileName = Math.round(Math.random() * 99999999) + 99999999;

        if (!isFirefox) {
            recordAudio.stopRecording(function () {
                containerVD.innerHTML = 'Upload: Áudio enviado! Enviando vídeo...';
                recordVideo.stopRecording(function () {
                    containerVD.innerHTML = 'Upload: Gravando e convertendo...';
                    PostBlobVideo(recordAudio.getBlob(), recordVideo.getBlob(), fileName);
                });
            });
        }
    };

    function xhrVD(url, data, callback) {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                callback(request.responseText);
            }
        };
        request.open('POST', url);
        request.send(data);
    }
</script>

<script>
    var guid = (function () {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
        }

        return function () {
            return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
        };
    })();

    function CK_jQ() {
        $("#conteudo").val(CKEDITOR.instances.conteudo.getData());
        $("#descricaoyoutube").val(CKEDITOR.instances.descricaoyoutube.getData());
    }

    window.parent.CKEDITOR.tools.callFunction(callback, "/browser/upload.jpg", function () {
        // Get the reference to a dialog window.
        var element,
                dialog = this.getDialog();
        // Check if this is the Image dialog window.
        if (dialog.getName() == 'imageresponsive') {
            // Get the reference to a text field that holds the "srcset" attribute.
            element = dialog.getContentElement('info', 'srcset');
            // Assign the new value.
            if (element)
                element.setValue('upload-small.jpg 100w, upload-medium.jpg 500w, upload-big.jpg 1000w');
        }
    });

    function getBiblioteca(html, url, categoria, titulo, tag) {
        $.ajax({
            url: url,
            type: 'POST',
            data: '<?php echo "{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}"; ?>&categoria=' + categoria + '&titulo=' + titulo + '&tag=' + tag,
            beforeSend: function () {
                $('html, body').animate({scrollTop: 0}, 1500);
                html.html('<div class="alert alert-info">Carregando...</div>').hide().fadeIn('slow');
            },
            error: function () {
                html.html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
            },
            success: function (data) {
                html.hide().html(data).fadeIn('slow', function () {
                    $('html, body').getNiceScroll().resize();
                });
            }
        });
    }

    CKEDITOR.replace('conteudo', {
        height: '600',
        filebrowserBrowseUrl: '<?php echo base_url('browser/browse.php'); ?>'
    });
    CKEDITOR.replace('descricaoyoutube', {
        height: '600',
        filebrowserBrowseUrl: '<?php echo base_url('browser/browse.php'); ?>'
    });

    $(function () {
        setInterval(CK_jQ, 500);

        $('input[name=modulo]').click(function () {

            var moduloSelecionado = $(this).val();
            var modulos = new Array('ckeditor', 'arquivos-pdf', 'quiz', 'atividades', 'video-youtube', 'videos-educador', 'aula-digital', 'jogos', 'livros-digitais', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia');
            var moduloAtual;

            if (moduloSelecionado === 'aula-digital' || moduloSelecionado === 'jogos' || moduloSelecionado === 'livros-digitais' || moduloSelecionado === 'experimentos' || moduloSelecionado === 'softwares' || moduloSelecionado === 'audios' || moduloSelecionado === 'links-externos' || moduloSelecionado === 'multimidia')
                moduloSelecionado = 'biblioteca';

            modulos.forEach(function (modulo) {
                moduloAtual = modulo;

                if (moduloAtual === 'aula-digital' || moduloAtual === 'jogos' || moduloAtual === 'livros-digitais' || moduloAtual === 'experimentos' || moduloAtual === 'softwares' || moduloAtual === 'audios' || moduloAtual === 'links-externos' || moduloAtual === 'multimidia')
                    moduloAtual = 'biblioteca';

                if (moduloAtual === moduloSelecionado) {
                    $('#box-' + moduloAtual).hide().fadeIn('slow');
                } else {
                    $('#box-' + moduloAtual).hide();
                }
            });

            if (moduloSelecionado === 'biblioteca') {
                getBiblioteca($('#html-biblioteca'), '<?php echo base_url('home/getbiblioteca_html'); ?>/' + $('input[name=modulo]:checked').data('tipo') + '/<?php echo $row->biblioteca; ?>', $('select[name=categoriabiblioteca]').val(), $('input[name=titulobiblioteca]').val(), $('input[name=tagsbiblioteca]').val());
            }

        });

        $('i[class*=remove-pergunta]').css('cursor', 'pointer').click(function () {
            var pid = $(this).attr('class').split('-');
            $('#box-' + pid[pid.length - 1]).fadeOut('slow', function () {
                $(this).remove();
            });
        });

        $('i[class*=remove-alternativa]').css('cursor', 'pointer').click(function () {
            $(this).parent().parent().fadeOut('slow', function () {
                $(this).remove();
            });
        });

        $('a[id*=adicionar-alternativa]').css('cursor', 'pointer').click(function () {
            var pid = $(this).attr('id').split('-');
            pid = pid[pid.length - 1];
            var aid = guid();

            var html = '<div class="form-group">';
            html += '	<label class="col-sm-5 col-lg-4 control-label">';
            html += '       Alternativa';
            html += '       <i class="glyphicon glyphicon-remove-circle remove-alternativa-' + aid + '" title="Remover" style="margin-left:2px; color:red;"></i>';
            html += '	</label>';
            html += '	<div class="col-sm-7 col-lg-8 controls">';
            html += '		<input type="text" name="alternativas[' + pid + '][' + aid + ']" placeholder="Alternativa" value="" class="form-control" />';
            html += '		<span class="help-inline" style="color: #000;"><input type="checkbox" name="corretas[' + aid + ']" value="1" /> Alternativa correta</span>';
            html += '	</div>';
            html += '</div>';

            $('#alternativas-' + pid).append(html).hide().fadeIn('slow', function () {
                $('html, body').getNiceScroll().resize();

                $('.remove-alternativa-' + aid).css('cursor', 'pointer').click(function () {
                    $(this).parent().parent().fadeOut('slow', function () {
                        $(this).remove();
                    });
                });
            });

            return false;

        });

        $('input[name*=tipoquestao]').click(function () {
            var id = $(this).data('pergunta');
            if ($(this).val() == 1) {
                $('#box-alternativa-' + id).fadeIn('slow');
                $('#box-resposta-errada-' + id).fadeIn('slow');
            } else if ($(this).val() == 2) {
                $('#box-alternativa-' + id).fadeOut('slow');
                $('#box-resposta-errada-' + id).fadeOut('slow');
            }
        });

        $('#adicionar-pergunta').click(function () {

            var pid = guid();

            var html = '<div id="box-' + pid + '" style="margin: 10px 0; padding: 10px; background-color: #b6d1f2;">';
            html += '	<div class="form-group">';
            html += '		<label class="col-sm-3 col-lg-2 control-label">';
            html += '			Pergunta';
            html += '			<i class="glyphicon glyphicon-remove-circle remove-pergunta-' + pid + '" title="Remover" style="margin-left:2px; color:red;"></i>';
            html += '		</label>';
            html += '		<div class="col-sm-9 col-lg-10 controls">';
            html += '			<textarea name="perguntas[' + pid + ']" class="form-control" rows="3"></textarea>';
            html += '		</div>';
            html += '	</div>';
            html += '	<div class="form-group">';
            html += '	   <label class="col-sm-3 col-lg-2 control-label">Questão</label>';
            html += '	   <div class="col-sm-9 col-lg-10 controls">';
            html += '	      <label class="radio-inline">';
            html += '	          <input type="radio" name="tipoquestao[' + pid + ']" data-pergunta="' + pid + '" value="1" checked="checked" /> Alternativa';
            html += '	      </label>';
            html += '	      <label class="radio-inline">';
            html += '	          <input type="radio" name="tipoquestao[' + pid + ']" data-pergunta="' + pid + '" value="2" /> Dissertativa';
            html += '	      </label>';
            html += '	   </div>';
            html += '	</div>';
            html += '	<div id="box-alternativa-' + pid + '" >';
            html += '       <div class="form-group">';
            html += '               <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>';
            html += '               <div class="col-sm-9 col-lg-10 controls">';
            html += '                       <a href="#" id="adicionar-alternativa-' + pid + '" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Adicionar Alternativa</a>';
            html += '               </div>';
            html += '       </div>';
            html += '       <div id="alternativas-' + pid + '"></div>';
            html += '   </div>';
            html += '<hr />';
            html += '<div class="form-group">';
            html += '    <label class="col-sm-3 col-lg-2 control-label">';
            html += '        Feedback para resposta correta';
            html += '    </label>';
            html += '    <div class="col-sm-9 col-lg-10 controls">';
            html += '        <textarea name="respostacorreta[' + pid + ']" class="form-control" rows="3"></textarea>';
            html += '    </div>';
            html += '</div>';
            html += '<div id="box-resposta-errada-' + pid + '">';
            html += '    <div class="form-group">';
            html += '        <label class="col-sm-3 col-lg-2 control-label">';
            html += '            Feedback para resposta errada';
            html += '        </label>';
            html += '        <div class="col-sm-9 col-lg-10 controls">';
            html += '            <textarea name="respostaerrada[' + pid + ']" class="form-control" rows="3"></textarea>';
            html += '        </div>';
            html += '    </div>';
            html += '</div>';
            html += '</div>';

            $('#perguntas').append(html).fadeIn('slow', function () {
                $('html, body').getNiceScroll().resize();

                $('.remove-pergunta-' + pid).css('cursor', 'pointer').click(function () {
                    $('#box-' + pid).fadeOut('slow', function () {
                        $(this).remove();
                    });
                });

                $('input[name*=tipoquestao]').click(function () {
                    var id = $(this).data('pergunta');
                    if ($(this).val() == 1) {
                        $('#box-alternativa-' + id).fadeIn('slow');
                        $('#box-resposta-errada-' + id).fadeIn('slow');
                    } else if ($(this).val() == 2) {
                        $('#box-alternativa-' + id).fadeOut('slow');
                        $('#box-resposta-errada-' + id).fadeOut('slow');
                    }
                });

                $('#adicionar-alternativa-' + pid).click(function () {

                    var aid = guid();

                    var html = '<div class="form-group">';
                    html += '	<label class="col-sm-5 col-lg-4 control-label">';
                    html += '       Alternativa';
                    html += '       <i class="glyphicon glyphicon-remove-circle remove-alternativa-' + aid + '" title="Remover" style="margin-left:2px; color:red;"></i>';
                    html += '	</label>';
                    html += '	<div class="col-sm-7 col-lg-8 controls">';
                    html += '		<input type="text" name="alternativas[' + pid + '][' + aid + ']" placeholder="Alternativa" value="" class="form-control" />';
                    html += '		<span class="help-inline" style="color: #000;"><input type="checkbox" name="corretas[' + aid + ']" value="1" /> Alternativa correta</span>';
                    html += '	</div>';
                    html += '</div>';

                    $('#alternativas-' + pid).append(html).hide().fadeIn('slow', function () {
                        $('html, body').getNiceScroll().resize();

                        $('.remove-alternativa-' + aid).css('cursor', 'pointer').click(function () {
                            $(this).parent().parent().fadeOut('slow', function () {
                                $(this).remove();
                            });
                        });
                    });

                    return false;
                });

            });

            return false;
        });

        $('#adicionar-pergunta-atividade').click(function () {

            var pid = guid();

            var html = '<div id="box-' + pid + '" style="margin: 10px 0; padding: 10px; background-color: #b6d1f2;">';
            html += '	<div class="form-group">';
            html += '		<label class="col-sm-3 col-lg-2 control-label">';
            html += '			Pergunta';
            html += '			<i class="glyphicon glyphicon-remove-circle remove-pergunta-' + pid + '" title="Remover" style="margin-left:2px; color:red;"></i>';
            html += '		</label>';
            html += '		<div class="col-sm-9 col-lg-10 controls">';
            html += '			<textarea name="perguntas[' + pid + ']" class="form-control" rows="3"></textarea>';
            html += '		</div>';
            html += '	</div>';
            html += '	<div class="form-group">';
            html += '	   <label class="col-sm-3 col-lg-2 control-label">Questão</label>';
            html += '	   <div class="col-sm-9 col-lg-10 controls">';
            html += '	      <label class="radio-inline">';
            html += '	          <input type="radio" name="tipoquestao[' + pid + ']" data-pergunta="' + pid + '" value="1" checked="checked" /> Alternativa';
            html += '	      </label>';
            html += '	   </div>';
            html += '	</div>';
            html += '	<div id="box-alternativa-' + pid + '" >';
            html += '       <div class="form-group">';
            html += '               <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>';
            html += '               <div class="col-sm-9 col-lg-10 controls">';
            html += '                       <a href="#" id="adicionar-alternativa-' + pid + '" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Adicionar Alternativa</a>';
            html += '               </div>';
            html += '       </div>';
            html += '       <div id="alternativas-' + pid + '"></div>';
            html += '   </div>';
            html += '<hr />';
            html += '<div class="form-group">';
            html += '    <label class="col-sm-3 col-lg-2 control-label">';
            html += '        Feedback para resposta correta';
            html += '    </label>';
            html += '    <div class="col-sm-9 col-lg-10 controls">';
            html += '        <textarea name="respostacorreta[' + pid + ']" class="form-control" rows="3"></textarea>';
            html += '    </div>';
            html += '</div>';
            html += '<div id="box-resposta-errada-' + pid + '">';
            html += '    <div class="form-group">';
            html += '        <label class="col-sm-3 col-lg-2 control-label">';
            html += '            Feedback para resposta errada';
            html += '        </label>';
            html += '        <div class="col-sm-9 col-lg-10 controls">';
            html += '            <textarea name="respostaerrada[' + pid + ']" class="form-control" rows="3"></textarea>';
            html += '        </div>';
            html += '    </div>';
            html += '</div>';
            html += '</div>';

            $('#perguntas-atividades').append(html).fadeIn('slow', function () {
                $('html, body').getNiceScroll().resize();

                $('.remove-pergunta-' + pid).css('cursor', 'pointer').click(function () {
                    $('#box-' + pid).fadeOut('slow', function () {
                        $(this).remove();
                    });
                });

                $('input[name*=tipoquestao]').click(function () {
                    var id = $(this).data('pergunta');
                    if ($(this).val() == 1) {
                        $('#box-alternativa-' + id).fadeIn('slow');
                        $('#box-resposta-errada-' + id).fadeIn('slow');
                    } else if ($(this).val() == 2) {
                        $('#box-alternativa-' + id).fadeOut('slow');
                        $('#box-resposta-errada-' + id).fadeOut('slow');
                    }
                });

                $('#adicionar-alternativa-' + pid).click(function () {

                    var aid = guid();

                    var html = '<div class="form-group">';
                    html += '	<label class="col-sm-5 col-lg-4 control-label">';
                    html += '       Alternativa';
                    html += '       <i class="glyphicon glyphicon-remove-circle remove-alternativa-' + aid + '" title="Remover" style="margin-left:2px; color:red;"></i>';
                    html += '	</label>';
                    html += '	<div class="col-sm-7 col-lg-8 controls">';
                    html += '		<input type="text" name="alternativas[' + pid + '][' + aid + ']" placeholder="Alternativa" value="" class="form-control" />';
                    html += '		<span class="help-inline" style="color: #000;"><input type="checkbox" name="corretas[' + aid + ']" value="1" /> Alternativa correta</span>';
                    html += '	</div>';
                    html += '</div>';

                    $('#alternativas-' + pid).append(html).hide().fadeIn('slow', function () {
                        $('html, body').getNiceScroll().resize();

                        $('.remove-alternativa-' + aid).css('cursor', 'pointer').click(function () {
                            $(this).parent().parent().fadeOut('slow', function () {
                                $(this).remove();
                            });
                        });
                    });

                    return false;
                });

            });

            return false;
        });

        $('input[name=buscabiblioteca]').keyup(function (e) {
            if (e.keyCode === 13) {
                getBiblioteca($('#html-biblioteca'), '<?php echo base_url('home/getbiblioteca_html'); ?>/' + $('input[name=modulo]:checked').data('tipo') + '/<?php echo $row->biblioteca; ?>', $('select[name=categoriabiblioteca]').val(), $('input[name=titulobiblioteca]').val(), $('input[name=tagsbiblioteca]').val());
                return false;
            }
        });

        $('#busca-biblioteca').click(function () {
            getBiblioteca($('#html-biblioteca'), '<?php echo base_url('home/getbiblioteca_html'); ?>/' + $('input[name=modulo]:checked').data('tipo') + '/<?php echo $row->biblioteca; ?>', $('select[name=categoriabiblioteca]').val(), $('input[name=titulobiblioteca]').val(), $('input[name=tagsbiblioteca]').val());
            return false;
        }).click();

    });
</script>
<?php
require_once "end_html.php";
?>