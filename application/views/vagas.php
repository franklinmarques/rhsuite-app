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

        body .container {
            padding: 10px !important;
            min-height: calc(100% - 40px);
        }

        .btn-primary {
            background-color: #337ab7 !important;
            border-color: #2e6da4 !important;
            color: #fff;
        }

        .btn-default: {
            background-color: #fff !important;
            border-color: #ccc !important
            color: #333 !important;
        }

        label.control-label {
            font-weight: bold;
        }

        .panel {
            background-color: rgba(255, 255, 255, 0.81);
        }

        .table tbody tr {
            background-color: #fff;
        }

        #modal_vaga .form-horizontal .control-label {
            padding-top: 7px;
        }

    </style>
</head>

<body>

<div id="cookie" class="text-danger text-center" style="background-color: #ffe; display: none;">
    Este site usa Cookies! Habilite o uso de cookies em seu navegador para o correto funcionamento do site.
</div>


<div class="container">
    <?php
    if ($logoempresa) {
        $logo = base_url('imagens/usuarios/' . $logo);
        $hr = '<hr style="margin-top:10px; margin-bottom:10px;"/>';
    } else {
        $logo = base_url('assets/img/Llogo-rhsuite.jpg');
        $cabecalho = '';
        $hr = '';
    }
    ?>

    <div class="panel">
        <div class="panel-header">
            <br>
            <div align="center">
                <img src="<?php echo $logo; ?>" style="width: auto; max-height: 100px; margin-bottom: 3%;">
                <h4 style="color: #111343; text-shadow: 1px 2px 4px rgba(0, 0, 0, .15);">
                    <strong><?php echo $cabecalho; ?></strong></h4>
            </div>
            <div align="center">
                <h4>Bem-vindo ao painel de vagas</h4>
                <h5>Caso alguma vaga seja de seu interesse, acione o botão "Candidatar-se"</h5>
                <?php if (!$this->session->userdata('logado')): ?>
                    <div class="row hidden-xs">
                        <div class="col-sm-4">
                            <a type="button" class="btn btn-block" href="<?php echo site_url('vagas/novoCandidato'); ?>"
                               style="width: 250px; color: #fff; background-color: #111343;">Cadastrar currículo</a>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-block" data-toggle="modal"
                                    data-target="#modal_recuperacao_senha"
                                    style="width: 250px; color: #fff; background-color: #111343;">Recuperar senha
                            </button>
                        </div>
                        <div class="col-sm-4">
                            <a type="button" class="btn btn-block" href="login"
                               style="width: 250px; color: #fff; background-color: #111343;">Entrar no portal de
                                vagas</a>
                        </div>
                    </div>
                    <div class="row visible-xs">
                        <div class="col-sm-12">
                            <a type="button" class="btn btn-block" href="<?php echo site_url('vagas/novoCandidato'); ?>"
                               style="width: 250px; color: #fff; background-color: #111343;">Cadastrar currículo</a>
                            <button type="button" class="btn btn-block" data-toggle="modal"
                                    data-target="#modal_recuperacao_senha"
                                    style="width: 250px; color: #fff; background-color: #111343;">Recuperar senha
                            </button>
                            <a type="button" class="btn btn-block" href="login"
                               style="width: 250px; color: #fff; background-color: #111343;">Entrar no portal de
                                vagas</a>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row visible-xs">
                    <br>
                    <div class="col-xs-4">
                        <i class="fa fa-arrow-left" style="font-size:18px; color:#111343;"></i>
                    </div>
                    <div class="col-xs-4 text-center">
                        Ver detalhes
                    </div>
                    <div class="col-xs-3 text-right">
                        <i class="fa fa-arrow-right" style="font-size:18px; color:#111343;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="table" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr class="active">
                        <th>Código</th>
                        <th>Abertura</th>
                        <th>Cargo/Função</th>
                        <th>Vagas</th>
                        <th>Cidade</th>
                        <?php if ($this->session->userdata('logado')): ?>
                            <th>Ações</th>
                        <?php else: ?>
                            <th>Ações</th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap modal -->
    <div class="modal fade" id="modal_vaga" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn btn-success" data-dismiss="modal" style="float:right;">Fechar
                    </button>
                    <h3 class="modal-title">Detalhes da vaga</h3>
                </div>
                <div class="modal-body form">
                    <form action="#" class="form-horizontal">
                        <div class="row">
                            <label class="control-label col-xs-6 col-md-3">Código da vaga:</label>
                            <div class="col-xs-6 col-md-1">
                                <p id="codigo" class="form-control-static"></p>
                            </div>
                            <label class="control-label col-xs-4 col-md-2">Cargo/Função:</label>
                            <div class="col-xs-8 col-md-5">
                                <p id="cargo_funcao" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Perfil profissional desejado:</label>
                            <div class="col-md-8">
                                <p id="perfil_profissional_desejado" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-6 col-md-3">Data de abertura:</label>
                            <div class="col-xs-6 col-md-1">
                                <p id="data_abertura" class="form-control-static"></p>
                            </div>
                            <label class="control-label col-xs-7 col-md-3">Quantidade de vagas:</label>
                            <div class="col-xs-5 col-md-1">
                                <p id="quantidade" class="form-control-static"></p>
                            </div>
                            <label class="control-label col-xs-6 col-md-2">Tipo de vínculo:</label>
                            <div class="col-xs-6 col-md-1">
                                <p id="tipo_vinculo" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-3 col-md-3">Cidade:</label>
                            <div class="col-xs-8 col-md-8">
                                <p id="cidade_vaga" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-6 col-md-3">Remuneração (R$):</label>
                            <div class="col-xs-6 col-md-8">
                                <p id="remuneracao" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Benefícios:</label>
                            <div class="col-md-8">
                                <p id="beneficios" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-3 col-md-3">Horário:</label>
                            <div class="col-xs-9 col-md-8">
                                <p id="horario_trabalho" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Observações:</label>
                            <div class="col-md-8">
                                <p id="observacoes_selecionador" class="form-control-static"></p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Fechar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="modal_candidato" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Candidatar-se a uma vaga</h3>
                </div>
                <div class="modal-body form">
                    <p>Para candidatar-se a uma vaga, é necessário cadastrar seu currículo e <strong>entrar no
                            portal.</strong></p>
                    <div class="row">
                        <div class="col-xs-12 col-md-6 text-center">
                            <h4>Já sou cadastrado</h4>
                            <a type="button" class="btn btn-primary btn-block" href="login">Entrar no portal</a>
                        </div>
                        <div class="col-xs-12 visible-xs">
                            <br>
                        </div>
                        <div class="col-xs-12 col-md-6 text-center">
                            <h4>Quero me cadastrar</h4>
                            <a type="button" class="btn btn-primary btn-block" href="#" id="url_cadastro">Cadastrar
                                currículo</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="modal_recuperacao_senha" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Recuperar senha</h3>
                </div>
                <div class="modal-body">
                    <form id="form_recuperacao_senha" action="#" class="form-horizontal" autocomplete="off">
                        <p>Digite abaixo o endereço de
                            e-mail que receberá o token para a redefinição da
                            senha.</p>
                        <p>Caso não se lembre do seu e-mail, digite seu CPF que recuperaremos o mesmo. Se não se lembrar
                            de seu e-mail digite seu CPF e acione o botão "necessito de ajuda", nós te enviaremos
                            instruções complementares.</p>
                        <div class="row form-group">
                            <label class="control-label col-md-3">Buscar por CPF</label>
                            <div class="col-md-4">
                                <input id="cpf" name="cpf" placeholder="CPF" class="form-control cpf" type="text">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="control-label col-md-2">E-mail <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input name="email" placeholder="E-mail" id="email" class="form-control" type="text">
                                <i id="alert_cpf"></i>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnSolicitarAjuda" type="button" class="btn btn-warning" onclick="solicitar_ajuda();"
                            style="float:left;">
                        <i class="fa fa-question-circle"></i> Necessito ajuda
                    </button>
                    <button id="btnRecuperarSenha" type="button" class="btn btn-warning" onclick="recuperar_senha();">
                        Enviar
                    </button>
                    <!--                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>-->
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>

<!--main content end-->

<footer class="footer">
    <p style="text-align: center; color: #151860; text-shadow: 1px 2px 4px rgba(0, 0, 0, .15);">Copyright &copy;
        PeopleNet In
        Education<br>
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
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>

    var table;

    $('.cpf').mask('000.000.000-00');

    $(document).ready(function () {

        table = $('#table').DataTable({
            'processing': true,
            'serverSide': true,
            'lengthChange': false,
            'searching': false,
            'ordering': false,
            'order': [['0', 'desc']],
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>',
                'sInfo': 'Mostrando de _START_ até _END_ de _TOTAL_ vagas'
            },
            'ajax': {
                'url': '<?php echo site_url('vagas/listar') ?>',
                'type': 'POST'
            },
            'columnDefs': [
                {
                    'className': 'text-center',
                    'targets': [1, 3]
                },
                {
                    'width': '50%',
                    'targets': [2, 4]
                },
                {
                    'className': 'text-center text-nowrap',
                    'targets': [-1]
                }
            ],
            'fnInfoCallback': function (oSettings, iStart, iEnd, iMax, iTotal, sPre) {
                return 'Mostrando de ' + iStart + ' até ' + iEnd + ' do total de ' + iTotal + ' vagas';
            }
        });

    });


    function visualizar_vaga(codigo) {
        $.ajax({
            'url': '<?php echo site_url('vagas/visualizarDetalhes') ?>',
            'type': 'GET',
            'dataType': 'json',
            'data': {'codigo': codigo},
            'success': function (json) {

                $.each(json, function (key, value) {
                    $('#' + key).html(value);
                });

                $('#modal_vaga').modal('show');
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function candidatar(codigo) {
        $('#url_cadastro').prop('href', '<?php echo site_url('vagas/novoCandidato'); ?>/' + codigo);
        $('#modal_candidato').modal('show');
    }


    $('#modal_recuperacao_senha').on('show.bs.modal', function (e) {
        $('#form_recuperacao_senha')[0].reset();
    })

    $('#cpf').on('change', function () {
        var cpf = this.value;
        $.ajax({
            'url': '<?php echo site_url('vagas/verificarCPF') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {'cpf': cpf},
            'beforeSend': function () {
                if (cpf.length === 0) {
                    $('#alert_cpf').removeClass('text-danger').html('');
                    return false;
                }
                $('#cpf').prop('disabled', true);
            },
            'success': function (json) {
                if (json.email) {
                    $('#email').val(json.email);
                    $('#alert_cpf').removeClass('text-danger').html('');
                } else if (json.status) {
                    $('#email').val('');
                    $('#alert_cpf').addClass('text-danger').html('Nenhum cadastro de e-mail encontrado com o CPF acima.');
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            },
            'complete': function () {
                $('#cpf').prop('disabled', false);
            }
        });
    });


    function solicitar_ajuda() {
        $.ajax({
            'url': '<?php echo site_url('vagas/solicitarAjuda') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': $('#form_recuperacao_senha').serialize(),
            'beforeSend': function () {
                $('#btnSolicitarAjuda').prop('disabled', true);
            },
            'success': function (json) {
                if (json.status) {
                    alert('Um e-mail foi enviado a cada selecionador.');
                    $('#modal_candidato').modal('hide');
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error send e-mail from ajax');
            },
            'complete': function () {
                $('#btnSolicitarAjuda').prop('disabled', false);
            }
        });
    }


    function recuperar_senha() {
        $.ajax({
            'url': '<?php echo site_url('vagas/recuperarSenhaCandidato') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': $('#form_recuperacao_senha').serialize(),
            'beforeSend': function () {
                $('#btnRecuperarSenha').text('Enviando...').prop('disabled', true);
            },
            'success': function (json) {
                if (json.status) {
                    alert('E-mail enviado com sucesso.');
                    $('#modal_candidato').modal('hide');
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error send e-mail from ajax');
            },
            'complete': function () {
                $('#btnRecuperarSenha').text('Enviar').prop('disabled', false);
            }
        });
    }

</script>
</body>
</html>
