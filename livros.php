<?php

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livraria - Catalogo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <a href="#" class="brand">Livraria</a>
        <div class="nav-user">
            <div class="user-info">
        
                <strong></strong>
                
                <span style="color: var(--gold-accent); font-size: 12px;"></span>
            </div>
            <a href="index.html" class="btn-logout">Sair</a>
        </div>
    </nav>

    <main class="main-container">
        <div class="section-header">
            <h2>Catálogo do Acervo</h2>
            <a href="cadastrar_livro.php" class="btn-book-accent">+ Novo Livro</a>
        </div>

        <div class="books-grid">


        </div>
    </main>

</body>
</html>
