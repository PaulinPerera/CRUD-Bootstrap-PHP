<?php
include "function.php";

exige_login();

// excluir só por POST (formulário do modal) e com o token CSRF: um link ou imagem de outro site não apaga nada
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redireciona('index.php');
}

exige_csrf('index.php');

delete($_POST['id'] ?? null);
