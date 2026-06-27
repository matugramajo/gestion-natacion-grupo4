<?php include __DIR__ . '/layout/panel-head.php'; include __DIR__ . '/layout/panel-start.php'; ?>

<h1 class="h3 fw-bold mb-4">Listado de nadadores</h1>

<div class="card-panel overflow-hidden">
    <div class="table-responsive">
        <table class="table table-panel table-hover mb-0">
            <thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Dirección</th><th></th></tr></thead>
            <tbody>
                <?php foreach ( $swimmers as $s ): ?>
                <tr>
                    <td><?= (int) $s['id'] ?></td>
                    <td class="fw-medium"><?= htmlspecialchars( $s['first_name'] . ' ' . $s['last_name'] ) ?></td>
                    <td class="text-muted"><?= htmlspecialchars( $s['email'] ) ?></td>
                    <td><?= htmlspecialchars( $s['phone'] ?? '-' ) ?></td>
                    <td><?= htmlspecialchars( $s['address'] ?? '-' ) ?></td>
                    <td>
                        <form class="ajax-form d-inline" method="post" action="?url=admin-delete-swimmer" data-action="?url=admin-delete-swimmer" data-confirm="¿Eliminar a <?= htmlspecialchars( $s['first_name'] ) ?>?">
                            <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
                            <button type="submit" class="btn btn-link btn-sm text-danger p-0">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/layout/panel-end.php'; ?>
