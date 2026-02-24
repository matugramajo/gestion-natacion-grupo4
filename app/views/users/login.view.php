<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-4">Iniciar Sesión</h3>
                    <form id="formLogin" action="?url=authenticate" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Entrar</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="?url=forgot-password">Olvidé mi contraseña</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="js/modules/auth/auth.js"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>