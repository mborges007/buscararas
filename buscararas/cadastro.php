<?php
include 'db.php'; // Incluindo a conexão com o banco de dados

// Consultando os departamentos
$departamentos = $conn->query("SELECT * FROM departamentos")->fetchAll(PDO::FETCH_ASSOC);

// Consultando as profissões
$profissoes = $conn->query("SELECT * FROM profissoes")->fetchAll(PDO::FETCH_ASSOC);




?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuscAraras Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
    <div class="container-fluid p-0 vh-100 d-flex">
     <!-- Sidebar -->
     <div class="sidebar d-flex flex-column p-3">
            <h1 class="text-light text-left">BuscAraras</h1>
            
            <!-- Botão Início -->
            <a class="btn btn-light no-border mb-2 tamanho" href="index.php">Início</a>
            <h3 class="text-danger text-left">Cadastre-se</h3>
            <!-- Dropdowns -->
            <div class="dropdown mt-2">

                <ul class="dropdown-menu no-border" aria-labelledby="dropdownReparoAutomotivo">  
                </ul>
            </div>
            
            <!-- Botões de Login e Cadastro -->
            <div class="mt-auto">
                <a class="btn btn-primary w-100 mb-2" href="login.html">Login</a>
                <a class="btn btn-secondary w-100" href="cadastro.php">Cadastro</a>
            </div>
        </div>                     
        <!-- Main Content -->
<div class="main-content d-flex justify-content-center align-items-center">
            <div class="card form-card">
                <div class="card-body">
                    <h4 class="card-title text-center">Cadastro</h4>
                    <form action="cadastrar.php" method="POST">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome_profissional" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email_profissional" required>
                        </div>
                        <div class="form-group">
                            <label for="telefone">Telefone com Whatsapp</label>
                            <small class="form-text text-muted">Insira apenas números (sem espaços ou caracteres especiais).</small>
                            <input type="tel" class="form-control" id="telefone" maxlength="11" name="tel_profissional" required pattern="[0-9]{11}">
                            
                        </div>
                        <div class="form-group">
                            <label for="area-atuacao">Área de atuação</label>
                            <select class="form-select" id="area-atuacao" name="fk_departamentos_id_area" required onchange="carregarProfissoes()">
                                <option selected>Selecione uma opção</option>
                                <?php foreach ($departamentos as $departamento): ?>
                                    <option value="<?= $departamento['id_area'] ?>"><?= $departamento['nome_area'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="profissao">Profissão</label>
                            <select class="form-select" id="profissao" name="fk_profissoes_id_profissao" required>
                                <option selected>Selecione uma opção</option>
                                <!-- Profissões serão carregadas aqui -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <small class="form-text text-muted">A senha deve ter entre 6 e 8 caracteres.</small>
                            <input type="password" class="form-control" id="senha" name="senha_profissional" required maxlength="8" pattern=".{6,8}" title="A senha deve ter entre 6 e 8 caracteres.">
                            
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição Profissional</label>
                            <textarea class="form-control" id="descricao" name="descricao_profissional" rows="3" placeholder="Breve relato sobre seu ofício"></textarea>
                        </div>
                        <div class="btn-container">
                            <button type="submit" class="btn btn-danger btn-sm btn-block">Enviar</button>
                        </div>
                    </form>
                </div>
        </div>
</div>

   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
    function carregarProfissoes() {
        const idArea = document.getElementById('area-atuacao').value;
        const profissaoSelect = document.getElementById('profissao');

        // Limpa as opções anteriores
        profissaoSelect.innerHTML = '<option selected>Selecione uma opção</option>';

        if (idArea) {
            fetch(`carregar_profissoes.php?id_area=${idArea}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(profissao => {
                        const option = document.createElement('option');
                        option.value = profissao.id_profissao;
                        option.textContent = profissao.nome_profissao;
                        profissaoSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Erro ao carregar profissões:', error));
        }
    }
    </script>
</body>

</html>