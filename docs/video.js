document.getElementById('welcome-video').addEventListener('ended', function() {
    showMainContent();
});

function skipVideo() {
    document.getElementById('welcome-video').pause();
    showMainContent();
}

function showMainContent() {
    document.getElementById('welcome-screen').style.display = 'none';
    document.body.style.overflow = 'auto';  // Permitir el desplazamiento
    document.getElementById('main-content').style.display = 'block';
}
