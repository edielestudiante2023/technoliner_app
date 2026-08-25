<?php $p = $producto ?? []; ?>
<div class="card" style="max-width:760px;">
    <h2><?= $modo === 'crear' ? 'Nuevo producto' : 'Editar producto' ?></h2>

    <form action="<?= $modo === 'crear' ? site_url('admin/productos') : site_url('admin/productos/' . $p['id']) ?>" method="post" id="form-producto">
        <?= csrf_field() ?>

        <div class="field">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required value="<?= esc(old('nombre', $p['nombre'] ?? '')) ?>">
            <?php if (isset($errors['nombre'])): ?><div class="field-error"><?= esc($errors['nombre']) ?></div><?php endif; ?>
        </div>

        <div class="field">
            <label for="categoria_id">Categoría</label>
            <select id="categoria_id" name="categoria_id" required style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;">
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (old('categoria_id', $p['categoria_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                        <?= $cat['parent_id'] ? '— ' : '' ?><?= esc($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['categoria_id'])): ?><div class="field-error"><?= esc($errors['categoria_id']) ?></div><?php endif; ?>
        </div>

        <div class="field">
            <label for="sku">SKU (opcional)</label>
            <input type="text" id="sku" name="sku" value="<?= esc(old('sku', $p['sku'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="resumen">Resumen para tarjeta</label>
            <input type="text" id="resumen" name="resumen" maxlength="500" value="<?= esc(old('resumen', $p['resumen'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="editor">Descripción</label>
            <div id="editor" style="background:#fff;"><?= $p['descripcion_html'] ?? '' ?></div>
            <input type="hidden" name="descripcion_html" id="descripcion_html">
        </div>

        <div class="field">
            <label for="seo_titulo">Título SEO</label>
            <input type="text" id="seo_titulo" name="seo_titulo" maxlength="70" value="<?= esc(old('seo_titulo', $p['seo_titulo'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="seo_descripcion">Meta descripción</label>
            <input type="text" id="seo_descripcion" name="seo_descripcion" maxlength="170" value="<?= esc(old('seo_descripcion', $p['seo_descripcion'] ?? '')) ?>">
        </div>

        <button type="submit" class="btn">Guardar</button>
        <a href="<?= site_url('admin/productos') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php if ($modo === 'editar'): ?>
<div class="card" style="max-width:760px;">
    <h2>Especificaciones</h2>

    <table class="admin-table" style="margin-bottom:16px;">
        <thead>
            <tr><th>Nombre</th><th>Valor</th><th>Unidad</th><th></th></tr>
        </thead>
        <tbody>
        <?php foreach ($especificaciones as $e): ?>
            <tr>
                <td><?= esc($e['nombre']) ?></td>
                <td><?= esc($e['valor']) ?></td>
                <td><?= esc($e['unidad'] ?? '') ?></td>
                <td>
                    <form class="inline" action="<?= site_url('admin/productos/' . $p['id'] . '/especificaciones/' . $e['id'] . '/eliminar') ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-link" style="background:none;border:none;color:var(--danger);cursor:pointer;text-decoration:underline;">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($especificaciones)): ?>
            <tr><td colspan="4" style="color:var(--muted);">Sin especificaciones todavía.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <form action="<?= site_url('admin/productos/' . $p['id'] . '/especificaciones') ?>" method="post" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <?= csrf_field() ?>
        <div class="field" style="margin:0;">
            <label>Nombre</label>
            <input type="text" name="nombre" placeholder="Diámetro" required>
        </div>
        <div class="field" style="margin:0;">
            <label>Valor</label>
            <input type="text" name="valor" placeholder="38" required>
        </div>
        <div class="field" style="margin:0;">
            <label>Unidad</label>
            <input type="text" name="unidad" placeholder="mm">
        </div>
        <button type="submit" class="btn">Agregar</button>
    </form>
</div>

<div class="card" style="max-width:760px;">
    <h2>Galería de imágenes</h2>

    <div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:20px;">
        <?php foreach ($imagenes as $img): ?>
            <div style="width:160px;">
                <img src="<?= base_url('uploads/productos/' . $img['ruta']) ?>" alt="<?= esc($img['alt_text'] ?? '') ?>" style="width:100%;height:120px;object-fit:cover;border-radius:8px;">
                <div style="font-size:.8rem;margin-top:6px;">
                    <?php if ((int) $img['es_principal'] === 1): ?>
                        <span class="badge badge-success">Principal</span>
                    <?php else: ?>
                        <form class="inline" action="<?= site_url('admin/productos/' . $p['id'] . '/imagenes/' . $img['id'] . '/principal') ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn-link" style="background:none;border:none;color:var(--primary);cursor:pointer;text-decoration:underline;">Marcar principal</button>
                        </form>
                    <?php endif; ?>
                </div>
                <form class="inline" action="<?= site_url('admin/productos/' . $p['id'] . '/imagenes/' . $img['id'] . '/eliminar') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-link" style="background:none;border:none;color:var(--danger);cursor:pointer;text-decoration:underline;font-size:.8rem;">Eliminar</button>
                </form>
            </div>
        <?php endforeach; ?>
        <?php if (empty($imagenes)): ?>
            <p style="color:var(--muted);">Sin imágenes todavía.</p>
        <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <form action="<?= site_url('admin/productos/' . $p['id'] . '/imagenes') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="field">
            <label for="imagen">Nueva imagen (JPG, PNG o WebP, máx. 5MB)</label>
            <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp" required>
        </div>
        <div class="field">
            <label for="alt_text">Texto alternativo</label>
            <input type="text" id="alt_text" name="alt_text">
        </div>
        <button type="submit" class="btn">Subir imagen</button>
    </form>
</div>
<?php endif; ?>
