<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório PDI</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">

    <!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
    <!--WARNING: Respond.js doesn't work if you view the page via file://-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
</head>
<body style="color: #000;">
<div class="container-fluid">
    <table class="table table-condensed pesquisa">
        <thead>
        <?php if (isset($avaliado)): ?>
            <tr>
                <th style="width: 100%; vertical-align: top;" colspan="4">
                    <div class="row">
                        <div class="col-sm-12">
                            <img src="<?= base_url('imagens/usuarios/LOGOAME-TP.png') ?>" align="left"
                                 style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                            <p class="text-left">
                                <img src="<?= base_url('imagens/usuarios/Descricao_AME.png') ?>"
                                     align="left"
                                     style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                            </p>
                        </div>
                    </div>
                </th>
            </tr>
            <tr>
                <th colspan="4">
                    <h2 class="text-center">PESQUISA DE PERFIL PROFISSIONAL</h2>
                </th>
            </tr>
        <?php else: ?>
            <tr>
                <th style="width: 100%; vertical-align: top;" colspan="3">
                    <div class="row">
                        <div class="col-sm-12">
                            <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                                 style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                            <p class="text-left">
                                <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>"
                                     align="left"
                                     style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                            </p>
                        </div>
                    </div>
                </th>
            </tr>
            <tr>
                <th colspan="3">
                    <h2 class="text-center">PESQUISA DE CLIMA ORGANIZACIONAL</h2>
                </th>
            </tr>
        <?php endif; ?>
        </thead>
        <tbody>
        <tr style='border-top: 5px solid #ddd;'>
            <td>
                <h6><strong>Avaliação: </strong><?= $pesquisa->nome ?></h6>
                <h6><strong>Data atual: </strong><?= date('d/m/Y') ?></h6>
            </td>
            <td colspan="<?= isset($avaliado) ? '3' : '2' ?>">
                <h6><strong>Data início pesquisa: </strong><?= $pesquisa->data_inicio ?></h6>
                <h6><strong>Data término pesquisa: </strong><?= $pesquisa->data_termino ?></h6>
            </td>
        </tr>

        <?php if (isset($avaliado)): ?>
            <tr style='border-top: 5px solid #ddd;'>
                <th>Colaborador alvo da pesquisa</th>
                <th>Função</th>
                <th>Depto/área/setor</th>
                <th>Data de início das atividades</th>
            </tr>
            <tr<?= (!($is_pdf and $omitirAvaliadores)) ? " style='border-bottom: 5px solid #ddd;'" : '' ?>>
                <td><?= $avaliado->nome ?></td>
                <td><?= $avaliado->funcao ?></td>
                <td><?= $avaliado->depto ?></td>
                <td><?= $avaliado->data_admissao ?></td>
            </tr>

            <?php if (isset($selecionado)): ?>
                <tr style='border-top: 5px solid #ddd;'>
                    <th>Colaborador pesquisado</th>
                    <th>Função</th>
                    <th>Depto/área/setor</th>
                    <th>Data de início das atividades</th>
                </tr>
                <tr>
                    <td id="avaliador"><?= $selecionado->nome ?></td>
                    <td id="funcao"><?= $selecionado->funcao ?></td>
                    <td id="depto"><?= $selecionado->depto ?></td>
                    <td id="data_admissao"><?= $selecionado->data_admissao ?></td>
                </tr>
            <?php elseif (!($is_pdf and $omitirAvaliadores)): ?>
                <tr style='border-top: 5px solid #ddd;'>
                    <th colspan="4">Colaboradores pesquisados</th>
                </tr>
                <tr style='border-bottom: 5px solid #ddd;'>
                    <td colspan="4">
                        <ol>
                            <?php foreach ($avaliadores as $avaliador): ?>
                                <li><?= $avaliador ?></li>
                            <?php endforeach; ?>
                        </ol>
                    </td>
                </tr>
            <?php endif; ?>
        <?php else: ?>
            <tr style='border-top: 5px solid #ddd;'>
                <td colspan="3">
                    <h6><strong>Departamentos/áreas/setores participantes: </strong></h6>
                </td>
            </tr>
            <tr>
                <td><h6>Departamentos: </h6></td>
                <td colspan="2"><h6><?= implode(', ', $depto) ?></h6></td>
            </tr>
            <tr>
                <td><h6>Áreas: </h6></td>
                <td colspan="2"><h6><?= implode(', ', $area) ?></h6></td>
            </tr>
            <tr style='border-bottom: 5px solid #ddd;'>
                <td><h6>Setores: </h6></td>
                <td colspan="2"><h6><?= implode(', ', $setor) ?></h6></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <?php if (isset($avaliado)): ?>
        <!--<div class="table-responsive">-->
        <table id="table" class="table table-striped table-bordered resultado" cellspacing="0" width="100%"
               style="border-radius: 0 !important;">
            <thead>
            <tr class="active">
                <th>Critérios de pesquisa</th>
                <th class="text-center">Peso</th>
                <?php if (isset($selecionado)): ?>
                    <th class="text-center">Resposta</th>
                <?php else: ?>
                    <th class="text-center">Consolidado (abs)</th>
                    <th class="text-center">Consolidado (%)</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php $pergunta = ''; ?>
            <?php foreach ($data as $coluna): ?>
                <?php if ($pergunta !== $coluna[0]): ?>
                    <tr class="success">
                        <th colspan="<?= isset($selecionado) ? '3' : '4' ?>"><?= $coluna[0] ?></th>
                    </tr>
                    <?php $pergunta = $coluna[0]; ?>
                <?php endif; ?>
                <tr>
                    <td><?= $coluna[1] ?></td>
                    <td class="text-right"><?= $coluna[2] ?></td>
                    <?php if (isset($selecionado)): ?>
                        <td class="text-right"><?= $coluna[3] ?></td>
                    <?php else: ?>
                        <td class="text-right"><?= $coluna[4] ?></td>
                        <td class="text-right"><?= ($coluna[5] === null ? '' : $coluna[5] . '%') ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
        <!--</div>-->

    <?php else: ?>

        <?php if ($pesquisa->depto): ?>
            <div class="row">
                <div class="col col-xs-12">Filtrado por departamento: <?= $pesquisa->depto ?></div>
            </div>
        <?php endif; ?>
        <?php if ($pesquisa->area): ?>
            <div class="row">
                <div class="col col-xs-12">Filtrado por área: <?= $pesquisa->area ?></div>
            </div>
        <?php endif; ?>
        <?php if ($pesquisa->setor): ?>
            <div class="row">
                <div class="col col-xs-12">Filtrado por setor: <?= $pesquisa->setor ?></div>
            </div>
        <?php endif; ?>
        <br>

        <!--<div class="table-responsive">-->
        <table id="table" class="table table-striped table-bordered resultado" cellspacing="0" width="100%"
               style="border-radius: 0 !important;">
            <thead>
            <tr class="active">
                <th>Critérios de pesquisa</th>
                <?php foreach ($alternativas as $alternativa): ?>
                    <th nowrap class="text-center"><?= $alternativa->alternativa ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php $categoria = ''; ?>
            <?php foreach ($data as $coluna): ?>
                <?php if ($categoria !== $coluna[0]): ?>
                    <tr class="success">
                        <th colspan="<?= count($alternativas) + 1 ?>"><?= $coluna[0] ?></th>
                    </tr>
                    <?php $categoria = $coluna[0]; ?>
                <?php endif; ?>
                <tr>
                    <td><?= $coluna[1] ?></td>
                    <?php foreach ($alternativas as $k => $alternativa): ?>
                        <td class="text-right"><?= ($coluna[$k + 2] === null ? '' : $coluna[$k + 2] . '%') ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif; ?>

    <div style="border-top: 5px solid #ddd;"></div>
    <!--</div>-->
</div>
</body>
</html>