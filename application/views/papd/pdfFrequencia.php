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
    <table id="frequencia" class="table table-condensed avaliado">
        <thead>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="3" style="padding-bottom: 8px;">
                <h3 class="text-center" style="font-weight: bold;">CONTROLE DE FREQUÊNCIA INDIVIDUAL</h3>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="2" style="padding: 5px 0px;">
                <h6><span style="font-weight: bold;">Prestador de serviços: </span><?= $paciente->instituicao_nome ?>
                </h6>
            </td>
            <td style="padding: 5px 0px;">
                <h6><span style="font-weight: bold;">CNPJ: </span><?= $paciente->instituicao_cnpj ?></h6>
            </td>
        </tr>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="3" style="padding-top: 4px;"><h5>Dados do paciente</h5></th>
        </tr>
        <tr class="dados_paciente">
            <td width="30%">Nome: <?= $paciente->nome ?></td>
            <td width="35%">Sexo: <?= $paciente->sexo ?></td>
            <td width="25%">Data de nascimento: <?= $paciente->data_nascimento ?></td>
        </tr>
        <tr class="dados_paciente">
            <td>CPF: <?= $paciente->cpf ?></td>
            <td>Cadastro Municipal: <?= $paciente->cadastro_municipal ?></td>
            <td>Deficiência: <?= $paciente->deficiencia ?></td>
        </tr>
        <tr class="dados_paciente">
            <td>HD: <?= $paciente->hd ?></td>
            <td>Responsável: <?= $paciente->nome_responsavel_1 ?></td>
            <td>Telefone: <?= $paciente->telefone_fixo_1 ?></td>
        </tr>
        <tr class="dados_paciente">
            <td>Endereço: <?= $paciente->endereco ?></td>
            <td>Complemento: <?= $paciente->complemento ?></td>
            <td>Bairro: <?= $paciente->bairro ?></td>
        </tr>
        <tr class="dados_paciente" style='border-bottom: 5px solid #ddd;'>
            <td>Cidade: <?= $paciente->cidade ?></td>
            <td>Estado: <?= $paciente->estado ?></td>
            <td>CEP: <?= $paciente->cep ?></td>
        </tr>
        <tr style='border-top: 5px solid #ddd;'>
            <td colspan="3" style="padding: 4px 0px;"><h5><span
                            style="font-weight: bold;">Declaração do mês: </span><?= $paciente->nome_mes_ingresso . ' de ' . $paciente->ano_ingresso ?>
                </h5></td>
        </tr>
        <tr>
            <td colspan="3" style="font-size: 11px; padding: 1px 0px;"><p style="text-indent: 2em;"><i>Declaramos que
                        neste mês, o paciente acima identificado, foi submetido às atividades/procedimentos abaixo
                        relacionadas, conforme assinaturas do paciente/responsável e do profissional realizador do
                        atendimento.</i></p></td>
        </tr>
        </tbody>
    </table>
    <table id="table" class="table table-bordered table-condensed">
        <thead>
        <tr class="active">
            <th colspan="3" class="text-center"><h4>PROGRAMA DE APOIO À PESSOA COM DEFICIÊNCIA</h4></th>
        </tr>
        <tr class="active">
            <th class="text-center" width="12%">Data</th>
            <th class="text-center text-nowrap" width="12%">Hora início</th>
            <th class="text-center" width="76%">Atividades/procedimentos</th>
        </tr>
        </thead>
        <tbody>
        <?php for ($i = 0; $i < 25; $i++): ?>
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>
    <br>
    <table class="table table-condensed">
        <thead>
        <tr>
            <th colspan="3">
                Reconheço os atendimentos acima apresentados.
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="border: 0; text-align: center;"><br>____________________________________<br>Data/local
            <td style="border: 0; text-align: center;"><br>____________________________________<br>Paciente/Responsável
            <td style="border: 0; text-align: center;"><br>____________________________________<br>Profissional
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>