<div id="alert-solicitar" style="margin-top:40px;" ></div>
<table class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <th>Treinamento</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($query->result() as $row) {
            ?>
            <tr>
                <td><?php echo $row->curso; ?></td>
                <td>
                    <a class="btn btn-warning btn-sm" href="javascript:void(0);"
                       onclick="detalhesCursos(<?= $row->id; ?>);"><i
                            class="glyphicon glyphicon-align-justify"></i> Ficha do Treinamento</a>
                    <a class="btn btn-info btn-sm solicitar" href="#"
                       data-href="<?php echo site_url('home/solicitarcurso_json/' . $row->id); ?>"
                       data-aviso="alert-solicitar"><i class="fa fa-shopping-cart"></i> Solicitar</a>
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
        /*
          <tr>
          <th colspan="3">Total de treinamentos: <?php echo $total; ?></th>
          </tr>
         */
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
<div class="text-center" data-html="html-solicitar-cursos" data-query="<?php echo $busca; ?>">
    <?php echo $this->pagination->create_links(); ?>
</div>
<div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog' style="width: 80%; line-height: 70%;">
        <div class='modal-content'>
            <div class='modal-header'>
                <h4 class='modal-title' id='myModalLabel' style='text-align: center !important;'>Ficha do
                    Treinamento</h4>
            </div>
            <div class='modal-body' id="getDetalhes" style="line-height: normal;">
            </div>
            <div class='modal-footer' style="margin-top: 0;">
                <button type='button' class='btn btn-default' data-dismiss='modal' id='fechaModal'>
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('.solicitar').click(function () {
            if (confirm('Tem certeza que deseja solicitar esse curso para o administrador?')) {
                var aviso = $('#' + $(this).data('aviso'));

                $.ajax({
                    url: $(this).data('href'),
                    type: 'GET',
                    data: '',
                    beforeSend: function () {
                        $('html, body').animate({scrollTop: 0}, 1500);
                        aviso.html('<div class="alert alert-info">Carregando...</div>').hide().fadeIn('slow');
                    },
                    error: function () {
                        aviso.html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    },
                    success: function (data) {
                        $('html, body').animate({scrollTop: 0}, 1500);
                        if (parseInt(data['retorno'])) {
                            aviso.html('<div class="alert alert-success">' + data['aviso'] + '</div>').hide().fadeIn('slow', function () {
                                if (parseInt(data['redireciona']))
                                    window.location = data['pagina'];
                            });
                        } else {
                            aviso.html('<div class="alert alert-danger">' + data['aviso'] + '</div>').hide().fadeIn('slow');
                            //aviso.html('<div class="alert alert-success" style="text-align:center"><h5><i class="fa fa-check" aria-hidden="true"></i> ' + data['aviso'] + '</h5></div>').hide().fadeIn('slow');
                        }
                    }
                });
            }
            return false;
        });

        $('.pagination li a').click(function () {
            if ($(this).attr('href') === "#")
                return false;

            ajax_post($(this).attr('href'), $(this).parent().parent().parent().data('query'), $('#' + $(this).parent().parent().parent().data('html')));
            return false;
        });
    });

    function detalhesCursos(id) {
        if (id > 0) {
            var url = '<?php echo site_url('curso/detalhesCurso_json'); ?>/' + id;
            $.ajax({
                url: url,
                dataType: 'json',
                success: function (data) {
                    $('#getDetalhes').html(data);
                    $('#myModal').modal('show');
                }
            });
        }
    }
</script>