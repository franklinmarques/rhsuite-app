<table class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <th>Treinamento</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $perguntas = array();
        $total_acertos = 0;

        foreach ($query->result() as $row) {
            /* Calcular avaliação final */
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
                    $total_acertos += ($total_atividades / $questao['alternativas']) * $questao['acertos'];
                }
            }
            ?>
            <tr>
                <td><?php echo $row->curso; ?></td>
                <td>
                    <?php if ($row->data_maxima >= date('Y-m-d') || empty($row->data_maxima)) {
                        ?>
                        <a class="btn btn-warning btn-sm" target="_blank" href="<?php echo site_url('home/acessarcurso/' . $row->id); ?>"><i
                                class="fa fa-book"></i> Acessar</a>
                            <?php
                        } else {
                            ?>
                        <a class="btn btn-warning btn-sm" href="javascript:void(0);"><i
                                class="fa fa-book"></i> Acessar</a>
                            <?php
                        }
                        ?>
                    <a class="btn btn-info btn-sm"
                       href="<?php echo site_url('curso/status_treinamento/' . $this->session->userdata['id'] . '/' . $row->id); ?>"><i
                            class="glyphicon glyphicon-align-center"></i> Andamento</a>
                        <?php if ($total_acertos >= 75) {
                            ?>
                        <a class="btn btn-success btn-sm"
                           href="<?php echo site_url('certificado/emissaoCertificado/' . $row->id . '/' . $this->session->userdata['id']); ?>"
                           target="_blank">
                            <i class="fa fa-print"></i> Certificado</a>
                        <?php
                    }
                    if ($row->data_maxima <> '' || !empty($row->data_maxima)) {
                        ?>
                        <span style="color: red; margin-left: 2%;"><i
                                class="fa fa-calendar-o"></i> Data limite de acesso = <?= implode("/", array_reverse(explode("-", $row->data_maxima))); ?></span>
                            <?php
                        } else {
                            ?>
                        <span style="color: red; margin-left: 2%;"><i
                                class="fa fa-calendar-o"></i> Sem data limite de acesso</span>
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
                <th colspan="3">Nenhuma curso encontrado</th>
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
                <th colspan="3">Total de treinamentos encontradas: <?php echo $query->num_rows(); ?></th>
            </tr>
            <?php
        }
        ?>
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