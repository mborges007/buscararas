<?php
include 'db.php';

// Verificar se a variável 'id_area' está definida e é um número
if (isset($_GET['id_area']) && is_numeric($_GET['id_area'])) {
    $id_area = (int) $_GET['id_area']; // Garantir que seja um inteiro
    
    try {
        // Consulta para obter profissões relacionadas à área
        $sql = "SELECT * FROM profissoes WHERE fk_departamentos_id_area = :id_area";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
        $stmt->execute();

        // Verificar se há resultados
        $profissoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Retornar as profissões em formato JSON
        echo json_encode($profissoes);
    } catch (PDOException $e) {
        // Tratar erro de consulta ao banco de dados
        echo json_encode(['error' => 'Erro ao buscar as profissões: ' . $e->getMessage()]);
    }
} else {
    // Se 'id_area' não for válido, retornar um erro
    echo json_encode(['error' => 'ID da área inválido']);
}
?>
