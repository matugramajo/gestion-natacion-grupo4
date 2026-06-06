<?php include __DIR__ . '/../auth/layout/header.php'; ?>

<style>
    body {
        background: #f0f4ff;
        padding-top: 0px;
    }

    .form-wrap {
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 0;
    }

    .form-card {
        width: 100%;
        max-width: 680px;
        background: #ffffff;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,.08);
        border-top: 4px solid #1a6cf6;
    }

    .form-title {
        font-size: 24px;
        font-weight: 700;
        color: #0b1120;
        margin-bottom: 6px;
    }

    .form-sub {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 28px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #e5e7eb;
        padding: 10px 14px;
        font-size: 14px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #1a6cf6;
        box-shadow: 0 0 0 3px rgba(26,108,246,0.1);
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .btn-submit {
        width: 100%;
        border: none;
        border-radius: 10px;
        padding: 13px;
        background: #1a6cf6;
        color: white;
        font-weight: 600;
        font-size: 15px;
        transition: .3s;
        margin-top: 8px;
    }

    .btn-submit:hover {
        background: #0d4fd6;
        color: white;
    }

    .btn-back {
        color: #6b7280;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 20px;
    }

    .btn-back:hover {
        color: #1a6cf6;
    }
</style>

<div class="form-wrap">
    <div class="form-card">

        <a href="?url=coaches" class="btn-back">← Volver a Profesores</a>
        <div class="form-title">Nuevo Profesor</div>
        <div class="form-sub">Completá los datos para crear el perfil del profesor. Se le enviará un email con su contraseña provisoria.</div>

        <form id="formCreateCoach" action="?url=coaches/store" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="first_name" class="form-control" placeholder="Ej: Juan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="juan@correo.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Especialidad</label>
                        <select name="specialty_id" class="form-select" required>
                            <option value="">Seleccioná una especialidad</option>
                            <?php foreach ( $specialties as $s ): ?>
                            <option value="<?php echo $s['id']; ?>">
                                <?php echo htmlspecialchars( $s['name'] ); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
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
                </div>
            </div>

            <button type="submit" class="btn-submit">Crear Profesor</button>
        </form>

    </div>
</div>

<?php include __DIR__ . '/../auth/layout/footer.php'; ?>