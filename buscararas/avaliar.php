<?php
// Incluindo o arquivo de conexão com o banco de dados
include 'db.php';

// Verificando se os dados do formulário foram enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_profissional = $_POST['id_profissional'];
    $estrelas = $_POST['estrelas']; // Renomeado de nota para estrelas
    $usuario_ip = $_POST['usuario_ip'];

    try {
        // Verificar se o usuário já avaliou o profissional pelo IP
        $avaliacaoQuery = "SELECT * FROM avaliacao WHERE fk_profissional_id_profissional = :id_profissional AND usuario_ip = :usuario_ip";
        $avaliacaoStmt = $conn->prepare($avaliacaoQuery);
        $avaliacaoStmt->bindParam(':id_profissional', $id_profissional, PDO::PARAM_INT);
        $avaliacaoStmt->bindParam(':usuario_ip', $usuario_ip, PDO::PARAM_STR);
        $avaliacaoStmt->execute();
        $avaliacaoExistente = $avaliacaoStmt->fetch(PDO::FETCH_ASSOC);

        if ($avaliacaoExistente) {
            // Atualizando a avaliação existente
            $updateQuery = "UPDATE avaliacao SET estrelas_avaliacao = :estrelas WHERE id_avaliacao = :id_avaliacao"; // Atualizado para estrelas
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindParam(':id_avaliacao', $avaliacaoExistente['id_avaliacao'], PDO::PARAM_INT);
            $updateStmt->bindParam(':estrelas', $estrelas, PDO::PARAM_INT); // Atualizado para estrelas
            $updateStmt->execute();

            // Mensagem de sucesso para atualização
            header("Location: perfilunico.php?id=$id_profissional&mensagem=" . urlencode("Avaliação atualizada com sucesso!"));
        } else {
            // Inserir a nova avaliação no banco de dados
            $insertQuery = "INSERT INTO avaliacao (fk_profissional_id_profissional, estrelas_avaliacao, usuario_ip) VALUES (:id_profissional, :estrelas, :usuario_ip)"; // Atualizado para estrelas
            $stmt = $conn->prepare($insertQuery);
            $stmt->bindParam(':id_profissional', $id_profissional, PDO::PARAM_INT);
            $stmt->bindParam(':estrelas', $estrelas, PDO::PARAM_INT); // Atualizado para estrelas
            $stmt->bindParam(':usuario_ip', $usuario_ip, PDO::PARAM_STR);
            $stmt->execute();

            // Mensagem de sucesso para nova avaliação
            header("Location: perfilunico.php?id=$id_profissional&mensagem=" . urlencode("Avaliação enviada com sucesso!"));
        }
        exit;
    } catch (PDOException $e) {
        // Redirecionar com uma mensagem de erro
        header("Location: perfilunico.php?id=$id_profissional&mensagem=" . urlencode("Erro ao enviar avaliação. Tente novamente."));
        exit;
    }
} else {
    // Redireciona se o método não for POST
    header("Location: perfilunico.php?id=" . $_GET['id_profissional'] . "&mensagem=" . urlencode("Erro: Método inválido."));
    exit;
}
?>
