<?php
    include("../config.php");

    // encerra a sessão e começa uma nova, só para poder mostrar o aviso de saída
    $_SESSION = [];
    session_destroy();
    session_start();
    session_regenerate_id(true);

    flash("Você saiu da sua conta.", "info");
    redireciona(BASEURL . "index.php");
