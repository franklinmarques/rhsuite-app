<table class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <th>Competência/item a desenvolver</th>
            <th>Ações para desenvolvimento</th>
            <th>Resultados esperados</th>
            <th>Resultados alcançados</th>
            <th class='text-center'>Data início</th>
            <th class='text-center'>Data término</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>

        <?php
        foreach ($query->result() as $row) {
            ?>
            <tr>
                <td style="max-width: 60px; word-wrap: break-word;"><?php echo $row->competencia; ?></td>
                <td style="max-width: 80px; word-wrap: break-word;"><?php echo $row->descricao; ?></td>
                <td style="max-width: 80px; word-wrap: break-word;"><?php echo $row->expectativa; ?></td>
                <td style="max-width: 80px; word-wrap: break-word;"><?php echo $row->resultado; ?></td>
                <td><?php echo $row->data_inicio; ?></td>
                <td><?php echo $row->data_termino; ?></td>
                <?php
                $status = '';
                switch ($row->status) {
                    case 'A': $row->status = 'Atrasado';
                        $status = 'text-warning';
                        break;
                    case 'E': $row->status = 'Em andamento';
                        $status = 'text-primary';
                        break;
                    case 'F': $row->status = 'Finalizado';
                        $status = 'text-success';
                        break;
                    case 'C': $row->status = 'Cancelado';
                        $status = 'text-danger';
                        break;
                    default: $row->status = 'Não iniciado';
                }
                ?>
                <td class="<?= $status ?>"><strong><?= $row->status ?></strong></td>
                <td nowrap>
                    <a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="edit_pdi('<?= $row->id ?>');">
                        <i class="fa fa-edit"></i>Editar
                    </a>
                    <a class="btn btn-danger btn-xs excluir" href="javascript:void(0);" onclick="delete_pdi('<?= $row->id ?>');">
                        <i class="fa fa-trash"></i> Excluir
                    </a>
                </td>
            </tr>
            <?php
        }

        if ($query->num_rows() == 0) {
            ?>
            <tr>
                <th colspan="8">Nenhum item encontrado</th>
            </tr>
            <?php
        }
        ?>
<!--        <tr>
            <th colspan="8">Total de itens existentes: <?php //echo $total; ?></th>
        </tr>-->
        <?php
        if ($query->num_rows() != $total && $query->num_rows() !== 0) {
            ?>
            <tr>
                <th colspan="8">Total de itens encontrados: <?php echo $query->num_rows(); ?></th>
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