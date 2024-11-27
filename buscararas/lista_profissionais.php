<?php
include 'db.php';
session_start();

if (!isset($_GET['profissao'])) {
    echo "<p>Por favor, selecione uma profissão para ver os profissionais.</p>";
    exit;
}

$profissaoName = $_GET['profissao'];

$ordenar = isset($_GET['ordenar']) ? $_GET['ordenar'] : 'nome'; // Valor padrão é 'nome'
$orderBy = $ordenar === 'avaliacao' ? 'media_estrelas DESC' : 'p.nome_profissional ASC';

try {
    $query = "
        SELECT p.*, 
        (SELECT AVG(a.estrelas_avaliacao) FROM avaliacao a WHERE a.fk_profissional_id_profissional = p.id_profissional) AS media_estrelas 
        FROM profissional p
        WHERE p.fk_profissoes_id_profissao = 
              (SELECT id_profissao FROM profissoes WHERE nome_profissao = :nome_profissao)
        ORDER BY $orderBy";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nome_profissao', $profissaoName);
    $stmt->execute();
    $profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $fotosPerfis = [];
    $sql_foto_perfil = "
        SELECT fk_profissional_id_profissional, caminho_foto_perfil 
        FROM fotos_perfil 
        WHERE fk_profissional_id_profissional = :id_profissional 
        ORDER BY id_foto_perfil DESC LIMIT 1";
    $stmt_foto_perfil = $conn->prepare($sql_foto_perfil);

    foreach ($profissionais as $profissional) {
        $stmt_foto_perfil->bindParam(':id_profissional', $profissional['id_profissional'], PDO::PARAM_INT);
        $stmt_foto_perfil->execute();
        $foto = $stmt_foto_perfil->fetch(PDO::FETCH_ASSOC);
        $fotosPerfis[$profissional['id_profissional']] = $foto ? htmlspecialchars($foto['caminho_foto_perfil']) : 'img/perfilpadrao.jpg';
    }
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
    <div class="container mt-5">
        <h1 style="text-align: center; text-transform: uppercase;"><?php echo htmlspecialchars($profissaoName); ?></h1>
        <br>
        <form method="GET" action="" style="text-align: center;">
            <input type="hidden" name="profissao" value="<?php echo urlencode($profissaoName); ?>" />
            <label for="ordenar" style="margin-bottom: 10px; font-weight: bold">Ordenar por:</label>

            <div class="select-container" style="display: flex; justify-content: center; align-items: center; width: 100%; gap: 10px; margin: 0 auto;">
                <select class="form-select custom-select" name="ordenar" id="ordenar" style="width: 150px; height: 38px; border: 2px solid #84B0B5; border-radius: 20px; padding: 5px 10px; margin: 0; box-sizing: border-box;">
                    <option value="nome" <?php echo isset($_GET['ordenar']) && $_GET['ordenar'] == 'nome' ? 'selected' : ''; ?>>Nome</option>
                    <option value="avaliacao" <?php echo isset($_GET['ordenar']) && $_GET['ordenar'] == 'avaliacao' ? 'selected' : ''; ?>>Média de Avaliação</option>
                </select>

                <button type="submit" class="btn hoverando verdinho" style="border-radius: 20px; height: 38px; padding: 5px 15px; margin: 0; box-sizing: border-box; width: auto;">Ordenar</button>
            </div>
        </form>

        <?php if (count($profissionais) > 0): ?>
            <?php foreach ($profissionais as $profissional): ?>
                <div class="w-100 mb-4">
                    <div class="card form-card w-500 p-2 profile-container">
                        <div class="row align-items-center-perfil mb-4" style="cursor: pointer;" onclick="window.location.href='perfilunico.php?id=<?php echo $profissional['id_profissional']; ?>';">
                                <div class="col-md-3 text-center">
                                    <img src="<?php echo $fotosPerfis[$profissional['id_profissional']]; ?>" alt="Foto do Profissional" class="img-fluid rounded-circle profile-photo">
                                </div>
                            <div class="col-md-8 profile-info">
                                <h4 class="text-center" style="text-transform: capitalize;"><?php echo htmlspecialchars($profissional['nome_profissional']); ?></h4>
                                <p>
                                <span style ="font-size: 1.25rem; font-weight: bold;margin: 0;">Telefone</span>
                                    <a href="https://wa.me/55<?php echo htmlspecialchars($profissional['tel_profissional']); ?>" target="_blank">
                                        <i class="fab fa-whatsapp text-success"></i> <?php echo htmlspecialchars($profissional['tel_profissional']); ?>
                                    </a>
                                </p>
                                <p>
                                <span style ="font-size: 1.25rem; font-weight: bold;margin: 0;">Profissão: </span> <?php echo htmlspecialchars($profissaoName); ?>
                                </p>
                                <span style ="font-size: 1.25rem; font-weight: bold;margin: 0;">Descrição</span>
                                <p><?php echo htmlspecialchars($profissional['descricao_profissional']); ?></p>
                                <p style="font-weight:500"> Clique para mais informações</p>
                                <span style ="font-size: 1.25rem; font-weight: bold;margin: 0;">Avaliação</span>
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
