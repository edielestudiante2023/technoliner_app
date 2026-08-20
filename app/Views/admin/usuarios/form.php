<?php $u = $usuarioEditar ?? []; ?>
<div class="card" style="max-width:480px;">
    <h2><?= $modo === 'crear' ? 'Nuevo usuario' : 'Editar usuario' ?></h2>

    <form action="<?= $modo === 'crear' ? site_url('admin/usuarios') : site_url('admin/usuarios/' . $u['id']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="field">
            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" required value="<?= esc(old('nombre', $u['nombre'] ?? '')) ?>">
            <?php if (isset($errors['nombre'])): ?><div class="field-error"><?= esc($errors['nombre']) ?></div><?php endif; ?>
        </div>

        <div class="field">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" required value="<?= esc(old('email', $u['email'] ?? '')) ?>">
            <?php if (isset($errors['email'])): ?><div class="field-error"><?= esc($errors['email']) ?></div><?php endif; ?>
        </div>

        <div class="field">
            <label for="rol_id">Rol</label>
            <select id="rol_id" name="rol_id" required style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;">
                <?php foreach ($roles as $rol): ?>
                    <option value="<?= $rol['id'] ?>" <?= (old('rol_id', $u['rol_id'] ?? '') == $rol['id']) ? 'selected' : '' ?>>
                        <?= esc($rol['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['rol_id'])): ?><div class="field-error"><?= esc($errors['rol_id']) ?></div><?php endif; ?>
        </div>

        <div class="field">
            <label for="password"><?= $modo === 'crear' ? 'Contraseña' : 'Nueva contraseña (opcional)' ?></label>
            <input type="password" id="password" name="password" minlength="12" <?= $modo === 'crear' ? 'required' : '' ?>>
            <?php if (isset($errors['password'])): ?><div class="field-error"><?= esc($errors['password']) ?></div><?php endif; ?>
        </div>

        <button type="submit" class="btn">Guardar</button>
        <a href="<?= site_url('admin/usuarios') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
