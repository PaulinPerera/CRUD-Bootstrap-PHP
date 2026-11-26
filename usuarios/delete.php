<?php
include "function.php";

exige_admin();

// excluir só por POST (formulário do modal) e com o token CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redireciona('index.php');
}

exige_csrf('index.php');

delete($_POST['id'] ?? null);
