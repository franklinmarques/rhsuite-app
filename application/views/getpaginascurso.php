<table id="table-dnd" class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <th>Título</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($query->result() as $row) {
            ?>
            <tr id="<?php echo $row->id; ?>">
                <td><?php echo $row->titulo; ?></td>
                <td>
                    <a class="btn btn-success btn-sm" href="<?php echo site_url('home/preview/' . $row->id); ?>"><i
                            class="glyphicon glyphicon-eye-open"></i> Preview</a>
                    <a class="btn btn-primary btn-sm"
                       href="<?php echo site_url('home/editarpaginacurso/' . $row->id); ?>"><i class="fa fa-edit"></i> Editar</a>
                    <a class="btn btn-info btn-sm" href="javascript: void(0)" onclick="copiar(<?= $row->id ?>)"><i class="fa fa-copy"></i>
                        Copiar</a>
                    <a class="btn btn-danger btn-sm excluir"
                       href="<?php echo site_url('home/excluirpaginacurso/' . $row->id); ?>" onclick="if (!confirm('Tem certeza que deseja excluir essa página do curso?'))
                                       return false;"><i class="fa fa-trash"></i> Excluir</a>
                </td>
            </tr>
            <?php
        }
        if ($query->num_rows() == 0) {
            ?>
            <tr>
                <th colspan="3">Nenhuma página do curso encontrada</th>
            </tr>
            <?php
        }
        ?>
        <tr>
            <th colspan="3">Total de páginas do curso: <?php echo $total; ?></th>
        </tr>
        <?php
        if ($query->num_rows() != $total && $query->num_rows() !== 0) {
            ?>
            <tr>
                <th colspan="3">Total de páginas do curso encontradas: <?php echo $query->num_rows(); ?></th>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<div class="text-center" data-html="html-paginas-curso" data-query="<?php echo $busca; ?>">
    <?php echo $this->pagination->create_links(); ?>
</div>
<script>
    $(function () {
<?php if ($query->num_rows() == $total && $query->num_rows() > 0) { ?>
            $('#table-dnd').tableDnD({
                onDrop: function (table, row) {
                    $.ajax({
                        url: '<?php echo site_url('home/ordempaginascurso/' . $this->uri->rsegment(3)); ?>',
                        type: 'POST',
                        data: '<?php echo "{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}"; ?>&' + $.tableDnD.serialize(),
                        success: function (data) {
                            ;
                            //
                        }
                    });
                }
            });
<?php } ?>
        $('.pagination li a').click(function () {
            if ($(this).attr('href') === "#")
                return false;

            ajax_post($(this).attr('href'), $(this).parent().parent().parent().data('query'), $('#' + $(this).parent().parent().parent().data('html')));
            return false;
        });
    });

    function copiar(id) {
        if (confirm('Deseja copiar essa página?')) {
            $.ajax({
                url: '<?php echo site_url('home/copiar_pagina') ?>',
                data: 'id=' + id,
                dataType: 'json',
                success: function (data) {
                    if (data === 'success') {
                        window.location.reload();
                    } else {
                        alert(data);
                    }
                }
            });
        }
    }
</script>