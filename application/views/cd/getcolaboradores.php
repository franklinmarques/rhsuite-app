<table class="table table-striped table-hover fill-head" width="100%">
    <thead>
        <tr>
            <th>Funcionário</th>
            <th>Depto/área/setor</th>
            <th>Função</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($query as $row): ?>
            <tr>
                <td><?php echo $row->nome; ?></td>
                <td><?php echo implode('/', array_filter(array($row->depto, $row->area, $row->setor))); ?></td>
                <td><?php echo $row->funcao; ?></td>
                <td nowrap>
                    <a class="btn btn-primary btn-xs" href="<?php echo site_url('cd/colaboradores/editar_perfil/' . $row->id); ?>">
                        <i class="fa fa-edit"></i> Edição rápida</a>
                    <a class="btn btn-success btn-xs" href="<?php echo site_url('ead/cursos_funcionario/index/' . $row->id); ?>">
                        <i class="fa fa-graduation-cap"></i> Treinamentos
                    </a>
                    <a class="btn btn-magenta btn-xs" href="<?php echo site_url('pdi/gerenciar/' . $row->id); ?>" style="background-color: #A0511D; color: #FFF;">
                        <i class="fa fa-briefcase"></i> PDIs
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <?php if ($total_encontrados): ?>
                <th colspan="4">Total de funcionários: <?php echo $total; ?></th>
            <?php else: ?>
                <th colspan="4">Nenhum funcionário encontrado</th>
            <?php endif; ?>
        </tr>

        <?php if ($total_encontrados != $total && $total_encontrados > 0): ?>
            <tr>
                <th colspan="4">Total de funcionários encontrados: <?php echo $total_encontrados; ?></th>
            </tr>
        <?php endif; ?>
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