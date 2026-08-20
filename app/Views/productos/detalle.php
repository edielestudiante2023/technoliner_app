<?= $this->include('templates/header') ?>

<section class="section blog-hero">
    <div class="container">
        <div class="producto-detalle">
            <div class="producto-galeria">
                <?php if (! empty($imagenes)): ?>
                    <?php $principal = null; foreach ($imagenes as $img) { if ((int) $img['es_principal'] === 1) { $principal = $img; break; } } $principal = $principal ?? $imagenes[0]; ?>
                    <img class="principal" id="imagen-principal" src="<?= base_url('uploads/productos/' . $principal['ruta']) ?>" alt="<?= esc($principal['alt_text'] ?? $producto['nombre']) ?>">
                    <?php if (count($imagenes) > 1): ?>
                        <div class="producto-galeria-miniaturas">
                            <?php foreach ($imagenes as $img): ?>
                                <img src="<?= base_url('uploads/productos/' . $img['ruta']) ?>" alt="<?= esc($img['alt_text'] ?? '') ?>"
                                     class="<?= $img['id'] === $principal['id'] ? 'activa' : '' ?>"
                                     onclick="document.getElementById('imagen-principal').src=this.src;document.querySelectorAll('.producto-galeria-miniaturas img').forEach(function(el){el.classList.remove('activa')});this.classList.add('activa');">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="producto-img-placeholder" style="height:340px;border-radius:var(--radius);">Sin imagen disponible</div>
                <?php endif; ?>
            </div>

            <div class="producto-info">
                <span class="producto-cat"><?= esc($producto['categoria_nombre']) ?></span>
                <h1><?= esc($producto['nombre']) ?></h1>

                <div class="producto-html"><?= $producto['descripcion_html'] ?></div>

                <?php if (! empty($especificaciones)): ?>
                    <table class="producto-especificaciones">
                        <tbody>
                        <?php foreach ($especificaciones as $e): ?>
                            <tr>
                                <th><?= esc($e['nombre']) ?></th>
                                <td><?= esc($e['valor']) ?><?= $e['unidad'] ? ' ' . esc($e['unidad']) : '' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <a href="<?= base_url('/') ?>#contacto" class="btn btn-primary">Solicitar información</a>
            </div>
        </div>
    </div>
</section>

<?= $this->include('templates/footer') ?>
