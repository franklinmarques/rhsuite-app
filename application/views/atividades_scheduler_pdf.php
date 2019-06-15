<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório de Atividades</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
    <!--WARNING: Respond.js doesn't work if you view the page via file://-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body style="color: #000;">
<div class="container-fluid">
    <table>
        <tr>
            <td>
                <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                     style="height: auto; width: auto; max-height: 92px; max-width:154px; vertical-align: middle; padding: 0 10px 5px 0;">
            </td>
            <td style="vertical-align: top;">
                <p>
                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                </p>
            </td>
        </tr>
    </table>
    <table id="scheduler" class="table table-condensed table-condensed">
        <thead>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="3" style="padding-bottom: 12px;">
                <h2 class="text-center" style="font-weight: bold;">RELATORIO DE SCHEDULER DE ATIVIDADES RECORRENTES</h2>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr class="success" style='border-top: 5px solid #ddd; border-bottom: 1px solid #ddd;'>
            <td style="padding: 4px 0px;">
                <h5><span style="font-weight: bold;">Data atual: </span><?= date('d/m/Y') ?></h5>
            </td>
            <td style="padding: 4px 0px;">
                <h5><span style="font-weight: bold;">Usuário: </span><?= $usuario->nome ?></h5>
            </td>
            <td style="padding: 4px 0px;">
                <?php if (!in_array($this->session->userdata('tipo'), ['administrador', 'empresa'])): ?>
                    <h5><span style="font-weight: bold;">Depto/área/setor: </span><?= $usuario->estrutura ?></h5>
                <?php endif; ?>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="row form-group">
        <label class="control-label col-md-3 text-primary">Atividades recorrentes
            semanais</label>
        <div class="col-md-9">
            <div class="btn-group btn-group-sm" data-toggle="buttons">
                <label class="btn btn-default">
                    <input type="checkbox" name="semana[]" value="1"> 1&ordf; Sem.
                </label>
                <label class="btn btn-default">
                    <input type="checkbox" name="semana[]" value="2"> 2&ordf; Sem.
                </label>
                <label class="btn btn-default">
                    <input type="checkbox" name="semana[]" value="3"> 3&ordf; Sem.
                </label>
                <label class="btn btn-default">
                    <input type="checkbox" name="semana[]" value="4"> 4&ordf; Sem.
                </label>
                <label class="btn btn-default">
                    <input type="checkbox" name="semana[]" value="5"> 5&ordf; Sem.
                </label>
            </div>
        </div>
    </div>

    <table id="table" class="table table-bordered table-condensed">
        <thead>
        <tr class='active'>
            <th class="text-center">Dia</th>
            <th class="text-center">Sem.</th>
            <th class="text-center">Mês</th>
            <th class="text-center">Atividade</th>
            <th class="text-center">Objetivos</th>
            <th class="text-center">Data limite</th>
            <th class="text-center">Envolvidos</th>
            <th class="text-center">Observações</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td class="text-center"><?= $row->dia ?></td>
                <td class="text-center"><?= $row->semana ?></td>
                <td class="text-center"><?= $row->mes ?></td>
                <td><?= $row->atividade ?></td>
                <td><?= $row->objetivos ?></td>
                <td><?= $row->data_limite ?></td>
                <td><?= $row->envolvidos ?></td>
                <td><?= $row->observacoes ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <!--</div>-->
</div>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

<script>

    var table;

    $(document).ready(function () {
        //datatables
        table = $('#table').DataTable({
            'columnDefs': [
                {
                    'width': '20%',
                    'targets': [3, 4, 5, 6, 7]
                },
                {
                    'width': '1%',
                    'className': 'text-center',
                    'targets': [0, 1, 2]
                }
            ]
        });

    });
</script>
</body>
</html>