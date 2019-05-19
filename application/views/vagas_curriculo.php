<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/favipn.ico'); ?>">
    <title>Rhsuite - Vagas</title>
    <!--Core CSS -->
    <link href="<?php echo base_url('assets/bs3/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-reset.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style-responsive.css'); ?>" rel="stylesheet"/>
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-combobox/css/bootstrap-combobox.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.css"); ?>"/>
    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <style>

        body {
            background-image: url('<?= base_url($imagem_fundo ? 'imagens/usuarios/' . $imagem_fundo : 'assets/images/fdmrh3.jpg') ?>');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
        }

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

        .btn-danger {
            color: #fff;
            background-color: #d9534f;
            border-color: #d43f3a;
        }

        .btn-default: {
            background-color: #fff !important;
            border-color: #ccc !important
            color: #333 !important;
        }

        label.control-label {
            font-weight: bold;
        }

        ul.nav-pills li {
            margin: 0 5px;

        }

        ul.nav-pills li a {
            cursor: default;
            border: 1px solid #ccc;
            border-radius: 20px;
            font-size: 16px;
            font-weight: normal;
        }

        ul.nav-pills li.active a {
            border-color: #2e6da4;
        }

        ul.nav-pills li.disable a {
            color: #777;
            text-decoration: none;
            background-color: transparent;
            cursor: not-allowed;
        }

        ul.nav-pills li.disable.active a {
            background-color: #78a6ce;
            border: 1px solid #77a5cd;
            color: #fdfdfd;
            border-radius: 20px !important;
        }

    </style>
</head>

<body>

<div id="cookie" class="text-danger text-center" style="background-color: #ffe; display: none;">
    Este site usa Cookies! Habilite o uso de cookies em seu navegador para o correto funcionamento do site.
</div>


<div class="container">
    <?php
    if ($url_empresa) {
        $logo = base_url('imagens/usuarios/' . $logo);
        $hr = '<hr style="margin-top:10px; margin-bottom:10px;"/>';
    } else {
        $logo = base_url('assets/img/Llogo-rhsuite.jpg');
        $cabecalho = '';
        $hr = '';
    }
    ?>
    <div style="width: 100%; max-width: 370px; margin: 0 auto;">
        <div align="center">
            <img src="<?php echo $logo; ?>" style="width: auto; max-height: 100px; margin-bottom: 3%;">
            <h4 style="color: #111343; text-shadow: 1px 2px 4px rgba(0, 0, 0, .15);">
                <strong><?php echo $cabecalho; ?></strong></h4>
        </div>
    </div>
    <div style="width: 100%; max-width: 70%; margin: 0 auto;">
        <div align="center">
            <h4>Caro profissional, seja bem-vindo ao nosso cadastro de profissionais.</h4>
            <h5>Preencha seus dados em nosso banco de profissionais e participe de todos os nossos processos
                seletivos.</h5>
        </div>
    </div>
    <br>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Cadastro de candidato</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>

                    <ul class="nav nav-pills" role="tablist" style="font-size: 15px; font-weight: bolder;">
                        <li role="presentation" class="active">
                            <a href="#dados_cadastrais" aria-controls="dados_cadastrais" role="tab" data-toggle="pill">1.
                                Dados cadastrais</a>
                        </li>
                        <li role="presentation">
                            <a href="#formacao" aria-controls="formacao" role="tab" data-toggle="pill">2. Formação</a>
                        </li>
                        <li role="presentation">
                            <a href="#historico_profissional" aria-controls="historico_profissional" role="tab"
                               data-toggle="pill">3. Histórico profissional</a>
                        </li>
                    </ul>

                    <hr>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="dados_cadastrais">
                            <?php echo form_open_multipart('#', 'id="form_dados_cadastrais" class="form-horizontal autocomplete="off"'); ?>
                            <input type="hidden" name="codigo" value="<?= $codigo; ?>">
                            <input type="hidden" name="id" value="">
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a class="btn btn-default" href="<?= site_url($url_empresa . 'vagas'); ?>"><i
                                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                    </a>
                                    <button type="button" class="btn btn-success btnSaveDadosCadastrais"
                                            onclick="save_dados_cadastrais();">Avançar <i
                                                class="glyphicon glyphicon-circle-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                            <fieldset>
                                <legend>Campos obrigatórios</legend>
                                <div class="form-group last">
                                    <label class="col-sm-2 control-label">Foto</label>
                                    <div class="col-lg-7 controls">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail"
                                                 style="width: auto; height: 150px;">
                                                <img src="<?= base_url('imagens/usuarios/Sem+imagem.png') ?>"
                                                     alt="Sem imagem"/>
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="width: auto; height: 150px;"></div>
                                            <div>
                                                <div class="btn btn-default btn-file">
                                                    <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                                    <span class="fileinput-exists"><i
                                                                class="fa fa-undo"></i> Alterar</span>
                                                    <input type="file" name="foto" class="default"
                                                           accept="image/*"/>
                                                </div>
                                                <a href="#" class="btn btn-default fileinput-exists"
                                                   data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Nome candidato <span
                                                class="text-danger">*</span></label>
                                    <div class="col-lg-7 controls">
                                        <input type="text" name="nome" placeholder="Nome do candidato" value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Data nascimento <span
                                                class="text-danger">*</span></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_nascimento" placeholder="dd/mm/aaaa"
                                               value=""
                                               class="form-control text-center date"/>
                                    </div>
                                    <label class="col-sm-1 control-label">Sexo <span
                                                class="text-danger">*</span></label>
                                    <div class="col-sm-2">
                                        <select name="sexo" class="form-control">
                                            <option value="M">Masculino</option>
                                            <option value="F">Feminino</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-1 control-label text-nowrap">Estado civil <span
                                                class="text-danger">*</span></label>
                                    <div class="col-sm-3">
                                        <select name="estado_civil" class="form-control">
                                            <option value="">selecione...</option>
                                            <option value="1">Solteiro(a)</option>
                                            <option value="2">Casado(a)</option>
                                            <option value="3">Desquitado(a)</option>
                                            <option value="4">Divorciado(a)</option>
                                            <option value="5">Viúvo(a)</option>
                                            <option value="6">Outro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Telefone <span
                                                class="text-danger">*</span></label>
                                    <div class="col-lg-4 controls">
                                        <input type="text" name="telefone" placeholder="Telefone" value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-1 control-label">E-mail <span
                                                class="text-danger">*</span></label>
                                    <div class="col-lg-4 controls">
                                        <input type="email" name="email" placeholder="E-mail"
                                               value="nome@email.com.br"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Senha <span
                                                class="text-danger">*</span></label>
                                    <div class="col-lg-4 controls">
                                        <input type="password" name="senha" placeholder="Senha" value="123"
                                               max="32"
                                               class="form-control" autocomplete="off"/>
                                        <i class="help-block">Senha padrão: 123.</i>
                                    </div>
                                    <label class="col-sm-1 control-label">Confirmar senha <span
                                                class="text-danger">*</span></label>
                                    <div class="col-lg-4 controls">
                                        <input type="password" name="confirmar_senha"
                                               placeholder="Confirmar senha"
                                               value="123" max="32" class="form-control" autocomplete="off"/>
                                        <i class="help-block">Confirmação de senha padrão: 123.</i>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Campos complementares</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Nome da mãe</label>
                                    <div class="col-lg-7 controls">
                                        <input type="text" name="nome_mae"
                                               placeholder="Nome da mãe do(a) candidato(a)"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Nome do pai</label>
                                    <div class="col-lg-7 controls">
                                        <input type="text" name="nome_pai"
                                               placeholder="Nome do pai do(a) candidato(a)"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Status</label>
                                    <div class="col-sm-3 col-lg-2 controls">
                                        <select name="status" class="form-control">
                                            <option value="A">Ativo</option>
                                            <option value="E">Excluído</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-2 control-label">Nível de acesso</label>
                                    <div class="col-sm-3 col-lg-2 controls">
                                        <select name="nivel_acesso" class="form-control">
                                            <option value="E">Candidato</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-1 control-label">CPF</label>
                                    <div class="col-lg-2 controls">
                                        <input type="text" name="cpf" id="cpf" placeholder="CPF" value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">RG</label>
                                    <div class="col-lg-2 controls">
                                        <input type="text" name="rg" id="rg" placeholder="RG" value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-1 control-label">PIS</label>
                                    <div class="col-lg-2 controls">
                                        <input type="text" name="pis" id="pis" placeholder="PIS" value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-1 control-label">CEP</label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="text" name="cep" id="cep" placeholder="CEP" value=""
                                                   class="form-control"/>
                                            <span class="input-group-btn">
                                            <button class="btn btn-info" id="consultar_cep" type="button"><i
                                                        class="glyphicon glyphicon-search"></i> Consultar CEP</button>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Logradouro</label>
                                    <div class="col-lg-6 controls">
                                        <input type="text" name="logradouro" id="logradouro"
                                               placeholder="Logradouro"
                                               value="" class="form-control"/>
                                    </div>
                                    <label class="col-sm-1 control-label">Número</label>
                                    <div class="col-lg-2 controls">
                                        <input type="number" name="numero" value=""
                                               class="form-control text-right"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Complemento</label>
                                    <div class="col-lg-4 controls">
                                        <input type="text" name="complemento" id="complemento"
                                               placeholder="Complemento"
                                               value="" class="form-control"/>
                                    </div>
                                    <label class="col-sm-1 control-label">Bairro</label>
                                    <div class="col-lg-4 controls">
                                        <input type="text" name="bairro" id="bairro" placeholder="Bairro"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Estado</label>
                                    <div class="col-sm-2 controls">
                                        <?php echo form_dropdown('estado', $estados, '', 'id="estado" class="form-control filtro"'); ?>
                                    </div>
                                    <label class="col-sm-1 control-label">Cidade </label>
                                    <div class="col-lg-6 controls">
                                        <?php echo form_dropdown('cidade', $cidades, '', 'id="cidade" class="form-control filtro"'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Escolaridade</label>
                                    <div class="col-sm-4 col-lg-3 controls">
                                        <?php echo form_dropdown('escolaridade', $escolaridades, '', 'id="escolaridade" class="form-control"'); ?>
                                    </div>
                                    <label class="col-sm-1 control-label">Deficiência</label>
                                    <div class="col-sm-4 col-lg-3 controls">
                                        <?php echo form_dropdown('deficiencia', $deficiencias, '', 'id="deficiencia" class="form-control"'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Fonte contratação</label>
                                    <div class="col-sm-5 col-lg-4 controls">
                                        <?php echo form_dropdown('fonte_contratacao', $fontesContratacao, '', 'class="form-control"'); ?>
                                    </div>
                                </div>
                            </fieldset>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a class="btn btn-default" href="<?= site_url($url_empresa . 'vagas'); ?>"><i
                                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                    </a>
                                    <button type="button" class="btn btn-success btnSaveDadosCadastrais"
                                            onclick="save_dados_cadastrais();">Avançar <i
                                                class="glyphicon glyphicon-circle-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="formacao">
                            <?php echo form_open('#', 'id="form_formacao" class="form-horizontal autocomplete="off"'); ?>
                            <input type="hidden" name="id_usuario" value="" class="id_usuario">
                            <div class="form-group last">
                                <label class="col-sm-2 control-label">Nível de escolaridade</label>
                                <div class="col-lg-4 controls">
                                    <?php echo form_dropdown('escolaridade', $escolaridades, '', 'class="form-control"'); ?>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button type="button" class="btn btn-default" onclick="edit_dados_cadastrais();"><i
                                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                    </button>
                                    <button type="button" class="btn btn-success btnSaveFormacao"
                                            onclick="save_formacao();">Avançar <i
                                                class="glyphicon glyphicon-circle-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                            <br>
                            <fieldset>
                                <legend>Ensino Fundamental</legend>
                                <input type="hidden" name="id_escolaridade[0]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Instituição</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="instituicao[0]"
                                               placeholder="Nome da instituição de ensino"
                                               value="" class="form-control"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" name="ano_conclusao[0]" placeholder="aaaa" value=""
                                               class="form-control text-center ano"/>
                                    </div>
                                    <div class="col-sm-2 controls">
                                        <div class="checkbox">
                                            <label>
                                                <input name="concluido[0]" type="checkbox" value="1">Completo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Ensino Médio</legend>
                                <input type="hidden" name="id_escolaridade[1]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Curso 1</label>
                                    <div class="col-sm-4 controls">
                                        <input type="text" name="curso[1]" placeholder="Nome do curso de formação"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-1 control-label">Tipo</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="tipo[1]" value="N"> Normal
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="tipo[1]" value="T"> Técnico
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Instituição</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="instituicao[1]"
                                               placeholder="Nome da instituição de ensino"
                                               value="" class="form-control"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" name="ano_conclusao[1]" placeholder="aaaa" value=""
                                               class="form-control text-center ano"/>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id_escolaridade[2]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Curso 2</label>
                                    <div class="col-sm-4 controls">
                                        <input type="text" name="curso[2]" placeholder="Nome do curso de formação"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-1 control-label">Tipo</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="tipo[2]" value="N"> Normal
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="tipo[2]" value="T"> Técnico
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Instituição</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="instituicao[2]"
                                               placeholder="Nome da instituição de ensino"
                                               value="" class="form-control"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" name="ano_conclusao[2]" placeholder="aaaa" value=""
                                               class="form-control text-center ano"/>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id_escolaridade[3]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Curso 1</label>
                                    <div class="col-sm-4 controls">
                                        <input type="text" name="curso[3]" placeholder="Nome do curso de formação"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-1 control-label">Tipo</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="tipo[3]" value="N"> Normal
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="tipo[3]" value="T"> Técnico
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Instituição</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="instituicao[3]"
                                               placeholder="Nome da instituição de ensino"
                                               value="" class="form-control"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" name="ano_conclusao[3]" placeholder="aaaa" value=""
                                               class="form-control text-center ano"/>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Graduação</legend>
                                <input type="hidden" name="id_escolaridade[4]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Curso 1</label>
                                    <div class="col-sm-4 controls">
                                        <input type="text" name="curso[4]" placeholder="Nome do curso de formação"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-1 control-label">Tipo</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="tipo[4]" value="B"> Bacharel
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="tipo[4]" value="T"> Tecnólogo
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Instituição</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="instituicao[4]"
                                               placeholder="Nome da instituição de ensino"
                                               value="" class="form-control"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" name="ano_conclusao[4]" placeholder="aaaa" value=""
                                               class="form-control text-center ano"/>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id_escolaridade[5]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Curso 2</label>
                                    <div class="col-sm-4 controls">
                                        <input type="text" name="curso[5]" placeholder="Nome do curso de formação"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-1 control-label">Tipo</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="tipo[5]" value="B"> Bacharel
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="tipo[5]" value="T"> Tecnólogo
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Instituição</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="instituicao[5]"
                                               placeholder="Nome da instituição de ensino"
                                               value="" class="form-control"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" name="ano_conclusao[5]" placeholder="aaaa" value=""
                                               class="form-control text-center ano"/>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id_escolaridade[6]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Curso 3</label>
                                    <div class="col-sm-4 controls">
                                        <input type="text" name="curso[6]" placeholder="Nome do curso de formação"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-1 control-label">Tipo</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="tipo[6]" value="B"> Bacharel
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="tipo[6]" value="T"> Tecnólogo
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Instituição</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="instituicao[6]"
                                               placeholder="Nome da instituição de ensino"
                                               value="" class="form-control"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" name="ano_conclusao[6]" placeholder="aaaa" value=""
                                               class="form-control text-center ano"/>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Pós-Graduação</legend>
                                <input type="hidden" name="id_escolaridade[7]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Curso 1</label>
                                    <div class="col-sm-4 controls">
                                        <input type="text" name="curso[7]" placeholder="Nome do curso de formação"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" name="ano_conclusao[7]" placeholder="aaaa" value=""
                                               class="form-control text-center ano"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Instituição</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="instituicao[7]"
                                               placeholder="Nome da instituição de ensino"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id_escolaridade[8]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Curso 2</label>
                                    <div class="col-sm-4 controls">
                                        <input type="text" name="curso[8]" placeholder="Nome do curso de formação"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" name="ano_conclusao[8]" placeholder="aaaa" value=""
                                               class="form-control text-center ano"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Instituição</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="instituicao[8]"
                                               placeholder="Nome da instituição de ensino"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id_escolaridade[9]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Curso 3</label>
                                    <div class="col-sm-4 controls">
                                        <input type="text" name="curso[9]" placeholder="Nome do curso de formação"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" name="ano_conclusao[9]" placeholder="aaaa" value=""
                                               class="form-control text-center ano"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Instituição</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="instituicao[9]"
                                               placeholder="Nome da instituição de ensino"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Mestrado</legend>
                                <input type="hidden" name="id_escolaridade[10]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Curso 1</label>
                                    <div class="col-sm-4 controls">
                                        <input type="text" name="curso[10]" placeholder="Nome do curso de formação"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" name="ano_conclusao[10]" placeholder="aaaa" value=""
                                               class="form-control text-center ano"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Instituição</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="instituicao[10]"
                                               placeholder="Nome da instituição de ensino"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id_escolaridade[11]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Curso 2</label>
                                    <div class="col-sm-4 controls">
                                        <input type="text" name="curso[11]" placeholder="Nome do curso de formação"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" name="ano_conclusao[11]" placeholder="aaaa" value=""
                                               class="form-control text-center ano"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Instituição</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="instituicao[11]"
                                               placeholder="Nome da instituição de ensino"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id_escolaridade[12]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Curso 3</label>
                                    <div class="col-sm-4 controls">
                                        <input type="text" name="curso[12]" placeholder="Nome do curso de formação"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Ano de conclusão</label>
                                    <div class="col-sm-2 controls">
                                        <input type="text" name="ano_conclusao[12]" placeholder="aaaa" value=""
                                               class="form-control text-center ano"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Instituição</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="instituicao[12]"
                                               placeholder="Nome da instituição de ensino"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                            </fieldset>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-default" onclick="edit_dados_cadastrais();"><i
                                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                    </button>
                                    <button type="button" class="btn btn-success btnSaveFormacao"
                                            onclick="save_formacao();">Avançar <i
                                                class="glyphicon glyphicon-circle-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <?php echo form_close(); ?>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="historico_profissional">
                            <?php echo form_open('#', 'id="form_historico_profissional" class="form-horizontal autocomplete="off"'); ?>
                            <input type="hidden" name="id_usuario" value="" class="id_usuario">
                            <div class="row">
                                <div class="col-sm-6">
                                    <i class="text-primary"><strong>*</strong> O grupo cujo nome da empresa estiver em
                                        branco será removido do cadastro.</i>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button type="button" class="btn btn-default" onclick="edit_formacao();">
                                        <i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                    </button>
                                    <button type="button" class="btn btn-success btnSaveHistoricoProfissional"
                                            onclick="save_historico_profissional();"><i
                                                class="fa fa-save"></i> Concluir
                                    </button>
                                </div>
                            </div>
                            <fieldset>
                                <legend>Experiência profissional</legend>
                                <input type="hidden" name="id[0]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Empresa 1<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="instituicao[0]" placeholder="Nome da empresa" value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Data de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_entrada[0]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Data de saída</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_saida[0]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_entrada[0]"
                                               placeholder="Nome do cargo de entrada"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de saída</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_saida[0]" placeholder="Nome do cargo de saída"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Salário de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_entrada[0]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 control-label">Salário de saída</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_saida[0]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Razão da saída</label>
                                    <div class="col-sm-9">
                                <textarea name="motivo_saida[0]" class="form-control" rows="1"
                                          maxlength="255"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Realizações</label>
                                    <div class="col-sm-9">
                                        <textarea name="realizacoes[0]" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id[1]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Empresa 2<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="instituicao[1]" placeholder="Nome da empresa" value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Data de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_entrada[1]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Data de saída</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_saida[1]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_entrada[1]"
                                               placeholder="Nome do cargo de entrada"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de saída</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_saida[1]" placeholder="Nome do cargo de saída"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Salário de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_entrada[1]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 control-label">Salário de saída</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_saida[1]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Razão da saída</label>
                                    <div class="col-sm-9">
                                <textarea name="motivo_saida[1]" class="form-control" rows="1"
                                          maxlength="255"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Realizações</label>
                                    <div class="col-sm-9">
                                        <textarea name="realizacoes[1]" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id[2]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Empresa 3<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="instituicao[2]" placeholder="Nome da empresa" value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Data de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_entrada[2]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Data de saída</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_saida[2]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_entrada[2]"
                                               placeholder="Nome do cargo de entrada"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de saída</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_saida[2]" placeholder="Nome do cargo de saída"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Salário de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_entrada[2]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 control-label">Salário de saída</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_saida[2]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Razão da saída</label>
                                    <div class="col-sm-9">
                                <textarea name="motivo_saida[2]" class="form-control" rows="1"
                                          maxlength="255"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Realizações</label>
                                    <div class="col-sm-9">
                                        <textarea name="realizacoes[2]" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id[3]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Empresa 4<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="instituicao[3]" placeholder="Nome da empresa" value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Data de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_entrada[3]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Data de saída</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_saida[3]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_entrada[3]"
                                               placeholder="Nome do cargo de entrada"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de saída</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_saida[3]" placeholder="Nome do cargo de saída"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Salário de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_entrada[3]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 control-label">Salário de saída</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_saida[3]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Razão da saída</label>
                                    <div class="col-sm-9">
                                <textarea name="motivo_saida[3]" class="form-control" rows="1"
                                          maxlength="255"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Realizações</label>
                                    <div class="col-sm-9">
                                        <textarea name="realizacoes[3]" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id[4]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Empresa 5<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="instituicao[4]" placeholder="Nome da empresa" value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Data de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_entrada[4]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Data de saída</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_saida[4]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_entrada[4]"
                                               placeholder="Nome do cargo de entrada"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de saída</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_saida[4]" placeholder="Nome do cargo de saída"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Salário de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_entrada[4]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 control-label">Salário de saída</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_saida[4]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Razão da saída</label>
                                    <div class="col-sm-9">
                                <textarea name="motivo_saida[4]" class="form-control" rows="1"
                                          maxlength="255"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Realizações</label>
                                    <div class="col-sm-9">
                                        <textarea name="realizacoes[4]" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id[5]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Empresa 6<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="instituicao[5]" placeholder="Nome da empresa" value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Data de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_entrada[5]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Data de saída</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_saida[5]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_entrada[5]"
                                               placeholder="Nome do cargo de entrada"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de saída</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_saida[5]" placeholder="Nome do cargo de saída"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Salário de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_entrada[5]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 control-label">Salário de saída</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_saida[5]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Razão da saída</label>
                                    <div class="col-sm-9">
                                <textarea name="motivo_saida[5]" class="form-control" rows="1"
                                          maxlength="255"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Realizações</label>
                                    <div class="col-sm-9">
                                        <textarea name="realizacoes[5]" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <hr>
                                <input type="hidden" name="id[6]" value="">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Empresa 7<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="instituicao[6]" placeholder="Nome da empresa" value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Data de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_entrada[6]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                    <label class="col-sm-2 control-label">Data de saída</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="data_saida[6]" placeholder="dd/mm/aaaa" value=""
                                               class="form-control text-center date"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_entrada[6]"
                                               placeholder="Nome do cargo de entrada"
                                               value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cargo de saída</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cargo_saida[6]" placeholder="Nome do cargo de saída"
                                               value=""
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Salário de entrada<span
                                                class="text-primary"> *</span></label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_entrada[6]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 control-label">Salário de saída</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" name="salario_saida[6]" value=""
                                                   class="form-control text-right valor"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Razão da saída</label>
                                    <div class="col-sm-9">
                                <textarea name="motivo_saida[6]" class="form-control" rows="1"
                                          maxlength="255"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Realizações</label>
                                    <div class="col-sm-9">
                                        <textarea name="realizacoes[6]" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </fieldset>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-default" onclick="edit_formacao();">
                                        <i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                    </button>
                                    <button type="button" class="btn btn-success btnSaveHistoricoProfissional"
                                            onclick="save_historico_profissional();"><i
                                                class="fa fa-save"></i> Concluir
                                    </button>
                                </div>
                            </div>

                            <?php echo form_close(); ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

<!--main content end-->

<footer class="footer">
    <p style="text-align: center; color: #151860; text-shadow: 1px 2px 4px rgba(0, 0, 0, .15);">Copyright &copy;
        PeopleNet In Education<br>
        <a href="mailto:contato@rhsuite.com.br" style="color: #151860;">contato@rhsuite.com.br</a> | <a
                href="mailto:contato@multirh.com.br" style="color: #151860;">contato@multirh.com.br</a>
    </p>
</footer>

<!-- Placed js at the end of the document so the pages load faster -->
<!--Core js-->
<script src="<?php echo base_url('assets/js/jquery.js'); ?>"></script>
<script src="<?php echo base_url('assets/bs3/js/bootstrap.min.js'); ?>"></script>
<!--[if lte IE 8]>
<script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
<!--common script init for all pages-->
<script src="<?php echo base_url("assets/js/ajax/ajax.simple.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/scripts.js"); ?>"></script>
<!--script for this page-->

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

<script src="<?php echo base_url("assets/js/bootstrap-combobox/js/bootstrap-combobox.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

<script>
    var table;

    $('.tags').tagsInput({width: 'auto', defaultText: 'Telefone', placeholderColor: '#999', delimiter: '/'});
    $('.date').mask('00/00/0000');
    $('.ano').mask('0000');
    $('.valor').mask('##.###.##0,00', {reverse: true});
    $('#rg').mask('00.000.000-0', {reverse: true});
    $('#cpf').mask('000.000.000-00', {reverse: true});
    $('#cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('#pis').mask('00.000.000.000', {reverse: true});

    $(document).ready(function () {
        $('.combobox').combobox();
    });

    $('ul.nav-pills li a').on('click', function () {
        return false;
    })


    $('select[name="nivel_acesso"]').on('change', function () {
        if (['2', '4', '5', '6', '12', '14', '15', '16', '17'].indexOf(this.value) > 0) {
            $('#tree input[type="checkbox"]').prop('checked', false).trigger('change');
        }
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

    $('.estrutura').on('change', function () {
        $.ajax({
            url: '<?php echo site_url('funcionario/atualizarEstrutura/') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                depto: $('#form [name="depto"]').val(),
                area: $('#form [name="area"]').val(),
                setor: $('#form [name="setor"]').val()
            },
            success: function (json) {
                $('#form [name="area"]').html($(json.area).html());
                $('#form [name="setor"]').html($(json.setor).html());
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    });

    $('.cargo_funcao').on('change', function () {
        $.ajax({
            url: '<?php echo site_url('funcionario/atualizarCargoFuncao/') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                cargo: $('#form [name="cargo"]').val(),
                funcao: $('#form [name="funcao"]').val()
            },
            success: function (json) {
                $('#form [name="funcao"]').html($(json.funcao).html());
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    });

    $('[name="tipo_vinculo"]').on('change', function () {
        if (this.value === '1') {
            $('#cnpj').prop('disabled', true);
            // $('#cpf, #pis').prop('disabled', false);
            $('#pis').prop('disabled', false);
        } else {
            $('#cnpj').prop('disabled', false);
            // $('#cpf, #pis').prop('disabled', true);
            $('#pis').prop('disabled', true);
        }
    });

    $('[name="status"]').on('change', function () {
        var email = $('[name="email"]').val();
        if (this.value === '5') {
            if (email.length > 0) {
                $('[name="email"]').val('d' + email);
            }
        } else {
            $('[name="email"]').val(email.replace(/^d/g, ''));
        }
    });


    $('#consultar_cep').on('click', function () {
        var cep = $('#cep').val();
        if (cep.length > 0) {
            $.ajax({
                'url': '<?php echo site_url('vagas/consultarCEP') ?>/',
                'type': 'GET',
                'dataType': 'json',
                'data': {
                    'cep': cep
                },
                'beforeSend': function () {
                    $('#consultar_cep').html('<i class="glyphicon glyphicon-search"></i> Consultando...');
                },
                'success': function (json) {
                    if (json.erro === undefined) {
                        $('#logradouro').val(json.logradouro);
                        $('#complemento').val(json.complemento);
                        $('#bairro').val(json.bairro);
                        $('#estado').val(json.estado);
                        $('#numero').val(json.numero);
                        $('#cidade').html(json.cidade);
                    } else {
                        alert('CEP não encontrado.');
                        $('.filtro').val();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            }).done(function () {
                $('#consultar_cep').html('<i class="glyphicon glyphicon-search"></i> Consultar CEP');
            });
        }
    });


    function edit_dados_cadastrais() {
        $('html, body').animate({scrollTop: 0}, 1500);
        $('.nav-pills a[href="#dados_cadastrais"]').tab('show');
    }

    function edit_formacao() {
        $('html, body').animate({scrollTop: 0}, 1500);
        $('.nav-pills a[href="#formacao"]').tab('show');
    }

    function edit_historico_profissional() {
        $('html, body').animate({scrollTop: 0}, 1500);
        $('.nav-pills a[href="#historico_profissional"]').tab('show');
    }


    function save_dados_cadastrais() {
        $('.btnSaveDadosCadastrais').html('Avançando... <i class="glyphicon glyphicon-circle-arrow-right">').attr('disabled', true);

        $.ajax({
            'url': '<?php echo site_url('vagas/salvarCandidato') ?>',
            'type': 'POST',
            'data': new FormData($('#form_dados_cadastrais')[0]),
            'enctype': 'multipart/form-data',
            'processData': false,
            'contentType': false,
            'cache': false,
            'dataType': 'json',
            'success': function (json) {
                if (json.status) {
                    $('#form_dados_cadastrais [name="id"], .id_usuario').val(json.id_usuario);
                    edit_formacao();
                } else if (json.erro) {
                    $('html, body').animate({scrollTop: 0}, 1500);
                    $('#alert').html('<div class="alert alert-danger">' + json.erro + '</div>').hide().fadeIn('slow');
                }
                $('.btnSaveDadosCadastrais').html('Avançar <i class="glyphicon glyphicon-circle-arrow-right">').attr('disabled', false);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('.btnSaveDadosCadastrais').html('Avançar <i class="glyphicon glyphicon-circle-arrow-right">').attr('disabled', false);
            }
        });
    }


    function save_formacao() {
        $('.btnSaveFormacao').html('Avançando... <i class="glyphicon glyphicon-circle-arrow-right">').attr('disabled', true);

        $.ajax({
            'url': '<?php echo site_url('vagas/salvarFormacaoCandidato') ?>',
            'type': 'POST',
            'data': $('#form_formacao').serialize(),
            'dataType': 'json',
            'success': function (json) {
                if (json.status) {
                    edit_historico_profissional();
                } else if (json.erro) {
                    $('html, body').animate({scrollTop: 0}, 1500);
                    $('#alert').html('<div class="alert alert-danger">' + json.erro + '</div>').hide().fadeIn('slow');
                }
                $('.btnSaveFormacao').html('Avançar <i class="glyphicon glyphicon-circle-arrow-right">').attr('disabled', false);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('.btnSaveFormacao').html('Avançar <i class="glyphicon glyphicon-circle-arrow-right">').attr('disabled', false);
            }
        });
    }


    function save_historico_profissional() {
        $('.btnSaveHistoricoProfissional').html('<i class="fa fa-save"> Concluindo...</i>').attr('disabled', true);

        $.ajax({
            'url': '<?php echo site_url('vagas/salvarHistoricoProfissional') ?>',
            'type': 'POST',
            'data': $('#form_historico_profissional').serialize(),
            'dataType': 'json',
            'success': function (json) {
                $('html, body').animate({scrollTop: 0}, 1500);
                if (json.status) {
                    $('#alert').html('<div class="alert alert-success">Cadastro realizado com sucesso</div>').hide().fadeIn('slow', function () {
                        window.location = '<?= site_url($url_empresa . 'vagas'); ?>';
                    });
                } else if (json.erro) {
                    $('#alert').html('<div class="alert alert-danger">' + json.erro + '</div>').hide().fadeIn('slow');
                    $('.btnSaveHistoricoProfissional').html('<i class="fa fa-save"> Concluir</i>').attr('disabled', false);
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('.btnSaveHistoricoProfissional').html('<i class="fa fa-save"> Concluir</i>').attr('disabled', false);
            }
        });
    }


</script>
</body>
</html>
