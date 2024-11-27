<?php
session_start();
include 'db.php';


if (!isset($_SESSION['id_profissional'])) {
    header("Location: login.php"); // página de login se não estiver aut
    exit;
}


//id do profissional logado
$id_profissional_logado = $_SESSION['id_profissional'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_foto'])) {
    $id_foto = $_POST['id_foto'];  // id foto a ser deletada

    try {
        $sql_foto = "SELECT caminho_foto, fk_profissional_id_profissional FROM fotos_profissionais WHERE id_foto = :id_foto";
        $stmt_foto = $conn->prepare($sql_foto);
        $stmt_foto->bindParam(':id_foto', $id_foto, PDO::PARAM_INT);
        $stmt_foto->execute();

        if ($stmt_foto->rowCount() > 0) {
            // Obter o caminho da foto e o ID do profissional
            $foto = $stmt_foto->fetch(PDO::FETCH_ASSOC);
            $caminho_foto = $foto['caminho_foto'];
            $foto_id_profissional = $foto['fk_profissional_id_profissional'];

            // foto pertence ao prof logado
            if ($foto_id_profissional == $id_profissional_logado) {
                if (file_exists($caminho_foto)) {
                    unlink($caminho_foto); // deleta o arquivo
                }

                $sql_deletar_foto = "DELETE FROM fotos_profissionais WHERE id_foto = :id_foto";
                $stmt_deletar_foto = $conn->prepare($sql_deletar_foto);
                $stmt_deletar_foto->bindParam(':id_foto', $id_foto, PDO::PARAM_INT);
                $stmt_deletar_foto->execute();

                // Redireciona para atualizar a página sem a foto deletada
                header("Location: meuperfil.php");
                exit();
            } else {
                //não pertença ao profissional logado
                echo "Você não tem permissão para excluir esta foto.";
            }
        } else {
            // não seja encontrada
            echo "Foto não encontrada!";
        }
    } catch (PDOException $e) {
        echo "Erro ao tentar excluir a foto: " . $e->getMessage();
    }
}



?>
