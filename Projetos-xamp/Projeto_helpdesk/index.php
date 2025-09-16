<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/security.php';

ensureSessionStarted();

$csrfToken = getCsrfToken();
$flashError = getFlash('error');

$loginStatus = $_GET['login'] ?? null;
if ($flashError === null && $loginStatus === 'erro') {
    $flashError = 'Usuário ou senha inválido(s).';
} elseif ($flashError === null && $loginStatus === 'erro2') {
    $flashError = 'Faça login antes de acessar as páginas protegidas.';
}

$lastEmail = isset($_SESSION['last_email']) ? (string) $_SESSION['last_email'] : '';
?>
<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App Help Desk</title>
    <meta name="csrf-token" content="<?=htmlspecialchars($csrfToken, ENT_QUOTES)?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      .card-login {
        max-width: 420px;
      }
    </style>
  </head>
  <body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
          <img src="imagens/logo.png" width="32" height="32" class="d-inline-block" alt="Logotipo">
          <span>App Help Desk</span>
        </a>
      </div>
    </nav>

    <main class="container py-5">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5 card-login">
          <div class="card shadow-sm">
            <div class="card-header text-center">Login</div>
            <div class="card-body">
              <?php if ($flashError !== null): ?>
                <div class="alert alert-danger" role="alert">
                  <?=htmlspecialchars($flashError, ENT_QUOTES)?>
                </div>
              <?php endif; ?>

              <form class="needs-validation" novalidate action="valida_login.php" method="post">
                <div class="mb-3">
                  <label class="form-label" for="email">E-mail</label>
                  <input type="email" class="form-control" id="email" name="email" value="<?=htmlspecialchars($lastEmail, ENT_QUOTES)?>" required autocomplete="email" maxlength="120">
                  <div class="invalid-feedback">Informe um endereço de e-mail válido.</div>
                </div>

                <div class="mb-3">
                  <label class="form-label" for="senha">Senha</label>
                  <input type="password" class="form-control" id="senha" name="senha" required minlength="4" maxlength="72" autocomplete="current-password">
                  <div class="invalid-feedback">Informe sua senha.</div>
                </div>

                <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($csrfToken, ENT_QUOTES)?>">

                <button class="btn btn-info w-100 text-white" type="submit">Entrar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <script>
      (() => {
        'use strict';
        window.addEventListener('load', () => {
          const forms = document.querySelectorAll('.needs-validation');
          Array.prototype.forEach.call(forms, (form) => {
            form.addEventListener('submit', (event) => {
              if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
              }

              form.classList.add('was-validated');
            });
          });
        });
      })();
    </script>
  </body>
</html>
