<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);



session_start();
ob_start(); 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuscAraras Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" type="text/css">
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
        <div class="sidebar d-flex flex-column p-3">
        <h1 class="text-light text-left" style="color:#F5F5E6; display: flex; align-items: center;">
                    <span class="text" style="color: #F5F5E6;"> Busc</span><span class="text" style="color: #BF4341;">Araras</span>
                        <img src="img/lupasidebar.svg" alt="Lupa" style="width: 25px; height: 25px; margin-right: -5px; margin-top:9px;">                
            </h1>
            
            <a class="btn btn-light no-border mb-2 tamanho" href="index.php">Início</a>
            <h3 class="text-danger text-left">Login</h3>
            
            <div class="mt-auto">
                <a class="btn btn-primary w-100 mb-2" href="login.php">Login</a>
                <a class="btn btn-secondary w-100" href="cadastro.php">Cadastro</a>
            </div>
        </div>                     

        <div class="main-content d-flex justify-content-center align-items-center">
            <div class="card form-card">
                <div class="card-body">
                <h4 class="card-title text-center">Login|<span class="text" style="color:#BF4341;"> Usuário</span></h4>
                    <div class="text-center mb-3">
                    <a href="login.php" class="btn btn-primary hoverando" style="background-color: #66888b; border-radius:25px;border-color: #66888b">Login Profissional</a>
                    <a href="login_usuario.php"class="btn btn-primary hoverando" style="background-color: #66888b; border-radius:25px;border-color: #66888b">Login Usuário</a>
                    </div>
                    <form action="processa_login_usuario.php" method="POST"> <!-- Ação do formulário -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email_usuario" name="email_usuario" required placeholder="Digite seu email">
                        </div>
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input type="password" class="form-control" id="senha_usuario" name="senha_usuario" required placeholder="Máx. 8">
                        </div>
                        <p class="text-center mt-3">
                            <a href="esqueci_senha_usuario.php" class="text-decoration-none" style="color:#66888b;">Esqueci minha senha</a>
                        </p>
                        <div class="btn-container">
                            <button type="submit" class="btn btn-success hoverando btn-sm btn-block">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
