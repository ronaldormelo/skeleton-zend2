$(function () {

    if ($('#id_estado').val()) {

        obterCidades($('#id_estado').val());
        $("#id_cidade").val($('#id_cidade_aux').val());
    }

    $('#id_estado').change(function () {

        obterCidades($(this).val());
    });
});

function obterCidades(val) {

    $.ajax({
        type: "post",
        dataType: "text",
        cache: false,
        url: '/cidade-cidade/obter-cidades',
        async: false,
        data: {
            id_estado: val
        },
        success: function (data) {

            $('#div_id_cidade').html(data);            
        }
    });
} 