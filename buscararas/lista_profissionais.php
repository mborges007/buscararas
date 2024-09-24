<?php
include 'db.php'; // Incluindo o arquivo de conexão

// Verificando se o parâmetro 'profissao' está presente na URL
if (isset($_GET['profissao'])) {
    $profissaoName = $_GET['profissao'];

    // Consultar informações da profissão no banco de dados
    $query = "SELECT * FROM profissional WHERE fk_profissoes_id_profissao = 
              (SELECT id_profissao FROM profissoes WHERE nome_profissao = :nome_profissao)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nome_profissao', $profissaoName);
    $stmt->execute();

    $profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Profissão não especificada.";
    exit;
}
include 'db.php'; // Incluindo o arquivo de conexão

// Consultar as profissões agrupadas por departamentos
$query = "SELECT p.nome_profissao, d.nome_area 
          FROM profissoes p 
          JOIN departamentos d ON p.fk_departamentos_id_area = d.id_area";

try {
    // Preparando e executando a consulta
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Fetching the results
    $profissoes = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $profissoes[$row['nome_area']][] = $row['nome_profissao'];
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
    <title>Profissionais - <?php echo htmlspecialchars($profissaoName); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" type="text/css"></head>
<body>
    

<?php include 'includes/sidebar.php'; ?>

<div class="content d-flex flex-column align-items-center flex-grow-1">

    <div class="container mt-5">
        <h1><?php echo htmlspecialchars($profissaoName); ?></h1>
        <ul class="list-group">
            <?php foreach ($profissionais as $profissional): ?>
                <li class="list-group-item">
                    <h5><?php echo htmlspecialchars($profissional['nome_profissional']); ?></h5>
                    <p><?php echo htmlspecialchars($profissional['descricao_profissional']); ?></p>
                    <p>Contato: <?php echo htmlspecialchars($profissional['tel_profissional']); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            let query = this.value;
            let filter = document.querySelector('input[name="searchFilter"]:checked').value;
            
            if (query.length > 0) {
                fetch(`search.php?query=${encodeURIComponent(query)}&filter=${filter}`)
                    .then(response => response.json())
                    .then(data => {
                        let resultsContainer = document.getElementById('results');
                        resultsContainer.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach(item => {
                                // Aqui você cria o link baseado no filtro selecionado
                                let link = filter === 'name' ? `profissional.php?id=${item.id}` : `lista_profissionais.php?profissao=${encodeURIComponent(item.nome)}`;
                                resultsContainer.innerHTML += `<a href="${link}" class="result-item">${item.nome}</a>`;
                            });
                        } else {
                            resultsContainer.innerHTML = '<p>Nenhum resultado encontrado.</p>';
                        }
                    });
            } else {
                document.getElementById('results').innerHTML = '';
            }
        });
    </script>


</body>
</html>
