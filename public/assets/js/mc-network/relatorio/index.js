$(function () {

    $('#collapseRelatorio1').off('click').on('click', function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-relatorio/relatorio-pagamentos-recebidos',
            async: true,
            data: {},
            beforeSend: function () {
                $('#modal-relatorio-body-1').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('#modal-relatorio-1').modal('show');
            },
            success: function (data) {

                $('#modal-relatorio-body-1').html(data);
            }
        });
    });

    $('#collapseRelatorio2').click(function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-relatorio/relatorio-bonus-pagos',
            async: true,
            data: {},
            beforeSend: function () {
                $('#modal-relatorio-body-2').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('#modal-relatorio-2').modal('show');
            },
            success: function (data) {

                $('#modal-relatorio-body-2').html(data);
            }
        });
    });
    
    $('#collapseRelatorio3').click(function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-relatorio/relatorio-saques-pagos',
            async: true,
            data: {},
            beforeSend: function () {
                $('#modal-relatorio-body-3').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('#modal-relatorio-3').modal('show');
            },
            success: function (data) {

                $('#modal-relatorio-body-3').html(data);
            }
        });
    });
    
    $('#collapseRelatorio4').click(function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-relatorio/relatorio-cadastrados',
            async: true,
            data: {},
            beforeSend: function () {
                $('#modal-relatorio-body-4').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('#modal-relatorio-4').modal('show');
            },
            success: function (data) {

                $('#modal-relatorio-body-4').html(data);
            }
        });
    });    
    
    $('#collapseRelatorio6').click(function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-relatorio/relatorio-saldo-usuarios',
            async: true,
            data: {},
            beforeSend: function () {
                $('#modal-relatorio-body-6').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('#modal-relatorio-6').modal('show');
            },
            success: function (data) {

                $('#modal-relatorio-body-6').html(data);
            }
        });
    });    

    $('.modal.printable').on('shown.bs.modal', function () {

        $(this).addClass('modalPrint');

    }).on('hidden.bs.modal', function () {

        $(this).removeClass('modalPrint');
    });
}); 