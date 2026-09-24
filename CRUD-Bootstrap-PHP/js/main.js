/**
 * Passa os dados da revista para o Modal, e atualiza o link para exclusão
 */
$('#delete-modal').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  var id = button.data('revista');
  var nome = button.data('nome');
  
  var modal = $(this); // this -> indica o modal que está sendo aberto
  modal.find('.modal-title').text('Excluir Revista #' + id);
  modal.find('.modal-nome').text('"' + nome + '"');
  modal.find('#confirm').attr('href', 'delete.php?id=' + id);
})

/**
 * Mostra a capa escolhida no formulário antes de salvar.
 * Se o arquivo for removido, volta para a capa que estava antes.
 */
$('#foto').on('change', function () {

  var preview = $('#preview');
  var arquivo = this.files[0];

  if (!arquivo) {
    preview.attr('src', preview.data('original'));
    return;
  }

  var leitor = new FileReader();
  leitor.onload = function (e) {
    preview.attr('src', e.target.result);
  };
  leitor.readAsDataURL(arquivo);
})
