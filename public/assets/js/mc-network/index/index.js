$(function () {

//@TODO
    loadCollapseOne();

    $('#Collapsible1').click(function () {

        $('.collapse').slideUp();
        loadCollapseOne();
    });
    $('#Collapsible2').click(function () {

        $('.collapse').slideUp();
        loadCollapseTwo();

    });
    $('#Collapsible3').click(function () {

        $('.collapse').slideUp();
        loadCollapseThree();
    });
    $('#Collapsible4').click(function () {

        $('.collapse').slideUp();
        loadCollapseFour();
    });
    $('#Collapsible5').click(function () {

        $('.collapse').slideUp();
        loadCollapseFive();
    });
    $('#Collapsible6, #btn_list_extrato1, #btn_list_extrato2').click(function () {

        $('.collapse').slideUp();
        loadCollapseSix();
    });
    $('#Collapsible7').click(function () {

        $('.collapse').slideUp();
        loadCollapseSeven();
    });
    $('#Collapsible8').click(function () {

        $('.collapse').slideUp();
        loadCollapseEight();
    });
    $('#Collapsible9').click(function () {

        $('.collapse').slideUp();
        loadCollapseNine();
    });
    $('#Collapsible10').click(function () {

        $('.collapse').slideUp();
        loadCollapseTen();
    });
    $('#Collapsible11').click(function () {

        $('.collapse').slideUp();
        loadCollapseEleven();
    });
    $('#Collapsible12').click(function () {

        $('.collapse').slideUp();
        loadCollapseTwelve();
    });
    $('#Collapsible13').click(function () {

        $('.collapse').slideUp();
        loadCollapseThirteen();
    });
    $('#Collapsible14').click(function () {

        $('.collapse').slideUp();
        loadCollapseFourteen();
    });

    $('[data-toggle="offcanvas"]').click(function () {
        $('.row-offcanvas').toggleClass('active')
    });
});

function loadCollapseOne() {

    var painel = $('#Collapsible1');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: true,
            url: '/mcnetwork-cursos/list',
            async: true,
            beforeSend: function () {

                $('#collapseOne').html(
					'<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
				);
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseOne').slideToggle();
            },
            data: {},
            success: function (data) {

                $('#collapseOne').html(data);

                $('#course-information').on('click', function () {

                    $.ajax({
                        type: "post",
                        dataType: "text",
                        cache: true,
                        url: '/mcnetwork-cursos/course-information',
                        async: true,
                        data: {},
                        success: function (data) {

                            $('#modal-course-information-body').html(data);
                            $('#modal-course-information').modal('show');
                        }
                    });
                });
            }
        });
    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}

function loadCollapseTwo() {

    var painel = $('#Collapsible2');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-rede/list',
            async: true,
            data: {},
            beforeSend: function () {

                $('#collapseTwo').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseTwo').slideToggle();
            },
            success: function (data) {

                $('#collapseTwo').html(data);
            }
        });

    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}
function loadCollapseThree() {

    var painel = $('#Collapsible3');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-rede/up-line',
            async: true,
            data: {},
            beforeSend: function () {

                $('#collapseThree').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseThree').slideToggle();
            },
            success: function (data) {

                $('#collapseThree').html(data);
            }
        });

    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}
function loadCollapseFour() {

    var painel = $('#Collapsible4');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-rede/uni-level',
            async: true,
            data: {},
            beforeSend: function () {

                $('#collapseFour').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseFour').slideToggle();
            },
            success: function (data) {

                $('#collapseFour').html(data);

                $('#course-information-rede').on('click', function () {

                    $.ajax({
                        type: "post",
                        dataType: "text",
                        cache: true,
                        url: '/mcnetwork-cursos/course-information',
                        async: true,
                        data: {},
                        success: function (data) {

                            $('#modal-course-information-body').html(data);
                            $('#modal-course-information').modal('show');
                        }
                    });

                });
            }
        });

    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}
function loadCollapseFive() {

    var painel = $('#Collapsible5');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/usuario-usuario/dados-pessoais',
            async: true,
            beforeSend: function () {

                $('#collapseFive').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseFive').slideToggle();
            },
            data: {},
            success: function (data) {

                $('#collapseFive').html(data);
            }
        });

    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}
function loadCollapseSix() {

    var painel = $('#Collapsible6');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/pagamento-pagamento/extrato',
            async: true,
            beforeSend: function () {

                $('#collapseSix').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseSix').slideToggle();
            },
            data: {},
            success: function (data) {

                $('#collapseSix').html(data);

                $('#course-information-financeiro-fatura,#course-information-financeiro-saque,#course-information-financeiro-extrato,#course-information-financeiro-saldo').each(function () {
                    $(this).on('click', function () {

                        $.ajax({
                            type: "post",
                            dataType: "text",
                            cache: true,
                            url: '/mcnetwork-cursos/course-information',
                            async: true,
                            data: {},
                            success: function (data) {

                                $('#modal-course-information-body').html(data);
                                $('#modal-course-information').modal('show');
                            }
                        });

                    });
                });
            }
        });

    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}

function loadCollapseSeven() {

    var painel = $('#Collapsible7');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/pagamento-pagamento/list-pagamentos-realizados',
            async: true,
            beforeSend: function () {

                $('#collapseSeven').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseSeven').slideToggle();
            },
            data: {},
            success: function (data) {

                $('#collapseSeven').html(data);
            }
        });

    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}

function loadCollapseEight() {

    var painel = $('#Collapsible8');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/pagamento-pagamento/list-contratos-pendentes',
            async: true,
            beforeSend: function () {

                $('#collapseEight').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseEight').slideToggle();
            },
            data: {},
            success: function (data) {

                $('#collapseEight').html(data);
            }
        });

    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}

function loadCollapseNine() {

    var painel = $('#Collapsible9');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-rede/list-ativacao',
            async: true,
            data: {},
            beforeSend: function () {

                $('#collapseNine').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseNine').slideToggle();
            },
            success: function (data) {

                $('#collapseNine').html(data);

            }
        });

    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}

function loadCollapseTen() {

    var painel = $('#Collapsible10');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/pagamento-pagamento/list-saques-realizados',
            async: true,
            data: {},
            beforeSend: function () {

                $('#collapseTen').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseTen').slideToggle();
            },
            success: function (data) {

                $('#collapseTen').html(data);

            }
        });

    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}

function loadCollapseEleven() {

    var painel = $('#Collapsible11');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-relatorio/index',
            async: true,
            data: {},
            beforeSend: function () {

                $('#collapseEleven').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseEleven').slideToggle();
            },
            success: function (data) {

                $('#collapseEleven').html(data);

            }
        });

    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}

function loadCollapseTwelve() {

    var painel = $('#Collapsible12');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
            url: '/mcnetwork-divisao/index',
            async: true,
            data: {},
            beforeSend: function () {

                $('#collapseTwelve').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseTwelve').slideToggle();
            },
            success: function (data) {

                $('#collapseTwelve').html(data);

            }
        });

    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}

function loadCollapseThirteen() {

    var painel = $('#Collapsible13');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: true,
            url: '/mcnetwork-suporte/list',
            async: true,
            beforeSend: function () {

                $('#collapseThirteen').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseThirteen').slideToggle();
            },
            data: {},
            success: function (data) {

                $('#collapseThirteen').html(data);

                $('#course-information-suporte').on('click', function () {

                    $.ajax({
                        type: "post",
                        dataType: "text",
                        cache: true,
                        url: '/mcnetwork-cursos/course-information',
                        async: true,
                        data: {},
                        success: function (data) {

                            $('#modal-course-information-body').html(data);
                            $('#modal-course-information').modal('show');
                        }
                    });

                });
            }
        });

    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}
function loadCollapseFourteen() {

    var painel = $('#Collapsible14');

    if (painel.parent().hasClass('panel-default')) {

        $.ajax({
            type: "post",
            dataType: "text",
            cache: true,
            url: '/comunicado-comunicado/index',
            async: true,
            beforeSend: function () {

                $('#collapseFourteen').html(
                        '<div class="row"><div class="col-md-12 text-center"><p><img src="/assets/img/carregando.gif"><p></div></div>'
                        );
                $('.collapse').parent().removeClass('panel-primary').addClass('panel-default');
                painel.parent().removeClass('panel-default').addClass('panel-primary');
                $('#collapseFourteen').slideToggle();
            },
            data: {},
            success: function (data) {

                $('#collapseFourteen').html(data);
            }
        });

    } else {

        painel.parent().removeClass('panel-primary').addClass('panel-default');
    }
}