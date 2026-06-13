<?php
require_once __DIR__ . '/../../core/ViewHelper.php';
$titulo = 'Profesores';
include __DIR__ . '/../auth/layout/panel-head.php';
include __DIR__ . '/../auth/layout/panel-start.php';
?>

<style>
    .coaches-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 24px;
    }

    .coaches-table thead th {
        background: #111827;
        color: #fff;
        font-weight: 500;
        padding: 14px 18px;
        text-align: left;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .coaches-table thead th:first-child { border-radius: 8px 0 0 0; }
    .coaches-table thead th:last-child  { border-radius: 0 8px 0 0; }

    .coaches-table tbody td {
        padding: 14px 18px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
        color: #374151;
        vertical-align: middle;
    }

    .coaches-table tbody tr:last-child td { border-bottom: none; }
    .coaches-table tbody tr:hover         { background: #f9fafb; }

    .coach-avatar,
    .profile-avatar-initials.coach-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .coach-name  { font-weight: 600; color: #0b1120; }
    .coach-email { font-size: 13px; color: #6b7280; }

    .specialty-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .spec-competitiva { background: #fef9c3; color: #854d0e; }
    .spec-infantil    { background: #cefffd; color: #0d9d9d; }
    .spec-adultos     { background: #dbeafe; color: #1e40af; }
    .spec-recreativa  { background: #f3e8ff; color: #6b21a8; }
    .spec-default     { background: #f3f4f6; color: #374151; }

    .badge-active {
        background: #dcfce7;
        color: #166534;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-active::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #22c55e;
        display: inline-block;
    }

    .empty-msg {
        text-align: center;
        color: #9ca3af;
        padding: 40px;
        font-size: 15px;
    }
</style>

<div class="card-panel p-4">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
            <h1 class="h3 fw-bold mb-0">Profesores</h1>
            <p class="text-muted small mb-0"><?= count($coaches) ?> profesores registrados</p>
        </div>
        <a href="?url=coaches/create" class="btn btn-sm-primary">Agregar profesor</a>
    </div>

    <table class="coaches-table">
        <thead>
            <tr>
                <th>Profesor</th>
                <th>Teléfono</th>
                <th>Especialidad</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( empty( $coaches ) ): ?>
            <tr>
                <td colspan="4" class="empty-msg">No hay profesores registrados todavía.</td>
            </tr>
            <?php else: ?>
            <?php foreach ( $coaches as $coach ): ?>
            <?php
                $specialty = strtolower( $coach['specialty'] ?? '' );
                if ( str_contains($specialty, 'competitiva') )     $badgeClass = 'spec-competitiva';
                elseif ( str_contains($specialty, 'infantil') )    $badgeClass = 'spec-infantil';
                elseif ( str_contains($specialty, 'adultos') )     $badgeClass = 'spec-adultos';
                elseif ( str_contains($specialty, 'recreativa') )  $badgeClass = 'spec-recreativa';
                else                                                $badgeClass = 'spec-default';
            ?>
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <?= ViewHelper::profileAvatar(
                            $coach['first_name'],
                            $coach['last_name'],
                            $coach['profile_image'] ?? null,
                            'coach-avatar',
                            'coaches'
                        ) ?>
                        <div>
                            <div class="coach-name"><?= htmlspecialchars( $coach['first_name'] . ' ' . $coach['last_name'] ) ?></div>
                            <div class="coach-email"><?= htmlspecialchars( $coach['email'] ) ?></div>
                        </div>
                    </div>
                </td>
                <td><?= htmlspecialchars( $coach['phone'] ?? '—' ) ?></td>
                <td><span class="specialty-badge <?= $badgeClass ?>"><?= htmlspecialchars( $coach['specialty'] ?? '—' ) ?></span></td>
                <td><span class="badge-active">Activo</span></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../auth/layout/panel-end.php'; ?>
