document.addEventListener('DOMContentLoaded', () => {
    const audio = document.getElementById('audioElement');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const shuffleBtn = document.getElementById('shuffleBtn');
    const loopBtn = document.getElementById('loopBtn');
    const progressBar = document.getElementById('progressBar');
    const volumeBar = document.getElementById('volumeBar');
    const currentTimeEl = document.getElementById('currentTime');
    const durationEl = document.getElementById('duration');
    const playerTitle = document.getElementById('playerTitle');
    const playerArtist = document.getElementById('playerArtist');
    const playerThumb = document.querySelector('.player-thumb');

    if (!audio) return;

    let playlist = [];
    let currentIndex = 0;
    let isShuffle = false;
    let isLoop = false;

    // Сканируем все карточки треков на странице
    const trackItems = document.querySelectorAll('.track-card-item');

    trackItems.forEach((item, index) => {
        const src = item.getAttribute('data-src');
        const title = item.getAttribute('data-title') || 'Без названия';
        const artist = item.getAttribute('data-artist') || 'Неизвестен';
        const cover = item.getAttribute('data-cover') || '';

        if (!src) return;

        playlist.push({ title, artist, src, cover });

        // Клик по кнопке play на карточке
        const playBtn = item.querySelector('.card-play-btn');
        if (playBtn) {
            playBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                currentIndex = index;
                loadTrack(currentIndex);
                playTrack();
            });
        }

        // Клик по самой карточке тоже запускает трек
        item.addEventListener('click', () => {
            currentIndex = index;
            loadTrack(currentIndex);
            playTrack();
        });
    });

    function loadTrack(index) {
        if (!playlist[index]) return;
        const track = playlist[index];
        audio.src = track.src;
        playerTitle.textContent = track.title;
        playerArtist.textContent = track.artist;

        // Вывод обложки в нижний плеер
        if (playerThumb) {
            if (track.cover && track.cover.trim() !== '') {
                playerThumb.innerHTML = `<img src="${track.cover}" alt="cover" style="width:100%; height:100%; object-fit:cover; border-radius:6px;">`;
            } else {
                playerThumb.innerHTML = '🎵';
            }
        }
        audio.load();
    }

    function playTrack() {
        audio.play().then(() => {
            playPauseBtn.textContent = '⏸';
        }).catch(err => console.log("Ошибка воспроизведения:", err));
    }

    function pauseTrack() {
        audio.pause();
        playPauseBtn.textContent = '▶';
    }

    // Кнопка Старт/Пауза
    playPauseBtn.addEventListener('click', () => {
        if (!audio.src && playlist.length > 0) {
            loadTrack(0);
        }
        if (audio.paused) {
            playTrack();
        } else {
            pauseTrack();
        }
    });

    // Следующий трек
    nextBtn.addEventListener('click', () => {
        if (playlist.length === 0) return;
        if (isShuffle) {
            currentIndex = Math.floor(Math.random() * playlist.length);
        } else {
            currentIndex = (currentIndex + 1) % playlist.length;
        }
        loadTrack(currentIndex);
        playTrack();
    });

    // Предыдущий трек
    prevBtn.addEventListener('click', () => {
        if (playlist.length === 0) return;
        currentIndex = (currentIndex - 1 + playlist.length) % playlist.length;
        loadTrack(currentIndex);
        playTrack();
    });

    // Перемешать (Shuffle)
    shuffleBtn.addEventListener('click', () => {
        isShuffle = !isShuffle;
        shuffleBtn.style.color = isShuffle ? '#00b37e' : '#c4c4cc';
    });

    // Зациклить (Loop)
    loopBtn.addEventListener('click', () => {
        isLoop = !isLoop;
        audio.loop = isLoop;
        loopBtn.style.color = isLoop ? '#00b37e' : '#c4c4cc';
    });

    // Обновление таймера и прогресс-бара
    audio.addEventListener('timeupdate', () => {
        if (audio.duration && !isNaN(audio.duration)) {
            const progress = (audio.currentTime / audio.duration) * 100;
            progressBar.value = progress;
            currentTimeEl.textContent = formatTime(audio.currentTime);
            durationEl.textContent = formatTime(audio.duration);
        }
    });

    // Перемотка трека ползунком
    progressBar.addEventListener('input', () => {
        if (audio.duration && !isNaN(audio.duration)) {
            audio.currentTime = (progressBar.value / 100) * audio.duration;
        }
    });

    // Громкость
    if (volumeBar) {
        volumeBar.addEventListener('input', () => {
            audio.volume = volumeBar.value;
        });
    }

    // Автоматический переход к следующему треку по окончании
    audio.addEventListener('ended', () => {
        if (!isLoop) {
            nextBtn.click();
        }
    });

    function formatTime(secs) {
        if (isNaN(secs)) return "0:00";
        const m = Math.floor(secs / 60);
        const s = Math.floor(secs % 60);
        return `${m}:${s < 10 ? '0' : ''}${s}`;
    }
});