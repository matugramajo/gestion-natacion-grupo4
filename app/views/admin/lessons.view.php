<?php
require_once __DIR__ . '/../../core/ViewHelper.php';
include __DIR__ . '/../auth/layout/panel-head.php';
include __DIR__ . '/../auth/layout/panel-start.php';
?>

<h1 class="h3 fw-bold mb-4">Gestión de Clases</h1>

<div class="card-panel p-4 mb-4">
    <h2 class="h5 fw-bold mb-3">Nueva clase</h2>
    <form id="formStoreLesson" class="ajax-form row g-3 align-items-end" data-action="?url=admin-store-lesson" data-validate="lesson">
        <div class="col-md-4 col-lg-2">
            <label class="form-label small">Coach</label>
            <select name="coach_id" class="form-select form-select-sm" required>
                <?php foreach ( $coaches as $c ): ?>
                <option value="<?= (int) $c['id'] ?>"><?= htmlspecialchars( $c['first_name'] . ' ' . $c['last_name'] ) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 col-lg-2">
            <label class="form-label small">Nivel</label>
            <select name="level_id" class="form-select form-select-sm" required>
                <?php foreach ( $levels as $lv ): ?>
                <option value="<?= (int) $lv['id'] ?>"><?= htmlspecialchars( $lv['name'] ) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 col-lg-2">
            <label class="form-label small">Día</label>
            <select name="day_of_week" class="form-select form-select-sm" required>
                <option value="Monday">Lunes</option><option value="Tuesday">Martes</option><option value="Wednesday">Miércoles</option>
                <option value="Thursday">Jueves</option><option value="Friday">Viernes</option><option value="Saturday">Sábado</option>
            </select>
        </div>
        <div class="col-6 col-lg-2"><label class="form-label small">Inicio</label><input type="time" name="start_time" class="form-control form-control-sm" required></div>
        <div class="col-6 col-lg-2"><label class="form-label small">Fin</label><input type="time" name="end_time" class="form-control form-control-sm" required></div>
        <div class="col-6 col-lg-1"><label class="form-label small">Cupos</label><input type="number" name="capacity" min="1" value="10" class="form-control form-control-sm" required></div>
        <div class="col-12"><button type="submit" class="btn btn-sm-primary btn-sm">Crear clase</button></div>
    </form>
</div>

<div class="card-panel overflow-hidden">
    <div class="p-3 border-bottom"><h2 class="h5 fw-bold mb-0">Clases activas</h2></div>
    <div class="table-responsive">
        <table class="table table-panel mb-0">
            <thead><tr><th>Nivel</th><th>Día</th><th>Horario</th><th>Coach</th><th>Cupos</th><th></th></tr></thead>
            <tbody>
            <?php foreach ( $lessons as $lesson ): ?>
                <tr>
                    <td><span class="<?= ViewHelper::levelBadgeClass( $lesson['level_name'] ) ?>"><?= htmlspecialchars( $lesson['level_name'] ) ?></span></td>
                    <td><?= Lesson::dayLabel( $lesson['day_of_week'] ) ?></td>
                    <td class="text-muted"><?= substr( $lesson['start_time'], 0, 5 ) ?> - <?= substr( $lesson['end_time'], 0, 5 ) ?></td>
                    <td><?= htmlspecialchars( trim( ( $lesson['coach_first_name'] ?? '' ) . ' ' . ( $lesson['coach_last_name'] ?? '' ) ) ) ?></td>
                    <td><?= (int) $lesson['enrolled'] ?> / <?= (int) $lesson['capacity'] ?></td>
                    <td class="text-nowrap">
                        <button type="button" class="btn btn-link btn-sm p-0 me-2 btn-edit-lesson"
                            data-id="<?= (int) $lesson['id'] ?>"
                            data-coach-id="<?= (int) $lesson['coach_id'] ?>"
                            data-level-id="<?= (int) $lesson['level_id'] ?>"
                            data-day="<?= htmlspecialchars( $lesson['day_of_week'] ) ?>"
                            data-start="<?= substr( $lesson['start_time'], 0, 5 ) ?>"
                            data-end="<?= substr( $lesson['end_time'], 0, 5 ) ?>"
                            data-capacity="<?= (int) $lesson['capacity'] ?>">
                            Editar
                        </button>
                        <form class="ajax-form d-inline" data-action="?url=admin-delete-lesson" data-confirm="¿Eliminar esta clase?">
                            <input type="hidden" name="id" value="<?= (int) $lesson['id'] ?>">
                            <button type="submit" class="btn btn-link btn-sm text-danger p-0">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalEditLesson" tabindex="-1" aria-labelledby="modalEditLessonLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5 fw-bold" id="modalEditLessonLabel">Editar clase</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formUpdateLesson" class="ajax-form" data-action="?url=admin-update-lesson" data-validate="lesson">
                <div class="modal-body row g-3">
                    <input type="hidden" name="id" id="edit-lesson-id">
                    <div class="col-md-6">
                        <label class="form-label small">Coach</label>
                        <select name="coach_id" id="edit-coach-id" class="form-select" required>
                            <?php foreach ( $coaches as $c ): ?>
                            <option value="<?= (int) $c['id'] ?>"><?= htmlspecialchars( $c['first_name'] . ' ' . $c['last_name'] ) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Nivel</label>
                        <select name="level_id" id="edit-level-id" class="form-select" required>
                            <?php foreach ( $levels as $lv ): ?>
                            <option value="<?= (int) $lv['id'] ?>"><?= htmlspecialchars( $lv['name'] ) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Día</label>
                        <select name="day_of_week" id="edit-day" class="form-select" required>
                            <option value="Monday">Lunes</option>
                            <option value="Tuesday">Martes</option>
                            <option value="Wednesday">Miércoles</option>
                            <option value="Thursday">Jueves</option>
                            <option value="Friday">Viernes</option>
                            <option value="Saturday">Sábado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Inicio</label>
                        <input type="time" name="start_time" id="edit-start" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Fin</label>
                        <input type="time" name="end_time" id="edit-end" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Cupos</label>
                        <input type="number" name="capacity" id="edit-capacity" min="1" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-edit-lesson').forEach((btn) => {
    btn.addEventListener('click', () => {
        document.getElementById('edit-lesson-id').value = btn.dataset.id;
        document.getElementById('edit-coach-id').value = btn.dataset.coachId;
        document.getElementById('edit-level-id').value = btn.dataset.levelId;
        document.getElementById('edit-day').value = btn.dataset.day;
        document.getElementById('edit-start').value = btn.dataset.start;
        document.getElementById('edit-end').value = btn.dataset.end;
        document.getElementById('edit-capacity').value = btn.dataset.capacity;
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditLesson')).show();
    });
});
</script>

<?php include __DIR__ . '/../auth/layout/panel-end.php'; ?>
