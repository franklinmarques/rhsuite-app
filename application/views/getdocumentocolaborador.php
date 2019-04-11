<table class="table table-striped table-hover fill-head">
    <thead>
    <tr>
        <th style="width:30%">Descrição</th>
        <th class="hidden-xs hidden-sm">Tipo</th>
        <th style="width:1%"><?php echo($this->agent->is_mobile() ? 'Ação' : 'Ações'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($query->result() as $row) { ?>
        <tr>
            <td><?php echo $row->descricao; ?></td>
            <td class="hidden-xs hidden-sm"><?php echo $row->tipo_descricao; ?></td>
            <td nowrap>
                <a class="btn btn-success btn-sm" href="<?= site_url('documento/visualizar/' . $row->id) ?>">
                    <i class="fa fa-eye"></i>
                    Visualizar
                </a>
                <a class="btn btn-success btn-sm hidden-xs hidden-sm"
                   href="<?php echo site_url('documento/colaborador/editar/' . $row->id); ?>">
                    <i class="fa fa-edit"></i>
                    Editar
                </a>
                <a class="btn btn-info btn-sm hidden-xs hidden-sm"
                   href="javascript:baixar_documento(<?= $row->id ?>);">
                    <i class="fa fa-download"></i>
                    Download
                </a>
                <a class="btn btn-danger btn-sm hidden-xs hidden-sm"
                   href="javascript:excluiArquivo(<?= $row->id ?>);">
                    <i class="fa fa-download"></i>
                    Excluir
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
    if ($this->agent->is_mobile() == false) {
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