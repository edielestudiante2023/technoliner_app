<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h2>Productos</h2>
        <a href="<?= site_url('admin/productos/nuevo') ?>" class="btn">Nuevo producto</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Destacado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($productos as $p): ?>
            <tr>
                <td><?= esc($p['nombre']) ?></td>
                <td><?= esc($p['categoria_nombre']) ?></td>
                <td>
                    <?php if ((int) $p['activo'] === 1): ?>
                        <span class="badge badge-success">Activo</span>
                    <?php else: ?>
                        <span class="badge badge-muted">Inactivo</span>
                    <?php endif; ?>
                </td>
                <td><?= (int) $p['destacado'] === 1 ? 'Sí' : '—' ?></td>
                <td style="white-space:nowrap;">
                    <a href="<?= site_url('admin/productos/' . $p['id'] . '/editar') ?>">Editar</a>
                    &nbsp;|&nbsp;
                    <form class="inline" action="<?= site_url('admin/productos/' . $p['id'] . '/estado') ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-link" style="background:none;border:none;color:var(--primary);cursor:pointer;text-decoration:underline;">
                            <?= (int) $p['activo'] === 1 ? 'Inactivar' : 'Activar' ?>
                        </button>
                    </form>
                    &nbsp;|&nbsp;
                    <form class="inline" action="<?= site_url('admin/productos/' . $p['id'] . '/destacado') ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-link" style="background:none;border:none;color:var(--primary);cursor:pointer;text-decoration:underline;">
                            <?= (int) $p['destacado'] === 1 ? 'Quitar destacado' : 'Destacar' ?>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
