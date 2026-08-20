<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h2>Artículos del blog</h2>
        <a href="<?= site_url('admin/blog/articulos/nuevo') ?>" class="btn">Nuevo artículo</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Publicado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($articulos as $a): ?>
            <tr>
                <td><?= esc($a['titulo']) ?></td>
                <td><?= esc($a['categoria_nombre'] ?? '—') ?></td>
                <td>
                    <?php if ((int) $a['publicado'] === 1): ?>
                        <span class="badge badge-success">Publicado</span>
                    <?php else: ?>
                        <span class="badge badge-muted">Borrador</span>
                    <?php endif; ?>
                </td>
                <td><?= $a['publicado_at'] ? esc($a['publicado_at']) : '—' ?></td>
                <td style="white-space:nowrap;">
                    <a href="<?= site_url('admin/blog/articulos/' . $a['id'] . '/editar') ?>">Editar</a>
                    &nbsp;|&nbsp;
                    <?php if ((int) $a['publicado'] === 1): ?>
                        <form class="inline" action="<?= site_url('admin/blog/articulos/' . $a['id'] . '/despublicar') ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn-link" style="background:none;border:none;color:var(--primary);cursor:pointer;text-decoration:underline;">Despublicar</button>
                        </form>
                    <?php else: ?>
                        <form class="inline" action="<?= site_url('admin/blog/articulos/' . $a['id'] . '/publicar') ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn-link" style="background:none;border:none;color:var(--primary);cursor:pointer;text-decoration:underline;">Publicar</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
