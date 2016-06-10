var hidWidth;
var scrollBarWidths = 40;

$(function () {

    $('.access-course').off('click').on('click', function () {

        var input = $(this);

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-cursos/access-course',
            async: true,
            data: {
                id: input.attr('value')
            },
            success: function (data) {

                $('#modal-course-access').children().children().children().find('.modal-title').html($('#title_curso_' + input.attr('value')).val());
                $('#modal-course-access-body').html(data);
                $('#modal-course-access').modal('show');

                setTimeout(function () {
                    videojs(input.attr('value'), {}, function () {
                        // Player (this) is initialized and ready.
                    });
                }, 1000);
            }
        });
    });

    $('#modal-course-access').on('hidden.bs.modal', function () {
        $('#modal-course-access-body').html('');
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