<?= $this->include('templates/header') ?>

<section class="section blog-hero">
    <div class="container">
        <div class="section-head">
            <h1>Clientes que confían en Technoliner</h1>
            <p class="section-sub">Empresas de las industrias alimentaria, farmacéutica, industrial y cosmética que ya confían en nuestras soluciones de sellado.</p>
        </div>

        <?php if (empty($clientes)): ?>
            <p class="producto-empty">Todavía no hay clientes publicados.</p>
        <?php else: ?>
            <div class="clientes-grid">
                <?php foreach ($clientes as $cliente): ?>
                    <div class="cliente-card">
                        <img src="<?= base_url('uploads/clientes/' . $cliente['logo']) ?>" alt="<?= esc($cliente['logo_alt'] ?: $cliente['nombre']) ?>">
                        <span><?= esc($cliente['nombre']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->include('templates/footer') ?>
