<table class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Título</th>
            <th>Disciplina</th>
            <th>Ano/Série</th>
            <th>Link</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($query->result() as $row) { ?>
            <tr>
                <td> 
                    <input type="radio" name="biblioteca" value="<?php echo $row->id; ?>"<?= ($row->id == $this->uri->rsegment(4) ? ' checked="checked"' : ''); ?> />
                </td>
                <td><?php echo $row->titulo; ?></td>
                <td><?php echo $row->disciplina; ?></td>
                <td><?php echo $row->anoserie; ?></td>
                <td>
                    <a href="<?php echo $row->link; ?>" title="<?php echo $row->titulo; ?>" target="_blank"><?php echo $row->link; ?></a>
                </td>
            </tr>
            <?php
        }
        if ($query->num_rows() == 0) {
            ?>
            <tr>
                <th colspan="5">Nenhuma biblioteca encontrada</th>
            </tr>
            <?php
        }
        ?>
        <tr>
            <th colspan="5">Total de bibliotecas: <?php echo $total; ?></th>
        </tr>
        <?php
        if ($query->num_rows() != $total && $query->num_rows() !== 0) {
            ?>
            <tr>
                <th colspan="5">Total de bibliotecas encontradas: <?php echo $query->num_rows(); ?></th>
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

            videosEdunet($('#html-biblioteca'), $(this).attr('href'), $('select[name=categoriabiblioteca]').val(), $('input[name=titulobiblioteca]').val(), $('input[name=tagsbiblioteca]').val());
            return false;
        });
    });
</script>