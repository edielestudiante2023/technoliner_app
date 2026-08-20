<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer contraseña — Panel Technoliner</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/admin.css') ?>">
</head>
<body>
<div class="auth-shell">
    <div class="auth-card">
        <h1>Nueva contraseña</h1>
        <p class="subtitle">Mínimo 12 caracteres.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php $errors = session()->getFlashdata('errors') ?? []; ?>

        <form action="<?= site_url('admin/restablecer/' . $token) ?>" method="post">
            <?= csrf_field() ?>
            <div class="field">
                <label for="password">Nueva contraseña</label>
                <input type="password" id="password" name="password" required minlength="12">
                <?php if (isset($errors['password'])): ?><div class="field-error"><?= esc($errors['password']) ?></div><?php endif; ?>
            </div>
            <div class="field">
                <label for="password_confirm">Confirmar contraseña</label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="12">
                <?php if (isset($errors['password_confirm'])): ?><div class="field-error"><?= esc($errors['password_confirm']) ?></div><?php endif; ?>
            </div>
            <button type="submit" class="btn btn-block">Guardar contraseña</button>
        </form>
    </div>
</div>
</body>
</html>
