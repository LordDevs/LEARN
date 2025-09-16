<?php
declare(strict_types=1);

require_once __DIR__ . '/validador_acesso.php';
?>
<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App Help Desk</title>
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
        <div class="col-12 col-lg-10">
          <div class="card shadow-sm">
            <div class="card-header">Menu</div>
            <div class="card-body">
              <div class="row g-4 text-center">
                <div class="col-12 col-md-6">
                  <a href="abrir_chamado.php" class="d-flex flex-column align-items-center text-decoration-none text-dark">
                    <img src="imagens/formulario_abrir_chamado.png" width="80" height="80" alt="Abrir chamado">
                    <span class="mt-3 fw-semibold">Abrir chamado</span>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="consultar_chamado.php" class="d-flex flex-column align-items-center text-decoration-none text-dark">
                    <img src="imagens/formulario_consultar_chamado.png" width="80" height="80" alt="Consultar chamados">
                    <span class="mt-3 fw-semibold">Consultar chamados</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
  </body>
</html>
