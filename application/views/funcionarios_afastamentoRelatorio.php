<?php
require_once "header.php";
?>
<style>
    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        font-weight: bold;
        margin-left: -100px;
        margin-top: -26px;
        text-align: center;
        padding: 1em 0;
    }

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
        <div style="color: #000;">
            <table class="table table-condensed pdi">
                <thead>
                <tr style='border-top: 5px solid #ddd;'>
                    <th colspan="3">
                        <div class="row">
                            <div class="col-sm-12">
                                <img src="<?= base_url($foto) ?>" align="left"
                                     style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                <p class="text-left">
                                    <img src="<?= base_url($foto_descricao) ?>" align="left"
                                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                </p>
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th colspan="3">
                        <?php if ($is_pdf == false): ?>
                            <h2 class="text-center"><strong>RELATÓRIO DE AFASTAMENTOS</strong></h2>
                        <?php else: ?>
                            <h3 class="text-center"><strong>RELATÓRIO DE AFASTAMENTOS</strong></h3>
                        <?php endif; ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style='border-top: 5px solid #ddd;'>
                    <td>
                        <div class="row">
                            <div class="col-md-7">
                                <label for="setor">Motivo de afastamento</label>
                                <select id="motivo_afastamento" class="form-control input-sm" autocomplete="off"
                                        onchange="filtrar_estrutura()">
                                    <option value="">Todos</option>
                                    <option value="1">Auxílio doença - INSS</option>
                                    <option value="2">Licença maternidade</option>
                                    <option value="3">Acidente de trabalho</option>
                                    <option value="4">Aposentadoria por invalidez</option>
                                </select>
                            </div>
                        </div>
                    </td>
                    <td class="text-right">
                        <?php if ($is_pdf == false): ?>
                            <br>
                            <a id="pdf" class="btn btn-sm btn-danger" href="<?= site_url('usuarioAfastamento/pdf'); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-4">
                    <label for="depto">Departamento</label>
                    <?php echo form_dropdown('', $depto, '', 'id="depto" class="form-control input-sm" onchange="filtrar_estrutura()" autocomplete="off"'); ?>
                </div>
                <div class="col-md-4">
                    <label for="area">Area</label>
                    <?php echo form_dropdown('', $area, '', 'id="area" class="form-control input-sm" onchange="filtrar_estrutura()" autocomplete="off"'); ?>
                </div>
                <div class="col-md-4">
                    <label for="setor">Setor</label>
                    <?php echo form_dropdown('', $setor, '', 'id="setor" class="form-control input-sm" onchange="filtrar_estrutura()" autocomplete="off"'); ?>
                </div>
            </div>
            <hr>

            <!--<div class="table-responsive">-->
            <table id="table" class="afastamento table table-bordered table-condensed" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Funcionário</th>
                    <th class="text-center">Data afastamento</th>
                    <th>Motivo do afastamento</th>
                    <th class="text-center">Data perícia médica</th>
                    <th class="text-center">Data limite do benefício</th>
                    <th class="text-center">Data do retorno ao trabalho</th>
                    <th class="text-center">Ação</th>
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

<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Relatório de Afastamentos';

        //datatables
        table = $('#table').DataTable({
            dom: "<'row'<'#campo_status.col-sm-8'><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            iDisplayLength: -1,
            lengthChange: false,
            ordering: false,
            paging: false,
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            "oLanguage": {
                "sSearch": "Pesquisar nome/matrícula"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('usuarioAfastamento/ajax_relatorio/') ?>",
                "type": "POST",
                timeout: 9000,
                data: function (d) {
                    d.motivo_afastamento = $('#motivo_afastamento').val();
                    d.depto = $('#depto').val();
                    d.area = $('#area').val();
                    d.setor = $('#setor').val();
                    d.status = ($('#status').is(':checked') ? 1 : 0);
                    d.status2 = ($('#status2').is(':checked') ? 1 : 0);
                    return d;
                },
                "dataSrc": function (json) {
                    if (json.draw === '1') {
                        $("#campo_status").html('<div class="checkbox"><label>' +
                            '<input type="checkbox" name="status" id="status" autocomplete="off" onchange="buscar();">' +
                            ' Mostrar apenas funcionários afastados (status)' +
                            '</label></div><br>' +
                            '<div class="checkbox"><label>' +
                            '<input type="checkbox" name="status" id="status2" autocomplete="off" onchange="buscar();">' +
                            ' Mostrar apenas funcionários sem data de retorno' +
                            '</label></div>');
                    }
                    return json.data;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: '30%',
                    targets: [0, 2]
                },
                {
                    className: 'text-center',
                    targets: [1, 3, 4, 5]
                },
                {
                    className: 'text-nowrap',
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false //set not orderable
                }
            ],
            'preDrawCallback': function () {
                $('#pdf').addClass('disabled');
            },
            'drawCallback': function () {
                setPdf_atributes();
                $('#pdf').removeClass('disabled');
            },
            rowsGroup: [0, -1]
        });


    });


    function filtrar_estrutura() {
        $.ajax({
            'url': '<?php echo site_url('usuarioAfastamento/filtrarEstrutura') ?>',
            'type': 'POST',
            'data': {
                'depto': $('#depto').val(),
                'area': $('#area').val(),
                'setor': $('#setor').val()
            },
            'dataType': 'JSON',
            'success': function (json) {
                $('#area').html($(json.area).html());
                $('#setor').html($(json.setor).html());
                reload_table();
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }


    function reload_table() {
        table.ajax.reload(null, false);
    }


    function buscar() {
        reload_table();
    }


    function delete_prontuario(id_usuario) {
        if (confirm('Deseja remover os afastamentos do colaborador selecionado?')) {
            $.ajax({
                url: "<?php echo site_url('usuarioAfastamento/limpar') ?>",
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
        if ($('#motivo_afastamento').val().length > 0) {
            q.push("motivo_afastamento=" + $('#motivo_afastamento').val());
        }
        if ($('#depto').val().length > 0) {
            q.push("depto=" + $('#depto').val());
        }
        if ($('#area').val().length > 0) {
            q.push("area=" + $('#area').val());
        }
        if ($('#setor').val().length > 0) {
            q.push("setor=" + $('#setor').val());
        }
        if ($('#status').is(':checked')) {
            q.push('status=1');
        }
        if ($('#status2').is(':checked')) {
            q.push('status2=1');
        }
        if (q.length > 0) {
            search = '/q?' + q.join('&');
        }
        $('#pdf').prop('href', '<?= site_url('usuarioAfastamento/pdf'); ?>' + search);
    }

</script>
<?php
require_once "end_html.php";
?>
