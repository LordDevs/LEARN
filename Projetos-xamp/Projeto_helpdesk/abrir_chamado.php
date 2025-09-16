<?php
declare(strict_types=1);

require_once __DIR__ . '/validador_acesso.php';
require_once __DIR__ . '/includes/security.php';
require_once __DIR__ . '/includes/tickets.php';

$csrfToken = getCsrfToken();
$flashSuccess = getFlash('success');
$flashError = getFlash('error');
$categories = getTicketCategories();
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
      <div class="row justify-content-center mb-4">
        <div class="col-12 col-lg-10">
          <?php if ($flashSuccess !== null): ?>
            <div class="alert alert-success" role="alert">
              <?=htmlspecialchars($flashSuccess, ENT_QUOTES)?>
            </div>
          <?php endif; ?>
          <?php if ($flashError !== null): ?>
            <div class="alert alert-danger" role="alert">
              <?=htmlspecialchars($flashError, ENT_QUOTES)?>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="row g-4">
        <div class="col-12 col-lg-6">
          <div class="card shadow-sm h-100">
            <div class="card-header">Abrir chamado</div>
            <div class="card-body">
              <div class="alert d-none" role="alert" data-ticket-feedback></div>
              <form class="needs-validation" novalidate method="post" action="registra_chamado.php" data-ticket-form data-ticket-endpoint="api/tickets.php">
                <div class="mb-3">
                  <label class="form-label" for="titulo">Título</label>
                  <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="120" autocomplete="off" placeholder="Resumo do problema">
                  <div class="invalid-feedback">Informe um título descritivo.</div>
                </div>

                <div class="mb-3">
                  <label class="form-label" for="categoria">Categoria</label>
                  <select class="form-select" id="categoria" name="categoria" required>
                    <option value="" selected disabled>Selecione uma categoria</option>
                    <?php foreach ($categories as $category): ?>
                      <option value="<?=htmlspecialchars($category, ENT_QUOTES)?>"><?=htmlspecialchars($category, ENT_QUOTES)?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback">Escolha a categoria mais adequada.</div>
                </div>

                <div class="mb-3">
                  <label class="form-label" for="descricao">Descrição</label>
                  <textarea class="form-control" id="descricao" name="descricao" rows="4" required maxlength="1000" placeholder="Detalhe o chamado"></textarea>
                  <div class="invalid-feedback">Descreva o chamado com mais detalhes.</div>
                </div>

                <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($csrfToken, ENT_QUOTES)?>">

                <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                  <a class="btn btn-outline-secondary w-100" href="home.php">Voltar</a>
                  <button class="btn btn-info text-white w-100" type="submit">Abrir chamado</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-6">
          <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span>Seus chamados</span>
              <button class="btn btn-outline-secondary btn-sm" type="button" data-ticket-refresh>Atualizar</button>
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
