<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: index.html");
    exit;
}

require_once "conexaobd.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo   = trim($_POST['titulo'] ?? '');
    $paginas  = (int)($_POST['paginas'] ?? 0);
    $ano      = (int)($_POST['ano'] ?? 0);
    $edicao   = (int)($_POST['edicao'] ?? 0);
    $editora  = trim($_POST['editora'] ?? '');
    $autores  = trim($_POST['autores'] ?? '');
    $idiomas  = trim($_POST['idiomas'] ?? '');

    if (empty($titulo) || empty($paginas) || empty($ano) || empty($edicao) || empty($editora) || empty($autores) || empty($idiomas)) {
        header("Location: cadastrar_livro.php?erro=" . urlencode("Todos os campos são obrigatórios."));
        exit;
    }

    try {
        $conexaoClasse = new Conexao();
        $pdo = $conexaoClasse->conectar();
        
        $pdo->beginTransaction();

        $sqlEditora = "SELECT cod_editora FROM editoras WHERE LOWER(nome) = LOWER(:nome) LIMIT 1";
        $stmtEd = $pdo->prepare($sqlEditora);
        $stmtEd->execute([':nome' => $editora]);
        $idEditora = $stmtEd->fetchColumn();

        if (!$idEditora) {
            $sqlInsEd = "INSERT INTO editoras (nome) VALUES (:nome)";
            $stmtInsEd = $pdo->prepare($sqlInsEd);
            $stmtInsEd->execute([':nome' => $editora]);
            $idEditora = $pdo->lastInsertId();
        }

        $sqlLivro = "INSERT INTO livros (titulo, paginas, ano, edicao, cod_editora, esta_emprestado) 
                     VALUES (:titulo, :paginas, :ano, :edicao, :cod_editora, 0)";
        $stmtLivro = $pdo->prepare($sqlLivro);
        $stmtLivro->execute([
            ':titulo'      => $titulo,
            ':paginas'     => $paginas,
            ':ano'         => $ano,
            ':edicao'      => $edicao,
            ':cod_editora' => $idEditora
        ]);
        $idLivro = $pdo->lastInsertId();

        $listaAutores = array_map('trim', explode(',', $autores));
        foreach ($listaAutores as $nomeAutor) {
            if (empty($nomeAutor)) continue;

            $sqlAut = "SELECT cod_autor FROM autores WHERE LOWER(nome) = LOWER(:nome) LIMIT 1";
            $stmtAut = $pdo->prepare($sqlAut);
            $stmtAut->execute([':nome' => $nomeAutor]);
            $idAutor = $stmtAut->fetchColumn();

            if (!$idAutor) {
                $sqlInsAut = "INSERT INTO autores (nome) VALUES (:nome)";
                $stmtInsAut = $pdo->prepare($sqlInsAut);
                $stmtInsAut->execute([':nome' => $nomeAutor]);
                $idAutor = $pdo->lastInsertId();
            }

            $sqlLA = "INSERT INTO LA (cod_livro, cod_autor) VALUES (:cod_livro, :cod_autor)";
            $pdo->prepare($sqlLA)->execute([':cod_livro' => $idLivro, ':cod_autor' => $idAutor]);
        }

        $listaIdiomas = array_map('trim', explode(',', $idiomas));
        foreach ($listaIdiomas as $nomeIdioma) {
            if (empty($nomeIdioma)) continue;

            $sqlIdi = "SELECT cod_idioma FROM idiomas WHERE LOWER(nome) = LOWER(:nome) LIMIT 1";
            $stmtIdi = $pdo->prepare($sqlIdi);
            $stmtIdi->execute([':nome' => $nomeIdioma]);
            $idIdioma = $stmtIdi->fetchColumn();

            if (!$idIdioma) {
                $sqlInsIdi = "INSERT INTO idiomas (nome) VALUES (:nome)";
                $stmtInsIdi = $pdo->prepare($sqlInsIdi);
                $stmtInsIdi->execute([':nome' => $nomeIdioma]);
                $idIdioma = $pdo->lastInsertId();
            }

            $sqlLI = "INSERT INTO LI (cod_livro, cod_idioma) VALUES (:cod_livro, :cod_idioma)";
            $pdo->prepare($sqlLI)->execute([':cod_livro' => $idLivro, ':cod_idioma' => $idIdioma]);
        }

        $pdo->commit();
        
        header("Location: livros.php");
        exit;

    } catch (PDOException $e) {
        if (isset($pdo)) $pdo->rollBack();
        die("Erro crítico ao salvar o livro no banco de dados: " . $e->getMessage());
    }
} else {
    header("Location: cadastrar_livro.php");
    exit;
}
?>
