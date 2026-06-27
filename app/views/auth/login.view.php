<style>

.login-wrap{
    min-height:80vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:24px 16px;
}

.login-card{
    width:100%;
    max-width:430px;
    background:#ffffff;
    border-radius:20px;
    padding:28px 20px;
    box-shadow:0 10px 30px rgba(0,0,0,.10);
}

@media (min-width: 576px) {
    .login-card { padding:35px; }
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

<?php include __DIR__ . '/../auth/layout/header.php'; ?>



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
            Iniciar Sesión
        </div>

        <div class="login-sub">
            Ingresá tus credenciales para acceder.
        </div>

        <form id="formLogin">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="correo@ejemplo.com"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>

                <div class="input-group">
                    <input
                        type="password"
                        name="password"
                        id="login_pass"
                        class="form-control"
                        placeholder="********"
                        required>

                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('login_pass')">
                        👁
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Entrar
            </button>

        </form>

        <div class="login-links">
            <p class="mb-1">
                ¿No tienes cuenta?
                <a href="?url=register">Regístrate aquí</a>
            </p>

            <a href="?url=forgot-password" class="text-muted small">
                Olvidé mi contraseña
            </a>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../auth/layout/footer.php'; ?>