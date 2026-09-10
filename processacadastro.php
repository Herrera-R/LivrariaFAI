<?php
session_start();

require_once "conexaobd.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nomeInformado      = trim($_POST['nome'] ?? '');
    $emailInformado     = trim($_POST['email'] ?? '');
    $senhaInformada     = $_POST['senha'] ?? '';
    $categoriaNome      = trim($_POST['categoria_nome'] ?? '');
    $cidadeNome         = trim($_POST['cidades_nome'] ?? '');
    $cidadeUf           = strtoupper(trim($_POST['cidades_uf'] ?? ''));

    if (empty($nomeInformado) || empty($emailInformado) || empty($senhaInformada) || empty($categoriaNome) || empty($cidadeNome) || empty($cidadeUf)) {
        header("Location: cadastro.php?erro=" . urlencode("Todos os campos são obrigatórios."));
        exit;
    }

    if (strlen($senhaInformada) < 6) {
        header("Location: cadastro.php?erro=" . urlencode("A senha deve ter no mínimo 6 caracteres!"));
        exit; 
    }

    $senhaHash = md5($senhaInformada);

    try {
        $conexaoClasse = new Conexao();
        $pdo = $conexaoClasse->conectar();

        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $stmtc = $pdo->prepare($sql);
        $stmtc->bindParam(":email", $emailInformado);
        $stmtc->execute();
        $total = $stmtc->fetchColumn();

        if ($total >= 1) {
            header("Location: cadastro.php?erro=" . urlencode("O e-mail já está sendo utilizado!"));
            exit;
        }

        $sqlCat = "SELECT cod_cat FROM cat_usuarios WHERE LOWER(descricao) = LOWER(:descricao) LIMIT 1";
        $stmtCat = $pdo->prepare($sqlCat);
        $stmtCat->execute([':descricao' => $categoriaNome]);
        $codCat = $stmtCat->fetchColumn();

        if (!$codCat) {
            $sqlInsCat = "INSERT INTO cat_usuarios (descricao, n_dias, n_exemplares) VALUES (:descricao, 14, 3)";
            $stmtInsCat = $pdo->prepare($sqlInsCat);
            $stmtInsCat->execute([':descricao' => $categoriaNome]);
            $codCat = $pdo->lastInsertId();
        }

        $sqlCidade = "SELECT cod_cidade FROM cidades WHERE LOWER(nome) = LOWER(:nome) AND UPPER(uf) = :uf LIMIT 1";
        $stmtCid = $pdo->prepare($sqlCidade);
        $stmtCid->execute([':nome' => $cidadeNome, ':uf' => $cidadeUf]);
        $codCidade = $stmtCid->fetchColumn();

        if (!$codCidade) {
            $sqlInsCid = "INSERT INTO cidades (nome, uf) VALUES (:nome, :uf)";
            $stmtInsCid = $pdo->prepare($sqlInsCid);
            $stmtInsCid->execute([':nome' => $cidadeNome, ':uf' => $cidadeUf]);
            $codCidade = $pdo->lastInsertId();
        }

     
        $sqlc = "INSERT INTO usuarios (nome, email, senha, cod_cat, cod_cidade) 
                 VALUES (:nome, :email, :senha, :cod_cat, :cod_cidade)";

        $stmt = $pdo->prepare($sqlc);
        $stmt->bindParam(":nome", $nomeInformado);
        $stmt->bindParam(":email", $emailInformado);
        $stmt->bindParam(":senha", $senhaHash);
        $stmt->bindParam(":cod_cat", $codCat);
        $stmt->bindParam(":cod_cidade", $codCidade);
        $stmt->execute();

        $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
        header("Location: index.html?sucesso=" . urlencode("Cadastro realizado com sucesso! Faça seu login."));
        exit;

    } catch (PDOException $e) {
        die("Erro no processamento do cadastro: " . $e->getMessage());
    }

} else {
    header("Location: index.html");
    exit;
}
?>
