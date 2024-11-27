<?php
session_start();
ob_start();
require 'db.php';

// Gerar um token CSRF se não existir na sessão
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Verificar se o token foi passado pela URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    $_SESSION['erro'] = "Token inválido.";
    header("Location: login_usuario.php");
    exit;
}

// Verificar se a requisição foi feita via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação do CSRF
    $post_token = $_POST['csrf_token'];
    $nova_senha = $_POST['nova_senha'];

    if (!isset($post_token) || $post_token !== $_SESSION['csrf_token']) {
        $_SESSION['erro'] = "Requisição inválida.";
        header("Location: redefinir_senha_usuario.php?token=$token");
        exit;
    }

    // Validar a nova senha
    if (strlen($nova_senha) < 6 || strlen($nova_senha) > 8) {
        $_SESSION['erro'] = "A senha deve ter entre 6 e 8 caracteres.";
        header("Location: redefinir_senha_usuario.php?token=$token");
        exit;
    }

    // Verificar se o token é válido
    $stmt = $conn->prepare("SELECT email_usuario, email_profissional FROM usuarios 
                            LEFT JOIN profissional ON usuarios.email_usuario = profissional.email_profissional 
                            WHERE (usuarios.tokens_redefinicao = :token OR profissional.tokens_redefinicao = :token) 
                            AND (usuarios.expira_em > NOW() OR profissional.expira_em > NOW())");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $email_usuario = $result['email_usuario'];
        $email_profissional = $result['email_profissional'];

        // Determinar o e-mail a ser utilizado
        $email = !empty($email_usuario) ? $email_usuario : $email_profissional;

        // Verificação do e-mail encontrado
        if (empty($email)) {
            $_SESSION['erro'] = "Nenhum e-mail encontrado para esse token!";
            header("Location: login_usuario.php");
            exit;
        }

        // Atualizar a senha
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $table = !empty($email_usuario) ? 'usuarios' : 'profissional';
        $column_email = !empty($email_usuario) ? 'email_usuario' : 'email_profissional';

        // Atualizar a senha no banco de dados
        $queryUpdate = "UPDATE $table SET senha_profissional = :senha WHERE $column_email = :email";
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bindParam(':senha', $senha_hash);
        $stmtUpdate->bindParam(':email', $email);
        $stmtUpdate->execute();

        // Verificação de sucesso na atualização
        if ($stmtUpdate->rowCount() > 0) {
            // Remover token após a atualização da senha
            $queryDeleteToken = "UPDATE $table SET token_redefinicao = NULL, expira_em = NULL WHERE $column_email = :email";
            $stmtDeleteToken = $conn->prepare($queryDeleteToken);
            $stmtDeleteToken->bindParam(':email', $email);
            $stmtDeleteToken->execute();

            $_SESSION['sucesso'] = "Senha redefinida com sucesso.";
            header("Location: login_usuario.php");
            exit;
        } else {
            $_SESSION['erro'] = "Erro ao atualizar a senha.";
            header("Location: redefinir_senha_usuario.php?token=$token");
            exit;
        }
    } else {
        $_SESSION['erro'] = "Token inválido ou expirado.";
        header("Location: login_usuario.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>

    <div class="container">
        <h2>Redefinir Senha</h2>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?>
            </div>
        <?php elseif (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
            </div>
        <?php endif; ?>

        <form action="redefinir_senha_usuario.php" method="POST">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <label for="nova_senha">Nova Senha:</label>
            <input type="password" id="nova_senha" name="nova_senha" required>
            <small>A senha deve ter entre 6 e 8 caracteres, sem a necessidade de números ou caracteres especiais.</small>

            <button type="submit">Redefinir Senha</button>
        </form>
    </div>

</body>
</html>
