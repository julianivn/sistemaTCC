;(function(){
  'use strict';

  const $lista = $('.lista-etapa-js');
  const $btnCadSemestre = $('#btn-cadastrar-semestre');
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


  $btnCadSemestre.on('click', function(e){
    e.preventDefault();

    const body = {
      campus: 'campus',
      ano: 'ano',
      semestre: '',
      tcc1: [121, 143],
      tcc2: [123,232,232,232]
    }
  })


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
          setTimeout(function(){
            location.reload();
          }, 2000);
        });

        request.fail(function(data){
          swal("Erro!", "Erro ao deletar a etapa, tente novamente", "error");
        });
      } else {
        swal("Cancelado", "A etapa não foi deletada!", "error");
        setTimeout(function(){
          location.reload();
        }, 2000);
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
