<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Formulário de Realização de Vistoria</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
    <!--WARNING: Respond.js doesn't work if you view the page via file://-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?= base_url("assets/js/jquery.js"); ?>"></script>
    <style>
        @page {
            margin: 40px 20px;
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

        tr.group, tr.group:hover {
            background-color: #ddd !important;
        }
    </style>
</head>
<body style="color: #000;">
<div class="container-fluid">

    <htmlpageheader name="myHeader">
        <table id="table" class="table table-condensed" style="margin-bottom: 5px;">
            <thead>
            <tr>
                <td style="width: auto;">
                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="width: 100%; vertical-align: top;">
                    <p>
                        <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    </p>
                </td>
                <td nowrap>
                    <?php if ($is_pdf == false): ?>
                        <button type="button" id="btnSave" onclick="$('#form').submit();" class="btn btn-success">
                            <i class="glyphicon glyphicon-floppy-disk"></i> Salvar
                        </button>
                        <a id="pdf" class="btn btn-info"
                           href="<?= site_url('facilities/vistorias/pdf/' . $query_string); ?>"
                           title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                        <button class="btn btn-default" onclick="javascript:window.close()"><i
                                    class="glyphicon glyphicon-remove"></i> Fechar
                        </button>
                    <?php endif; ?>
                </td>
            </tr>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="3" style="text-align: center;">
                    <h3 class="text-center" style="font-weight: bold;">PROGRAMA DE VISTORIA PERIÓDICA</h3>
                </th>
            </tr>
            </thead>
        </table>
        <?php if ($is_pdf == false): ?>
            <div class="row">
                <div class="col col-md-6">
                    <h5><strong>Identificação do plano:</strong> <?= $nomeVistoria; ?></h5>
                </div>
                <div class="col col-md-6">
                    <h5><strong>Mês/ano da vistoria:</strong> <?= date('m/Y'); ?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col col-md-6">
                    <h5><strong>Empresa:</strong> <?= $empresaFacilities; ?></h5>
                </div>
            </div>
        <?php else: ?>
            <p>
            <h5><span style="font-weight: bold;">Identificação do plano:</span> <?= $nomeVistoria; ?></h5>
            <h5><span style="font-weight: bold;">Mês/ano da vistoria:</span> <?= date('m/Y'); ?></h5>
            <h5><span style="font-weight: bold;">Empresa:</span> <?= $empresaFacilities; ?></h5>
            </p>
        <?php endif; ?>
    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

    <br>

    <?php if (empty($vistorias)): ?>
        <table id="no_itens" class="table table-bordered table-condensed">
            <thead>
            <tr class="active">
                <th rowspan="2" style="display: none;">ID</th>
                <th rowspan="2">Ativo/facility</th>
                <th rowspan="2">Item</th>
                <th rowspan="2" class="text-center">Vistoria realizada</th>
                <th rowspan="2" class="text-center">Apresenta problemas</th>
                <th rowspan="2" class="text-center">Problema/solicitação</th>
                <th rowspan="2" class="text-center text-nowrao">O. S.</th>
                <th rowspan="2" class="text-center">Observações</th>
                <th colspan="2" class="text-center">Realização</th>
            </tr>
            <tr class="active">
                <th class="text-center">Data</th>
                <th class="text-center">CAT.</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="10" class="text-center text-muted">Nenhum item encontrado.</td>
            </tr>
            </tbody>
        </table>
    <?php endif; ?>

    <div id="alert"></div>

    <?php foreach ($vistorias as $vistoria): ?>
        <table class="table table-bordered table-condensed itens">
            <thead>
            <tr class="success">
                <th colspan="10">
                    <h4>
                        <strong>Unidade:</strong> <span
                                style="font-weight: normal;"><?= $vistoria['nome']->unidade; ?></span>&emsp;
                        <strong>Andar:</strong> <span
                                style="font-weight: normal;"><?= $vistoria['nome']->andar; ?></span>&emsp;
                        <strong>Sala:</strong> <span
                                style="font-weight: normal;"><?= $vistoria['nome']->sala; ?></span>
                    </h4>
                </th>
            </tr>
            <tr class="active">
                <th rowspan="2" style="display: none;">ID</th>
                <th rowspan="2">Ativo/facility</th>
                <th rowspan="2">Item</th>
                <th rowspan="2" class="text-center">Vistoria realizada</th>
                <th rowspan="2" class="text-center">Apresenta problemas</th>
                <th rowspan="2" class="text-center">Problema/solicitação</th>
                <th rowspan="2" class="text-center text-nowrap">O. S.</th>
                <th rowspan="2" class="text-center">Observações</th>
                <th colspan="2" class="text-center">Realização</th>
            </tr>
            <tr class="active">
                <th class="text-center">Data</th>
                <th class="text-center">CAT.</th>
            </tr>
            </thead>
            <tbody>
            <?php echo form_open('facilities/vistorias/salvarItens/' . $this->uri->rsegment(3), 'data-aviso="alert" class="ajax-upload" id="form" autocomplete="off"'); ?>
            <?php foreach ($vistoria['subitens'] as $subitem): ?>
                <tr class="<?= $subitem->tipo; ?>">
                    <td style="display: none;"><input type="hidden" name="id[<?= $subitem->id_subitem; ?>]"
                                                      value="<?= $subitem->id; ?>"> Sim
                    </td>
                    <td><?= $subitem->item; ?></td>
                    <td><?= $subitem->subitem; ?></td>
                    <td>
                        <div class="radio">
                            <label>
                                <?php echo form_radio("vistoriado[{$subitem->id_subitem}]", '1', $subitem->vistoriado === '1'); ?>
                                Sim
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <?php echo form_radio("vistoriado[{$subitem->id_subitem}]", '0', $subitem->vistoriado === '0'); ?>
                                Não
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <?php echo form_radio("vistoriado[{$subitem->id_subitem}]", '2', $subitem->vistoriado === '2'); ?>
                                Não se aplica
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio">
                            <label>
                                <?php echo form_radio("possui_problema[{$subitem->id_subitem}]", '1', $subitem->possui_problema === '1'); ?>
                                Sim
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <?php echo form_radio("possui_problema[{$subitem->id_subitem}]", '0', $subitem->possui_problema === '0'); ?>
                                Não
                            </label>
                        </div>
                    </td>
                    <td>
                            <textarea name="descricao_problema[<?= $subitem->id_subitem; ?>]" class="form-control"
                                      rows="3"><?= $subitem->descricao_problema; ?></textarea>
                    </td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm"
                                onclick="add_os(<?= $subitem->id_subitem; ?>, <?= $subitem->numero_os; ?>);"><i
                                    class="glyphicon glyphicon-pencil"></i>
                        </button>
                    </td>
                    <td>
                            <textarea name="observacoes[<?= $subitem->id_subitem; ?>]" class="form-control"
                                      rows="3"><?= $subitem->observacoes; ?></textarea>
                    </td>
                    <td>
                        <input type="text" name="data_realizacao[<?= $subitem->id_subitem; ?>]"
                               class="form-control text-center date" placeholder="dd/mm/aaaa"
                               value="<?= $subitem->data_realizacao; ?>">
                    </td>
                    <td>
                        <input type="text" name="realizacao_cat[<?= $subitem->id_subitem; ?>]" class="form-control"
                               value="<?= $subitem->realizacao_cat; ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php echo form_close(); ?>
            </tbody>
        </table>

    <?php endforeach; ?>


    <!-- Bootstrap modal -->
    <div class="modal fade" id="modal_form" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Gerenciar Ordem de Serviço</h3>
                </div>
                <div class="modal-body form">
                    <div id="alert"></div>
                    <form action="#" id="form_os" class="form-horizontal" autocomplete="off">
                        <input type="hidden" value="<?= $this->uri->rsegment(3); ?>" name="id_realizacao">
                        <input type="hidden" value="" name="id_modelo_vistoria">
                        <input type="hidden" value="<?= $idUsuario ?>" name="id_usuario">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Número da O. S.</label>
                                <div class="col-md-3 controls">
                                    <?php echo form_dropdown('numero_os', $numeroOS, $novaOS, 'class="form-control"'); ?>
                                </div>
                                <label class="control-label col-md-1">Status</label>
                                <div class="col-md-3">
                                    <select name="status" class="form-control">
                                        <option value="A">Aberta</option>
                                        <option value="F">Fechada</option>
                                        <option value="P">Parcialmente fechada</option>
                                        <option value="E">Em tratamento</option>
                                    </select>
                                </div>
                                <div class="col-md-3 text-right">
                                    <button type="button" id="btnSaveOS" onclick="salvar_os()" class="btn btn-success">
                                        Salvar
                                    </button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-2">Data abertura</label>
                                <div class="col-md-2">
                                    <input name="data_abertura" class="form-control text-center date"
                                           type="text">
                                </div>
                                <label class="control-label col-md-2">Data fechamento</label>
                                <div class="col-md-2">
                                    <input name="data_fechamento" class="form-control text-center date"
                                           type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Departamento</label>
                                <div class="col-md-9 controls">
                                    <?php echo form_dropdown('id_depto', $os['deptos'], $os['id_depto'], 'id="depto" class="form-control" onchange="montar_estrutura()"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Área</label>
                                <div class="col-md-9 controls">
                                    <?php echo form_dropdown('id_area', $os['areas'], $os['id_area'], 'id="area" class="form-control" onchange="montar_estrutura()"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Setor</label>
                                <div class="col-md-9 controls">
                                    <?php echo form_dropdown('id_setor', $os['setores'], $os['id_setor'], 'id="setor" class="form-control" onchange="montar_estrutura()"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Requisitante</label>
                                <div class="col-md-9 controls">
                                    <?php echo form_dropdown('id_requisitante', $os['requisitantes'], $os['id_requisitante'], 'id="requisitante" class="form-control"'); ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row form-group">
                                <div class="col-md-10 col-md-offset-1">
                                    <label>Problema/solicitação</label>
                                    <textarea name="descricao_problema" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-10 col-md-offset-1">
                                    <label>Observações/andamaneto</label>
                                    <textarea name="observacoes" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Bootstrap modal -->

</div>

<script src="<?= base_url("assets/bs3/js/bootstrap.min.js"); ?>"></script>

<script src="<?php echo base_url("assets/js/scripts.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/ajax/ajax.form.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/ajax/ajax.upload.js"); ?>"></script>
<script src="<?php echo base_url('assets/js/ajax/ajax.custom.js'); ?>"></script>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

<script>
    $(document).ready(function () {
        $('.date').mask('00/00/0000');
    });


    function montar_estrutura() {
        var depto = $('#depto').val();
        var area = $('#area').val();
        var setor = $('#setor').val();
        var requisitante = $('#requisitante').val();
        $('#depto,#area, #setor').prop('disabled', true);

        $.ajax({
            'url': '<?php echo site_url('facilities/ordensServico/montarEstrutura') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'depto': depto,
                'area': area,
                'setor': setor,
                'requisitante': requisitante
            },
            'success': function (json) {
                $('#depto,#area, #setor').prop('disabled', false);

                $('#area').html($(json.area).html());
                $('#setor').html($(json.setor).html());
                $('#requisitante').html($(json.requisitante).html());
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
                $('#depto,#area, #setor').prop('disabled', false);
            }
        });
    }


    function add_os(id_modelo_vistoria, numero_os) {
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        if (numero_os === undefined) {
            numero_os = '<?= $novaOS; ?>';
        }
        $('[name="id_modelo_vistoria"]').val(id_modelo_vistoria);
        $('[name="numero_os"]').val(numero_os).trigger('change');
        $('[name="numero_os"]').val(numero_os);
        $('#modal_form').modal('show');
        $('.combo_nivel1').hide();
    }

    $('[name="numero_os"]').on('change', function () {
        if (this.value !== '<?= $novaOS; ?>') {
            filtrar_os(this.value);
        } else {
            nova_os();
        }
    });

    function filtrar_os(numero_os) {
        $.ajax({
            'url': "<?php echo site_url('facilities/ordensServico/ajaxEdit') ?>",
            'type': 'POST',
            'dataType': 'json',
            'data': {'numero_os': numero_os},
            'success': function (json) {
                $('#form_os select:not([name="numero_os"]), #form_os input:not([type="hidden"]), #form_os textarea').prop('disabled', json.data.id_usuario !== '<?= $idUsuario ?>');
                $.each(json.input, function (key, value) {
                    $('#' + key).html($(value).html());
                });

                $.each(json.data, function (key, value) {
                    if ($('#form_os [name="' + key + '"]').is(':checkbox') === false) {
                        $('#form_os [name="' + key + '"]').val(value);
                    } else {
                        $('#form_os [name="' + key + '"][value="' + value + '"]').prop('checked', value === '1');
                    }
                });
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function nova_os() {
        $.ajax({
            'url': '<?php echo site_url('facilities/ordensServico/ajaxNovo') ?>',
            'type': 'POST',
            'dataType': 'json',
            'success': function (json) {
                $('#form_os select:not([name="numero_os"]), #form_os input:not([type="hidden"]), #form_os textarea').prop('disabled', false);
                $('#depto').html($(json.deptos).html());
                $('#area').html($(json.areas).html());
                $('#setor').html($(json.setores).html());
                $('#requisitante').html($(json.requisitantes).html());

                $('[name="data_abertura"]').val(moment().format('DD/MM/YYYY'));
                $('[name="status"]').val('A');
                $('[name="data_fechamento"], [name="descricao_problema"], [name="observacoes"]').val('');
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function salvar_os() {
        $('#btnSaveOS').text('Salvando...'); //change button text
        $('#btnSaveOS').attr('disabled', true); //set button enable

        $.ajax({
            'url': '<?php echo site_url('facilities/vistorias/salvarOS') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': $('#form_os').serialize(),
            'success': function (json) {
                if (json.status) {
                    $('#modal_form').modal('hide');
                    document.location.reload();
                } else if (json.erro) {
                    alert(json.erro);
                }

                $('#btnSaveOS').text('Salvar'); //change button text
                $('#btnSaveOS').attr('disabled', false); //set button enable
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
                $('#btnSaveOS').text('Salvar'); //change button text
                $('#btnSaveOS').attr('disabled', false); //set button enable
            }
        });
    }
</script>

</body>
</html>