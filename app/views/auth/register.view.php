<style>

.login-wrap{
    min-height:80vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:24px 0;
}

.login-card{
    width:100%;
    max-width:720px;
    background:#ffffff;
    border-radius:20px;
    padding:35px;
    box-shadow:0 10px 30px rgba(0,0,0,.10);
}

.login-logo{
    text-align:center;
    margin-bottom:20px;
}

.login-logo span{
    font-size:28px;
    font-weight:700;
}

.login-logo em{
    color:#1a6cf6;
    font-style:normal;
}

.login-title{
    text-align:center;
    font-size:24px;
    font-weight:700;
    margin-bottom:10px;
}

.login-sub{
    text-align:center;
    color:#6c757d;
    margin-bottom:25px;
}

.btn-login{
    width:100%;
    border:none;
    border-radius:10px;
    padding:12px;
    background:#1a6cf6;
    color:white;
    font-weight:600;
    transition:.3s;
}

.btn-login:hover{
    opacity:.9;
    color:white;
}

.login-links{
    text-align:center;
    margin-top:20px;
}

.login-links a{
    text-decoration:none;
}

.form-control{
    border-radius:10px;
}
</style>

<?php include __DIR__ . '/layout/header.php'; ?>

<div class="login-wrap">
    <div class="login-card">

        <div class="login-logo">
            <a href="?url=home" style="text-decoration:none; color:inherit;">
                <svg width="44" height="28" viewBox="0 0 34 22" fill="none" style="margin-bottom:8px">
                    <path d="M2 14c2-4 5-6 8-4s5 6 8 4 5-6 8-4" stroke="#1a6cf6" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                    <path d="M2 19c2-4 5-6 8-4s5 6 8 4 5-6 8-4" stroke="#0bc5c5" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                    <circle cx="17" cy="7" r="2.5" fill="#1a6cf6"/>
                </svg>
                <br>
                <span>Swim<em>Manager</em></span>
            </a>
        </div>

        <div class="login-title">
            <?= htmlspecialchars( $title ?? 'Registro de nadador' ) ?>
        </div>

        <div class="login-sub">
            Completá tus datos para crear tu cuenta.
        </div>

        <form id="formRegister" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="first_name" class="form-control" placeholder="Ej: Juan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email" class="form-control" placeholder="juan@correo.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="Mín. 6 caracteres" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input type="date" name="birth_date" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="last_name" class="form-control" placeholder="Ej: Pérez" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="phone" class="form-control" placeholder="11 1234 5678">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Repetí tu contraseña" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto de perfil</label>
                        <input type="file" name="profile_image" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-login mt-2">
                Crear cuenta
            </button>
        </form>

        <div class="login-links">
            <p class="mb-0">
                ¿Ya tenés cuenta?
                <a href="?url=login">Ingresá aquí</a>
            </p>
        </div>

    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
