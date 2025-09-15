<?php

declare(strict_types=1);

session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== 'SIM') {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Faça login para acessar os chamados.',
    ]);
    exit;
}

require_once dirname(__DIR__) . '/includes/ticket_repository.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$userId = (int) ($_SESSION['id'] ?? 0);
$isAdmin = (int) ($_SESSION['perfil_id'] ?? 2) === 1;

try {
    switch ($method) {
        case 'GET':
            $tickets = ticket_collection_for_user($userId, $isAdmin);
            echo json_encode([
                'status' => 'success',
                'data' => $tickets,
            ]);
            break;

        case 'POST':
            $payload = json_decode(file_get_contents('php://input'), true);
            if (!is_array($payload)) {
                $payload = $_POST;
            }
            $ticket = ticket_create($payload, $userId);
            echo json_encode([
                'status' => 'success',
                'data' => ticket_transform($ticket, $userId, $isAdmin),
            ]);
            break;

        case 'PUT':
        case 'PATCH':
            $id = $_GET['id'] ?? '';
            if ($id === '') {
                throw new RuntimeException('Identificador do chamado não informado.', 400);
            }
            $payload = json_decode(file_get_contents('php://input'), true);
            if (!is_array($payload)) {
                $payload = [];
            }
            $ticket = ticket_update($id, $payload, $userId, $isAdmin);
            echo json_encode([
                'status' => 'success',
                'data' => ticket_transform($ticket, $userId, $isAdmin),
            ]);
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? '';
            if ($id === '') {
                throw new RuntimeException('Identificador do chamado não informado.', 400);
            }
            ticket_delete($id, $userId, $isAdmin);
            echo json_encode([
                'status' => 'success',
                'message' => 'Chamado removido com sucesso.',
            ]);
            break;

        default:
            http_response_code(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Método não suportado.',
            ]);
    }
} catch (InvalidArgumentException $exception) {
    http_response_code(422);
    echo json_encode([
        'status' => 'error',
        'message' => $exception->getMessage(),
    ]);
} catch (RuntimeException $exception) {
    $code = $exception->getCode();
    if ($code < 400 || $code > 599) {
        $code = 400;
    }
    http_response_code($code);
    echo json_encode([
        'status' => 'error',
        'message' => $exception->getMessage(),
    ]);
} catch (Throwable $exception) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro inesperado ao processar o chamado.',
    ]);
}
