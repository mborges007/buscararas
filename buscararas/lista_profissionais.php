<?php
// Incluindo o arquivo de conexão com o banco de dados
include 'db.php';

// Verificando se o parâmetro 'profissao' está presente na URL
if (!isset($_GET['profissao'])) {
    echo "<p>Por favor, selecione uma profissão para ver os profissionais.</p>";
    exit;
}

$profissaoName = $_GET['profissao'];

// Pegando a escolha de ordenação
$ordenar = isset($_GET['ordenar']) ? $_GET['ordenar'] : 'nome'; // Valor padrão é 'nome'

// Modificando a consulta de acordo com a ordenação escolhida
if ($ordenar == 'avaliacao') {
    $orderBy = 'media_estrelas DESC'; // Ordena pela média de estrelas (decrescente)
} else {
    $orderBy = 'p.nome_profissional ASC'; // Ordena pelo nome do profissional (crescente)
}

try {
    // Consultar os profissionais relacionados à profissão selecionada com a ordenação
    $query = "
        SELECT p.*, 
        (SELECT AVG(a.estrelas_avaliacao) FROM avaliacao a WHERE a.fk_profissional_id_profissional = p.id_profissional) AS media_estrelas 
        FROM profissional p
        WHERE p.fk_profissoes_id_profissao = 
              (SELECT id_profissao FROM profissoes WHERE nome_profissao = :nome_profissao)
        ORDER BY $orderBy"; // Aplica a ordenação

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="content d-flex flex-column align-items-center flex-grow-1">
        <div class="container mt-5">
            <h1 style="text-align: center; text-transform: uppercase;"><?php echo htmlspecialchars($profissaoName); ?></h1>
            <br>
            <!-- Formulário de Ordenação -->
            <form method="GET" action="" style="text-align: center;">
                <input type="hidden" name="profissao" value="<?php echo urlencode($profissaoName); ?>" />
                <label for="ordenar" style="margin-bottom: 10px;">Ordenar por:</label>

                <!-- Contêiner para o select e botão -->
                <div class="select-container" style="display: flex; justify-content: center; align-items: center; width: 100%; gap: 10px; margin: 0 auto;">
                    <!-- Select com bordas, arredondamento e altura ajustada -->
                    <select class="form-select custom-select" name="ordenar" id="ordenar" style="width: 150px; height: 38px; border: 2px solid #84B0B5; border-radius: 20px; padding: 5px 10px; margin: 0; box-sizing: border-box;">
                        <option value="nome" <?php echo isset($_GET['ordenar']) && $_GET['ordenar'] == 'nome' ? 'selected' : ''; ?>>Nome</option>
                        <option value="avaliacao" <?php echo isset($_GET['ordenar']) && $_GET['ordenar'] == 'avaliacao' ? 'selected' : ''; ?>>Média de Avaliação</option>
                    </select>

                    <!-- Botão de Ordenar com altura ajustada e largura reduzida -->
                    <button type="submit" class="btn verdinho" style="border-radius: 20px; height: 38px; padding: 5px 15px; margin: 0; box-sizing: border-box; width: auto;">Ordenar</button>
                </div>
            </form>

            <!-- Verifica se há profissionais cadastrados -->
            <?php if (count($profissionais) > 0): ?>
                <?php foreach ($profissionais as $profissional): ?>
                    

                    
                    <div class="w-100 mb-4">
                        
                        <div class="card form-card w-500 p-2 profile-container">
                            <div class="row align-items-center-perfil mb-4" style="cursor: pointer;" 
                                 onclick="window.location.href='perfilunico.php?id=<?php echo $profissional['id_profissional']; ?>';">
                                <div class="col-md-4 text-center">
                                    <img src="img/chiquinha.jpg" alt="Foto do Profissional" class="img-fluid rounded-circle profile-photo">
                                </div>
                                <div class="col-md-8 profile-info">
                                        <h4 class="text-center" style="text-transform: capitalize;"><?php echo htmlspecialchars($profissional['nome_profissional']); ?></h4>
                                        <p><strong>Telefone:</strong> 
                                        <a href="https://wa.me/55<?php echo htmlspecialchars($profissional['tel_profissional']);?>" target="_blank">
                                                <i class="fab fa-whatsapp text-success"></i> <?php echo htmlspecialchars($profissional['tel_profissional']); ?>
                                            </a>
                                        </p>
                                        <p><strong>Profissão:</strong> <?php echo htmlspecialchars($profissaoName); ?></p>
                                        
                                        <h5>Descrição</h5>
                                        <p><?php echo htmlspecialchars($profissional['descricao_profissional']); ?></p>
                                        
                                        <h5>Avaliação</h5>
                                                <p>
                                                    <?php
                                                    
                                                    $media_estrelas = isset($profissional['media_estrelas']) ? $profissional['media_estrelas'] : null;
                                                    if ($media_estrelas !== null) {
                                                        
                                                        $stars_full = floor($media_estrelas); 
                                                        $stars_half = ($media_estrelas - $stars_full >= 0.5) ? 1 : 0; 
                                                        $stars_empty = 5 - ($stars_full + $stars_half); 

                                                        // Exibe as estrelas
                                                        for ($i = 0; $i < $stars_full; $i++) {
                                                            echo '<i class="fas fa-star"></i>'; 
                                                        }
                                                        if ($stars_half) {
                                                            echo '<i class="fas fa-star-half-alt"></i>'; 
                                                        }
                                                        for ($i = 0; $i < $stars_empty; $i++) {
                                                            echo '<i class="far fa-star"></i>'; 
                                                        }
                                                        
                                                        echo " (" . htmlspecialchars(number_format($media_estrelas, 1)) . " estrelas)";
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
