<?php
$sessionActive = session_status() === PHP_SESSION_ACTIVE;
$hasAuth = $sessionActive && isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === 'SIM';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= $hasAuth ? 'home.php' : 'index.php' ?>">
            <img src="imagens/logo.png" alt="App Help Desk" class="rounded" />
            <span class="fw-semibold">App Help Desk</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="ms-auto d-flex align-items-center gap-2">
                <?php if ($hasAuth): ?>
                    <a class="btn btn-outline-light" href="logoff.php">Sair</a>
                <?php else: ?>
                    <a class="btn btn-outline-light" href="index.php">Entrar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
