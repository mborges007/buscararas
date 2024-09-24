<?php
include 'db.php';

if (isset($_GET['id_area'])) {
    $id_area = $_GET['id_area'];
    
    // Consulta para obter profissões relacionadas à área
    $sql = "SELECT * FROM profissoes WHERE fk_departamentos_id_area = :id_area";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_area', $id_area);
    $stmt->execute();

    $profissoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Retorna as profissões em formato JSON
    echo json_encode($profissoes);
}
?>
