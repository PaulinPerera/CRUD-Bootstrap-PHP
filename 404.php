<?php
http_response_code(404);

include 'config.php';

$titulo_pagina = 'Página não encontrada';
include HEADER_TEMPLATE;
?>

<section class="text-center py-5" aria-labelledby="titulo-404">
    <div class="display-1 fw-bold text-primary">404</div>
    <h1 id="titulo-404" class="mb-3">Página não encontrada</h1>
    <p class="lead text-secondary mb-4">
        O endereço pode estar incorreto ou a página não está mais disponível.
    </p>
    <a class="btn btn-primary" href="<?php echo BASEURL; ?>index.php">
        <i class="fa-solid fa-house-chimney" aria-hidden="true"></i>
        Voltar ao início
    </a>
</section>

<?php include FOOTER_TEMPLATE; ?>
