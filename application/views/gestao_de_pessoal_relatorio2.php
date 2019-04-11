<?php
require_once "header.php";
?>

<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div style="color: #000;">
            <table class="table table-condensed pdi">
                <thead>
                <tr style='border-top: 5px solid #ddd;'>
                    <th>
                        <div class="row">
                            <div class="col-sm-10">
                                <img src="<?= base_url($foto) ?>" align="left"
                                     style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                <p class="text-left">
                                    <img src="<?= base_url($foto_descricao) ?>" align="left"
                                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                </p>
                            </div>
                            <div class="col-sm-2 text-right">
                                <?php if ($is_pdf == false): ?>
                                    <a href="<?= site_url('gestaoDePessoal/pdf/q?&ano=' . $ano); ?>"
                                       class="btn btn-info disabled"><i class="glyphicon glyphicon-print"></i> Imprimir</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <?php if ($is_pdf == false): ?>
                            <h2 class="text-center"><strong>CONSOLIDADO DE GESTÃO DE PESSOAS - <?= $ano; ?></strong>
                            </h2>
                        <?php else: ?>
                            <h3 class="text-center"><strong>CONSOLIDADO DE GESTÃO DE PESSOAS - <?= $ano; ?></strong>
                            </h3>
                        <?php endif; ?>
                    </td>
                </tr>
                </tbody>
            </table>


            <h4 style="color: #111343;"><strong>Consolidado de Quadro de Colaboradores</strong></h4>
            <table id="table_quadro_colaboradores" class="table table_gestao table-bordered table-condensed"
                   cellspacing="0" width="100%">
                <thead>
                <tr class="active">
                    <th>Departamento (unidade de negócios)</th>
                    <th class="meses_quadro_colaboradores">Jan</th>
                    <th class="meses_quadro_colaboradores">Fev</th>
                    <th class="meses_quadro_colaboradores">Mar</th>
                    <th class="meses_quadro_colaboradores">Abr</th>
                    <th class="meses_quadro_colaboradores">Mai</th>
                    <th class="meses_quadro_colaboradores">Jun</th>
                    <th class="meses_quadro_colaboradores">Jul</th>
                    <th class="meses_quadro_colaboradores">Ago</th>
                    <th class="meses_quadro_colaboradores">Set</th>
                    <th class="meses_quadro_colaboradores">Out</th>
                    <th class="meses_quadro_colaboradores">Nov</th>
                    <th class="meses_quadro_colaboradores">Dez</th>
                    <th>Média anual</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($quadroColaboradores as $row): ?>
                    <tr>
                        <?php for ($i = 0; $i < 14; $i++): ?>
                            <td><?= $row[$i]; ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <hr>
            <h4 style="color: #111343;"><strong>Consolidado de Requisições de Pessoal</strong></h4>
            <table id="table_requisicoes_pessoal" class="table table_gestao table-bordered table-condensed"
                   cellspacing="0" width="100%">
                <thead>
                <tr class="active">
                    <th rowspan="2" style="vertical-align: middle;">Mês</th>
                    <th colspan="2" class="text-center">Abertas</th>
                    <th colspan="2" class="text-center">Fechadas</th>
                    <th colspan="2" class="text-center">Suspensas</th>
                    <th class="text-center">Canceladas</th>
                </tr>
                <tr class="active">
                    <th class="text-center">RPs</th>
                    <th class="text-center">Vagas</th>
                    <th class="text-center">RPs</th>
                    <th class="text-center">Vagas</th>
                    <th class="text-center">RPs</th>
                    <th class="text-center">Vagas</th>
                    <th class="text-center">RPs</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($requisicoesPessoal as $row): ?>
                    <tr>
                        <?php for ($i = 0; $i < 8; $i++): ?>
                            <td><?= $row[$i]; ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr class="active">
                    <th>Total</th>
                    <th class="text-center"></th>
                    <th class="text-center"></th>
                    <th class="text-center"></th>
                    <th class="text-center"></th>
                    <th class="text-center"></th>
                    <th class="text-center"></th>
                    <th class="text-center"></th>
                </tr>
                </tfoot>
            </table>


            <hr>
            <h4 style="color: #111343;"><strong>Consolidado de Movimentação de Pessoal</strong></h4>
            <table id="table_turnover" class="table table_gestao table-bordered table-condensed" cellspacing="0"
                   width="100%">
                <thead>
                <tr class="active">
                    <th>Indicadores</th>
                    <th class="meses_turnover">Jan</th>
                    <th class="meses_turnover">Fev</th>
                    <th class="meses_turnover">Mar</th>
                    <th class="meses_turnover">Abr</th>
                    <th class="meses_turnover">Mai</th>
                    <th class="meses_turnover">Jun</th>
                    <th class="meses_turnover">Jul</th>
                    <th class="meses_turnover">Ago</th>
                    <th class="meses_turnover">Set</th>
                    <th class="meses_turnover">Out</th>
                    <th class="meses_turnover">Nov</th>
                    <th class="meses_turnover">Dez</th>
                    <th>Média anual</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($turnover as $row): ?>
                    <tr>
                        <?php for ($i = 0; $i < 14; $i++): ?>
                            <td><?= $row[$i]; ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>


            <hr>
            <h4 style="color: #111343;"><strong>Consolidado do Quadro de Afastados</strong></h4>
            <table id="table_afastamentos" class="table table_gestao table-bordered table-condensed" cellspacing="0"
                   width="100%">
                <thead>
                <tr class="active">
                    <th>Indicadores</th>
                    <th class="meses_afastamentos">Jan</th>
                    <th class="meses_afastamentos">Fev</th>
                    <th class="meses_afastamentos">Mar</th>
                    <th class="meses_afastamentos">Abr</th>
                    <th class="meses_afastamentos">Mai</th>
                    <th class="meses_afastamentos">Jun</th>
                    <th class="meses_afastamentos">Jul</th>
                    <th class="meses_afastamentos">Ago</th>
                    <th class="meses_afastamentos">Set</th>
                    <th class="meses_afastamentos">Out</th>
                    <th class="meses_afastamentos">Nov</th>
                    <th class="meses_afastamentos">Dez</th>
                    <th>Média anual</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($afastamentos as $row): ?>
                    <tr>
                        <?php for ($i = 0; $i < 14; $i++): ?>
                            <td><?= $row[$i]; ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>


            <hr>
            <h4 style="color: #111343;"><strong>Consolidado de Faltas e Atrasos</strong></h4>
            <table id="table_faltas_atrasos" class="table table_gestao table-bordered table-condensed"
                   cellspacing="0" width="100%">
                <thead>
                <tr class="active">
                    <th rowspan="2" class="text-center">Departamento (unidade de negócios)</th>
                    <th colspan="2" class="text-center">Jan</th>
                    <th colspan="2" class="text-center">Fev</th>
                    <th colspan="2" class="text-center">Mar</th>
                    <th colspan="2" class="text-center">Abr</th>
                    <th colspan="2" class="text-center">Mai</th>
                    <th colspan="2" class="text-center">Jun</th>
                    <th colspan="2" class="text-center">Jul</th>
                    <th colspan="2" class="text-center">Ago</th>
                    <th colspan="2" class="text-center">Set</th>
                    <th colspan="2" class="text-center">Out</th>
                    <th colspan="2" class="text-center">Nov</th>
                    <th colspan="2" class="text-center">Dez</th>
                    <th colspan="2" class="text-center">Média anual</th>
                </tr>
                <tr class="active">
                    <th class="meses_faltas">F</th>
                    <th class="meses_atrasos">A</th>
                    <th class="meses_faltas">F</th>
                    <th class="meses_atrasos">A</th>
                    <th class="meses_faltas">F</th>
                    <th class="meses_atrasos">A</th>
                    <th class="meses_faltas">F</th>
                    <th class="meses_atrasos">A</th>
                    <th class="meses_faltas">F</th>
                    <th class="meses_atrasos">A</th>
                    <th class="meses_faltas">F</th>
                    <th class="meses_atrasos">A</th>
                    <th class="meses_faltas">F</th>
                    <th class="meses_atrasos">A</th>
                    <th class="meses_faltas">F</th>
                    <th class="meses_atrasos">A</th>
                    <th class="meses_faltas">F</th>
                    <th class="meses_atrasos">A</th>
                    <th class="meses_faltas">F</th>
                    <th class="meses_atrasos">A</th>
                    <th class="meses_faltas">F</th>
                    <th class="meses_atrasos">A</th>
                    <th class="meses_faltas">F</th>
                    <th class="meses_atrasos">A</th>
                    <th>F</th>
                    <th>A</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($faltasAtrasos as $row): ?>
                    <tr>
                        <?php for ($i = 0; $i < 27; $i++): ?>
                            <td><?= $row[$i]; ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </section>
</section>

<?php
require_once "end_js.php";
?>

<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
      rel="stylesheet">

<!-- Js -->
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Consolidado de Gestão de Pessoas';

        //datatables
        table = $('#table').DataTable({
            dom: "<'row'<'#tipo_vinculo.col-sm-2'><'#status.col-sm-3'><'#mes_ano.col-sm-4'><'col-sm-3'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            iDisplayLength: -1,
            lengthChange: false,
            ordering: false,
            paging: false,
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>",
                'searchPlaceholder': 'Nome/matrícula'
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('examePeriodico/ajax_relatorio/') ?>",
                "type": "POST",
                timeout: 9000,
                data: function (d) {
                    d.realizados = $('[name="realizados"]:checked').val();
                    d.mes = $('#mes_ano [name="mes"]').val();
                    d.ano = $('#mes_ano [name="ano"]').val();
                    d.tipo_vinculo = $('#tipo_vinculo [name="tipo_vinculo"]').val();
                    d.status = $('#status [name="status"]').val();
                    return d;
                },
                "dataSrc": function (json) {
                    if (json.draw === '1') {
                        $("#mes_ano").append('<br>Mês/ano ' + json.mes);
                        $("#mes_ano").append(' &emsp;' + json.ano);
                        $("#tipo_vinculo").append('<br>Vínculo ' + json.tipo_vinculo);
                        $("#status").append('<br>Status ' + json.status);
                    }
                    return json.data;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: '30%',
                    targets: [0, 3, 7]
                },
                {
                    className: 'text-center',
                    targets: [6, 8, 9, 10]
                },
                {
                    className: "text-nowrap",
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false //set not orderable
                }
            ],
            rowsGroup: [0, 1, 2, 3, 4, 5, -1]
        });

    });

    function enviar_email(id_usuario = null, nome_usuario = '') {
        var msg = 'Deseja enviar e-mail de convocação à ' + nome_usuario + '?';
        if (id_usuario === null && nome_usuario === '') {
            msg = 'Deseja enviar e-mails de convocação a todos os funcionários listados abaixo?';
        }
        if (confirm(msg)) {
            $.ajax({
                url: "<?php echo site_url('examePeriodico/enviarEmail') ?>",
                type: "POST",
                data: {
                    id_usuario: id_usuario,
                    realizados: $('[name="realizados"]:checked').val(),
                    mes: $('#mes_ano [name="mes"]').val(),
                    ano: $('#mes_ano [name="ano"]').val(),
                    tipo_vincuo: $('#tipo_vinculo [name="tipo_vinculo"]').val(),
                    status: $('#status [name="status"]').val(),
                    mensagem: $('[name="mensagem"]').val()
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.status) {
                        if (id === null) {
                            alert('E-mails de convocação enviados com sucesso');
                        } else {
                            alert('E-mail de convocação enviado com sucesso');
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (id === null) {
                        alert('Erro ao enviar e-mails de convocação');
                    } else {
                        alert('Erro ao enviar e-mail de convocação');
                    }
                }
            });
        }
    }

    function reload_table() {
        table.ajax.reload(null, false);
    }

    function buscar() {
        reload_table();
        setPdf_atributes();
    }

    function delete_prontuario(id_usuario) {
        if (confirm('Deseja remover os exames periódicos do colaborador selecionado?')) {
            $.ajax({
                url: "<?php echo site_url('examePeriodico/limpar') ?>",
                type: "POST",
                data: {id_usuario: id_usuario},
                dataType: "JSON",
                success: function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        reload_table();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        }
    }

    function setPdf_atributes() {
        var search = '';
        var q = new Array();
        q.push("realizados=" + $('[name="realizados"]:checked').val());
        q.push("mes=" + $('#mes_ano [name="mes"]').val());
        q.push("ano=" + $('#mes_ano [name="ano"]').val());
        q.push("tipo_vinculo=" + $('#tipo_vinculo [name="tipo_vinculo"]').val());
        q.push("status=" + $('#status [name="status"]').val());
        q = q.filter(function (v) {
            return v !== undefined;
        });
        if (q.length > 0) {
            search = '/q?' + q.join('&');
        }
        $('#pdf').prop('href', '<?= site_url('examePeriodico/pdf'); ?>' + search);
    }
</script>
<?php
require_once "end_html.php";
?>
