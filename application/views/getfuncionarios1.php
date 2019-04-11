<table class="table table-striped table-hover fill-head">
    <thead>
    <tr>
        <th>Funcionário</th>
        <th>Status</th>
        <th>Depto/área/setor</th>
        <th>Nível acesso</th>
        <th>Matrícula</th>
        <th>Cargo</th>
        <th>Função</th>
        <th>Ação</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($query->result() as $row) {
        ?>
        <tr>
            <td><?php echo $row->nome; ?></td>
            <td nowrap><?php switch ($row->status): case '1':
                    echo 'Ativo';
                    break;
                    case '2':
                        echo 'Inativo';
                        break;
                    case '3':
                        echo 'Em experiência';
                        break;
                    case '4':
                        echo 'Em desligamento';
                        break;
                    case '5':
                        echo 'Desligado';
                        break;
                    case '6':
                        echo 'Afastado (maternidade)';
                        break;
                    case '7':
                        echo 'Afastado (aposentadoria)';
                        break;
                    case '8':
                        echo 'Afastado (doença)';
                        break;
                    case '9':
                        echo 'Afastado (acidente)';
                        break;
                    case '10':
                        echo 'Desisitu da vaga';
                        break;
                endswitch; ?></td>
            <td><?php echo implode('/', array_filter(array($row->depto, $row->area, $row->setor))); ?></td>
            <td nowrap><?php switch ($row->nivel_acesso): case '1':
                    echo 'Administrador';
                    break;
                    case  '7':
                        echo 'Presidente';
                        break;
                    case '8':
                        echo 'Gerente';
                        break;
                    case '9':
                        echo 'Coordenador';
                        break;
                    case '15':
                        echo 'Representante';
                        break;
                    case '10':
                        echo 'Supervisor';
                        break;
                    case '11':
                        echo 'Encarregado';
                        break;
                    case '12':
                        echo 'Líder';
                        break;
                    case '4':
                        echo 'Colaborador CLT';
                        break;
                    case '16':
                        echo 'Colaborador MEI';
                        break;
                    case '14':
                        echo 'Colaborador PJ';
                        break;
                    case '13':
                        echo 'Cuidador Comunitário';
                        break;
                    case '3':
                        echo 'Gestor';
                        break;
                    case '2':
                        echo 'Multiplicador';
                        break;
                    case '6':
                        echo 'Selecionador';
                        break;
                    case '5':
                        echo 'Cliente';
                        break;
                endswitch; ?></td>
            <td><?php echo $row->matricula; ?></td>
            <td><?php echo $row->cargo; ?></td>
            <td><?php echo $row->funcao; ?></td>
            <td nowrap>
                <a class="btn btn-primary btn-xs" href="<?php echo site_url('funcionario/editar/' . $row->id); ?>">
                    <i class="fa fa-edit"></i>Editar</a>
                <a class="btn btn-danger btn-xs excluir"
                   href="<?php echo site_url('home/excluirfuncionario/' . $row->id); ?>" onclick="if (!confirm('Tem certeza que deseja excluir esse funcionário?'))
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
            <th colspan="7">Nenhum funcionário encontrado</th>
        </tr>
        <?php
    }
    ?>
    <tr>
        <th colspan="7">Total de funcionários: <?php echo $total; ?></th>
    </tr>
    <?php
    if ($query->num_rows() != $total && $query->num_rows() !== 0) {
        ?>
        <tr>
            <th colspan="7">Total de funcionários encontrados: <?php echo $query->num_rows(); ?></th>
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