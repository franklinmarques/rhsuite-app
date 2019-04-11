<table class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <th>Público</th>
            <th>Treinamento</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($query->result() as $row) {
            ?>
            <tr>
                <td><?php echo $row->publico == 1 ? "Sim" : "Não"; ?></td>
                <td><?php echo $row->curso; ?></td>
                <td>
                    <?php if ($row->publico == 1) { ?>
                        <p>-</p>
                    <?php } else { ?>
                        <a class="btn btn-danger btn-sm excluir"
                           href="<?php echo site_url('home/excluircursosempresa/' . $this->uri->rsegment(3) . '/' . $row->id); ?>"
                           onclick="if (!confirm('Tem certeza que deseja excluir esse curso da empresa?'))
                                       return false;">
                            <i class="fa fa-trash"></i> Excluir
                        </a>
                        <a class="btn btn-primary btn-sm"
                           href="<?php echo site_url('home/editarcursoempresa/' . $this->uri->rsegment(3) . '/' . $row->id); ?>">
                            <i class="fa fa-key"></i> Atualizar Licença
                        </a>
    <?php } ?>
                </td>
            </tr>
            <?php
        }
        if ($query->num_rows() == 0) {
            ?>
            <tr>
                <th colspan="3">Nenhum curso encontrado</th>
            </tr>
            <?php
        }
        ?>
        <tr>
            <th colspan="3">Total de treinamentos: <?php echo $total; ?></th>
        </tr>
        <?php
        if ($query->num_rows() != $total && $query->num_rows() !== 0) {
            ?>
            <tr>
                <th colspan="3">Total de treinamentos encontrados: <?php echo $query->num_rows(); ?></th>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<div class="text-center" data-html="html-cursos-empresa" data-query="<?php echo $busca; ?>">
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