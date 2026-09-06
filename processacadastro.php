<?php

// Importa a classe de conexão já disponibilizada no projeto
require_once "conexaobd.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $emailInformado = $_POST['email'] ?? '';
    $senhaInformada   = $_POST['senha']   ?? '';
    $nomeInformado = $_POST['nome'] ?? '';

    // Aplica o hash MD5 na senha digitada para comparar com o padrão do banco
    $senhaHash = md5($senhaInformada);

    try {
        $conexaoClasse = new Conexao();
        $pdo = $conexaoClasse->conectar();

        $sql = "SELECT COUNT(*) FROM usuarios
                WHERE email = :email";

        $stmtc = $pdo->prepare($sql);
        $stmtc->bindParam(":email", $emailInformado);
        $stmtc->execute();
        $total = $stmtc->fetchColumn();

        if($total >= 1)
        {
            $_SESSION['erro'] = "O email já está sendo utilizado!";
            header("Location: cadastro.html");
            exit;
        }

        if(strlen($senhaInformada) < 6)
        {
            $_SESSION['erro'] = "A senha deve ter no mínimo 6 caracteres!";
            header("Location: cadastro.html");
            exit; 
        }


        $sqlc = "INSERT INTO usuarios (email, senha, Nome) values (:email, :senha, :Nome)";

        $stmt = $pdo->prepare($sqlc);
        $stmt->bindParam(":email", $emailInformado);
        $stmt->bindParam(":senha", $senhaHash);
        $stmt->bindParam(":Nome", $nomeInformado);
        $stmt->execute();

        $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
        header("Location: index.html");
        exit;

    } catch (PDOException $e) {
        die("Erro no processamento do login: " . $e->getMessage());
    }

} else {
    header("Location: index.html");
    exit;
}
?>