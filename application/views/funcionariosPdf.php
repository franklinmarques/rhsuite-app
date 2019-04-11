<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Quadro de Colaboradores</title>
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
                <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                     style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
            </td>
            <td style="vertical-align: top;">
                <p>
                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    <!--<span style="font-weight: bold;">Associação dos Amigos Metroviários dos Excepcionais - AME</span><br>
                    <span style="font-size: small;">Rua Serra de Botucatu, 1.197 - São Paulo, Brasil ─ CEP 03317-001 ─ Tel.: 2360-8900</span><br>
                    <span style="font-size: small;">Site: www.ame-sp.org.br ─ e-mail: ame@ame-sp.org.br</span>-->
                </p>
            </td>
        </tr>
    </table>
    <table id="table" class="table table-bordered table-condensed">
        <thead>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="6" style="padding-bottom: 8px; text-align: center;">
                <h3 style="font-weight: bold;">QUADRO DE COLABORADORES</h3>
            </th>
        </tr>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="3" class="text-nowrap" style="padding-bottom: 8px; border-right-width: 0px;">
                <h6>N&ordm; de colaboradores: <?= count($colaboradores) ?></h6>
            </th>
            <th colspan="3" class="text-right text-nowrap" style="padding-bottom: 8px; border-left-width: 0px;">
                <h6>Data: <?= date('d/m/Y') ?></h6>
            </th>
        </tr>
        <tr class="active">
            <th class="text-center" width="9%">Contrato</th>
            <th class="text-center" width="24%">Depto/área/setor</th>
            <th class="text-center" width="25%">Funcionário</th>
            <th class="text-center" width="9%">Matrícula</th>
            <th class="text-center" width="24%">Cargo/função</th>
            <th class="text-center text-nowrap" width="9%">Data admissão</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($colaboradores as $colaborador): ?>
            <tr>
                <td><?= $colaborador->contrato ?></td>
                <td><?= $colaborador->estrutura ?></td>
                <td><?= $colaborador->nome ?></td>
                <td><?= $colaborador->matricula ?></td>
                <td><?= $colaborador->cargo_funcao ?></td>
                <td class="text-center"><?= $colaborador->data_admissao ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>