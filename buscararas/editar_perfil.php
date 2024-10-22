<?php
session_start();
include 'db.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit;
}

$profissional_id = $_SESSION['user'];

try {
    // Obtém as informações do profissional
    $sql = "SELECT nome_profissional, tel_profissional, email_profissional, descricao_profissional, fk_profissoes_id_profissao, fk_departamentos_id_area FROM profissional WHERE id_profissional = :id_profissional";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_profissional', $profissional_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $profissional = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "Profissional não encontrado!";
        exit;
    }
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
}

// Verifica se o formulário foi enviado
// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $descricao = $_POST['descricao'];
    $profissao = $_POST['profissao'];
    $departamento = $_POST['departamento'];
    
    // Captura a nova senha e a confirmação
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Valida se os campos estão vazios
    if (empty($nome) || empty($telefone) || empty($email) || empty($descricao) || empty($profissao) || empty($departamento)) {
        $error_message = "Por favor, preencha todos os campos!";
    } elseif (!empty($senha) && $senha !== $confirmar_senha) {
        $error_message = "As senhas não coincidem!";
    } else {
        // Atualiza as informações do profissional no banco de dados
        try {
            $updateSql = "UPDATE profissional SET nome_profissional = :nome, tel_profissional = :telefone, email_profissional = :email, descricao_profissional = :descricao, fk_profissoes_id_profissao = :profissao, fk_departamentos_id_area = :departamento";
            
            // Adiciona a parte da senha se ela não estiver vazia
            if (!empty($senha)) {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $updateSql .= ", senha_profissional = :senha";
            }
            
            $updateSql .= " WHERE id_profissional = :id_profissional";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bindParam(':nome', $nome);
            $updateStmt->bindParam(':telefone', $telefone);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->bindParam(':descricao', $descricao);
            $updateStmt->bindParam(':profissao', $profissao);
            $updateStmt->bindParam(':departamento', $departamento);
            $updateStmt->bindParam(':id_profissional', $profissional_id, PDO::PARAM_INT);

            // Vincula a senha, se não estiver vazia
            if (!empty($senha)) {
                $updateStmt->bindParam(':senha', $senha_hash);
            }

            $updateStmt->execute();

            // Redireciona após a atualização
            header("Location: meuperfil.php");
            exit;
        } catch (PDOException $e) {
            echo "Erro ao atualizar: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Editar Perfil</h1>
        <form method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($profissional['nome_profissional']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control" value="<?php echo htmlspecialchars($profissional['tel_profissional']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($profissional['email_profissional']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-control" required><?php echo htmlspecialchars($profissional['descricao_profissional']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="profissao" class="form-label">Profissão</label>
                <input type="text" id="profissao" name="profissao" class="form-control" value="<?php echo htmlspecialchars($profissional['fk_profissoes_id_profissao']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="departamento" class="form-label">Departamento</label>
                <input type="text" id="departamento" name="departamento" class="form-control" value="<?php echo htmlspecialchars($profissional['fk_departamentos_id_area']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Nova Senha</label>
                <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite uma nova senha (opcional)">
            </div>
            <div class="mb-3">
                <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control" placeholder="Confirme a nova senha (opcional)">
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
