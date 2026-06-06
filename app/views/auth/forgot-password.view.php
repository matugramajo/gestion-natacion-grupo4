<?php $titulo = 'Recuperar contraseña - SwimManager'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars( $titulo ) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap" rel="stylesheet">
    <link href="<?= rtrim( Env::get( 'ASSET_URL' ), '/' ) ?>/css/panel.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>body { font-family: 'Outfit', sans-serif; background: var(--sm-bg, #faf8ff); }</style>
</head>
<body class="min-vh-100 d-flex align-items-center justify-content-center p-3">

<div class="card-panel auth-card-panel bg-white p-4 p-md-5">
    <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle mx-auto mb-4" style="width:64px;height:64px">
        <span class="material-symbols-outlined icon-fill fs-2">pool</span>
    </div>
    <div class="text-center mb-4">
        <h1 class="h4 fw-bold">Recuperar contraseña</h1>
        <p class="text-muted small mb-0">Ingresá tu correo y te enviaremos un enlace para restablecerla.</p>
    </div>
    <form id="formForgotPassword" action="?url=send-reset" method="POST">
        <div class="mb-3">
            <label class="form-label small fw-semibold" for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" required placeholder="tu@email.com" class="form-control">
        </div>
        <button type="submit" class="btn btn-sm-primary w-100 d-flex align-items-center justify-content-center gap-2">
            Enviar instrucciones <span class="material-symbols-outlined">arrow_forward</span>
        </button>
    </form>
    <div class="text-center mt-4 pt-3 border-top">
        <a href="?url=login" class="text-primary text-decoration-none small d-inline-flex align-items-center gap-1">
            <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Volver al login
        </a>
    </div>
</div>

<script type="module" src="<?= rtrim( Env::get( 'ASSET_URL' ), '/' ) ?>/js/modules/authMain.js"></script>
</body></html>
