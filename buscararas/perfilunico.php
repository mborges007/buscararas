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

// Adicionando a consulta para buscar as fotos associadas ao profissional
$sql_fotos = "SELECT caminho_foto FROM fotos_profissionais WHERE fk_profissional_id_profissional = :id_profissional";
$stmt_fotos = $conn->prepare($sql_fotos);
$stmt_fotos->bindParam(':id_profissional', $profissionalId, PDO::PARAM_INT);
$stmt_fotos->execute();

// Verifica se há fotos associadas ao profissional
$fotos_profissional = $stmt_fotos->fetchAll(PDO::FETCH_ASSOC);



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
                <p><strong>Telefone:</strong>
                    <a href="https://wa.me/55<?php echo htmlspecialchars($profissional['tel_profissional']);?>" target="_blank">
                        <i class="fab fa-whatsapp text-success"></i><?php echo htmlspecialchars($profissional['tel_profissional']);?>
                    </a>
                </p>

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

                <h5>Avalie este profissional</h5>
                <?php if ($avaliacaoExistente): ?>
                    <p class="text-warning">Você já avaliou este profissional com <?php echo htmlspecialchars($avaliacaoExistente['estrelas_avaliacao']); ?> estrelas.</p>
                    <form method="POST" action="avaliar.php">
                        <input type="hidden" name="id_profissional" value="<?php echo htmlspecialchars($profissional['id_profissional']); ?>">
                        <input type="hidden" name="usuario_ip" value="<?php echo htmlspecialchars($usuarioIp); ?>">
                        <div class="mb-3">
                            <label for="nota" class="form-label">Estrelas (0 a 5):</label>
                            <div id="avaliacao" class="star-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa fa-star <?php echo ($i <= $avaliacaoExistente['estrelas_avaliacao']) ? 'text-warning' : ''; ?>" data-value="<?php echo $i; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="estrelas" id="estrelas" value="<?php echo htmlspecialchars($avaliacaoExistente['estrelas_avaliacao']); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Atualizar Avaliação</button>
                    </form>
                <?php else: ?>
                    <form method="POST" action="avaliar.php">
                        <input type="hidden" name="id_profissional" value="<?php echo htmlspecialchars($profissional['id_profissional']); ?>">
                        <input type="hidden" name="usuario_ip" value="<?php echo htmlspecialchars($usuarioIp); ?>">
                        <div class="mb-3">
                            <label for="nota" class="form-label">Nota (0 a 5):</label>
                            <div id="avaliacao" class="star-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa fa-star" data-value="<?php echo $i; ?>" onclick="setRating(<?php echo $i; ?>)"></i>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="estrelas" id="estrelas" value="">
                        </div>
                        <button type="submit" class="btn btn-primary">Avaliar</button>
                    </form>
                <?php endif; ?>

                <br>
                <p><strong>Trabalhos feitos</strong></p>
<?php if (!empty($fotos_profissional)): ?> <!-- Verifica se há fotos -->
    <div class="fotos-profissionais">
        <div id="fotoCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner" style="text-align: center;">
                <?php $first = true; ?>
                <?php foreach ($fotos_profissional as $foto): ?>
                    <div class="carousel-item <?php echo $first ? 'active' : ''; ?>">
                        <img src="<?php echo htmlspecialchars($foto['caminho_foto']); ?>" alt="Foto do profissional" class="img-fluid rounded mb-2" data-bs-toggle="modal" data-bs-target="#fotoModal" data-foto="<?php echo htmlspecialchars($foto['caminho_foto']); ?>">
                    </div>
                    <?php $first = false; ?>
                <?php endforeach; ?>
            </div>

            <!-- Indicadores (Pontos) -->
                            <div class="carousel-indicators">
                                <?php $first = true; ?>
                                <?php foreach ($fotos_profissional as $index => $foto): ?>
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
                            <?php foreach ($fotos_profissional as $index => $foto): ?>
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

                                <!-- Modal para exibir imagem em tamanho grande -->
                                <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="" id="imagemGrande" class="img-fluid" alt="Imagem Grande">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <div class="fb-comments" 
                                data-href="https://www.seusite.com/perfil_profissional.php?id=<?php echo $profissionalId; ?>" 
                                data-width="100%" 
                                data-numposts="5">
                            </div>
                    </div>
                    </div>
                </div>
            </div>
</div>


<script>
    function setRating(rating) {
        document.getElementById('estrelas').value = rating;
        const stars = document.querySelectorAll('#avaliacao i');
        stars.forEach(star => {
            if (parseInt(star.getAttribute('data-value')) <= rating) {
                star.classList.add('text-warning');
            } else {
                star.classList.remove('text-warning');
            }
        });
    }

  // Script para carregar a imagem no modal
  var modal = document.getElementById('fotoModal');
    modal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; // Botão que abriu o modal
    var foto = button.getAttribute('data-foto'); // Caminho da foto
    var modalImg = document.getElementById('imagemGrande');
    
    modalImg.src = foto; // Atualiza a imagem no modal

    // Limita o tamanho da imagem a 1920x1080
    modalImg.style.width = "auto";  // Define largura automática para manter a proporção
    modalImg.style.height = "auto"; // Define altura automática para manter a proporção

    // Verifica se a largura ou altura excede 1920x1080 e ajusta
    if (modalImg.naturalWidth > 1920 || modalImg.naturalHeight > 1080) {
        var ratio = Math.min(1920 / modalImg.naturalWidth, 1080 / modalImg.naturalHeight);
        modalImg.style.width = (modalImg.naturalWidth * ratio) + 'px';
        modalImg.style.height = (modalImg.naturalHeight * ratio) + 'px';
    }
});

    
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
