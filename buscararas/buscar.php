<?php
include 'db.php'; // Incluindo a conexão com o banco de dados

// Obtém os parâmetros de filtro e consulta da URL
$filter = $_GET['filter'] ?? '';
$query = $_GET['query'] ?? '';

if ($filter === 'name') {
    // Busca por nome de profissional
    $sql = "SELECT id_profissional, nome_profissional FROM profissional WHERE nome_profissional LIKE :query LIMIT 10";
} else {
    // Busca por nome de profissão
    $sql = "SELECT nome_profissao FROM profissoes WHERE nome_profissao LIKE :query LIMIT 10";
}

try {
    $stmt = $conn->prepare($sql); // Prepara a consulta
    $stmt->bindValue(':query', '%' . $query . '%'); // Associa o valor da consulta
    $stmt->execute(); // Executa a consulta

    // Obtém os resultados e retorna como JSON
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results); // Retorna os resultados em formato JSON
} catch (PDOException $e) {
    // Caso haja erro, exibe a mensagem de erro
    echo json_encode(['error' => 'Erro ao buscar: ' . $e->getMessage()]);
}
?>
