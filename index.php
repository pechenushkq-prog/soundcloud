<?php
$page_title = "Главная - SoundCloud21";
include 'includes/header.php';

$stmt = $pdo->query("SELECT tracks.*, users.username FROM tracks JOIN users ON tracks.user_id = users.id ORDER BY created_at DESC LIMIT 8");
$tracks = $stmt->fetchAll();
?>

<section class="hero-banner">
    <h2>Добро пожаловать на SoundCloud21</h2>
    <p>Загружайте и слушайте любимую музыку.</p>
</section>

<section class="section-block">
    <h3>Недавно добавленные</h3>
    <?php if (empty($tracks)): ?>
        <p class="empty-text">Пока нет загруженных треков. Добавьте их через <a href="dashboard.php">Личный кабинет</a>.</p>
    <?php else: ?>
        <div class="tracks-grid">
            <?php foreach ($tracks as $track): ?>
                <div class="track-card-item" 
                     data-src="<?php echo htmlspecialchars($track['file_path']); ?>" 
                     data-title="<?php echo htmlspecialchars($track['title']); ?>" 
                     data-artist="<?php echo htmlspecialchars($track['username']); ?>" 
                     data-cover="<?php echo !empty($track['cover_path']) ? htmlspecialchars($track['cover_path']) : ''; ?>">
                    
                    <div class="track-cover-box">
                        <?php if (!empty($track['cover_path'])): ?>
                            <img src="<?php echo htmlspecialchars($track['cover_path']); ?>" alt="Cover">
                        <?php else: ?>
                            <span>🎵</span>
                        <?php endif; ?>
                        <button class="card-play-btn">▶</button>
                    </div>
                    
                    <div class="track-info">
                        <h4><?php echo htmlspecialchars($track['title']); ?></h4>
                        <p><?php echo htmlspecialchars($track['username']); ?> • <?php echo htmlspecialchars($track['genre']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>