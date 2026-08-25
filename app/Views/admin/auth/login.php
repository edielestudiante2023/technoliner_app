<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Iniciar sesión — Panel Technoliner</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/admin.css') ?>">

    <!-- PWA -->
    <meta name="theme-color" content="#06297f">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Technoliner Admin">
    <link rel="manifest" href="<?= base_url('manifest_login.json') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/icons/apple-touch-icon.png') ?>">
</head>
<body>
<div class="auth-shell">
    <div class="auth-card">
        <h1>Panel administrativo</h1>
        <p class="subtitle">Technoliner SAS</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('mensaje')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('mensaje')) ?></div>
        <?php endif; ?>

        <?php $errors = session()->getFlashdata('errors') ?? []; ?>

        <form action="<?= site_url('admin/login') ?>" method="post">
            <?= csrf_field() ?>
            <div class="field">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required value="<?= esc(old('email')) ?>">
                <?php if (isset($errors['email'])): ?><div class="field-error"><?= esc($errors['email']) ?></div><?php endif; ?>
            </div>
            <div class="field">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
                <?php if (isset($errors['password'])): ?><div class="field-error"><?= esc($errors['password']) ?></div><?php endif; ?>
            </div>
            <button type="submit" class="btn btn-block">Ingresar</button>
        </form>

        <div class="auth-links">
            <a href="<?= site_url('admin/recuperar') ?>">¿Olvidaste tu contraseña?</a>
        </div>

        <div class="pwa-install-section" id="pwaInstallSection">
            <img src="<?= base_url('assets/icons/icon-192.png') ?>" alt="Technoliner" class="pwa-install-icon">
            <div class="pwa-install-info">
                <h5>Instala el panel</h5>
                <p>Acceso rápido desde la pantalla de inicio de tu dispositivo.</p>
                <button type="button" class="btn-pwa-install" id="pwaInstallBtn">
                    <span id="pwaInstallBtnText">Descargar app</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal iOS -->
<div class="pwa-ios-modal" id="pwaIosModal">
    <div class="pwa-ios-modal-content">
        <h4>Cómo instalar en iPhone/iPad</h4>
        <ol>
            <li>Toca el botón <strong>Compartir</strong> en la barra de Safari.</li>
            <li>Elige <strong>"Añadir a pantalla de inicio"</strong>.</li>
            <li>Confirma con <strong>Añadir</strong>.</li>
        </ol>
        <button type="button" class="btn-close-ios" id="pwaIosModalClose">Entendido</button>
    </div>
</div>

<script>
    (function () {
        var deferredPrompt = null;
        var section = document.getElementById('pwaInstallSection');
        var btn = document.getElementById('pwaInstallBtn');
        var btnText = document.getElementById('pwaInstallBtnText');
        var iosModal = document.getElementById('pwaIosModal');
        var iosClose = document.getElementById('pwaIosModalClose');

        var ua = window.navigator.userAgent;
        var isIOS = /iPad|iPhone|iPod/.test(ua) && !window.MSStream;
        var isStandalone = window.matchMedia('(display-mode: standalone)').matches
            || window.navigator.standalone === true;

        if (isStandalone) { return; }

        if (isIOS) {
            section.classList.add('visible');
            btnText.textContent = 'Cómo instalar';
            btn.addEventListener('click', function () { iosModal.classList.add('visible'); });
            iosClose.addEventListener('click', function () { iosModal.classList.remove('visible'); });
            iosModal.addEventListener('click', function (e) { if (e.target === iosModal) iosModal.classList.remove('visible'); });
            return;
        }

        window.addEventListener('beforeinstallprompt', function (e) {
            e.preventDefault();
            deferredPrompt = e;
            section.classList.add('visible');
        });

        btn.addEventListener('click', function () {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(function (choice) {
                if (choice.outcome === 'accepted') section.classList.remove('visible');
                deferredPrompt = null;
            });
        });

        window.addEventListener('appinstalled', function () {
            section.classList.remove('visible');
            deferredPrompt = null;
        });
    })();

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('<?= base_url('sw_login.js') ?>', {
                scope: '/admin/',
                updateViaCache: 'none',
            }).catch(function (err) { console.warn('SW login no registrado:', err); });
        });
    }
</script>
</body>
</html>
