$(function () {

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
    $("#tablesorterExtrato").tablesorter({
        // this will apply the bootstrap theme if "uitheme" widget is included
        // the widgetOptions.uitheme is no longer required to be set
        theme: "bootstrap",
        widthFixed: true,
        headerTemplate: '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

        // widget code contained in the jquery.tablesorter.widgets.js file
        // use the zebra stripe widget if you plan on hiding any rows (filter widget)
        widgets: ["uitheme", "filter", "zebra"],
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
            6: {filter: false, sorter: false}
        }
    }).tablesorterPager({
        // target the pager markup - see the HTML block below
        container: $(".ts-pager"),
        // target the pager page select dropdown - choose a page
        cssGoto: ".pagenum",
        // remove rows from the table to speed up the sort of large tables.
        // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
        removeRows: false,
        // output string - default is '{page}/{totalPages}';
        // possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
        output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'
    });

    // call the tablesorter plugin and apply the uitheme widget
    $("#tablesorterFatura").tablesorter({
        // this will apply the bootstrap theme if "uitheme" widget is included
        // the widgetOptions.uitheme is no longer required to be set
        theme: "bootstrap",
        widthFixed: true,
        headerTemplate: '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

        // widget code contained in the jquery.tablesorter.widgets.js file
        // use the zebra stripe widget if you plan on hiding any rows (filter widget)
        widgets: ["uitheme", "filter", "zebra"],
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
            5: {filter: false, sorter: false}
        }
    });

    $('.btnConprovantePagamento').off('click').on('click', function () {

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

                $('#modal-view-pay-body-extrato').html(data);
                $('#modal-view-pay-extrato').modal('show');
            }
        });
    });

    $('#btnSolicitarSaque').off('click').on('click', function () {

        var vl_saque = $('#vl_saque').val();

        if ($.trim(vl_saque) != '') {

            $('#modal-confirm-saque-body-message').html('Deseja realmente solicitar o saque no valor de R$ ' + $.trim(vl_saque) + ',00 do seu saldo?');
            $('#modal-confirm-saque').modal('show');

            $('#btn-ok-confirm-saque').off('click').on('click', function () {

                var pw_senha_financeira = $('#pw_senha_financeira_saque').val();

                $('#modal-confirm-saque').modal('hide');
                $.ajax({
                    type: "post",
                    dataType: "json",
                    cache: false,
                    url: '/pagamento-pagamento/solicitar-saque',
                    async: true,
                    data: {
                        vl_saque: $.trim(vl_saque),
                        pw_senha_financeira: pw_senha_financeira
                    },
                    success: function (data) {

                        if (data.status == 'success') {

                            var message = data.message;

                            $.ajax({
                                type: "post",
                                dataType: "text",
                                cache: false,
                                url: '/pagamento-pagamento/extrato',
                                async: true,
                                data: {},
                                success: function (data) {

                                    $('#collapseSix').html(data);
                                    $('#modal-confirm-saque').modal('hide');
                                    $('#modal-message-saque-body').html(
                                            "<div class='bs-callout bs-callout-success'>" +
                                            "<span class='glyphicon glyphicon-exclamation-sign'></span> " +
                                            message
                                            +
                                            "</div>"
                                            );
                                    $('#modal-message-saque').modal('show');
                                }
                            });
                        } else {

                            $('#modal-message-saque-body').html(
                                    "<div class='bs-callout bs-callout-danger'>" +
                                    "<span class='glyphicon glyphicon-exclamation-sign'></span> " +
                                    data.message
                                    +
                                    "</div>"
                                    );
                            $('#modal-message-saque').modal('show');
                        }
                    }
                });
            });
        }
    });

    $('#gerar_recibo_bonus').off('click').on('click', function () {

        var id = $(this).attr('value');
        var win = window.open('/pagamento-pagamento/gerar-recibo/' + id, '_blank');
        win.focus();
    });

    $('#btnPagamentoComBonus').off('click').on('click', function () {

        var nr_referencia = $('#nr_referencia').val();

        if ($.trim(nr_referencia) != '') {

            $.ajax({
                type: "post",
                dataType: "json",
                cache: false,
                url: '/pagamento-pagamento/verificar-liberar-pagamento-bonus',
                async: true,
                data: {
                    nr_referencia: nr_referencia
                },
                success: function (data) {

                    if (data.status == 'success') {

                        $('#modal-confirm-pagamento-bonus-body-message').html(
                                data.message
                                );
                        $('#modal-confirm-pagamento-bonus').modal('show');

                    } else {
                        $('#modal-confirm-pagamento-bonus-body-error').html("<div class='bs-callout bs-callout-danger'>" +
                                "<span class='glyphicon glyphicon-exclamation-sign'></span> " + data.message +
                                "</div>");
                        $('#modal-confirm-pagamento-bonus-error').modal('show');
                    }
                }
            });
        }
    });

    $('.btn-extrato-fatura').off('click').on('click', function () {

        var input = $(this);
        var win = window.open('/boleto/itau/html/' + input.attr('value'), '_blank');
        win.focus();
    });

    $('#btn-ok-confirm-pagamento').off('click').on('click', function () {

        var nr_referencia = $('#nr_referencia').val();
        var pw_senha_financeira = $('#pw_senha_financeira_bonus').val();

        if (($.trim(nr_referencia) != '') && ($.trim(pw_senha_financeira) != '')) {
            $.ajax({
                type: "post",
                dataType: "json",
                cache: false,
                url: '/pagamento-pagamento/liberar-pagamento-bonus',
                async: true,
                data: {
                    nr_referencia: nr_referencia,
                    pw_senha_financeira: pw_senha_financeira
                },
                success: function (data) {

                    if (data.status == 'success') {

                        $('#modal-message-saque-body').html("<div class='bs-callout bs-callout-success'>" +
                                "<span class='glyphicon glyphicon-exclamation-sign'></span> " +
                                data.message +
                                "</div>"
                                );
                        $('#modal-confirm-pagamento-bonus').modal('hide');
                        $('#modal-message-saque').modal('show');
                        $('#pw_senha_financeira_bonus').val('');

                    } else {
                        $('#modal-message-saque-body').html("<div class='bs-callout bs-callout-danger'>" +
                                "<span class='glyphicon glyphicon-exclamation-sign'></span> " +
                                data.message +
                                "</div>");
                        $('#modal-confirm-pagamento-bonus').modal('hide');
                        $('#modal-message-saque').modal('show');
                        $('#pw_senha_financeira_bonus').val('');
                    }
                }
            });
        }
    });

    setTimeout(function () {
        $("button.btn.last").trigger('click');
    }, 1000);

});