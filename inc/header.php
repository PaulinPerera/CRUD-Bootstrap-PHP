
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>QuadriVerse - Revistas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="theme-color" content="#ff4800">

    <link rel="icon" type="image/png" href="<?php echo BASEURL; ?>img/icon.png">

    <!-- Fontes: IBM Plex Sans e Bangers -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Bangers&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap">

    <link rel="stylesheet" href="<?php echo BASEURL; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASEURL; ?>css/all.min.css">

    <!-- style.css deve ser o último -->
    <link rel="stylesheet" href="<?php echo BASEURL; ?>css/style.css">
</head>

<body>

    <a class="visually-hidden-focusable skip-link" href="#conteudo">
        Ir para o conteúdo
    </a>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top app-navbar"
        data-bs-theme="dark"
        aria-label="Navegação principal">

        <div class="container">

            <!-- LOGO -->
            <a class="navbar-brand" href="<?php echo BASEURL; ?>index.php">
                <i class="fa-solid fa-house-chimney"></i>
                <span class="brand-logo">QuadriVerse</span>
            </a>

            <!-- BOTÃO MENU RESPONSIVO -->
            <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarCrud"
                aria-controls="navbarCrud"
                aria-expanded="false"
                aria-label="Alternar navegação">

                <span class="navbar-toggler-icon"></span>

            </button>

            <!-- CONTEÚDO DA NAVBAR -->
            <div class="collapse navbar-collapse" id="navbarCrud">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <!-- REVISTAS -->
                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <i class="fa-solid fa-book"></i>
                            Revistas

                        </a>

                        <ul class="dropdown-menu">

                            <li>
                                <a class="dropdown-item"
                                    href="<?php echo BASEURL; ?>revistas/add.php">

                                    <i class="fa-solid fa-plus"></i>
                                    Nova Revista

                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                    href="<?php echo BASEURL; ?>revistas">

                                    <i class="fa-solid fa-book"></i>
                                    Gerenciar Revistas

                                </a>
                            </li>

                        </ul>

                    </li>

                    <!-- CLIENTES -->
                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <i class="fa-solid fa-users"></i>
                            Clientes

                        </a>

                        <ul class="dropdown-menu">

                            <li>
                                <a class="dropdown-item"
                                    href="<?php echo BASEURL; ?>customers/add.php">

                                    <i class="fa-solid fa-user-plus"></i>
                                    Adicionar Cliente

                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                    href="<?php echo BASEURL; ?>customers/">

                                    <i class="fa-solid fa-users"></i>
                                    Gerenciar Clientes

                                </a>
                            </li>

                        </ul>

                    </li>

                    <!-- USUÁRIOS - SOMENTE ADMIN -->
                    <?php
                    if (isset($_SESSION['user']) && $_SESSION['user'] == 'admin') :
                    ?>

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle"
                                href="#"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <i class="fa-solid fa-user-lock"></i>
                                Usuários

                            </a>

                            <ul class="dropdown-menu">

                                <li>
                                    <a class="dropdown-item"
                                        href="<?php echo BASEURL; ?>usuarios/add.php">

                                        <i class="fa-solid fa-user-plus"></i>
                                        Adicionar Usuário

                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item"
                                        href="<?php echo BASEURL; ?>usuarios/">

                                        <i class="fa-solid fa-users"></i>
                                        Gerenciar Usuários

                                    </a>
                                </li>

                            </ul>

                        </li>

                    <?php endif; ?>

                </ul>

                <!-- LOGIN / LOGOUT -->
                <ul class="navbar-nav">

                    <?php if (isset($_SESSION['user'])) : ?>

                        <!-- USUÁRIO LOGADO -->
                        <li class="nav-item">

                            <a class="nav-link"
                                href="<?php echo BASEURL; ?>inc/logout.php">

                                <i class="fa-solid fa-right-from-bracket"></i>
                                Desconectar

                            </a>

                        </li>

                    <?php else : ?>

                        <!-- USUÁRIO NÃO LOGADO -->
                        <li class="nav-item">

                            <a class="nav-link"
                                href="<?php echo BASEURL; ?>inc/login.php">

                                <i class="fa-solid fa-users"></i>
                                Login

                            </a>

                        </li>

                    <?php endif; ?>

                </ul>

            </div>

        </div>

    </nav>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="container" id="conteudo">
