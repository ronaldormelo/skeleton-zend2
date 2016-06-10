$(function () {

    $('.btnVisualizarConprovanteSaque').off('click').on('click', function () {

        var input = $(this);

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-cursos/view-pay',
            async: true,
            data: {
                id: input.attr('value')
            },
            success: function (data) {

                $('#idLiberarSaque').val(input.attr('value'));
                $('#modal-view-pay-body-saque').html(data);
                $('#modal-view-pay-saque').modal('show');
            }
        });
    });

    $('#btn-liberar-view-pay-saque').off('click').on('click', function () {
        
        var idPagamento = $('#idLiberarSaque').val();

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/pagamento-pagamento/liberar-saque',
            async: true,
            complete: function () {
                $('#collapseTen').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
            },
            data: {
                id_saque: idPagamento
            },
            success: function () {

                $.ajax({
                    type: "post",
                    dataType: "text",
                    cache: false,
                    url: '/pagamento-pagamento/list-saques-realizados',
                    async: true,
                    data: {},
                    success: function (data) {

                        $('#collapseTen').html(data);
                        
                        var win = window.open('/pagamento-pagamento/dados-usuario/' + idPagamento, '_blank');
                        win.focus();
                    }
                });
            }
        });
    });

    $('#btn-negar-view-pay-saque').off('click').on('click', function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/pagamento-pagamento/negar-saque',
            async: true,
            complete: function () {
                $('#collapseTen').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
            },
            data: {
                id_saque: $('#idLiberarSaque').val()
            },
            success: function () {

                $.ajax({
                    type: "post",
                    dataType: "text",
                    cache: false,
                    url: '/pagamento-pagamento/list-saques-realizados',
                    async: true,
                    data: {},
                    success: function (data) {

                        $('#collapseTen').html(data);
                    }
                });
            }
        });
    });
});