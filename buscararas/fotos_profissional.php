<?php

require_once 'db.php';
session_start();  // Inicia a sessão

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['id_profissional'])) {
        $usuario_id = $_SESSION['id_profissional'];  
    } else {
        $error_message = "Você precisa estar logado para fazer isso.";
    }

    // arquivo foi enviado
    if (isset($_FILES['foto_profissional']) && $_FILES['foto_profissional']['error'] == 0) {
        $arquivo_tmp = $_FILES['foto_profissional']['tmp_name'];
        $nome_arquivo = basename($_FILES['foto_profissional']['name']);
        $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));

        //tipo MIME da imagem
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tipo_arquivo = finfo_file($finfo, $arquivo_tmp);
        finfo_close($finfo);
        
        // tipos de img permitidos
        $tipos_permitidos = ['image/jpeg', 'image/png'];
        
        if (in_array($tipo_arquivo, $tipos_permitidos)) {
            // extensões permitidas
            $extensoes_permitidas = ['jpg', 'jpeg', 'png'];
            if (in_array($extensao, $extensoes_permitidas)) {                
                if ($_FILES['foto_profissional']['size'] <= 5 * 1024 * 1024) {// tamanho do arquivo (5MB máximo)
                    //caminhio final do arquivo
                    $diretorio_upload = 'uploads/fotos_profissionais/';
                    
                    //diretório existe senao cria
                    if (!is_dir($diretorio_upload)) {
                        mkdir($diretorio_upload, 0777, true);
                    }

                    $caminho_foto = $diretorio_upload . uniqid() . '.' . $extensao;

                    // arqui para de uploads
                    if (move_uploaded_file($arquivo_tmp, $caminho_foto)) {
                        $inserir_foto_query = "INSERT INTO fotos_profissionais (caminho_foto, fk_profissional_id_profissional) VALUES (:caminho_foto, :id_profissional)";
                        $stmt = $conn->prepare($inserir_foto_query);
                        $stmt->bindParam(':caminho_foto', $caminho_foto);
                        $stmt->bindParam(':id_profissional', $usuario_id);
                        
                        if ($stmt->execute()) {
                            //  página 'meuperfil.php' se deu certo
                            header("Location: meuperfil.php");
                            exit(); 
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

    if (isset($error_message)) {
        echo "<p style='color:red;'>$error_message</p>";
    }
}

?>
