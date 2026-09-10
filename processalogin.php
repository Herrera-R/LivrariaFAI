<?php
session_start();

require_once "conexaobd.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($email) || empty($senha)) {
        header("Location: index.html?erro=" . urlencode("Preencha todos os campos."));
        exit;
    }

    $senhaHash = md5($senha);

    try {
        $conexaoClasse = new Conexao();
        $pdo = $conexaoClasse->conectar();

        // Busca o usuário e a descrição da sua categoria no banco via PDO
        $sql = "SELECT u.cod_usuario, u.nome, u.email, c.descricao AS perfil 
                FROM usuarios u 
                LEFT JOIN cat_usuarios c ON u.cod_cat = c.cod_cat 
                WHERE u.email = :email 
                  AND u.senha = :senha 
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha", $senhaHash);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION['logado']         = true;
            $_SESSION['usuario_id']     = $user['cod_usuario'];
            $_SESSION['usuario_nome']   = $user['nome'];
            $_SESSION['usuario_perfil'] = $user['perfil'];
            $_SESSION['email']          = $user['email'];

            header("Location: livros.php");
            exit;
        }

        header("Location: index.html?erro=" . urlencode("E-mail ou senha incorretos."));
        exit;

    } catch (PDOException $e) {
        die("Erro no processamento do login: " . $e->getMessage());
    }
} else {
    header("Location: index.html");
    exit;
}
