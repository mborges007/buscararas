<?php
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_usuario = $_POST['nome_usuario'];
    $email_usuario = $_POST['email_usuario'];
    $senha_usuario = password_hash($_POST['senha_usuario'], PASSWORD_DEFAULT); // Hashing da senha
    
        $checkEmail = $conn->prepare("SELECT * FROM usuarios WHERE email_usuario = :email");
    $checkEmail->bindParam(':email', $email_usuario);
    $checkEmail->execute();

    if ($checkEmail->rowCount() > 0) {
        echo "<script>alert('Esse e-mail já está cadastrado. Por favor, utilize outro.'); window.history.back();</script>";
        exit; 
    } else {

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

            
            $_SESSION['id_usuario'] = $conn->lastInsertId(); 

echo "ID do Usuario: " . $_SESSION['id_usuario'];


header("Location: index.php");
exit; 

        } catch (PDOException $e) {
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>
