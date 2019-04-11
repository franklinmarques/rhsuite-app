<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="ThemeBucket">
        <link rel="shortcut icon" href="<?= base_url("assets/images/favipn.ico"); ?>">
        <title>CORPORATE RH - LMS</title>
    </head>

    <body style="margin: 0;">
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div> 
                    <object type="text/html" data="<?= site_url('ead/treinamento/acessar/' . $id) ?>" width="100%" height="600px" style="overflow:auto;">
                    </object>
                </div>
            </section>
        </section>

        <script>
            $('#fullscreen').on('click', function () {
                if (!(document.fullscreenEnabled || document.oFullscreenEnabled || document.msFullscreenEnabled || document.mozFullScreenEnabled || document.webkitFullscreenEnabled || document.webkitCurrentFullScreenEnabled)) {
                    return false;
                }
                if (document.fullscreenElement || document.mozFullScreen || document.webkitIsFullScreen || document.msFullscreenElement) {
                    exitFullscreen();
                } else {
                    launchIntoFullscreen(document.documentElement);
                }
            });
            
            function launchIntoFullscreen(elem) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.mozRequestFullScreen) {
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullScreen) {
                    elem.webkitRequestFullScreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                }
                sessionStorage.setItem('fullscreen', true);
            }

            function exitFullscreen() {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
                sessionStorage.setItem('fullscreen', false);
            }
        </script>
    </body>
</html>