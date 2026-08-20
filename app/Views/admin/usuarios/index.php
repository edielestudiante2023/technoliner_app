<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h2>Usuarios</h2>
        <a href="<?= site_url('admin/usuarios/nuevo') ?>" class="btn">Nuevo usuario</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Último ingreso</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= esc($u['nombre']) ?></td>
                <td><?= esc($u['email']) ?></td>
                <td><?= esc($u['rol_nombre']) ?></td>
                <td>
                    <?php if ((int) $u['activo'] === 1): ?>
                        <span class="badge badge-success">Activo</span>
                    <?php else: ?>
                        <span class="badge badge-muted">Inactivo</span>
                    <?php endif; ?>
                </td>
                <td><?= $u['ultimo_login_at'] ? esc($u['ultimo_login_at']) : '—' ?></td>
                <td style="white-space:nowrap;">
                    <a href="<?= site_url('admin/usuarios/' . $u['id'] . '/editar') ?>">Editar</a>
                    <?php if ((int) $u['id'] !== (int) $usuarioActual['id']): ?>
                        &nbsp;|&nbsp;
                        <form class="inline" action="<?= site_url('admin/usuarios/' . $u['id'] . '/estado') ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn-link" style="background:none;border:none;color:var(--primary);cursor:pointer;text-decoration:underline;">
                                <?= (int) $u['activo'] === 1 ? 'Inactivar' : 'Activar' ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
