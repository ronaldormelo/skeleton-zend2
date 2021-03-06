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

    $('.datetimepicker').datetimepicker({
        pickTime: false
    });

    // NOTE: $.tablesorter.theme.bootstrap is ALREADY INCLUDED in the jquery.tablesorter.widgets.js
    // file; it is included here to show how you can modify the default classes
    $.tablesorter.themes.bootstrap = {
        // these classes are added to the table. To see other table classes available,
        // look here: http://getbootstrap.com/css/#tables
        table: 'table table-bordered',
        caption: 'caption',
        header: 'bootstrap-header', // give the header a gradient background
        footerRow: '',
        footerCells: '',
        icons: '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
        sortNone: 'bootstrap-icon-unsorted',
        sortAsc: 'icon-chevron-up glyphicon glyphicon-chevron-up', // includes classes for Bootstrap v2 & v3
        sortDesc: 'icon-chevron-down glyphicon glyphicon-chevron-down', // includes classes for Bootstrap v2 & v3
        active: '', // applied when column is sorted
        hover: '', // use custom css here - bootstrap class may not override it
        filterRow: '', // filter row class
        even: '', // odd row zebra striping
        odd: ''  // even row zebra striping
    };

    // call the tablesorter plugin and apply the uitheme widget
    $("#tablesorterPagamentosRecebidos").tablesorter({
        // this will apply the bootstrap theme if "uitheme" widget is included
        // the widgetOptions.uitheme is no longer required to be set
        theme: "bootstrap",
        widthFixed: true,
        headerTemplate: '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

        // widget code contained in the jquery.tablesorter.widgets.js file
        // use the zebra stripe widget if you plan on hiding any rows (filter widget)
        widgets: ["uitheme", "zebra"],
        widgetOptions: {
            // using the default zebra striping class name, so it actually isn't included in the theme variable above
            // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
            zebra: ["even", "odd"],
            // reset filters button
            filter_reset: ".reset"

                    // set the uitheme widget to use the bootstrap theme class names
                    // this is no longer required, if theme is set
                    // ,uitheme : "bootstrap"

        },
        headers: {
            8: {sorter: false}
        }
    });

    $('#filtrarRelatorioPagamentosRecebidos').click(function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-relatorio/relatorio-pagamentos-recebidos',
            async: true,
            data: {
                dt_inicio: $('#dt_inicio').val(),
                dt_fim: $('#dt_fim').val()
            },
            beforeSend: function () {

                $('#modal-relatorio-body-1').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
            },
            success: function (data) {

                $('#modal-relatorio-body-1').html(data);
            }
        });
    });

    $('#limparRelatorioPagamentosRecebidos').click(function () {

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
            },
            success: function (data) {

                $('#modal-relatorio-body-1').html(data);
            }
        });
    });
    
    $('.relatorio-nota').click(function () {

        var idPagamento = $(this).attr('rel');

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-relatorio/relatorio-nota-usuario',
            async: true,
            data: {
                idPagamento: idPagamento
            },
            beforeSend: function () {

                $('#modal-relatorio-1').modal('hide');
                
                $('#modal-relatorio-body-7').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('#modal-relatorio-7').modal('show');
            },
            success: function (data) {

                $('#modal-relatorio-body-7').html(data);
            }
        });
    });    
    

    $("#imprimirRelatorioPagamentosRecebidos").click(function () {

        var modalPrint = $('.modalPrint');
        modalPrint.removeClass('modal');
        $('#printSection').addClass('printSection');
        $('.form-inline').hide();
        $('#printSection').html(modalPrint.html());
        window.print();
        modalPrint.addClass('modal');
        $('#printSection').removeClass('printSection');
        $('.form-inline').show();
        $('#printSection').html('');
    });
    
    
});