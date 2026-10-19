<?php
$page_title = "Личный кабинет - SoundCloud21";
include 'includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['audio_file'])) {
    $title = trim($_POST['title']);
    $genre = trim($_POST['genre']);
    $user_id = 1;

    if ($title !== '' && $genre !== '' && isset($_FILES['audio_file']['tmp_name'])) {
        $file_ext = strtolower(pathinfo($_FILES['audio_file']['name'], PATHINFO_EXTENSION));
        $allowed_audio = ['mp3', 'wav', 'ogg', 'm4a'];
        
        if (in_array($file_ext, $allowed_audio)) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $audio_filename = uniqid('track_') . '.' . $file_ext;
            $audio_dest = $upload_dir . $audio_filename;
            
            $cover_dest = null;
            if (isset($_FILES['cover_file']) && $_FILES['cover_file']['error'] === UPLOAD_ERR_OK) {
                $cover_ext = strtolower(pathinfo($_FILES['cover_file']['name'], PATHINFO_EXTENSION));
                if (in_array($cover_ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $cover_filename = uniqid('cover_') . '.' . $cover_ext;
                    $cover_dest = $upload_dir . $cover_filename;
                    move_uploaded_file($_FILES['cover_file']['tmp_name'], $cover_dest);
                }
            }
            
            if (move_uploaded_file($_FILES['audio_file']['tmp_name'], $audio_dest)) {
                $stmt = $pdo->prepare("INSERT INTO tracks (user_id, title, genre, file_path, cover_path) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$user_id, $title, $genre, $audio_dest, $cover_dest]);
                $message = "<p class='success-msg'>Трек успешно загружен!</p>";
            } else {
                $message = "<p class='error-msg'>Ошибка при загрузке аудиофайла.</p>";
            }
        } else {
            $message = "<p class='error-msg'>Неверный формат аудиофайла.</p>";
        }
    } else {
        $message = "<p class='error-msg'>Заполните все обязательные поля.</p>";
    }
}

$stats_user = $pdo->query("SELECT created_at FROM users WHERE id = 1")->fetch();
$stats_tracks = $pdo->query("SELECT COUNT(*) as cnt, SUM(plays_count) as sum_plays FROM tracks WHERE user_id = 1")->fetch();
?>

<h2>Личный кабинет</h2>

<?php echo $message; ?>

<div class="dashboard-grid" style="margin-top:20px;">
    <section class="dashboard-card">
        <h3>Загрузить новый трек</h3>
        <form class="upload-form" action="dashboard.php" method="POST" enctype="multipart/form-data">
            <label>Название трека:</label>
            <input type="text" name="title" placeholder="Введите название" required>

            <label>Жанр:</label>
            <select name="genre" required>
                <option value="Рок">Рок</option>
                <option value="Поп">Поп</option>
                <option value="Хип-хоп">Хип-хоп</option>
                <option value="Электроника">Электроника</option>
                <option value="Инди">Инди</option>
                <option value="Ambient">Ambient</option>
            </select>

            <label>Аудиофайл (MP3, WAV):</label>
            <input type="file" name="audio_file" accept="audio/*" required>

            <label>Обложка трека (Картинка JPG/PNG):</label>
            <input type="file" name="cover_file" accept="image/*">

            <button type="submit" class="btn-submit">Загрузить</button>
        </form>
    </section>

    <section class="dashboard-card">
        <h3>Статистика аккаунта</h3>
        <p style="margin-top:10px;">Дата регистрации: <strong><?php echo date('d.m.Y', strtotime($stats_user['created_at'])); ?></strong></p>
        <p style="margin-top:5px;">Загружено треков: <strong><?php echo $stats_tracks['cnt']; ?></strong></p>
        <p style="margin-top:5px;">Всего прослушиваний: <strong><?php echo (int)$stats_tracks['sum_plays']; ?></strong></p>
    </section>
</div>

<?php include 'includes/footer.php'; ?>