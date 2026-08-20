<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña — Panel Technoliner</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/admin.css') ?>">
</head>
<body>
<div class="auth-shell">
    <div class="auth-card">
        <h1>Recuperar contraseña</h1>
        <p class="subtitle">Te enviaremos un enlace de restablecimiento.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php $errors = session()->getFlashdata('errors') ?? []; ?>

        <form action="<?= site_url('admin/recuperar') ?>" method="post">
            <?= csrf_field() ?>
            <div class="field">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required value="<?= esc(old('email')) ?>">
                <?php if (isset($errors['email'])): ?><div class="field-error"><?= esc($errors['email']) ?></div><?php endif; ?>
            </div>
            <button type="submit" class="btn btn-block">Enviar enlace</button>
        </form>

        <div class="auth-links">
            <a href="<?= site_url('admin/login') ?>">Volver a iniciar sesión</a>
        </div>
    </div>
</div>
</body>
</html>
