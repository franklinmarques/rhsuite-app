<table class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <th>Descrição</th>
            <th>Categoria</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($query->result() as $row) { ?>
            <tr>
                <td><?php echo $row->descricao; ?></td>
                <td><?= ($row->categoria == 1 ? 'Consultor' : 'Organização'); ?></td>
                <td>
                    <a class="btn btn-success btn-sm" href="<?php echo site_url('tipo/editar/' . $row->id); ?>">
                        <i class="fa fa-edit"></i>
                        Editar
                    </a>
                </td>
            </tr>
            <?php
        }
        if ($query->num_rows() == 0) {
            ?>
            <tr>
                <th colspan="3">Nenhum item encontrado</th>
            </tr>
            <?php
        }
        if ($total) {
            ?>
            <tr>
                <th colspan="3">Total de itens: <?php echo $total; ?></th>
            </tr>
            <?php
        }
        if ($query->num_rows() != $total && $query->num_rows() !== 0) {
            ?>
            <tr>
                <th colspan="3">Total de itens encontrados: <?php echo $query->num_rows(); ?></th>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<div class="text-center" data-html="html-biblioteca" data-query="<?php echo $busca; ?>">
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