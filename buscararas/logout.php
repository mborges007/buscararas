<?php
session_start(); // Inicia a sessão

// Remove todas as variáveis da sessão
$_SESSION = [];

// Se você deseja destruir a sessão completamente, também deve chamar session_destroy()
session_destroy();

// Redireciona o usuário para a página de login ou outra página desejada
header("Location: login.html");
exit;
?>
