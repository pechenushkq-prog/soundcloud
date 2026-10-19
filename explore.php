<?php
$page_title = "Изучать - SoundCloud21";
include 'includes/header.php';

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$genre = isset($_GET['genre']) ? trim($_GET['genre']) : '';

$sql = "SELECT tracks.*, users.username FROM tracks JOIN users ON tracks.user_id = users.id WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (tracks.title LIKE ? OR users.username LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($genre !== '') {
    $sql .= " AND tracks.genre = ?";
    $params[] = $genre;
}

$sql .= " ORDER BY tracks.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tracks = $stmt->fetchAll();

$genres_list = ['Рок', 'Поп', 'Хип-хоп', 'Электроника', 'Инди', 'Ambient'];
?>

<h2>Каталог и Жанры</h2>

<div class="explore-container" style="display:flex; gap:20px; margin-top:20px;">
    <aside class="sidebar-filters" style="width:220px; background-color:#202024; padding:15px; border-radius:6px; border:1px solid #323238;">
        <h3>Жанры</h3>
        <ul style="list-style:none; margin-top:10px;">
            <li style="margin-bottom:8px;"><a href="explore.php" style="color:<?php echo $genre === '' ? '#00b37e' : '#c4c4cc'; ?>; text-decoration:none;">Все жанры</a></li>
            <?php foreach ($genres_list as $g): ?>
                <li style="margin-bottom:8px;"><a href="explore.php?genre=<?php echo urlencode($g); ?>" style="color:<?php echo $genre === $g ? '#00b37e' : '#c4c4cc'; ?>; text-decoration:none;"><?php echo $g; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <section class="explore-results" style="flex:1;">
        <h3><?php echo $genre ? "Жанр: " . htmlspecialchars($genre) : "Все треки"; ?></h3>
        
        <?php if (empty($tracks)): ?>
            <p class="empty-text" style="margin-top:15px;">Треки не найдены.</p>
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
</div>

<?php include 'includes/footer.php'; ?>