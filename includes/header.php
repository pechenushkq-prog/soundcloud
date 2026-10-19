<?php include_once __DIR__ . '/db.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'SoundCloud21'; ?></title>
    <link rel="stylesheet" href="/soundcloud21/assets/css/style.css">
</head>
<body>
    <div class="app-wrapper">
        <header class="site-header">
            <div class="header-container">
                <a href="index.php" class="logo">🎵 SoundCloud21</a>
                
                <form action="explore.php" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="Поиск треков..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                    <button type="submit">Найти</button>
                </form>

                <nav class="main-nav">
                    <ul>
                        <li><a href="index.php">Главная</a></li>
                        <li><a href="explore.php">Изучать</a></li>
                        <li><a href="trending.php">Популярное</a></li>
                        <li><a href="profile.php">Мой профиль</a></li>
                        <li><a href="dashboard.php" class="btn-dashboard">Кабинет</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <main class="main-content">