<?php
$host = 'localhost'; // ou o endereço do seu servidor de banco de dados
$db = 'busca'; // substitua pelo nome do seu banco de dados
$user = 'root'; // substitua pelo seu usuário do banco de dados
$pass = ''; // substitua pela sua senha do banco de dados

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email_profissional'];
        $senha = $_POST['senha_profissional'];

        // Consulta para verificar as credenciais
        $stmt = $conn->prepare("SELECT * FROM profissional WHERE email_profissional = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifique a senha usando password_verify
            if (password_verify($senha, $usuario['senha_profissional'])) {
                // Credenciais corretas, faça o login
                session_start();
                $_SESSION['user'] = $email; // ou outra informação do usuário
                header("Location: meuperfil.html"); // Redireciona para a página do painel
                exit;
            } else {
                echo "Email ou senha incorretos.";
            }

            // Para depuração, você pode descomentar a linha abaixo:
            echo "Email: $email, Senha: $senha, Senha Hash: " . $usuario['senha_profissional'] . "<br>";
        } else {
            echo "Email ou senha incorretos.";
        }
    }
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}

// Mensagens de erro amigáveis
if ($stmt->rowCount() > 0) {
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($senha, $usuario['senha_profissional'])) {
        session_start();
        $_SESSION['user'] = $email; // ou outra informação do usuário
        header("Location: meuperfil.html");
        exit;
    } else {
        echo "<script>alert('Email ou senha incorretos.'); window.location.href='login.html';</script>";
    }
} else {
    echo "<script>alert('Email ou senha incorretos.'); window.location.href='login.html';</script>";
}


?>
