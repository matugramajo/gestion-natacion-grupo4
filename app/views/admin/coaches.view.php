<?php include __DIR__ . '/../auth/layout/panel-head.php'; include __DIR__ . '/../auth/layout/panel-start.php'; ?>

<div class="mb-4">
    <h1 class="h3 fw-bold">Gestión de Coaches</h1>
    <p class="text-muted mb-0">Alta, edición y baja de entrenadores</p>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-5">
        <div class="card-panel p-4">

            <?php if ( isset( $editCoach ) ): ?>
                <h2 class="h5 fw-bold mb-3">Editar entrenador</h2>
                <form id="formUpdateCoach" class="ajax-form" data-action="?url=admin-update-coach" data-validate="coach">
                    <input type="hidden" name="id" value="<?= (int) $editCoach['id'] ?>">
                    <div class="mb-3"><label class="form-label small fw-semibold">Nombre</label><input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars( $editCoach['first_name'] ) ?>" required></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Apellido</label><input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars( $editCoach['last_name'] ) ?>" required></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Teléfono</label><input type="text" name="phone" class="form-control" value="<?= htmlspecialchars( $editCoach['phone'] ?? '' ) ?>"></div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Especialidad</label>
                        <select name="specialty_id" class="form-select" required>
                            <?php foreach ( $specialties as $s ): ?>
                            <option value="<?= (int) $s['id'] ?>" <?= (int) $s['id'] === (int) $editCoach['specialty_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars( $s['name'] ) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm-primary w-100">Guardar cambios</button>
                        <a href="?url=admin-coaches" class="btn btn-light w-100">Cancelar</a>
                    </div>
                </form>

            <?php else: ?>
                <h2 class="h5 fw-bold mb-3">Alta de entrenador</h2>
                <form id="formStoreCoach" class="ajax-form" data-action="?url=admin-store-coach" data-validate="coach">
                    <div class="mb-3"><label class="form-label small fw-semibold">Nombre</label><input type="text" name="first_name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Apellido</label><input type="text" name="last_name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Teléfono</label><input type="text" name="phone" class="form-control"></div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Especialidad</label>
                        <select name="specialty_id" class="form-select" required>
                            <option value="">Seleccioná una especialidad</option>
                            <?php foreach ( $specialties as $s ): ?>
                            <option value="<?= (int) $s['id'] ?>"><?= htmlspecialchars( $s['name'] ) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm-primary w-100">Crear y enviar credenciales</button>
                </form>
            <?php endif; ?>

        </div>
    </div>

    <div class="col-12 col-lg-7">
        <div class="card-panel overflow-hidden">
            <div class="p-3 border-bottom"><h2 class="h5 fw-bold mb-0">Listado</h2></div>
            <div class="table-responsive">
                <table class="table table-panel table-hover mb-0">
                    <thead><tr><th>Nombre</th><th>Email</th><th>Especialidad</th><th>Acciones</th></tr></thead>
                    <tbody>
                    <?php foreach ( $coaches as $c ): ?>
                        <tr>
                            <td class="fw-medium"><?= htmlspecialchars( $c['first_name'] . ' ' . $c['last_name'] ) ?></td>
                            <td class="text-muted"><?= htmlspecialchars( $c['email'] ) ?></td>
                            <td><?= htmlspecialchars( $c['specialty_name'] ) ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="?url=admin-edit-coach&id=<?= (int) $c['id'] ?>" class="btn btn-sm btn-light">Editar</a>
                                    <form class="ajax-form mb-0" data-action="?url=admin-delete-coach" data-confirm="¿Eliminar a <?= htmlspecialchars( $c['first_name'] ) ?>?">
                                        <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../auth/layout/panel-end.php'; ?>