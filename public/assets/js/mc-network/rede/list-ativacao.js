var hidWidth;
var scrollBarWidths = 40;

$(function () {

    $('.btn_ativar_id').off('click').on('click', function () {

        var input = $(this);
        $('#idEmpresaAsContrato').val(input.attr('value'));
        $('#modal-confirm-ativar-id').modal('show');
    });


    $('#btn_confirm_ativar_id').off('click').on('click', function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-rede/ativar-id',
            async: true,
            complete: function () {
                $('#collapseNine').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
            },
            data: {
                id_empresa_as_contrato: $('#idEmpresaAsContrato').val()
            },
            success: function () {

                $.ajax({
                    type: "post",
                    dataType: "text",
                    cache: false,
                    url: '/mcnetwork-rede/list-ativacao',
                    async: true,
                    data: {},
                    success: function (data) {

                        $('#collapseNine').html(data);
                    }
                });
            }
        });
    });

    $('#btn_negar_ativar_id').off('click').on('click', function () {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-rede/negar-id',
            async: true,
            complete: function () {
                $('#collapseNine').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
            },
            data: {
                id_empresa_as_contrato: $('#idEmpresaAsContrato').val()
            },
            success: function () {

                $.ajax({
                    type: "post",
                    dataType: "text",
                    cache: false,
                    url: '/mcnetwork-rede/list-ativacao',
                    async: true,
                    data: {},
                    success: function (data) {

                        $('#collapseNine').html(data);
                    }
                });
            }
        });
    });

    var widthOfList = function () {
        var itemsWidth = 0;
        $('.list li').each(function () {
            var itemWidth = $(this).outerWidth();
            itemsWidth += itemWidth;
        });
        return itemsWidth;
    };

    var widthOfHidden = function () {
        return (($('.wrapper').outerWidth()) - widthOfList() - getLeftPosi()) - scrollBarWidths;
    };

    var getLeftPosi = function () {
        return $('.list').position().left;
    };

    var reAdjust = function () {

        setTimeout(function () {

            if (($('.wrapper').outerWidth()) < widthOfList()) {
                $('.scroller-right').show();
            }
            else {
                $('.scroller-right').hide();
            }

            if (getLeftPosi() < 0) {
                $('.scroller-left').show();
            }
            else {
                $('.item').animate({left: "-=" + getLeftPosi() + "px"}, 'slow');
                $('.scroller-left').hide();
            }
        }, 500);
    };

    $(window).on('resize', function (e) {
        reAdjust();
    });

    $('.scroller-right').click(function () {

        $('.scroller-left').fadeIn('slow');
        $('.scroller-right').fadeOut('slow');

        $('.list').animate({left: "+=" + widthOfHidden() + "px"}, 'slow', function () {

        });
    });

    $('.scroller-left').click(function () {

        $('.scroller-right').fadeIn('slow');
        $('.scroller-left').fadeOut('slow');

        $('.list').animate({left: "-=" + getLeftPosi() + "px"}, 'slow', function () {

        });
    });

    reAdjust();
});
