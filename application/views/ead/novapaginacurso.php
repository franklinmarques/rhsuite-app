<?php
require_once APPPATH . 'views/header.php';
?>

    <style>
        .fileinput .form-control.disabled {
            background-color: #eee;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <span><i class="fa fa-file-text-o"></i><strong>&nbsp;Cadastrar página do treinamento - <?php echo $row->nome; ?></strong></span>
                            <a class="btn btn-default btn-sm"
                               href="<?php echo site_url('ead/pagina_curso/index/' . $row->id); ?>"
                               style="float: right; margin-top: -0.6%;">
                                <i class="fa fa-reply"></i> &nbsp;&nbsp; Voltar
                            </a>
                        </header>
                        <div class="panel-body">
                            <div id="alert"></div>

                            <?php echo form_open_multipart('ead/pagina_curso/ajax_add/' . $row->id, 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                            <input type="hidden" name="id_curso" value="<?= $row->id ?>">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="control-label">Recursos de edição</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="modulo" autocomplete="off" value="ckeditor"
                                                       checked=""/>
                                                <i class="fa fa-file-zip-o"></i> Objetos (texto, figura, tabela, Flash,
                                                links)
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="modulo" autocomplete="off" value="quiz"/>
                                                <i class="fa fa-question-circle"></i> Quick quiz
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="modulo" autocomplete="off"
                                                       value="atividades"/>
                                                <i class="fa fa-pencil"></i> Quiz atividades (avaliação)
                                            </label>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="modulo" autocomplete="off" value="pdf"/>
                                                <i class="fa fa-file-word-o"></i> Arquivos (Word, PorwerPoint, Bloco de
                                                Notas, PDF)
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="modulo" autocomplete="off" value="url"/>
                                                <i class="fa fa-youtube-play"></i> Vídeos e links: Youtube, SlideShare,
                                                URLs (HTTP)
                                            </label>
                                            <!--                                <label class="radio-inline>
                                                                                <input type="radio" name="modulo" value="links-externos" data-tipo="7"<?php //echo ($row->modulo == "links-externos" ? ' checked="checked"' : '');                                                                                                                                                             ?> />
                                                                                <i class="fa fa-link"></i> Links (Youtube, Vimeo, SlideShare, outros)
                                                                            </label>>-->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-right">
                                    <p>
                                        <!--                                    <a class="btn btn-info btn-sm" onclick="$('#audio').trigger('click');">
                                                                                <i class="fa fa-upload"></i> Upload áudio
                                                                            </a>
                                                                            <input type="file" id="audio" accept=".mp3" style="display: none;">-->
                                        <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-audio">
                                            <i class="fa fa-microphone"></i> Gravar áudio
                                        </a>
                                        <!-- Campo com o nome do arquivo de áudio -->
                                        <input id="gravacao_audio" name="gravacao_audio" type="hidden" value=""
                                               readonly>

                                        <!--                                    <a class="btn btn-default" data-toggle="modal" data-target="#modal-video">
                                                                            <i class="fa fa-video-camera"></i> Gravar vídeo
                                                                        </a>
                                                                         Campo com o nome do arquivo de vídeo 
                                                                        <input id="arquivo_video" name="arquivo_video" type="hidden" value="<?php //if (!empty($row->video)) echo $row->video;                                                                                                                                                     ?>" readonly>-->
                                    </p>
                                </div>
                            </div>

                            <!--                        <div class="row">
                                                        <label class="col-md-2 control-label">Tipo unidade</label>
                                                        <div class="col-md-10">
                                                            <label class="radio-inline">
                                                                <input type="radio" name="tipo_unidade">Em tempo de treinamento
                                                            </label>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="tipo_unidade">
                                                                Programada para &nbsp;<input type="number" maxlength="3">&nbsp; dias apos o termino do treinamento
                                                            </label>
                                                        </div>
                                                    </div>-->

                            <hr/>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label">Título unidade/aula</label>
                                    <div class="col-sm-6 col-lg-7 controls">
                                        <input type="text" name="titulo" placeholder="Título" value=""
                                               class="form-control"/>
                                    </div>
                                    <div class="col-sm-3 col-lg-3 controls">
                                        <button type="submit" name="submit" class="btn btn-primary">
                                            <i class="fa fa-ok"></i> Salvar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row url" style="display: none;">
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label">Endereço da URL</label>
                                    <div class="col-sm-8 col-lg-9 controls">
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <input type="radio" name="tipo_url" value="1" checked="">
                                        </span>
                                            <input type="text" name="url" placeholder="Endereço da URL" value=""
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pdf url" style="display: none;">
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label">Arquivo</label>
                                    <div class="pdf col-sm-8 col-lg-9 controls">
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">Selecionar arquivo</span>
                                            <span class="fileinput-exists">Alterar</span>
                                            <input type="file" name="pdf" placeholder="hfggfs"
                                                   accept=".pdf,.doc,.docx,.txt,.ppt,.pptx"/>
                                        </span>
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                               data-dismiss="fileinput">Remover</a>
                                        </div>
                                        <span class="help-inline"></span>
                                        <br>
                                        <i class="text-danger" style="font-size: larger;">Importante: O nome do arquivo
                                            não pode conter sinais especiais nem acentuação.</i>
                                    </div>
                                    <div class="url col-sm-8 col-lg-9 controls">
                                        <span class="help-inline"></span>
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <span class="input-group-addon">
                                            <input name="tipo_url" value="2" type="radio">
                                        </span>
                                            <div class="form-control disabled" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-default btn-file">
                                            <input type="radio" class="url" value="2">
                                            <span class="fileinput-new">Selecionar arquivo</span>
                                            <span class="fileinput-exists">Alterar</span>
                                            <input type="file" name="arquivo_video"
                                                   placeholder="Apenas vídeos .mp4 são suportados!" value=""
                                                   accept="video/mp4" disabled=""/>
                                        </span>
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                               data-dismiss="fileinput">Remover</a>
                                        </div>
                                        <span class="help-inline">Formato permitido: .mp4 (tamanho máximo: 20 Mb)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row quiz atividades" style="display: none;">
                                <input type="hidden" name="questoes_add" value="">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-success" onclick="add_questao()"><i
                                                class="glyphicon glyphicon-plus"></i> Adicionar questao
                                    </button>
                                    <a class="btn btn-success" href="<?= site_url('ead/biblioteca/') ?>"><i
                                                class="glyphicon glyphicon-list-alt"></i> Ver biblioteca de questões</a>
                                </div>
                            </div>
                            <div class="row quiz atividades" style="display: none;">
                                <br>
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label">(NC) Nota de corte</label>
                                    <div class="col-sm-3 col-lg-2 controls">
                                        <input type="number" name="nota_corte" value="" min="0" max="100" step="1"
                                               class="form-control"/>
                                    </div>
                                    <div class="col-sm-6 col-lg-5">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="questao_aleatoria"/>
                                                Mostrar questões em ordem aleatória
                                            </label>
                                        </div>
                                        <div class="checkbox disabled">
                                            <label>
                                                <input type="checkbox" name="alternativa_aleatoria"/>
                                                Mostrar questões em ordem aleatória
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row quiz atividades" style="display: none;">
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-3 control-label">Resultado igual ou maior que
                                        NC</label>
                                    <div class="col-sm-6 col-lg-5 form-inline">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="sizing-addon2">Ir para página</span>
                                            <?php echo form_dropdown('id_pagina_aprovacao', $row->proxima_pagina, '', 'autocomplete="off" class="form-control"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row quiz atividades" style="display: none;">
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-3 control-label">Resultado menor que NC</label>
                                    <div class="col-sm-6 col-lg-5 form-inline">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="sizing-addon2">Ir para página</span>
                                            <?php echo form_dropdown('id_pagina_aprovacao', $row->proxima_pagina, '', 'autocomplete="off" class="form-control"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row quiz atividades" style="display: none;">
                                <hr>
                                <div class="col-sm-12">
                                    <table id="table" class="table table-striped" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th nowrap width="50%">Resumo descritivo da questão</th>
                                            <th nowrap width="50%">Modelo da questão</th>
                                            <th width="auto">Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row ckeditor url">
                                <div class="form-group">
                                    <div class="col-sm-12 col-lg-12 controls">
                                        <textarea name="conteudo" id="conteudo" class="form-control"
                                                  rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--                        <div class="row" id="box-biblioteca"<?php // echo !(in_array($row->modulo, array('mapas', 'simuladores', 'aula-digital', 'jogos', 'livros-digitais', 'infograficos', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia')) ? ' style="display: none;"' : '');                                                                                                                                        ?>>
                            <div class="form-group">
                                <label class="col-sm-2 col-lg-2 control-label">Categoria</label>

                                <div class="col-sm-2 col-lg-2 controls">
                                    <select name="categoriabiblioteca" class="form-control input-sm">
                                        <option value="">Todas</option>
                        <?php //foreach ($categoria->result() as $row_): ?>
                                            <option value="<?php //echo $row_->id;                                                                                                                                        ?>"<?php //echo ($row->categoriabiblioteca == $row_->id ? ' selected="selected"' : '');                                                                                                                                        ?>><?php //echo $row_->curso;                                                                                                                                        ?></option>
                        <?php //endforeach; ?>
                                    </select>
                                </div>
                                <label class="col-sm-1 col-lg-1 control-label">Título</label>

                                <div class="col-sm-2 col-lg-2 controls">
                                    <input type="text" name="titulobiblioteca" placeholder="Título"
                                           value="<?php //echo $row->titulobiblioteca;                                                                                                                                        ?>" class="form-control input-sm"/>
                                </div>
                                <label class="col-sm-1 col-lg-1 control-label">Tags</label>

                                <div class="col-sm-2 col-lg-2 controls">
                                    <input type="text" name="tagsbiblioteca" placeholder="Tags" value="<?php //echo $row->tagsbiblioteca;                                                                                                                                        ?>"
                                           class="form-control input-sm"/>
                                </div>
                                <div class="col-sm-2 col-lg-2">
                                    <a href="#" id="busca-biblioteca" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></a>
                                </div>
                            </div>
                            <div id="html-biblioteca"></div>
                        </div>-->

                            <div class="modal fade" id="modal-audio" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                        aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Gravação de áudio</h4>
                                        </div>
                                        <div class="modal-body">
                                            <audio id="audio" controls style="width: 100%;" src=""></audio>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="autoplay" value="1"> Execução
                                                    automática
                                                </label>
                                            </div>
                                            <hr>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="audio_modo" id="audio_modo_2" value="2"
                                                           checked=""> Enviar arquivo de áudio
                                                </label>
                                            </div>
                                            <div id="modo_2">
                                                <div class="fileinput fileinput-new input-group"
                                                     data-provides="fileinput">
                                                    <div class="form-control" data-trigger="fileinput">
                                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                        <span class="fileinput-filename"></span>
                                                    </div>
                                                    <span class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">Selecionar arquivo</span>
                                                    <span class="fileinput-exists">Alterar</span>
                                                    <input type="file" name="arquivo_audio"
                                                           placeholder="Apenas audio .mp3 são suportados!"
                                                           accept="audio/mp3"/>
                                                </span>
                                                    <a href="#"
                                                       class="input-group-addon btn btn-default fileinput-exists"
                                                       data-dismiss="fileinput">Remover</a>
                                                </div>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="audio_modo" id="audio_modo_1" value="1"
                                                           disabled=""> Gravar áudio usando microfone
                                                </label>
                                            </div>
                                            <div id="modo_1">
                                                <div id="time">
                                                    <span id="stopwatch">00:00:00</span>
                                                </div>
                                                <div class="text-center">
                                                    <div id="buttons" class="btn-group" role="group">
                                                        <button type="button" id="record-audio"
                                                                class="btn btn-default disabled" data-status="0">
                                                            <i class="glyphicon glyphicon-record text-danger"></i>
                                                            Gravar
                                                        </button>
                                                        <button type="button" id="clear-audio"
                                                                onclick="window.location.reload();"
                                                                class="btn btn-default disabled" disabled>
                                                            <i class="glyphicon glyphicon-eject text-info"></i> Limpar
                                                        </button>
                                                        <button type="button" id="send-audio"
                                                                class="btn btn-default disabled" disabled>
                                                            <i class="glyphicon glyphicon-save text-success"></i> Salvar
                                                        </button>
                                                    </div>
                                                </div>

                                                <div id="container-audio" style="padding:1em 2em; font-weight: bolder;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss='modal'
                                                    id='fechaModal'>Fechar
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->

                            <?php echo form_close(); ?>
                        </div>
                    </section>

                </div>
            </div>
            <!-- page end-->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_questao" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Adicionar questão</h4>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form_questao" class="form-horizontal">
                                <input name="id" type="hidden">
                                <input name="row" type="hidden">
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Nome da questão</label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Digite o nome descritivo da questão"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Modelo de referência da biblioteca</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_biblioteca', $questao, '', 'class="form-control"'); ?>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Tipo de questão</label>
                                        <div class="col-md-5">
                                            <select name="tipo" class="form-control">
                                                <option value="1">Múltiplas alternativas</option>
                                                <option value="3">Múltiplas alternativas (quick quiz)</option>
                                                <option value="2">Dissertativa</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Observações</label>
                                        <div class="col-md-9">
                                            <textarea name="observacoes" class="form-control" rows="2"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-9 col-md-offset-3">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="criar_modelo">
                                                    <strong>Adicionar à biblioteca</strong>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="perguntas" value="P"
                                                           class="aleatorizacao">
                                                    Permitir a ordenação aleatória da questão
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="alternativas" value="A"
                                                           class="aleatorizacao">
                                                    Permitir a exibição de alternativas em ordem aleatória
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveQuestao" onclick="save_questao()" class="btn btn-primary">
                                Adicionar à lista
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_conteudo" role="dialog">
                <div class="modal-dialog" style="width: 98%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar conteúdo da questão</h3>
                        </div>
                        <div class="modal-body">
                            <div id="alert"></div>
                            <div class="text-right">
                                <button type="button" id="btnSaveConteudo" onclick="save_conteudo()"
                                        class="btn btn-primary">Salvar
                                </button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            </div>
                            <form action="#" id="form_conteudo" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="row"/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label"><strong>Instruções:</strong> digite ou cole o texto
                                            a ser interpretado na janela abaixo</label>
                                        <textarea name="conteudo" id="questao_conteudo" class="form-control" rows="16"
                                                  placeholder="Insira o texto descritivo da questão aqui"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_respostas" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar respostas</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form_respostas" class="form-horizontal">
                                <input type="hidden" value="" name="id_questao"/>
                                <input type="hidden" value="" name="row"/>
                                <div class="form-body">
                                    <div class="form-group" style="margin-bottom: 0px;">
                                        <label class="control-label col-md-2">Questão</label>
                                        <div class="col-md-10">
                                            <h5 id="nome"
                                                style="overflow:hidden; text-overflow:ellipsis; white-space: nowrap; font-weight: bolder;"></h5>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <button type="button" id="btnSaveRespostas" onclick="save_respostas()"
                                                    class="btn btn-primary">Salvar
                                            </button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <hr style="margin: 10px 0px;">
                                    <div class="text-right text-danger" style="margin-bottom: 20px;">
                                        <i><strong>* Peso igual a 1:</strong> verdadeiro / resposta correta. <strong>Peso
                                                igual a 0:</strong> falso / resposta incorreta.</i>
                                    </div>
                                    <div class="form-group alternativa">
                                        <input type="hidden" value="" name="id_alternativa[]"/>
                                        <label class="control-label col-md-2">Resposta 1</label>
                                        <div class="col-md-7">
                                            <textarea name="alternativa[]" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-1">Peso</label>
                                        <div class="col-md-2">
                                            <input name="peso[]" class="form-control" type="number" value="">
                                        </div>
                                    </div>
                                    <div class="form-group alternativa">
                                        <input type="hidden" value="" name="id_alternativa[]"/>
                                        <label class="control-label col-md-2">Resposta 2</label>
                                        <div class="col-md-7">
                                            <textarea name="alternativa[]" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-1">Peso</label>
                                        <div class="col-md-2">
                                            <input name="peso[]" class="form-control" type="number" value="">
                                        </div>
                                    </div>
                                    <div class="form-group alternativa">
                                        <input type="hidden" value="" name="id_alternativa[]"/>
                                        <label class="control-label col-md-2">Resposta 3</label>
                                        <div class="col-md-7">
                                            <textarea name="alternativa[]" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-1">Peso</label>
                                        <div class="col-md-2">
                                            <input name="peso[]" class="form-control" type="number" value="">
                                        </div>
                                    </div>
                                    <div class="form-group alternativa">
                                        <input type="hidden" value="" name="id_alternativa[]"/>
                                        <label class="control-label col-md-2">Resposta 4</label>
                                        <div class="col-md-7">
                                            <textarea name="alternativa[]" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-1">Peso</label>
                                        <div class="col-md-2">
                                            <input name="peso[]" class="form-control" type="number" value="">
                                        </div>
                                    </div>
                                    <div class="form-group alternativa">
                                        <input type="hidden" value="" name="id_alternativa[]"/>
                                        <label class="control-label col-md-2">Resposta 5</label>
                                        <div class="col-md-7">
                                            <textarea name="alternativa[]" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-1">Peso</label>
                                        <div class="col-md-2">
                                            <input name="peso[]" class="form-control" type="number" value="">
                                        </div>
                                    </div>
                                    <div class="form-group alternativa">
                                        <input type="hidden" value="" name="id_alternativa[]"/>
                                        <label class="control-label col-md-2">Resposta 6</label>
                                        <div class="col-md-7">
                                            <textarea name="alternativa[]" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-1">Peso</label>
                                        <div class="col-md-2">
                                            <input name="peso[]" class="form-control" type="number" value="">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Feedback resposta correta</label>
                                        <div class="col-md-10">
                                            <textarea name="feedback_correta" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Feedback resposta incorreta</label>
                                        <div class="col-md-10">
                                            <textarea name="feedback_incorreta" class="form-control"
                                                      rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

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
                                <source id="previewAudio" src="">
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

    <!-- page end-->

<?php
require_once APPPATH . 'views/end_js.php';
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <!--<link href="<?php // echo base_url('assets/datatables/plugins/dataTables.rowReorder.min.css')                                                                                    ?>" rel="stylesheet">-->
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

    <!-- Js -->
    <script>

        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Cadastrar Página do Treinamento - <?php echo $row->nome; ?>';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <!--<script src="<?php // echo base_url('assets/datatables/plugins/dataTables.rowReorder.min.js');                                                                                    ?>"></script>-->
    <!--<script src="<?php // echo base_url('assets/datatables/plugins/dataTables.editor.js');                                                                                    ?>"></script>-->
    <script src="<?php echo base_url("assets/js/timer/jquery.timer.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/timer/timer.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/ckeditor/adapters/jquery.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/gravar/RecordRTC.js'); ?>"></script>

    <script>
        var table;
        var questoes_add = {};
        //    var editor;

        $(document).ready(function () {
            $('#conteudo, #questao_conteudo').ckeditor({
                'height': '600',
                'filebrowserBrowseUrl': '<?= base_url('browser/browse.php'); ?>'
            });

            table = $('#table').DataTable({
                'processing': true, //Feature control the processing indicator.
                'serverSide': true, //Feature control DataTables' server-side processing mode.
                'iDisplayLength': 25,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                // Load data for the table's content from an Ajax source
                'ajax': {
                    'url': '<?php echo site_url('ead/pagina_curso/ajax_questoes/' . $row->id) ?>',
                    'type': 'POST',
                    'timeout': 9000,
                    'data': function (d) {
                        d.questoes_add = JSON.stringify(questoes_add);
                        return d;
                    }
                },
                //Set column definition initialisation properties.
                'columnDefs': [
                    {
                        'width': '100%',
                        'targets': [1]
                    },
                    {
                        'width': 'auto',
                        'className': 'text-nowrap',
                        'targets': [-1], //last column
                        'orderable': false, //set not orderable
                        'searchable': false //set not orderable
                    }
                ]
            });

            $('input[name="modulo"]').on('change', function () {
                $('div.ckeditor, div.pdf, div.quiz, div.atividades, div.url').hide();
                $('.' + this.value).show();
            });

            $('[name="tipo_url"]').on('change', function () {
                $('[name="url"]').prop('disabled', this.value === '2');
                $('[name="arquivo_video"]').prop('disabled', this.value === '1');
                if (this.value === '1') {
                    $('[name="arquivo_video"], .fileinput:eq(1) .form-control').addClass('disabled');
                } else {
                    $('[name="arquivo_video"], .fileinput:eq(1) .form-control').removeClass('disabled');
                }
            });

            $('[name="audio_modo"]').on('change', function () {
                if (this.value === '1') {
                    $('[name="arquivo_audio"]').prop('disabled', true);
                    $('#modo_2 .fileinput .form-control').addClass('disabled');
                    $('#buttons button').removeClass('disabled');
                } else if (this.value === '2') {
                    $('[name="arquivo_audio"]').prop('disabled', false);
                    $('#modo_2 .fileinput .form-control').removeClass('disabled');
                    $('#buttons button').addClass('disabled');
                }
            });
        });

        $('#form_questao [name="id_biblioteca"]').on('change', function () {
            var checked = false;
            var row = $('#form_questao [name="criar_modelo"]').val();
            if (questoes_add[row] !== undefined) {
                checked = questoes_add[row].criar_modelo;
            }
            $('#form_questao [name="criar_modelo"]').prop({
                'disabled': this.value.length > 0,
                'checked': checked
            });
        });

        function add_questao() {
            $('#form_questao')[0].reset(); // reset form on modals
            $('#form_questao input[type="hidden"]').val(''); // reset hidden input form on modals
            $('#form_questao [name="id_biblioteca"] option').show();
            $.each(questoes_add, function (i, row) {
                $('#form_questao [name="id_biblioteca"] option[value="' + row.id_biblioteca + '"]').hide();
            });
            $('#form_questao [name="criar_modelo"]').prop({'disabled': false, 'checked': false});

            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_questao').modal('show'); // show bootstrap modal
            $('#modal_questao .modal-title').text('Adicionar questão'); // Set Title to Bootstrap modal title
            $('#btnSaveQuestao').text('Adicionar à lista'); // Set title to Bootstrap modal footer
            $('.combo_nivel1').hide();
        }

        function edit_questao(id) {
            $('#form_questao')[0].reset(); // reset form on modals
            $('#form_questao input[type="hidden"]').val(''); // reset hidden input form on modals
            $('#form_questao [name="id_biblioteca"] option').show();
            $.each(questoes_add, function (i, row) {
                if (row.id !== id) {
                    $('#form_questao [name="id_biblioteca"] option[value="' + row.id_biblioteca + '"]').hide();
                }
            });
            $('#form_questao .form-group').removeClass('has-error'); // clear error class
            $('#form_questao .help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('ead/pagina_curso/editar_questao') ?>',
                'type': 'POST',
                'dataType': 'json',
                'timeout': 9000,
                'data': {
                    'id': id
                },
                'success': function (json) {
                    $('#form_questao [name="id"]').val(json.id);
                    $('#form_questao [name="nome"]').val(json.nome);
                    $('#form_questao [name="tipo"]').val(json.tipo);
                    $('#form_questao [name="observacoes"]').val(json.observacoes);
                    $('#form_questao [name="id_biblioteca"]').val(json.id_biblioteca);
                    $('#form_questao [name="criar_modelo"]').prop({
                        checked: false,
                        disabled: json.id_biblioteca !== null
                    });
                    switch (json.aleatorizacao) {
                        case 'P':
                            $('#form_questao [name="perguntas"]').prop('checked', true);
                            break;
                        case 'A':
                            $('#form_questao [name="alternativas"]').prop('checked', true);
                            break;
                        case 'T':
                            $('#form_questao [name="perguntas"], #form_questao [name="alternativas"]').prop('checked', true);
                    }
                    $('#form_questao [name="row"]').val('');

                    $('#modal_questao').modal('show');
                    $('#modal_questao .modal-title').text('Editar questão'); // Set title to Bootstrap modal title
                    $('#btnSaveQuestao').text('Atualizar lista'); // Set title to Bootstrap modal footer
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_questao_row(row) {
            $('#form_questao')[0].reset(); // reset form on modals
            $('#form_questao input[type="hidden"]').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form_questao [name="id_biblioteca"] option').show();
            $.each(questoes_add, function (i, row) {
                if (row.row !== row) {
                    $('#form_questao [name="id_biblioteca"] option[value="' + row.id_biblioteca + '"]').hide();
                }
            });

            var data = questoes_add[row];

            $('#form_questao [name="id"]').val(data.id);
            $('#form_questao [name="nome"]').val(data.nome);
            $('#form_questao [name="tipo"]').val(data.tipo);
            $('#form_questao [name="observacoes"]').val(data.observacoes);
            $('#form_questao [name="id_biblioteca"]').val(data.id_biblioteca);
            $('#form_questao [name="criar_modelo"]').prop({
                'checked': data.criar_modelo,
                'disabled': data.id_biblioteca !== null
            });
            switch (data.aleatorizacao) {
                case 'P':
                    $('#form_questao [name="perguntas"]').prop('checked', true);
                    break;
                case 'A':
                    $('#form_questao [name="alternativas"]').prop('checked', true);
                    break;
                case 'T':
                    $('#form_questao [name="perguntas"], #form_questao [name="alternativas"]').prop('checked', true);
            }
            $('#form_questao [name="row"]').val(data.row);

            $('#modal_questao').modal('show');
            $('#modal_questao .modal-title').text('Editar modelo de questão');
            $('#btnSaveQuestao').text('Atualizar lista');
        }

        function edit_conteudo(id) {
            $('#form_conteudo')[0].reset(); // reset form on modals
            $('#form_conteudo input[type="hidden"]').val(''); // reset hidden input form on modals
            $('#form_conteudo .form-group').removeClass('has-error'); // clear error class
            $('#form_conteudo .help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('ead/pagina_curso/editar_conteudo') ?>',
                'type': 'POST',
                'dataType': 'json',
                'timeout': 9000,
                'data': {
                    'id': id
                },
                'success': function (json) {
                    $('#form_conteudo [name="id"]').val(json.id);
                    $('#form_conteudo [name="conteudo"]').val(json.conteudo);
                    $('#modal_conteudo').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_conteudo_row(row) {
            $('#form_conteudo')[0].reset(); // reset form on modals
            $('#form_conteudo input[type="hidden"]').val(''); // reset hidden input form on modals
            $('#form_conteudo .form-group').removeClass('has-error'); // clear error class
            $('#form_conteudo .help-block').empty(); // clear error string

            //Ajax Load data from ajax
            var data = questoes_add[row];

            $('#modal_conteudo [name="id"]').val(data.id);
            $('#modal_conteudo [name="row"]').val(data.row);
            $('#modal_conteudo [name="conteudo"]').val(data.conteudo);

            $('#modal_conteudo').modal('show');
        }

        function edit_respostas(id) {
            $('#form_respostas')[0].reset(); // reset form on modals
            $('#form_respostas input[type="hidden"]').val(''); // reset hidden input form on modals
            $('#form_respostas .form-group').removeClass('has-error'); // clear error class
            $('#form_respostas .help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('ead/pagina_curso/editar_respostas') ?>',
                'type': 'POST',
                'dataType': 'json',
                'timeout': 9000,
                'data': {
                    'id': id
                },
                'success': function (json) {
                    $('#nome').text(json.nome);
                    $('#form_respostas [name="id_questao"]').val(json.id_questao);
                    $('#form_respostas [name="feedback_correta"]').val(json.feedback_correta);
                    $('#form_respostas [name="feedback_incorreta"]').val(json.feedback_incorreta);
                    if (json.tipo === '2') {
                        $('#form_respostas .alternativa').hide().find('input').val('');
                    } else {
                        $('#form_respostas .alternativa').show();
                    }

                    $(json.alternativas).each(function (index, field) {
                        $('#form_respostas .alternativa:eq(' + index + ') input[name="id_alternativa[]"]').val(field.id);
                        $('#form_respostas .alternativa:eq(' + index + ') textarea[name="alternativa[]"]').val(field.alternativa);
                        $('#form_respostas .alternativa:eq(' + index + ') input[name="peso[]"]').val(field.peso);
                    });
                    $('#modal_respostas').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_respostas_row(row) {
            $('#form_respostas')[0].reset(); // reset form on modals
            $('#form_respostas input[type="hidden"]').val(''); // reset hidden input form on modals
            $('#form_respostas .form-group').removeClass('has-error'); // clear error class
            $('#form_respostas .help-block').empty(); // clear error string

            //Ajax Load data from ajax
            var data = questoes_add[row];

            $('#nome').text(data.nome);
            $('#form_respostas [name="id_questao"]').val(data.id);
            $('#form_respostas [name="row"]').val(data.row);
            $('#form_respostas [name="feedback_correta"]').val(data.feedback_correta);
            $('#form_respostas [name="feedback_incorreta"]').val(data.feedback_incorreta);
            if (data.tipo === '2') {
                $('#form_respostas .alternativa').hide().find('input').val('');
            } else {
                $('#form_respostas .alternativa').show();
            }
            $(data.alternativas).each(function (index, field) {
                $('#form_respostas .alternativa:eq(' + index + ') input[name="id_alternativa[]"]').val(field.id);
                $('#form_respostas .alternativa:eq(' + index + ') textarea[name="alternativa[]"]').val(field.alternativa);
                $('#form_respostas .alternativa:eq(' + index + ') input[name="peso[]"]').val(field.peso);
            });

            $('#modal_respostas').modal('show');
        }

        function save_questao() {
            $('#btnSaveQuestao').text('Salvando...').attr('disabled', true);

            var row = $('#form_questao [name="row"]').val();
            var conteudo = '';
            var feedback_correta = '';
            var feedback_incorreta = '';
            var alternativas = {};

            var ajax_questao = false;
            if (row.length === 0) {
                row = Object.keys(questoes_add).length.toString();
                if ($('#form_questao [name="id_biblioteca"]').val().length > 0) {
                    ajax_questao = true;
                }
            } else {
                if (questoes_add[row].id_biblioteca !== $('#form_questao [name="id_biblioteca"]').val()) {
                    ajax_questao = true;
                }
            }
            if (ajax_questao === true) {
                $.ajax({
                    'url': '<?php echo site_url('ead/biblioteca/ajax_conteudo') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'async': false,
                    'timeout': 9000,
                    'data': {
                        'id': $('#form_questao [name="id_biblioteca"]').val()
                    },
                    'success': function (json) {
                        conteudo = json.conteudo;
                    }
                });
                $.ajax({
                    'url': '<?php echo site_url('ead/biblioteca/ajax_respostas') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'async': false,
                    'timeout': 9000,
                    'data': {
                        'id': $('#form_questao [name="id_biblioteca"]').val()
                    },
                    'success': function (json) {
                        feedback_correta = json.feedback_correta;
                        feedback_incorreta = json.feedback_incorreta;
                        alternativas = json.alternativas;
                    }
                });
            } else if (questoes_add[row] !== undefined) {
                conteudo = questoes_add[row].conteudo;
                feedback_correta = questoes_add[row].feedback_correta;
                feedback_incorreta = questoes_add[row].feedback_incorreta;
                alternativas = questoes_add[row].alternativas;
            }

            var aleatorizacao = '';
            if ($('#form_questao [name="perguntas"]').is(':checked') && $('#form_questao [name="alternativas"]').is(':checked')) {
                aleatorizacao = 'T';
            } else if ($('#form_questao [name="perguntas"]').is(':checked')) {
                aleatorizacao = 'P';
            } else if ($('#form_questao [name="alternativas"]').is(':checked')) {
                aleatorizacao = 'A';
            }
            var values = {
                'id': $('#form_questao [name="id"]').val(),
                'nome': $('#form_questao [name="nome"]').val(),
                'tipo': $('#form_questao [name="tipo"]').val(),
                'conteudo': conteudo,
                'feedback_correta': feedback_correta,
                'feedback_incorreta': feedback_incorreta,
                'observacoes': $('#form_questao [name="observacoes"]').val(),
                'aleatorizacao': aleatorizacao,
                'id_biblioteca': $('#form_questao [name="id_biblioteca"]').val(),
                'criar_modelo': $('#form_questao [name="criar_modelo"]').is(':checked'),
                'alternativas': alternativas,
                'row': row
            };

            questoes_add[row] = values;

            $('#modal_questao').modal('hide');
            reload_table();

            $('[name="questoes_add"]').val(JSON.stringify(questoes_add));

            $('#btnSaveQuestao').text('Adicionar à lista'); //change button text
            $('#btnSaveQuestao').attr('disabled', false); //set button enable
        }

        function save_conteudo() {
            $('#btnSaveConteudo').text('Salvando...').attr('disabled', true);

            var row = $('#form_conteudo [name="row"]').val();
            var alternativas = {};
            var values = {};

            if (questoes_add[row] === undefined) {
                row = Object.keys(questoes_add).length.toString();

                $.ajax({
                    'url': '<?php echo site_url('ead/pagina_curso/editar_respostas') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'async': false,
                    'timeout': 9000,
                    'data': {
                        'id': $('#form_conteudo [name="id"]').val()
                    },
                    'success': function (json) {
                        alternativas = json.alternativas;
                    }
                });

                $.ajax({
                    'url': '<?php echo site_url('ead/pagina_curso/editar_questao') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'async': false,
                    'timeout': 9000,
                    'data': {
                        'id': $('#form_conteudo [name="id"]').val()
                    },
                    'success': function (json) {
                        values = {
                            'id': json.id,
                            'nome': json.nome,
                            'tipo': json.tipo,
                            'conteudo': $('#form_conteudo [name="conteudo"]').val(),
                            'feedback_correta': json.feedback_correta,
                            'feedback_incorreta': json.feedback_incorreta,
                            'observacoes': json.observacoes,
                            'aleatorizacao': json.aleatorizacao,
                            'id_biblioteca': json.id_biblioteca,
                            'criar_modelo': false,
                            'alternativas': alternativas,
                            'row': row
                        };
                    }
                });

                questoes_add[row] = values;
            } else {
                questoes_add[row].conteudo = $('#form_conteudo [name="conteudo"]').val();
            }

            $('#modal_conteudo').modal('hide');
            reload_table();

            $('[name="questoes_add"]').val(JSON.stringify(questoes_add));

            $('#btnSaveConteudo').text('Salvar').attr('disabled', false);
        }

        function save_respostas() {
            $('#btnSaveRespostas').text('Salvando...').attr('disabled', true);

            var row = $('#form_respostas [name="row"]').val();
            var values = {};
            var alternativas = [];
            $('#form_respostas .alternativa').each(function (i) {
                var resposta = {
                    'id': $('#form_respostas .alternativa:eq(' + i + ') [name="id_alternativa[]"]').val(),
                    'alternativa': $('#form_respostas .alternativa:eq(' + i + ') [name="alternativa[]"]').val(),
                    'peso': $('#form_respostas .alternativa:eq(' + i + ') [name="peso[]"]').val()
                };
                if (resposta.alternativa.length > 0) {
                    alternativas.push(resposta);
                }
            });

            if (questoes_add[row] === undefined) {
                row = Object.keys(questoes_add).length.toString();

                $.ajax({
                    'url': '<?php echo site_url('ead/pagina_curso/editar_questao') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'async': false,
                    'timeout': 9000,
                    'data': {
                        'id': $('#form_respostas [name="id_questao"]').val()
                    },
                    'success': function (json) {
                        values = {
                            'id': json.id,
                            'nome': json.nome,
                            'tipo': json.tipo,
                            'conteudo': json.conteudo,
                            'feedback_correta': $('#form_respostas [name="feedback_correta"]').val(),
                            'feedback_incorreta': $('#form_respostas [name="feedback_incorreta"]').val(),
                            'observacoes': json.observacoes,
                            'aleatorizacao': json.aleatorizacao,
                            'id_biblioteca': json.id_biblioteca,
                            'criar_modelo': false,
                            'alternativas': alternativas,
                            'row': row
                        };
                    }
                });

                questoes_add[row] = values;
            } else {
                questoes_add[row].feedback_correta = $('#form_respostas [name="feedback_correta"]').val();
                questoes_add[row].feedback_incorreta = $('#form_respostas [name="feedback_incorreta"]').val();
                questoes_add[row].alternativas = alternativas;
            }

            $('#modal_respostas').modal('hide');
            reload_table();

            $('[name="questoes_add"]').val(JSON.stringify(questoes_add));

            $('#btnSaveRespostas').text('Salvar').attr('disabled', false);
        }

        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }

        function delete_questao(id) {
            $.each(questoes_add, function (i, key) {
                if (key.id === id) {
                    delete questoes_add[key.row];
                    return false;
                }
            });

            reload_table();

            $('[name="questoes_add"]').val(JSON.stringify(questoes_add));
        }

        function delete_questao_row(row) {
            delete questoes_add[row];

            reload_table();

            $('[name="questoes_add"]').val(JSON.stringify(questoes_add));
        }
    </script>

    <script>
        /********************** RecordRTC - config **********************/
        (function () {
            var params = {}, r = /([^&=]+)=?([^&]*)/g;

            function d(s) {
                return decodeURIComponent(s.replace(/\+/g, ' '));
            }

            var match, search = window.location.search;
            while (match === r.exec(search.substring(1))) {
                params[d(match[1])] = d(match[2]);
            }
            window.params = params;
        })();
        xhr = function (url, data, progress, percentage, callback) {
            var request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    callback(request.responseText);
                }
            };

            if (url.indexOf('<?= site_url('gravacao/delete_audio') ?>') === -1) {
                request.upload.onloadstart = function () {
                    percentage.innerHTML = 'Upload: iniciado...';
                };

                request.upload.onprogress = function (event) {
                    progress.max = event.total;
                    progress.value = event.loaded;
                    percentage.innerHTML = 'Upload: progresso ' + Math.round(event.loaded / event.total * 100) + '%';
                };

                request.upload.onload = function () {
                    percentage.innerHTML = 'Arquivo salvo! Para finalizar, salve as alterações na página.  ';
                };
            }

            request.open('POST', url);
            request.send(data);
        };
        xhrVD = function (url, data, callback) {
            var request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    callback(request.responseText);
                }
            };
            request.open('POST', url);
            request.send(data);
        };

        /********************** Áudio **********************/
            // Painel de execução de áudio atual
        var audio = document.querySelector('audio');
        // Botão de gravar/pausar gravação de áudio
        var recordAudio = document.getElementById('record-audio');
        // Botão de limpar gravação de áudio
        var clearAudio = document.getElementById('clear-audio');
        // Botão de salvar gravação de áudio
        var sendAudio = document.getElementById('send-audio');
        // Div de armazenamento de áudio recém gravado
        var container_audio = document.getElementById('container-audio');

        // Mais variáveis de áudio
        var isFirefox = Boolean(navigator.mozGetUserMedia);
        var strong = null;
        var progress = null;
        var audioStream;
        var recorder;
        var Example1;
        var audioConstraints = {
            'audio': true,
            'video': false
        };

        recordAudio.onclick = function () {
            if (recordAudio.dataset.status === '0') {
                gravar_audio();
                recordAudio.dataset.status = '1';
                this.innerHTML = '<i class="glyphicon glyphicon-pause text-primary"></i> Pausar';
            } else {
                pausar_audio();
                recordAudio.dataset.status = '0';
                this.innerHTML = '<i class="glyphicon glyphicon-record text-danger"></i> Gravar';
            }
            clearAudio.disabled = false;
            sendAudio.disabled = false;
        };

        clearAudio.onclick = function () {
            limpar_audio();
            recordAudio.dataset.status = '0';
            recordAudio.innerHTML = '<i class="glyphicon glyphicon-record text-danger"></i> Gravar';
            sendAudio.disabled = true;
            this.disabled = true;
//                ilmhçkf                
        };

        sendAudio.onclick = function () {
            if (recordAudio.dataset.status === '1') {
                recordAudio.click();
            }
            this.disabled = true;
            concluir_audio();
            recordAudio.disabled = false;
            clearAudio.disabled = false;
        };

        function gravar_audio() {
            if (Boolean(recorder)) {
                recorder.resumeRecording();
            } else {
                if (Boolean(audioStream) === false) {
                    navigator.getUserMedia(audioConstraints, function (stream) {
                        if (window.IsChrome) {
                            stream = new window.MediaStream(stream.getAudioTracks());
                        }
                        audioStream = stream;

                        // "audio" is a default type
                        recorder = window.RecordRTC(stream, {
                            'type': 'audio',
                            'bufferSize': typeof (params.bufferSize) === undefined ? 16384 : params.bufferSize,
                            'sampleRate': typeof (params.sampleRate) === undefined ? 44100 : params.sampleRate,
                            'leftChannel': params.leftChannel || false,
                            'disableLogs': params.disableLogs || false
                        });
                        recorder.startRecording();
                    }, function () {
                    });
                } else {
                    audio.src = URL.createObjectURL(audioStream);
                    audio.muted = true;
                    audio.play();
                    if (recorder) {
                        recorder.startRecording();
                    }
                }
            }
            Example1.Timer.toggle();
        }

        function pausar_audio() {
            if (Boolean(recorder)) {
                recorder.pauseRecording();
                Example1.Timer.toggle();
            }
        }

        function limpar_audio() {
            if (Boolean(recorder)) {
                audio.src = '';
                recorder.stopRecording(function () {
                    recorder = null;
                    audioStream = null;
                });
                Example1.Timer.stop();
                Example1.resetStopwatch();

                window.addEventListener('beforeunload', function (event) {
                    event.returnValue = 'Limpando cache de gravações temporárias...';
                    // Limpa os dados gravados temporariamente
                    $('body').load('<?= site_url('home/limparArquivosTemp') ?>');
                });
            }
        }

        function concluir_audio() {
            audio.src = '';
            fileName = Math.round(Math.random() * 99999999) + 99999999;

            if (!isFirefox) {
                recorder.stopRecording(function () {
                    save_audio(recorder.getBlob(), 'audio', fileName + '.mp3');
                });
            } else {
                recorder.stopRecording(function () {
                    save_audio(recorder.getBlob(), 'audio', fileName + '.ogg');
                });
            }

            //Zerar timer e mudar nome do botão
            Example1.Timer.stop();
            Example1.resetStopwatch();
            // Habilita a verificação de descarregamento da página
            window.addEventListener('beforeunload', function (event) {
                event.returnValue = 'Limpando cache de gravações temporárias...';
                // Limpa os dados gravados temporariamente
                $('body').load('<?= site_url('home/limparArquivosTemp') ?>');
            });
        }

        function save_audio(blob, fileType, fileName) {
            var formData = new FormData();
            formData.append(fileType + '-filename', fileName);
            formData.append(fileType + '-blob', blob);

            if (strong === null) {
                strong = document.createElement('strong');
                strong.id = 'percentage';
            }
            strong.innerHTML = fileType + ' upload progresso: ';

            container_audio.appendChild(strong);
            if (progress === null) {
                progress = document.createElement('progress');
            }

            container_audio.appendChild(progress);

            xhr('<?= site_url('gravacao/save_audio') ?>', formData, progress, percentage, function (fileURL) {
                var source = document.createElement('source');
                var href = '<?= base_url('arquivos/media') ?>/';
                audio.src = href + fileURL;
                audio.autoplay = false;

                source.src = href + fileURL;

                //Verifica se não retornou erro
                if (!fileURL.match('Error')) {
                    $('#gravacao_audio').val(fileName);
                }

                if (fileType === 'video') {
                    source.type = 'video/webm; codecs="vp8, vorbis"';
                }
                if (fileType === 'audio') {
                    source.type = isFirefox ? 'audio/ogg' : 'audio/mp3';
                }
            });
        }

        /********************** Vídeo **********************/
            // Variáveis de Vídeo
        var recordVD = document.getElementById('recordVideo');
        var stopVD = document.getElementById('stopVideo');
        var recordVideo = document.getElementById('record-video');
        var previewVD = document.getElementById('previewVideo');
        var containerVD = document.getElementById('containerVideo');

        recordVD.onclick = function () {
            gravar_video();
        };

        stopVD.onclick = function () {
            concluir_video();
        };

        function gravar_video() {
            recordVD.disabled = true;
            !window.stream && navigator.getUserMedia({
                'audio': true,
                'video': true
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
                    'type': 'video'
                });

                recordAudio.startRecording();

                stopVD.disabled = false;
            }
        }

        function concluir_video() {
            containerVD.innerHTML = 'Upload: enviando arquivos...';

            recordVD.disabled = false;
            stopVD.disabled = true;

            previewVD.src = '';
            previewVD.poster = 'ajax-loader.gif';

            fileName = Math.round(Math.random() * 99999999) + 99999999;

            if (!isFirefox) {
                recordAudio.stopRecording(function () {
                    containerVD.innerHTML = 'Upload: áudio enviado! Enviando vídeo...';
                    recordVideo.stopRecording(function () {
                        containerVD.innerHTML = 'Upload: gravando e convertendo...';
                        PostBlobVideo(recordAudio.getBlob(), recordVideo.getBlob(), fileName);
                    });
                });
            }
        }

        function save_video(audioBlob, videoBlob, fileName) {
            var formData = new FormData();
            formData.append('filename', fileName);
            formData.append('audio-blob', audioBlob);
            formData.append('video-blob', videoBlob);
            xhrVD('<?= site_url('gravacao/save_video') ?>', formData, function (ffmpeg_output) {
                containerVD.innerHTML = ffmpeg_output.replace(/\\n/g, '<br />');
                previewVD.src = '<?= base_url('arquivos/media/'); ?>/' + fileName + '-merged.webm';

                //Salvar arquivo no input
                $('#arquivo_video').val('<?= base_url('arquivos/media/'); ?>/' + fileName + '-merged.webm');

                previewVD.muted = false;
            });
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
            $('#conteudo').val(CKEDITOR.instances.conteudo.getData());
            $('#descricaoyoutube').val(CKEDITOR.instances.descricaoyoutube.getData());
        }

        function getBiblioteca(html, url, categoria, titulo, tag) {
            $.ajax({
                'url': url,
                'type': 'POST',
                'data': '<?php echo "{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}"; ?>&categoria=' + categoria + '&titulo=' + titulo + '&tag=' + tag,
                'beforeSend': function () {
                    $('html, body').animate({'scrollTop': 0}, 1500);
                    html.html('<div class="alert alert-info">Carregando...</div>').hide().fadeIn('slow');
                },
                'error': function () {
                    html.html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                },
                'success': function (json) {
                    html.hide().html(json).fadeIn('slow', function () {
                        $('html, body').getNiceScroll().resize();
                    });
                }
            });
        }

        //    CKEDITOR.replace('conteudo', {
        //        'height': '600',
        //        'filebrowserBrowseUrl': '<?php //echo base_url('browser/browse.php'); ?>'
        //    });
        //    CKEDITOR.replace('descricaoyoutube', {
        //        'height': '600',
        //        'filebrowserBrowseUrl': '<?php //echo base_url('browser/browse.php'); ?>'
        //    });
        //
        //    $(function () {
        //        setInterval(CK_jQ, 500);
        //
        //        $('input[name=buscabiblioteca]').keyup(function (e) {
        //            if (e.keyCode === 13) {
        //                getBiblioteca($('#html-biblioteca'), '<?php //echo site_url('ead/paginas/ajax_biblioteca'); ?>/' + $('input[name=modulo]:checked').data('tipo') + '/<?php //echo $row->biblioteca;                                                                                                                                        ?>', $('select[name=categoriabiblioteca]').val(), $('input[name=titulobiblioteca]').val(), $('input[name=tagsbiblioteca]').val());
        //                return false;
        //            }
        //        });
        //
        //        $('#busca-biblioteca').click(function () {
        //            getBiblioteca($('#html-biblioteca'), '<?php //echo site_url('ead/paginas/ajax_biblioteca'); ?>/' + $('input[name=modulo]:checked').data('tipo') + '/<?php //echo $row->biblioteca;                                                                                                                                        ?>', $('select[name=categoriabiblioteca]').val(), $('input[name=titulobiblioteca]').val(), $('input[name=tagsbiblioteca]').val());
        //            return false;
        //        });
        //
        //    });
    </script>
    <!--<script>
    window.onbeforeunload = ConfirmExit;
    function ConfirmExit()
    {
        //Pode se utilizar um window.confirm aqui também...
        $('#ajuda').load('<?php // site_url('ajuda')                                                                                                                                                     ?>');
        alert('Mensagem de fechamento de janela....');
    }
</script>-->
<?php
require_once APPPATH . 'views/end_html.php';
?>
