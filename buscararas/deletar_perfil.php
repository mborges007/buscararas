<?php
session_start();
include 'db.php'; // Inclui a conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['id_profissional'])) {
    header('Location: login.php'); // Redireciona para a página de login se não estiver logado
    exit();
}

$usuario_id = $_SESSION['id_profissional'];

// Deletar o perfil do profissional
$query = "DELETE FROM profissional WHERE id_profissional = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $usuario_id);

// Executa a exclusão
if ($stmt->execute()) {
    // Destrua a sessão após a exclusão do perfil
    session_destroy();
    header('Location: index.php'); // Redireciona para a página inicial após excluir o perfil
    exit();
} else {
    // Caso ocorra algum erro, redireciona de volta para a página de edição de perfil
    header('Location: editar_perfil.php?error=1');
    exit();
}
?>
