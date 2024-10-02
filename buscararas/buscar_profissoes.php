<?php
include 'db.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'] . '%'; // Usar % para o LIKE

    try {
        $stmt = $conn->prepare("SELECT id_profissao, nome_profissao FROM profissoes WHERE nome_profissao LIKE :query");
        $stmt->bindParam(':query', $query);
        $stmt->execute();

        $profissoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($profissoes);
    } catch (PDOException $e) {
        echo json_encode([]);
    }
}
?>
