<?php
include 'db.php'; // Incluindo o arquivo de conexão

$query = '';
$params = [];

// Verifica se a query e o filtro foram passados pela URL
if (isset($_GET['query']) && isset($_GET['filter'])) {
    $searchQuery = $_GET['query'];
    $filter = $_GET['filter'];

    if ($filter === 'name') {
        $query = "SELECT p.id, p.nome_profissional AS nome 
                  FROM profissional p 
                  WHERE p.nome_profissional LIKE :searchQuery";
        $params[':searchQuery'] = $searchQuery . '%'; // Busca profissionais que começam com a string
    } elseif ($filter === 'profession') {
        $query = "SELECT pr.id, pr.nome_profissao AS nome 
                  FROM profissoes pr 
                  WHERE pr.nome_profissao LIKE :searchQuery";
        $params[':searchQuery'] = $searchQuery . '%'; // Busca profissões que começam com a string
    }
}

try {
    // Preparando e executando a consulta
    if ($query) {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);

        // Fetching the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results); // Retorna os resultados como JSON
    } else {
        echo json_encode([]); // Retorna um array vazio se não houver consulta
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]); // Retorna um erro se ocorrer
}
?>
