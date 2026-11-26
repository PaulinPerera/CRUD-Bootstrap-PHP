<?php
    // Esse é o valida.php: só processa o login e redireciona (não mostra nada na tela)
    include("../config.php");
    require_once(DBAPI);

    $login = BASEURL . "inc/login.php";

    // só aceita o envio do formulário de login
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redireciona($login);
    }

    exige_csrf($login);

    $usuario = trim($_POST['login'] ?? '');
    $senha = (string) ($_POST['senha'] ?? '');

    if ($usuario === '' || $senha === '') {
        flash("Informe o usuário e a senha.", "warning");
        redireciona($login);
    }

    $dados = null;

    try {
        $db = open_database();

        // prepare + bind_param: o que foi digitado nunca vira parte do SQL
        $stmt = $db->prepare("SELECT id, nome, user, nivel, password FROM usuarios WHERE user = ? LIMIT 1");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $dados = $stmt->get_result()->fetch_assoc();

        close_database($db);
    } catch (Exception $e) {
        flash("Não foi possível conectar ao sistema. Tente novamente.", "danger");
        redireciona($login);
    }

    // a senha é guardada com hash (password_hash) e conferida com password_verify.
    // Com usuário inexistente também roda uma verificação, para a resposta demorar igual.
    $hash = $dados['password'] ?? '$2b$10$OiP/EzYeiGzVFS9UPA5wsu9FWe71bhh1C6AHHgJTMoS6kyMC/iXTa';
    $senhaOk = password_verify($senha, $hash);

    if ($dados && $senhaOk) {
        session_regenerate_id(true); // novo id de sessão a cada login (evita fixação de sessão)

        $_SESSION['id'] = (int) $dados['id'];
        $_SESSION['nome'] = $dados['nome'];
        $_SESSION['user'] = $dados['user'];
        $_SESSION['nivel'] = $dados['nivel'] ?? 'user';

        flash("Bem-vindo, " . h($dados['nome']) . "!", "success");

        // volta para a página que o usuário tentou abrir antes de entrar (só dentro do sistema)
        $destino = $_SESSION['voltar'] ?? '';
        unset($_SESSION['voltar']);

        if (strpos($destino, BASEURL) !== 0 || strpbrk($destino, "\r\n\\") !== false) {
            $destino = BASEURL . "index.php";
        }

        redireciona($destino);
    }

    flash("Usuário ou senha incorretos.", "danger");
    redireciona($login);
