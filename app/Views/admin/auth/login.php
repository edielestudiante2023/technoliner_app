<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión — Panel Technoliner</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
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
    </div>
</div>
</body>
</html>
