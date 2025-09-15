<?php

declare(strict_types=1);

function ticket_storage_path(): string
{
    return dirname(__DIR__) . '/storage/tickets.json';
}

function ticket_storage_bootstrap(): void
{
    $path = ticket_storage_path();
    $directory = dirname($path);

    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    if (!file_exists($path)) {
        $tickets = ticket_load_legacy();
        file_put_contents($path, json_encode($tickets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

function ticket_load_legacy(): array
{
    $legacyPath = dirname(__DIR__, 2) . '/app_helpdesk/arquivo.hd';
    if (!is_file($legacyPath)) {
        return [];
    }

    $lines = file($legacyPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return [];
    }

    $tickets = [];
    foreach ($lines as $line) {
        $parts = explode('#', $line);
        if (count($parts) < 4) {
            continue;
        }
        $tickets[] = [
            'id' => ticket_generate_id(),
            'user_id' => (int) $parts[0],
            'titulo' => $parts[1],
            'categoria' => $parts[2],
            'descricao' => $parts[3],
            'created_at' => date('c'),
            'updated_at' => null,
        ];
    }

    return $tickets;
}

function ticket_generate_id(): string
{
    try {
        return bin2hex(random_bytes(8));
    } catch (Throwable $exception) {
        return uniqid('ticket_', true);
    }
}

function ticket_read_all(): array
{
    ticket_storage_bootstrap();
    $contents = file_get_contents(ticket_storage_path());
    if ($contents === false || trim($contents) === '') {
        return [];
    }

    $data = json_decode($contents, true);
    return is_array($data) ? $data : [];
}

function ticket_write_all(array $tickets): void
{
    ticket_storage_bootstrap();
    file_put_contents(ticket_storage_path(), json_encode(array_values($tickets), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function ticket_categories(): array
{
    return [
        'Criação Usuário',
        'Impressora',
        'Hardware',
        'Software',
        'Rede',
    ];
}

function ticket_sanitize_payload(array $input): array
{
    return [
        'titulo' => trim((string) ($input['titulo'] ?? '')),
        'categoria' => trim((string) ($input['categoria'] ?? '')),
        'descricao' => trim((string) ($input['descricao'] ?? '')),
    ];
}

function ticket_validate_payload(array $payload): array
{
    $errors = [];

    if ($payload['titulo'] === '') {
        $errors[] = 'Informe um título para o chamado.';
    }

    if ($payload['categoria'] === '') {
        $errors[] = 'Selecione uma categoria.';
    }

    if ($payload['descricao'] === '') {
        $errors[] = 'Descreva o chamado para a equipe de suporte.';
    }

    if ($payload['categoria'] !== '' && !in_array($payload['categoria'], ticket_categories(), true)) {
        $errors[] = 'Categoria inválida.';
    }

    return $errors;
}

function ticket_create(array $input, int $userId): array
{
    $payload = ticket_sanitize_payload($input);
    $errors = ticket_validate_payload($payload);

    if (!empty($errors)) {
        throw new InvalidArgumentException(implode(' ', $errors));
    }

    $tickets = ticket_read_all();

    $ticket = [
        'id' => ticket_generate_id(),
        'user_id' => $userId,
        'titulo' => $payload['titulo'],
        'categoria' => $payload['categoria'],
        'descricao' => $payload['descricao'],
        'created_at' => date('c'),
        'updated_at' => null,
    ];

    $tickets[] = $ticket;
    ticket_write_all($tickets);

    return $ticket;
}

function ticket_update(string $id, array $input, int $userId, bool $isAdmin): array
{
    $payload = ticket_sanitize_payload($input);
    $errors = ticket_validate_payload($payload);

    if (!empty($errors)) {
        throw new InvalidArgumentException(implode(' ', $errors));
    }

    $tickets = ticket_read_all();
    foreach ($tickets as $index => $ticket) {
        if ($ticket['id'] !== $id) {
            continue;
        }

        if (!$isAdmin && $ticket['user_id'] !== $userId) {
            throw new RuntimeException('Você não tem permissão para editar este chamado.', 403);
        }

        $tickets[$index]['titulo'] = $payload['titulo'];
        $tickets[$index]['categoria'] = $payload['categoria'];
        $tickets[$index]['descricao'] = $payload['descricao'];
        $tickets[$index]['updated_at'] = date('c');

        ticket_write_all($tickets);

        return $tickets[$index];
    }

    throw new RuntimeException('Chamado não encontrado.', 404);
}

function ticket_delete(string $id, int $userId, bool $isAdmin): void
{
    $tickets = ticket_read_all();
    foreach ($tickets as $index => $ticket) {
        if ($ticket['id'] !== $id) {
            continue;
        }

        if (!$isAdmin && $ticket['user_id'] !== $userId) {
            throw new RuntimeException('Você não tem permissão para excluir este chamado.', 403);
        }

        array_splice($tickets, $index, 1);
        ticket_write_all($tickets);
        return;
    }

    throw new RuntimeException('Chamado não encontrado.', 404);
}

function ticket_transform(array $ticket, int $userId, bool $isAdmin): array
{
    $isOwner = $ticket['user_id'] === $userId;

    return [
        'id' => $ticket['id'],
        'titulo' => $ticket['titulo'],
        'categoria' => $ticket['categoria'],
        'descricao' => $ticket['descricao'],
        'createdAt' => $ticket['created_at'] ?? null,
        'updatedAt' => $ticket['updated_at'] ?? null,
        'ownerId' => $ticket['user_id'],
        'isOwner' => $isOwner,
        'canEdit' => $isAdmin || $isOwner,
        'canDelete' => $isAdmin || $isOwner,
        'ownerLabel' => $isOwner ? 'Você' : 'Usuário #' . $ticket['user_id'],
    ];
}

function ticket_collection_for_user(int $userId, bool $isAdmin): array
{
    $tickets = ticket_read_all();

    if (!$isAdmin) {
        $tickets = array_values(array_filter(
            $tickets,
            static fn (array $ticket) => $ticket['user_id'] === $userId
        ));
    }

    return array_map(
        static fn (array $ticket) => ticket_transform($ticket, $userId, $isAdmin),
        $tickets
    );
}
