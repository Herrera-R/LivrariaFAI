<?php
session_start();
require_once 'conexaobd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($email) || empty($senha)) {
        header("Location: login.php?erro=" . urlencode("Preencha todos os campos."));
        exit;
    }

    // Busca o usuário e a descrição da sua categoria no banco
    $sql = "SELECT u.cod_usuario, u.nome, u.senha, c.descricao AS perfil 
            FROM usuarios u 
            LEFT JOIN cat_usuarios c ON u.cod_cat = c.cod_cat 
            WHERE u.email = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Se usar password_hash no cadastro, use: password_verify($senha, $user['senha'])
        if ($senha === $user['senha']) { 
            $_SESSION['logado'] = true;
            $_SESSION['usuario_id'] = $user['cod_usuario'];
            $_SESSION['usuario_nome'] = $user['nome'];
            $_SESSION['usuario_perfil'] = $user['perfil'];

            header("Location: livros.php");
            exit;
        }
    }

    header("Location: login.php?erro=" . urlencode("E-mail ou senha incorretos."));
    exit;
} else {
    header("Location: login.php");
    exit;
}