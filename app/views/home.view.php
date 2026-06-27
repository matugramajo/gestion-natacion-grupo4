<?php
require_once __DIR__ . '/../core/ViewHelper.php';
include __DIR__ . '/auth/layout/panel-head.php';
include __DIR__ . '/auth/layout/panel-start.php';
?>

<?php if ( $roleId === Role::ADMIN ): ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-3 mb-4">
    <div>
        <p class="hero-sub mb-1">Panel de administración</p>
        <h1 class="hero-title mb-0">Hola, <?= htmlspecialchars( $firstName ) ?></h1>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="?url=admin-lessons" class="btn btn-sm-primary btn-sm d-inline-flex align-items-center gap-1">
            <span class="material-symbols-outlined" style="font-size:20px">add</span> Gestionar clases
        </a>
        <a href="?url=admin-coaches" class="btn btn-sm-outline-primary btn-sm">Gestionar coaches</a>
        <a href="?url=swimmers" class="btn btn-light btn-sm">Ver nadadores</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <?php
    $cards = [
        [ 'label' => 'Nadadores', 'value' => $stats['swimmers'], 'icon' => 'group' ],
        [ 'label' => 'Coaches', 'value' => $stats['coaches'], 'icon' => 'sports' ],
        [ 'label' => 'Clases activas', 'value' => $stats['lessons'], 'icon' => 'calendar_month' ],
        [ 'label' => 'Inscripciones', 'value' => $stats['bookings'], 'icon' => 'how_to_reg' ],
    ];
    foreach ( $cards as $c ):
    ?>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card-panel stat-card h-100">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="stat-icon"><span class="material-symbols-outlined" style="font-size:18px"><?= $c['icon'] ?></span></span>
                <span class="stat-label"><?= $c['label'] ?></span>
            </div>
            <div class="stat-value"><?= (int) $c['value'] ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php elseif ( $roleId === Role::COACH ): ?>

<div class="mb-4">
    <p class="hero-sub mb-1">Panel del entrenador</p>
    <h1 class="hero-title mb-0">Hola, <?= htmlspecialchars( $firstName ) ?></h1>
</div>

<div class="row g-4">
    <div class="col-12 col-md-3">
        <div class="card-panel stat-card h-100">
            <h2 class="h5 fw-bold mb-4">Mis clases</h2>
            <span class="stat-value text-primary"><?= count( $coachLessons ) ?></span>
            <span class="text-muted ms-2">activas</span>
        </div>
    </div>
    <div class="col-12 col-md-8">
        <div class="card-panel p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h5 fw-bold mb-0">Próximas clases</h3>
                <a href="?url=coach-students" class="small text-primary text-decoration-none">Ver alumnos →</a>
            </div>
            <?php if ( empty( $coachLessons ) ): ?>
                <p class="text-muted mb-0">No tenés clases asignadas.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-panel table-hover mb-0">
                    <thead><tr><th>Día</th><th>Horario</th><th>Nivel</th><th class="text-end">Inscriptos</th></tr></thead>
                    <tbody>
                    <?php foreach ( $coachLessons as $lesson ): ?>
                        <tr>
                            <td class="fw-medium"><?= Lesson::dayLabel( $lesson['day_of_week'] ) ?></td>
                            <td class="text-muted"><?= substr( $lesson['start_time'], 0, 5 ) ?> - <?= substr( $lesson['end_time'], 0, 5 ) ?></td>
                            <td><span class="<?= ViewHelper::levelBadgeClass( $lesson['level_name'] ) ?>"><?= htmlspecialchars( $lesson['level_name'] ) ?></span></td>
                            <td class="text-end"><?= (int) $lesson['enrolled'] ?> / <?= (int) $lesson['capacity'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php else: ?>

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-8">
        <div class="card-panel p-4 p-md-5 h-100">
            <h2 class="h3 fw-bold mb-2">Hola, <?= htmlspecialchars( $firstName ) ?></h2>
            <p class="hero-sub mb-4">¿Listo para tu próximo entrenamiento?</p>
            <div class="d-flex flex-wrap gap-2">
                <a href="?url=swimmer-lessons" class="btn btn-sm-primary d-inline-flex align-items-center gap-2">
                    <span class="material-symbols-outlined">search</span> Ver clases disponibles
                </a>
                <a href="?url=swimmer-profile" class="btn btn-sm-outline-primary d-inline-flex align-items-center gap-2">
                    <span class="material-symbols-outlined">person</span> Mi perfil
                </a>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="swimmer-stat-card h-100 d-flex flex-column justify-content-center">
            <span class="material-symbols-outlined icon-fill mb-2" style="font-size:48px;opacity:.85">assignment_turned_in</span>
            <div class="display-4 fw-bold"><?= count( $myBookings ) ?></div>
            <p class="mb-0 opacity-90">Mis inscripciones</p>
        </div>
    </div>
</div>

<?php if ( !empty( $myBookings ) ): ?>
<h2 class="h4 fw-bold mb-3">Mis clases actuales</h2>
<div class="row g-3">
    <?php foreach ( $myBookings as $b ): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card-panel p-4 h-100">
            <span class="<?= ViewHelper::levelBadgeClass( $b['level_name'] ) ?> mb-3 d-inline-block"><?= htmlspecialchars( $b['level_name'] ) ?></span>
            <h4 class="h6 fw-bold"><?= Lesson::dayLabel( $b['day_of_week'] ) ?></h4>
            <p class="text-muted small mb-1"><span class="material-symbols-outlined" style="font-size:16px">schedule</span> <?= substr( $b['start_time'], 0, 5 ) ?> - <?= substr( $b['end_time'], 0, 5 ) ?></p>
            <p class="text-muted small mb-0">Prof. <?= htmlspecialchars( trim( $b['coach_first_name'] . ' ' . $b['coach_last_name'] ) ) ?></p>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php endif; ?>

<?php include __DIR__ . '/auth/layout/panel-end.php'; ?>
