var hidWidth;
var scrollBarWidths = 40;

$(function () {

    $('.abrir-modal-video-suporte').off('click').on('click', function () {

        var input = $(this);

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-suporte/view-video',
            async: true,
            data: {
                cod: input.attr('value')
            },
            success: function (data) {

                $('#modal-suporte-access-body').html(data);
                $('#modal-suporte-access').modal('show');
            }
        });
    });

    $('#btnAdicionarEmpresaSuporte').off('click').on('click', function () {

        var id_empresa = [];
        $("input[name='id_empresa[]']:checked:enabled").each(function () {

            id_empresa.push($(this).val());
        });

        $.ajax({
            type: "post",
            dataType: "json",
            cache: false,
            url: '/mcnetwork-suporte/gravar',
            async: false,
            data: {
                id_empresa: id_empresa
            },
            success: function (data) {

                if (data.status == 'success') {
                    
                    var painel = $('#Collapsible13');

                    $.ajax({
                        type: "post",
                        dataType: "text",
                        cache: true,
                        url: '/mcnetwork-suporte/list',
                        async: true,
                        beforeSend: function () {

                            $('#collapseThirteen').html(
                                    '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                            );                            
                        },
                        data: {},
                        success: function (data) {

                            $('#collapseThirteen').html(data);

                            $('#course-information-suporte').on('click', function () {

                                $.ajax({
                                    type: "post",
                                    dataType: "text",
                                    cache: true,
                                    url: '/mcnetwork-cursos/course-information',
                                    async: true,
                                    data: {},
                                    success: function (data) {

                                        $('#modal-course-information-body').html(data);
                                        $('#modal-course-information').modal('show');
                                    }
                                });

                            });
                        }
                    });
                }
            }
        });
    });

    $('#modal-suporte-access').on('hidden.bs.modal', function () {
        $('#modal-suporte-access-body').html('');
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