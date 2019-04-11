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

    .table tbody .data_nascimento {
        border-radius: 1px;
        border-width: 2px !important;
        border-style: outset solid solid outset !important;
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
                    <th>
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
                    <th class="text-right" style="vertical-align: top;">
                        <a id="pdf" class="btn btn-sm btn-danger"
                           href="<?= site_url('funcionario/pdfAniversariantes'); ?>"
                           title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF
                        </a>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style='border-top: 5px solid #ddd;'>
                    <td colspan="2">
                        <h2 class="text-center"><strong>RELATÓRIO DE ANIVERSARIANTES</strong></h2>
                    </td>
                </tr>
                </tbody>
            </table>

            <!--<div class="table-responsive">-->
            <table id="table" class="demissao table table-bordered table-condensed" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Funcionário(a)</th>
                    <th class="text-center">Data nascimento <span class="text-info">*</span></th>
                    <th class="text-center">Ação</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <!--</div>-->
        </div>

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_form" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Edição rápida de funcionário(a)</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="" name="id"/>
                            <div class="row form-group">
                                <label class="control-label col-md-4">Funcionário(a):</label>
                                <div class="col-md-7">
                                    <p class="form-control-static">
                                        <span id="nome"></span>
                                    </p>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-4">Data de nascimento:</label>
                                <div class="col-md-3">
                                    <input name="data_nascimento" type="text" value=""
                                           class="form-control text-center date">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveFaturamento" onclick="save()" class="btn btn-success">Salvar
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </section>
</section>

<?php
require_once "end_js.php";
?>

<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<!-- Js -->
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>
    var table;

    $('.date').mask('00/00/0000');

    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Relatório de Aniversariantes';

        //datatables
        table = $('#table').DataTable({
            'dom': "<'row'<'#meses.col-sm-2'><'#info_data_nascimento.col-sm-6'><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            iDisplayLength: -1,
            lengthChange: false,
            paging: false,
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>",
                'searchPlaceholder': 'Nome/matrícula'
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('funcionario/ajaxListAniversariantes/') ?>",
                "type": "POST",
                timeout: 9000,
                data: function (d) {
                    d.mes = $('#mes').val();
                    return d;
                },
                'dataSrc': function (json) {
                    if (json.draw === '1') {
                        $("#meses").append('<br>Mês ' + json.meses);
                        $("#info_data_nascimento").append('<br><i class="text-info"><strong>*</strong> Clique em uma data de nascimento para edição rápida.</i>');
                    }
                    setPdf_atributes();

                    return json.data;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs":
                [
                    {
                        'width': '100%',
                        'targets': [0]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).css('cursor', 'pointer').on('click', function () {
                                edit_aniversariante(rowData[3], rowData[0], rowData[col]); // id, nome, data_nascimento
                            });
                        },
                        'className': 'text-center text-nowrap data_nascimento',
                        'targets': [1]
                    },
                    {
                        'className': 'text-nowrap',
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ]
        });
    });

    function edit_aniversariante(id, nome, data_nascimento) {
        $('#form [name="id"]').val(id);
        $('#nome').html(nome);
        $('#form [name="data_nascimento"]').val(data_nascimento);
        $('#modal_form').modal('show');
    }

    function save() {
        $('#btnSave').text('Salvando...').attr('disabled', true);
        $.ajax({
            'url': '<?php echo site_url('funcionario/ajaxSaveAniversariante') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': $('#form').serialize(),
            'success': function (json) {
                if (json.erro) {
                    alert(json.erro);
                } else {
                    $('#modal_form').modal('hide');
                    reload_table();
                }
                $('#btnSave').text('Salvar').attr('disabled', false);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
                $('#btnSave').text('Salvar').attr('disabled', false);
            }
        });
    }

    function reload_table() {
        table.ajax.reload(null, false);
    }

    function setPdf_atributes() {
        var search = '';
        var q = new Array();

        var mes = $('#mes').val();
        if (mes.length > 0) {
            q[0] = 'mes=' + mes;
        }

        if (table.order()[0] !== undefined) {
            q[q.length] = 'order[0][0]=' + (table.order()[0][0] + 1) + '&order[0][1]=' + table.order()[0][1];
        }
        if (table.order()[1] !== undefined) {
            q[q.length] = 'order[1][0]=' + (table.order()[1][0] + 2) + '&order[1][1]=' + table.order()[1][1];
        }

        q = q.filter(function (v) {
            return v.length > 0;
        });
        if (q.length > 0) {
            search = '/q?' + q.join('&');
        }
        $('#pdf').prop('href', "<?= site_url('funcionario/pdfAniversariantes'); ?>" + search);
    }
</script>
<?php
require_once "end_html.php";
?>
