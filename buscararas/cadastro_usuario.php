<?php
include 'db.php'; // Incluindo a conexão com o banco de dados


?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuscAraras Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" type="text/css">
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
                <a class="btn btn-primary w-100 mb-2" href="login.php">Login</a>
                <a class="btn btn-secondary w-100" href="cadastro.php">Cadastro</a>
            </div>
        </div>                     
        <!-- Main Content -->
<div class="main-content d-flex justify-content-center align-items-center">
            <div class="card form-card">
                <div class="card-body">
                <h4 class="card-title text-center">Cadastro|<span class="text" style="color:#BF4341;"> Usuário</span></h4>
                    <div class="text-center mb-3">
                    <a href="cadastro.php" class="btn btn-primary hoverando" style="background-color: #66888b; border-radius:25px;border-color: #66888b">Cadastro Profissional</a>
                    <a href="cadastro_usuario.php"class="btn btn-primary hoverando" style="background-color: #66888b; border-radius:25px;border-color: #66888b">Cadastro Usuário</a>
                    </div>
                    <form action="cadastrar_usuario.php" method="POST">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome_usuario" name="nome_usuario" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email_usuario" name="email_usuario" required>
                        </div>                                        
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <small class="form-text text-muted" style="margin-top: -6px;">A senha deve ter entre 6 e 8 caracteres.</small>
                            <input type="password" class="form-control" id="senha_usuario" name="senha_usuario" required maxlength="8" pattern=".{6,8}" title="A senha deve ter entre 6 e 8 caracteres.">            
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