<div id="alert-solicitar" style="margin-top:40px;text-align:left;"></div>

<table class="table table-striped table-hover fill-head">
    <thead>
        <tr>
            <?php
            if ($this->session->userdata('tipo') == "administrador") {
                ?>
                <th>Público</th>
            <?php } else if ($this->session->userdata('tipo') == "empresa") { ?>
                <th>Tipo</th>
            <?php } ?>
            <th>Treinamento</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($query->result() as $row) {
            $tipo = "";
            ?>
            <tr>
                <?php if ($this->session->userdata('tipo') == "administrador") { ?>
                    <td><?php echo $row->publico == 1 ? "Sim" : "Não"; ?></td>
                <?php } else if ($this->session->userdata('tipo') == "empresa") { ?>
                    <td>
                        <?php if ($row->usuario == $this->session->userdata('id')) { ?>
                            <p>Desenvolvido</p>
                        <?php } else if ($row->tipo == "administrador" && $row->publico == 1) { ?>
                            <p>Gratuito</p>
                            <?php
                        } else {
                            $verificatipocurso = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ? AND curso = ?", array($this->session->userdata('id'), $row->id))->num_rows();
                            if ($verificatipocurso > 0) {
                                $tipo = "comprado";
                                ?>
                                <p>Comprado</p>
                                <?php
                            } else {
                                $tipo = "avenda";
                                ?>
                                <p>À venda</p>
                            <?php } ?>
                        <?php } ?>
                    </td>
                <?php } ?>
                <td><?php echo $row->curso; ?></td>
                <td>
                    <?php if ($this->session->userdata('tipo') == "administrador" || $row->usuario == $this->session->userdata('id')) { ?>
                        <a class="btn btn-success btn-sm" href="<?php echo site_url('home/paginascurso/' . $row->id); ?>"><i
                                class="fa fa-file-text"></i> Páginas</a>
                        <a class="btn btn-primary btn-sm" href="<?php echo site_url('home/editarcurso/' . $row->id); ?>"><i
                                class="fa fa-edit"></i> Editar</a>
                        <a class="btn btn-info btn-sm" href="javascript: void(0)"
                           onclick="copiaCursos(<?= $row->id; ?>);"><i class="fa fa-copy"></i> Copiar</a>
                           <?php
                           # Verificar status do curso
                           if ($row->status == 1) {
                               ?>
                            <a class="btn btn-warning btn-sm" href="javascript: void(0)"
                               onclick="statusCursos('0', <?= $row->id; ?>);">
                                <i class="fa fa-eye-slash"></i>
                                Ocultar
                            </a>
                            <?php
                        } else {
                            ?>
                            <a class="btn btn-success btn-sm" href="javascript: void(0)"
                               onclick="statusCursos('1', <?= $row->id; ?>);">
                                <i class="fa fa-eye"></i>
                                Publicar
                            </a>
                            <?php
                        }
                        ?>
                        <a class="btn btn-danger btn-sm excluir"
                           href="<?php echo site_url('home/excluircurso/' . $row->id); ?>" onclick="if (!confirm('Tem certeza que deseja excluir esse curso?'))
                                               return false;"><i class="fa fa-trash"></i> Excluir</a>
                       <?php } else { ?>
                           <?php if ($tipo == "comprado") { ?>
                            <a class="btn btn-default btn-sm" href="javascript:void(0);"
                               onclick="detalhesCursos(<?= $row->id; ?>);" style="background-color: #3F5AA5;"><i
                                    class="glyphicon glyphicon-list"></i> Ficha do treinamento</a>
                            <?php } else if ($tipo == "avenda") { ?>
                            <a class="btn btn-default btn-sm" href="javascript:void(0);"
                               onclick="detalhesCursos(<?= $row->id; ?>);" style="background-color: #3F5AA5;"><i
                                    class="glyphicon glyphicon-list"></i> Ficha do treinamento</a>
                            <a class="btn btn-default btn-sm solicitar" href="#"
                               data-href="<?php echo site_url('home/solicitarcurso_json/' . $row->id); ?>"
                               data-aviso="alert-solicitar" style="background-color: #B40ECF;"><i
                                    class="fa fa-shopping-cart"></i> Comprar</a>
                            <?php } else { ?>
                            <p>-</p>
                        <?php } ?>
                    <?php } ?>
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
        /*
          if ($query->num_rows() != $total && $query->num_rows() !== 0) {
          ?>
          <tr>
          <th colspan="3">Total de treinamentos encontrados: <?php echo $query->num_rows(); ?></th>
          </tr>
          <?php
          }
         */
        ?>
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
                       style="float: right;">
                    </a>
                </h4>
            </div>
            <div class='modal-body' id="getDetalhes" style="text-align: justify;">
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

    function copiaCursos(id) {
        if (confirm('Deseja copiar esse curso?')) {
            $.ajax({
                url: '<?php echo site_url('home/copiaCursos') ?>',
                data: 'valor=' + id,
                dataType: 'json',
                success: function (data) {
                    if (data === 'sucesso') {
                        window.location.reload();
                    } else {
                        alert(data);
                    }
                }
            });
        }
    }

    function statusCursos(status, id) {
        if (confirm('Deseja alterar a situação desse curso?')) {
            $.ajax({
                url: '<?php echo site_url('curso/statusCurso') ?>/' + status + '/' + id,
                dataType: 'json',
                success: function (data) {
                    if (data === 'sucesso') {
                        window.location.reload();
                    } else {
                        alert(data);
                    }
                }
            });
        }
    }

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