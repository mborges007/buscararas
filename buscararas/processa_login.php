<?php
session_start();
ob_start(); // Inicia o buffer de saída

$host = 'localhost';
$db = 'busca';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o formulário foi enviado via POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email_profissional'];
        $senha = $_POST['senha_profissional'];

        // Busca o profissional pelo email
        $stmt = $conn->prepare("SELECT * FROM profissional WHERE email_profissional = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica se a senha está correta
            if (password_verify($senha, $usuario['senha_profissional'])) {
                $_SESSION['id'] = $usuario['id_profissional'];
                header("Location: meuperfil.php");
                exit; // Encerra o script após o redirecionamento
            } else {
                echo "<script>alert('Email ou senha incorretos.'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('Email ou senha incorretos.'); window.location.href='login.php';</script>";
        }
    }
} catch (PDOException $e) {
    echo "<script>alert('Erro ao conectar com o banco de dados: " . $e->getMessage() . "');</script>";
}
?>
