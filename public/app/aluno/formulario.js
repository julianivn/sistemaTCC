$(function() {

    const $form = $('#form-js');
    const itemID = $form.find('#id').val();
    const restURL = './aluno/';
    const listaURL = './aluno/listar';

    function verifyErrors(err) {
        const errors = err || {};
      // blz..aqui ele vai verificar se tem campos com erros que oserver manda...
      /// adiciona um classe que mostra  amensagem
        $.each(['nome', 'email', 'telefone', 'cgu', 'matricula', 'sexo'], function(key, value) {
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
				// Aqui... muda para os teus campos...ok ? Isso faz aqui..entao..
      //Tipo esse '#nome' é por causa que é id?
        const values = {
            nome: $form.find('#nome').val(),
          	matricula: $form.find('#matricula').val(),
            telefone: $form.find('#telefone').val(),
            email: $form.find('#email').val(),
            sexo: $form.find('#sexo option:selected').val(),
          	cgu:  $form.find('#cgu').val(),
        };

				// manda ver
       //jquery.min.js:4 PUT http://localhost/aluno/2/ 405 (Method Not Allowed)
      // nao entrao no JS e na linha 5 e 6 coloca o ponto
//      const restURL = './professor/'; ali em cima...e testa de novo
      //Acabou ou tem que modificar mais alguma coisa?Não sei, minha tarefa era Frond End - Editar Aluno.
      // Shoe...mostrou a telinha animada ?
      // acho que isso...só tipo não sei se é tua parte ou nao...fazer as validacoes?
      // entao o backend tem que faaer as validacoes..porque testa ai apaga os dados e salva..deixa em branco
      //Ok
      // entao deixa assim... pelo menos funciona....
      // da o git push ai eu baixo aqui pra ver
      //Feito subi pra minha branch. vou ver
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
