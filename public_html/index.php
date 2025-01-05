<?php

include "../engine/connectSQL.php";
include '../engine/getPage.php';
include '../config/const.php';

$pages = include '../config/pages.php';
$page = getPage($pages);

session_start();

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>PHP Галерея</title>
</head>
<body>
<header class="header">
    <a class="logo" href="?page=gallery">Главная</a>
    <div class="auth-buttons">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="?page=sign-in" class="btn">Войти</a>
            <a href="?page=sign-up" class="btn">Зарегистрироваться</a>
        <?php else: ?>
            <a href="?page=logout" class="btn">Выйти</a>
        <?php endif; ?>
    </div>
</header>

<?php

include '../tamplates/' . $page;

?>
</body>
</html>
