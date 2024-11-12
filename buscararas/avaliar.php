<?php
// Incluir a conexão com o banco de dados
include('db.php'); // Certifique-se de que o caminho esteja correto

// Verificar se os dados foram enviados pelo formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar os dados do formulário
    $id_profissional = $_POST['id_profissional'];
    $usuario_ip = $_POST['usuario_ip'];
    $estrelas = $_POST['estrelas'];

    // Validar os dados (por exemplo, garantir que as estrelas estejam entre 1 e 5)
    if ($estrelas < 1 || $estrelas > 5) {
        echo "Erro: A nota deve ser entre 1 e 5.";
        exit;
    }

    // Conectar ao banco usando PDO
    try {
        // Verificar se o usuário já avaliou este profissional (evitar múltiplos votos do mesmo IP)
        $stmt_check = $conn->prepare("SELECT * FROM avaliacao WHERE fk_profissional_id_profissional = :id_profissional AND usuario_ip = :usuario_ip");
        $stmt_check->bindParam(':id_profissional', $id_profissional);
        $stmt_check->bindParam(':usuario_ip', $usuario_ip);
        $stmt_check->execute();

        // Se o usuário já avaliou, então atualiza a avaliação
        if ($stmt_check->rowCount() > 0) {
            // Atualizar a avaliação existente
            $stmt_update = $conn->prepare("UPDATE avaliacao SET estrelas_avaliacao = :estrelas WHERE fk_profissional_id_profissional = :id_profissional AND usuario_ip = :usuario_ip");
            $stmt_update->bindParam(':id_profissional', $id_profissional);
            $stmt_update->bindParam(':usuario_ip', $usuario_ip);
            $stmt_update->bindParam(':estrelas', $estrelas);

            if ($stmt_update->execute()) {
                echo "<script>
                    window.onload = function() { 
                        alert('Avaliação atualizada com sucesso!');
                        window.location.href = 'perfilunico.php?id=" . $id_profissional . "';
                    }
                </script>";
            } else {
                echo "<script>
                    window.onload = function() { 
                        alert('Erro ao atualizar avaliação.');
                    }
                </script>";
            }
        } else {
            // Inserir nova avaliação
            $stmt_insert = $conn->prepare("INSERT INTO avaliacao (fk_profissional_id_profissional, usuario_ip, estrelas_avaliacao) VALUES (:id_profissional, :usuario_ip, :estrelas)");
            $stmt_insert->bindParam(':id_profissional', $id_profissional);
            $stmt_insert->bindParam(':usuario_ip', $usuario_ip);
            $stmt_insert->bindParam(':estrelas', $estrelas);

            // Verificar se a inserção foi bem-sucedida
            if ($stmt_insert->execute()) {
                echo "<script>
                    window.onload = function() { 
                        alert('Avaliação enviada com sucesso!');
                        window.location.href = 'perfilunico.php?id=" . $id_profissional . "';
                    }
                </script>";
            } else {
                echo "<script>
                    window.onload = function() { 
                        alert('Erro ao enviar avaliação.');
                    }
                </script>";
            }
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>
