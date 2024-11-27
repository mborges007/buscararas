<?php
$host = 'localhost'; 
$db = 'busca'; 
$user = 'root'; 
$pass = ''; 

try {
    
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Definindo o modo de busca dos dados
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
?>
