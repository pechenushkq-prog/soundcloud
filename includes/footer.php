</main>

        <footer class="site-footer">
            <div class="footer-container">
                <p>&copy; <?php echo date('Y'); ?> SoundCloud21 — Аудиохостинг.</p>
            </div>
        </footer>
    </div>

    <!-- Фиксированный плеер -->
    <div class="sticky-player" id="stickyPlayer">
        <audio id="audioElement"></audio>
        <div class="player-container">
            
            <div class="player-track-info">
                <div class="player-thumb">🎵</div>
                <div class="player-meta">
                    <h4 id="playerTitle">Выберите трек</h4>
                    <p id="playerArtist">SoundCloud21</p>
                </div>
            </div>

            <div class="player-center">
                <div class="player-controls">
                    <button id="shuffleBtn" class="control-btn" title="Перемешать">🔀</button>
                    <button id="prevBtn" class="control-btn" title="Предыдущий">⏮</button>
                    <button id="playPauseBtn" class="control-btn play-main" title="Играть">▶</button>
                    <button id="nextBtn" class="control-btn" title="Следующий">⏭</button>
                    <button id="loopBtn" class="control-btn" title="Зациклить">🔁</button>
                </div>
                
                <div class="player-progress-container">
                    <span id="currentTime">0:00</span>
                    <input type="range" id="progressBar" value="0" max="100" step="0.1">
                    <span id="duration">0:00</span>
                </div>
            </div>

            <div class="player-volume">
                <span>🔊</span>
                <input type="range" id="volumeBar" min="0" max="1" step="0.05" value="1">
            </div>

        </div>
    </div>

    <script src="/soundcloud21/assets/js/main.js"></script>
</body>
</html>