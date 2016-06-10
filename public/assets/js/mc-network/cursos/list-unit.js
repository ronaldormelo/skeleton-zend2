$(function () { 

    $('.collapseCurriculumUnit').off('click').on('click', function () {

        $('.panel-list-content').slideUp();
        loadCollapseListUnit(this);
    });

});

function loadCollapseListUnit(obj) {

    var painel = $(obj);

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: true,
            url: '/mcnetwork-cursos/list-content',
            async: true,
            beforeSend: function () {

                painel.parent().find('.panel-list-content').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.panel-list-content').parent().removeClass('panel-info').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-info');
                painel.parent().find('.panel-list-content').slideToggle();
            },
            data: {
                id: painel.attr('value')
            },
            success: function (data) {

                painel.parent().find('.panel-list-content').html(data);
            }
        });
    } else {

        painel.parent().removeClass('panel-info').addClass('panel-default');
    }
}