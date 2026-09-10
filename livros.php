<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit;
}

require_once "conexaobd.php";

try {
    $conexaoClasse = new Conexao();
    $pdo = $conexaoClasse->conectar();

    $sql = "SELECT l.*, e.nome AS nome_editora,
            (SELECT GROUP_CONCAT(a.nome SEPARATOR ', ') FROM LA la JOIN autores a ON la.cod_autor = a.cod_autor WHERE la.cod_livro = l.cod_livro) AS nomes_autores,
            (SELECT GROUP_CONCAT(i.nome SEPARATOR ', ') FROM LI li JOIN idiomas i ON li.cod_idioma = i.cod_idioma WHERE li.cod_livro = l.cod_livro) AS nomes_idiomas
            FROM livros l
            LEFT JOIN editoras e ON l.cod_editora = e.cod_editora
            ORDER BY l.cod_livro DESC";

    $stmt = $pdo->query($sql);
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao carregar o catálogo de livros: " . $e->getMessage());
}
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
                <strong><?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?></strong>
                <span style="color: var(--gold-accent); font-size: 12px;"><?php echo htmlspecialchars($_SESSION['usuario_perfil'] ?? ''); ?></span>
            </div>
            <a href="logout.php" class="btn-logout">Sair</a>
        </div>
    </nav>

    <main class="main-container">
        <div class="section-header">
            <h2>Catálogo do Acervo</h2>
            <a href="cadastrar_livro.php" class="btn-book-accent">+ Novo Livro</a>
        </div>

        <div class="books-grid">
            <?php if (empty($livros)): ?>
                <p style="grid-column: 1/-1; text-align: center; color: var(--text-light); font-style: italic; margin-top: 40px;">
                    Nenhum livro cadastrado no acervo até o momento.
                </p>
            <?php else: ?>
                <?php foreach ($livros as $livro): ?>
                    <div class="book-card">
                        <div>
    
                            <span class="status-badge <?php echo $livro['esta_emprestado'] ? 'borrowed' : 'available'; ?>">
                                <?php echo $livro['esta_emprestado'] ? 'Emprestado' : 'Disponível'; ?>
                            </span>
                            
                            <div class="book-title"><?php echo htmlspecialchars($livro['titulo']); ?></div>
                            <div class="book-author">Por: <?php echo htmlspecialchars($livro['nomes_autores'] ?? 'Autor não especificado'); ?></div>
                            
                            <div class="catalog-details">
                                <div><strong>Editora:</strong> <?php echo htmlspecialchars($livro['nome_editora'] ?? 'Não informada'); ?></div>
                                <div><strong>Edição:</strong> <?php echo htmlspecialchars($livro['edicao']); ?>ª Ed.</div>
                                <div><strong>Ano:</strong> <?php echo htmlspecialchars($livro['ano']); ?></div>
                                <div><strong>Páginas:</strong> <?php echo htmlspecialchars($livro['paginas']); ?> págs.</div>
                                <div><strong>Idioma:</strong> <?php echo htmlspecialchars($livro['nomes_idiomas'] ?? 'Não informado'); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
