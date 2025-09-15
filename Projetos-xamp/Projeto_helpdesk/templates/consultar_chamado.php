<div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h4 mb-1">Chamados</h1>
        <p class="text-muted mb-0">Gerencie seus chamados com atualizações em tempo real.</p>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary" href="home.php">Voltar</a>
        <a class="btn btn-primary" href="abrir_chamado.php">Novo chamado</a>
    </div>
</div>

<div id="ticketsLoading" class="alert alert-info d-none" role="status">
    Carregando chamados...
</div>
<div id="ticketsEmpty" class="alert alert-light border d-none" role="status">
    Nenhum chamado encontrado.
</div>
<div id="ticketsList" class="row g-4"></div>

<div class="modal fade" id="ticketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5">Editar chamado</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form id="ticketEditForm">
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label for="tituloEdit" class="form-label">Título</label>
                        <input type="text" class="form-control" id="tituloEdit" name="titulo" required />
                    </div>
                    <div>
                        <label for="categoriaEdit" class="form-label">Categoria</label>
                        <select class="form-select" id="categoriaEdit" name="categoria" required>
                            <?php
                            require_once __DIR__ . '/../includes/ticket_repository.php';
                            foreach (ticket_categories() as $category): ?>
                                <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="descricaoEdit" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricaoEdit" name="descricao" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm align-middle me-2 d-none" role="status" aria-hidden="true"></span>
                        Salvar alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
