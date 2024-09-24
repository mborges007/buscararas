<?php
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
    <title>BuscAraras - Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
  
 <!-- Incluir a sidebar -->
 <?php include 'includes/sidebar.php'; ?>
 
    <div class="content d-flex flex-column align-items-center flex-grow-1">
        <h1>Início</h1>
        
        <!-- Radio buttons para Filtrar por Nome ou Profissão -->
        <div class="d-flex justify-content-center mb-4">
            <div class="form-check me-4">
                <input class="form-check-input" type="radio" name="searchFilter" id="searchByName" value="name" checked>
                <label class="form-check-label" for="searchByName">Nome</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="searchFilter" id="searchByProfession" value="profession">
                <label class="form-check-label" for="searchByProfession">Profissão</label>
            </div>
        </div>
        <div class="search-container mb-4">
            <input type="text" id="searchInput" class="search-input" placeholder="Busque aqui">
        </div>

        <div id="results" class="mb-4"></div> <!-- Container para resultados da busca -->

        <hr><hr><hr>
        
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
