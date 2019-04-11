<div class="form-group hidden-md hidden-lg">
    <label class="form-label">Legenda:</label>
    <br>
    <button class="btn btn-info btn-xs" type="button"><i class="glyphicon glyphicon-align-center"></i></button>
    <small> Andamento</small>
    <button class="btn btn-success btn-xs" type="button"
            onclick="alert('Imprima o certificados acessando a plataforma via um computador desktop.');">
        <i class="fa fa-print"></i></button>
    <small> Certificado</small>
    <hr>
</div>
<table class="table table-striped table-hover fill-head">
    <thead>
    <tr>
        <th>Treinamento</th>
        <th class="hidden-xs hidden-sm">Data de início</th>
        <th class="hidden-xs hidden-sm">Data de término</th>
        <th class="hidden-xs hidden-sm">Avaliação final</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($query as $row): ?>
        <tr>
            <td><?php echo $row->nome; ?></td>
            <td class="hidden-xs hidden-sm"><?php echo $row->data_inicio; ?></td>
            <td class="hidden-xs hidden-sm"><?php echo $row->data_maxima; ?></td>
            <td class="hidden-xs hidden-sm"><?php echo $row->resultado ? round($row->resultado, 2) . '%' : ''; ?></td>
            <td nowrap>
                <a class="btn btn-primary btn-sm"
                   href="<?php echo site_url('ead/cursos_funcionario/editar/' . $row->id); ?>">
                    <i class="fa fa-edit"></i><span class="hidden-xs hidden-sm"> Editar</span>
                </a>
                <button class="btn btn-danger btn-sm excluir" onclick="ajax_delete('<?= $row->id ?>')">
                    <i class="fa fa-trash"></i><span class="hidden-xs hidden-sm"> Excluir</span>
                </button>
                <a class="btn btn-info btn-sm"
                   href="<?php echo site_url('ead/treinamento/status/' . $row->id); ?>">
                    <i class="glyphicon glyphicon-align-center"></i><span class="hidden-xs hidden-sm"> Andamento</span>
                </a>
                <?php if ($this->agent->is_mobile() == false): ?>
                    <?php if ($row->resultado >= $row->nota_aprovacao) : ?>
                        <a class="btn btn-success btn-sm"
                           href="<?php echo site_url('ead/treinamento/certificado/' . $row->id); ?>"
                           target="_blank">
                            <i class="fa fa-print"></i><span class="hidden-xs hidden-sm"> Certificado</span>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-sm disabled">
                            <i class="fa fa-lock text-danger"></i><span class="hidden-xs hidden-sm"> Certificado</span>
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($query)): ?>
        <tr>
            <th colspan="5">Nenhum curso encontrado</th>
        </tr>
    <?php endif; ?>
    <tr>
        <th colspan="5">Total de treinamentos: <?php echo $total; ?></th>
    </tr>
    <?php if (count($query) != $total && count($query) > 0): ?>
        <tr>
            <th colspan="5">Total de treinamentos encontrados: <?php echo count($query); ?></th>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<div class="text-center" data-html="html-cursos-funcionario" data-query="<?php echo $busca; ?>">
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

    function ajax_delete(id) {
        if (confirm('Tem certeza que deseja excluir esse curso do funcionário?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('ead/cursos_funcionario/ajax_delete') ?>",
                type: "POST",
                dataType: "JSON",
                timeout: 9000,
                data: {
                    id: id
                },
                success: function (data) {
                    //if success reload ajax table
                    location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Erro ao excluir treinamento');
                }
            });

        }
    }
</script>