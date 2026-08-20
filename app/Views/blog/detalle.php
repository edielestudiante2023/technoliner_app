<?= $this->include('templates/header') ?>

<section class="section blog-hero">
    <div class="container">
        <article class="blog-articulo">
            <?php if (! empty($articulo['categoria_nombre'])): ?>
                <span class="blog-card-cat"><?= esc($articulo['categoria_nombre']) ?></span>
            <?php endif; ?>
            <h1><?= esc($articulo['titulo']) ?></h1>
            <div class="blog-meta">Publicado el <?= date('d/m/Y', strtotime($articulo['publicado_at'])) ?></div>

            <?php if (! empty($articulo['imagen_portada'])): ?>
                <img class="portada" src="<?= base_url('uploads/blog/' . $articulo['imagen_portada']) ?>" alt="<?= esc($articulo['imagen_alt'] ?? '') ?>">
            <?php endif; ?>

            <div class="blog-contenido">
                <?= $articulo['contenido_html'] ?>
            </div>
        </article>
    </div>
</section>

<?= $this->include('templates/footer') ?>
