$(function() {

    var $lista = $('#lista-js');
    var url = './etapatipo/';
    var urlListar = './etapatipo/listar';

    function ajax(id) {
        var request = $.ajax({
                url: url + id + '/',
                type: 'delete',
                dataType: 'json',
            });
        // Caiu aqui deu certo
        request.done(function(data) {
            location.href = urlListar;
            return;
        });

        // Caiu aqui, tem erro
        request.fail(function(err) {
            swal("OPS!","A etapa possui algum vínculo e portanto não pode ser removido!" , "error");
            console.log(err.responseText);
            return false;
        });
    }

    $lista.on('click', '.excluir', function(event) {
        event.preventDefault();
        var id = $(this).data('id');
        if (!id) {
            return false;
        }
        swal({
            title: "Deseja mesmo excluir?",
            text: "Você irá remover esse registro!",
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, excluir agora!",
            closeOnConfirm: false },
            function(){
                swal("Ok!", "Registro excluido!", "success");
                ajax(id);
            });
    });

});
