<?php $p = $profile ?? []; include __DIR__ . '/../auth/layout/panel-head.php'; include __DIR__ . '/../auth/layout/panel-start.php'; ?>

<h1 class="h3 fw-bold mb-4">Mi perfil</h1>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card-panel p-4">
            <h2 class="h5 fw-bold mb-3">Datos personales</h2>
            <form class="ajax-form" data-action="?url=coach-update-profile" data-validate="profile">
                <div class="mb-3"><label class="form-label">Nombre</label><input type="text" name="first_name" value="<?= htmlspecialchars( $p['first_name'] ?? '' ) ?>" required class="form-control"></div>
                <div class="mb-3"><label class="form-label">Apellido</label><input type="text" name="last_name" value="<?= htmlspecialchars( $p['last_name'] ?? '' ) ?>" required class="form-control"></div>
                <div class="mb-3"><label class="form-label">Teléfono</label><input type="text" name="phone" value="<?= htmlspecialchars( $p['phone'] ?? '' ) ?>" class="form-control"></div>
                <div class="mb-3"><label class="form-label">Dirección</label><input type="text" name="address" value="<?= htmlspecialchars( $p['address'] ?? '' ) ?>" class="form-control"></div>
                <div class="mb-3"><label class="form-label">Fecha de nacimiento</label><input type="date" name="birth_date" value="<?= htmlspecialchars( $p['birth_date'] ?? '' ) ?>" class="form-control"></div>
                <?php if ( !empty( $coach['specialty_name'] ) ): ?><p class="text-muted small">Especialidad: <?= htmlspecialchars( $coach['specialty_name'] ) ?></p><?php endif; ?>
                <button type="submit" class="btn btn-sm-primary">Guardar cambios</button>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-panel p-4">
            <h2 class="h5 fw-bold mb-3">Cambiar contraseña</h2>
            <form class="ajax-form" data-action="?url=coach-update-password" data-validate="password">
                <div class="mb-3"><label class="form-label">Contraseña actual</label><input type="password" name="current_password" required class="form-control"></div>
                <div class="mb-3"><label class="form-label">Nueva contraseña</label><input type="password" name="password" minlength="6" required class="form-control"></div>
                <div class="mb-3"><label class="form-label">Confirmar</label><input type="password" name="confirm_password" minlength="6" required class="form-control"></div>
                <button type="submit" class="btn btn-sm-outline-primary">Actualizar contraseña</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../auth/layout/panel-end.php'; ?>
