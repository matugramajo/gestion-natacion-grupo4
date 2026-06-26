<?php $p = $profile ?? []; include __DIR__ . '/../auth/layout/panel-head.php'; include __DIR__ . '/../auth/layout/panel-start.php'; ?>

<h1 class="h3 fw-bold mb-4 text-center">Mi perfil</h1>

<div class="card-panel p-4 mx-auto" style="max-width: 560px">
    <form class="ajax-form" data-action="?url=swimmer-update-profile" data-validate="profile">
        <div class="mb-3"><label class="form-label">Nombre</label><input type="text" name="first_name" value="<?= htmlspecialchars( $p['first_name'] ?? '' ) ?>" required class="form-control"></div>
        <div class="mb-3"><label class="form-label">Apellido</label><input type="text" name="last_name" value="<?= htmlspecialchars( $p['last_name'] ?? '' ) ?>" required class="form-control"></div>
        <div class="mb-3"><label class="form-label">Teléfono</label><input type="text" name="phone" value="<?= htmlspecialchars( $p['phone'] ?? '' ) ?>" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Dirección</label><input type="text" name="address" value="<?= htmlspecialchars( $p['address'] ?? '' ) ?>" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Fecha de nacimiento</label><input type="date" name="birth_date" value="<?= htmlspecialchars( $p['birth_date'] ?? '' ) ?>" class="form-control"></div>
        <button type="submit" class="btn btn-sm-primary">Guardar cambios</button>
    </form>
</div>

<?php include __DIR__ . '/../auth/layout/panel-end.php'; ?>
