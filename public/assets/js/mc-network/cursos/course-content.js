$(function () {

    $('#btn-mark-completion').off('click').on('click', function () {
    
        var btn = $(this);
        
        $.ajax({
            type: "post",
            dataType: "text",
            cache: true,
            url: '/mcnetwork-cursos/mark-completion',
            async: true,
            beforeSend: function () {

                $('#course-content').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
            },
            data: {
                id: btn.attr('rel')
            },
            success: function (data) {

                $('#course-content').html(data);
            }
        });
    });
    
    $('#btn-prev-content, #btn-next-content').off('click').on('click', function () {
    
        var btn = $(this);
        
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
            },
            data: {
                id: btn.attr('rel')
            },
            success: function (data) {

                $('#course-content').html(data);
                
                $('.collapseCurriculumTitle').parent().removeClass('panel-primary').addClass('panel-default');
                $(".collapseCurriculumTitle[value='" + btn.attr('rel') + "']").parent().removeClass('panel-default').addClass('panel-primary');
                
            }
        });
    });
    
    $('#btn-start-test').off('click').on('click', function () {
    
        var btn = $(this);
        
        $.ajax({
            type: "post",
            dataType: "text",
            cache: true,
            url: '/mcnetwork-cursos/start-test',
            async: true,
            beforeSend: function () {

                $('#course-content').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
            },
            data: {
                id: btn.attr('rel')
            },
            success: function (data) {

                $('#course-content').html(data);
            }
        });
    });
});