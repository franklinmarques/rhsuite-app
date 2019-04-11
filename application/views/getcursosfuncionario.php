<table class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <th>Treinamento</th>
            <th>Avaliação final</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $perguntas = array();
        $total_acertos = 0;

        foreach ($query->result() as $row) {
            /* Calcular avaliação final */
            $acertos = 0;
            $total_atividades = $row->total_atividades ? 100 / $row->total_atividades : 0;

            if ($atividades[$row->id]) {

                # Verifica a quantidade de alternativas e acertos
                foreach ($atividades[$row->id] as $questao) {
                    if (!isset($perguntas[$questao->pagina])) {
                        $perguntas[$questao->pagina] = array('alternativas' => 0, 'acertos' => 0);
                    }
                    
                    $perguntas[$questao->pagina]['alternativas'] += 1;
                    if ($questao->status == 1) {
                        $perguntas[$questao->pagina]['acertos'] += 1;
                    }
                }

                # Verifica o valor total
                foreach ($perguntas as $questao) {
                    $acertos += ($total_atividades / $questao['alternativas'] ) * $questao['acertos'];
                }
                $total_acertos += $acertos;
            }
            ?>
            <tr>
                <td><?php echo $row->curso; ?></td>
                <td><?= ($acertos > 100 ? 100 : round($acertos, 2)); ?>%</td>
                <td>
                    <a class="btn btn-primary btn-sm editar"
                       href="<?php echo site_url('home/editarcursofuncionario/' . $this->uri->rsegment(3) . '/' . $row->id); ?>"><i
                            class="fa fa-edit"></i> Editar</a>
                    <a class="btn btn-info btn-sm"
                       href="<?php echo site_url('curso/status_treinamento/' . $this->uri->rsegment(3) . '/' . $row->id); ?>"><i
                            class="glyphicon glyphicon-align-center"></i> Andamento</a>
                    <a class="btn btn-danger btn-sm excluir"
                       href="<?php echo site_url('home/excluircursosfuncionario/' . $this->uri->rsegment(3) . '/' . $row->id); ?>"
                       onclick="if (!confirm('Tem certeza que deseja excluir esse curso do funcionário?'))
                                   return false;"><i
                            class="fa fa-trash"></i> Excluir</a>
                        <?php
                        if ($acertos >= 75) {
                            ?>
                        <a class="btn btn-success btn-sm"
                           href="<?php echo site_url('certificado/emissaoCertificado/' . $row->id . '/' . $this->uri->rsegment(3)); ?>"
                           target="_blank">
                            <i class="fa fa-print"></i> Certificado</a>
                        <?php
                    }
                    ?>
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
</script>