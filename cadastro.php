<?php
    require_once 'conexaobd.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livraria - Criar Conta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-bg">

    <div class="auth-card wide">
        <div class="auth-header">
            <h1>Criar Nova Conta</h1>
            <p>Cadastre-se para gerenciar seus empréstimos e leituras</p>
        </div>

        <?php if(isset($_GET['erro'])): ?>
            <div style="background-color: #ffdddd; color: #a94442; padding: 12px; border: 1px solid #ebccd1; border-radius: 4px; margin-bottom: 20px; font-size: 14px;">
                <strong>Erro:</strong> <?php echo htmlspecialchars($_GET['erro']); ?>
            </div>
        <?php endif; ?>
        
        <form action="processacadastro.php" method="POST">

            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" required placeholder="Ex: João Silva">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required placeholder="joao@email.com">
                </div>
          
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required placeholder="Crie uma senha">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    
                    <label for="categoria_nome">Categoria de Assinante</label>
                    <input type="text" id="categoria_nome" name="categoria_nome" required placeholder="Ex: Leitor Casual, Premium, Acadêmico">
                </div>

                <div class="form-group">
                    <div class="city-state-group">
                        <div style="flex: 3;">
                            <label for="cidades_nome">Cidade onde mora</label>
                            <input type="text" id="cidades_nome" name="cidades_nome" required placeholder="Ex: Santa Rita do Sapucaí">
                        </div>
                        <div style="flex: 1;">
                            <label for="cidades_uf">UF</label>
                            <input type="text" id="cidades_uf" name="cidades_uf" required maxlength="2" placeholder="MG" style="text-transform: uppercase; text-align: center;">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-book-primary">Finalizar Meu Cadastro</button>
        </form>

        <div class="auth-footer">
            Já possui cadastro? <a href="index.html">Voltar para o Login</a>
        </div>
    </div>

</body>
</html>
