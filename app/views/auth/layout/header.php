<!DOCTYPE html>

<html lang="es">



<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $titulo ?? 'SwimManager' ?></title>



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="<?= rtrim( Env::get( 'ASSET_URL' ), '/' ) ?>/css/navbar.css?v=3" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <style>

        body { font-family: 'Outfit', sans-serif; }

    </style>

</head>



<body>



<?php

$url = $_GET['url'] ?? '';

$hideNav = in_array( $url, [ 'login', 'register', 'forgot-password', 'reset-password' ], true );

?>



<?php if ( !$hideNav ): ?>

    <?php

    $navVariant = isset( $_SESSION['user_id'] ) ? 'authenticated' : 'guest';

    include __DIR__ . '/navbar.php';

    ?>

<?php endif; ?>

<main>

