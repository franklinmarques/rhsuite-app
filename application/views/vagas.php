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
    <div style="width: 100%; max-width: 370px; margin: 0 auto;">
        <div align="center">
            <img src="<?php echo $logo; ?>" style="width: auto; max-height: 100px; margin-bottom: 3%;">
            <h4 style="color: #111343; text-shadow: 1px 2px 4px rgba(0, 0, 0, .15);">
                <strong><?php echo $cabecalho; ?></strong></h4>
        </div>
    </div>
    <div style="width: 100%; max-width: 60%; margin: 0 auto;">
        <div align="center">
            <h4>Caro candidato, seja bem-vindo ao nosso painel de vagas.</h4>
            <h5>Caso algumas das vagas seja de seu interesse, basta acionar o botão "Candidatar-se!" que você
                será
                automaticamente incluído no processo seletivo da mesma.</h5>
            <div class="controls">
                <a type="button" class="btn" href="login"
                   style="width: 250px; color: #fff; background-color: #111343;">Entrar no portal</a>
            </div>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr class="active">
                <th>Código</th>
                <th>Abertura</th>
                <th>Cargo/Função</th>
                <th nowrap>N&ordm; vagas</th>
                <th>Cidade</th>
                <th>Bairro</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap modal -->
    <div class="modal fade" id="modal_vaga" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Detalhes da vaga</h3>
                </div>
                <div class="modal-body form">
                    <form action="#" class="form-horizontal">
                        <div class="row">
                            <label class="control-label col-md-3">Código da vaga</label>
                            <div class="col-md-8">
                                <p id="codigo" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Cargo/Função</label>
                            <div class="col-md-8">
                                <p id="cargo_funcao" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Perfil profissional desejado</label>
                            <div class="col-md-8">
                                <p id="perfil_profissional_desejado" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Data de abertura</label>
                            <div class="col-md-8">
                                <p id="data_abertura" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Quantidade de vagas</label>
                            <div class="col-md-8">
                                <p id="quantidade" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Cidade da vaga</label>
                            <div class="col-md-8">
                                <p id="cidade_vaga" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Bairro da vaga</label>
                            <div class="col-md-8">
                                <p id="bairro_vaga" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Tipo de vínculo</label>
                            <div class="col-md-8">
                                <p id="tipo_vinculo" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Remuneração (R$)</label>
                            <div class="col-md-8">
                                <p id="remuneracao" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Benefícios</label>
                            <div class="col-md-8">
                                <p id="beneficios" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Horário de trabalho</label>
                            <div class="col-md-8">
                                <p id="horario_trabalho" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Formação mínima</label>
                            <div class="col-md-8">
                                <p id="formacao_minima" class="form-control-static"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-md-3">Contato do selecionador</label>
                            <div class="col-md-8">
                                <p id="contato_selecionador" class="form-control-static"></p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
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
                    <p>Para candidatar-se a uma vaga, é necessário cadastrar seu currículo e fazer login no sistema.</p>
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <h4>Já sou cadastrado</h4>
                            <a type="button" class="btn btn-primary btn-block" href="login">Entrar no portal</a>
                        </div>
                        <div class="col-md-6 text-center">
                            <h4>Quero me candidatar</h4>
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

<script>

    var table;

    $(document).ready(function () {

        table = $('#table').DataTable({
            'processing': true,
            'serverSide': true,
            'lengthChange': false,
            'searching': false,
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>',
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ vagas"
            },
            'ajax': {
                'url': '<?php echo site_url('vagas/listar/') ?>',
                'type': 'POST'
            },
            'columnDefs': [
                {
                    'className': 'text-center',
                    'targets': [1, 3]
                },
                {
                    'width': '34%',
                    'targets': [2]
                },
                {
                    'width': '33%',
                    'targets': [4, 5]
                },
                {
                    'className': 'text-center text-nowrap',
                    'orderable': false,
                    'searchable': false,
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
            'url': '<?php echo site_url('vagas/visualizarDetalhes/') ?>',
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
        $('#url_cadastro').prop('href', '<?php echo site_url('vagas/cadastrarCurriculo'); ?>/' + codigo);
        $('#modal_candidato').modal('show');
    }


</script>
</body>
</html>
