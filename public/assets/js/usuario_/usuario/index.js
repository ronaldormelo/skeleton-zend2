$(function () {

    $.ajax({
        type: "post",
        dataType: "text",
        cache: false,
        url: '/usuario/index-pagination',
        async: true,
        data: {
        },
        beforeSend: function () {

            $('#usuario-pagination').html(
                '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
            );
        },
        success: function (data) {

            $('#usuario-pagination').html(data);
        }
    });
})