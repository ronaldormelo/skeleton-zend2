var hidWidth;
var scrollBarWidths = 40;

$(function () {

    $('.abrir-modal-video').off('click').on('click', function () {

        var input = $(this);

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-rede/view-video',
            async: true,
            data: {
                cod: input.attr('value')
            },
            success: function (data) {

                $('#modal-video-apresentacao-body').html(data);
                $('#modal-video-apresentacao').modal('show');
            }
        });
    });

    $('.abrir-modal-video-apresentacao').off('click').on('click', function () {

        var input = $(this);

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-rede/view-video-apresentacao',
            async: true,
            data: {
                cod: input.attr('value')
            },
            success: function (data) {

                $('#modal-video-apresentacao-body').html(data);
                $('#modal-video-apresentacao').modal('show');
            }
        });
    });

    $('#modal-video-apresentacao').on('hidden.bs.modal', function () {
        $('#modal-video-apresentacao-body').html('');
    });

    $('.btn_solicitar_patrocinador').off('click').on('click', function () {

        var input = $(this);

        $.ajax({
            type: "post",
            dataType: "json",
            cache: false,
            url: '/mcnetwork-rede/solicitar-patrocinador',
            async: true,
            data: {
                cod_empresa: input.attr('value')
            },
            success: function (data) {

                if (data.status == 'success') {

                    $('#div_solicitar_patrocinador').html(
                            '<div class="col-12 col-sm-12 col-lg-12"><div class="bs-callout bs-callout-success">' +
                            '<span class="glyphicon glyphicon-exclamation-sign"></span> ' +
                            data.message +
                            '</div></div>');
                }
            }
        });
    });

    $('.btn_recusar_patrocinador').off('click').on('click', function () {

        var inputRecusar = $(this);
        $('#id_empresa_recusar').val(inputRecusar.attr('value'));
        $('#modal-confirm-recusar-patrocinador').modal('show');
    });

    $('#btn_confirm_recusar_patrocinador').off('click').on('click', function () {

        $.ajax({
            type: "post",
            dataType: "json",
            cache: false,
            url: '/mcnetwork-rede/recusar-patrocinador',
            async: true,
            data: {
                cod_empresa: $('#id_empresa_recusar').val()
            },
            success: function (data) {

                if (data.status == 'success') {

                    $('#div_solicitar_patrocinador').html(
                            '<div class="col-12 col-sm-12 col-lg-12"><div class="bs-callout bs-callout-success">' +
                            '<span class="glyphicon glyphicon-exclamation-sign"></span> ' +
                            data.message +
                            '</div></div>');
                }
            }
        });
    });

    $('.btn_enviar_id').off('click').on('click', function () {

        var input = $(this);

        if ($.trim(input.parent().parent().find('.tx_id').val()) == '') {

            input.parent().parent().find('.tx_id_error').html('<ul class="list-unstyled"><li style="color:#A94442;">Campo é obrigatório.</li></ul>');
        } else {

            $.ajax({
                type: "post",
                dataType: "json",
                cache: false,
                url: '/mcnetwork-rede/enviar-id',
                async: true,
                data: {
                    id: $.trim(input.parent().parent().find('.tx_id').val()),
                    cod_empresa: $.trim(input.parent().parent().find('.cod_empresa').val())
                },
                success: function (data) {

                    if (data.status == 'success') {

                        input.parent().parent().parent().parent().parent().parent('.div_enviar_id').html(
                                '<div class="col-12 col-sm-12 col-lg-12"><div class="bs-callout bs-callout-success">' +
                                '<span class="glyphicon glyphicon-exclamation-sign"></span> ' +
                                data.message +
                                '</div></div>');
                    } else {

                        input.parent().parent().find('.tx_id_error').html('<ul class="list-unstyled"><li style="color:#A94442;">' + data.message + '</li></ul>');
                    }
                }
            });
        }
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