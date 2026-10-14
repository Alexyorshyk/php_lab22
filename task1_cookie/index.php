<?php

declare(strict_types=1);
$cookieName = 'lab2_username';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        setcookie($cookieName, '', time() - 3600, '/');
        header('Location: index.php');
        exit;
    }
    $name = trim((string)($_POST['name'] ?? ''));
    if ($name !== '') {
        setcookie($cookieName, $name, ['expires' => time() + 7 * 24 * 60 * 60, 'path' => '/', 'samesite' => 'Lax']);
        header('Location: index.php');
        exit;
    }
}
$name = (string)($_COOKIE[$cookieName] ?? '');
?>
<!doctype html>
<html lang="uk">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Завдання 1</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <main class="container">
        <div class="topbar"><a class="back" href="../index.html">← На головну</a><span class="badge">$_COOKIE</span></div>
        <section class="card">
            <h1>Збереження імені</h1>
            <?php if ($name !== ''): ?><p class="success">Вітаю, <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>!</p>
                <p>Ім’я збережене в cookie на 7 днів.</p>
                <form method="post"><button class="danger" name="delete" value="1">Видалити cookie</button></form>
            <?php else: ?><form method="post">
                    <div><label for="name">Ваше ім’я</label><input id="name" name="name" required maxlength="50" autocomplete="name"></div><button>Зберегти на 7 днів</button>
                </form><?php endif; ?>
        </section>
    </main>
</body>

</html>