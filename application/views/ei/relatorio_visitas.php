<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório de Unidades Visitadas</title>
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

    <script src="<?= base_url("assets/js/jquery.js"); ?>"></script>
    <style>
        @page {
            margin: 40px 20px;
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
        <table id="table" class="table table-condensed">
            <thead>
            <tr>
                <td style="width: auto;">
                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="width: 100%; vertical-align: top;">
                    <p>
                        <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    </p>
                </td>
                <?php if ($is_pdf == false): ?>
                    <td nowrap>
                        <div class="form-inline">
                            <label class="control-label">Ano:</label>
                            <input type="hidden" name="mes" value="<?= $mes ?>" autocomplete="off">
                            <input type="text" name="ano" value="<?= $ano ?>" class="form-control input-sm text-center"
                                   style="width:60px;" autocomplete="off">
                        </div>
                    </td>
                    <td nowrap>
                        <a id="pdf" class="btn btn-sm btn-info"
                           href="<?= site_url('ei/relatorios/pdfUnidadeVisitada/q?') . $query_string; ?>"
                           title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                    </td>
                <?php endif; ?>
            </tr>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="<?= $is_pdf == false ? '4' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
                    <?php if ($is_pdf == false): ?>
                        <h3 class="text-center" style="font-weight: bold;">RELATÓRIO DE UNIDADES
                            VISITADAS - <?= $mes_ano ?></h3>
                    <?php else: ?>
                        <h2 class="text-center" style="font-weight: bold;">RELATÓRIO DE UNIDADES
                            VISITADAS - <?= $mes_ano ?></h2>
                    <?php endif; ?>
                </th>
            </tr>
            </thead>
        </table>
    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

    <div>
        <table class="table unidades_visitadas table-bordered table-condensed">
            <thead>
            <tr class="active">
                <th>Data visita</th>
                <th>Unidade visitada</th>
                <th>Supervisor visitante</th>
                <th>Prestadores de serviços tratados</th>
                <th>Motivo visita</th>
                <th>Gastos materiais</th>
                <th>Sumário da visita</th>
                <th>Observações</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($visitas)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">Nenhum registro encontrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($visitas as $visita): ?>
                    <tr>
                        <td class="text-center"><?= $visita->data_visita; ?></td>
                        <td><?= $visita->escola; ?></td>
                        <td><?= $visita->supervisor_visitante; ?></td>
                        <td><?= $visita->prestadores_servicos_tratados; ?></td>
                        <td><?= $visita->motivo_visita; ?></td>
                        <td class="text-right"><?= $visita->gastos_materiais; ?></td>
                        <td><?= $visita->sumario_visita; ?></td>
                        <td><?= $visita->observacoes; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

    </div>

</div>

<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>
    $('[name="ano"]').mask('0000');

    $('[name="mes"], [name="ano"]').on('change', function () {
        var q = new Array();
        q.push("id_unidade_visitada=" + '<?= $id ?>');
        q.push("mes=" + $('[name="mes"]').val());
        q.push("ano=" + $('[name="ano"]').val());

        window.open('<?php echo site_url('ei/relatorios/unidadeVisitada'); ?>/q?' + q.join('&'), '_self');
    });
</script>
</body>
</html>