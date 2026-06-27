</main>

<nav class="panel-mobile-nav d-md-none">
    <?php foreach ( $navItems ?? [] as $item ):
        $isActive = ( $_GET['url'] ?? 'home' ) === $item['url'];
    ?>
    <a href="?url=<?= htmlspecialchars( $item['url'] ) ?>" class="<?= $isActive ? 'active' : '' ?>">
        <span class="material-symbols-outlined <?= $isActive ? 'icon-fill' : '' ?>"><?= $item['icon'] ?></span>
        <?= htmlspecialchars( $item['label'] ) ?>
    </a>
    <?php endforeach; ?>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="<?= rtrim( Env::get( 'ASSET_URL' ), '/' ) ?>/js/modules/authMain.js?v=4"></script>
<script type="module" src="<?= rtrim( Env::get( 'ASSET_URL' ), '/' ) ?>/js/modules/appMain.js?v=4"></script>
</body>
</html>
