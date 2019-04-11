<div class="col-sm-12">
    <div id="alert-solicitar"></div>                                
</div>
<table class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <th>Treinamento</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($query as $row): ?>
            <tr>
                <td><?php echo $row->nome; ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="detalhesCursos(<?= $row->id; ?>);">
                        <i class="glyphicon glyphicon-align-justify"></i> Ficha do treinamento
                    </button>
                    <!--<button class="btn btn-info btn-sm" onclick="solicitaCursos(<?/*= $row->id; */?>);">
                        <i class="fa fa-shopping-cart"></i> Solicitar
                    </button>-->
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (count($query) == 0): ?>
            <tr>
                <th colspan="3">Nenhum curso encontrado</th>
            </tr>
        <?php endif; ?>
        <tr>
            <th colspan="3">Total de treinamentos: <?php echo $total; ?></th>
        </tr>
        <?php if (count($query) != $total && count($query) !== 0): ?>
            <tr>
                <th colspan="3">Total de treinamentos encontrados: <?php echo count($query); ?></th>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<div class="text-center" data-html="html-solicitar-cursos" data-query="<?php echo $busca; ?>">
    <?php echo $this->pagination->create_links(); ?>
</div>
<div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog' style="width: 80%; line-height: 70%;">
        <div class='modal-content'>
            <div class='modal-header'>
                <h4 class='modal-title' id='myModalLabel' style='text-align: center !important;'>
                    Ficha do Treinamento
                </h4>
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
        $('.pagination li a').click(function () {
            if ($(this).attr('href') === "#")
                return false;

            ajax_post($(this).attr('href'), $(this).parent().parent().parent().data('query'), $('#' + $(this).parent().parent().parent().data('html')));
            return false;
        });
    });

    function detalhesCursos(id) {
        if (id > 0) {
            var url = '<?php echo site_url('ead/cursos/detalhes'); ?>/' + id;
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

    function solicitaCursos(id) {
        if (confirm('Tem certeza que deseja solicitar esse curso para o administrador?')) {
            var aviso = $('#alert-solicitar');

            $.ajax({
                url: "<?php echo site_url('ead/cursos/solicitar') ?>",
                type: "POST",
                dataType: "JSON",
                timeout: 9000,
                data: {
                    id: id
                },
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
                            if (parseInt(data['redireciona'])) {
                                window.location = data['pagina'];
                            }
                        });
                    } else {
                        aviso.html('<div class="alert alert-danger">' + data['aviso'] + '</div>').hide().fadeIn('slow');
                        //aviso.html('<div class="alert alert-success" style="text-align:center"><h5><i class="fa fa-check" aria-hidden="true"></i> ' + data['aviso'] + '</h5></div>').hide().fadeIn('slow');
                    }
                }
            });
        }
    }
</script>