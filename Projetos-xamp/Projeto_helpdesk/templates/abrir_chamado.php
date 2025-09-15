<?php
require_once __DIR__ . '/../includes/ticket_repository.php';
$categories = ticket_categories();
?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                    <div>
                        <h1 class="h4 mb-1">Abrir novo chamado</h1>
                        <p class="text-muted mb-0">Descreva o problema para que possamos ajudar rapidamente.</p>
                    </div>
                    <a href="home.php" class="link-muted">&larr; Voltar</a>
                </div>
                <form id="ticketCreateForm" class="row g-4">
                    <div class="col-12">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Resuma o problema" required />
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="categoria" class="form-label">Categoria</label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="" disabled selected>Selecione</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="4" placeholder="Conte-nos o que está acontecendo" required></textarea>
                    </div>
                    <div class="col-12 d-flex flex-column flex-md-row gap-3 justify-content-end">
                        <a class="btn btn-outline-secondary" href="home.php">Cancelar</a>
                        <button class="btn btn-primary" type="submit">
                            <span class="spinner-border spinner-border-sm align-middle me-2 d-none" role="status" aria-hidden="true"></span>
                            Enviar chamado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
