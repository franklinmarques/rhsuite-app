<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Gerenciar Ordem de Serviço de Alunos</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
    <!--WARNING: Respond.js doesn't work if you view the page via file://-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?= base_url("assets/js/jquery.js"); ?>"></script>

    <style>
        .form-group {
            margin-bottom: 0;
        }
    </style>
</head>
<body style="color: #000;">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10">
            <h3 class="text-primary"><strong>Perfil do candidato</strong></h3>
        </div>
        <div class="col-sm-2 text-right">
            <br>
            <button class="btn btn-default" onclick="javascript:window.close()"><i
                        class="glyphicon glyphicon-remove"></i> Fechar
            </button>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">

            <ul class="nav nav-tabs" role="tablist" style="font-size: 15px; font-weight: bolder;">
                <li role="presentation" class="active">
                    <a href="#dados_cadastrais" aria-controls="dados_cadastrais" role="tab" data-toggle="tab">Dados
                        cadastrais</a>
                </li>
                <li role="presentation">
                    <a href="#formacao" aria-controls="formacao" role="tab" data-toggle="tab">Formação</a>
                </li>
                <li role="presentation">
                    <a href="#historico_profissional" aria-controls="historico_profissional" role="tab"
                       data-toggle="tab">Histórico profissional</a>
                </li>
                <li role="presentation">
                    <a href="#curriculo" aria-controls="curriculo" role="tab" data-toggle="tab">Currículo</a>
                </li>
            </ul>

            <br/>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="dados_cadastrais">
                    <div class="form-horizontal">
                        <fieldset>
                            <legend>Dados pessoais</legend>
                            <div class="row">
                                <div class="col-sm-4 visible-xs-block">
                                    <?php if ($foto): ?>
                                        <img src="<?= base_url('imagens/usuarios/' . $foto) ?>" alt="<?= $foto ?>"
                                             class="img-thumbnail">
                                    <?php else: ?>
                                        <img src="<?= base_url('imagens/usuarios/Sem+imagem.png') ?>" alt="Sem imagem"
                                             class="img-thumbnail">
                                    <?php endif; ?>
                                    <hr>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Nome:</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"><?= $nome ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Data de nascimento:</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"><?= $data_nascimento ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Sexo:</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"><?= $sexo ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Estado civil:</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"><?= $estado_civil ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Telefone:</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"><?= $telefone ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">E-mail:</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"><?= $email ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Nome da mãe:</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"><?= $nome_mae ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Nome do pai:</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"><?= $nome_pai ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Status:</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"><?= $status ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">CPF:</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"><?= $cpf ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">RG:</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"><?= $rg ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">PIS:</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"><?= $pis ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">CEP:</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static"><?= $cep ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 hidden-xs text-right">
                                    <?php if ($foto): ?>
                                        <img src="<?= base_url('imagens/usuarios/' . $foto) ?>" alt="<?= $foto ?>"
                                             class="img-thumbnail">
                                    <?php else: ?>
                                        <img src="<?= base_url('imagens/usuarios/Sem+imagem.png') ?>" alt="Sem imagem"
                                             class="img-thumbnail">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <legend>Endereço</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Logradouro:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $logradouro ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Número:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $numero ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Complemento:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $complemento ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Bairro:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $bairro ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Cidade:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $cidade ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Estado:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $estado ?></p>
                                </div>
                            </div>
                            <legend>Dados complementares</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Escolaridade:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $escolaridade ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Deficiência:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $deficiencia ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fonte contratação:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $fonte_contratacao ?></p>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="formacao">
                    <div class="form-horizontal">
                        <fieldset>
                            <legend>Ensino Fundamental</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instituição:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[0]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ano de conclusão:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[0]->ano_conclusao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Completo:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[0]->concluido ? 'Sim' : 'Não'; ?></p>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Ensino Médio</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Curso 1:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[1]->curso; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instituição:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[1]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipo de curso:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[1]->tipo == 'N' ? 'Normal' : ($formacao[1]->tipo == 'T' ? 'Técnico' : ''); ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ano de conclusão:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[1]->ano_conclusao; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Curso 2:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[1]->curso; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instituição:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[2]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipo de curso:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[2]->tipo == 'N' ? 'Normal' : ($formacao[2]->tipo == 'T' ? 'Técnico' : ''); ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ano de conclusão:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[2]->ano_conclusao; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Curso 3:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[3]->curso; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instituição:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[3]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipo de curso:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[3]->tipo == 'N' ? 'Normal' : ($formacao[3]->tipo == 'T' ? 'Técnico' : ''); ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ano de conclusão:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[3]->ano_conclusao; ?></p>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Graduação</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Curso 1:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[4]->curso; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instituição:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[4]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Título de graduação:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[4]->tipo == 'B' ? 'Bacharel' : ($formacao[4]->tipo == 'T' ? 'Tecnólogo' : ''); ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ano de conclusão:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[4]->ano_conclusao; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Curso 2:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[5]->curso; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instituição:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[5]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Título de graduação:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[5]->tipo == 'B' ? 'Bacharel' : ($formacao[5]->tipo == 'T' ? 'Tecnólogo' : ''); ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ano de conclusão:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[5]->ano_conclusao; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Curso 3:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[6]->curso; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instituição:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[6]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Título de graduação:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[6]->tipo == 'B' ? 'Bacharel' : ($formacao[6]->tipo == 'T' ? 'Tecnólogo' : ''); ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ano de conclusão:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[6]->ano_conclusao; ?></p>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Pós-Graduação</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Curso 1:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[7]->curso; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instituição:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[7]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ano de conclusão:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[7]->ano_conclusao; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Curso 2:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[8]->curso; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instituição:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[8]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ano de conclusão:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[8]->ano_conclusao; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Curso 3:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[9]->curso; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instituição:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[9]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ano de conclusão:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[9]->ano_conclusao; ?></p>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Mestrado</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Curso 1:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[10]->curso; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instituição:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[10]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ano de conclusão:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[10]->ano_conclusao; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Curso 2:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[11]->curso; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instituição:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[11]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ano de conclusão:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[11]->ano_conclusao; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Curso 3:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[12]->curso; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instituição:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[12]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ano de conclusão:</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?= $formacao[12]->ano_conclusao; ?></p>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="historico_profissional">
                    <div class="form-horizontal">
                        <fieldset>
                            <legend>Experiência profissional</legend>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Empresa 1:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[0]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[0]->data_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[0]->data_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[0]->cargo_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[0]->cargo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de entrada (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[0]->salario_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de saída (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[0]->salario_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Razão da saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[0]->motivo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Realizações:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[0]->realizacoes; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Empresa 2:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[1]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[1]->data_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[1]->data_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[1]->cargo_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[1]->cargo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de entrada (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[1]->salario_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de saída (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[1]->salario_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Razão da saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[1]->motivo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Realizações:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[1]->realizacoes; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Empresa 3:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[2]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[2]->data_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[2]->data_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[2]->cargo_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[2]->cargo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de entrada (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[2]->salario_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de saída (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[2]->salario_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Razão da saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[2]->motivo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Realizações:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[2]->realizacoes; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Empresa 4:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[3]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[3]->data_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[3]->data_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[3]->cargo_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[3]->cargo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de entrada (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[3]->salario_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de saída (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[3]->salario_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Razão da saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[3]->motivo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Realizações:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[3]->realizacoes; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Empresa 5:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[4]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[4]->data_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[4]->data_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[4]->cargo_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[4]->cargo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de entrada (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[4]->salario_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de saída (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[4]->salario_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Razão da saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[4]->motivo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Realizações:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[4]->realizacoes; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Empresa 6:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[5]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[5]->data_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[5]->data_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[5]->cargo_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[5]->cargo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de entrada (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[5]->salario_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de saída (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[5]->salario_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Razão da saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[5]->motivo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Realizações:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[5]->realizacoes; ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Empresa 7:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[6]->instituicao; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[6]->data_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[6]->data_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de entrada:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[6]->cargo_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo de saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[6]->cargo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de entrada (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[6]->salario_entrada; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Salário de saída (R$):</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[6]->salario_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Razão da saída:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[6]->motivo_saida; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Realizações:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?= $historicoProfissional[6]->realizacoes; ?></p>
                                </div>
                            </div>
                        </fieldset>

                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="curriculo">
                    <div class="row">
                        <div class="col-sm-12">
                            <iframe src="https://docs.google.com/gview?embedded=true&url=<?php echo base_url('arquivos/curriculos/' . convert_accented_characters($arquivo_curriculo)); ?>"
                                    width="100%" height="450px" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<div id="script_js" style="display: none;"></div>
<script src="<?= base_url("assets/bs3/js/bootstrap.min.js"); ?>"></script>

</body>
</html>
