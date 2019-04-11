<?php
require_once "header.php";
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
                </tr>
                <tr>
                    <th>
                        <?php if ($is_pdf == false): ?>
                            <h2 class="text-center"><strong>EXAMES MÉDICOS PERIÓDICOS</strong></h2>
                        <?php else: ?>
                            <h3 class="text-center"><strong>EXAMES MÉDICOS PERIÓDICOS</strong></h3>
                        <?php endif; ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style='border-top: 5px solid #ddd;'>
                    <td>
                        <div class="row">
                            <div class="col-sm-7">
                                <label class="radio-inline">
                                    <input type="radio" name="realizados" value="" checked="" onchange="buscar()"
                                           autocomplete="off">
                                    Todos os exames
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="realizados" value="1" onchange="buscar()"
                                           autocomplete="off"> Somente realizados
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="realizados" value="0" onchange="buscar()"
                                           autocomplete="off"> Somente não-realizados
                                </label>
                            </div>
                            <div class="col-sm-5 text-right">
                                <?php if ($is_pdf == false): ?>
                                    <button id="email" class="btn btn-sm btn-warning" onclick="enviar_email()"
                                            title="Enviar e-mails de convocação"><i
                                                class="glyphicon glyphicon-envelope"></i>
                                        Enviar e-mails de convocação
                                    </button>
                                    <a id="pdf" class="btn btn-sm btn-danger"
                                       href="<?= site_url('examePeriodico/pdf'); ?>"
                                       title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar
                                        PDF
                                    </a>
                                    <br>
                                    <br>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Texto de e-mail</label>
                                <div class="col-sm-10">
                                    <textarea name="mensagem" rows="2" class="form-control">Caro colaborador, você está convocado para realizar exame médico periódico na data de: dd/mm/aaaa. Favor verificar com o Departamento de Gestão de Pessoas.</textarea>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <!--<div class="table-responsive">-->
            <table id="table" class="exame table table-bordered table-condensed">
                <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>CPF</th>
                    <th>Função</th>
                    <th>Local exame</th>
                    <th>Município</th>
                    <th>Matrícula</th>
                    <th class="text-center">Status</th>
                    <th>Depto/Área/Setor</th>
                    <th class="text-center">Data programada</th>
                    <th class="text-center">Data realização</th>
                    <th class="text-center">Data entrega</th>
                    <th class="text-center">Ações</th>
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
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Exames Médicos Periódicos';

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
