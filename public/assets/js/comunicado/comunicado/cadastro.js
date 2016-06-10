$(function () {

    moment.locale('en', {
        days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado", "Domingo"],
        daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb", "Dom"],
        weekdays: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb", "Dom"],
        daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa", "Do"],
        weekdaysShort: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa", "Do"],
        weekdaysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa", "Do"],
        months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        today: "Hoje"
    });
    $('#datetimepicker1').datetimepicker({
        pickTime: true,
        format: 'DD/MM/YYYY HH:mm'
    });
    $('#datetimepicker2').datetimepicker({
        pickTime: true,
        format: 'DD/MM/YYYY HH:mm'
    });

    $('.btn-salvar-comunicado').on('click', function () {

        var id = $('#id').val();
        var nmTituloComunicado = $('#nm_titulo_comunicado').val();
        var txComunicado = $('#tx_comunicado').val();
        var dtComunicado = $('#dt_comunicado').val();
        var dtExpiracao = $('#dt_expiracao').val();
        var idSituacao = $('#id_situacao').val();
        var idTipoComunicado = $("input[type='radio'][name='id_tipo_comunicado']:checked").val();

        if (nmTituloComunicado != '' &&
                txComunicado != '' &&
                dtComunicado != '' &&
                dtExpiracao != '' &&
                idSituacao != '' &&
                idTipoComunicado != '') {

            $.ajax({
                type: "post",
                dataType: "json",
                cache: true,
                url: '/comunicado-comunicado/gravar',
                async: true,
                beforeSend: function () {
                },
                data: {
                    id: id,
                    nm_titulo_comunicado: nmTituloComunicado,
                    tx_comunicado: txComunicado,
                    dt_comunicado: dtComunicado,
                    dt_expiracao: dtExpiracao,
                    id_situacao: idSituacao,
                    id_tipo_comunicado: idTipoComunicado
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
                    } else {

                    }
                }
            });
        }
    });


    $('.btn-voltar-comunicado').on('click', function () {

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
    });
}); 