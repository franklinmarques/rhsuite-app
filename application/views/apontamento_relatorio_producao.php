<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CORPORATE RH - LMS - Relatório de Produção EMTU</title>
	<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
		  rel="stylesheet">

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
		<table id="table" class="table table-condensed">
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
			</tr>
			<tr style='border-top: 5px solid #ddd;'>
				<th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
					<?php if ($is_pdf == false): ?>
						<h3 class="text-center" style="font-weight: bold;">RELATÓRIO DE PRODUÇÂO EMTU
							- <?= mb_strtoupper($mes_ano) ?></h3>
					<?php else: ?>
						<h4 class="text-center" style="font-weight: bold;">RELATÓRIO DE PRODUÇÂO EMTU
							- <?= mb_strtoupper($mes_ano) ?></h4>
					<?php endif; ?>
				</th>
			</tr>
			</thead>
		</table>
	</htmlpageheader>
	<sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

	<div>
		<div class="row">
			<div class="col-sm-12 form-inline">
				<div class="form-group">
					<span style="font-weight: bold;">Qtde. de colaboradores calculados:</span>&emsp;&emsp;
					<label>Mínimo:</label> <span><?= $fator_divisor['min']; ?>;</span>&emsp;&emsp;
					<label>Máximo:</label> <span><?= $fator_divisor['max']; ?>;</span>&emsp;&emsp;
					<label>Média:</label> <span><?= $fator_divisor['avg']; ?>;</span>&emsp;&emsp;
					<label>Fator divisor:</label> <span><?= $fator_divisor['base']; ?>.</span>
				</div>
			</div>
		</div>

		<table id="relatorio_producao" class="table table-bordered table-condensed">
			<thead>
			<tr class="active">
				<th rowspan="2" style="vertical-align: middle;">Colaborador(a)</th>
				<th colspan="<?= count($dias) ?>" class="text-center">Dias</th>
				<th rowspan="2" class="text-center" nowrap>Total</th>
			</tr>
			<tr class="active">
				<?php foreach ($dias as $dia): ?>
					<th class="text-center"><?= $dia ?></th>
				<?php endforeach; ?>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($data as $k => $linha): ?>
				<?php $className = ''; ?>
				<tr>
					<?php foreach ($linha as $coluna): ?>
						<?php if ($k === (count($data) - 1)): ?>
							<td style="font-weight: bold;" class="<?= $className; ?>"><?= $coluna; ?></td>
						<?php else: ?>
							<td class="<?= $className; ?>"><?= $coluna; ?></td>
						<?php endif; ?>
						<?php $className = 'text-center'; ?>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>

</div>


<script>
    var query_string = '<?= $query_string ?>';

    $('[name="calculo_totalizacao"]').on('change', function () {
        var queryStr = query_string;
        var arrQuery = {};
        $.each(queryStr.split('&'), function (i, v) {
            var q = v.split('=');
            arrQuery[q[0]] = q[1];
        });
        if (this.value === '2') {
            $('.totalizacao_1').hide();
            $('.totalizacao_2').show();
            arrQuery['calculo_totalizacao'] = '2';
        } else {
            $('.totalizacao_1').show();
            $('.totalizacao_2').hide();
            arrQuery['calculo_totalizacao'] = '1';
        }

        var search = [];
        $.each(arrQuery, function (i, v) {
            search.push(i + '=' + v);
        });
        query_string = search.join('&');
        $('#pdf').prop('href', "<?= site_url('apontamento_relatorios/pdf'); ?>/" + query_string);
    });

    function fechar_mes() {
        $('#fechar_mes').prop('disabled', true);

        $.ajax({
            url: "<?php echo site_url('apontamento_totalizacao/fecharMes') ?>",
            type: "POST",
            data: query_string.replace('q?', ''),
            dataType: "JSON",
            success: function (data) {
                if (data.status === true) {
                    alert('Mês fechado com sucesso!');
                } else {
                    alert(data.status);
                }

                $('#fechar_mes').prop('disabled', false);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Não foi possível fechar o mês');
                $('#fechar_mes').prop('disabled', false);
            }
        });
    }
</script>
</body>
</html>
