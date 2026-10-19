<?php
$page_title = "Мой профиль - SoundCloud21";
include 'includes/header.php';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = 1");
$stmt->execute();
$user = $stmt->fetch();

$stmt_tracks = $pdo->prepare("SELECT * FROM tracks WHERE user_id = 1 ORDER BY created_at DESC");
$stmt_tracks->execute();
$my_tracks = $stmt_tracks->fetchAll();
?>

<div class="profile-header" style="display:flex; align-items:center; gap:20px; background-color:#202024; padding:20px; border-radius:8px; margin-bottom:30px; border:1px solid #323238;">
    <div class="profile-avatar" style="font-size:2.5rem; background-color:#29292e; width:70px; height:70px; display:flex; align-items:center; justify-content:center; border-radius:50%;">👤</div>
    <div class="profile-details">
        <h2><?php echo htmlspecialchars($user['username']); ?></h2>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p class="reg-date">Дата регистрации: <?php echo date('d.m.Y', strtotime($user['created_at'])); ?></p>
    </div>
</div>

<section class="section-block">
    <h3>Мои загруженные треки</h3>
    <?php if (empty($my_tracks)): ?>
        <p class="empty-text">Вы еще ничего не загрузили. Перейдите в <a href="dashboard.php">Личный кабинет</a>.</p>
    <?php else: ?>
        <div class="tracks-grid">
            <?php foreach ($my_tracks as $track): ?>
                <div class="track-card-item" 
                     data-src="<?php echo htmlspecialchars($track['file_path']); ?>" 
                     data-title="<?php echo htmlspecialchars($track['title']); ?>" 
                     data-artist="<?php echo htmlspecialchars($user['username']); ?>" 
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
                        <p><?php echo htmlspecialchars($track['genre']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>