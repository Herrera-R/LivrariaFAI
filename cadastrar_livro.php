<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livraria - Cadastrar Livro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <a href="livros.php" class="brand">Livraria</a>
        <a href="livros.php" class="btn-logout" style="color: white; border-color: white;">Voltar ao Catálogo</a>
    </nav>

    <main class="main-container">
        <a href="livros.php" class="btn-back">← Voltar para a lista de livros</a>
        
        <div class="form-container">
            <div class="auth-header" style="text-align: left; margin-bottom: 25px;">
                <h1 style="font-size: 28px;">Cadastrar Novo Livro</h1>
                <p>Insira os dados técnicos do exemplar para adicioná-lo ao acervo</p>
            </div>
            
            <?php if(isset($_GET['erro'])): ?>
                <div style="background-color: #ffdddd; color: #a94442; padding: 12px; border: 1px solid #ebccd1; border-radius: 4px; margin-bottom: 20px; font-size: 14px;">
                    <strong>Erro:</strong> <?php echo htmlspecialchars($_GET['erro']); ?>
                </div>
            <?php endif; ?>
         
          
            <form action="processar_livro.php" method="POST">
                
                <div class="form-group">
                    <label for="titulo">Título do Livro</label>
                    <input type="text" id="titulo" name="titulo" required placeholder="Ex: Fundamentos de Bancos de Dados">
                </div>

                <div class="form-row">
              
                    <div class="form-group">
                        <label for="paginas">Número de Páginas</label>
                        <input type="number" id="paginas" name="paginas" min="1" required placeholder="Ex: 350">
                    </div>
               
                    <div class="form-group">
                        <label for="ano">Ano de Publicação</label>
                        <input type="number" id="ano" name="ano" min="1" required placeholder="Ex: 2024">
                    </div>
                </div>

                <div class="form-row">
                  
                    <div class="form-group">
                        <label for="edicao">Edição</label>
                        <input type="number" id="edicao" name="edicao" min="1" required placeholder="Ex: 2">
                    </div>
               
                    <div class="form-group">
                        <label for="nome_editora">Editora</label>
                        <input type="text" id="nome_editora" name="editora" required placeholder="Ex: Bookman, Novatec, Alta Books">
                    </div>
                </div>

                <div class="form-group">
                    <label for="nome_autor">Autor(es)</label>
                    <div class="form-text">Caso o livro tenha mais de um autor responsável, separe os nomes por vírgula.</div>
                    <input type="text" id="nome_autor" name="autores" required placeholder="Ex: Machado de Assis, Celso Almeirim" style="margin-top: 5px;">
                </div>

                <div class="form-group">
                    <label for="nome_idioma">Idioma(s)</label>
                    <div class="form-text">Caso o livro possua mais de um idioma disponível, separe os nomes por vírgula.</div>
                    <input type="text" id="nome_idioma" name="idiomas" required placeholder="Ex: Português, Inglês" style="margin-top: 5px;">
                </div>

                <button type="submit" class="btn-book-primary" style="margin-top: 10px;">Disponibilizar no Acervo</button>
            </form>
        </div>
    </main>

</body>
</html>
