<?php include __DIR__ . '/../auth/layout/panel-head.php'; include __DIR__ . '/../auth/layout/panel-start.php'; ?>

<h1 class="h3 fw-bold mb-4">Mis alumnos</h1>

<div class="card-panel p-4 mb-4">
    <form method="get" class="row g-2">
        <input type="hidden" name="url" value="coach-students">
        <div class="col-md-5">
            <label class="form-label small fw-semibold">Filtrar por clase</label>
            <select name="lesson_id" onchange="this.form.submit()" class="form-select">
                <option value="">— Seleccioná una clase —</option>
                <?php foreach ( $lessons as $l ): ?>
                <option value="<?= (int) $l['id'] ?>" <?= $lessonId === (int) $l['id'] ? 'selected' : '' ?>>
                    <?= Lesson::dayLabel( $l['day_of_week'] ) ?> · <?= htmlspecialchars( $l['level_name'] ) ?> (<?= substr( $l['start_time'], 0, 5 ) ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<?php if ( $lessonId ): ?>
<div class="card-panel overflow-hidden">
    <?php if ( empty( $students ) ): ?>
        <p class="p-4 text-muted mb-0">No hay alumnos inscriptos en esta clase.</p>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-panel mb-0">
            <thead><tr><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Inscripto</th></tr></thead>
            <tbody>
            <?php foreach ( $students as $s ): ?>
                <tr>
                    <td class="fw-medium"><?= htmlspecialchars( $s['first_name'] . ' ' . $s['last_name'] ) ?></td>
                    <td class="text-muted"><?= htmlspecialchars( $s['email'] ) ?></td>
                    <td><?= htmlspecialchars( $s['phone'] ?? '-' ) ?></td>
                    <td><?= date( 'd/m/Y', strtotime( $s['created_at'] ) ) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../auth/layout/panel-end.php'; ?>
