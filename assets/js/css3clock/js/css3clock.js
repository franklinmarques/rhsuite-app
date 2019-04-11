$(function () {
    var hora = 0;
    var minuto = 0;

    setInterval(function () {
        var seconds = new Date().getSeconds();
        var sdegree = seconds * 6;
        var srotate = "rotate(" + sdegree + "deg)";

        $("#sec").css({"-moz-transform": srotate, "-webkit-transform": srotate});

    }, 1000);


    setInterval(function () {
        var hours = new Date().getHours();

        // Horas
        if (hora == 0 && hours != 0) {
            hora == hours;
            if (hours < 10) {
                hours = '0' + hours;
            }
            $("#hora").html(hours);
        } else {
            if (hora != hours) {
                hora = hours;
                if (hours < 10) {
                    hours = '0' + hours;
                }
                $("#hora").html(hours);
            }
        }

        var mins = new Date().getMinutes();

        // Minutos
        if (minuto == 0 && mins != 0) {
            minuto == mins;
            if (mins < 10) {
                mins = '0' + mins;
            }
            $("#minuto").html(mins);
        } else {
            if (minuto != mins) {
                minuto = mins;
                if (mins < 10) {
                    mins = '0' + mins;
                }
                $("#minuto").html(mins);
            }
        }

        var hdegree = hours * 30 + (mins / 2);
        var hrotate = "rotate(" + hdegree + "deg)";

        $("#hour").css({"-moz-transform": hrotate, "-webkit-transform": hrotate});

    }, 1000);


    setInterval(function () {
        var mins = new Date().getMinutes();
        var mdegree = mins * 6;
        var mrotate = "rotate(" + mdegree + "deg)";

        $("#min").css({"-moz-transform": mrotate, "-webkit-transform": mrotate});

    }, 1000);

});