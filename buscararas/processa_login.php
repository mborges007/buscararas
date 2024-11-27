<?php
session_start();
ob_start();

$host = 'localhost';
$db = 'busca';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = filter_var($_POST['email_profissional'], FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha_profissional'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erro'] = "E-mail inválido!";
            header("Location: login.php");
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM profissional WHERE email_profissional = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($senha, $usuario['senha_profissional'])) {
                $_SESSION['id_profissional'] = $usuario['id_profissional'];
                $_SESSION['sucesso'] = "Login bem-sucedido!";
                header("Location: meuperfil.php");
                exit; 
            } else {
                $_SESSION['erro'] = "E-mail ou senha incorretos.";
                header("Location: login.php");
                exit;
            }
        } else {
            $stmt_user = $conn->prepare("SELECT * FROM usuarios WHERE email_usuario = :email");
            $stmt_user->bindParam(':email', $email);
            $stmt_user->execute();

            if ($stmt_user->rowCount() > 0) {
                $_SESSION['erro'] = "Este e-mail pertence a um usuário, vou te redirecionar para a página correta.";
                header("Location: login_usuario.php");
                exit;
            } else {
                $_SESSION['erro'] = "E-mail ou senha incorretos.";
                header("Location: login.php");
                exit;
            }
        }
    }
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao conectar com o banco de dados: " . $e->getMessage();
    header("Location: login.php");
    exit;
}
?>
