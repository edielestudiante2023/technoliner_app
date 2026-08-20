<?php $c = $categoria ?? []; ?>
<div class="card" style="max-width:560px;">
    <h2><?= $modo === 'crear' ? 'Nueva categoría' : 'Editar categoría' ?></h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <form action="<?= $modo === 'crear' ? site_url('admin/productos/categorias') : site_url('admin/productos/categorias/' . $c['id']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="field">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required value="<?= esc(old('nombre', $c['nombre'] ?? '')) ?>">
            <?php if (isset($errors['nombre'])): ?><div class="field-error"><?= esc($errors['nombre']) ?></div><?php endif; ?>
        </div>

        <div class="field">
            <label for="parent_id">Categoría padre</label>
            <select id="parent_id" name="parent_id" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;">
                <option value="">Ninguna (categoría principal)</option>
                <?php foreach ($principales as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= (old('parent_id', $c['parent_id'] ?? '') == $p['id']) ? 'selected' : '' ?>>
                        <?= esc($p['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label for="descripcion">Descripción</label>
            <input type="text" id="descripcion" name="descripcion" value="<?= esc(old('descripcion', $c['descripcion'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="orden">Orden</label>
            <input type="number" id="orden" name="orden" value="<?= esc(old('orden', $c['orden'] ?? 0)) ?>">
        </div>

        <div class="field">
            <label for="seo_titulo">Título SEO</label>
            <input type="text" id="seo_titulo" name="seo_titulo" maxlength="70" value="<?= esc(old('seo_titulo', $c['seo_titulo'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="seo_descripcion">Meta descripción</label>
            <input type="text" id="seo_descripcion" name="seo_descripcion" maxlength="170" value="<?= esc(old('seo_descripcion', $c['seo_descripcion'] ?? '')) ?>">
        </div>

        <button type="submit" class="btn">Guardar</button>
        <a href="<?= site_url('admin/productos/categorias') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
