var swalExcluir = {
  title: "Você tem certeza?",
  text: "Após a exclusão não será possível recupera os dados.",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "Deletar!",
  cancelButtonText: "Cancelar!",
  closeOnConfirm: false,
  closeOnCancel: false
};
var $lista = $('.lista-aluno-js');
$lista.on('click', '.excluir-aluno-js', function (e) {
  e.preventDefault();

  var alunoId = $(this).data('id');

  if (!alunoId)
    return false;

  swal(swalExcluir, function (isConfirm) {
    if (isConfirm) {
      var request = $.ajax({
        url: 'aluno/' + alunoId + '/',
        type: 'DELETE',
        dataType: 'json'
      });
      request.done(function (data) {
        swal("Deletado!", "O Aluno foi deletado com sucesso!", "success");
        $('#aluno-' + alunoId).remove();
      });
      request.fail(function (data) {
        swal("Erro!", "Erro ao deletar o aluno, tente novamente", "error");
      });
    } else {
      swal("Cancelado!", "O Aluno não foi deletado!", "error");
    }
  });
});
