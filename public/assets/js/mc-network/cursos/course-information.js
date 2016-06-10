$(function () {
    $('#reenviar-email').off('click').on('click', function () {

        $.ajax({
            type: "post",
            dataType: "json",
            cache: false,
            url: '/usuario-usuario/reenviar-email',
            async: true,
            success: function (data) {

                $('#modal-course-information').modal('hide');
                
                $('#modal-course-reenvio-email').modal('show');
            }
        });
    });
});

