<?php
session_start();

include 'db.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login_usuario.php'); // login se não estiver logado
    exit();
}

$usuario_id = $_SESSION['id_usuario']; 

$query = "SELECT usuarios.*, nome_usuario, email_usuario 
          FROM usuarios
          WHERE id_usuario = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $usuario_id);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// att de perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome_usuario']);
    $email = trim($_POST['email_usuario']);
    $senha = trim($_POST['senha_usuario']);
  

    if (strlen($descricao) < 80) {
        $error_message = "A descrição deve ter pelo menos 80 caracteres.";
    } else {
        // att o perfil do usuario no bds
        $update_query = "UPDATE usuarios SET 
                            nome_usuario = :nome_usuario, 
                            email_usuario = :email_usuario,                            
                            senha_usuario = :senha_usuario,                           
                         WHERE id_usuario = :id";
        
        $stmt = $conn->prepare($update_query);
        $stmt->bindParam(':nome_usuario', $nome_usuario);
        $stmt->bindParam(':email_usuario', $email_usuario);
        $stmt->bindParam(':senha_usuario', password_hash($senha_usuario, PASSWORD_DEFAULT)); // Hash de senha para segurança
        $stmt->bindParam(':id', $usuario_id);
        
        if ($stmt->execute()) {
            $success_message = "Perfil atualizado com sucesso!";
            // Redireciona para a página do perfil do usuário
            header('Location: meuperfil.php');
            exit();
        } else {
            $error_message = "Ocorreu um erro ao atualizar o perfil. Tente novamente.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - BuscAraras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h1 class="text-light text-left" style="color:#F5F5E6;">Busc<span class="text" style="color: #BF4341;">Araras</span></h1>
        <a class="btn btn-light no-border mb-2 tamanho" href="index.php">Início</a>
            <h3 class="text-danger text-left">Editar Perfil</h3>
        </div>

        <div class="main-content d-flex justify-content-center align-items-center">
            <div class="card form-card">
                <div class="card-body">
                    <h4 class="card-title text-center">Editar Perfil</h4>
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?= $error_message ?></div>
                    <?php elseif (isset($success_message)): ?>
                        <div class="alert alert-success"><?= $success_message ?></div>
                    <?php endif; ?>
                    <form action="editar_perfil.php" method="POST">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome_usuario" value="<?= htmlspecialchars($usuario['nome_usuario']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email_usuario" value="<?= htmlspecialchars($usuario['email_usuario']) ?>" required>
                        </div>                    
                           
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha_usuario" required maxlength="8" pattern=".{6,8}" title="A senha deve ter entre 6 e 8 caracteres.">
                        </div>
                        
                        <div class="btn-container">
                            <button type="submit" class="btn btn-danger btn-sm btn-block">Atualizar</button>
                            <a href="deletar_perfil.php" class="btn btn-danger btn-sm btn-block mt-2" onclick="return confirm('Tem certeza que deseja deletar seu perfil?')">Deletar Perfil</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
