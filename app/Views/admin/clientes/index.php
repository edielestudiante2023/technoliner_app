<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h2>Clientes</h2>
        <a href="<?= site_url('admin/clientes/nuevo') ?>" class="btn">Nuevo cliente</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Logo</th>
                <th>Nombre</th>
                <th>Orden</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($clientes as $c): ?>
            <tr>
                <td><img src="<?= base_url('uploads/clientes/' . $c['logo']) ?>" alt="<?= esc($c['nombre']) ?>" style="max-height:36px;max-width:100px;object-fit:contain;"></td>
                <td><?= esc($c['nombre']) ?></td>
                <td><?= (int) $c['orden'] ?></td>
                <td>
                    <?php if ((int) $c['activo'] === 1): ?>
                        <span class="badge badge-success">Activo</span>
                    <?php else: ?>
                        <span class="badge badge-muted">Inactivo</span>
                    <?php endif; ?>
                </td>
                <td style="white-space:nowrap;">
                    <a href="<?= site_url('admin/clientes/' . $c['id'] . '/editar') ?>">Editar</a>
                    &nbsp;|&nbsp;
                    <form class="inline" action="<?= site_url('admin/clientes/' . $c['id'] . '/estado') ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-link" style="background:none;border:none;color:var(--primary);cursor:pointer;text-decoration:underline;">
                            <?= (int) $c['activo'] === 1 ? 'Inactivar' : 'Activar' ?>
                        </button>
                    </form>
                    &nbsp;|&nbsp;
                    <form class="inline" action="<?= site_url('admin/clientes/' . $c['id'] . '/eliminar') ?>" method="post" onsubmit="return confirm('¿Eliminar este cliente?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-link" style="background:none;border:none;color:var(--danger);cursor:pointer;text-decoration:underline;">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
