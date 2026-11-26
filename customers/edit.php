<?php
include "function.php";
exige_login();
edit();
$titulo_pagina = 'Editar Cliente';
include HEADER_TEMPLATE;
?>

<h2>Atualizar Cliente</h2>

<?php if ($cliente): ?>
<form action="edit.php?id=<?= (int) $cliente['id']; ?>" method="post" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <!-- area de campos do form -->
    <hr>
    <div class="row">
        <div class="col-md-4 col-lg-3">
            <div class="capa-preview">
                <img src="<?php echo foto($cliente['foto']); ?>" id="preview" data-original="<?php echo foto($cliente['foto']); ?>"
                    class="foto-detalhe" alt="Foto de <?php echo htmlspecialchars($cliente['nome']); ?>">
            </div>
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="row">
                <div class="form-group col-md-8">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" id="nome" name="cliente[nome]" maxlength="50" required
                        value="<?php echo htmlspecialchars($cliente['nome']); ?>">
                </div>

                <div class="form-group col-md-4">
                    <label for="datacadastro">Data de Cadastro</label>
                    <input type="date" class="form-control" id="datacadastro" disabled
                        value="<?php echo formatData($cliente['datacadastro'], "Y-m-d"); ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="cpf">CPF</label>
                    <input type="text" class="form-control" id="cpf" name="cliente[cpf]" maxlength="14"
                        inputmode="numeric" placeholder="000.000.000-00" required
                        value="<?php echo htmlspecialchars($cliente['cpf']); ?>">
                </div>

                <div class="form-group col-md-6">
                    <label for="telefone">Telefone</label>
                    <input type="tel" class="form-control" id="telefone" name="cliente[telefone]" maxlength="15"
                        placeholder="(00) 00000-0000" required
                        value="<?php echo htmlspecialchars($cliente['telefone']); ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="cliente[email]" maxlength="100" required
                        value="<?php echo htmlspecialchars($cliente['email']); ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <label for="foto">Nova foto (opcional)</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                </div>
            </div>
        </div>
    </div>

    <div id="actions" class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Salvar
            </button>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="fa-solid fa-rotate-left"></i> Cancelar
            </a>
        </div>
    </div>
</form>
<?php else: ?>
    <hr>
    <div class="alert alert-danger" role="alert">Cliente não encontrado!</div>
    <a href="index.php" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-rotate-left"></i> Voltar</a>
<?php endif; ?>

<?php include FOOTER_TEMPLATE; ?>
