<?php
// Incluindo o arquivo de conexão com o banco de dados
include 'db.php';

// Verificando se o parâmetro 'id' está presente na URL
if (!isset($_GET['id'])) {
    echo "<p class='text-danger'>Erro: ID do profissional não foi fornecido.</p>";
    exit;
}

$profissionalId = $_GET['id'];

try {
    // Consultar os detalhes do profissional com base no ID
    $query = "SELECT * FROM profissional WHERE id_profissional = :id_profissional";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_profissional', $profissionalId, PDO::PARAM_INT);
    $stmt->execute();
    $profissional = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar se o profissional foi encontrado
    if (!$profissional) {
        echo "<p class='text-danger'>Nenhum profissional encontrado com o ID fornecido.</p>";
        exit;
    }

    // Verifica se o usuário já avaliou o profissional pelo IP
    $usuarioIp = $_SERVER['REMOTE_ADDR'];
    $avaliacaoQuery = "SELECT * FROM avaliacao WHERE fk_profissional_id_profissional = :id_profissional AND usuario_ip = :usuario_ip";
    $avaliacaoStmt = $conn->prepare($avaliacaoQuery);
    $avaliacaoStmt->bindParam(':id_profissional', $profissionalId, PDO::PARAM_INT);
    $avaliacaoStmt->bindParam(':usuario_ip', $usuarioIp, PDO::PARAM_STR);
    $avaliacaoStmt->execute();
    $avaliacaoExistente = $avaliacaoStmt->fetch(PDO::FETCH_ASSOC);

    // Consulta para calcular a média das notas
    $mediaQuery = "SELECT AVG(notas_avaliacao) as media FROM avaliacao WHERE fk_profissional_id_profissional = :id_profissional";
    $mediaStmt = $conn->prepare($mediaQuery);
    $mediaStmt->bindParam(':id_profissional', $profissionalId, PDO::PARAM_INT);
    $mediaStmt->execute();
    $mediaResultado = $mediaStmt->fetch(PDO::FETCH_ASSOC);
    $media = $mediaResultado['media'] ? round($mediaResultado['media'], 2) : 0; // Arredondando para duas casas decimais

} catch (PDOException $e) {
    echo "<p class='text-danger'>Ocorreu um erro ao buscar o profissional. Por favor, tente novamente mais tarde.</p>";
    exit;
}

// Mensagem de popup se o usuário já avaliou
if (isset($_GET['mensagem'])) {
    echo "<script>alert('" . htmlspecialchars($_GET['mensagem']) . "');</script>";
}

include 'includes/busca_profissionais.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuscAraras - <?php echo htmlspecialchars($profissional['nome_profissional']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v17.0" nonce="Zt9PfOj5"></script>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<div class="content d-flex flex-column align-items-center flex-grow-1">
   
    <!-- Card do Perfil -->
    <div class="card form-card w-150 p-8 perfil-container">
        <div class="row align-items-center-perfil mb-4">
            <div class="col-md-6 text-center">
                <img src="img/luka.jpg" alt="Foto do Profissional" class="img-fluid rounded-circle perfil-photo">
            </div>
            <div class="col-md-8 perfil-info">
                <h4 class="text-center"><?php echo htmlspecialchars($profissional['nome_profissional']); ?></h4>
                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($profissional['tel_profissional']); ?></p>
                <p><strong>Profissão:</strong>
                    <?php
                    // Consultar a profissão do profissional
                    try {
                        $profQuery = "SELECT nome_profissao FROM profissoes WHERE id_profissao = :id_profissao";
                        $profStmt = $conn->prepare($profQuery);
                        $profStmt->bindParam(':id_profissao', $profissional['fk_profissoes_id_profissao'], PDO::PARAM_INT);
                        $profStmt->execute();
                        $profissao = $profStmt->fetch(PDO::FETCH_ASSOC);
                        echo htmlspecialchars($profissao['nome_profissao']);
                    } catch (PDOException $e) {
                        echo "<p class='text-danger'>Erro ao buscar profissão</p>";
                    }
                    ?>
                </p>
                <h5>Descrição</h5>
                <p><?php echo htmlspecialchars($profissional['descricao_profissional']); ?></p>

                <h5>Média das Avaliações: <?php echo $media; ?></h5>

                <h5>Avalie este profissional</h5>
                <?php if ($avaliacaoExistente): ?>
                    <p class="text-warning">Você já avaliou este profissional com nota: <?php echo htmlspecialchars($avaliacaoExistente['notas_avaliacao']); ?></p>
                    <form method="POST" action="avaliar.php">
                        <input type="hidden" name="id_profissional" value="<?php echo htmlspecialchars($profissional['id_profissional']); ?>">
                        <input type="hidden" name="usuario_ip" value="<?php echo htmlspecialchars($usuarioIp); ?>">
                        <div class="mb-3">
                            <label for="nota" class="form-label">Nova Nota (0 a 10):</label>
                            <input type="number" class="form-control" id="nota" name="nota" min="0" max="10" required value="<?php echo htmlspecialchars($avaliacaoExistente['notas_avaliacao']); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Atualizar Avaliação</button>
                    </form>
                <?php else: ?>
                    <form method="POST" action="avaliar.php">
                        <input type="hidden" name="id_profissional" value="<?php echo htmlspecialchars($profissional['id_profissional']); ?>">
                        <input type="hidden" name="usuario_ip" value="<?php echo htmlspecialchars($usuarioIp); ?>">
                        <div class="mb-3">
                            <label for="nota" class="form-label">Nota (0 a 10):</label>
                            <input type="number" class="form-control" id="nota" name="nota" min="0" max="10" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Avaliar</button>
                    </form>
                    
                <?php endif; ?>
                <div class="fb-comments" 
                        data-href="https://www.seusite.com/perfil_profissional.php?id=<?php echo $profissionalId; ?>" 
                        data-width="100%" 
                        data-numposts="5">
                    </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
