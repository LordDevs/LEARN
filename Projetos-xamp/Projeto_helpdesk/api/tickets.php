<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/tickets.php';

requireAuthentication(true);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        $tickets = listTicketsForUser(currentUserId() ?? 0, currentUserProfileId() ?? 0);
        respondJson(['tickets' => $tickets]);
        return;
    }

    if ($method === 'POST') {
        $input = [];

        if (isJsonRequest()) {
            try {
                $input = readJsonInput();
            } catch (RuntimeException $exception) {
                respondJson(['error' => $exception->getMessage()], 400);
                return;
            }
        } else {
            $input = $_POST;
        }

        $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($input['csrf_token'] ?? null);

        try {
            requireCsrfToken(is_string($csrfToken) ? $csrfToken : null);
        } catch (RuntimeException $exception) {
            respondJson(['error' => 'Token CSRF ausente ou inválido.'], 400);
            return;
        }

        $validation = validateTicketPayload($input);
        if (!empty($validation['errors'])) {
            respondJson(['errors' => $validation['errors']], 422);
            return;
        }

        $ticket = appendTicket(currentUserId() ?? 0, $validation['data']);

        respondJson(['ticket' => formatTicketForResponse($ticket)], 201);
        return;
    }

    header('Allow: GET, POST');
    respondJson(['error' => 'Método não suportado.'], 405);
} catch (RuntimeException $exception) {
    respondJson(['error' => $exception->getMessage()], 500);
}
