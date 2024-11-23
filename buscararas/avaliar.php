<?php
// Incluir a conexão com o banco de dados
include('db.php');

// Iniciar a sessão para verificar login
session_start();


if (!isset($_SESSION['id_usuario'])) {
    // Armazenar a URL de onde o usuário veio (página de avaliação)
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

    echo "<script>
        alert('Você precisa estar logado para avaliar!');
        window.location.href = 'login_usuario.php'; // Redireciona para a página de login
    </script>";
    exit;
}

// Verificar se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar os dados do formulário
    $id_profissional = intval($_POST['id_profissional']);
    $id_usuario = intval($_SESSION['id_usuario']);
    $estrelas_avaliacao = intval($_POST['estrelas_avaliacao']);

    // Validar as estrelas (entre 1 e 5)
    if ($estrelas_avaliacao < 1 || $estrelas_avaliacao > 5) {
        echo "<script>
            alert('Erro: A nota deve ser entre 1 e 5.');
            history.back(); // Retorna para a página anterior
        </script>";
        exit;
    }

    // Conectar ao banco de dados usando PDO
    try {
        // Verificar se o usuário já avaliou este profissional
        $stmt_check = $conn->prepare("SELECT id_avaliacao FROM avaliacao WHERE fk_profissional_id_profissional = :id_profissional AND fk_usuario_id_usuario = :id_usuario");
        $stmt_check->bindParam(':id_profissional', $id_profissional, PDO::PARAM_INT);
        $stmt_check->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            // Atualizar a avaliação existente
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
            // Inserir uma nova avaliação
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
        // Exibir erro de forma segura
        echo "<script>
            alert('Erro no servidor: " . addslashes($e->getMessage()) . "');
            history.back(); // Retorna para a página anterior
        </script>";
    }
}
?>