<?php
include 'db.php';

$query = "SELECT id_profissional, nome_profissional FROM profissional";
$stmt = $conn->prepare($query);
$stmt->execute();
$nomes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($nomes);
?>
