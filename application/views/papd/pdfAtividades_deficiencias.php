<!DOCTYPE html>
<html>
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CORPORATE RH - LMS - Controle de Frequência Individual</title>
        <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">

        <!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries--> 
        <!--WARNING: Respond.js doesn't work if you view the page via file://--> 
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style>
            .btn-success{
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
            .text-nowrap{
                white-space: nowrap;
            }   

            tr.group, tr.group:hover {
                background-color: #ddd !important;
            }
        </style>
    </head> 
    <body style="color: #000;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <img src="<?= base_url($foto) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    <p class="text-left">
                        <img src="<?= base_url($foto_descricao) ?>" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    </p>
                </div>
            </div>
            <table class="table table-condensed avaliado">
                <thead>
                    <tr style='border-top: 5px solid #ddd;'>
                        <th colspan="3">
                            <?php if ($is_pdf == false): ?>
                                <h1 class="text-center"><strong>RELATÓRIO DE ATIVIDADES E DEFICIÊNCIAS</strong></h1>
                            <?php else: ?>
                                <h2 class="text-center"><strong>RELATÓRIO DE ATIVIDADES E DEFICIÊNCIAS</strong></h2>
                            <?php endif; ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>

            <br/>
            <!--<div class="table-responsive">-->
            <table id="table1" class="table table-bordered table-condensed deficiencias" width="100%">
                <thead>
                    <tr class="active">
                        <th class="text-center">Hipótese Diagnóstica</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($deficiencias as $deficiencia): ?>
                        <tr>
                            <td><?= $deficiencia->nome ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <table id="table2" class="table table-bordered table-condensed atividades" width="100%">
                <thead>
                    <tr class="active">
                        <th class="text-center" style="width: 80%;">Atividade</th>
                        <th class="text-center">Valor (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($atividades as $atividade): ?>
                        <tr>
                            <td><?= $atividade->nome ?></td>
                            <td class="text-right"><?= $atividade->valor ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!--</div>-->
        </div>
    </body>
</html>