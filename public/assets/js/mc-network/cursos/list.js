$(function () {

    $('.collapseCourseTitle').off('click').on('click', function () {

        $('.collapseCourse').slideUp();
        loadCollapseCourse(this);
    });


});

function loadCollapseCourse(obj) {

    var painel = $(obj);

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: true,
            url: '/mcnetwork-cursos/list-unit',
            async: true,
            beforeSend: function () {

                painel.parent().find('.collapseCourse').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapseCourse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                painel.parent().find('.collapseCourse').slideToggle();
            },
            data: {
                id: painel.attr('value')
            },
            success: function (data) {

                painel.parent().find('.collapseCourse').html(data);
            }
        });
        $.ajax({
            type: "post",
            dataType: "text",
            cache: true,
            url: '/mcnetwork-cursos/course-instructions',
            async: true,
            beforeSend: function () {

                $('#course-content').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
            },
            data: {
                id: painel.attr('value')
            },
            success: function (data) {

                $('#course-content').html(data);
            }
        });
    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}