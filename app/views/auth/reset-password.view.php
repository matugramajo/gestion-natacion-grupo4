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
.form-control { border-radius:10px; }
</style>

<?php include __DIR__ . '/../auth/layout/header.php'; ?>

<div class="login-wrap">
    <div class="login-card">

        <h4 class="text-center mb-4 fw-bold">Nueva contraseña</h4>

        <p class="text-muted text-center small mb-4">
            Estás a un paso de recuperar tu acceso. Elegí una contraseña segura.
        </p>

        <form id="formResetPassword" action="?url=update-password" method="POST">

            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

            <div class="mb-3">
                <label class="form-label">Nueva contraseña</label>
                <div class="input-group">
                    <input type="password" name="password" id="reset_pass" class="form-control"
                        placeholder="Mínimo 6 caracteres" minlength="6" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('reset_pass')">👁</button>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar contraseña</label>
                <div class="input-group">
                    <input type="password" name="confirm_password" id="reset_confirm" class="form-control"
                        placeholder="Repetí tu contraseña" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('reset_confirm')">👁</button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2" style="background:#1a6cf6;border-color:#1a6cf6;border-radius:10px;font-weight:600;">
                Actualizar contraseña
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="?url=login" class="text-muted small text-decoration-none">Volver al login</a>
        </div>

    </div>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
<?php include __DIR__ . '/../auth/layout/footer.php'; ?>