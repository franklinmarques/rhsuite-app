<?php
//Chamar DOMPDF
require_once(APPPATH . 'libraries/dompdf/dompdf_config.inc.php');

$font1 = base_url('assets/font/HoneyScript-Light.eot?');
$logo = base_url("imagens/usuarios/$foto");
$assinatura = base_url("imagens/usuarios/$assinatura");

//Gerar nome do arquivo
$arquivo = substr(md5(uniqid(time())), 0, $nome);
$data_atual = strftime('%d de %B de %Y', strtotime('today'));
$background = base_url('assets/img/certificado.jpg');

if ($logo) {
    $logo = "<img src='$logo'>";
}
if ($assinatura) {
    $assinatura = "
    <br /><br /><br /><br /><br />
    <div class='assinatura'><img src='$assinatura'></div>
    ";
} else {
    $assinatura = "
    <br /><br /><br /><br /><br />
    <hr style='color:#FFF; width:100%; padding: 0 !important; margin: 0 !important;' />
    <h4 style='text-align: center; padding: 0 !important; margin: 0 !important;'>$empresa</h4>
    ";
}

//Gerar HTML para o PDF
$html = <<<HTML
<html>
<head>
	<title>Certificado</title>
	<style type='text/css'>
	    /* reset de margens */
        * {
            margin: 0;
            padding: 0;
        }
        .container {
            border: 10px solid rgb(64, 127, 80);
            width: 100%;
            height: 97.5%;
            position: absolute;
            bottom: 0;
        }
        .content {
            border: 3px solid rgb(64, 127, 80);
            position: absolute;
            top: 8;
            left: 8;
            margin-right: 8;
            height: 47.5%;
        }
        .borda {
            margin-top: 1.5%;
            margin-left: 2.5%;
            position: absolute;
            width: 10%;
            height: 45%;
            float: left;
            background-image: url($background);
        }
        .borda2 {
            margin-top: 1.5%;
            margin-left: 1%;
            position: absolute;
            width: 2%;
            height: 45%;
            float: left;
            background-image: url($background);
        }
	    .title {
	        text-align: left;
	        margin-top: 60px;
	        font-size: 60px;
	        font-family: "Times New Roman";
	        margin-left: 5%;
	        font-weight: bold;
	        font-style: italic;
	    }
	    .logo {
            top: 2%;
            margin-right: 5%;
            position: absolute;
            width: 20%;
            height: 8%;
            float: right;
        }
        .logo img{
            width: 200px;
            height: 200px;
        }
        .assinatura {
            top: -3.5%;
            position: absolute;
            margin-left: 30%;
            float: left;
        }
        .assinatura img{
            width: 300px;
            height: 130px;
        }
	    .subtitle {
	        text-align: center;
	    }
	    .nome {
	        text-transform: uppercase;
	        font-size: 25px;
	    }
	    .text {
	        margin-right: -17.5%;
	        font-size: 25px;
	        float: right;
	        width: 60%;
	        position: absolute;
	    }
	</style>
</head>
<body>
<div class='container'>
    <div class='content'>
        <div class="borda"></div>
        <div class="borda2"></div>
        <div class="certificado">
            <h1 class='title'>CERTIFICADO</h1>
            <div class="logo">$logo</div>
            <div class='text'>
                <br />
                <br />
                <br />
                <br />
                <table>
                    <tr>
                        <td style="text-align: justify;">
                            Certificamos que <b class='nome'>$nome</b>
                            participou do programa de capacitação <b>$nome_treinamento ?? $nome_treinamento_presenciel</b>
                            no período de $data_inicial a $data_final, com carga horária de $duracao hora(s).
                        </td>
                    </tr>
                </table>
                <br />
                <br />
            </div>

            <p style='text-align: right; margin-top: 20%; margin-right: -21%;'>São Paulo/SP, $data_atual</p>
            <div style='width:40%; float:right; margin-top: 5%; margin-right: -31%;'>
                $assinatura
            </div>
        </div>
    </div>
</div>
</body>
</html>
HTML;

//Gerar PDF
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper('A4', 'landscape');
$dompdf->render();

$dompdf->stream("$arquivo.pdf", array("Attachment" => false));
?>