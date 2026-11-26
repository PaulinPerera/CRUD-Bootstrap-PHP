/**
 * Passa os dados do registro para o Modal de exclusão (revista, cliente ou usuário).
 * O botão "Excluir" informa data-id, data-nome e data-tipo; o id vai num campo do formulário POST.
 */
$(document).on('show.bs.modal', '#delete-modal', function (event) {

  var button = $(event.relatedTarget);
  var id = button.data('id');
  var nome = button.data('nome');
  var tipo = button.data('tipo') || 'Registro';

  var modal = $(this); // this -> indica o modal que está sendo aberto
  modal.find('.modal-title').text('Excluir ' + tipo + ' #' + id);
  modal.find('.modal-nome').text('"' + nome + '"');
  modal.find('#delete-id').val(id);
})

/**
 * Mostra a capa escolhida no formulário antes de salvar.
 * Se o arquivo for removido, volta para a capa que estava antes.
 */
$(document).on('change', '#foto', function () {

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

/**
 * Máscara do CPF: 000.000.000-00
 */
$(document).on('input', '#cpf', function () {
  var v = this.value.replace(/\D/g, '').slice(0, 11);
  v = v.replace(/(\d{3})(\d)/, '$1.$2')
       .replace(/(\d{3})(\d)/, '$1.$2')
       .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
  this.value = v;
})

/**
 * Máscara do telefone: (00) 0000-0000 ou (00) 00000-0000
 */
$(document).on('input', '#telefone', function () {
  var v = this.value.replace(/\D/g, '').slice(0, 11);
  if (v.length > 10) {
    v = v.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
  } else if (v.length > 6) {
    v = v.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
  } else if (v.length > 2) {
    v = v.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
  } else if (v.length > 0) {
    v = '(' + v;
  }
  this.value = v;
})
