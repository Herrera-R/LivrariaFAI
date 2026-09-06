<?php
session_start();

// Importa a classe de conexão já disponibilizada no projeto
require_once "conexaobd.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $emailInformado = $_POST['email'] ?? '';
    $senhaInformada   = $_POST['senha']   ?? '';

    // Aplica o hash MD5 na senha digitada para comparar com o padrão do banco
    $senhaHash = md5($senhaInformada);

    try {
        $conexaoClasse = new Conexao();
        $pdo = $conexaoClasse->conectar();

        $sql = "SELECT * FROM usuarios
                WHERE email = :email
                  AND senha   = :senha LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":email", $emailInformado);
        $stmt->bindParam(":senha", $senhaHash);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // As chaves seguem exatamente os nomes das colunas da tabela
            $_SESSION['email']   = $usuario['email'];
            $_SESSION['nome_usuario'] = $usuario['Nome'];
            $_SESSION['logado']       = true;

            // Redireciona para a página restrita do sistema
            header("Location: livros.php");
            exit;

        } else {
            // Falha na autenticação
            $_SESSION['erro'] = "Usuário ou senha inválidos!";
            header("Location: index.html");
            exit;
        }

    } catch (PDOException $e) {
        die("Erro no processamento do login: " . $e->getMessage());
    }

} else {
    header("Location: index.html");
    exit;
}
?>