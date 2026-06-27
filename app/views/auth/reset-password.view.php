<?php include __DIR__ . '/../auth/layout/header.php'; ?>

<div class='container mt-5'>
    <div class='row justify-content-center'>
        <div class='col-md-5'>

            <div class='card shadow-sm border-0'>
                <div class='card-body p-4'>

                    <h4 class='text-center mb-4'>
                        <i class='bi bi-key'></i> Nueva contraseña
                    </h4>

                    <p class='text-muted text-center small mb-4'>
                        Estás a un paso de recuperar tu acceso. Elegí una contraseña segura.
                    </p>

                    <form id='formResetPassword' action='?url=update-password' method='POST'>

                        <input type='hidden' name='token' value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

                        <!-- Nueva contraseña -->
                        <div class="mb-3">
                            <label class="form-label">Nueva contraseña</label>

                            <div class="input-group">
                                <input
                                    type="password"
                                    name="password"
                                    id="reset_pass"
                                    class="form-control"
                                    placeholder="Mínimo 6 caracteres"
                                    minlength="6"
                                    required>

                                <button type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="togglePassword('reset_pass')">
                                    👁
                                </button>
                            </div>
                        </div>

                        <!--  Confirmar contraseña -->
                        <div class="mb-3">
                            <label class="form-label">Confirmar contraseña</label>

                            <div class="input-group">
                                <input
                                    type="password"
                                    name="confirm_password"
                                    id="reset_confirm"
                                    class="form-control"
                                    placeholder="Repetí tu contraseña"
                                    required>

                                <button type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="togglePassword('reset_confirm')">
                                    👁
                                </button>
                            </div>
                        </div>

                        <button type="submit" class='btn btn-primary w-100 py-2'>
                            Actualizar contraseña
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../auth/layout/footer.php'; ?>