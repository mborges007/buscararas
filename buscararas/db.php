<?php
$host = 'busca.mysql.database.azure.com'; // Nome do servidor do banco de dados no Azure
$db = 'busca'; // Nome do banco de dados (substitua conforme necessário)
$user = 'kfzftclrbr'; // Nome de usuário
$pass = 'xRZCifXw5$Ba3SY9'; // Senha do banco de dados (substitua pela sua senha real)

// Configuração de SSL
$options = [
    PDO::MYSQL_ATTR_SSL_CA => '/caminho/para/certificado.pem' // Caminho para o certificado SSL
];

try {
    // Criando a conexão PDO com SSL
    $conn = new PDO("mysql:host=$host;dbname=$db;port=3306;charset=utf8", $user, $pass, $options);
    
    // Definindo o modo de erro do PDO para exceções
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    echo "Conexão bem-sucedida!";
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
?>
