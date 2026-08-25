<?php $c = $cliente ?? []; ?>
<div class="card" style="max-width:560px;">
    <h2><?= $modo === 'crear' ? 'Nuevo cliente' : 'Editar cliente' ?></h2>

    <form action="<?= $modo === 'crear' ? site_url('admin/clientes') : site_url('admin/clientes/' . $c['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="field">
            <label for="nombre">Nombre del cliente</label>
            <input type="text" id="nombre" name="nombre" required value="<?= esc(old('nombre', $c['nombre'] ?? '')) ?>">
            <?php if (isset($errors['nombre'])): ?><div class="field-error"><?= esc($errors['nombre']) ?></div><?php endif; ?>
        </div>

        <div class="field">
            <label for="logo">Logo (JPG, PNG o WebP, máx. 2MB)</label>
            <?php if (! empty($c['logo'])): ?>
                <div style="margin-bottom:8px;">
                    <img src="<?= base_url('uploads/clientes/' . $c['logo']) ?>" alt="" style="max-height:60px;display:block;">
                </div>
            <?php endif; ?>
            <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp">
            <?php if (isset($errors['logo'])): ?><div class="field-error"><?= esc($errors['logo']) ?></div><?php endif; ?>
        </div>

        <div class="field">
            <label for="logo_alt">Texto alternativo del logo</label>
            <input type="text" id="logo_alt" name="logo_alt" value="<?= esc(old('logo_alt', $c['logo_alt'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="orden">Orden (menor número aparece primero)</label>
            <input type="number" id="orden" name="orden" value="<?= esc(old('orden', $c['orden'] ?? 0)) ?>">
        </div>

        <button type="submit" class="btn">Guardar</button>
        <a href="<?= site_url('admin/clientes') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
