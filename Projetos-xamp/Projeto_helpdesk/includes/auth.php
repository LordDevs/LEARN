<?php
declare(strict_types=1);

require_once __DIR__ . '/security.php';

function requireAuthentication(bool $expectsJson = false): void
{
    ensureSessionStarted();

    if (isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === 'SIM') {
        return;
    }

    if ($expectsJson) {
        respondJson(['error' => 'Não autenticado.'], 401);
    } else {
        setFlash('error', 'Faça login antes de acessar as páginas protegidas.');
        header('Location: index.php');
    }

    exit;
}

function currentUserId(): ?int
{
    ensureSessionStarted();

    return isset($_SESSION['id']) ? (int) $_SESSION['id'] : null;
}

function currentUserProfileId(): ?int
{
    ensureSessionStarted();

    return isset($_SESSION['perfil_id']) ? (int) $_SESSION['perfil_id'] : null;
}
