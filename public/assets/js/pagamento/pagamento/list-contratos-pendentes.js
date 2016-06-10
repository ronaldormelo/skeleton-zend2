$(function () {

    $('.btnExcluirContrato').off('click').on('click', function () {

        var input = $(this);
        $('#idContratoPendente').val(input.attr('value'));
        $('#modal-remove-contrato').modal('show');
    });

    $('#btn-remove-contrato').off('click').on('click', function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/pagamento-pagamento/excluir-contrato',
            async: true,
            complete: function () {
                $('#collapseEight').html(
                    '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                );
            },
            data: {
                id_contrato: $('#idContratoPendente').val()
            },
            success: function () {

                $.ajax({
                    type: "post",
                    dataType: "text",
                    cache: false,
                    url: '/pagamento-pagamento/list-contratos-pendentes',
                    async: true,
                    data: {},
                    success: function (data) {

                        $('#collapseEight').html(data);
                    }
                });
            }
        });
    });
});