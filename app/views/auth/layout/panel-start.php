<?php
require_once __DIR__ . '/../../../core/ViewHelper.php';
$activeRoute = $_GET['url'] ?? 'home';
$roleId = (int) ( $_SESSION['role_id'] ?? 0 );
$firstName = htmlspecialchars( $_SESSION['first_name'] ?? 'Usuario' );
$foto = $_SESSION['profile_image'] ?? 'default-profile.png';
$avatarUrl = rtrim( Env::get( 'ASSET_URL' ), '/' ) . '/img/uploads/profiles/swimmers/' . rawurlencode( $foto );

$navItems = [];
if ( $roleId === Role::ADMIN ) {
    $navItems = [
        [ 'url' => 'home', 'label' => 'Dashboard', 'icon' => 'dashboard' ],
        [ 'url' => 'admin-coaches', 'label' => 'Coaches', 'icon' => 'sports' ],
        [ 'url' => 'admin-lessons', 'label' => 'Clases', 'icon' => 'calendar_month' ],
        [ 'url' => 'swimmers', 'label' => 'Nadadores', 'icon' => 'group' ],
    ];
} elseif ( $roleId === Role::COACH ) {
    $navItems = [
        [ 'url' => 'home', 'label' => 'Dashboard', 'icon' => 'dashboard' ],
        [ 'url' => 'coach-students', 'label' => 'Alumnos', 'icon' => 'groups' ],
        [ 'url' => 'coach-profile', 'label' => 'Perfil', 'icon' => 'person' ],
    ];
} else {
    $navItems = [
        [ 'url' => 'home', 'label' => 'Dashboard', 'icon' => 'dashboard' ],
        [ 'url' => 'swimmer-lessons', 'label' => 'Clases', 'icon' => 'pool' ],
        [ 'url' => 'swimmer-profile', 'label' => 'Perfil', 'icon' => 'person' ],
    ];
}
?>
<header class="panel-topnav d-none d-md-block">
    <div class="container-fluid h-100 px-4">
        <div class="d-flex justify-content-between align-items-center h-100">
            <a href="?url=home" class="brand d-flex align-items-center gap-2">
                <span class="material-symbols-outlined icon-fill text-white">water</span>
                SwimManager
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white-50 d-none d-lg-inline small">Hola, <?= $firstName ?></span>
                <a href="?url=logout" class="logout-link">Salir</a>
                <img src="<?= $avatarUrl ?>" alt="Perfil" class="panel-avatar">
            </div>
        </div>
    </div>
</header>

<aside class="panel-sidebar d-none d-md-flex">
    <nav class="w-100">
        <?php foreach ( $navItems as $item ): ?>
        <a href="?url=<?= htmlspecialchars( $item['url'] ) ?>" class="<?= ViewHelper::sidebarLinkClass( $item['url'], $activeRoute ) ?>">
            <span class="material-symbols-outlined <?= $item['url'] === $activeRoute ? 'icon-fill' : '' ?>"><?= $item['icon'] ?></span>
            <?= htmlspecialchars( $item['label'] ) ?>
        </a>
        <?php endforeach; ?>
    </nav>
</aside>

<main class="panel-main container-fluid">
