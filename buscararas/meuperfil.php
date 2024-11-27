<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id_profissional'])) {
    header("Location: login.php");
    exit;
}

$profissional_id = $_SESSION['id_profissional'];


// Função para verificar e salvar imagens no servidor
function salvarImagem($arquivo, $diretorio, $tabela, $coluna, $profissional_id, $conn) {
    // verificação se arquivo foi enviado
    if (empty($arquivo['name'])) {
        return "Nenhuma imagem foi selecionada para envio. Por favor, escolha um arquivo.";
    }

    $extensoes_permitidas = ['jpg', 'jpeg', 'png'];
    $tipos_permitidos = ['image/jpeg', 'image/png'];

    $arquivo_tmp = $arquivo['tmp_name'];
    $nome_arquivo = basename($arquivo['name']);
    $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $tipo_arquivo = finfo_file($finfo, $arquivo_tmp);
    finfo_close($finfo);

    if (!in_array($tipo_arquivo, $tipos_permitidos) || !in_array($extensao, $extensoes_permitidas)) {
        return "Formato inválido. Por favor, envie um arquivo nos formatos JPG, JPEG ou PNG.";
    }

    if ($arquivo['size'] > 5 * 1024 * 1024) {
        return "O arquivo excede o tamanho máximo de 5MB. Por favor, envie um arquivo menor.";
    }

    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    $caminho_foto = $diretorio . uniqid() . '.' . $extensao;

    if (move_uploaded_file($arquivo_tmp, $caminho_foto)) {
        $sql = "INSERT INTO $tabela ($coluna, fk_profissional_id_profissional) VALUES (:caminho_foto, :id_profissional)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':caminho_foto', $caminho_foto);
        $stmt->bindParam(':id_profissional', $profissional_id);

        if ($stmt->execute()) {
            return "Imagem salva com sucesso! Sua imagem foi enviada e está visível no seu perfil.";
        } else {
            return "Houve um erro ao salvar a imagem no banco de dados. Tente novamente.";
        }
    }

    return "Erro ao mover a imagem para o diretório de destino. Tente novamente.";
}


//  upload de foto de perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['perfilFoto'])) {
    $mensagem_perfil = salvarImagem($_FILES['perfilFoto'], 'uploads/fotos_perfil/', 'fotos_perfil', 'caminho_foto_perfil', $profissional_id, $conn);
}

//  upload de fotos para a galeria
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto_profissional'])) {
    $mensagem_galeria = salvarImagem($_FILES['foto_profissional'], 'uploads/fotos_profissionais/', 'fotos_profissionais', 'caminho_foto', $profissional_id, $conn);
}

// informações do prof
try {
    $sql = "SELECT p.nome_profissional, p.tel_profissional, p.descricao_profissional, p.email_profissional,
                   pr.nome_profissao, d.nome_area
            FROM profissional p
            JOIN profissoes pr ON pr.id_profissao = p.fk_profissoes_id_profissao
            JOIN departamentos d ON d.id_area = p.fk_departamentos_id_area
            WHERE p.id_profissional = :id_profissional";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_profissional', $profissional_id, PDO::PARAM_INT);
    $stmt->execute();
    $profissional = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profissional) {
        echo "Profissional não encontrado.";
        exit;
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

// puxando foto profissional
$sql_foto_perfil = "SELECT caminho_foto_perfil FROM fotos_perfil 
                    WHERE fk_profissional_id_profissional = :id_profissional
                    ORDER BY id_foto_perfil DESC LIMIT 1";
$stmt_foto_perfil = $conn->prepare($sql_foto_perfil);
$stmt_foto_perfil->bindParam(':id_profissional', $profissional_id, PDO::PARAM_INT);
$stmt_foto_perfil->execute();
$foto_perfil = $stmt_foto_perfil->fetch(PDO::FETCH_ASSOC);
$fotoPerfilCaminho = $foto_perfil ? htmlspecialchars($foto_perfil['caminho_foto_perfil']) : 'img/default_profile.jpg';

// puxando fotos da galeria
$sql_fotos_galeria = "SELECT id_foto, caminho_foto FROM fotos_profissionais WHERE fk_profissional_id_profissional = :id_profissional";
$stmt_fotos_galeria = $conn->prepare($sql_fotos_galeria);
$stmt_fotos_galeria->bindParam(':id_profissional', $profissional_id, PDO::PARAM_INT);
$stmt_fotos_galeria->execute();
$fotos_galeria = $stmt_fotos_galeria->fetchAll(PDO::FETCH_ASSOC);




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
    <style>
        body {
            background-image: url('img/lupa.svg');
            background-repeat: repeat;
            background-size: 30px 30px;
        }
    </style>
</head>
<body>
    <div class="sidebar d-flex flex-column p-3">
    <h1 class="text-light text-left" style="color:#F5F5E6; display: flex; align-items: center;">
                    <span class="text" style="color: #F5F5E6;"> Busc</span><span class="text" style="color: #BF4341;">Araras</span>
                        <img src="img/lupasidebar.svg" alt="Lupa" style="width: 25px; height: 25px; margin-right: -5px; margin-top:9px;">                
            </h1>
        <a class="btn btn-light no-border mb-2 tamanho" href="index.php">Início</a>
        <h3 class="text-danger text-left">Meu Perfil</h3>
        <div class="mt-auto">
            <a class="btn btn-primary w-100 mb-2" href="editar_perfil.php">Editar Perfil</a>
            <a class="btn btn-secondary w-100" href="logout.php">Sair</a>
        </div>
    </div>
          
    <div class="content d-flex flex-column align-items-center flex-grow-1">
    <h1>Meu Perfil</h1>

    <div class="card form-card w-100 p-4 perfil-container">
        <div class="row align-items-center mb-4">
            <div class="col-md-4 text-center">
                <img src="<?php echo isset($fotoPerfilCaminho) && !empty($fotoPerfilCaminho) ? $fotoPerfilCaminho : 'img/perfilpadrao.jpg'; ?>" 
                     alt="Foto do Profissional" 
                     class="img-fluid rounded-circle perfil-photo">

                <button type="button" style="width: 49%;" class="btn btn-secondary  saltando mt-3" onclick="mostrarFormulario()">Alterar Foto Perfil</button>

                <form action="meuperfil.php" method="POST" enctype="multipart/form-data" class="mt-3" id="formFoto" style="display: none;">
                    <div class="form-group">
                        <label for="perfilFoto" class="form-label">Alterar Foto Perfil</label>
                        <input type="file" class="form-control" id="perfilFoto" name="perfilFoto" required>
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm btn-block">Enviar</button>
                </form>
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

  



    
              <hr>
    </br>
                    <form action="meuperfil.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="foto">Enviar Foto de trabalhos anteriores</label>
                            <input type="file" class="form-control" id="foto" name="foto_profissional">
                            <small class="form-text text-muted">Você pode enviar uma imagem no formato JPG, JPEG ou PNG.</small>
                        </div>

                        <div class="btn-container">
                            <button type="submit" class="btn btn-danger btn-sm btn-block">Enviar trabalhos</button>
                        </div>
                    </form>

                    <?php if (!empty($fotos_galeria)): ?>
                        <h5>Fotos enviadas</h5>
                        <div class="row">
                            <?php foreach ($fotos_galeria as $foto): ?>
                                <div class="col-md-4 mb-3">
                                    <img src="<?php echo htmlspecialchars($foto['caminho_foto']); ?>" class="img-fluid rounded mb-2" alt="Foto do Profissional">
                                    <form action="deletar_foto.php" method="POST">
                                    <input type="hidden" name="id_foto" value="<?php echo htmlspecialchars($foto['id_foto']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" style="border-radius:20px; background-color:#BF4341;">Deletar</button>
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

    <script>
    function mostrarFormulario() {
    var form = document.getElementById('formFoto');
    form.style.display = 'block'; 
    }

</script>
</body>
</html>
