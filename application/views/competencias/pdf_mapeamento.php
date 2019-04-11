<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajax CRUD with Bootstrap modals and Datatables</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<style>
    li {
        font-size: 13px;
    }
</style>
<body>
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
                </p>
            </td>
        </tr>
    </table>
    <table class="table table-condensed cargo">
        <thead>
        <tr>
            <th colspan="2">
                <?php if ($is_pdf == false): ?>
                    <h1 class="text-center">MAPEAMENTO DE COMPETÊNCIAS</h1>
                <?php else: ?>
                    <h2 class="text-center">MAPEAMENTO DE COMPETÊNCIAS</h2>
                <?php endif; ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style='border-top: 5px solid #ddd;'>
            <td>
                <?php if ($is_pdf == false): ?>
                    <h5><strong>Cargo/função: </strong><?= $cargo_funcao ?></h5>
                    <h5><strong>Data atual: </strong><?= $data_atual ?></h5>
                <?php else: ?>
                    <h6><strong>Cargo/função: </strong><?= $cargo_funcao ?></h6>
                    <h6><strong>Data atual: </strong><?= $data_atual ?></h6>
                <?php endif; ?>
            </td>
            <td class="text-right">
                <?php if ($is_pdf == false): ?>
                    <a class="btn btn-sm btn-danger"
                       href="<?= site_url('avaliacao/cargos/pdfCompetencias/' . $this->uri->rsegment(3) . "/" . $this->uri->rsegment(4)); ?>"
                       title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                    <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                <?php endif; ?>
            </td>
        </tr>

        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="2">Legenda</th>
        </tr>
        <tr>
            <td>
                <div class="bg-info">
                    <ul class="text-info">
                        <li>
                            <small>Peso (0 a 100) - Peso ou grau de importância do comportamento;</small>
                        </li>
                        <li>
                            <small>Atitude (0 a 100) - Grau de Atitude demandada;</small>
                        </li>
                        <li>
                            <small>Nível (0 a 5) - Nível de Conhecimento e Habilidade Demandados;</small>
                        </li>
                        <ol start="0">
                            <li>
                                <small>Mínimo conhecimento;</small>
                            </li>
                            <li>
                                <small>Conhecimento básico, habilidade mínima;</small>
                            </li>
                            <li>
                                <small>Conhecimento e prática básica;</small>
                            </li>
                            <li>
                                <small>Conhecimento e prática intermediários;</small>
                            </li>
                            <li>
                                <small>Conhecimento e prática avançados;</small>
                            </li>
                            <li>
                                <small>Especialista - Multiplicador.</small>
                            </li>
                        </ol>
                    </ul>
                </div>
            </td>
            <td>
                <div class="bg-info">
                    <ul class="text-info" type="none">
                        <li>&nbsp;</li>
                        <li>
                            <small>NCTf - Nível de Competência Técnica Demandado pelo Cargo-Função</small>
                        </li>
                        <li>
                            <small>NCCf - Nível de Competência Comportamental Demandado pelo Cargo-Função</small>
                        </li>
                        <li>
                            <small>IDcf - Índice de Desempenho Demandado pelo Cargo-Função</small>
                        </li>
                        <li>&nbsp;</li>
                    </ul>
                </div>
                <table class="competencias table table-bordered table-condensed">
                    <thead>
                    <tr class="active">
                        <th>NCTf</th>
                        <th nowrap>Peso (CT)</th>
                        <th>NCCf</th>
                        <th nowrap>Peso (CC)</th>
                        <th>IDcf</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="text-right">
                        <td><?= $nctf ?></td>
                        <td><?= $peso_ct ?></td>
                        <td><?= $nccf ?></td>
                        <td><?= $peso_cc ?></td>
                        <td><?= $idcf ?></td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

    <?php foreach ($ct as $competencia): ?>
        <!--<div class="table-responsive">-->
        <table class="competencias table table-bordered table-condensed">
            <thead>
            <tr class='success'>
                <th colspan="5" class='text-success'><?= $competencia['nome'] ?></th>
            </tr>
            <tr class='active'>
                <th rowspan="2" style='vertical-align: middle;'>Comportamento/dimensão</th>
                <th colspan="4" class="text-center">Cargo/função</th>
            </tr>
            <tr class='active'>
                <th>Peso</th>
                <th>Nível</th>
                <th>Atitude</th>
                <th>IDcf</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($competencia['dimensao'] as $dm): ?>

                <tr class='text-right'>
                    <td class='text-left'><?= $dm->nome ?></td>
                    <td><?= round($dm->peso, 3) ?></td>
                    <td><?= $dm->nivel ?></td>
                    <td><?= $dm->atitude ?></td>
                    <td><?= round($dm->indice, 3) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <!--</div>-->
    <?php endforeach; ?>

    <?php foreach ($cc as $competencia): ?>
        <!--<div class="table-responsive">-->
        <table class="competencias table table-bordered table-condensed">
            <thead>
            <tr class='success'>
                <th colspan="5" class='text-success'><?= $competencia['nome'] ?></th>
            </tr>
            <tr class='active'>
                <th rowspan="2" style='vertical-align: middle;'>Comportamento/dimensão</th>
                <th colspan="4" class="text-center">Cargo/função</th>
            </tr>
            <tr class='active'>
                <th>Peso</th>
                <th>Nível</th>
                <th>Atitude</th>
                <th>IDcf</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($competencia['dimensao'] as $dm): ?>

                <tr class='text-right'>
                    <td class='text-left'><?= $dm->nome ?></td>
                    <td><?= round($dm->peso, 3) ?></td>
                    <td><?= $dm->nivel ?></td>
                    <td><?= $dm->atitude ?></td>
                    <td><?= round($dm->indice, 3) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <!--</div>-->
    <?php endforeach; ?>

</div>
</body>
</html>