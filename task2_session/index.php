<?php

declare(strict_types=1);
session_start();
const DEMO_LOGIN = 'student';
const DEMO_PASSWORD = '12345';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: ../index.html');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $login = trim((string)$_POST['login']);
    $password = (string)($_POST['password'] ?? '');
    if ($login === DEMO_LOGIN && $password === DEMO_PASSWORD) {
        session_regenerate_id(true);
        $_SESSION['user'] = $login;
        header('Location: index.php');
        exit;
    }
    $error = 'Неправильний логін або пароль.';
}
$user = $_SESSION['user'] ?? null;
?>
<!doctype html>
<html lang="uk">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Завдання 2</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <main class="container">
        <div class="topbar"><a class="back" href="../index.html">← На головну</a><span class="badge">$_SESSION</span></div>
        <section class="card">
            <h1>Авторизація</h1>
            <?php if ($user): ?><p class="success">Вітаю, <?= htmlspecialchars((string)$user, ENT_QUOTES, 'UTF-8') ?>! Сесія активна.</p>
                <form method="post"><button class="danger" name="logout" value="1">Вихід</button></form>
            <?php else: ?><div class="notice warning">Тестові дані: <strong>student</strong> / <strong>12345</strong></div><?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?><form method="post">
                    <div><label for="login">Логін</label><input id="login" name="login" required></div>
                    <div><label for="password">Пароль</label><input id="password" type="password" name="password" required></div><button>Увійти</button>
                </form><?php endif; ?>
        </section>
    </main>
</body>

</html>