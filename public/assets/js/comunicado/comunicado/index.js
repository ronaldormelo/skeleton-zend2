$(function () {

    $('#btn-cadastrar-comunicado').on('click', function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: true,
            url: '/comunicado-comunicado/cadastro',
            async: true,
            beforeSend: function () {

                $('#collapseFourteen').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
            },
            data: {
            },
            success: function (data) {

                $('#collapseFourteen').html(data);
            }
        });
    });
    $('.btn-editar-comunicado').on('click', function () {

        var obj = $(this);

        $.ajax({
            type: "post",
            dataType: "text",
            cache: true,
            url: '/comunicado-comunicado/cadastro',
            async: true,
            beforeSend: function () {

                $('#collapseFourteen').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
            },
            data: {
                id: obj.attr('value')
            },
            success: function (data) {

                $('#collapseFourteen').html(data);
            }
        });
    });
    $('.btn-excluir-comunicado').on('click', function () {

        var obj = $(this);

        $.ajax({
            type: "post",
            dataType: "json",
            cache: true,
            url: '/comunicado-comunicado/excluir',
            async: true,
            beforeSend: function () {

                $('#collapseFourteen').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
            },
            data: {
                id: obj.attr('value')
            },
            success: function (data) {

                if (data.status == 'success') {

                    $.ajax({
                        type: "post",
                        dataType: "text",
                        cache: true,
                        url: '/comunicado-comunicado/index',
                        async: true,
                        beforeSend: function () {
                            $('#collapseFourteen').html(
                                    '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                                    );
                        },
                        data: {
                        },
                        success: function (data) {
                            $('#collapseFourteen').html(data);
                        }
                    });
                }
            }
        });
    });
});

