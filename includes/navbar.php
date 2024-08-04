<nav class="navbar navbar-expand-lg navbar-light bg-warning">
    <a class="navbar-brand" href="index.php">Gerenciamento de Plantão</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="empresasDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Empresas
                </a>
                <div class="dropdown-menu" aria-labelledby="empresasDropdown">
                    <a class="dropdown-item" href="empresas/lista_empresa.php">Listar Empresas</a>
                    <a class="dropdown-item" href="empresas/add_empresa.php">Adicionar Empresa</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="usuariosDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Usuários
                </a>
                <div class="dropdown-menu" aria-labelledby="usuariosDropdown">
                    <a class="dropdown-item" href="usuarios/lista_usuario.php">Listar Usuários</a>
                    <a class="dropdown-item" href="usuarios/add_usuario.php">Adicionar Usuário</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="contatosDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Contatos
                </a>
                <div class="dropdown-menu" aria-labelledby="contatosDropdown">
                    <a class="dropdown-item" href="contatos/lista_contato.php">Listar Contatos</a>
                    <a class="dropdown-item" href="contatos/add_contato.php">Adicionar Contato</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="chamadosDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Chamados
                </a>
                <div class="dropdown-menu" aria-labelledby="chamadosDropdown">
                    <a class="dropdown-item" href="chamados/lista_chamado.php">Listar Chamados</a>
                    <a class="dropdown-item" href="chamados/add_chamado.php">Adicionar Chamado</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="rel_plantao.php">Relatório dos Plantões</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>
