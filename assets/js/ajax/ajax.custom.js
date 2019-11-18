function ajax_post(url, serialize, html) {
    $.ajax({
        url: url,
        type: (serialize === null) ? 'GET' : 'POST',
        data: serialize,
        beforeSend: function(buu) {
            $('html, body').animate({scrollTop: 0}, 1500);
            html.html('<div class="alert alert-info">Carregando...</div>').hide().fadeIn('slow');
        },
        error: function() {
            html.html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
            return 0;
        },
        success: function(data) {
            html.hide().html(data).fadeIn('slow', function() {
                $('html, body').getNiceScroll().resize();
            });
            return 1;
        }
    });
}
