<?php require_once APPPATH . 'views/header.php'; ?>

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
</style>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <div class="row">
            <div class="col-sm-12">
                <img src="<?= base_url('imagens/usuarios/' . $foto) ?>" align="left"
                     style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                <p class="text-left">
                    <img src="<?= base_url('imagens/usuarios/' . $foto_descricao) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                </p>
            </div>
        </div>
        <table class="table table-condensed table-condensed clientes">
            <thead>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="3">
                    <h3 class="text-center">MAPA DE CLIENTES/PROSPECTS</h3>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr style='border-top: 5px solid #ddd; border-bottom: 1px solid #ddd;'>
                <td colspan="2">
                    <h5><strong>Data: </strong><?= $data; ?></h5>
                </td>
                <td class="text-right">
                    <a id="pdf" class="btn btn-sm btn-info"
                       href="<?= site_url('icom/clientes/pdf'); ?>"
                       title="Exportar PDF" target="_blank"><i class="glyphicon glyphicon-print"></i> Exportar PDF</a>
                </td>
            </tr>
            </tbody>
        </table>
        <table id="table" class="table table-bordered table-condensed">
            <thead>
            <tr class='active'>
                <th>Cliente/Prospect</th>
                <th>Contato</th>
                <th>Telefone</th>
                <th>E-mail</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($rows): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td width="25%"><?= $row->nome; ?></td>
                        <td width="25%"><?= $row->contato_principal; ?></td>
                        <td width="25%"><?= $row->telefone_contato_principal; ?></td>
                        <td width="25%"><?= $row->email_contato_principal; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class="text-center text-muted" colspan="4">Nenhum registro encontrado</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>
</section>

<?php require_once APPPATH . 'views/end_js.php'; ?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Gestão Comercial: Mapa de Clientes/Prospects';
    });
</script>

<?php require_once APPPATH . 'views/end_html.php'; ?>

