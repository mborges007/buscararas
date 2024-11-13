<?php

require_once 'db.php';
session_start();  // Inicia a sessão

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se o usuário está logado
    if (isset($_SESSION['id_profissional'])) {
        $usuario_id = $_SESSION['id_profissional'];  // Recupera o ID do usuário da sessão
    } else {
        $error_message = "Você precisa estar logado para fazer isso.";
    }

    // Verifica se o arquivo foi enviado
    if (isset($_FILES['foto_profissional']) && $_FILES['foto_profissional']['error'] == 0) {
        $arquivo_tmp = $_FILES['foto_profissional']['tmp_name'];
        $nome_arquivo = basename($_FILES['foto_profissional']['name']);
        $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));

        // Verifica o tipo MIME da imagem
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tipo_arquivo = finfo_file($finfo, $arquivo_tmp);
        finfo_close($finfo);
        
        // Tipos MIME permitidos
        $tipos_permitidos = ['image/jpeg', 'image/png'];
        
        if (in_array($tipo_arquivo, $tipos_permitidos)) {
            // Define as extensões permitidas
            $extensoes_permitidas = ['jpg', 'jpeg', 'png'];
            if (in_array($extensao, $extensoes_permitidas)) {
                // Verifica o tamanho do arquivo (5MB máximo)
                if ($_FILES['foto_profissional']['size'] <= 5 * 1024 * 1024) {
                    // Define o diretório e nome final do arquivo
                    $diretorio_upload = 'uploads/fotos_profissionais/';
                    
                    // Verifica se o diretório existe, senão, cria
                    if (!is_dir($diretorio_upload)) {
                        mkdir($diretorio_upload, 0777, true);
                    }

                    $caminho_foto = $diretorio_upload . uniqid() . '.' . $extensao;

                    // Move o arquivo para o diretório de uploads
                    if (move_uploaded_file($arquivo_tmp, $caminho_foto)) {
                        // Insere a foto no banco de dados
                        $inserir_foto_query = "INSERT INTO fotos_profissionais (caminho_foto, fk_profissional_id_profissional) VALUES (:caminho_foto, :id_profissional)";
                        $stmt = $conn->prepare($inserir_foto_query);
                        $stmt->bindParam(':caminho_foto', $caminho_foto);
                        $stmt->bindParam(':id_profissional', $usuario_id);
                        
                        if ($stmt->execute()) {
                            // Redireciona para a página 'meuperfil.php' após o sucesso
                            header("Location: meuperfil.php");
                            exit(); // Garante que o script pare após o redirecionamento
                        } else {
                            $error_message = "Erro ao salvar a foto no banco de dados.";
                        }
                    } else {
                        $error_message = "Falha ao mover o arquivo de upload.";
                    }
                } else {
                    $error_message = "O arquivo é muito grande. O tamanho máximo permitido é 5MB.";
                }
            } else {
                $error_message = "Formato de arquivo não permitido. Use JPG, JPEG ou PNG.";
            }
        } else {
            $error_message = "O arquivo enviado não é uma imagem válida.";
        }
    } else {
        $error_message = "Nenhum arquivo foi enviado ou houve um erro no envio.";
    }

    // Exibe mensagens de erro, se houver
    if (isset($error_message)) {
        echo "<p style='color:red;'>$error_message</p>";
    }
}

?>
