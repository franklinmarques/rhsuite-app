<div class="col-sm-12">
    <div id="alert-solicitar"></div>
</div>
<table class="table table-striped table-hover fill-head">
    <thead>
    <tr>
        <?php if ($this->session->userdata('tipo') == "administrador"): ?>
            <th>Público</th>
        <?php elseif ($this->session->userdata('tipo') == "empresa"): ?>
            <th>Tipo</th>
        <?php endif; ?>
        <th>Treinamento</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($query as $row): ?>
        <tr>
            <?php if ($this->session->userdata('tipo') == 'administrador'): ?>
                <td><?= $row->publico == 1 ? 'Sim' : 'Não'; ?></td>
            <?php elseif ($this->session->userdata('tipo') == 'empresa'): ?>
                <?php if ($row->id_empresa == $this->session->userdata('id')): ?>
                    <td><p>Desenvolvido</p></td>
                <?php elseif ($row->tipo == "administrador" && $row->publico == 1): ?>
                    <td><p>Gratuito</p></td>
                <?php else: ?>
                    <td><p><?= $row->tipo_curso > 0 ? 'Comprado' : 'À venda' ?></p></td>
                <?php endif; ?>
            <?php endif; ?>
            <td><?php echo $row->nome; ?></td>
            <td>
                <?php if ($this->session->userdata('tipo') == "administrador" || $row->id_empresa == $this->session->userdata('id')): ?>
                    <a class="btn btn-success btn-sm" href="<?= site_url('ead/pagina_curso/index/' . $row->id); ?>">
                        <i class="fa fa-file-text"></i> Páginas
                    </a>
                    <?php if ($row->qtde_paginas > 0): ?>
                        <a class="btn btn-info btn-sm" href="<?= site_url('ead/cursos/preview/' . $row->id); ?>"
                           target="_blank">
                            <i class="glyphicon glyphicon-eye-open"></i> Preview
                        </a>
                    <?php else: ?>
                        <button class="btn btn-info btn-sm disabled">
                            <i class="glyphicon glyphicon-eye-open"></i> Preview
                        </button>
                    <?php endif; ?>
                    <a class="btn btn-primary btn-sm" href="<?= site_url('ead/cursos/editar/' . $row->id); ?>">
                        <i class="fa fa-edit"></i> Editar
                    </a>
                    <button class="btn btn-info btn-sm" onclick="copiaCursos(<?= $row->id; ?>);">
                        <i class="fa fa-copy"></i> Copiar
                    </button>
                    <?php if ($row->status == 1): ?>
                        <button class="btn btn-warning btn-sm" onclick="statusCursos('0', <?= $row->id; ?>);">
                            <i class="fa fa-eye-slash"></i> Ocultar&nbsp;
                        </button>
                    <?php else: ?>
                        <button class="btn btn-success btn-sm" onclick="statusCursos('1', <?= $row->id; ?>);">
                            <i class="fa fa-eye"></i> Publicar
                        </button>
                    <?php endif; ?>
                    <button class="btn btn-danger btn-sm excluir" onclick="ajax_delete('<?= $row->id ?>')">
                        <i class="fa fa-trash"></i> Excluir
                    </button>
                <?php else: ?>
                    <?php if ($row->tipo_curso > 0): ?>
                        <button class="btn btn-default btn-sm" onclick="detalhesCursos(<?= $row->id; ?>);"
                                style="background-color: #3F5AA5;">
                            <i class="glyphicon glyphicon-list"></i> Ficha do treinamento
                        </button>
                    <?php elseif ($row->tipo_curso == 0): ?>
                        <button class="btn btn-default btn-sm" onclick="detalhesCursos(<?= $row->id; ?>);"
                                style="background-color: #3F5AA5;">
                            <i class="glyphicon glyphicon-list"></i> Ficha do treinamento
                        </button>
                        <button class="btn btn-default btn-sm" onclick="solicitaCursos(<?= $row->id; ?>);"
                                style="background-color: #B40ECF;">
                            <i class="fa fa-shopping-cart"></i> Comprar
                        </button>
                    <?php else: ?>
                        <p>-</p>
                    <?php endif; ?>
                <?php endif; ?>
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
    <?php // if (count($query) != $total && count($query) !== 0): ?>
    <!--            <tr>
                <th colspan="3">Total de treinamentos encontrados: <?php // echo count($query);          ?></th>
            </tr>-->
    <?php // endif; ?>

    </tbody>
</table>
<div class="text-center" data-html="html-cursos" data-query="<?php echo $busca; ?>">
    <?php echo $this->pagination->create_links(); ?>
</div>
<!-- Modal -->
<div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog' style="width: 99%;">
        <div class='modal-content'>
            <div class='modal-header'>
                <h4 class='modal-title' id='myModalLabel' style='text-align: center !important;'>
                    Ficha do Treinamento
                    <a href="javascript:void(0);" class='fa fa-remove' data-dismiss='modal' id='fechaModal'
                       style="float: right;"></a>
                </h4>
            </div>
            <div class='modal-body' id="getDetalhes" style="text-align: justify;">
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

    function copiaCursos(id) {
        if (confirm('Deseja copiar esse curso?')) {
            $.ajax({
                'url': '<?php echo site_url('ead/cursos/duplicar') ?>',
                'type': 'POST',
                'dataType': 'json',
                'timeout': 9000,
                'data': {
                    'id': id
                },
                'success': function (json) {
                    if (json === 'sucesso') {
                        window.location.reload();
                    } else {
                        alert(json);
                    }
                }
            });
        }
    }

    function statusCursos(status, id) {
        if (confirm('Deseja alterar a situação desse curso?')) {
            $.ajax({
                'url': '<?php echo site_url('ead/cursos/status') ?>/' + status + '/' + id,
                'dataType': 'json',
                'success': function (json) {
                    if (json === 'sucesso') {
                        window.location.reload();
                    } else {
                        alert(json);
                    }
                }
            });
        }
    }

    function detalhesCursos(id) {
        if (id > 0) {
            $.ajax({
                'url': '<?php echo site_url('ead/cursos/detalhes'); ?>/' + id,
                'dataType': 'json',
                'success': function (json) {
                    $('#getDetalhes').html(json);
                    $('#myModal').modal('show');
                }
            });
        }
    }

    function solicitaCursos(id) {
        if (confirm('Tem certeza que deseja solicitar esse curso para o administrador?')) {
            var aviso = $('#alert-solicitar');

            $.ajax({
                'url': '<?php echo site_url('ead/cursos/solicitar') ?>',
                'type': 'POST',
                'dataType': 'json',
                'timeout': 9000,
                'data': {
                    'id': id
                },
                'beforeSend': function () {
                    $('html, body').animate({'scrollTop': 0}, 1500);
                    aviso.html('<div class="alert alert-info">Carregando...</div>').hide().fadeIn('slow');
                },
                'error': function () {
                    aviso.html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                },
                'success': function (json) {
                    $('html, body').animate({'scrollTop': 0}, 1500);
                    if (parseInt(json['retorno'])) {
                        aviso.html('<div class="alert alert-success">' + json['aviso'] + '</div>').hide().fadeIn('slow', function () {
                            if (parseInt(json['redireciona']))
                                window.location = json['pagina'];
                        });
                    } else {
                        aviso.html('<div class="alert alert-danger">' + json['aviso'] + '</div>').hide().fadeIn('slow');
                    }
                }
            });
        }
    }

    function ajax_delete(id) {
        if (confirm('Tem certeza que deseja excluir esse curso?')) {
            // ajax delete data to database
            $.ajax({
                'url': '<?php echo site_url('ead/cursos/ajax_delete') ?>',
                'type': 'POST',
                'dataType': 'json',
                'timeout': 9000,
                'data': {
                    'id': id
                },
                'success': function (data) {
                    //if success reload ajax table
                    location.reload();
                }
            });

        }
    }
</script>
