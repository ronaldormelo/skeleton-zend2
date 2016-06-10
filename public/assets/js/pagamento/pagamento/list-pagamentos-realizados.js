$(function () {

    $('#btn-liberar-view-pay-pagamento-verificar').off('click').on('click', function () {

        if ($('#idLiberarPagamento').val() != '') {

            $.ajax({
                type: "post",
                dataType: "json",
                cache: false,
                url: '/pagamento-pagamento/verificar-liberar-pagamento',
                async: true,
                data: {
                    id_pagamento: $('#idLiberarPagamento').val()
                },
                success: function (data) {

                    if (data.status == 'success') {

                        $('#modal-view-pay-body-pagamento').html(
                                data.message
                                );
                        $('#modal-view-pay-pagamento').modal('show');
                    } else {
                        $('#modal-view-pay-body-pagamento-error').html("<div class='bs-callout bs-callout-danger'>" +
                                "<span class='glyphicon glyphicon-exclamation-sign'></span> " + data.message +
                                "</div>");
                        $('#modal-view-pay-pagamento-error').modal('show');
                    }
                }
            });
        }
    });

    $('#btn-liberar-view-pay-pagamento').off('click').on('click', function () {

        if ($('#idLiberarPagamento').val() != '') {

            $.ajax({
                type: "post",
                dataType: "json",
                cache: false,
                url: '/pagamento-pagamento/liberar-pagamento',
                async: true,
                complete: function () {
                    
                    $('#modal-view-pay-pagamento').modal('hide');
                    
                    $('#collapseSeven').html(
                            '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                            );
                },
                data: {
                    id_pagamento: $('#idLiberarPagamento').val()
                },
                success: function (retorno) {

                    $.ajax({
                        type: "post",
                        dataType: "text",
                        cache: false,
                        url: '/pagamento-pagamento/list-pagamentos-realizados',
                        async: true,
                        data: {
                            status: retorno.status,
                            message: retorno.message
                        },
                        success: function (data) {

                            $('#collapseSeven').html(data);
                        }
                    });
                }
            });
        }
    });
});