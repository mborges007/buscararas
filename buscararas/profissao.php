<?php
include 'db.php'; // Incluindo o arquivo de conexão

// Verificando se o parâmetro 'name' está presente na URL
if (isset($_GET['name'])) {
    $profissaoName = $_GET['name'];

    // Consultar informações da profissão no banco de dados
    $query = "SELECT * FROM profissoes WHERE nome_profissao = :nome_profissao";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nome_profissao', $profissaoName);
    $stmt->execute();

    $profissao = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Se a profissão não for encontrada
    if (!$profissao) {
        echo "Profissão não encontrada.";
        exit;
    }
} else {
    echo "Nome da profissão não especificado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($profissao['nome_profissao']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
    <div class="sidebar d-flex flex-column p-3">
        <h1 class="text-light text-left">BuscAraras</h1>
        <a class="btn btn-light no-border mb-2 tamanho" href="index.php">Início</a>
        <h3 class="text-danger text-left">Departamentos</h3>
        <!-- Aqui você pode adicionar o código dos dropdowns como no index.php -->
        <!-- ... -->
        <div class="mt-auto">
            <a class="btn btn-primary w-100 mb-2" href="login.html">Login</a>
            <a class="btn btn-secondary w-100" href="cadastro.php">Cadastro</a>
        </div>
    </div>

    <div class="content d-flex flex-column align-items-center flex-grow-1">
        <h1><?php echo htmlspecialchars($profissao['nome_profissao']); ?></h1>
        <p><strong>Descrição:</strong> <?php echo htmlspecialchars($profissao['descricao']); ?></p>
        <p><strong>Requisitos:</strong> <?php echo htmlspecialchars($profissao['requisitos']); ?></p>
        <p><strong>Contato:</strong> <?php echo htmlspecialchars($profissao['contato']); ?></p>

        <!-- Passo a Passo -->
        <div class="steps-container d-flex justify-content-around w-100 p-4">
            <div class="step text-center">
                <img src="img/4.passo1.svg" alt="Passo 1" class="step-image mb-2">
                <h4>PASSO 1</h4>
                <p>Use o nosso menu lateral para encontrar a área de atendimento que precisa</p>
            </div>
            <div class="step text-center">
                <img src="img/5.passo2.svg" alt="Passo 2" class="step-image mb-2">
                <h4>PASSO 2</h4>
                <p>Clique em um de nossos departamentos para selecionar a área desejada</p>
            </div>
            <div class="step text-center">
                <img src="img/6.passo3.svg" alt="Passo 3" class="step-image mb-2">
                <h4>PASSO 3</h4>
                <p>Entre em contato com o profissional que mais te agrade</p>
            </div>
        </div>
    </div>

    <!-- Link para o Bootstrap 5 JS e Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
