<?php
// Incluindo o arquivo de conexão com o banco de dados
include 'db.php';

// Verificando se o parâmetro 'profissao' está presente na URL
if (!isset($_GET['profissao'])) {
    echo "<p>Por favor, selecione uma profissão para ver os profissionais.</p>";
    exit;
}

$profissaoName = $_GET['profissao'];

try {
    // Consultar os profissionais relacionados à profissão selecionada
    $query = "
        SELECT p.*, 
        (SELECT AVG(a.notas_avaliacao) FROM avaliacao a WHERE a.fk_profissional_id_profissional = p.id_profissional) AS media_avaliacao 
        FROM profissional p
        WHERE p.fk_profissoes_id_profissao = 
              (SELECT id_profissao FROM profissoes WHERE nome_profissao = :nome_profissao)";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nome_profissao', $profissaoName);
    $stmt->execute();
    $profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<p>Ocorreu um erro ao buscar os profissionais: " . $e->getMessage() . "</p>";
    exit;
}

include 'includes/busca_profissionais.php';


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profissionais - <?php echo htmlspecialchars($profissaoName); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="content d-flex flex-column align-items-center flex-grow-1">
        <div class="container mt-5 ">
            <h1 style="text-align: center;"><?php echo htmlspecialchars($profissaoName); ?></h1>
            
            <!-- Verifica se há profissionais cadastrados -->
            <?php if (count($profissionais) > 0): ?>
    <?php foreach ($profissionais as $profissional): ?>
        <div class="w-100 mb-4">
            <div class="card form-card w-500 p-2 profile-container">
                <!-- Tornar a row clicável e redirecionar para a página de perfil -->
                <div class="row align-items-center-perfil mb-4" style="cursor: pointer;" 
                     onclick="window.location.href='perfilunico.php?id=<?php echo $profissional['id_profissional']; ?>';">
                    <!-- Foto do Profissional -->
                    <div class="col-md-4 text-center">
                        <img src="img/chiquinha.jpg" alt="Foto do Profissional" class="img-fluid rounded-circle profile-photo">
                    </div>
                    <!-- Informações do Profissional -->
                    <div class="col-md-8 profile-info">
                        <h4 class="text-center"><?php echo htmlspecialchars($profissional['nome_profissional']); ?></h4>
                        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($profissional['tel_profissional']); ?></p>
                        <p><strong>Profissão:</strong> <?php echo htmlspecialchars($profissaoName); ?></p>
                        <!-- Descrição Profissional -->
                        <h5>Descrição</h5>
                        <p><?php echo htmlspecialchars($profissional['descricao_profissional']); ?></p>
                        
                        <!-- Ranking de Avaliação -->
                        <h5>Avaliação</h5>
                        <p>
                            <?php
                            // Exibir a média de avaliações, ou "Sem avaliações" se não houver média
                            $media_avaliacao = isset($profissional['media_avaliacao']) ? $profissional['media_avaliacao'] : null;
                            if ($media_avaliacao !== null) {
                                echo htmlspecialchars(number_format($media_avaliacao, 1)) . " de média";
                            } else {
                                echo "Profissional não avaliado";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php else: ?>
        <p>Ainda não temos nenhum profissional cadastrado neste departamento.</p>
    <?php endif; ?>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
