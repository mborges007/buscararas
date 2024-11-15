<?php
session_start();
include 'db.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['id_profissional'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver autenticado
    exit;
}

// Obtendo o ID do profissional da sessão
$profissional_id = $_SESSION['id_profissional']; // Usando o id correto da sessão

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                        $stmt->bindParam(':id_profissional', $profissional_id); // Usando a variável correta para o ID do profissional
                        
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

try {
    // Consulta SQL para obter as informações do profissional
    $sql = "SELECT p.nome_profissional, p.tel_profissional, p.descricao_profissional, p.email_profissional,
                   pr.nome_profissao,
                   d.nome_area
            FROM profissional p
            JOIN profissoes pr ON pr.id_profissao = p.fk_profissoes_id_profissao
            JOIN departamentos d ON d.id_area = p.fk_departamentos_id_area
            WHERE p.id_profissional = :id_profissional";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_profissional', $profissional_id, PDO::PARAM_INT);
    $stmt->execute();

    // Verifica se o profissional foi encontrado
    if ($stmt->rowCount() > 0) {
        $profissional = $stmt->fetch(PDO::FETCH_ASSOC); // Pega o resultado como array associativo
    } else {
        echo "Profissional não encontrado!";
        exit;
    }
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
}


// Consulta para obter as fotos enviadas pelo profissional
$sql_fotos = "SELECT * FROM fotos_profissionais WHERE fk_profissional_id_profissional = :id_profissional";
$stmt_fotos = $conn->prepare($sql_fotos);
$stmt_fotos->bindParam(':id_profissional', $profissional_id, PDO::PARAM_INT);
$stmt_fotos->execute();

// Verifica se há fotos enviadas
$fotos = $stmt_fotos->fetchAll(PDO::FETCH_ASSOC);




?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuscAraras - Meu Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar d-flex flex-column p-3">
        <h1 class="text-light text-left">BuscAraras</h1>
        <a class="btn btn-light no-border mb-2 tamanho" href="index.php">Início</a>
        <h3 class="text-danger text-left">Meu Perfil</h3>
        <div class="mt-auto">
            <a class="btn btn-primary w-100 mb-2" href="editar_perfil.php">Alterar</a>
            <a class="btn btn-secondary w-100" href="logout.php">Sair</a>
        </div>
    </div>
          
    <div class="content d-flex flex-column align-items-center flex-grow-1">
        <h1>Meu Perfil</h1>
        
        <div class="card form-card w-100 p-4 perfil-container">
            <div class="row align-items-center mb-4">
                <div class="col-md-4 text-center">
                    <img src="img/luka.jpg" alt="Foto do Profissional" class="img-fluid rounded-circle perfil-photo">
                </div>
                <div class="col-md-8 perfil-info">
                    <h4 class="text-center"><?php echo htmlspecialchars($profissional['nome_profissional']); ?></h4>
                    <p><strong>Telefone:</strong> <?php echo htmlspecialchars($profissional['tel_profissional']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($profissional['email_profissional']); ?></p>
                    <p><strong>Profissão:</strong> <?php echo htmlspecialchars($profissional['nome_profissao']); ?></p>
                    <p><strong>Departamento:</strong> <?php echo htmlspecialchars($profissional['nome_area']); ?></p>
                    <h5>Descrição</h5>
                    <p><?php echo htmlspecialchars($profissional['descricao_profissional']); ?></p>
                </div>
                <form action="meuperfil.php" method="POST" enctype="multipart/form-data">
                <!-- Campo para upload de fotos -->
                <div class="form-group">
                    <label for="foto">Enviar Foto</label>
                    <input type="file" class="form-control" id="foto" name="foto_profissional">
                    <small class="form-text text-muted">Você pode enviar uma imagem no formato JPG, JPEG ou PNG.</small>
                </div>
                
                <div class="btn-container">
                    <button type="submit"class="btn btn-danger btn-sm btn-block">Enviar</button>
                </div>
            </form>
            
            </div>
            <?php if ($fotos): ?>
    <h5>Fotos enviadas</h5>
    <div class="row">
        <?php foreach ($fotos as $foto): ?>
            <div class="col-md-4 mb-3">
                <img src="<?php echo htmlspecialchars($foto['caminho_foto']); ?>" class="img-fluid rounded mb-2" alt="Foto do Profissional">
                <form action="deletar_foto.php" method="POST">
                    <input type="hidden" name="foto_id" value="<?php echo $id_foto; ?>"> <!-- A ID da foto a ser deletada -->
                    <button type="submit" class="btn btn-danger btn-sm" style="border-radius:20px; background-color:#BF4341;" >Deletar</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Você não enviou nenhuma foto ainda.</p>
<?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
