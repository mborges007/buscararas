<?php
include 'db.php'; // Incluindo o arquivo de conexão

// Consultar as profissões agrupadas por departamentos
$query = "SELECT p.nome_profissao, d.nome_area 
          FROM profissoes p 
          JOIN departamentos d ON p.fk_departamentos_id_area = d.id_area";

try {
    // Preparando e executando a consulta
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Fetching the results
    $profissoes = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $profissoes[$row['nome_area']][] = $row['nome_profissao'];
    }

} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
}
?>