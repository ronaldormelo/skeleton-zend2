$(function () {

    $('#btn-continuar-curso').off('click').on('click', function () {
    
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
            }
        });
    });
});