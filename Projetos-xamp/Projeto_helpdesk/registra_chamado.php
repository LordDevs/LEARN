<?php
declare(strict_types=1);

require_once __DIR__ . '/validador_acesso.php';
require_once __DIR__ . '/includes/security.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/tickets.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: abrir_chamado.php');
    exit;
}

try {
    requireCsrfToken($_POST['csrf_token'] ?? null);
} catch (RuntimeException $exception) {
    setFlash('error', 'Sessão expirada. Recarregue a página e tente novamente.');
    header('Location: abrir_chamado.php');
    exit;
}

$validation = validateTicketPayload($_POST);

if (!empty($validation['errors'])) {
    $message = implode(' ', array_values($validation['errors']));
    setFlash('error', $message);
    header('Location: abrir_chamado.php');
    exit;
}

try {
    appendTicket(currentUserId() ?? 0, $validation['data']);
    setFlash('success', 'Chamado registrado com sucesso!');
} catch (RuntimeException $exception) {
    setFlash('error', 'Não foi possível registrar o chamado. Tente novamente.');
}

header('Location: abrir_chamado.php');
exit;
