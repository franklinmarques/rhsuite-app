<?php
$font1 = base_url('assets/font/HoneyScript-Light.eot');
$logo = base_url("imagens/usuarios/$foto");
$assinatura = base_url("imagens/usuarios/AssinaturaDigital.jpg");

//Gerar nome do arquivo
//$arquivo = substr(md5(uniqid(time())), 0, $nome);
$data_atual = strftime('%d de %B de %Y', strtotime('today'));
$background = base_url('assets/img/certificado.jpg');

if ($logo) {
    $logo = "<img src='$logo'>";
}
//if ($assinatura) {
//    $assinatura = "
//    <br /><br /><br /><br /><br />
//    <div class='assinatura'><img src='$assinatura'></div>
//    ";
//} else {
//    $assinatura = "
//    <br /><br /><br /><br /><br />
//    <hr style='color:#FFF; width:100%; padding: 0 !important; margin: 0 !important;' />
//    <h4 style='text-align: center; padding: 0 !important; margin: 0 !important;'>$empresa</h4>
//    ";
//}
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CORPORATE RH - LMS - Certificado</title>
        <style>
            @font-face {
                font-family: 'HoneyScriptLight';
                src:url('../../../assets/font/HoneyScriptLight.eot');
                src:url('../../../assets/font/HoneyScriptLight.eot?#iefix') format('embedded-opentype'),
                    url('../../../assets/font/HoneyScriptLight.woff') format('woff'),
                    url('../../../assets/font/HoneyScriptLight.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }
            /* reset de margens */
            body {
                width: 100%;
                height: 100%;
                min-height: 100%;
            }
            .container {
                height: 100%;
                min-height: 100%;

                display: -ms-flex;
                display: -webkit-flex;
                display: flex;

                /* centraliza na vertical */
                -ms-align-items: center;
                -webkit-align-items: center;
                align-items: center;

                /* centraliza na horizontal */
                -ms-justify-content: center;
                -webkit-justify-content: center;
                justify-content: center;

                padding: 7px;
                border: 10px solid rgb(64, 127, 80);
            }
            .content {
                height: 95%;
                box-sizing: border-box;
                max-height: inherit;
                border: 3px solid rgb(64, 127, 80);
            }
/*            .borda {
                margin-top: 1.5%;
                margin-left: 2.5%;
                position: absolute;
                width: 10%;
                height: 45%;
                float: left;
                background-image: url(<?= $background ?>);
            }
            .borda2 {
                margin-top: 1.5%;
                margin-left: 1%;
                position: absolute;
                width: 20%;
                height: 45%;
                float: left;
                background-image: url(<?= $background ?>);
            }*/
            .title {
                text-align: center;
                margin-top: 60px;
                font-size: 5.5em;
                font-family: "HoneyScriptLight";
                margin-bottom: 80px;
                font-style: italic;
                text-shadow: 0.05em 0.05em 0.05em #bbb;
            }
            .logo {
                position: absolute;
                float: left;
                margin: 2%;
                width: 20%;
                height: 10%;
                clear:both;
            }
            .logo img{
                width: 100%;
                max-width: 200px;
                height: auto;
                max-height: 200px;
                box-shadow: 0.5em 0.05em 0.05em #bbb;
            }
            .assinatura {
                text-align: center;
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
                /*font-size: 25px;*/
            }
            .text p {
                text-indent: 50px;
                padding-right: 40px;
                padding-left: 40px;
                font-family: 'Times New Roman';
                font-size: 1.4em;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='content'>
                <!--<div class="borda"></div>-->
                <!--<div class="borda2"></div>-->
                <div class="certificado">
                    <div class="logo"><?php echo $logo; ?></div>
                    <h1 class='title'>Certificado</h1>
                    <br/>
                    <br/>
                    <div class='text'>
                        <p style="text-align: justify;">
                            Certificamos que <strong><?php echo $nome; ?></strong>
                            participou do programa de capacitação <strong><?php echo $nome_treinamento; ?></strong>
                            no período de <?php echo $data_inicial; ?> a <?php echo $data_final; ?>, com carga horária de <?php echo $duracao; ?> hora(s).
                        </p>
                        <br/>
                    </div>
                    <p style="text-align: right; padding-right: 50px; font-size: 1.2em;">São Paulo-SP, <?php echo $data_atual; ?></p>
                    
                    <div>
                        <?php if ($assinatura): ?>
                            <div class='assinatura'><img src='<?php echo $assinatura; ?>'></div>
                        <?php else: ?>
                            <hr style='color:#FFF; width:100%; padding: 0 !important; margin: 0 !important;'/>
                            <h4 style='text-align: center; padding: 0 !important; margin: 0 !important;'>
                                <?php echo $empresa; ?>
                            </h4>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>