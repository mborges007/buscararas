<?php
include 'db.php'; // Incluindo a conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtendo os dados do formulário
    $nome_profissional = $_POST['nome_profissional'];
    $email_profissional = $_POST['email_profissional'];
    $tel_profissional = $_POST['tel_profissional'];
    $fk_departamentos_id_area = $_POST['fk_departamentos_id_area'];
    $fk_profissoes_id_profissao = $_POST['fk_profissoes_id_profissao'];
    $senha_profissional = password_hash($_POST['senha_profissional'], PASSWORD_DEFAULT); // Hashing da senha
    $descricao_profissional = $_POST['descricao_profissional'];
    $ranking_profissional = 0; // Inicializando o ranking como 0 (ou outro valor padrão)

    // Verificando se o email já está cadastrado
    $checkEmail = $conn->prepare("SELECT * FROM profissional WHERE email_profissional = :email");
    $checkEmail->bindParam(':email', $email_profissional);
    $checkEmail->execute();

    if ($checkEmail->rowCount() > 0) {
        // Email já cadastrado
        echo "<script>alert('Esse e-mail já está cadastrado. Por favor, utilize outro.'); window.history.back();</script>";
        exit; // Encerra a execução
    } else {
        // Preparando a consulta SQL para inserir o novo profissional
        $sql = "INSERT INTO profissional (nome_profissional, senha_profissional, descricao_profissional, email_profissional, tel_profissional, ranking_profissional, fk_departamentos_id_area, fk_profissoes_id_profissao) 
                VALUES (:nome, :senha, :descricao, :email, :telefone, :ranking, :id_area, :id_profissao)";
        
        // Usando Prepared Statements
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $nome_profissional);
            $stmt->bindParam(':senha', $senha_profissional);
            $stmt->bindParam(':descricao', $descricao_profissional);
            $stmt->bindParam(':email', $email_profissional);
            $stmt->bindParam(':telefone', $tel_profissional);
            $stmt->bindParam(':ranking', $ranking_profissional);
            $stmt->bindParam(':id_area', $fk_departamentos_id_area);
            $stmt->bindParam(':id_profissao', $fk_profissoes_id_profissao);

            // Executando a consulta
            $stmt->execute();

            // Redirecionando para a página meuperfil.php
            header("Location: meuperfil.php");
            exit; // Encerra o script para evitar execução adicional
        } catch (PDOException $e) {
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>
