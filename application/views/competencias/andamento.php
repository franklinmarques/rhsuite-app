<?php
require_once APPPATH . "views/header.php";
?>
<style>
    .btn-success {
        background-color: #5cb85c;
        border-color: #4cae4c;
        color: #fff;
    }

    .btn-primary {
        background-color: #337ab7 !important;
        border-color: #2e6da4 !important;
        color: #fff;
    }

    .btn-info {
        color: #fff;
        background-color: #5bc0de;
        border-color: #46b8da;
    }

    .btn-warning {
        color: #fff;
        background-color: #f0ad4e;
        border-color: #eea236;
    }

    .btn-danger {
        color: #fff;
        background-color: #d9534f;
        border-color: #d43f3a;
    }

    .text-nowrap {
        white-space: nowrap;
    }
</style>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <!-- page start-->
        <table>
            <tr>
                <td>
                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="vertical-align: top;">
                    <p>
                        <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    </p>
                </td>
            </tr>
        </table>
        <div class="row">
            <div class="col col-md-12">
                <h1 class="text-center">Avaliação de Desempenho - Andamento</h1>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col col-md-9">
                <label>Avaliação: </label> <?= $dadosAvaliacao->nome; ?><br/>
                <label>Período de avaliação: </label>
                <span class="<?= ($dadosAvaliacao->data_valida == 'ok' ? '' : 'text-danger'); ?>">
                    <?= $dadosAvaliacao->data_inicio . ' a ' . $dadosAvaliacao->data_termino; ?>
                </span><br/>
                <label>Data atual: </label> <?= $dadosAvaliacao->data_atual; ?><br/>
            </div>
            <div class="col col-md-3 text-right">
                <?php if ($is_pdf == false): ?>
                    <a id="pdf" class="btn btn-sm btn-danger"
                       href="<?= site_url('competencias/relatorios/pdfAndamento/' . $this->uri->rsegment(3)); ?>"
                       title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                    <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <hr/>
        <?php if ($is_pdf == false): ?>
            <div class="row">
                <div class="col col-md-12">
                    <label>Exibir status: </label>&nbsp;
                    <label class="radio-inline">
                        <input type="radio" name="status" class="status" value="" onchange="filtrar_status()"
                               checked=""> Todos
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" class="status" onchange="filtrar_status()" value="avaliado">
                        Somente avaliados
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" class="status" onchange="filtrar_status()" value="avaliar">
                        Somente a avaliar
                    </label>
                </div>
            </div>
            <br>
        <?php endif; ?>
        <div class="row">
            <div class="col col-md-12">
                <?php if (empty($dadosAvaliadores)): ?>
                    <span class="text-center text-muted"><h3>Nenhuma competência avaliada</h3></span>
                <?php endif; ?>
                <?php foreach ($dadosAvaliadores as $id => $competencia) : ?>
                    <div class="panel panel-success">
                        <div class="panel-heading"
                             style="color: #3c763d !important; background-color: #dff0d8 !important;">
                            <label><?= $competencia->nome ?></label></div>
                        <div class="panel-body">
                            <table id="table_<?= $competencia->id ?>" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Avaliador</th>
                                    <th>Avaliado</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($competencia->dimensao as $dadosAvaliacao) : ?>
                                    <tr>
                                        <td>
                                            <?= $dadosAvaliacao['avaliador']; ?>
                                        </td>
                                        <td>
                                            <?= $dadosAvaliacao['avaliado']; ?>
                                        </td>
                                        <td class="<?= ($dadosAvaliacao['status'] === 'Avaliado' ? 'text-success' : 'text-danger'); ?>">
                                            <strong><?= $dadosAvaliacao['status']; ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        </div>
        </div>
        <!-- page end-->

    </section>
</section>
<!--main content end-->

<?php
require_once APPPATH . "views/end_js.php";
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Avaliação de Desempenho - Andamento';
        $('status').val('');
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

<script>

    var tables = new Object();

    <?php foreach ($dadosAvaliadores as $id => $competencia) : ?>
    tables[<?= $competencia->id ?>] = $('#table_' + '<?= $competencia->id ?>').DataTable({
        "info": false,
        "processing": true, //Feature control the processing indicator.
        "serverSide": false, //Feature control DataTables' server-side processing mode.
        searching: false,
        lengthChange: false,
        paging: false,
        ordering: false,
        "paginate": false,
        "language": {
            "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
        },
        "columnDefs": [
            {
                width: '50%',
                targets: [0, 1]
            },
            {
                className: 'text-center',
                render: function (data) {
                    if (data === 'Avaliado') {
                        data = '<strong class="text-success">' + data + '</strong>';
                    } else if (data === 'Avaliar') {
                        data = '<strong class="text-danger">' + data + '</strong>';
                    }
                    return data;
                },
                targets: [2]
            }
        ]
    });
    <?php endforeach; ?>

    function filtrar_status() {
        var status = $('.status:checked').val();

        $.ajax({
            url: "<?php echo site_url('competencias/relatorios/ajax_andamento/') ?>/",
            type: "POST",
            dataType: "JSON",
            timeout: 9000,
            data: {
                id: '<?= $this->uri->rsegment(3, 0) ?>',
                status: status
            },
            beforeSend: function () {
                $('.status').attr('disabled', true);
            },
            success: function (data) {
                if (data.length > 0) {
                    $(data).each(function (i, v) {
                        tables[v.id].clear().rows.add(v.dados).draw();
                    });
                } else {
                    $.each(tables, function (i) {
                        tables[i].clear().draw();
                    });
                }

                var search = '';
                if (status.length > 0) {
                    search = '/q?status=' + status;
                }

                $('#pdf').prop('href', "<?= site_url('competencias/relatorios/pdfAndamento/' . $this->uri->rsegment(3)); ?>" + search);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        }).done(function () {
            $('.status').attr('disabled', false);
        });
    }

</script>

<?php
require_once APPPATH . "views/end_html.php";
?>
