<div class="card">
    <h2 style="margin-bottom:16px;">Contactos</h2>

    <table id="tabla-contactos" class="admin-table" style="width:100%;">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Empresa</th>
                <th>Producto de interés</th>
                <th>Mensaje</th>
                <th>Envío de aviso</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($contactos as $c): ?>
            <tr>
                <td data-order="<?= esc($c['created_at']) ?>"><?= esc($c['created_at']) ?></td>
                <td><?= esc($c['nombre']) ?></td>
                <td><a href="mailto:<?= esc($c['email']) ?>"><?= esc($c['email']) ?></a></td>
                <td><?= $c['telefono'] ? esc($c['telefono']) : '—' ?></td>
                <td><?= $c['empresa'] ? esc($c['empresa']) : '—' ?></td>
                <td><?= $c['producto_interes'] ? esc($c['producto_interes']) : '—' ?></td>
                <td title="<?= esc($c['mensaje']) ?>"><?= esc(mb_strimwidth($c['mensaje'], 0, 60, '…')) ?></td>
                <td>
                    <?php if ($c['email_notificado_at']): ?>
                        <span class="badge badge-success">Enviado</span>
                    <?php elseif ($c['email_error']): ?>
                        <span class="badge" style="background:var(--danger-soft);color:var(--danger);" title="<?= esc($c['email_error']) ?>">Error</span>
                    <?php else: ?>
                        <span class="badge badge-muted">Pendiente</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
