<?php
include 'db.php'; 

$filter = $_GET['filter'] ?? '';
$query = $_GET['query'] ?? '';

if ($filter === 'name') {
    $sql = "SELECT id_profissional, nome_profissional FROM profissional WHERE nome_profissional LIKE :query LIMIT 10";
} else {
    $sql = "SELECT nome_profissao FROM profissoes WHERE nome_profissao LIKE :query LIMIT 10";
}

try {
    $stmt = $conn->prepare($sql); 
    $stmt->bindValue(':query', '%' . $query . '%');
    $stmt->execute(); 

    // Obtém os resultados e retorna como JSON
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao buscar: ' . $e->getMessage()]);
}
?>
