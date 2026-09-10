<?php
session_start();

require_once "conexaobd.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $emailInformado = $_POST['email'] ?? '';
    $senhaInformada = $_POST['senha'] ?? '';

    $senhaHash = md5($senhaInformada);

    try {
        $conexaoClasse = new Conexao();
        $pdo = $conexaoClasse->conectar();

        // Busca o usuário e a descrição da categoria vinculada
        $sql = "SELECT u.cod_usuario, u.nome, u.email, c.descricao AS perfil 
                FROM usuarios u
                LEFT JOIN cat_usuarios c ON u.cod_cat = c.cod_cat
                WHERE u.email = :email
                  AND u.senha = :senha 
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":email", $emailInformado);
        $stmt->bindParam(":senha", $senhaHash);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Grava nas variáveis exatamente como o front-end espera
            $_SESSION['logado']         = true;
            $_SESSION['usuario_id']     = $usuario['cod_usuario'];
            $_SESSION['usuario_nome']   = $usuario['nome']; // 'nome' em minúsculo igual ao BD
            $_SESSION['usuario_perfil'] = $usuario['perfil'];
            $_SESSION['email']          = $usuario['email'];

            header("Location: livros.php");
            exit;

        } else {
            header("Location: login.php?erro=" . urlencode("Usuário ou senha inválidos!"));
            exit;
        }

    } catch (PDOException $e) {
        die("Erro no processamento do login: " . $e->getMessage());
    }

} else {
    header("Location: login.php");
    exit;
}
?>