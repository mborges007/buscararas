<?php
session_start(); // Inicia a sessão para usar a variável $_SESSION

include 'db.php'; // Inclui a conexão com o banco de dados

// Verifique se o usuário está logado
if (!isset($_SESSION['id_profissional'])) {
    header('Location: login.php'); // Redireciona para a página de login se não estiver logado
    exit();
}

$usuario_id = $_SESSION['id_profissional']; 

// Consultar os dados do usuário logado
$query = "SELECT profissional.*, departamentos.nome_area, profissoes.nome_profissao 
          FROM profissional 
          LEFT JOIN departamentos ON profissional.fk_departamentos_id_area = departamentos.id_area 
          LEFT JOIN profissoes ON profissional.fk_profissoes_id_profissao = profissoes.id_profissao 
          WHERE id_profissional = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $usuario_id);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Consultar departamentos e profissões (usado nos selects)
$departamentos = $conn->query("SELECT * FROM departamentos")->fetchAll(PDO::FETCH_ASSOC);
$profissoes = $conn->query("SELECT * FROM profissoes")->fetchAll(PDO::FETCH_ASSOC);

// Lógica de atualização de perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome_profissional']);
    $email = trim($_POST['email_profissional']);
    $telefone = trim($_POST['tel_profissional']);
    $descricao = trim($_POST['descricao_profissional']);
    $senha = trim($_POST['senha_profissional']);
    $area_atuacao = $_POST['fk_departamentos_id_area'];
    $profissao = $_POST['fk_profissoes_id_profissao'];

    // Validação da descrição (mínimo de 80 caracteres)
    if (strlen($descricao) < 80) {
        $error_message = "A descrição deve ter pelo menos 80 caracteres.";
    } else {
        // Atualizar o perfil do usuário no banco de dados
        $update_query = "UPDATE profissional SET 
                            nome_profissional = :nome, 
                            email_profissional = :email, 
                            tel_profissional = :telefone, 
                            descricao_profissional = :descricao, 
                            senha_profissional = :senha, 
                            fk_departamentos_id_area = :area_atuacao, 
                            fk_profissoes_id_profissao = :profissao 
                         WHERE id_profissional = :id";
        
        $stmt = $conn->prepare($update_query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':senha', password_hash($senha, PASSWORD_DEFAULT)); // Hash de senha para segurança
        $stmt->bindParam(':area_atuacao', $area_atuacao);
        $stmt->bindParam(':profissao', $profissao);
        $stmt->bindParam(':id', $usuario_id);
        
        if ($stmt->execute()) {
            $success_message = "Perfil atualizado com sucesso!";
            // Redireciona para a página do perfil do usuário
            header('Location: meuperfil.php');
            exit();
        } else {
            $error_message = "Ocorreu um erro ao atualizar o perfil. Tente novamente.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - BuscAraras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
    <div class="container-fluid p-0 vh-100 d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column p-3">
            <h1 class="text-light text-left">BuscAraras</h1>
            <a class="btn btn-light no-border mb-2 tamanho" href="index.php">Início</a>
            <h3 class="text-danger text-left">Editar Perfil</h3>
        </div>

        <!-- Main Content -->
        <div class="main-content d-flex justify-content-center align-items-center">
            <div class="card form-card">
                <div class="card-body">
                    <h4 class="card-title text-center">Editar Perfil</h4>
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?= $error_message ?></div>
                    <?php elseif (isset($success_message)): ?>
                        <div class="alert alert-success"><?= $success_message ?></div>
                    <?php endif; ?>
                    <form action="editar_perfil.php" method="POST">
                        <!-- Nome -->
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome_profissional" value="<?= htmlspecialchars($usuario['nome_profissional']) ?>" required>
                        </div>
                        
                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email_profissional" value="<?= htmlspecialchars($usuario['email_profissional']) ?>" required>
                        </div>
                        
                        <!-- Telefone -->
                        <div class="form-group">
                            <label for="telefone">Telefone com Whatsapp</label>
                            <small class="form-text text-muted" style="margin-top: -6px;">Insira apenas números (sem espaços ou caracteres especiais).</small>
                            <input type="tel" class="form-control" id="telefone" maxlength="11" name="tel_profissional" value="<?= htmlspecialchars($usuario['tel_profissional']) ?>" required pattern="[0-9]{11}">
                        </div>
                        
                        <!-- Área de atuação -->
                        <div class="form-group">
                            <label for="area-atuacao">Área de atuação</label>
                            <select class="form-select" id="area-atuacao" name="fk_departamentos_id_area" required onchange="carregarProfissoes()">
                                <option selected value="<?= $usuario['fk_departamentos_id_area'] ?>"><?= $usuario['nome_area'] ?></option>
                                <?php foreach ($departamentos as $departamento): ?>
                                    <option value="<?= $departamento['id_area'] ?>"><?= $departamento['nome_area'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Profissão -->
                        <div class="form-group">
                            <label for="profissao">Profissão</label>
                            <select class="form-select" id="profissao" name="fk_profissoes_id_profissao" required>
                                <option selected value="<?= $usuario['fk_profissoes_id_profissao'] ?>"><?= $usuario['nome_profissao'] ?></option>
                                <?php foreach ($profissoes as $profissao): ?>
                                    <option value="<?= $profissao['id_profissao'] ?>"><?= $profissao['nome_profissao'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Descrição Profissional -->
                        <div class="form-group">
                            <label for="descricao">Descrição Profissional</label>
                            <small class="form-text text-muted" style="margin-top: -6px;">Mínimo 80 caracteres.</small>
                            <textarea class="form-control" id="descricao" name="descricao_profissional" rows="3" minlength="80" required><?= htmlspecialchars($usuario['descricao_profissional']) ?></textarea>
                        </div>
                        
                        <!-- Senha -->
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha_profissional" required maxlength="8" pattern=".{6,8}" title="A senha deve ter entre 6 e 8 caracteres.">
                        </div>
                        
                        <div class="btn-container">
                            <button type="submit" class="btn btn-danger btn-sm btn-block">Atualizar</button>
                            <a href="deletar_perfil.php" class="btn btn-danger btn-sm btn-block mt-2" onclick="return confirm('Tem certeza que deseja deletar seu perfil?')">Deletar Perfil</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function carregarProfissoes() {
            const idArea = document.getElementById('area-atuacao').value;
            const profissaoSelect = document.getElementById('profissao');

            // Limpa as opções anteriores
            profissaoSelect.innerHTML = '<option selected>Selecione uma opção</option>';

            if (idArea) {
                fetch(`carregar_profissoes.php?id_area=${idArea}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(profissao => {
                            const option = document.createElement('option');
                            option.value = profissao.id_profissao;
                            option.textContent = profissao.nome_profissao;
                            profissaoSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Erro ao carregar profissões:', error));
            }
        }
    </script>

</body>
</html>
