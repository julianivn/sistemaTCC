$(function() {

    "use strict";

    const $form = $('#form-js');
    const itemID = $form.find('#id').val();
    const restURL = './aluno/';
    const listaURL = './aluno/';

	$('#telefone').mask('(99) 9999-9999?9');
	
    function verifyErrors(err) {
        const errors = err || {};

        $.each(['nome', 'email', 'telefone', 'cgu', 'matricula'], function(key, value) {
            const message = errors[value] || false;
            const element = $form.find('#' + value);
            if (message) {
                element.parent().addClass('has-error').find('.help-block').html(message);
            } else {
                element.parent().removeClass('has-error').find('.help-block').html('');
            }
        });
    }

    $form.on('submit', function(event) {
        event.preventDefault();

        const values = {
            nome: $form.find('#nome').val(),
          	matricula: $form.find('#matricula').val(),
            telefone: $form.find('#telefone').val(),
            email: $form.find('#email').val(),
            sexo: $form.find('[name=sexo]').val(),
          	cgu:  $form.find('#cgu').val(),
        };


        const url = restURL + (itemID ? itemID + '/' : '' );
        const method = itemID ? 'put' : 'post';

        const request = $.ajax({
                url: url,
                type: method,
                dataType: 'json',
                data: values
            });

        // Caiu aqui deu certo
        request.done(function(data) {
            verifyErrors();
            swal({
                title: "OK",
                text: "Alterado!",
                type: "success",
                showCancelButton: false,
                confirmButtonText: "Voltar para Lista",
                closeOnConfirm: false },
                function() {
                    location.href = listaURL;
                });
        });

        // Caiu aqui, tem erro
        request.fail(function(err) {
            const errors = err.responseJSON;
            verifyErrors(errors);
        });

    });

});
