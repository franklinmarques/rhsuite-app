<?php require_once APPPATH . 'views/header.php'; ?>

    <!--main content start-->
    <section id="main-content" class="merge-left">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-table"></i> Meus Treinamentos
                        </header>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-10 col-lg-push-1 col-sm-1 hidden-xs hidden-sm">
                                    <div class="panel-group m-bot20" id="accordion">
                                        <div class="well well-sm">
                                            <div class="">
                                                <a class="accordion-toggle" data-toggle="collapse"
                                                   data-parent="#accordion"
                                                   href="#collapseOne" style="height: 1px;">
                                            <span style="padding-left: 40%; font-weight: bold;">
                                                <i class="fa fa-search"></i>&nbsp;&nbsp;&nbsp;&nbsp;Buscar
                                            </span>
                                                </a>
                                            </div>
                                            <div id="collapseOne" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <?php echo form_open('ead/treinamento_cliente/ajax_list', 'data-html="html-meus-cursos" class="form-horizontal" id="busca-meus-cursos"'); ?>
                                                    <div class="form-group">
                                                        <div class="col-sm-2 col-lg-3">
                                                            <select class="form-control input-sm" name="categoria">
                                                                <option value="" selected="">Todas as Categorias
                                                                </option>
                                                                <?php foreach ($categorias->result() as $curso): ?>
                                                                    <option value="<?= $curso->categoria ?>"><?= $curso->categoria ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-3 col-lg-4">
                                                            <select class="form-control input-sm"
                                                                    name="area_conhecimento">
                                                                <option value="" selected="">Todas as Áreas de
                                                                    Conhecimeto
                                                                </option>
                                                                <?php foreach ($areas_conhecimento->result() as $area_conhecimento): ?>
                                                                    <option value="<?= $area_conhecimento->area_conhecimento ?>"><?= $area_conhecimento->area_conhecimento ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-3 col-lg-4 controls">
                                                            <input type="text" name="busca"
                                                                   placeholder="Palavra chave ou Tema..."
                                                                   class="form-control input-sm"/>
                                                        </div>
                                                        <div class="col-sm-3 col-lg-1">
                                                            <button type="submit" class="btn btn-primary busca">
                                                                <i class="glyphicon glyphicon-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <?php echo $this->session->userdata(); ?>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="form-label">Botões de navegação:</label>
                                        <?php if ($this->agent->is_mobile()): ?>
                                            <p>
                                                <button class="btn btn-primary btn-xs" type="button"><i
                                                            class="glyphicons glyphicons-volume_up"></i></button>
                                                <small> Ativar audio</small>&emsp;
                                                <button class="btn btn-default btn-xs" type="button"><i
                                                            class="fa fa-times"></i></button>
                                                <small> Fechar janela</small>&emsp;
                                                <button class="btn btn-primary btn-xs" type="button">
                                                    <i class="glyphicons glyphicons-fullscreen"></i></button>
                                                <small> Tela Cheia</small>
                                            </p>
                                            <p>
                                                <button class="btn btn-primary btn-xs" type="button">A-</button>
                                                <button class="btn btn-primary btn-xs" type="button">A+</button>
                                                <small> Aumentar/Diminuir letras</small>
                                            </p>
                                            <p>
                                                <button class="btn btn-primary btn-xs" type="button">
                                                    <i class="glyphicon glyphicon-arrow-left"></i></button>
                                                <button class="btn btn-primary btn-xs" type="button">
                                                    <i class="glyphicon glyphicon-arrow-right"></i></button>
                                                <small> Pág. anterior/seguinte</small>&emsp;
                                                <button class="btn btn-success btn-xs" type="button"><i
                                                            class="fa fa-check"></i></button>
                                                <small> Finalizar aula</small>
                                            </p>
                                            <p>
                                                <button class="btn btn-warning btn-xs" type="button"><i
                                                            class="fa fa-book"></i></button>
                                                <small> Acessar</small>
                                                <button class="btn btn-info btn-xs" type="button"><i
                                                            class="glyphicon glyphicon-align-center"></i></button>
                                                <small> Andamento</small>
                                                <button class="btn btn-success btn-xs" type="button"
                                                        onclick="alert('Imprima o certificados acessando a plataforma via um computador desktop.');">
                                                    <i class="fa fa-print"></i></button>
                                                <small> Certificado</small>
                                            </p>
                                        <?php else: ?>
                                            <p>
                                                <button class="btn btn-primary btn-xs" type="button"><i
                                                            class="glyphicons glyphicons-volume_up"></i></button>
                                                <small> Ativar audio</small>&emsp;
                                                <button class="btn btn-primary btn-xs" type="button">A-</button>
                                                <button class="btn btn-primary btn-xs" type="button">A+</button>
                                                <small> Aumentar/Diminuir letras</small>&emsp;
                                                <button class="btn btn-primary btn-xs" type="button">
                                                    <i class="glyphicon glyphicon-arrow-left"></i> Anterior
                                                </button>
                                                <button class="btn btn-primary btn-xs" type="button">
                                                    Próximo <i class="glyphicon glyphicon-arrow-right"></i></button>
                                                <small> Página anterior/seguinte</small>
                                                <button class="btn btn-success btn-xs" type="button"><i
                                                            class="fa fa-check"></i> Finalizar aula
                                                </button>
                                                <small> Finalizar aula (necessário para apontar andamento)
                                                </small>&emsp;
                                                <button class="btn btn-primary btn-xs" type="button">
                                                    <i class="glyphicons glyphicons-fullscreen"></i></button>
                                                <small> Ativar Tela Cheia</small>
                                            </p>
                                        <?php endif; ?>
                                        <hr>
                                    </div>
                                </div>
                            </div>

                            <div class="box-content" id="html-meus-cursos"></div>
                        </div>
                    </section>
                </div>
            </div>
            <!-- page end-->

        </section>
    </section>

    <div class="modal fade" id="modal_form" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Editar meu perfil</h3>
                </div>
                <div class="modal-body form">
                    <div id="alert_form"></div>
                    <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data"
                          accept-charset="utf-8">
                        <input type="hidden" value="" name="id"/>
                        <input type="hidden" value="<?= $empresa ?>" name="id_empresa"/>
                        <div class="form-body">
                            <div class="row form-group">
                                <label class="control-label col-md-2">Cliente <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <input name="cliente" placeholder="Nome do cliente" class="form-control"
                                           type="text">
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-md-3 text-right hidden-xs hidden-sm">
                                    <button type="button" class="btn btn-success" id="btnSave" onclick="save()">
                                        Salvar
                                    </button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-2">Usuário <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input name="nome" placeholder="Nome do usuário" class="form-control"
                                           type="text">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-2">E-mail <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input name="email" placeholder="E-mail do usuário" class="form-control"
                                           type="text">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="row form-group" id="senha">
                                <label class="control-label col-md-2">Senha <span
                                            class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input name="senha" class="form-control" type="password"
                                           placeholder="Senha do usuário" autocomplete="new-password">
                                    <span class="help-block senha"></span>
                                </div>
                            </div>
                            <div class="row form-group" id="confirmar_senha">
                                <label class="control-label col-md-2">Confirmar senha <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input name="confirmar_senha" class="form-control" type="password"
                                           placeholder="Confirmar a senha do usuário" autocomplete="new-password">
                                    <span class="help-block senha"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">Foto</label>
                                <div class="col-md-10 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail"
                                             style="width: auto; height: 150px;">
                                            <img src="https://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem"
                                                 alt="Sem imagem">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="width: auto; height: 150px;"></div>
                                        <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new btn btn-default"><i
                                                        class="fa fa-plus text-info"></i> Selecionar Imagem</span>
                                            <span class="fileinput-exists btn btn-default"><i
                                                        class="fa fa-undo text-info"></i> Alterar</span>
                                            <input type="file" name="foto" class="default" accept="image/*"/>
                                            <span class="help-block"></span>
                                        </span>
                                            <a href="#" class="btn btn-default fileinput-exists"
                                               data-dismiss="fileinput"><i class="fa fa-trash text-danger"></i>
                                                Remover</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnSave2" onclick="save()">Salvar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <!--main content end-->

    <link rel="stylesheet" href="<?php echo base_url('assets/js/bootstrap-fileinput/bootstrap-fileinput.css'); ?>">

<?php require_once APPPATH . 'views/end_js.php'; ?>

    <!-- Js -->
    <script src="<?php echo base_url('assets/js/bootstrap-fileinput/bootstrap-fileinput.js'); ?>"></script>

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Meus Treinamentos';
        });

        $('#busca-meus-cursos').submit(function () {
            ajax_post($(this).attr('action'), $(this).serialize(), $('#' + $(this).data('html')));
            return false;
        }).submit();


        function edit_perfil() {
            $('#form')[0].reset();
            $('#form .form-group').removeClass('has-error');
            $('#form span.help-block').html('');

            $.ajax({
                'url': '<?php echo site_url('ead/clientes/editarPerfil') ?>',
                'type': 'POST',
                'dataType': 'json',
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $.each(json, function (key, value) {
                        if ($('#form input[name="' + key + '"]').prop('type') !== 'file') {
                            $('#form input[name="' + key + '"]').val(value);
                        }
                    });

                    if (json.foto) {
                        $('.fileinput-new img').prop({
                            'src': '<?= base_url('imagens/usuarios'); ?>/' + json.foto,
                            'alt': json.foto
                        });
                    } else {
                        $('.fileinput-new img').prop({
                            'src': 'https://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem',
                            'alt': 'Sem imagem'
                        });
                    }

                    $('#alert_form').html('');
                    $('#senha label span, #confirmar_senha label span').removeClass('text-danger');
                    $('#senha label span, #confirmar_senha label span').addClass('text-primary');
                    $('#form .senha').html('<small><i>Obs.: deixe em branco para conservar a senha atual</i></small>');
                    // $('#form .senha').html('<small><i>Obs.: caso não queira alterar a senha, deixar este campo em branco</i></small>');
                    $('#modal_form').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Erro ao excluir o cliente/usuário');
                }
            });
        }


        function save() {
            $('#form .form-group').removeClass('has-error');
            $('#form span.help-block').html('');

            var form = $('#form')[0];
            var data = new FormData(form);

            $.ajax({
                'url': '<?php echo site_url('ead/clientes/salvarPerfil') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': data,
                'enctype': 'multipart/form-data',
                'processData': false,
                'contentType': false,
                'cache': false,
                'beforeSend': function () {
                    $('#btnSave, #btnSave2').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');

                        $('#alert').html('<div class="alert alert-success">' + json.aviso + '</div>').hide().fadeIn('slow', function () {
                            top.location.href = json.pagina;
                        });
                    } else {
                        $('#modal_form').animate({scrollTop: 0});
                        if (json.msg) {
                            $.each(json.msg, function (key, value) {
                                $('#form input[name="' + key + '"]').parents('div.form-group').addClass('has-error');
                                $('#form input[name="' + key + '"] + span.help-block').html(value);
                            });
                        }
                        if (json.erro) {
                            $('#alert_form').html('<div class="alert alert-danger">' + json.erro + '</div>').hide().fadeIn('slow');
                        }
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert_form').html('<div class="alert alert-warning">Erro ao salvar cliente/usuário</div>').hide().fadeIn('slow');
                },
                'complete': function () {
                    $('#btnSave, #btnSave2').text('Salvar').attr('disabled', false);
                }
            });
        }
    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>