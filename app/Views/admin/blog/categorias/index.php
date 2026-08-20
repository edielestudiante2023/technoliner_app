<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h2>Categorías del blog</h2>
        <a href="<?= site_url('admin/blog/categorias/nuevo') ?>" class="btn">Nueva categoría</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Orden</th>
                <th>Nombre</th>
                <th>Slug</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categorias as $c): ?>
            <tr>
                <td><?= (int) $c['orden'] ?></td>
                <td><?= esc($c['nombre']) ?></td>
                <td><?= esc($c['slug']) ?></td>
                <td>
                    <?php if ((int) $c['activo'] === 1): ?>
                        <span class="badge badge-success">Activa</span>
                    <?php else: ?>
                        <span class="badge badge-muted">Inactiva</span>
                    <?php endif; ?>
                </td>
                <td style="white-space:nowrap;">
                    <a href="<?= site_url('admin/blog/categorias/' . $c['id'] . '/editar') ?>">Editar</a>
                    &nbsp;|&nbsp;
                    <form class="inline" action="<?= site_url('admin/blog/categorias/' . $c['id'] . '/estado') ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-link" style="background:none;border:none;color:var(--primary);cursor:pointer;text-decoration:underline;">
                            <?= (int) $c['activo'] === 1 ? 'Inactivar' : 'Activar' ?>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
