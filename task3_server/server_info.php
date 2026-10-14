<?php

declare(strict_types=1);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form.php');
    exit;
}
$data = [
    'IP-адреса клієнта' => $_SERVER['REMOTE_ADDR'] ?? 'Невідомо',
    'Браузер / User-Agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Невідомо',
    'Скрипт (PHP_SELF)' => $_SERVER['PHP_SELF'] ?? 'Невідомо',
    'Метод запиту' => $_SERVER['REQUEST_METHOD'] ?? 'Невідомо',
    'Шлях до файлу' => $_SERVER['SCRIPT_FILENAME'] ?? __FILE__,
];
?>
<!doctype html>
<html lang="uk">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Дані SERVER</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <main class="container">
        <div class="topbar"><a class="back" href="form.php">← Назад</a><span class="badge">POST</span></div>
        <section class="card">
            <h1>Дані з $_SERVER</h1>
            <table class="server-table">
                <tbody><?php foreach ($data as $label => $value): ?><tr>
                            <th><?= htmlspecialchars($label) ?></th>
                            <td><?= htmlspecialchars((string)$value) ?></td>
                        </tr><?php endforeach; ?></tbody>
            </table>
        </section>
    </main>
</body>

</html>