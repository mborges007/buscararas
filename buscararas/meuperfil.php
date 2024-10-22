<?php
session_start();
include 'db.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user'])) {
    header("Location: login.html"); // Redireciona para a página de login se não estiver autenticado
    exit;
}

// Obtendo o ID do profissional da sessão
$profissional_id = $_SESSION['user']; // 'user' armazena o ID do profissional

try {
    // Consulta SQL para obter as informações do profissional
    $sql = "SELECT p.nome_profissional, p.tel_profissional, p.descricao_profissional, p.email_profissional,
                   pr.nome_profissao,
                   d.nome_area
            FROM profissional p
            JOIN profissoes pr ON pr.id_profissao = p.fk_profissoes_id_profissao
            JOIN departamentos d ON d.id_area = p.fk_departamentos_id_area  -- Correção aqui
            WHERE p.id_profissional = :id_profissional";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_profissional', $profissional_id, PDO::PARAM_INT);
    $stmt->execute();

    // Verifica se o profissional foi encontrado
    if ($stmt->rowCount() > 0) {
        $profissional = $stmt->fetch(PDO::FETCH_ASSOC); // Pega o resultado como array associativo
    } else {
        echo "Profissional não encontrado!";
        exit;
    }
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuscAraras - Meu Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
    <div class="sidebar d-flex flex-column p-3">
        <h1 class="text-light text-left">BuscAraras</h1>
        <a class="btn btn-light no-border mb-2 tamanho" href="index.php">Início</a>
        <h3 class="text-danger text-left">Meu Perfil</h3>
        <div class="mt-auto">
        <a class="btn btn-primary w-100 mb-2" href="editar_perfil.php">Alterar</a>
            <a class="btn btn-secondary w-100" href="logout.php">Sair</a>
        </div>
    </div>
          
    <div class="content d-flex flex-column align-items-center flex-grow-1">
        <h1>Meu Perfil</h1>
        <div class="card form-card w-150 p-8 perfil-container">
            <div class="row align-items-center-perfil mb-4">
                <div class="col-md-6 text-center">
                    <img src="img/luka.jpg" alt="Foto do Profissional" class="img-fluid rounded-circle perfil-photo">
                </div>
                <div class="col-md-8 perfil-info">
                    <h4 class="text-center"><?php echo htmlspecialchars($profissional['nome_profissional']); ?></h4>
                    <p><strong>Telefone:</strong> <?php echo htmlspecialchars($profissional['tel_profissional']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($profissional['email_profissional']); ?></p>
                    <p><strong>Profissão:</strong> <?php echo htmlspecialchars($profissional['nome_profissao']); ?></p>
                    <p><strong>Departamento:</strong> <?php echo htmlspecialchars($profissional['nome_area']); ?></p> <!-- Correção aqui -->
                    <h5>Descrição</h5>
                    <p><?php echo htmlspecialchars($profissional['descricao_profissional']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
