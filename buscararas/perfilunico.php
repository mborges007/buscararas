<?php
// Incluindo o arquivo de conexão com o banco de dados
include 'db.php';

// Validar o ID do profissional fornecido
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo $errorMessages['invalid_id'];
    exit;
}

$profissionalId = intval($_GET['id']); // Convertendo para inteiro

// Iniciar sessão
session_start();

// Verificar se o usuário está logado
$usuarioLogado = isset($_SESSION['id_usuario']);
$id_usuario = $usuarioLogado ? $_SESSION['id_usuario'] : null;

try {
    // Iniciar transação para garantir consistência dos dados
    $conn->beginTransaction();

    // Buscar os detalhes do profissional com base no ID
    if (!$profissionalId) {
        echo $errorMessages['no_professional'];
        $conn->rollBack(); // Reverter transações em caso de ID inválido
        exit;
    }

    // Buscar as fotos associadas ao profissional
    $sqlFotos = "
        SELECT caminho_foto 
        FROM fotos_profissionais 
        WHERE fk_profissional_id_profissional = :id_profissional";
    $stmtFotos = $conn->prepare($sqlFotos);
    $stmtFotos->bindParam(':id_profissional', $profissionalId, PDO::PARAM_INT);
    $stmtFotos->execute();
    $fotosProfissional = $stmtFotos->fetchAll(PDO::FETCH_ASSOC);

    // Se o usuário estiver logado, buscar a avaliação do profissional feita por ele
    $avaliacaoEstrelas = 0;
    $avaliacaoExistente = false;
    if ($usuarioLogado) {
        $sqlAvaliacao = "
            SELECT estrelas_avaliacao 
            FROM avaliacao 
            WHERE fk_profissional_id_profissional = :id_profissional 
              AND fk_usuario_id_usuario = :id_usuario";
        $stmtAvaliacao = $conn->prepare($sqlAvaliacao);
        $stmtAvaliacao->bindParam(':id_profissional', $profissionalId, PDO::PARAM_INT);
        $stmtAvaliacao->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmtAvaliacao->execute();
        $avaliacaoEstrelas = $stmtAvaliacao->fetchColumn();
        $avaliacaoExistente = $avaliacaoEstrelas !== false; // Verifica se uma avaliação já existe
        $avaliacaoEstrelas = $avaliacaoExistente ? $avaliacaoEstrelas : 0; // Define valor padrão 0 se nenhuma avaliação encontrada
    }

    

    // Commit da transação após todas as consultas serem realizadas
    $conn->commit();
} catch (PDOException $e) {
    // Registrar erro para análise (em ambiente de desenvolvimento)
    error_log("Erro no banco de dados: " . $e->getMessage());
    echo $errorMessages['generic_error'];
    $conn->rollBack(); // Reverter transações em caso de falha
    exit;
}

$sqlProfissional = "SELECT * FROM profissional WHERE id_profissional = :id_profissional";
$stmtProfissional = $conn->prepare($sqlProfissional);
$stmtProfissional->bindParam(':id_profissional', $profissionalId, PDO::PARAM_INT);
$stmtProfissional->execute();
$profissional = $stmtProfissional->fetch(PDO::FETCH_ASSOC);

if (!$profissional) {
    echo $errorMessages['no_professional'];
    exit;
}


?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuscAraras - <?php echo htmlspecialchars($profissional['nome_profissional']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v17.0" nonce="Zt9PfOj5"></script>
    <style>
        body {
            background-image: url('img/lupa.svg');
            background-repeat: repeat;
            background-size: 30px 30px;
        }
    </style>
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
                
                <p>
                    <strong>Telefone:</strong>
                    <a href="https://wa.me/55<?php echo htmlspecialchars($profissional['tel_profissional']); ?>" target="_blank">
                        <i class="fab fa-whatsapp text-success"></i> <?php echo htmlspecialchars($profissional['tel_profissional']); ?>
                    </a>
                </p>

                <p>
                    <strong>Profissão:</strong>
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
            </div>

            <h5>Avaliação</h5>
<p class="text">
    <span id="avaliacao">
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <i class="fas fa-star <?php echo ($i <= $avaliacaoEstrelas) ? 'filled' : ''; ?>" 
            data-value="<?php echo $i; ?>" 
            onclick="setRating(<?php echo $i; ?>)"></i>
        <?php endfor; ?>
    </span>
</p>
<form id="ratingForm" action="avaliar.php" method="POST" class="text">
    <input type="hidden" name="estrelas_avaliacao" id="estrelas_avaliacao" value="<?php echo $avaliacaoEstrelas; ?>">
    <input type="hidden" name="id_profissional" value="<?php echo $profissionalId; ?>">
    <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
    <button type="submit" class="btn btn-primary saltando">
        <?php echo ($avaliacaoEstrelas > 0) ? 'Atualizar Avaliação' : 'Enviar Avaliação'; ?>
    </button>
</form>

            <br>
            <p><strong>Trabalhos feitos</strong></p>
            <?php if (!empty($fotosProfissional)): ?> <!-- Verifica se há fotos -->
                <div class="fotos-profissionais">
                    <div id="fotoCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" style="text-align: center;">
                            <?php $first = true; ?>
                            <?php foreach ($fotosProfissional as $foto): ?>
                                <div class="carousel-item <?php echo $first ? 'active' : ''; ?>">
                                    <img src="<?php echo htmlspecialchars($foto['caminho_foto']); ?>" alt="Foto do profissional" class="img-fluid rounded mb-2" data-bs-toggle="modal" data-bs-target="#fotoModal" data-foto="<?php echo htmlspecialchars($foto['caminho_foto']); ?>">
                                </div>
                                <?php $first = false; ?>
                            <?php endforeach; ?>
                        </div>

                        <!-- Indicadores (Pontos) -->
                        <div class="carousel-indicators">
                            <?php $first = true; ?>
                            <?php foreach ($fotosProfissional as $index => $foto): ?>
                                <button type="button" data-bs-target="#fotoCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $first ? 'active' : ''; ?>" aria-current="true" aria-label="Slide <?php echo $index + 1; ?>"></button>
                                <?php $first = false; ?>
                            <?php endforeach; ?>
                        </div>

                        <!-- Controles de Navegação (Setas) -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#fotoCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#fotoCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                       <!-- Miniaturas abaixo do carrossel -->
                       <div id="fotoThumbnails" class="d-flex justify-content-center mt-3">
                            <?php foreach ($fotosProfissional as $index => $foto): ?>
                                <div class="thumbnail-item">
                                    <img src="<?php echo htmlspecialchars($foto['caminho_foto']); ?>" class="img-thumbnail" data-bs-target="#fotoCarousel" data-bs-slide-to="<?php echo $index; ?>" alt="Miniatura da foto" style="cursor: pointer; width: 100px; height: 100px; object-fit: cover;">
                                </div>
                            <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p>Este profissional ainda não possui fotos cadastradas.</p> <!-- Mensagem caso não haja fotos -->
                            <?php endif; ?>

        </div>
            <div class="fb-comments" data-href="https://www.seusite.com/perfil_profissional.php?id=<?php echo $profissionalId; ?>" data-width="100%"data-numposts="5">
            </div>
    </div>
    
</div>

                <!-- Modal de Foto Maior -->
                <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <img id="modalFoto" src="" alt="Foto maior" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>


<!-- Inclusão do script do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Função para definir a avaliação de estrelas
    function setRating(stars) {
        document.getElementById('estrelas_avaliacao').value = stars;
        const estrelas = document.querySelectorAll("#avaliacao i");
        estrelas.forEach(star => {
            star.classList.remove('filled');
            if (star.getAttribute("data-value") <= stars) {
                star.classList.add('filled');
            }
        });
    }

    // Mostrar foto maior no modal
    const modalFoto = document.getElementById("modalFoto");
    const images = document.querySelectorAll('.carousel-item img');
    images.forEach(image => {
        image.addEventListener('click', (event) => {
            modalFoto.src = event.target.dataset.foto;
        });
    });


      // Exibir o modal automaticamente
      var myModal = new bootstrap.Modal(document.getElementById('feedbackModal'), {
        keyboard: false
    });
    myModal.show();
</script>

</body>
</html>
