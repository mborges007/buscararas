<?php
$host = 'localhost'; // ou o endereço do seu servidor de banco de dados
$db = 'busca'; // substitua pelo nome do seu banco de dados
$user = 'root'; // substitua pelo seu usuário do banco de dados
$pass = ''; // substitua pela sua senha do banco de dados

try {
    // Criando a conexão PDO de forma única
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    
    // Definindo o modo de erro do PDO para exceções
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Definindo o modo de busca dos dados
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
?>
