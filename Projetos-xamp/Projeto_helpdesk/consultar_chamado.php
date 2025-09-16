<?php
declare(strict_types=1);

require_once __DIR__ . '/validador_acesso.php';
require_once __DIR__ . '/includes/security.php';

$csrfToken = getCsrfToken();
?>
<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App Help Desk</title>
    <meta name="csrf-token" content="<?=htmlspecialchars($csrfToken, ENT_QUOTES)?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
      <div class="container d-flex justify-content-between">
        <a class="navbar-brand d-flex align-items-center gap-2" href="home.php">
          <img src="imagens/logo.png" width="32" height="32" alt="Logotipo">
          <span>App Help Desk</span>
        </a>
        <div>
          <a class="btn btn-outline-light btn-sm" href="logoff.php">Sair</a>
        </div>
      </div>
    </nav>

    <main class="container py-5">
      <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
          <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span>Consulta de chamados</span>
              <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary btn-sm" href="home.php">Voltar</a>
                <button class="btn btn-outline-primary btn-sm" type="button" data-ticket-refresh>Atualizar</button>
              </div>
            </div>
            <div class="card-body" data-ticket-list>
              <p class="text-muted mb-0">Carregando chamados...</p>
            </div>
          </div>
        </div>
      </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <script src="assets/js/tickets.js" defer></script>
  </body>
</html>
