<table class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <th>PDI</th>
            <th>Período do PDI</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>

        <?php
        foreach ($query->result() as $row) {
            ?>
            <tr>
                <td style="max-width: 160px; word-wrap: break-word;"><?php echo $row->nome; ?></td>
                <td><?php echo $row->data_inicio . ' a ' . $row->data_termino; ?></td>
                <td>
                    <a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="edit_pdi('<?= $row->id ?>');">
                        <i class="fa fa-edit"></i>Editar
                    </a>
                    <a class="btn btn-info btn-xs" href="<?php echo site_url('pdi/pdfRelatorio/' . $row->id); ?>">
                        <i class="fa fa-print"></i> Imprimir
                    </a>
                    <a class="btn btn-danger btn-xs excluir" href="javascript:void(0);" onclick="delete_pdi('<?= $row->id ?>');">
                        <i class="fa fa-trash"></i> Excluir
                    </a>
                    <a class="btn btn-warning btn-xs" href="<?php echo site_url('pdi_desenvolvimento/gerenciar/' . $row->usuario . '/' . $row->id); ?>">
                        <i class="fa fa-list-alt"></i> Plano de desenvolvimento
                    </a>
                    <a class="btn btn-info btn-xs" href="<?php echo site_url('pdi/relatorio/' . $row->id); ?>">
                        <i class="fa fa-file-pdf-o"></i> Relatório
                    </a>
                </td>
            </tr>
            <?php
        }

        if ($query->num_rows() == 0) {
            ?>
            <tr>
                <th colspan="3">Nenhum PDI encontrado</th>
            </tr>
            <?php
        }
        ?>
<!--        <tr>
            <th colspan="3">Total de PDIs existentes: <?php //echo $total; ?></th>
        </tr>-->
        <?php
        if ($query->num_rows() != $total && $query->num_rows() !== 0) {
            ?>
            <tr>
                <th colspan="3">Total de PDIs encontrados: <?php echo $query->num_rows(); ?></th>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<div class="text-center" data-html="html-funcionarios" data-query="<?php echo $busca; ?>">
    <?php echo $this->pagination->create_links(); ?>
</div>
<script>
    $(function () {
        $('.pagination li a').click(function () {
            if ($(this).attr('href') === "#")
                return false;

            ajax_post($(this).attr('href'), $(this).parent().parent().parent().data('query'), $('#' + $(this).parent().parent().parent().data('html')));
            return false;
        });
    });
</script>