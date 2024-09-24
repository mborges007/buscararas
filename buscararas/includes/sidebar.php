<div class="sidebar d-flex flex-column p-3">
        <h1 class="text-light text-left">BuscAraras</h1>
        
        <!-- Botão Início -->
        <a class="btn btn-light no-border mb-2 tamanho" href="index.php">Início</a>
        
        <h3 class="text-danger text-left">Departamentos</h3>
        <!-- Dropdowns -->
        <?php foreach ($profissoes as $area => $profissaoList): ?>
            <div class="dropdown">
                <button class="btn dropdown-toggle no-border" type="button" id="dropdown<?php echo $area; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo $area; ?>
                </button>
                <ul class="dropdown-menu no-border" aria-labelledby="dropdown<?php echo $area; ?>">
                    <?php if (!empty($profissaoList)): ?>
                        <?php foreach ($profissaoList as $profissao): ?>
                            <li>
                                <a class="dropdown-item" href="lista_profissionais.php?profissao=<?php echo urlencode($profissao); ?>">
                                    <?php echo htmlspecialchars($profissao); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="#">Nenhuma profissão encontrada</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endforeach; ?>

        <!-- Botões de Login e Cadastro -->
        <div class="mt-auto">
            <a class="btn btn-primary w-100 mb-2" href="login.html">Login</a>
            <a class="btn btn-secondary w-100" href="cadastro.php">Cadastro</a>
        </div>
    </div>