<table class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <th>Funcionário</th>
            <th>Depto/área/setor</th>
            <th>Função</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($query->result() as $row) {
            ?>
            <tr>
                <td><?php echo $row->nome; ?></td>
                <td><?php echo implode('/', array_filter(array($row->depto, $row->area, $row->setor))); ?></td>
                <td><?php echo $row->funcao; ?></td>
                <td nowrap>
                    <a class="btn btn-primary btn-xs" href="<?php echo site_url('home/editarfuncionario/' . $row->id); ?>">
                        <i class="fa fa-edit"></i>Editar</a>
                    <a class="btn btn-success btn-xs" href="<?php echo site_url('ead/cursos_funcionario/index/' . $row->id); ?>">
                        <i class="fa fa-graduation-cap"></i> Treinamentos
                    </a>
                    <a class="btn btn-magenta btn-xs" href="<?php echo site_url('pdi/gerenciar/' . $row->id); ?>" style="background-color: #A0511D; color: #FFF;">
                        <i class="fa fa-briefcase"></i> PDIs
                    </a>
                    <a class="btn btn-magenta btn-xs" href="<?php echo site_url('avaliacaoexp/avaliado/' . $row->id); ?>" style="background-color: #1B9544; color: #FFF;">
                        <i class="fa fa-list-alt"></i> Avaliações
                    </a>
                    <a class="btn btn-xs" href="<?= site_url('documento/colaborador/gerenciar/' . $row->id); ?>" style="background-color: #E87528; color: #FFF;">
                        <i class="fa fa-file-o"></i> Docs
                    </a>
                    <a class="btn btn-danger btn-xs excluir" href="<?php echo site_url('home/excluirfuncionario/' . $row->id); ?>" onclick="if (!confirm('Tem certeza que deseja excluir esse funcionário?'))
                                return false;">
                        <i class="fa fa-trash"></i> Excluir
                    </a>
                </td>
            </tr>
            <?php
        }

        if ($query->num_rows() == 0) {
            ?>
            <tr>
                <th colspan="5">Nenhum funcionário encontrado</th>
            </tr>
            <?php
        }
        ?>
        <tr>
            <th colspan="5">Total de funcionários: <?php echo $total; ?></th>
        </tr>
        <?php
        if ($query->num_rows() != $total && $query->num_rows() !== 0) {
            ?>
            <tr>
                <th colspan="5">Total de funcionários encontrados: <?php echo $query->num_rows(); ?></th>
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