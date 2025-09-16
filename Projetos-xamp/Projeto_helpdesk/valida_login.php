<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/security.php';

ensureSessionStarted();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: index.php');
    exit;
}

try {
    requireCsrfToken($_POST['csrf_token'] ?? null);
} catch (RuntimeException $exception) {
    setFlash('error', 'Sessão expirada. Tente novamente.');
    header('Location: index.php');
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = trim((string) ($_POST['senha'] ?? ''));

if ($email === false) {
    $email = null;
}

if ($email === null || $password === '') {
    setFlash('error', 'Informe um e-mail válido e a senha.');
    $_SESSION['last_email'] = $email ?? '';
    header('Location: index.php');
    exit;
}

$users = [
    ['id' => 1, 'email' => 'adm@teste.com.br', 'senha' => '$2y$12$IOBgU8kUGb167vreTHOYhOz3qysKVaLn0X8K33p0ygS1dqPjFidyS', 'perfil_id' => 1],
    ['id' => 2, 'email' => 'user@teste.com.br', 'senha' => '$2y$12$IOBgU8kUGb167vreTHOYhOz3qysKVaLn0X8K33p0ygS1dqPjFidyS', 'perfil_id' => 1],
    ['id' => 3, 'email' => 'jose@teste.com.br', 'senha' => '$2y$12$IOBgU8kUGb167vreTHOYhOz3qysKVaLn0X8K33p0ygS1dqPjFidyS', 'perfil_id' => 2],
    ['id' => 4, 'email' => 'maria@teste.com.br', 'senha' => '$2y$12$IOBgU8kUGb167vreTHOYhOz3qysKVaLn0X8K33p0ygS1dqPjFidyS', 'perfil_id' => 2],
];

$authenticatedUser = null;

foreach ($users as $user) {
    if (strcasecmp($user['email'], $email) === 0 && password_verify($password, $user['senha'])) {
        $authenticatedUser = $user;
        break;
    }
}

if ($authenticatedUser !== null) {
    $_SESSION['autenticado'] = 'SIM';
    $_SESSION['id'] = $authenticatedUser['id'];
    $_SESSION['perfil_id'] = $authenticatedUser['perfil_id'];

    session_regenerate_id(true);
    regenerateCsrfToken();
    unset($_SESSION['last_email']);

    header('Location: home.php');
    exit;
}

$_SESSION['autenticado'] = 'NAO';
setFlash('error', 'Usuário ou senha inválido(s).');
$_SESSION['last_email'] = $email;
header('Location: index.php');
exit;
