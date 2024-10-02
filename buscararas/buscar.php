<?php
include 'db.php'; // Inclua seu arquivo de conexão

$filter = $_GET['filter'] ?? '';
$query = $_GET['query'] ?? '';

// Verifique se o filtro é válido
if ($filter === 'name') {
    $sql = "SELECT id_profissional, nome_profissional FROM profissional WHERE nome_profissional LIKE ?";
} elseif ($filter === 'profession') {
    $sql = "SELECT nome_profissao FROM profissoes WHERE nome_profissao LIKE ?";
} else {
    echo json_encode([]);
    exit;
}

// Prepare e execute a consulta
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['%' . $query . '%']);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Caso ocorra algum erro, retorne uma mensagem de erro
    echo json_encode(['error' => 'Erro ao buscar opções: ' . $e->getMessage()]);
    exit;
}

// Defina o cabeçalho para JSON e retorne os dados
header('Content-Type: application/json');
echo json_encode($result);




?>
