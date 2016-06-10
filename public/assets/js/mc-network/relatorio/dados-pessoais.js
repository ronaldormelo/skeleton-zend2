$(function () {

    $('#modal-relatorio-8').on('hidden.bs.modal', function () {

        $('#modal-relatorio-4').modal('show');
    });

    $('#btn-salvar-edicao-usuario').off('click').on('click', function () {

        var id = $('#id').val();
        var nmUsuario = $('#nm_usuario').val();
        var emEmail = $('#em_email').val();
        var nuCpf = $('#nu_cpf').val();

        if (id != '' &&
                nmUsuario != '' &&
                emEmail != '' &&
                nuCpf != '') {

            $.ajax({
                type: "post",
                dataType: "json",
                cache: true,
                url: '/mcnetwork-relatorio/atualizar-dados',
                async: true,
                beforeSend: function () {
                },
                data: {
                    id: id,
                    nm_usuario: nmUsuario,
                    em_email: emEmail,
                    nu_cpf: nuCpf
                },
                success: function (data) {

                    if (data.status == 'success') {
                        
                        $('#modal-relatorio-8').modal('hide');
                        $('#modal-relatorio-4').modal('show');
                    }
                }
            });
        }
    });
});