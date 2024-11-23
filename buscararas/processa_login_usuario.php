<?php
// Defina as configurações de sessão antes de iniciar a sessão
ini_set('session.cookie_domain', '.ngrok.io'); // Define o domínio de cookies para ngrok (se necessário)
ini_set('session.cookie_path', '/');  // Garante que o cookie da sessão seja válido para todo o site

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inicia a sessão
session_start();

// Inicia o buffer de saída
ob_start();

$host = 'localhost';
$db = 'busca';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email_usuario'];
        $senha = $_POST['senha_usuario'];

        // Busca o profissional pelo email
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email_usuario = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica se a senha está correta
            if (password_verify($senha, $usuario['senha_usuario'])) {
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                header("Location: index.php");
                exit;
            } else {
                // Armazenando o erro na sessão para exibição na próxima página
                $_SESSION['erro_login'] = 'Email ou senha incorretos.';
                header("Location: login_usuario.php");
                exit;
            }
        } else {
            // Busca na tabela de profissionais
            $stmt_user = $conn->prepare("SELECT * FROM profisional WHERE email_profissional = :email");
            $stmt_user->bindParam(':email', $email);
            $stmt_user->execute();

            if ($stmt_user->rowCount() > 0) {
                $profissional = $stmt_user->fetch(PDO::FETCH_ASSOC);

                // Verifica a senha
                if (password_verify($senha, $profissional['senha_profissional'])) {
                    $_SESSION['id_profissional'] = $profissional['id_profissional'];
                    header("Location: index.php");
                    exit;
                } else {
                    // Armazenando o erro na sessão para exibição na próxima página
                    $_SESSION['erro_login'] = 'Email ou senha incorretos.';
                    header("Location: login_usuario.php");
                    exit;
                }
            } else {
                // Armazenando o erro na sessão para exibição na próxima página
                $_SESSION['erro_login'] = 'Usuário não encontrado.';
                header("Location: login_usuario.php");
                exit;
            }
        }
    }
} catch (PDOException $e) {
    // Armazenando o erro de banco de dados na sessão
    $_SESSION['erro_login'] = 'Erro ao conectar com o banco de dados: ' . $e->getMessage();
    header("Location: login_usuario.php");
    exit;
}
?>
