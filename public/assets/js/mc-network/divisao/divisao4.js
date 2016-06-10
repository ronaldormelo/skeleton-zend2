$(function () {

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
        $("#tablesorterDivisao4").tablesorter({
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
                //6: {filter: false, sorter: false}
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

    });

    $('#btnPagarDivisao4').off('click').on('click', function () {

        var valor_a_pagar = $('#valor_a_pagar_4').val();

        if ($.trim(valor_a_pagar) != '') {

            $('#modal-divisao-4').modal('hide');

            $('#modal-confirm-divisao-4-body').html('Deseja liberar o pagamento no valor de R$ ' + $.trim(valor_a_pagar) + ' para cada um dos usuários listados?');

            $('#modal-confirm-divisao-4').modal('show');

            $('#btn-ok-confirm-divisao-4').off('click').on('click', function () {

                $('#modal-confirm-divisao-4').modal('hide');
                $.ajax({
                    type: "post",
                    dataType: "json",
                    cache: false,
                    url: '/pagamento-pagamento/pagar-divisao4',
                    async: true,
                    data: {
                        valor_a_pagar_4: $.trim(valor_a_pagar)
                    },
                    success: function (data) {

                        if (data.status =='success') {

                            $('#modal-message-divisao-body').html(
                                    '<div class="col-12 col-sm-12 col-lg-12"><div class="bs-callout bs-callout-success">' +
                                    '<span class="glyphicon glyphicon-exclamation-sign"></span> ' + 
                                    data.message+ 
                                    '</div></div>');
                            $('#modal-message-divisao').modal('show');
                        } else {

                            $('#modal-message-divisao-body').html(
                                    '<div class="col-12 col-sm-12 col-lg-12"><div class="bs-callout bs-callout-danger">' +
                                    '<span class="glyphicon glyphicon-exclamation-sign"></span> ' + 
                                    data.message + 
                                    '</div></div>');
                            $('#modal-message-divisao').modal('show');
                        }
                    }
                });
            });
        }
    });
});