<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($titulo ?? 'Panel') ?> — Panel Technoliner</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/admin.css') ?>">
    <?= $extraHead ?? '' ?>
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="brand">Technoliner</div>
        <nav>
            <a href="<?= site_url('admin') ?>">Inicio</a>
            <a href="<?= site_url('admin/usuarios') ?>">Usuarios</a>
            <a href="<?= site_url('admin/blog/articulos') ?>">Artículos del blog</a>
            <a href="<?= site_url('admin/blog/categorias') ?>">Categorías del blog</a>
            <a href="<?= site_url('admin/productos') ?>">Productos</a>
            <a href="<?= site_url('admin/productos/categorias') ?>">Categorías de productos</a>
        </nav>
    </aside>
    <div class="admin-main">
        <div class="admin-topbar">
            <div><?= esc($titulo ?? '') ?></div>
            <div>
                <span><?= esc($usuario['nombre'] ?? '') ?></span>
                &middot;
                <form class="inline" action="<?= site_url('admin/logout') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-secondary" style="padding:4px 10px;font-size:.8rem;">Salir</button>
                </form>
            </div>
        </div>
        <div class="admin-content">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('mensaje')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('mensaje')) ?></div>
            <?php endif; ?>

            <?= $contenido ?? '' ?>
        </div>
    </div>
</div>
<?= $extraScripts ?? '' ?>
</body>
</html>
