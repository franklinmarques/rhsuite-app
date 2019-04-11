<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Gerenciar Ordem de Serviço de Cuidadores</title>
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
    <br>
    <button class="btn btn-default" onclick="javascript:window.close()"><i
                class="glyphicon glyphicon-remove"></i> Fechar
    </button>
    <br>
    <br>
    <!--<h5 class="text-primary">
        <strong>Cliente/diretoria: <? /*= $nomeCliente */ ?></strong></h5>
    <h5 class="text-primary">
        <strong>Unidade de ensino: <? /*= $nomeEscola */ ?></strong></h5>
    <h5 class="text-primary">
        <strong>Contrato: <? /*= $nomeContrato */ ?></strong></h5>
    <h5 class="text-primary">
        <strong>Ordem de Serviço: <? /*= $ordemServico */ ?></strong>&emsp;<i style="float:right;"><strong>Obs: Para cadastrar
                demais dados dos funcionários basta um clique sobre o nome dos mesmos.</strong></i>
    </h5>
    <h5 class="text-primary">
        <strong>Ano/semestre: <? /*= $anoSemestre */ ?></strong></h5>-->
    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
           width="100%">
        <thead>
        <tr>
            <th>Unidade</th>
            <th>Andar</th>
            <th>Sala</th>
            <th>Facility</th>
            <th>Item</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>

<div id="script_js" style="display: none;"></div>
<script src="<?= base_url("assets/bs3/js/bootstrap.min.js"); ?>"></script>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>

<script>
    var table;

    $(document).ready(function () {

        table = $('#table').DataTable({
            'processing': true,
            'serverSide': true,
            'order': [[1, 'asc'], [0, 'desc']],
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('facilities/vistorias/ajaxListInspecao/' . $this->uri->rsegment(3)) ?>',
                'type': 'POST'
            },
            'columnDefs': [
                {
                    'width': '20%',
                    'targets': [0, 1, 2, 3, 4]
                },
                {
                    'className': 'text-center text-nowrap',
                    'targets': [-1],
                    'orderable': false
                }
            ],
            'rowsGroup': [0, 1, 2, 3, 4]
        });

    });

    $('.filtro').on('change', function () {
        $.ajax({
            url: '<?php echo site_url('ei/ordemServico_profissionais/atualizarFiltros/') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                busca: $('.filtro').serialize(),
                id_usuarios: $('#id_usuarios').val()
            },
            success: function (json) {
                $('#area').html($(json.area).html());
                $('#setor').html($(json.setor).html());
                $('#cargo').html($(json.cargo).html());
                $('#funcao').html($(json.funcao).html());
                $('#municipio').html($(json.municipio).html());
                $('#id_usuarios').html($(json.id_usuarios).html());
                demo1.bootstrapDualListbox('refresh', true);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    });


    $('#form_dados [name="valor_hora"], #form_dados [name="horas_semanais"], #form_dados [name="qtde_semanas"]').on('change', function () {
        calcularValorMensal();
    });

    $('.desconto, #form_dados [name="valor_hora_operacional"]').on('change', function () {
        calcularDescontoMensal();
    });

    function calcularValorMensal() {
        var horas_semanais = parseFloat($('#form_dados [name="horas_semanais"]').val().replace(',', '.'));
        var qtde_semanas = parseInt($('#form_dados [name="qtde_semanas"]').val());
        var horas_mensais = (horas_semanais * qtde_semanas).toFixed(2).toString().replace('.', ',');
        var valor_hora = parseFloat($('#form_dados [name="valor_hora"]').val().replace(',', '.'));

        if (horas_mensais !== 'NaN' && valor_hora !== 'NaN') {
            var valor_hora_mensal = ((horas_semanais * qtde_semanas) * valor_hora).toFixed(2).toString().replace('.', ',');
            $('#form_dados [name="horas_mensais"]').val(horas_mensais);
            $('#form_dados [name="valor_hora_mensal"]').val(valor_hora_mensal);
        } else {
            $('#form_dados [name="horas_mensais"], #form_dados [name="valor_hora_mensal"]').val('');
        }

        calcularDescontoMensal();
    }

    function calcularDescontoMensal() {
        var desconto_1 = parseFloat($('#form_dados [name="desconto_mensal_1"]').val().replace(',', '.'));
        var desconto_2 = parseFloat($('#form_dados [name="desconto_mensal_2"]').val().replace(',', '.'));
        var desconto_3 = parseFloat($('#form_dados [name="desconto_mensal_3"]').val().replace(',', '.'));
        var desconto_4 = parseFloat($('#form_dados [name="desconto_mensal_4"]').val().replace(',', '.'));
        var desconto_5 = parseFloat($('#form_dados [name="desconto_mensal_5"]').val().replace(',', '.'));
        var desconto_6 = parseFloat($('#form_dados [name="desconto_mensal_6"]').val().replace(',', '.'));

        var horas_mensais = parseFloat($('#form_dados [name="horas_mensais"]').val().replace(',', '.'));
        var valor_hora_operacional = parseFloat($('#form_dados [name="valor_hora_operacional"]').val().replace(',', '.'));

        var valor_mensal_1 = ((horas_mensais - desconto_1) * valor_hora_operacional).toFixed(2).toString().replace('.', ',');
        var valor_mensal_2 = ((horas_mensais - desconto_2) * valor_hora_operacional).toFixed(2).toString().replace('.', ',');
        var valor_mensal_3 = ((horas_mensais - desconto_3) * valor_hora_operacional).toFixed(2).toString().replace('.', ',');
        var valor_mensal_4 = ((horas_mensais - desconto_4) * valor_hora_operacional).toFixed(2).toString().replace('.', ',');
        var valor_mensal_5 = ((horas_mensais - desconto_5) * valor_hora_operacional).toFixed(2).toString().replace('.', ',');
        var valor_mensal_6 = ((horas_mensais - desconto_6) * valor_hora_operacional).toFixed(2).toString().replace('.', ',');

        $('#form_dados [name="valor_mensal_1"]').val(valor_mensal_1 !== 'NaN' ? valor_mensal_1 : '');
        $('#form_dados [name="valor_mensal_2"]').val(valor_mensal_2 !== 'NaN' ? valor_mensal_2 : '');
        $('#form_dados [name="valor_mensal_3"]').val(valor_mensal_3 !== 'NaN' ? valor_mensal_3 : '');
        $('#form_dados [name="valor_mensal_4"]').val(valor_mensal_4 !== 'NaN' ? valor_mensal_4 : '');
        $('#form_dados [name="valor_mensal_5"]').val(valor_mensal_5 !== 'NaN' ? valor_mensal_5 : '');
        $('#form_dados [name="valor_mensal_6"]').val(valor_mensal_6 !== 'NaN' ? valor_mensal_6 : '');
    }

    $('#municipio_sub1').on('change', function () {
        $.ajax({
            url: '<?php echo site_url('ei/ordemServico_profissionais/atualizarSubstituto/') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                'municipio': this.value,
                'id_usuario': $('#form_substituto1 [name="id_usuario"]').val()
            },
            success: function (json) {
                $('#form_substituto1 [name="id_usuario_sub1"]').html($(json.usuario).html());
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    });

    $('#municipio_sub2').on('change', function () {
        $.ajax({
            url: '<?php echo site_url('ei/ordemServico_profissionais/atualizarSubstituto/') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                'municipio': this.value,
                'id_usuario': $('#form_substituto2 [name="id_usuario"]').val()
            },
            success: function (json) {
                $('#form_substituto2 [name="id_usuario_sub2"]').html($(json.usuario).html());
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    });


    function gerenciar_profissionais() {
        $('#form_profissionais')[0].reset();
        $('#form_profissionais [name="id"]').val('');
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            url: '<?php echo site_url('ei/ordemServico_profissionais/ajaxEdit/') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_escola: '<?= $this->uri->rsegment(3); ?>'
            },
            success: function (json) {
                $('#form_profissionais [name="id_ordem_servico_escola"]').val(json.id_ordem_servico_escola);
                $('#depto').html($(json.depto).html());
                $('#area').html($(json.area).html());
                $('#setor').html($(json.setor).html());
                $('#cargo').html($(json.cargo).html());
                $('#funcao').html($(json.funcao).html());
                $('#municipio').html($(json.municipio).html());
                $('#id_usuarios').html($(json.id_usuarios).html());
                console.log(json.id_usuarios);
                demo1.bootstrapDualListbox('refresh', true);

                $('.modal-title').text('Gerenciar profissionais');
                $('#modal_profissionais').modal('show');
                $('.combo_nivel1').hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function add_profissional(id_os_profissional) {
        save_method = 'add';
        console.log(id_os_profissional);
        $('#form')[0].reset();
        $('#form [name="id"]').val('');
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            url: '<?php echo site_url('ei/ordemServico_profissionais/ajaxEditHorario/') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_profissional: id_os_profissional,
                id_escola: '<?= $this->uri->rsegment(3); ?>'
            },
            success: function (json) {
                $('#form [name="id_os_profissional"]').html($(json.id_os_profissional).html());
                $('#alunos').html($(json.alunos).html());
                demo2.bootstrapDualListbox('refresh', true);

                $('.modal-title').text('Cadastrar programação semanal');
                $('#adicionar_horario').show();
                $('.horario:gt(0)').remove();
                $('#modal_form').modal('show');
                $('.combo_nivel1').hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function add_horario() {
        var horario = $('.horario:last').html();
        var manterHorarios = $('#manter_horarios').is(':checked');
        var ultimoHorarioInicio = $('.horario:last [name="horario_inicio[]"]').val();
        var ultimoHorariotermino = $('.horario:last [name="horario_termino[]"]').val();

        $('<div class="row form-group horario">' + horario + '</div>').insertAfter('.horario:last');
        $('.remover_horario:last').show();
        $('.hora').mask('00:00');

        if (manterHorarios) {
            $('.horario:last [name="horario_inicio[]"]').val(ultimoHorarioInicio);
            $('.horario:last [name="horario_termino[]"]').val(ultimoHorariotermino);
        }
    }

    function remove_horario(event) {
        $(event).parents('.horario').remove();
    }

    function add_dados() {
        save_method = 'add';
        $('#form_dados')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $('.modal-title').text('Editar dados do profissional');
        $('#modal_dados').modal('show');
        $('.combo_nivel1').hide();
    }

    function edit_profissional(id) {
        save_method = 'update';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('.combo_nivel1').hide();

        $.ajax({
            url: '<?php echo site_url('ei/ordemServico_profissionais/ajaxEditHorario/') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id,
                id_escola: '<?= $this->uri->rsegment(3); ?>'
            },
            success: function (json) {
                $('#form [name="id"]').val(json.id);
                $('#form [name="dia_semana[]"]').val(json.dia_semana);
                $('#form [name="horario_inicio[]"]').val(json.horario_inicio);
                $('#form [name="horario_termino[]"]').val(json.horario_termino);
                $('#form [name="id_os_profissional"]').html($(json.id_os_profissional).html());
                $('#form [name="id_funcao"]').html($(json.id_funcao).html());
                $('#alunos').html($(json.alunos).html());
                demo2.bootstrapDualListbox('refresh', true);

                $('.modal-title').text('Editar programação semanal');
                $('#adicionar_horario').hide();
                $('.horario:gt(0)').remove();
                $('#modal_form').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_dados(id) {
        save_method = 'update';
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('.combo_nivel1').hide();

        $.ajax({
            url: '<?php echo site_url('ei/ordemServico_profissionais/ajaxEditDados/') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (json) {
                $('#profissional').text(json.nome_usuario);
                $.each(json, function (key, value) {
                    $('#form_dados [name="' + key + '"]').val(value);
                });

                $('.modal-title').text('Editar dados do profissional');
                $('#modal_dados').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_substituto1(id) {
        save_method = 'update';
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('.combo_nivel1').hide();

        $.ajax({
            url: '<?php echo site_url('ei/ordemServico_profissionais/ajaxEditSubstituto1/') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (json) {
                $('#form_substituto1 [name="id"]').val(json.id);
                $('#municipio_sub1').html($(json.municipio).html());
                $('#form_substituto1 [name="id_usuario_sub1"]').html($(json.id_usuario_sub1).html());
                $('#form_substituto1 [name="data_substituicao1"]').val(json.data_substituicao1);

                $('#modal_substituto1').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_substituto2(id) {
        save_method = 'update';
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('.combo_nivel1').hide();

        $.ajax({
            url: '<?php echo site_url('ei/ordemServico_profissionais/ajaxEditSubstituto2/') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (json) {
                $('#form_substituto2 [name="id"]').val(json.id);
                $('#municipio_sub2').html($(json.municipio).html());
                $('#form_substituto2 [name="id_usuario_sub2"]').html($(json.id_usuario_sub2).html());
                $('#form_substituto2 [name="data_substituicao2"]').val(json.data_substituicao2);

                $('#modal_substituto2').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function saveProfissionais() {
        $('#btnSaveProfissionais').text('Salvando...').attr('disabled', true);
        $.ajax({
            url: '<?php echo site_url('ei/ordemServico_profissionais/ajaxSave') ?>',
            type: 'POST',
            data: $('#form_profissionais').serialize(),
            dataType: 'JSON',
            success: function (json) {
                if (json.status) {
                    $('#modal_profissionais').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }

                $('#btnSaveProfissionais').text('Salvar').attr('disabled', false);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus) {
                    alert(jqXHR.responseText);
                } else {
                    alert('Error adding / update data');
                }
                $('#btnSaveProfissionais').text('Salvar').attr('disabled', false);
            }
        });
    }


    function save() {
        $('#btnSave, #btnSave2').text('Salvando...').attr('disabled', true);
        var url;
        if (save_method === 'add') {
            url = '<?php echo site_url('ei/ordemServico_profissionais/ajaxAddHorarios') ?>';
        } else {
            url = '<?php echo site_url('ei/ordemServico_profissionais/ajaxUpdateHorario') ?>';
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: $('#form').serialize(),
            dataType: 'JSON',
            success: function (json) {
                if (json.status) {
                    $('#modal_form').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }

                $('#btnSave, #btnSave2').text('Salvar').attr('disabled', false);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus) {
                    alert(jqXHR.responseText);
                } else {
                    alert('Error adding / update data');
                }
                $('#btnSave, #btnSave2').text('Salvar').attr('disabled', false);
            }
        });
    }

    function save_dados() {
        $.ajax({
            url: '<?php echo site_url('ei/ordemServico_profissionais/ajaxSaveDados') ?>',
            type: 'POST',
            data: $('#form_dados').serialize(),
            dataType: 'JSON',
            success: function (json) {
                if (json.status) {
                    $('#modal_dados').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }

                $('#btnSaveDados').text('Salvar').attr('disabled', false);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus) {
                    alert(jqXHR.responseText);
                } else {
                    alert('Error adding / update data');
                }
                $('#btnSaveDados').text('Salvar').attr('disabled', false);
            }
        });
    }

    function save_substituto1() {
        $('#btnSaveSubstituto1').text('Salvando').attr('disabled', true);
        $.ajax({
            url: '<?php echo site_url('ei/ordemServico_profissionais/ajaxSaveSubstituto1') ?>',
            type: 'POST',
            data: $('#form_substituto1').serialize(),
            dataType: 'json',
            success: function (json) {
                if (json.status) {
                    $('#modal_substituto1').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }

                $('#btnSaveSubstituto1').text('Salvar').attr('disabled', false);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus) {
                    alert(jqXHR.responseText);
                } else {
                    alert('Error adding / update data');
                }
                $('#btnSaveSubstituto1').text('Salvar').attr('disabled', false);
            }
        });
    }

    function save_substituto2() {
        $('#btnSaveSubstituto2').text('Salvando').attr('disabled', true);
        $.ajax({
            url: '<?php echo site_url('ei/ordemServico_profissionais/ajaxSaveSubstituto2') ?>',
            type: 'POST',
            data: $('#form_substituto2').serialize(),
            dataType: 'json',
            success: function (json) {
                if (json.status) {
                    $('#modal_substituto2').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }

                $('#btnSaveSubstituto2').text('Salvar').attr('disabled', false);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus) {
                    alert(jqXHR.responseText);
                } else {
                    alert('Error adding / update data');
                }
                $('#btnSaveSubstituto2').text('Salvar').attr('disabled', false);
            }
        });
    }

    function delete_profissional(id) {
        if (confirm('Deseja remover a programação semanal?')) {
            $.ajax({
                url: '<?php echo site_url('ei/ordemServico_profissionais/ajaxDeleteHorario') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    alert('Error deleting data');
                }
            });

        }
    }

    function limpar_profissional(id) {
        if (confirm('Deseja remover este profissional?')) {
            $.ajax({
                url: '<?php echo site_url('ei/ordemServico_profissionais/ajaxDelete') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    alert('Error deleting data');
                }
            });

        }
    }

    function reload_table() {
        table.ajax.reload(null, false);
    }
</script>
</body>
</html>