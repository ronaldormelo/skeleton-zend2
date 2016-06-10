$(function () {

    $('#form-test').on('submit', function(e) {

        e.preventDefault();  //prevent form from submitting
        
         $.ajax({
            type: "post",
            dataType: "text",
            cache: true,
            url: '/mcnetwork-cursos/save-test',
            async: true,
            beforeSend: function () {

                $('#course-content').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
            },
            data: {
                data : $("#form-test :input").serializeArray()
            },
            success: function (data) {

                $('#course-content').html(data);
            }
        });
    });
});