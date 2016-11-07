;(function(){
  'use strict';

  const $lista = $('.lista-etapa-js');
  const $formSemestre = $('#form-js');
  const $btnSalvarEtapa = $('#btn-salvar-etapa-js');
  const $btnAlterarEtapa = $('#btn-alterar-etapa-js');
  const urlSemestre = './semestre/';
  const urlEtapa = './etapa-semestre/';

  function init(){
    const id = $('#id-semestre').val();
    if(id) {
      $('#container-etapas-js').show();
      $('#btn-cadastrar-semestre').html('Cadastrar');
    }
    $('.datepicker').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });
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
  };

  const swalEditar = {
    title: "Editar essa etapa?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#8CD4F5",
    confirmButtonText: "Editar!",
    cancelButtonText: "Cancelar!",
    closeOnConfirm: false,
    closeOnCancel: false
  };

  const swalSalvar = {
    title: "Cadastrar essa etapa?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#8CD4F5",
    confirmButtonText: "Cadastrar!",
    cancelButtonText: "Cancelar!",
    closeOnConfirm: false,
    closeOnCancel: false
  };

  $formSemestre.on('submit', function(e){
    e.preventDefault();

    const body = {
      nome: $formSemestre.find('#nome').val(),
      dataInicio: $formSemestre.find('#data-inicio').val(),
      dataFim: $formSemestre.find('#data-final').val(),
      // tipo: $formSemestre.find('#campus').val(),
      campus: $formSemestre.find('#campus').val(),
    };

    const id = $('#id-semestre').val();

    if(id) {
      body.etapa_tcc1 = pushEtapa($('#etapa-tcc1-js').children());
      body.etapa_tcc2 = pushEtapa($('#etapa-tcc2-js').children());
    }

    function pushEtapa(elem){
      let arr = [];
      $.each(elem, function(index, e){
        arr.push(e.dataset.id);
      });
      return arr;
    }
    console.log(body);
    const url = urlSemestre + (id ? id + '/' : '');
    const method = id ? 'PUT' : 'POST';
    console.log(method);
    var request = $.ajax({
        url: url,
        type: method,
        dataType: 'json',
        data: body
    });

    request.done(function(data){
      if(data.id) {
          swal({
           title: "Cadastrado!",
           text: "Continuar cadastrando o semestre!",
           type: "success",
           confirmButtonText: "OK"
          },
          function(isConfirm){
           if (isConfirm) {
             location.href = urlSemestre + 'editar/' + data.id;
           }
          });
      }
    });

    request.fail(function(data){
      swal("Erro!", "Erro ao cadastrar o semestre, tente novamente", "error");
    });
  });


  $('.table').on('click', '.btn-cadastrar-etapa', function(event){
    event.preventDefault();
    $('#btn-open-etapa').click();
    var tccValue = $(this).parent().parent().parent().data('tcc');
    $('#add-tcc').val(tccValue);
  });


  $lista.on('click', '.btn-excluir-etapa', function(e){ //Excluir etapa
    e.preventDefault();

    const etapaId = $(this).parent().parent().data('id');
    if(!etapaId) return false;

    swal(swalExcluir,
    function(isConfirm){
      if (isConfirm) {
        var request = $.ajax({
              url: urlEtapa + etapaId + '/',
              type: 'DELETE',
              dataType: 'json'
        });

        request.done(function(data){
          swal({
           title: "Deletado!",
           text: "A etapa foi deletada com sucesso!",
           type: "success",
           confirmButtonText: "OK"
          },
          function(isConfirm){
           if (isConfirm) {
             location.reload();
           }
          });
        });

        request.fail(function(data){
          swal("Erro!", "Erro ao deletar a etapa, tente novamente", "error");
        });
      } else {
        swal("Cancelado!", "A etapa não foi deletada!", "error");
      }
    });
  });

  $lista.on('click', '.btn-editar-etapa', function(e){
    e.preventDefault();

    const etapaId = $(this).parent().parent().data('id');

    // GET - busca os dados da etapa e popula no modal
    var request = $.ajax({
        url: urlEtapa + etapaId + '/',
        type: 'GET',
        dataType: 'json',
    });

    request.done(function(data){
      $('#id-etapa').val(data.id);
      $('#etapa-edit-nome').val(data.nome);
      $('#etapa-edit-tipo').val(data.tipo);
      $('#etapa-edit-peso').val(data.peso);
      $('#etapa-edit-limite').val(data.dataFim);
      $('#etapa-edit-descricao').val(data.ordem);
      $('#etapa-edit-abertura').val(data.dataInicio);
      $('#edit-tcc').val(data.tcc);
    });

    request.fail(function(data){
      $('#editar-etapa-js').find('.form-group').hide();
      $('#editar-etapa-js').html('Houve um problema');
    });
  });


  $btnAlterarEtapa.on('click', function(e){ // função alterar etapa
    e.preventDefault();

    const etapaId = $('#id-etapa').val();
    if(!etapaId) return false;

    const body = {
      id: etapaId,
      nome: $('#etapa-edit-nome').val(),
      tipo: $('#etapa-edit-tipo').val(),
      peso: $('#etapa-edit-peso').val(),
      dataFim: $('#etapa-edit-limite').val(),
      ordem: $('#etapa-edit-descricao').val(),
      dataInicio: $('#etapa-edit-abertura').val(),
      semestre: $('#id-semestre').val(),
      tcc: $('#edit-tcc').val()
    };
    console.log(body);
    swal(swalEditar,
    function(isConfirm){
      if (isConfirm) {
        var request = $.ajax({
              url: urlEtapa + etapaId + '/',
              type: 'PUT',
              dataType: 'json',
              data: body
        });

        request.done(function(data){
          swal({
           title: "Alterado!",
           text: "A etapa foi alterada com sucesso!",
           type: "success",
           confirmButtonText: "OK"
          },
          function(isConfirm){
           if (isConfirm) {
             location.reload();
           }
          });
        });

        request.fail(function(data){
          swal("Erro!", "Erro ao alterar a etapa, tente novamente", "error");
        });
      } else {
        swal("Cancelado", "A etapa não foi alterada!", "error");
      }
    });
  });

  $btnSalvarEtapa.on('click', function(e){ // função cadastrar etapa
    e.preventDefault();

    const body = {
      nome: $('#etapa-add-nome').val(),
      tipo: $('#etapa-add-tipo').val(),
      semestre: $('#id-semestre').val(),
      peso: $('#etapa-add-peso').val(),
      dataInicio: $('#etapa-add-abertura').val(),
      dataFim: $('#etapa-add-limite').val(),
      ordem: $('#etapa-add-descricao').val(),
      tcc: $('#add-tcc').val()
    };
    console.log(JSON.stringify(body));
    swal(swalSalvar,
    function(isConfirm){
      if (isConfirm) {
        var request = $.ajax({
              url: urlEtapa,
              type: 'POST',
              dataType: 'json',
              data: body
        });

        request.done(function(data){
          swal({
           title: "Alterado!",
           text: "A etapa foi alterada com sucesso!",
           type: "success",
           confirmButtonText: "OK"
          },
          function(isConfirm){
           if (isConfirm) {
             location.reload();
           }
          });
        });

        request.fail(function(data){
          swal("Erro!", "Erro ao alterar a etapa, tente novamente", "error");
        });
      } else {
        swal("Cancelado", "A etapa não foi alterada!", "error");
      }
    });
  });
})();
