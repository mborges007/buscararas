<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Carregar variáveis do arquivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize e validação do email
    $email_input = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email_input, FILTER_VALIDATE_EMAIL)) {
        echo 'Por favor, insira um email válido.';
        exit;
    }

    require 'db.php';

    // verificar primeiro na tabela usuarios
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email_usuario = ?");
    $stmt->execute([$email_input]);
    $user = $stmt->fetch();

    // procurar na tabela 'profissionais'
    if (!$user) {
        $stmt = $conn->prepare("SELECT id_profissional FROM profissional WHERE email_profissional = ?");
        $stmt->execute([$email_input]);
        $user = $stmt->fetch();
    }

    if ($user) {
        // Gerar token único
        $token = bin2hex(random_bytes(32));
        $link = "https://buscararas.serveo.net/buscararas/redefinir_senha_usuario.php?token=$token";

        if (isset($user['id_usuario'])) {
            // Atualizar a tabela de usuários
            $table = 'usuarios';
            $email_column = 'email_usuario';
            $token_column = 'token';
            $expire_column = 'expira_em';
        } else {
            // Atualizar a tabela de profissionais
            $table = 'profissional';
            $email_column = 'email_profissional';
            $token_column = 'token';
            $expire_column = 'expira_em';
        }

        $stmt = $conn->prepare("UPDATE $table SET $token_column = ?, $expire_column = ? WHERE $email_column = ?");
        $expira_em = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt->execute([$token, $expira_em, $email_input]);

        // enviar o email
        $mail = new PHPMailer(true);
        try {
            // configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['EMAIL_USERNAME'];
            $mail->Password   = $_ENV['EMAIL_PASSWORD'];
            $mail->Port       = $_ENV['SMTP_PORT'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // configurar remetente e destinatário
            $mail->setFrom($_ENV['EMAIL_USERNAME'], 'BuscAraras');
            $mail->addAddress($email_input);
            $mail->Subject = 'Redefinição de Senha - BuscAraras';

            // configurar mensagem em HTML
            $mail->isHTML(true);
            $mail->Body = "
                <p>Olá,</p>
                <p>Clique no link abaixo para redefinir sua senha:</p>
                <a href='$link'>$link</a>
                <p>Este link expirará em 1 hora.</p>
            ";

            $mail->send();
            echo 'Se o email informado estiver registrado, você receberá instruções para redefinir sua senha.';
        } catch (Exception $e) {
            echo "Erro ao enviar email: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Se o email informado estiver registrado, você receberá instruções para redefinir sua senha.';
    }
}
?>
