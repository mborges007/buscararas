<?php
$host = 'busca.mysql.database.azure.com'; // Nome do servidor
$db = 'busca'; // Nome do banco de dados
$user = 'kfzftclrbr'; // Nome de usuário
$pass = 'GremiO@10'; // Senha do banco de dados

// Caminho para o certificado SSL
$certPath = 'C:/xampp/htdocs/DigiCertGlobalRootG2.crt'; // Certificado SSL no Windows

$options = [
    PDO::MYSQL_ATTR_SSL_CA => $certPath, // Configuração de SSL para PDO
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Habilitar exceções
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Modo de busca padrão
];

try {
    // Conexão usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$db;port=3306;charset=utf8", $user, $pass, $options);
    echo "Conexão bem-sucedida com PDO!";
} catch (PDOException $e) {
    echo "Erro na conexão PDO: " . $e->getMessage();
}

// Conexão usando mysqli
$connMysqli = mysqli_init();

// Configuração SSL para mysqli
mysqli_ssl_set($connMysqli, NULL, NULL, $certPath, NULL, NULL);

// Tentativa de conexão
if (!mysqli_real_connect($connMysqli, $host, $user, $pass, $db, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die('Erro na conexão mysqli: ' . mysqli_connect_error());
} else {
    echo "Conexão bem-sucedida com mysqli!";
}

// Fechar conexões
$connMysqli->close();
?>
