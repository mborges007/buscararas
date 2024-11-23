<?php
session_start(); // Começar a sessão no início do script
include 'db.php'; // Incluindo a conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtendo os dados do formulário
    $nome_usuario = $_POST['nome_usuario'];
    $email_usuario = $_POST['email_usuario'];
    $senha_usuario = password_hash($_POST['senha_usuario'], PASSWORD_DEFAULT); // Hashing da senha
    
    // Verificando se o email já está cadastrado
    $checkEmail = $conn->prepare("SELECT * FROM usuarios WHERE email_usuario = :email");
    $checkEmail->bindParam(':email', $email_usuario);
    $checkEmail->execute();

    if ($checkEmail->rowCount() > 0) {
        // Email já cadastrado
        echo "<script>alert('Esse e-mail já está cadastrado. Por favor, utilize outro.'); window.history.back();</script>";
        exit; // Encerra a execução
    } else {
        // Preparando a consulta SQL para inserir o novo profissional
        $sql = "INSERT INTO usuarios (nome_usuario, email_usuario,senha_usuario) 
                VALUES (:nome,:email,:senha)";
        
        // Usando Prepared Statements
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $nome_usuario);            
            $stmt->bindParam(':email', $email_usuario);
            $stmt->bindParam(':senha', $senha_usuario);

            // Executando a consulta
            $stmt->execute();

            // Armazenar o ID do profissional na sessão após o cadastro
            $_SESSION['id_usuario'] = $conn->lastInsertId(); // Salva o ID do profissional recém-cadastrado

echo "ID do Usuario: " . $_SESSION['id_usuario'];


header("Location: index.php");
exit; 

        } catch (PDOException $e) {
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>
