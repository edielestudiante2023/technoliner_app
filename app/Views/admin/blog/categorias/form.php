<?php $c = $categoria ?? []; ?>
<div class="card" style="max-width:480px;">
    <h2><?= $modo === 'crear' ? 'Nueva categoría' : 'Editar categoría' ?></h2>

    <form action="<?= $modo === 'crear' ? site_url('admin/blog/categorias') : site_url('admin/blog/categorias/' . $c['id']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="field">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required value="<?= esc(old('nombre', $c['nombre'] ?? '')) ?>">
            <?php if (isset($errors['nombre'])): ?><div class="field-error"><?= esc($errors['nombre']) ?></div><?php endif; ?>
        </div>

        <div class="field">
            <label for="descripcion">Descripción</label>
            <input type="text" id="descripcion" name="descripcion" value="<?= esc(old('descripcion', $c['descripcion'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="orden">Orden</label>
            <input type="number" id="orden" name="orden" value="<?= esc(old('orden', $c['orden'] ?? 0)) ?>">
        </div>

        <button type="submit" class="btn">Guardar</button>
        <a href="<?= site_url('admin/blog/categorias') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
