;(function(){
  'use strict';

  const $lista = $('.lista-etapa-js');
  const url = '/semestre/deletar/';

  const swalExcluir = {
    title: "Você tem certeza?",
    text: "Excluindo essa etapa não será possível recuperá-la",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Deletar!",
    cancelButtonText: "Cancelar!",
    closeOnConfirm: false,
    closeOnCancel: false
  }

  const swalEditar = {
    title: "Editar essa etapa?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#8CD4F5",
    confirmButtonText: "Editar!",
    cancelButtonText: "Cancelar!",
    closeOnConfirm: false,
    closeOnCancel: false
  }

  $lista.on('click', '.btn-excluir-etapa', function(e){
    e.preventDefault();
    const etapaId = $(this).parent().data('id');

    if(!etapaId) return false;

    swal(swalExcluir,
    function(isConfirm){
      if (isConfirm) {
        var request = $.ajax({
              url: url + etapaId + '/',
              type: 'DELETE',
              dataType: 'json'
        });

        request.done(function(data){
          swal("Deletado!", "A etapa foi deletada com sucesso!", "success");
        });

        request.fail(function(data){
          swal("Erro!", "Erro ao deletar a etapa, tente novamente", "error");
        });
      } else {
    	    swal("Cancelado", "A etapa não foi deletada!", "error");
      }
    });
  });

  $lista.on('click', '.btn-editar-etapa', function(e){
    e.preventDefault();
    const etapaId = $(this).parent().data('id');

    if(!etapaId) return false;

    swal(swalEditar,
    function(isConfirm){
      if (isConfirm) {

      } else {
    	  swal("Cancelado", "A etapa não foi editada!", "error");
      }
    });
  });
})();
