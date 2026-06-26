<?php
$titulo = 'Nuevo Profesor';
include __DIR__ . '/../auth/layout/panel-head.php';
include __DIR__ . '/../auth/layout/panel-start.php';
?>

<style>
    .form-card {
        max-width: 680px;
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

    .btn-back {
        color: #6b7280;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 20px;
    }

    .btn-back:hover { color: #1a6cf6; }
</style>

<div class="card-panel p-4 form-card">

    <a href="?url=coaches" class="btn-back">← Volver a Profesores</a>

    <h1 class="h4 fw-bold mb-1">Nuevo Profesor</h1>
    <p class="text-muted small mb-4">Completá los datos para crear el perfil del profesor. Se le enviará un email con su contraseña provisoria.</p>

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

        <button type="submit" class="btn btn-sm-primary w-100 mt-2">Crear Profesor</button>
    </form>

</div>

<?php include __DIR__ . '/../auth/layout/panel-end.php'; ?>
