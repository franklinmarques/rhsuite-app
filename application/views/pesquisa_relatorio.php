<?php
require_once "header.php";
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

        tr.group, tr.group:hover {
            background-color: #ddd !important;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div style="color: #000;">
                <table class="table table-condensed pesquisa">
                    <thead>
                    <?php if (isset($avaliado)): ?>
                        <tr>
                            <th style="width: 100%; vertical-align: top;" colspan="4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                                             style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                        <p class="text-left">
                                            <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>"
                                                 align="left"
                                                 style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                        </p>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4">
                                <h1 class="text-center">PESQUISA DE PERFIL PROFISSIONAL</h1>
                            </th>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <th style="width: 100%; vertical-align: top;" colspan="3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <img src="<?= base_url('imagens/usuarios/LOGOAME-TP.png') ?>" align="left"
                                             style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                        <p class="text-left">
                                            <img src="<?= base_url('imagens/usuarios/Descricao_AME.png') ?>"
                                                 align="left"
                                                 style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                        </p>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <h1 class="text-center">PESQUISA DE CLIMA ORGANIZACIONAL</h1>
                            </th>
                        </tr>
                    <?php endif; ?>
                    </thead>
                    <tbody>
                    <tr style='border-top: 5px solid #ddd;'>
                        <td nowrap>
                            <h5><strong>Avaliação: </strong><?= $pesquisa->nome ?></h5>
                            <h5><strong>Data atual: </strong><?= date('d/m/Y') ?></h5>
                        </td>
                        <td nowrap colspan="<?= isset($avaliado) ? '2' : '1' ?>">
                            <h5><strong>Data início pesquisa: </strong><?= $pesquisa->data_inicio ?></h5>
                            <h5><strong>Data término pesquisa: </strong><?= $pesquisa->data_termino ?></h5>
                        </td>
                        <td class="text-right">
                            <?php if (isset($avaliado)): ?>
                                <a id="pdf" class="btn btn-sm btn-danger"
                                   href="<?= site_url('pesquisa_avaliados/pdfRelatorio/' . $this->uri->rsegment(3) . '/' . $this->uri->rsegment(4)); ?>"
                                   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar
                                    PDF</a>
                            <?php else: ?>
                                <a id="pdf" class="btn btn-sm btn-danger"
                                   href="<?= site_url('pesquisa/pdfRelatorio/' . $this->uri->rsegment(3)); ?>"
                                   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar
                                    PDF</a>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                        </td>
                    </tr>

                    <?php if (isset($avaliado)): ?>
                        <tr style='border-top: 5px solid #ddd;'>
                            <th>Colaborador alvo da pesquisa</th>
                            <th>Função</th>
                            <th>Depto/área/setor</th>
                            <th>Data de início das atividades</th>
                        </tr>
                        <tr>
                            <td><?= $avaliado->nome ?></td>
                            <td><?= $avaliado->funcao ?></td>
                            <td><?= $avaliado->depto ?></td>
                            <td><?= $avaliado->data_admissao ?></td>
                        </tr>

                        <?php if (isset($selecionado)): ?>
                            <tr style='border-top: 5px solid #ddd;'>
                                <th>Colaborador pesquisado</th>
                                <th>Função</th>
                                <th>Depto/área/setor</th>
                                <th>Data de início das atividades</th>
                            </tr>
                            <tr>
                                <td><?php echo form_dropdown('avaliador', $avaliadores, $selecionado->id, 'id="avaliador" class="form-control filtro input-sm"'); ?></td>
                                <td id="funcao"><?= $selecionado->funcao ?></td>
                                <td id="depto"><?= $selecionado->depto ?></td>
                                <td id="data_admissao"><?= $selecionado->data_admissao ?></td>
                            </tr>
                        <?php else: ?>
                            <?php if (!($is_pdf and $omitirAvaliadores)): ?>
                                <tr style='border-top: 5px solid #ddd;'>
                                    <?php if ($is_pdf): ?>
                                        <th colspan="4">Colaboradores pesquisados</th>
                                    <?php else: ?>
                                        <th>Colaboradores pesquisados</th>
                                        <th colspan="3">
                                            <div class="checkbox" style="margin: 0px;">
                                                <label>
                                                    <input id="avaliadores" type="checkbox" autocomplete="off"> Omitir
                                                    os avaliadores no relatório impresso
                                                </label>
                                            </div>
                                        </th>
                                    <?php endif; ?>
                                </tr>
                                <tr style='border-bottom: 5px solid #ddd;'>
                                    <td colspan="4">
                                        <ol>
                                            <?php foreach ($avaliadores as $avaliador): ?>
                                                <li><?= $avaliador ?></li>
                                            <?php endforeach; ?>
                                        </ol>
                                    </td>
                                </tr>
                            <?php endif; ?>

                        <?php endif; ?>
                    <?php else: ?>
                        <tr style='border-top: 5px solid #ddd;'>
                            <th colspan="3">Departamentos/áreas/setores participantes:</th>
                        </tr>
                        <tr>
                            <td>Departamentos:</td>
                            <td colspan="2"><?= implode(', ', $depto) ?></td>
                        </tr>
                        <tr>
                            <td>Áreas:</td>
                            <td colspan="2"><?= implode(', ', $area) ?></td>
                        </tr>
                        <tr style='border-bottom: 5px solid #ddd;'>
                            <td>Setores:</td>
                            <td colspan="2"><?= implode(', ', $setor) ?></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>

                <?php if (isset($avaliado)): ?>
                    <!--<div class="table-responsive">-->
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%"
                           style="border-radius: 0 !important;">
                        <thead>
                        <tr class="active">
                            <th>Perguntas</th>
                            <th>Critérios de pesquisa</th>
                            <th>Peso</th>
                            <th>Resposta</th>
                            <th>Consolidado (abs)</th>
                            <th>Consolidado (%)</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <!--</div>-->

                <?php else: ?>

                    <div class="row">
                        <div class="col-xs-4">
                            <label class="control-label">Filtrar por departamento</label>
                            <div class="controls">
                                <?php echo form_dropdown('depto', array('' => 'Todos') + $depto, $pesquisa->depto, 'id="depto" class="form-control filtro input-sm"'); ?>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <label class="control-label">Filtrar por área</label>
                            <div class="controls">
                                <?php echo form_dropdown('area', array('' => 'Todos') + $area, $pesquisa->area, 'id="area" class="form-control filtro input-sm"'); ?>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <label class="control-label">Filtrar por setor</label>
                            <div class="controls">
                                <?php echo form_dropdown('setor', array('' => 'Todos') + $setor, $pesquisa->setor, 'id="setor" class="form-control filtro input-sm"'); ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!--<div class="table-responsive">-->
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%"
                           style="border-radius: 0 !important;">
                        <thead>
                        <tr class="active">
                            <th>Categoria</th>
                            <th>Critérios de pesquisa</th>
                            <?php foreach ($alternativas as $alternativa): ?>
                                <th nowrap class="text-center"><?= $alternativa->alternativa ?></th>
                            <?php endforeach; ?>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <!--</div>-->
                <?php endif; ?>

            </div>
        </section>
    </section>
    <!--main content end-->

<?php
require_once "end_js.php";
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Pesquisa de ' + "<?= isset($avaliado) ? 'Perfil Profissional' : 'Clima Organizacional' ?>";
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

    <script>

        var table;
        var avaliado = <?= isset($avaliado) ? 'true' : 'false' ?>;
        var selecionado = <?= isset($selecionado) ? 'true' : 'false' ?>;

        $(document).ready(function () {

            if (avaliado === true) {
                var url = "<?php echo site_url('pesquisa_avaliados/ajax_relatorio/' . (isset($avaliado) ? $avaliado->id : '')) ?>";
                if (selecionado === true) {
                    $('#pdf').prop('href', "<?= site_url('pesquisa_avaliados/pdfRelatorio/' . $this->uri->rsegment(3) . (isset($selecionado) ? '/q?avaliador=' . $selecionado->id : '')); ?>");
                }
            } else {
                var url = "<?php echo site_url('pesquisa/ajax_relatorio/' . $pesquisa->id) ?>";
                $('.filtro').val('');
            }

            //datatables
            table = $('#table').DataTable({
                "info": false,
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                searching: false,
                lengthChange: false,
                paging: false,
                ordering: false,
//            "paginate": false,
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": url,
                    "type": "POST",
                    data: function (d) {
                        if (avaliado === true) {
                            d.avaliador = $('#avaliador').val();
                        } else {
                            d.depto = $('#depto').val();
                            d.area = $('#area').val();
                            d.setor = $('#setor').val();
                        }
                    },
                    dataSrc: function (json) {
                        if (json.draw > 1 && json.selecionado !== undefined) {
                            $('#funcao').html(json.selecionado.funcao);
                            $('#depto').html(json.selecionado.depto);
                            $('#data_admissao').html(json.selecionado.data_admissao);
                        }
                        return json.data;
                    }
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        visible: false,
                        targets: (selecionado === true ? [0, 4, 5] : (avaliado === true ? [0, 3] : [0]))
                    },
                    {
                        className: 'text-left',
                        width: '100%',
                        targets: [1]
                    },
                    {
                        className: 'text-right',
                        targets: '_all'
                    },
                    {
                        render: function (data) {
                            return typeof (data) === 'number' ? data + '%' : data;
                        },
                        targets: (avaliado === true ? [-1] : '_all')
                    }
                ],
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({page: 'current'}).nodes();
                    var last = null;

                    api.column(0, {page: 'current'}).data().each(function (group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr class="success"><th colspan="<?= isset($alternativas) ? count($alternativas) + 1 : '5' ?>">' + group + '</th></tr>'
                            );
                            last = group;
                        }
                    });
                }
            });

        });


        $('.filtro').on('change', function () {
            setPdfAttributes();
        });

        $('#avaliadores').on('change', function () {
            setPdfAttributes();
        });

        function setPdfAttributes() {
            table.ajax.reload();

            var search = '';
            var q = new Array();

            $('.filtro').each(function (i, v) {
                if (v.value.length > 0) {
                    q[i] = v.name + "=" + v.value;
                }
            });
            if ($('#avaliadores').is(':checked')) {
                q.push("omitirAvaliadores=1");
            }
            if (q.length > 0) {
                search = '/q?' + q.join('&');
            }


            if (avaliado === true) {
                $('#pdf').prop('href', "<?= site_url('pesquisa_avaliados/pdfRelatorio/' . $this->uri->rsegment(3) . '/' . $this->uri->rsegment(4)); ?>" + search);
            } else {
                $('#pdf').prop('href', "<?= site_url('pesquisa/pdfRelatorio/' . $this->uri->rsegment(3) . '/' . $this->uri->rsegment(4)); ?>" + search);
            }
        }

    </script>

<?php
require_once "end_html.php";
?>