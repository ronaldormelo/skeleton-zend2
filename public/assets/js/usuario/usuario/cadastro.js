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
    
})