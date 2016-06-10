$(function () {

    $('#btn-nota-emitida').click(function () {

        var idPagamento = $('#id_pagamento_nota').val();

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-relatorio/cadastrar-nota',
            async: true,
            data: {
                idPagamento: idPagamento
            },
            beforeSend: function () {

                $('#modal-relatorio-7').modal('hide');

                $('#modal-relatorio-body-1').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('#modal-relatorio-1').modal('show');
            },
            success: function () {

                $.ajax({
                    type: "post",
                    dataType: "text",
                    cache: false,
                    url: '/mcnetwork-relatorio/relatorio-pagamentos-recebidos',
                    async: true,
                    data: {},                    
                    success: function (data) {

                        $('#modal-relatorio-body-1').html(data);
                    }
                });
            }
        });
    });

    $('#modal-relatorio-7').on('hidden.bs.modal', function () {

        $('#modal-relatorio-1').modal('show');
    });
});