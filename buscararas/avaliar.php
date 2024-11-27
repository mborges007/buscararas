<?php
include('db.php');

session_start();


if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

    echo "<script>
        alert('Você precisa estar logado para avaliar!');
        window.location.href = 'login_usuario.php'; // Redireciona para a página de login
    </script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_profissional = intval($_POST['id_profissional']);
    $id_usuario = intval($_SESSION['id_usuario']);
    $estrelas_avaliacao = intval($_POST['estrelas_avaliacao']);

    if ($estrelas_avaliacao < 1 || $estrelas_avaliacao > 5) {
        echo "<script>
            alert('Erro: A nota deve ser entre 1 e 5.');
            history.back(); // Retorna para a página anterior
        </script>";
        exit;
    }

    try {
        // o usuário já avaliou este prof
        $stmt_check = $conn->prepare("SELECT id_avaliacao FROM avaliacao WHERE fk_profissional_id_profissional = :id_profissional AND fk_usuario_id_usuario = :id_usuario");
        $stmt_check->bindParam(':id_profissional', $id_profissional, PDO::PARAM_INT);
        $stmt_check->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            // att a avaliação ja feita
            $stmt_update = $conn->prepare("UPDATE avaliacao SET estrelas_avaliacao = :estrelas_avaliacao, data_avaliacao = NOW() WHERE fk_profissional_id_profissional = :id_profissional AND fk_usuario_id_usuario = :id_usuario");
            $stmt_update->bindParam(':id_profissional', $id_profissional, PDO::PARAM_INT);
            $stmt_update->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_update->bindParam(':estrelas_avaliacao', $estrelas_avaliacao, PDO::PARAM_INT);

            if ($stmt_update->execute()) {
                echo "<script>
                    alert('Sua avaliação foi atualizada com sucesso!');
                    window.location.href = 'perfilunico.php?id=" . $id_profissional . "'; 
                </script>";
            } else {
                echo "<script>
                    alert('Erro ao atualizar a avaliação. Tente novamente mais tarde.');
                    history.back(); // Retorna para a página anterior
                </script>";
            }
        } else {
            // nova avaliação
            $stmt_insert = $conn->prepare("INSERT INTO avaliacao (fk_profissional_id_profissional, fk_usuario_id_usuario, estrelas_avaliacao, data_avaliacao) VALUES (:id_profissional, :id_usuario, :estrelas_avaliacao, NOW())");
            $stmt_insert->bindParam(':id_profissional', $id_profissional, PDO::PARAM_INT);
            $stmt_insert->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_insert->bindParam(':estrelas_avaliacao', $estrelas_avaliacao, PDO::PARAM_INT);

            if ($stmt_insert->execute()) {
                echo "<script>
                    alert('Avaliação enviada com sucesso!');
                    window.location.href = 'perfilunico.php?id=" . $id_profissional . "'; 
                </script>";
            } else {
                echo "<script>
                    alert('Erro ao enviar a avaliação. Tente novamente mais tarde.');
                    history.back(); // Retorna para a página anterior
                </script>";
            }
        }
    } catch (PDOException $e) {
        echo "<script>
            alert('Erro no servidor: " . addslashes($e->getMessage()) . "');
            history.back(); // Retorna para a página anterior
        </script>";
    }
}
?>