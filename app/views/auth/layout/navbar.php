<?php
require_once __DIR__ . '/../../../core/ViewHelper.php';

$navVariant = $navVariant ?? ( isset( $_SESSION['user_id'] ) ? 'authenticated' : 'guest' );
$navExtraClass = $navExtraClass ?? '';
$roleId = (int) ( $_SESSION['role_id'] ?? 0 );
$firstNameRaw = $firstName ?? $_SESSION['first_name'] ?? 'Usuario';
$lastNameRaw = $_SESSION['last_name'] ?? '';
$firstName = htmlspecialchars( $firstNameRaw );
$profileImage = $_SESSION['profile_image'] ?? null;
$isPanelNav = $navVariant === 'panel';
?>
<?php if ( $isPanelNav ): ?>

<nav class="nav-main nav-panel d-none d-md-flex align-items-center <?= htmlspecialchars( $navExtraClass ) ?>">
    <div class="nav-panel-bar">
        <div class="nav-panel-logo-zone">
            <a href="?url=home" class="nav-logo">
                <svg width="34" height="22" viewBox="0 0 34 22" fill="none" aria-hidden="true">
                    <path d="M2 14c2-4 5-6 8-4s5 6 8 4 5-6 8-4" stroke="#1a6cf6" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                    <path d="M2 19c2-4 5-6 8-4s5 6 8 4 5-6 8-4" stroke="#0bc5c5" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                    <circle cx="17" cy="7" r="2.5" fill="#1a6cf6"/>
                </svg>
                <span>Swim<em>Manager</em></span>
            </a>
        </div>
        <div class="nav-panel-user">
            <span class="nav-greeting">Hola, <?= $firstName ?></span>
            <a href="?url=logout" class="btn-nav-logout">Salir</a>
            <?= ViewHelper::profileAvatar( $firstNameRaw, $lastNameRaw, $profileImage ) ?>
        </div>
    </div>
</nav>

<?php else: ?>

<nav class="nav-main <?= htmlspecialchars( $navExtraClass ) ?>">
    <div class="container">
        <a href="?url=home" class="nav-logo">
            <svg width="34" height="22" viewBox="0 0 34 22" fill="none" aria-hidden="true">
                <path d="M2 14c2-4 5-6 8-4s5 6 8 4 5-6 8-4" stroke="#1a6cf6" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                <path d="M2 19c2-4 5-6 8-4s5 6 8 4 5-6 8-4" stroke="#0bc5c5" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                <circle cx="17" cy="7" r="2.5" fill="#1a6cf6"/>
            </svg>
            <span>Swim<em>Manager</em></span>
        </a>
        <div class="d-flex align-items-center gap-3">

            <?php if ( $navVariant === 'guest' ): ?>

                <a href="?url=login" class="btn-nav-login">Ingresar</a>
                <a href="?url=register" class="btn-nav-register">Registrarse</a>

            <?php else: ?>

                <a href="?url=home" class="nav-link-item">Dashboard</a>

                <?php if ( $roleId === 1 ): ?>
                    <a href="?url=swimmers" class="nav-link-item">Nadadores</a>
                    <a href="?url=coaches" class="nav-link-item">Profesores</a>
                    <a href="?url=admin-lessons" class="nav-link-item">Clases</a>
                <?php elseif ( $roleId === 2 ): ?>
                    <a href="?url=coach-students" class="nav-link-item">Mis alumnos</a>
                    <a href="?url=coach-profile" class="nav-link-item">Mi perfil</a>
                <?php elseif ( $roleId === 3 ): ?>
                    <a href="?url=swimmer-lessons" class="nav-link-item">Clases</a>
                    <a href="?url=swimmer-profile" class="nav-link-item">Mi perfil</a>
                <?php endif; ?>

                <?= ViewHelper::profileAvatar( $firstNameRaw, $lastNameRaw, $profileImage ) ?>
                <span class="nav-greeting">Hola, <?= $firstName ?></span>
                <a href="?url=logout" class="btn-nav-logout">Cerrar sesión</a>

            <?php endif; ?>

        </div>
    </div>
</nav>

<?php endif; ?>
