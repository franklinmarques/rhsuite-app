<?php require_once 'header.php'; ?>

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
                         style="height: auto; width: auto; max-height: 92px; max-width:154px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="vertical-align: top;">
                    <p>
                        <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    </p>
                </td>
            </tr>
        </table>
        <table id="atividades" class="table table-condensed table-condensed">
            <thead>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="4" style="padding-bottom: 12px;">
                    <h2 class="text-center" style="font-weight: bold;">RELATORIO DE ATIVIDADES PENDENTES</h2>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr style='border-top: 5px solid #ddd; border-bottom: 1px solid #ddd;'>
                <td>
                    <h5><span style="font-weight: bold;">Data atual: </span><?= date('d/m/Y') ?></h5>
                </td>
                <td>
                    <h5><span style="font-weight: bold;">Usuário: </span><?= $usuario->nome ?></h5>
                </td>
                <td>
                    <h5><span style="font-weight: bold;">Depto/área/setor: </span><?= $usuario->estrutura ?></h5>
                </td>
                <td class="text-right">
                    <a id="pdf" class="btn btn-info btn-sm" href="<?= site_url('atividades/pdf'); ?>"><i
                                class="glyphicon glyphicon-print"></i> Imprimir</a>
                    <button class="btn btn-default btn-sm" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="row" id="busca">
            <div class="col-md-12">
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label">Prioridades</label>
                            <?php echo form_dropdown('prioridades', $prioridades, '', 'id="deficiencia" class="form-control filtro input-sm" autocomplete="off" onchange="reload_table();"'); ?>
                        </div>
                        <div class="col-md-3">
                            <label class="control-label">Status</label>
                            <?php echo form_dropdown('status', $status, '', 'id="status" class="form-control filtro input-sm" autocomplete="off" onchange="reload_table();"'); ?>
                        </div>
                        <div class="col-md-3">
                            <label class="control-label">Data início</label>
                            <input name="data_inicio" type="text" value=""
                                   id="data_inicio" placeholder="dd/mm/aaaa" onchange="reload_table();"
                                   class="form-control filtro input-sm text-center" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label class="control-label">Data término</label>
                            <input name="data_termino" type="text" value=""
                                   id="data_termino" placeholder="dd/mm/aaaa" onchange="reload_table();"
                                   class="form-control filtro input-sm text-center" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Colaborador/empresa</label>
                            <?php echo form_dropdown('usuarios', $usuarios, '', 'id="contrato" class="form-control filtro input-sm" autocomplete="off" onchange="reload_table();"'); ?>
                        </div>
                        <div class="col-md-3">
                            <br>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="observacoes" autocomplete="off"
                                           onchange="reload_table();" id="observacoes" checked> Mostrar observações
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label><br>
                            <div class="btn-group" role="group" aria-label="...">
                                <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
                                    Limpar filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <br>

        <table id="table" class="table table-bordered table-condensed" width="100%">
            <thead>
            <tr class='active'>
                <th class="text-center">Atv.</th>
                <th class="text-center">Colaborador</th>
                <th class="text-center">PR</th>
                <th class="text-center">ST</th>
                <th class="text-center">Atividades</th>
                <th class="text-center">Cadastro</th>
                <th class="text-center">Limite</th>
                <th class="text-center">Fechamento</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <!--</div>-->

    </section>
</section>
<!--main content end-->

<?php require_once 'end_js.php'; ?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">


<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

<script>

    var table;

    $(document).ready(function () {
        //datatables
        table = $('#table').DataTable({
            'iDisplayLength': -1,
            'lengthChange': false,
            'searching': false,
            'paging': false,
            'processing': true,
            'serverSide': true,
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('atividades/ajaxRelatorio') ?>',
                'type': 'POST',
                'timeout': 90000,
                'data': function (d) {
                    d.prioridade = $('[name="prioridades"]').val();
                    d.status = $('[name="status"]').val();
                    d.data_inicio = $('[name="data_inicio"]').val();
                    d.data_termino = $('[name="data_termino"]').val();
                    d.usuario = $('[name="usuarios"]').val();
                    d.observacoes = ($('[name="observacoes"]').is(':checked') ? 1 : 0);
                    return d;
                }
            },
            'createdRow': function (row, data, index) {
                $(row).css('font-weight', 'bolder');
                if (data[8] !== null) {
                    $(row).addClass('info').css('font-style', 'italic');
                }
            },
            'columnDefs': [
                {
                    'className': 'text-center',
                    'targets': [0, 2, 3, 5, 6, 7]
                },
                {
                    'width': '40%',
                    'targets': [1]
                },
                {
                    'width': '60%',
                    'targets': [4]
                },
            ],
            'preDrawCallback': function () {
                $('.filtro, #observacoes, #limpa_filtro').prop('disabled', true);
                $('#pdf').addClass('disabled');
            },
            'drawCallback': function () {
                var search = '';
                var q = new Array();

                $('.filtro').each(function (i, v) {
                    if (v.value.length > 0) {
                        q[i] = v.name + "=" + v.value;
                    }
                });

                if ($('#observacoes').is(':checked')) {
                    q[q.length] = 'observacoes=1';
                }

                q = q.filter(function (v) {
                    return v.length > 0;
                });
                if (q.length > 0) {
                    search = '/q?' + q.join('&');
                }

                $('#pdf').prop('href', "<?= site_url('atividades/pdf/'); ?>" + search).removeClass('disabled');
                $('.filtro, #observacoes, #limpa_filtro').prop('disabled', false);
            }
        });

    });

    function reload_table() {
        table.ajax.reload(null, false);
    }
</script>


<?php require_once 'end_html.php'; ?>

