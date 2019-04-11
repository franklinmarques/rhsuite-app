<div class="form-group hidden-md hidden-lg">
    <label class="form-label">Legenda:</label>
    <br>
    <button class="btn btn-warning btn-xs" type="button"><i class="fa fa-book"></i></button>
    <small> Acessar</small>
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
        <th class="hidden-xs hidden-sm">Data início</th>
        <th class="hidden-xs hidden-sm">Data término</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($query as $row): ?>
        <tr>
            <td><?php echo $row->nome; ?></td>
            <td class="hidden-xs hidden-sm"><?php echo $row->data_inicio; ?></td>
            <td class="hidden-xs hidden-sm"><?php echo $row->data_maxima; ?></td>
            <td nowrap>
                <?php if (date('Ymd', strtotime(str_replace('/', '-', $row->data_maxima))) >= date('Ymd') || empty($row->data_maxima)): ?>
                    <a class="btn btn-warning btn-sm" target="_blank" title="Acessar"
                       href="<?php echo site_url('ead/treinamento/acessar/' . $row->id); ?>">
                        <i class="fa fa-book"></i><span class="hidden-xs hidden-sm"> Acessar</span>
                    </a>
                <?php else: ?>
                    <button class="btn btn-warning btn-sm disabled" title="Acessar">
                        <i class="fa fa-book"></i><span class="hidden-xs hidden-sm"> Acessar</span>
                    </button>
                <?php endif; ?>
                <a class="btn btn-info btn-sm" title="Andamento"
                   href="<?php echo site_url('ead/treinamento/status/' . $row->id); ?>">
                    <i class="glyphicon glyphicon-align-center"></i> <span class="hidden-xs hidden-sm"> Andamento</span>
                </a>
                <?php if ($this->agent->is_mobile() == false): ?>
                    <?php if ($row->nota_aprovacao > 0 and $row->resultado < $row->nota_aprovacao) : ?>
                        <button class="btn btn-sm disabled" title="Certificado">
                            <i class="fa fa-lock text-danger"></i> <span class="hidden-xs hidden-sm"> Certificado</span>
                        </button>
                    <?php else: ?>
                        <a class="btn btn-success btn-sm" title="Certificado"
                           href="<?php echo site_url('ead/treinamento/certificado/' . $row->id); ?>"
                           target="_blank">
                            <i class="fa fa-print"></i><span class="hidden-xs hidden-sm"> Certificado</span>
                        </a>
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
<div class="text-center" data-html="html-meus-cursos" data-query="<?php echo $busca; ?>">
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