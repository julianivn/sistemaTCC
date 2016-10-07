;(function(){
  'use strict';

  const $lista = $('.lista-etapa-js');
  const $formSemestre = $('#form-js');
  const $btnSalvarEtapa = $('#btn-salvar-etapa-js');
  const url = '/semestre/deletar/';

  function init(){
    $('.datepicker').datepicker();
  }
  init();

  const swalExcluir = {
    title: "Você tem certeza?",
    text: "Após a exclusão não será possível recuperá-lo!",
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

  $formSemestre.on('submit', function(e){
    e.preventDefault();
    // const id = ('#id-semestre').val();
    const body = {
      campus: $formSemestre.find('#campus').val(),
      ano: $formSemestre.find('#ano').val(),
      semestre: $formSemestre.find('#semestre').val(),
      etapa_tcc1: pushEtapa($('#etapa-tcc1-js').children()),
      etapa_tcc2: pushEtapa($('#etapa-tcc2-js').children()),
    }
    
    function pushEtapa(elem){
      let arr = [];
      $.each(elem, function(index, e){
        arr.push(e.dataset.id);
      });
      return arr;
    }

    console.log(body);

  });


  $lista.on('click', '.btn-excluir-etapa', function(e){
    e.preventDefault();
    const etapaId = $(this).parent().parent().data('id');

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
    const etapaId = $(this).parent().parent().data('id');
    $('#id-etapa').val(etapaId);
  });

  $btnSalvarEtapa.on('click', function(e){
    e.preventDefault();
    alert('vai salvar');
  })
})();
