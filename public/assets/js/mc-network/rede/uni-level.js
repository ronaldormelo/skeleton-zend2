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


    $('#collapseNivel1').click(function () {

        $('#collapseRede1').slideToggle();
    });

    $('#collapseNivel2').click(function () {

        $('#collapseRede2').slideToggle();
    });

    $('#collapseNivel3').click(function () {

        $('#collapseRede3').slideToggle();
    });
});
