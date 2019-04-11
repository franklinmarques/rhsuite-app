<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório Recrutamento</title>
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
    <table>
        <tr>
            <td>
                <img src="<?= base_url('imagens/usuarios/LOGOAME-TP.png') ?>" align="left"
                     style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
            </td>
            <td style="vertical-align: top;">
                <p>
                    <img src="<?= base_url('imagens/usuarios/Descricao_AME.png') ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    <!--<span style="font-weight: bold;">Associação dos Amigos Metroviários dos Excepcionais - AME</span><br>
                            <span style="font-size: small;">Rua Serra de Botucatu, 1.197 - São Paulo, Brasil ─ CEP 03317-001 ─ Tel.: 2360-8900</span><br>
                            <span style="font-size: small;">Site: www.ame-sp.org.br ─ e-mail: ame@ame-sp.org.br</span>-->
                </p>
            </td>
        </tr>
    </table>
    <table class="table table-condensed recrutamento">
        <thead>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="3" style="padding-bottom: 12px;">
                <h3 class="text-center" style="font-weight: bold;">RELATÓRIO DE ENTREVISTA POR COMPETÊNCIAS</h3>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style='border-top: 5px solid #ddd;'>
            <td nowrap>
                <h6><strong>Teste aplicado: </strong><?= $teste->modelo ?></h6>
                <h6><strong>Data atual: </strong><?= date('d/m/Y') ?></h6>
            </td>
            <td nowrap>
                <h6><strong>Data início teste: </strong><?= $teste->data_inicio ?></h6>
                <h6><strong>Data término teste: </strong><?= $teste->data_termino ?></h6>
            </td>
        </tr>
        <tr style='border-top: 5px solid #ddd;'>
            <td><strong>Requisitante:</strong> <?= $teste->requisitante ?></td>
            <td><strong>Cargo/função alvo:</strong> <?= $teste->cargo ?></td>
        </tr>
        <tr style='border-top: 1px solid #ddd;'>
            <?php if ($teste->tipo === 'I'): ?>
                <td colspan="2"><strong>Candidato avaliado:</strong> <?= $teste->candidato ?></td>
            <?php else: ?>
                <td><strong>Candidato avaliado:</strong> <?= $teste->candidato ?></td>
                <?php if ($teste->tipo === 'D'): ?>
                    <td><strong>Desempenho:</strong> <span style="font-size: 15px;"><?= $teste->total ?>
                            % (<?= $teste->caracteres ?></span> caracteres em <span
                                style="font-size: 15px;"><?= $teste->minutos ?></span> minutos)
                    </td>
                <?php else: ?>
                    <td><strong>Percentual de desempenho:</strong> <span
                                style="font-size: 15px;"><?= $teste->total ?>%</span></td>
                <?php endif; ?>
            <?php endif; ?>
        </tr>
        </tbody>
    </table>

    <!--<div class="table-responsive">-->
    <table id="table" class="table table-striped table-bordered resultado" cellspacing="0" width="100%"
           style="border-radius: 0 !important;">
        <thead>
        <tr class="active">
            <th width="45%">Pergunta</th>
            <th class="text-center" width="45%">Resposta</th>
            <th class="text-center" width="10%" nowrap>Nota</th>
        </tr>
        </thead>
        <tbody>
        <form id="form" method="post" autocomplete="off">
            <?php foreach ($competencias as $competencia): ?>
                <tr>
                    <td colspan="3" class="success"><?= $competencia[0]->competencia ?? ''; ?></td>
                </tr>
                <?php foreach ($competencia as $k => $pergunta): ?>
                    <tr>
                        <td><strong><?php echo $pergunta->pergunta; ?></strong></td>
                        <td style="word-break: break-all;"><?php echo $pergunta->resposta; ?></td>
                        <td class="text-center" nowrap><?php echo $pergunta->nota; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </form>
        </tbody>
    </table>
    <!--</div>-->
</div>
</body>
</html>