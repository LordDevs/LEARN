<?php
$loginStatus = $loginStatus ?? ($_GET['login'] ?? null);
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="card shadow-soft p-4">
            <div class="text-center mb-4">
                <img src="imagens/logo.png" alt="App Help Desk" class="mb-3" style="height: 56px;" />
                <h1 class="h3 mb-1">Bem-vindo de volta</h1>
                <p class="text-muted mb-0">Acesse para gerenciar seus chamados de suporte.</p>
            </div>

            <?php if ($loginStatus === 'erro'): ?>
                <div class="alert alert-danger" role="alert">
                    Usuário ou senha inválido(s).
                </div>
            <?php elseif ($loginStatus === 'erro2'): ?>
                <div class="alert alert-warning" role="alert">
                    Faça login antes de acessar as páginas protegidas.
                </div>
            <?php endif; ?>

            <form action="valida_login.php" method="post" class="d-flex flex-column gap-3">
                <div>
                    <label for="email" class="form-label">E-mail</label>
                    <input id="email" name="email" type="email" class="form-control" placeholder="nome@empresa.com" required />
                </div>
                <div>
                    <label for="senha" class="form-label">Senha</label>
                    <input id="senha" name="senha" type="password" class="form-control" placeholder="••••••" required />
                </div>
                <button class="btn btn-primary btn-lg" type="submit">Entrar</button>
            </form>
        </div>
    </div>
</div>
