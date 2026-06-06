<?php
require_once __DIR__ . '/../../core/ViewHelper.php';
include __DIR__ . '/../auth/layout/panel-head.php';
include __DIR__ . '/../auth/layout/panel-start.php';
?>

<h1 class="h3 fw-bold mb-4">Clases disponibles</h1>

<?php if ( !empty( $myBookings ) ): ?>
<div class="mb-4">
    <h2 class="h5 fw-bold mb-3">Mis inscripciones</h2>
    <?php foreach ( $myBookings as $b ): ?>
    <div class="card-panel p-3 mb-2 small">
        <strong><?= htmlspecialchars( $b['level_name'] ) ?></strong> —
        <?= Lesson::dayLabel( $b['day_of_week'] ) ?>
        <?= substr( $b['start_time'], 0, 5 ) ?>-<?= substr( $b['end_time'], 0, 5 ) ?>
        <span class="text-muted">· Prof. <?= htmlspecialchars( $b['coach_first_name'] . ' ' . $b['coach_last_name'] ) ?></span>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="card-panel overflow-hidden">
    <div class="table-responsive">
        <table class="table table-panel mb-0">
            <thead><tr><th>Nivel</th><th>Día</th><th>Horario</th><th>Entrenador</th><th>Cupos</th><th></th></tr></thead>
            <tbody>
            <?php foreach ( $lessons as $lesson ):
                $enrolled = (int) $lesson['enrolled'];
                $capacity = (int) $lesson['capacity'];
                $isEnrolled = in_array( (int) $lesson['id'], $enrolledIds, true );
                $full = $enrolled >= $capacity;
            ?>
                <tr>
                    <td><span class="<?= ViewHelper::levelBadgeClass( $lesson['level_name'] ) ?>"><?= htmlspecialchars( $lesson['level_name'] ) ?></span></td>
                    <td><?= Lesson::dayLabel( $lesson['day_of_week'] ) ?></td>
                    <td class="text-muted"><?= substr( $lesson['start_time'], 0, 5 ) ?> - <?= substr( $lesson['end_time'], 0, 5 ) ?></td>
                    <td><?= htmlspecialchars( trim( ( $lesson['coach_first_name'] ?? '' ) . ' ' . ( $lesson['coach_last_name'] ?? '' ) ) ) ?></td>
                    <td><?= $enrolled ?> / <?= $capacity ?></td>
                    <td>
                        <?php if ( $isEnrolled ): ?>
                            <span class="badge bg-success">Inscripto</span>
                        <?php elseif ( $full ): ?>
                            <span class="badge bg-secondary">Completo</span>
                        <?php else: ?>
                            <form class="ajax-form d-inline" data-action="?url=swimmer-enroll">
                                <input type="hidden" name="lesson_id" value="<?= (int) $lesson['id'] ?>">
                                <button type="submit" class="btn btn-sm-primary btn-sm">Inscribirme</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../auth/layout/panel-end.php'; ?>
