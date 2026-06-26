<?php
require_once __DIR__ . '/../../../core/ViewHelper.php';
$activeRoute = $_GET['url'] ?? 'home';
$roleId = (int) ( $_SESSION['role_id'] ?? 0 );
$firstName = htmlspecialchars( $_SESSION['first_name'] ?? 'Usuario' );

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
<?php
$navVariant = 'panel';
$navExtraClass = '';
include __DIR__ . '/navbar.php';
?>

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
