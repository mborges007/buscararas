<?php include 'includes/busca_profissionais.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuscAraras - Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
</head>
<body>
  
    <!-- Incluir a sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <div class="content d-flex flex-column align-items-center flex-grow-1">
    <h1>Início</h1>
    <div class="d-flex justify-content-center mb-4">
        <div class="form-group me-3">
            <label for="searchFilter">Filtrar por</label>
            <select class="form-select aumentar" id="searchFilter" onchange="toggleSearchInput()">
                <option value="name">Nome</option>
                <option value="profession">Profissão</option>
            </select>
        </div>
        <div class="form-group">
            <label for="searchInput">Digite para buscar</label>
            <input type="text" class="form-control aumentar" id="searchInput" onkeyup="carregarOpcoes()" placeholder="Comece a digitar...">
            <ul id="searchResults" class="list-group position-absolute z-index-100" style="display: none;"></ul>
        </div>
    </div>

<br>
<br>
<br>
<br>
<br>
<br>

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
    
    function toggleSearchInput() {
    document.getElementById('searchInput').value = '';
    document.getElementById('searchResults').style.display = 'none';
}

    function carregarOpcoes() {
    const filtro = document.getElementById('searchFilter').value;
    const query = document.getElementById('searchInput').value;

    if (query.length < 2) {
        document.getElementById('searchResults').style.display = 'none';
        return;
    }

    fetch(`buscar.php?filter=${filtro}&query=${query}`)
        .then(response => {
            console.log('Resposta do servidor:', response);
            return response.json();
        })
        .then(data => {
            const resultsContainer = document.getElementById('searchResults');
            resultsContainer.innerHTML = ''; // Limpa resultados anteriores

            if (data.length > 0) {
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = filtro === 'name' ? item.nome_profissional : item.nome_profissao;

                    // Lógica para redirecionar ao clicar
                    li.onclick = () => {
                        if (filtro === 'name') {
                            // Redirecionar para o perfil do profissional
                            window.location.href = `perfilunico.php?id=${item.id_profissional}`; // Altere para a sua estrutura de URL
                        } else if (filtro === 'profession') {
                            // Redirecionar para a lista de profissionais por profissão
                            window.location.href = `lista_profissionais.php?profissao=${item.nome_profissao}`; // Altere para a sua estrutura de URL
                        }
                    };

                    resultsContainer.appendChild(li);
                });
                resultsContainer.style.display = 'block'; // Exibe resultados
            } else {
                resultsContainer.style.display = 'none'; // Oculta se não houver resultados
            }
        })
        .catch(error => console.error('Erro ao buscar opções:', error));
}

 
    
    function redirecionar() {
        const filtro = document.getElementById('searchFilter').value;
        const searchInput = document.getElementById('searchInput').value;

        if (filtro === 'name') {
            // Redirecionar para o perfil do profissional
            window.location.href = `perfilunico.php?id=${searchInput}`;
        } else if (filtro === 'profession') {
            // Redirecionar para a lista de profissionais por profissão
            window.location.href = `lista_profissionais.php?profissao=${searchInput}`;
        }
    }
    </script>
</body>
</html>
