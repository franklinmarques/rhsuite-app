<table class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <th>Empresa</th>
            <th>E-mail</th>
            <th>Situação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($query->result() as $row) {
            if ($row->status == 0) {
                $row->status = "Bloqueado";
            } else {
                $row->status = "Ativo";
            }
            ?>
            <tr>
                <td style="width: 25%;"><?php echo $row->nome; ?></td>
                <td><?php echo $row->email; ?></td>
                <td><?php echo $row->status; ?></td>
                <td>
                    <a class="btn btn-primary btn-sm" href="javascript:void(0);"><i class="fa fa-line-chart"></i> Estatísticas</a>
                    <a class="btn btn-warning btn-sm" href="<?php echo site_url('home/cursosempresa/' . $row->id); ?>"><i class="fa fa-book"></i> Treinamentos</a>
                    <a class="btn btn-info btn-sm" href="<?php echo site_url('home/editarempresa/' . $row->id); ?>"><i class="fa fa-edit"></i> Editar</a>
                    <a class="btn btn-danger btn-sm excluir" href="<?php echo site_url('home/excluirempresa/' . $row->id); ?>" onclick="if (!confirm('Tem certeza que deseja excluir essa empresa?'))
                                return false;"><i class="fa fa-trash"></i> Excluir</a>
                </td>
            </tr>
            <?php
        }
        if ($query->num_rows() == 0) {
            ?>
            <tr>
                <th colspan="4">Nenhuma empresa encontrada</th>
            </tr>
            <?php
        }
        ?>
        <tr>
            <th colspan="4">Total de empresas: <?php echo $total; ?></th>
        </tr>
        <?php
        if ($query->num_rows() != $total && $query->num_rows() !== 0) {
            ?>
            <tr>
                <th colspan="4">Total de empresas encontradas: <?php echo $query->num_rows(); ?></th>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<div class="text-center" data-html="html-empresas" data-query="<?php echo $busca; ?>">
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