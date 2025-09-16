<?php
declare(strict_types=1);

require_once __DIR__ . '/security.php';

const TICKET_STORAGE_FILE = __DIR__ . '/../storage/arquivo.hd';
const TICKET_CATEGORIES = [
    'Criação Usuário',
    'Impressora',
    'Hardware',
    'Software',
    'Rede',
];

function getTicketCategories(): array
{
    return TICKET_CATEGORIES;
}

function ensureTicketStorage(): void
{
    $directory = dirname(TICKET_STORAGE_FILE);

    if (!is_dir($directory)) {
        if (!mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('Não foi possível criar o diretório de armazenamento.');
        }
    }

    if (!file_exists(TICKET_STORAGE_FILE)) {
        if (file_put_contents(TICKET_STORAGE_FILE, '') === false) {
            throw new RuntimeException('Não foi possível inicializar o arquivo de chamados.');
        }
    }
}

function parseTicketLine(string $line): ?array
{
    $line = trim($line);
    if ($line === '') {
        return null;
    }

    $parts = explode('#', $line);
    if (count($parts) < 4) {
        return null;
    }

    $authorId = (int) array_shift($parts);
    $title = (string) ($parts[0] ?? '');
    $category = (string) ($parts[1] ?? '');
    $description = (string) ($parts[2] ?? '');
    $createdAt = $parts[3] ?? null;

    return [
        'author_id' => $authorId,
        'title' => $title,
        'category' => $category,
        'description' => $description,
        'created_at' => $createdAt,
    ];
}

function loadTickets(): array
{
    ensureTicketStorage();

    $tickets = [];
    $handle = fopen(TICKET_STORAGE_FILE, 'rb');

    if ($handle === false) {
        throw new RuntimeException('Não foi possível abrir o arquivo de chamados.');
    }

    while (($line = fgets($handle)) !== false) {
        $ticket = parseTicketLine($line);
        if ($ticket !== null) {
            $tickets[] = $ticket;
        }
    }

    fclose($handle);

    return $tickets;
}

function validateTicketPayload(array $input): array
{
    $data = [
        'titulo' => sanitizeText($input['titulo'] ?? '', 120),
        'categoria' => sanitizeText($input['categoria'] ?? '', 60),
        'descricao' => sanitizeText($input['descricao'] ?? '', 1000),
    ];

    $errors = [];

    if ($data['titulo'] === '') {
        $errors['titulo'] = 'Informe o título do chamado.';
    }

    if ($data['categoria'] === '' || !in_array($data['categoria'], TICKET_CATEGORIES, true)) {
        $errors['categoria'] = 'Selecione uma categoria válida.';
    }

    if ($data['descricao'] === '') {
        $errors['descricao'] = 'Adicione uma descrição para o chamado.';
    }

    return ['data' => $data, 'errors' => $errors];
}

function appendTicket(int $userId, array $ticketData): array
{
    ensureTicketStorage();

    $ticket = [
        'author_id' => $userId,
        'title' => $ticketData['titulo'],
        'category' => $ticketData['categoria'],
        'description' => $ticketData['descricao'],
        'created_at' => date(DATE_ATOM),
    ];

    $serialized = implode('#', [
        $ticket['author_id'],
        $ticket['title'],
        $ticket['category'],
        $ticket['description'],
        $ticket['created_at'],
    ]) . PHP_EOL;

    if (file_put_contents(TICKET_STORAGE_FILE, $serialized, FILE_APPEND | LOCK_EX) === false) {
        throw new RuntimeException('Não foi possível gravar o chamado.');
    }

    return $ticket;
}

function listTicketsForUser(int $userId, int $profileId): array
{
    $tickets = loadTickets();

    $filtered = array_filter($tickets, static function (array $ticket) use ($userId, $profileId): bool {
        if ($profileId === 2 && $ticket['author_id'] !== $userId) {
            return false;
        }

        return true;
    });

    return array_values(array_map('formatTicketForResponse', $filtered));
}

function formatTicketForResponse(array $ticket): array
{
    return [
        'autor_id' => $ticket['author_id'],
        'titulo' => $ticket['title'],
        'categoria' => $ticket['category'],
        'descricao' => $ticket['description'],
        'criado_em' => $ticket['created_at'],
    ];
}
