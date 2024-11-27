<?php
include 'db.php';

// Verificar se a variável 'id_area' está definida e é um número
if (isset($_GET['id_area']) && is_numeric($_GET['id_area'])) {
    $id_area = (int) $_GET['id_area']; // Garantir que seja um inteiro
    
    try {
       
        $sql = "SELECT * FROM profissoes WHERE fk_departamentos_id_area = :id_area";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
        $stmt->execute();

        // Verificar se há resultados
        $profissoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
        echo json_encode($profissoes);
    } catch (PDOException $e) {
        
        echo json_encode(['error' => 'Erro ao buscar as profissões: ' . $e->getMessage()]);
    }
} else {
    
    echo json_encode(['error' => 'ID da área inválido']);
}
?>
