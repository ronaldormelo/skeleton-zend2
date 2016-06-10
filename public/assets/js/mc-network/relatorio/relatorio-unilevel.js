$(function () {

    $('[data-toggle="popover"]').popover({
        'placement': 'right',
        html: true,
        trigger: "hover"
    });

    $('.start').click(function () {

        var input = this;
        if ($(input).parent().next().find('.fill:first').is(":hidden")) {

            $(input).parent().parent().children('tr').find('.fill:first, .empty:first').hide();
            $(input).parent().next().find('.fill:first').toggle();
        } else {
            $(input).parent().parent().children('tr').find('.fill:first').hide();
        }
    });
    $('.end').click(function () {

        var input = this;
        if ($(input).parent().next().find('.empty:first').is(":hidden")) {

            $(input).parent().parent().children('tr').find('.fill:first, .empty:first').hide();
            $(input).parent().next().find('.empty:first').toggle();
        } else {
            $(input).parent().parent().children('tr').find('.empty:first').hide();
        }
    });

    $("#imprimirRelatorioUnilevel").click(function () {

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
    
    $('#modal-relatorio-5').on('hidden.bs.modal', function () {

        $('#modal-relatorio-4').modal('show');
    });
});