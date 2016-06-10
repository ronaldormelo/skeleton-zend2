$(function () {

    $('#collapseDivisao1').off('click').on('click', function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-divisao/divisao1',
            async: true,
            data: {},
            beforeSend: function () {
                $('#modal-divisao-body-1').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('#modal-divisao-1').modal('show');
            },
            success: function (data) {

                $('#modal-divisao-body-1').html(data);
            }
        });
    });

    $('#collapseDivisao2').click(function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-divisao/divisao2',
            async: true,
            data: {},
            beforeSend: function () {
                $('#modal-divisao-body-2').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('#modal-divisao-2').modal('show');
            },
            success: function (data) {

                $('#modal-divisao-body-2').html(data);
            }
        });
    });
    
    $('#collapseDivisao3').click(function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-divisao/divisao3',
            async: true,
            data: {},
            beforeSend: function () {
                $('#modal-divisao-body-3').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('#modal-divisao-3').modal('show');
            },
            success: function (data) {

                $('#modal-divisao-body-3').html(data);
            }
        });
    });
    
    $('#collapseDivisao4').click(function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-divisao/divisao4',
            async: true,
            data: {},
            beforeSend: function () {
                $('#modal-divisao-body-4').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('#modal-divisao-4').modal('show');
            },
            success: function (data) {

                $('#modal-divisao-body-4').html(data);
            }
        });
    });    
    
    $('#collapseDivisao5').click(function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-divisao/divisao5',
            async: true,
            data: {},
            beforeSend: function () {
                $('#modal-divisao-body-5').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('#modal-divisao-5').modal('show');
            },
            success: function (data) {

                $('#modal-divisao-body-5').html(data);
            }
        });
    });    
    
    $('#collapseDivisaoMais5').click(function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-divisao/divisao-mais5',
            async: true,
            data: {},
            beforeSend: function () {
                $('#modal-divisao-body-mais5').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('#modal-divisao-mais5').modal('show');
            },
            success: function (data) {

                $('#modal-divisao-body-mais5').html(data);
            }
        });
    });    
}); 