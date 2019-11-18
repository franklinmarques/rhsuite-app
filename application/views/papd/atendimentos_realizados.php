<?php
require_once APPPATH . "views/header.php";
?>
<style>
    /*    .modal, .modal-backdrop {
            overflow: auto;
            height: 100%;
        }    
        #main-content .modal, .modal-backdrop {
            position: absolute;
        }    
        #main-content .modal-backdrop {
            z-index: 1001;
        }    
        .wrapper {
            overflow: auto;
            position:relative;
            height: 90%;
            min-height: 600px;
        }
        #main-content {
            height: 100%;
        }*/
</style>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div style="color: #000;">
            <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                <li class="active">Relatório de Atendimentos Realizados</li>
            </ol>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="well well-sm">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label">Data início</label>
                                <input name="data_inicio" type="text" id="data_inicio" value="<?= $paciente->data_inicio ?>" placeholder="dd/mm/aaaa" class="form-control filtro input-sm text-center" autocomplete="false">
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Data término</label>
                                <input name="data_termino" type="text" id="data_termino" value="<?= $paciente->data_termino ?>" placeholder="dd/mm/aaaa" class="form-control filtro input-sm text-center" autocomplete="false">
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Atividade</label>
                                <?php echo form_dropdown('atividade', $atividades, '', 'id="atividade" class="form-control filtro input-sm" autocomplete="false"'); ?>
                            </div>
                            <div class="col-md-3 text-center">
                                <label>&nbsp;</label><br>
                                <button type="button" id="pesquisar" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-search"></i> Pesquisar</button>
                                <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">Limpar filtros</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-9">
                    <img src="<?= base_url('imagens/usuarios/LOGOAME-TP.png') ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    <p class="text-left">
                        <img src="<?= base_url('imagens/usuarios/Descricao_AME.png') ?>" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                        </p>
                </div>
                <div class="col-sm-3 text-right">
                    <a id="pdf" class="btn btn-sm btn-danger" href="<?= site_url('papd/relatorios/pdfAtendimentos_realizados/'); ?>" title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                    <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>
                </div>
            </div>
            <table class="table table-condensed avaliado">
                <thead>
                    <tr style='border-top: 5px solid #ddd;'>
                        <th colspan="3">
                            <?php if ($is_pdf == false): ?>
                                <h1 class="text-center"><strong>RELATÓRIO DE ATENDIMENTOS REALIZADOS</strong></h1>
                            <?php else: ?>
                                <h2 class="text-center"><strong>RELATÓRIO DE ATENDIMENTOS REALIZADOS</strong></h2>
                            <?php endif; ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Paciente: <?= $paciente->nome ?></td>
                        <td>Data início: <span id="texto_data_inicio"><?= $paciente->data_inicio ?></span></td>
                        <td>Data témino: <span id="texto_data_termino"><?= $paciente->data_termino ?></span></td>
                    </tr>
                    <?php if ($paciente->data_inicio and $paciente->data_termino): ?>
                        <tr>
                            <td>Deficiência: <?= $paciente->deficiencia ?></td>
                            <td colspan="2">Hipótese Diagnóstica: <?= $paciente->hipotese_diagnostica ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <br/>
            <!--<div class="table-responsive">-->
            <table id="table" class="table table-bordered table-condensed avaliacao">
                <thead>
                    <tr class="active">
                        <th class="text-center">Data</th>
                        <th class="text-center">Horário início</th>
                        <th class="text-center">Atividades/procedimentos</th>
                        <th class="text-center">Profissional</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <!--</div>-->
        </div>
    </section>
</section>

<?php
require_once APPPATH . "views/end_js.php";
?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Relatório de Atendimentos Realizados';
    });
</script>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

<script>
    var table;

    $(document).ready(function () {
        table = $('#table').DataTable({
            "searching": false,
            "bLengthChange": false,
            "processing": true,
            "serverSide": true,
            "iDisplayLength": 25,
            "ajax": {
                "url": "<?php echo site_url('papd/relatorios/ajax_atendimentos_realizados/') ?>",
                "type": "POST",
                data: function (d) {
                    d.id_paciente = '<?= $this->uri->rsegment(3, 0) ?>';
                    d.data_inicio = $('#data_inicio').val();
                    d.data_termino = $('#data_termino').val();
                    d.atividade = $('#atividade').val();

                    return d;
                },
                "dataSrc": function (json) {
                    $('#texto_data_inicio').html(json.medicao.data_inicio);
                    $('#texto_data_termino').html(json.medicao.data_termino);
                    setPdf_atributes();

                    return json.data;
                }
            },
            "columnDefs": [
                {
                    className: "text-center text-nowrap",
                    targets: [0, 1]
                },
                {
                    width: "50%",
                    targets: [2]
                },
                {
                    width: "30%",
                    targets: [3]
                }
            ]
        });

    });

    $('#data_inicio, #data_termino').mask('00/00/0000');

    $('#pesquisar').on('click', function () {
        var mes = $('[name="mes"] option:selected').text().toLowerCase();
        var ano = $('[name="ano"]').val();
        if (mes.length > 0 && ano.length > 0) {
            $('#mes_ano').html(mes + ' de ' + ano);
        }
        reload_table(true);
        setPdf_atributes();
    });

    function reload_table(reset = false)
    {
        table.ajax.reload(null, reset); //reload datatable ajax 
    }

    function setPdf_atributes() {
        var search = '';
        var q = new Array();

        $('.filtro').each(function (i, v) {
            if (v.value.length > 0) {
                q[i] = v.name + "=" + v.value;
            }
        });
        q[q.length] = 'order[0]=' + (table.order()[0][0] + 1) + '&order[1]=' + table.order()[0][1];

        q = q.filter(function (v) {
            return v.length > 0;
        });
        if (q.length > 0) {
            search = '/q?' + q.join('&');
        }

        $('#pdf').prop('href', "<?= site_url('papd/relatorios/pdfAtendimentos_realizados/' . $this->uri->rsegment(3)); ?>" + search);
    }
</script>

<?php
require_once APPPATH . "views/end_html.php";
?>
