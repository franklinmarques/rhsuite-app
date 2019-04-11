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
                            <h2 class="text-center"><strong>RELATÓRIO DE DEMISSÕES</strong></h2>
                        <?php else: ?>
                            <h3 class="text-center"><strong>RELATÓRIO DE DEMISSÕES</strong></h3>
                        <?php endif; ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style='border-top: 5px solid #ddd;'>
                    <td>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label">Data inicial</label>
                                <input name="data_inicial" type="text" id="data_inicial" placeholder="dd/mm/aaaa"
                                       class="form-control filtro input-sm text-center data filtro" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Data final</label>
                                <input name="data_final" type="text" id="data_final" placeholder="dd/mm/aaaa"
                                       class="form-control filtro input-sm text-center data filtro" autocomplete="off">
                            </div>
                            <div class="col-md-5">
                                <label class="control-label">Tipo de demissão</label>
                                <select name="tipo_demissao" id="tipo_demissao" class="form-control input-sm filtro"
                                        autocomplete="off">
                                    <option value="">Todas</option>
                                    <option value="1">Demissões sem justa causa</option>
                                    <option value="2">Demissões por justa causa</option>
                                    <option value="3">Pedidos de demissão</option>
                                    <option value="4">Términos de contrato</option>
                                    <option value="5">Rescisões antecipadas por empregado</option>
                                    <option value="6">Rescisões antecipadas por empregador</option>
                                    <option value="7">Desistências de vagas</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label><br>
                                <button type="button" id="pesquisar" class="btn btn-sm btn-default"><i
                                            class="glyphicon glyphicon-search"></i>
                                </button>
                                <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">Limpar
                                </button>
                            </div>
                        </div>
                    </td>
                    <td class="text-right">
                        <?php if ($is_pdf == false): ?>
                            <br>
                            <a id="pdf" class="btn btn-sm btn-danger" href="<?= site_url('usuarioDemissao/pdf'); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                </tbody>
            </table>

            <!--<div class="table-responsive">-->
            <table id="table" class="demissao table table-bordered table-condensed" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Funcionário</th>
                    <th class="text-center">Data demissão</th>
                    <th>Tipo de demissão</th>
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
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

<script>
    $('.data').mask('00/00/0000');

    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Relatório de Demissões';

        //datatables
        table = $('#table').DataTable({
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
                "url": "<?php echo site_url('usuarioDemissao/ajax_relatorio/') ?>",
                "type": "POST",
                timeout: 9000,
                data: function (d) {
                    d.data_inicial = $('#data_inicial').val();
                    d.data_final = $('#data_final').val();
                    d.tipo_demissao = $('#tipo_demissao').val();

                    return d;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs":
                [
                    {
                        width: '50%',
                        targets: [0, 2]
                    },
                    {
                        className: 'text-center text-nowrap',
                        targets: [1]
                    },
                    {
                        className: 'text-nowrap',
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ],
            rowsGroup:
                [0, -1]
        });

        setPdf_atributes();
    });

    $('#pesquisar').on('click', function () {
        filtrar();
    });

    $('#limpa_filtro').on('click', function () {
        $(".filtro").val('');
        filtrar();
    });

    function filtrar() {
        reload_table();
        setPdf_atributes();
    }

    function reload_table() {
        table.ajax.reload(null, false);
    }

    function setPdf_atributes() {
        var search = '';
        var q = new Array();

        $('.filtro').each(function (i, v) {
            if (v.value.length > 0 && (v.value !== 'Todos' || v.value !== 'Todas')) {
                q[i] = v.name + "=" + v.value;
            }
        });
        q = q.filter(function (v) {
            return v.length > 0;
        });
        if (q.length > 0) {
            search = '/q?' + q.join('&');
        }
        $('#pdf').prop('href', "<?= site_url('usuarioDemissao/pdf'); ?>" + search);
    }
</script>
<?php
require_once "end_html.php";
?>
