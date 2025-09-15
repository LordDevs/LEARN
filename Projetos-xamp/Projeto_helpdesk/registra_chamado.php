<?php
require_once __DIR__ . '/validador_acesso.php';
require_once __DIR__ . '/includes/ticket_repository.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: abrir_chamado.php');
    exit;
}

try {
    ticket_create($_POST, (int) ($_SESSION['id'] ?? 0));
    $_SESSION['flash_message'] = 'Chamado criado com sucesso!';
    header('Location: consultar_chamado.php');
    exit;
} catch (InvalidArgumentException $exception) {
    $_SESSION['flash_message'] = $exception->getMessage();
} catch (Throwable $exception) {
    $_SESSION['flash_message'] = 'Não foi possível registrar o chamado. Tente novamente.';
}

header('Location: abrir_chamado.php');
exit;
