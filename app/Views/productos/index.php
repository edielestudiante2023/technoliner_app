<?= $this->include('templates/header') ?>

<section class="section blog-hero">
    <div class="container">
        <div class="section-head">
            <h1>Catálogo de liners y sellos para envases</h1>
            <p class="section-sub">Liners y sellos para empaques industriales.</p>
        </div>

        <?php if (! empty($categorias)): ?>
            <div class="catalogo-filtros">
                <a href="<?= site_url('productos') ?>" class="<?= $categoriaSlug ? '' : 'activo' ?>">Todos</a>
                <?php foreach ($categorias as $cat): ?>
                    <a href="<?= site_url('productos') ?>?categoria=<?= esc($cat['slug']) ?>" class="<?= $categoriaSlug === $cat['slug'] ? 'activo' : '' ?>">
                        <?= esc($cat['nombre']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($productos)): ?>
            <p class="producto-empty">Todavía no hay productos publicados en esta categoría.</p>
        <?php else: ?>
            <div class="producto-grid">
                <?php foreach ($productos as $producto): ?>
                    <a href="<?= site_url('productos/' . $producto['slug']) ?>" class="producto-card">
                        <?php if (! empty($producto['imagen_principal_ruta'])): ?>
                            <img class="producto-img" src="<?= base_url('uploads/productos/' . $producto['imagen_principal_ruta']) ?>" alt="<?= esc($producto['imagen_principal_alt'] ?? $producto['nombre']) ?>">
                        <?php else: ?>
                            <div class="producto-img-placeholder">Sin imagen</div>
                        <?php endif; ?>
                        <div class="producto-body">
                            <span class="producto-cat"><?= esc($producto['categoria_nombre']) ?></span>
                            <h3><?= esc($producto['nombre']) ?></h3>
                            <?php if (! empty($producto['resumen'])): ?>
                                <p><?= esc($producto['resumen']) ?></p>
                            <?php endif; ?>
                            <?php if ((int) $producto['destacado'] === 1): ?>
                                <span class="badge badge-destacado" style="background:var(--accent);color:#fff;padding:2px 10px;border-radius:999px;font-size:.72rem;font-weight:700;">Destacado</span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if ($pager->getPageCount() > 1): ?>
                <div class="blog-pager">
                    <?php for ($i = 1; $i <= $pager->getPageCount(); $i++): ?>
                        <?php if ($i === $pager->getCurrentPage()): ?>
                            <span class="current"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= site_url('productos') ?>?page=<?= $i ?><?= $categoriaSlug ? '&categoria=' . esc($categoriaSlug) : '' ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?= $this->include('templates/footer') ?>
