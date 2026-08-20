<?php $a = $articulo ?? []; ?>
<div class="card" style="max-width:760px;">
    <h2><?= $modo === 'crear' ? 'Nuevo artículo' : 'Editar artículo' ?></h2>

    <form action="<?= $modo === 'crear' ? site_url('admin/blog/articulos') : site_url('admin/blog/articulos/' . $a['id']) ?>" method="post" enctype="multipart/form-data" id="form-articulo">
        <?= csrf_field() ?>

        <div class="field">
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" required value="<?= esc(old('titulo', $a['titulo'] ?? '')) ?>">
            <?php if (isset($errors['titulo'])): ?><div class="field-error"><?= esc($errors['titulo']) ?></div><?php endif; ?>
        </div>

        <div class="field">
            <label for="categoria_id">Categoría</label>
            <select id="categoria_id" name="categoria_id" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;">
                <option value="">Sin categoría</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (old('categoria_id', $a['categoria_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                        <?= esc($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label for="extracto">Extracto</label>
            <input type="text" id="extracto" name="extracto" maxlength="500" value="<?= esc(old('extracto', $a['extracto'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="editor">Contenido</label>
            <div id="editor" style="background:#fff;"><?= $a['contenido_html'] ?? '' ?></div>
            <input type="hidden" name="contenido_html" id="contenido_html">
            <?php if (isset($errors['contenido_html'])): ?><div class="field-error"><?= esc($errors['contenido_html']) ?></div><?php endif; ?>
        </div>

        <div class="field">
            <label for="imagen">Imagen de portada (JPG, PNG o WebP, máx. 5MB)</label>
            <?php if (! empty($a['imagen_portada'])): ?>
                <div style="margin-bottom:8px;">
                    <img src="<?= base_url('uploads/blog/' . $a['imagen_portada']) ?>" alt="" style="max-width:200px;border-radius:8px;display:block;">
                </div>
            <?php endif; ?>
            <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp">
            <?php if (isset($errors['imagen'])): ?><div class="field-error"><?= esc($errors['imagen']) ?></div><?php endif; ?>
        </div>

        <div class="field">
            <label for="imagen_alt">Texto alternativo de la imagen</label>
            <input type="text" id="imagen_alt" name="imagen_alt" value="<?= esc(old('imagen_alt', $a['imagen_alt'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="seo_titulo">Título SEO</label>
            <input type="text" id="seo_titulo" name="seo_titulo" maxlength="70" value="<?= esc(old('seo_titulo', $a['seo_titulo'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="seo_descripcion">Meta descripción</label>
            <input type="text" id="seo_descripcion" name="seo_descripcion" maxlength="170" value="<?= esc(old('seo_descripcion', $a['seo_descripcion'] ?? '')) ?>">
        </div>

        <div class="field">
            <label>
                <input type="checkbox" name="destacado" value="1" <?= (old('destacado', $a['destacado'] ?? 0)) ? 'checked' : '' ?>>
                Destacado
            </label>
        </div>

        <button type="submit" class="btn">Guardar</button>
        <a href="<?= site_url('admin/blog/articulos') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    var quill = new Quill('#editor', { theme: 'snow' });
    document.getElementById('form-articulo').addEventListener('submit', function () {
        document.getElementById('contenido_html').value = quill.root.innerHTML;
    });
</script>
