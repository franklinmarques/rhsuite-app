<table id="table-dnd" class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <th>Título</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($query->result() as $row): ?>
            <tr id="<?php echo $row->id; ?>">
                <td><?php echo $row->titulo; ?></td>
                <td>
                    <a class="btn btn-success btn-sm" href="<?php echo site_url('ead/pagina_curso/preview/' . $row->id); ?>">
                        <i class="glyphicon glyphicon-eye-open"></i> Preview
                    </a>
                    <a class="btn btn-primary btn-sm" href="<?php echo site_url('ead/pagina_curso/editar/' . $row->id); ?>">
                        <i class="fa fa-edit"></i> Editar
                    </a>
                    <button class="btn btn-info btn-sm" onclick="copiar(<?= $row->id ?>)">
                        <i class="fa fa-copy"></i> Copiar
                    </button>
                    <button class="btn btn-danger btn-sm excluir" onclick="ajax_delete('<?= $row->id ?>')">
                        <i class="fa fa-trash"></i> Excluir
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($query->num_rows() == 0): ?>
            <tr>
                <th colspan="3">Nenhuma página do curso encontrada</th>
            </tr>
        <?php endif; ?>
        <tr>
            <th colspan="3">Total de páginas do curso: <?php echo $total; ?></th>
        </tr>
        <?php if ($query->num_rows() != $total && $query->num_rows() !== 0): ?>
            <tr>
                <th colspan="3">Total de páginas do curso encontradas: <?php echo $query->num_rows(); ?></th>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<div class="text-center" data-html="html-paginas-curso" data-query="<?php echo $busca; ?>">
    <?php echo $this->pagination->create_links(); ?>
</div>
<script>
    $(function () {
<?php if ($query->num_rows() == $total && $query->num_rows() > 0): ?>
            $('#table-dnd').tableDnD({
                onDrop: function (table, row) {
                    $.ajax({
                        url: '<?php echo site_url('ead/pagina_curso/ordenar/' . $this->uri->rsegment(3)); ?>',
                        type: 'POST',
                        data: '<?php echo "{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}"; ?>&' + $.tableDnD.serialize(),
                        success: function (data) {
                            ;
                            //
                        }
                    });
                }
            });
<?php endif; ?>
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
                url: '<?php echo site_url('ead/pagina_curso/duplicar') ?>',
                type: "POST",
                dataType: "JSON",
                timeout: 9000,
                data: {
                    id: id
                },
                success: function (data) {
                    if (data === 'sucesso') {
                        window.location.reload();
                    } else {
                        alert(data);
                    }
                }
            });
        }
    }

    function ajax_delete(id) {
        if (confirm('Tem certeza que deseja excluir essa página do curso?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('ead/pagina_curso/ajax_delete') ?>",
                type: "POST",
                dataType: "JSON",
                timeout: 9000,
                data: {
                    id: id
                },
                success: function (data)
                {
                    //if success reload ajax table
                    location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Erro ao excluir a página do curso');
                }
            });

        }
    }
</script>