$(function () {

    $('.collapseCurriculumTitle').off().on('click', function () {
    
        var painel = $(this);
        
        $.ajax({
            type: "post",
            dataType: "text",
            cache: true,
            url: '/mcnetwork-cursos/course-content',
            async: true,
            beforeSend: function () {

                $('#course-content').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapseCurriculumTitle').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
            },
            data: {
                id: painel.attr('value')
            },
            success: function (data) {

                $('#course-content').html(data);
            }
        });
    });
});