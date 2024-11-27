<?php
session_start();
ob_start();
require 'db.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT id_usuario FROM usuarios WHERE email_usuario = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $tipo_email = 'usuarios';
            $email_exists = true;
        } else {
            $queryProf = "SELECT id_profissional FROM profissional WHERE email_profissional = :email";
            $stmtProf = $conn->prepare($queryProf);
            $stmtProf->bindParam(':email', $email);
            $stmtProf->execute();

            if ($stmtProf->rowCount() > 0) {
                $tipo_email = 'profissional';
                $email_exists = true;
            } else {
                $email_exists = false;
            }
        }

        if ($email_exists) {
            $queryDelete = "DELETE FROM tokens_redefinicao WHERE email_redefinicao = :email";
            $stmtDelete = $conn->prepare($queryDelete);
            $stmtDelete->bindParam(':email', $email);
            $stmtDelete->execute();

            $token = bin2hex(random_bytes(16));
            $expira_em = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $queryToken = "INSERT INTO tokens_redefinicao (email_redefinicao, token, expira_em) VALUES (:email, :token, :expira_em)";
            $stmtToken = $conn->prepare($queryToken);
            $stmtToken->bindParam(':email', $email);
            $stmtToken->bindParam(':token', $token);
            $stmtToken->bindParam(':expira_em', $expira_em);
            $stmtToken->execute();

            $link = "https://buscararas.serveo.net/buscararas/redefinir_senha_usuario.php?token=$token";
            $mensagem = "
            <p>Olá,</p>
            <p>Clique no link abaixo para redefinir sua senha:</p>
            <a href='$link'>$link</a>
            <p>Este link expirará em 1 hora.</p>
            ";

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = $_ENV['SMTP_HOST'];
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['EMAIL_USERNAME'];
                $mail->Password = $_ENV['EMAIL_PASSWORD'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = $_ENV['SMTP_PORT'];

                $mail->setFrom($_ENV['EMAIL_USERNAME'], 'BuscAraras');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Redefinição de Senha';
                $mail->Body = $mensagem;

                $mail->send();
                $_SESSION['sucesso'] = "Se o email informado estiver registrado, você receberá instruções para redefinir sua senha.";
                header("Location: login_usuario.php");
                exit;
            } catch (Exception $e) {
                $_SESSION['erro'] = "Erro ao enviar email: {$mail->ErrorInfo}";
            }
        } else {
            $_SESSION['erro'] = "O email informado não está registrado.";
            header("Location: login_usuario.php");
            exit;
        }
    }

    $_SESSION['erro'] = "Por favor, insira um email válido.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci Minha Senha</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
    <div class="form-container">
        <h2>Recuperação de Senha</h2>
        <?php if (isset($_SESSION['erro'])): ?>
            <p style="color: red;"><?php echo htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['sucesso'])): ?>
            <p style="color: green;"><?php echo htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="email">Digite seu email:</label>
            <input type="email" name="email" id="email" placeholder="Seu email" required>
            <button type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>
