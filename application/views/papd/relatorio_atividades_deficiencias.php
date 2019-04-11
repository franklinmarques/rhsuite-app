<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Controle de Frequência Individual</title>
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
    <br>
    <?php if ($is_pdf == false): ?>
        <div class="row">
            <div class="col-sm-10">
                <img src="<?= base_url($foto) ?>" align="left"
                     style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                <p class="text-left">
                    <img src="<?= base_url($foto_descricao) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                </p>
            </div>
            <div class="col-sm-2 text-right">
                <?php if ($is_pdf == false): ?>
                    <a id="pdf" class="btn btn-sm btn-danger"
                       href="<?= site_url('papd/relatorios/pdfAtividades_deficiencias/'); ?>" title="Exportar PDF"><i
                                class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                    <div class="checkbox">
                        <label>
                            <input name="valor" type="checkbox" value="1" checked="checked"> Mostrar valores
                        </label>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <table>
            <tr>
                <td>
                    <img src="<?= base_url('imagens/usuarios/LOGOAME-TP.png') ?>" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="vertical-align: top;">
                    <p>
                        <img src="<?= base_url('imagens/usuarios/Descricao_AME.png') ?>" align="left" style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                        <!--<span style="font-weight: bold;">Associação dos Amigos Metroviários dos Excepcionais - AME</span><br>
                        <span style="font-size: small;">Rua Serra de Botucatu, 1.197 - São Paulo, Brasil ─ CEP 03317-001 ─ Tel.: 2360-8900</span><br>
                        <span style="font-size: small;">Site: www.ame-sp.org.br ─ e-mail: ame@ame-sp.org.br</span>-->
                    </p>
                </td>
            </tr>
        </table>
    <?php endif; ?>
    <table id="table" class="table table-condensed">
        <thead>
        <tr style='border-top: 5px solid #ddd; border-bottom: 3px solid #ddd;'>
            <th colspan="3">
                <?php if ($is_pdf == false): ?>
                    <h2 class="text-center"><strong>RELATÓRIO DE ATIVIDADES E DEFICIÊNCIAS</strong></h2>
                <?php else: ?>
                    <h3 class="text-center"><strong>RELATÓRIO DE ATIVIDADES E DEFICIÊNCIAS</strong></h3>
                <?php endif; ?>
            </th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <br/>
    <!--<div class="table-responsive">-->
    <div class="row">
        <div class="col-md-5">
            <table id="table1" class="table table-bordered table-condensed deficiencias" width="100%">
                <thead>
                <tr class="active">
                    <th class="text-center">Hipótese Diagnóstica</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($deficiencias as $deficiencia): ?>
                    <tr>
                        <td><?= $deficiencia->nome ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-7">
            <table id="table2" class="table table-bordered table-condensed atividades" width="100%">
                <thead>
                <tr class="active">
                    <th class="text-center" style="width: 70%;">Atividade</th>
                    <?php if ($is_pdf == false or $is_valor): ?>
                        <th class="text-center valor">Valor (R$)</th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($atividades as $atividade): ?>
                    <tr>
                        <td><?= $atividade->nome ?></td>
                        <?php if ($is_pdf == false or $is_valor): ?>
                            <td class="text-right valor"><?= $atividade->valor ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--</div>-->
</div>
<script>
    $(document).ready(function () {
        setPdf_atributes();
    });

    $('[name="valor"]').on('change', function () {
        setPdf_atributes();
    });

    function setPdf_atributes() {
        var search = [];
        if ($('[name="valor"]').is(':checked')) {
            search.push('?valor=' + $('[name="valor"]').val());
            $('.valor').show();
        } else {
            $('.valor').hide();
        }
        $('#pdf').prop('href', "<?= site_url('papd/relatorios/pdfAtividades_deficiencias'); ?>/" + search.join('&'));
    }
</script>
</body>
</html>