<?php
/** @var string $contentTemplate */
$page = $page ?? '';
$pageTitle = $pageTitle ?? 'App Help Desk';
$showNav = $showNav ?? true;
$mainClass = trim(($mainClass ?? 'py-5') . '');
$flashMessage = null;
if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/theme.css" />
</head>
<body class="bg-surface" data-page="<?= htmlspecialchars($page) ?>" data-flash="<?= htmlspecialchars($flashMessage ?? '') ?>">
<?php if ($showNav): ?>
    <?php include __DIR__ . '/partials/navbar.php'; ?>
<?php endif; ?>
    <main class="app-main <?= htmlspecialchars($mainClass) ?>">
        <div class="container">
            <?php include $contentTemplate; ?>
        </div>
    </main>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="appToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script type="module" src="assets/js/app.js"></script>
</body>
</html>
