<?php


// Incluindo o arquivo de conexão com o banco de dados
include 'db.php';


// Verificando as variáveis de sessão para identificar o tipo de usuário
$isProfissional = isset($_SESSION['id_profissional']);
$isUsuario = isset($_SESSION['id_usuario']);

// Recuperando os dados do profissional ou usuário se estiver logado
if ($isProfissional) {
    // Recuperando o nome do profissional a partir do banco de dados
    $stmtProf = $conn->prepare("SELECT nome_profissional FROM profissional WHERE id_profissional = :id");
    $stmtProf->bindParam(':id', $_SESSION['id_profissional'], PDO::PARAM_INT);
    $stmtProf->execute();
    $profissional = $stmtProf->fetch(PDO::FETCH_ASSOC);

    if ($profissional) {
        $_SESSION['nome_profissional'] = $profissional['nome_profissional'];
    }
}

if ($isUsuario) {
    // Recuperando o nome do usuário a partir do banco de dados
    $stmtUser = $conn->prepare("SELECT nome_usuario FROM usuarios WHERE id_usuario = :id");
    $stmtUser->bindParam(':id', $_SESSION['id_usuario'], PDO::PARAM_INT);
    $stmtUser->execute();
    $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
    }
}

// Consultando as áreas e profissões
$query = "SELECT nome_area FROM departamentos ORDER BY nome_area ASC";
$stmt = $conn->prepare($query);
$stmt->execute();
$areas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organizar as profissões por área
$profissoes = [];
foreach ($areas as $area) {
    $areaNome = $area['nome_area'];

    // Consulta para obter as profissões da área
    $queryProf = "SELECT nome_profissao FROM profissoes 
    WHERE fk_departamentos_id_area = (SELECT id_area FROM departamentos WHERE nome_area = :area_departamento) ORDER BY nome_profissao ASC";
    $stmtProf = $conn->prepare($queryProf);
    $stmtProf->bindParam(':area_departamento', $areaNome, PDO::PARAM_STR);
    $stmtProf->execute();

    $profissoes[$areaNome] = $stmtProf->fetchAll(PDO::FETCH_COLUMN);
}
?>

<div class="sidebar d-flex flex-column p-3">

        <h1 class="text-light text-left" style="color:#F5F5E6; display: flex; align-items: center;">
                    <span class="text" style="color: #F5F5E6;"> Busc</span><span class="text" style="color: #BF4341;">Araras</span>
                        <img src="img/lupasidebar.svg" alt="Lupa" style="width: 25px; height: 25px; margin-right: -5px; margin-top:9px;">                
            </h1>




    <!-- Botão Início -->
    <a class="btn btn-light no-border mb-2 tamanho" href="index.php">Início</a>

    <?php if ($isProfissional): ?>
        <!-- Sidebar para Profissional -->
        <a class="btn btn-light no-border mb-2" href="meuperfil.php">Meu Perfil</a>
    <?php endif; ?>

    <h3 class="text-danger text-left">Departamentos</h3>
    <!-- Dropdowns -->
    <?php foreach ($profissoes as $area => $profissaoList): ?>
        <div class="dropdown">
            <button class="btn dropdown-toggle no-border" type="button" id="dropdown<?php echo $area; ?>" data-bs-toggle="dropdown" aria-expanded="false">
               <span> <?php echo htmlspecialchars($area); ?></span>
            </button>
            <ul class="dropdown-menu no-border" aria-labelledby="dropdown<?php echo $area; ?>">
                <?php if (!empty($profissaoList)): ?>
                    <?php foreach ($profissaoList as $profissao): ?>
                        <li>
                            <a class="dropdown-item ladinho" href="lista_profissionais.php?profissao=<?php echo urlencode($profissao); ?>">
                              <span> <?php echo htmlspecialchars($profissao); ?></span> 
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li><a class="dropdown-item" href="#">Nenhuma profissão encontrada</a></li>
                <?php endif; ?>
            </ul>
        </div>
    <?php endforeach; ?>

    <!-- Botões de Login e Cadastro ou Logout -->
    <?php if (!$isProfissional && !$isUsuario): ?>
        <div class="mt-auto ">
            <a class="btn btn-primary hoverando w-100 mb-2" href="login.php">Login</a>
            <a class="btn btn-secondary hoverando  w-100" href="cadastro.php">Cadastro</a>
        </div>
    <?php else: ?>
        <!-- Se o usuário ou profissional estiver logado, exibe o nome e a opção de sair -->
       <!-- Exibição do nome como botão para edição de perfil -->
<div class="mt-auto">
    <?php if ($isProfissional && isset($_SESSION['nome_profissional'])): ?>
        <a class="btn btn-primary w-100 mb-2" href="editar_perfil.php">
            <?php echo htmlspecialchars($_SESSION['nome_profissional']). " - Editar Perfil"; ?>
        </a>
        
    <?php elseif ($isUsuario && isset($_SESSION['nome_usuario'])): ?>
        <a class="btn btn-primary hoverando w-100 mb-2" href="editar_perfil_usuarios.php">
        <?php echo htmlspecialchars($_SESSION['nome_usuario']) . " - Editar Perfil"; ?>
    </a>
    <?php endif; ?>
    <a class="btn btn-secondary hoverando w-100" href="logout.php">Sair</a>
</div>
    <?php endif; ?>
</div>


