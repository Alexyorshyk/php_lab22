<?php

declare(strict_types=1);
session_start();
const TIMEOUT = 300;
const HISTORY_COOKIE = 'lab2_previous_purchases';
$now = time();
$timedOut = false;
if (isset($_SESSION['last_activity']) && $now - (int)$_SESSION['last_activity'] > TIMEOUT) {
    $_SESSION = [];
    session_destroy();
    session_start();
    $timedOut = true;
}
$_SESSION['last_activity'] = $now;
$_SESSION['cart'] = $_SESSION['cart'] ?? [];
$products = ['notebook' => ['Ноутбук', 28000], 'mouse' => ['Миша', 700], 'keyboard' => ['Клавіатура', 1400]];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string)($_POST['action'] ?? '');
    $id = (string)($_POST['product'] ?? '');
    if ($action === 'add' && isset($products[$id])) $_SESSION['cart'][] = $id;
    if ($action === 'remove' && isset($_SESSION['cart'][(int)($_POST['index'] ?? -1)])) {
        unset($_SESSION['cart'][(int)$_POST['index']]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    if ($action === 'clear') $_SESSION['cart'] = [];
    if ($action === 'checkout' && $_SESSION['cart']) {
        $names = array_map(fn($x) => $products[$x][0], $_SESSION['cart']);
        setcookie(HISTORY_COOKIE, json_encode($names, JSON_UNESCAPED_UNICODE), ['expires' => time() + 30 * 24 * 60 * 60, 'path' => '/', 'samesite' => 'Lax']);
        $_SESSION['cart'] = [];
    }
    header('Location: index.php');
    exit;
}
$history = json_decode((string)($_COOKIE[HISTORY_COOKIE] ?? '[]'), true);
if (!is_array($history)) $history = [];
$total = 0;
foreach ($_SESSION['cart'] as $id) if (isset($products[$id])) $total += $products[$id][1];
?>
<!doctype html>
<html lang="uk">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Корзина</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <main class="container">
        <div class="topbar"><a class="back" href="../index.html">← На головну</a><span class="badge">$_COOKIE + $_SESSION</span></div><?php if ($timedOut): ?><div class="notice warning">Сесію завершено через 5 хвилин неактивності.</div><?php endif; ?><div class="grid">
            <section class="card">
                <h2>Товари</h2><?php foreach ($products as $id => [$name, $price]): ?><div class="product">
                        <div><strong><?= htmlspecialchars($name) ?></strong><br><span class="muted"><?= number_format($price, 0, ',', ' ') ?> грн</span></div>
                        <form method="post"><input type="hidden" name="action" value="add"><button name="product" value="<?= htmlspecialchars($id) ?>">Додати</button></form>
                    </div>
                    <hr><?php endforeach; ?>
            </section>
            <section class="card">
                <h2>Поточна корзина</h2><?php if (!$_SESSION['cart']): ?><p class="muted">Корзина порожня.</p><?php else: ?><ul class="list"><?php foreach ($_SESSION['cart'] as $i => $id): ?><li>
                                <div class="product"><span><?= htmlspecialchars($products[$id][0]) ?></span>
                                    <form method="post"><input type="hidden" name="action" value="remove"><button class="danger" name="index" value="<?= $i ?>">×</button></form>
                                </div>
                            </li><?php endforeach; ?></ul>
                    <p><strong>Разом: <?= number_format($total, 0, ',', ' ') ?> грн</strong></p>
                    <div class="row">
                        <form method="post"><button name="action" value="checkout">Оформити покупку</button></form>
                        <form method="post"><button class="secondary" name="action" value="clear">Очистити</button></form>
                    </div><?php endif; ?>
            </section>
            <section class="card">
                <h2>Попередні покупки</h2><?php if (!$history): ?><p class="muted">У cookie ще немає покупок.</p><?php else: ?><ul><?php foreach ($history as $item): ?><li><?= htmlspecialchars((string)$item) ?></li><?php endforeach; ?></ul><?php endif; ?><p class="muted">Сесія завершується після 5 хвилин неактивності.</p>
            </section>
        </div>
    </main>
</body>

</html>