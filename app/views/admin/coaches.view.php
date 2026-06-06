<?php include __DIR__ . '/../auth/layout/panel-head.php'; include __DIR__ . '/../auth/layout/panel-start.php'; ?>

<div class="mb-4">
    <h1 class="h3 fw-bold">Gestión de Coaches</h1>
    <p class="text-muted mb-0">Alta de entrenadores y envío de credenciales</p>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card-panel p-4">
            <h2 class="h5 fw-bold mb-3">Alta de entrenador</h2>
            <form id="formStoreCoach" class="ajax-form" data-action="?url=admin-store-coach" data-validate="coach">
                <div class="mb-3"><label class="form-label small fw-semibold">Nombre</label><input type="text" name="first_name" class="form-control" required></div>
                <div class="mb-3"><label class="form-label small fw-semibold">Apellido</label><input type="text" name="last_name" class="form-control" required></div>
                <div class="mb-3"><label class="form-label small fw-semibold">Email</label><input type="email" name="email" class="form-control" required></div>
                <div class="mb-3"><label class="form-label small fw-semibold">Teléfono</label><input type="text" name="phone" class="form-control"></div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Especialidad</label>
                    <select name="specialty_id" class="form-select" required>
                        <?php foreach ( $specialties as $s ): ?>
                        <option value="<?= (int) $s['id'] ?>"><?= htmlspecialchars( $s['name'] ) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-sm-primary w-100">Crear y enviar credenciales</button>
            </form>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card-panel overflow-hidden">
            <div class="p-3 border-bottom"><h2 class="h5 fw-bold mb-0">Listado</h2></div>
            <div class="table-responsive">
                <table class="table table-panel table-hover mb-0">
                    <thead><tr><th>Nombre</th><th>Email</th><th>Especialidad</th><th>Teléfono</th></tr></thead>
                    <tbody>
                    <?php foreach ( $coaches as $c ): ?>
                        <tr>
                            <td class="fw-medium"><?= htmlspecialchars( $c['first_name'] . ' ' . $c['last_name'] ) ?></td>
                            <td class="text-muted"><?= htmlspecialchars( $c['email'] ) ?></td>
                            <td><?= htmlspecialchars( $c['specialty_name'] ) ?></td>
                            <td><?= htmlspecialchars( $c['phone'] ?? '-' ) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../auth/layout/panel-end.php'; ?>
