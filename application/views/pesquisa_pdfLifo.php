<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Avaliação de Personalidade</title>
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
    <htmlpageheader name="myHeader">
        <div style="color: #000;">
            <table>
                <tr>
                    <td>
                        <img src="<?= base_url($foto) ?>" align="left"
                             style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                    </td>
                    <td style="vertical-align: top;">
                        <p>
                            <img src="<?= base_url($foto_descricao) ?>" align="left"
                                 style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                        </p>
                    </td>
                </tr>
            </table>
            <table class="table table-condensed pesquisa">
                <thead>
                <tr style='border-top: 5px solid #ddd;'>
                    <th colspan="2">
                        <h2 class="text-center" style="margin-top: 10px;">AVALIAÇÃO DE PERSONALIDADE</h2>
                    </th>
                </tr>
                </thead>
                <tbody>
                </tr>
                <tr style='border-top: 5px solid #ddd;'>
                    <td nowrap>
                        <h5><strong>Teste aplicado: </strong><?= $teste->modelo ?></h5>
                        <h5><strong>Data atual: </strong><?= date('d/m/Y') ?></h5>
                    </td>
                    <td nowrap>
                        <h5><strong>Data início teste: </strong><?= $teste->data_inicio ?></h5>
                        <h5><strong>Data término teste: </strong><?= $teste->data_termino ?></h5>
                    </td>
                </tr>
                <tr style='border-top: 5px solid #ddd; border-bottom: 5px solid #ddd;'>
                    <td><strong>Profissional avaliado:</strong> <?= $teste->candidato ?></td>
                    <td><strong>Cargo/função alvo:</strong> <?= $teste->cargo_funcao ?></td>
                </tr>
                </tbody>
            </table>
    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

    <h3>Traços de Personalidade Mapeados no Avaliado</h3>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered laudo" cellspacing="0"
                   width="100%"
                   style="border-radius: 0 !important;">
                <thead>
                <tr class="success">
                    <th colspan="2" class="text-center">
                        <h3><strong>LAUDO DA AVALIAÇÃO</strong></h3>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr class="active">
                    <th colspan="2" tyle="vertical-align: middle;">Perfil - comportamentos majoritário
                    </th>
                </tr>
                <tr>
                    <?php if (strlen($laudoPerfil->estilo_personalidade_majoritario) > 0): ?>
                        <td colspan="2"><?= $laudoPerfil->estilo_personalidade_majoritario; ?></td>
                    <?php else: ?>
                        <td colspan="2"><span class="text-muted">Nenhum comportamento apresentado</span>
                        </td>
                    <?php endif; ?>
                </tr>
                <tr class="active">
                    <th colspan="2" tyle="vertical-align: middle;">Perfil - comportamentos secundário
                    </th>
                </tr>
                <tr>
                    <?php if (strlen($laudoPerfil->estilo_personalidade_secundario) > 0): ?>
                        <td colspan="2"><?= $laudoPerfil->estilo_personalidade_secundario; ?></td>
                    <?php else: ?>
                        <td colspan="2"><span class="text-muted">Nenhum comportamento apresentado</span>
                        </td>
                    <?php endif; ?>
                </tr>
                <tr class="active">
                    <th style="vertical-align: middle;">Posicionamentos possíveis em situações normais
                    </th>
                    <th style="vertical-align: middle;">Posicionamentos possíveis em situações de
                        pressão/estresse
                    </th>
                </tr>
                <tr>
                    <?php if (strlen($laudoPerfil->comportamentos_normais) > 0): ?>
                        <td><?= $laudoPerfil->comportamentos_normais; ?></td>
                    <?php else: ?>
                        <td><span class="text-muted">Nenhum posicionamento apresentado</span></td>
                    <?php endif; ?>
                    <?php if (strlen($laudoPerfil->comportamentos_estresse) > 0): ?>
                        <td><?= $laudoPerfil->comportamentos_estresse; ?></td>
                    <?php else: ?>
                        <td><span class="text-muted">Nenhum posicionamento apresentado</span></td>
                    <?php endif; ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
</body>
</html>