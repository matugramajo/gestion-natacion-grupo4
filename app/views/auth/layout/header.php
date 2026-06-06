<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?? 'SwimManager' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; }

        .nav-main {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 100;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            padding: 16px 0;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-logo span {
            font-size: 20px;
            font-weight: 700;
            color: #0b1120;
        }

        .nav-logo span em {
            color: #1a6cf6;
            font-style: normal;
        }

        .nav-link-item {
            color: #374151;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 8px;
            transition: background .2s;
        }

        .nav-link-item:hover {
            background: #f3f4f6;
            color: #0b1120;
        }

        .btn-nav-login {
            border: 1.5px solid #9ca3af;
            color: #374151;
            padding: 6px 18px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-nav-login:hover { background: #1a6cf6; color: #fff; }

        .btn-nav-register {
            background: #1a6cf6;
            color: #fff;
            padding: 6px 18px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-nav-register:hover { background: #0d4fd6; color: #fff; }

        .btn-nav-logout {
            border: 1.5px solid #e6e5eb;
            color: #6b7280;
            background: #f3f4f6;
            padding: 6px 18px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-nav-logout:hover { background: #0d4fd6; color: #fff; }

        .profile-img-nav {
            width: 34px;
            height: 34px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #1a6cf6;
        }

        .nav-greeting {
            font-size: 14px;
            font-weight: 600;
            color: #1a6cf6;
        }
    </style>
</head>

<body>

<?php
$url = $_GET['url'] ?? '';
$hideNav = in_array( $url, [ 'login', 'register', 'forgot-password', 'reset-password' ], true );
$roleId = (int) ( $_SESSION['role_id'] ?? 0 );
?>

<?php if ( !$hideNav ): ?>
<nav class="nav-main">
    <div class="container d-flex align-items-center justify-content-between">

        <a href="?url=home" class="nav-logo">
            <svg width="34" height="22" viewBox="0 0 34 22" fill="none">
                <path d="M2 14c2-4 5-6 8-4s5 6 8 4 5-6 8-4" stroke="#1a6cf6" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                <path d="M2 19c2-4 5-6 8-4s5 6 8 4 5-6 8-4" stroke="#0bc5c5" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                <circle cx="17" cy="7" r="2.5" fill="#1a6cf6"/>
            </svg>
            <span>Swim<em>Manager</em></span>
        </a>

        <div class="d-flex align-items-center gap-3">

            <?php if ( isset( $_SESSION['user_id'] ) ): ?>

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

                <?php
                    $foto = $_SESSION['profile_image'] ?? 'default-profile.png';
                    $rutaFoto = rtrim( Env::get( 'ASSET_URL' ), '/' ) . '/img/uploads/profiles/swimmers/' . $foto;
                ?>
                <img src="<?= $rutaFoto ?>" alt="Perfil" class="profile-img-nav">
                <span class="nav-greeting">Hola, <?= htmlspecialchars( $_SESSION['first_name'] ?? 'Usuario' ) ?></span>
                <a href="?url=logout" class="btn-nav-logout">Cerrar sesión</a>

            <?php else: ?>

                <a href="?url=login" class="btn-nav-login">Ingresar</a>
                <a href="?url=register" class="btn-nav-register">Registrarse</a>

            <?php endif; ?>

        </div>
    </div>
</nav>
<?php endif; ?>
<main style="padding-top: 80px;">
