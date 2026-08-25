<?= $this->include('templates/header') ?>

<section class="section blog-hero">
    <div class="container">
        <div class="section-head">
            <h1>Guías técnicas de liners y sellado de envases</h1>
            <p class="section-sub">Guías de sellado, materiales, seguridad e inocuidad para tu empaque.</p>
        </div>

        <?php if (empty($articulos)): ?>
            <p class="blog-empty">Todavía no hay artículos publicados. Vuelve pronto.</p>
        <?php else: ?>
            <div class="blog-grid">
                <?php foreach ($articulos as $articulo): ?>
                    <a href="<?= site_url('blog/' . $articulo['slug']) ?>" class="blog-card" style="text-decoration:none;color:inherit;">
                        <?php if (! empty($articulo['imagen_portada'])): ?>
                            <img src="<?= base_url('uploads/blog/' . $articulo['imagen_portada']) ?>" alt="<?= esc($articulo['imagen_alt'] ?? '') ?>">
                        <?php endif; ?>
                        <div class="blog-card-body">
                            <?php if (! empty($articulo['categoria_nombre'])): ?>
                                <span class="blog-card-cat"><?= esc($articulo['categoria_nombre']) ?></span>
                            <?php endif; ?>
                            <h3><?= esc($articulo['titulo']) ?></h3>
                            <?php if (! empty($articulo['extracto'])): ?>
                                <p><?= esc($articulo['extracto']) ?></p>
                            <?php endif; ?>
                            <span class="blog-card-date"><?= date('d/m/Y', strtotime($articulo['publicado_at'])) ?></span>
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
                            <a href="<?= site_url('blog') ?>?page=<?= $i ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?= $this->include('templates/footer') ?>
