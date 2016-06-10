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

    $('#datetimepicker').datetimepicker({
        pickTime: false
    });

    var optionsPwstrength = {};
    optionsPwstrength.common = {
        minChar: 8,
        onKeyUp: function (evt, data) {
            if (!$(evt.target).is(":focus")) {
                if (data.verdictLevel == 0) {

                    $(evt.target).val('');
                    $(evt.target).pwstrength("forceUpdate");
                }
            }
        }
    };
    optionsPwstrength.ui = {
        showStatus: true,
        verdicts: ["fraca", "fraca", "media", "media", "forte"],
        showVerdicts: true,
        showVerdictsInsideProgressBar: true
    };
    optionsPwstrength.rules = {
        activated: {
            wordThreeNumbers: false,
            wordSequences: false
        }
    };
    $('#pw_senha').pwstrength(optionsPwstrength);


    $('input[name=id_perfil]').click(function () {
        if ($("input[name=id_perfil][value=3]").is(':checked')) {

            $('#spanContratoCredenciamentoVenda').hide();
        } else {

            $('#spanContratoCredenciamentoVenda').show();
        }
    });


    $('#ckhConcordo').click(function () {

        if ($('#ckhConcordo').is(':checked')) {

            if ($("input[name=id_perfil][value=3]").is(':checked')) {

                $('#modal-termo-uso').modal('show');
            } else {

                $('#modal-termo-uso-contrato').modal('show');
            }
        }
    });
}); 