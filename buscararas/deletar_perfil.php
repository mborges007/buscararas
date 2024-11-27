<?php
session_start();
include 'db.php'; 

// usuario está logado
if (!isset($_SESSION['id_profissional'])) {
    header('Location: login.php'); // pagina de login se não estiver logado
    exit();
}

$usuario_id = $_SESSION['id_profissional'];

$query = "DELETE FROM profissional WHERE id_profissional = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $usuario_id);

if ($stmt->execute()) {
    session_destroy();
    header('Location: index.php'); 
    exit();
} else {
    // Caso ocorra algum erro, redireciona de volta para a página de edição de perfil
    header('Location: editar_perfil.php?error=1');
    exit();
}
?>
