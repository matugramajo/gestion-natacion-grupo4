<?php include __DIR__ . '/auth/layout/header.php'; ?>
    <style>
        main {
            padding-top: 0 !important;
        }
        

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-logo span {
            font-size: 21px;
            font-weight: 700;
            color: #0b1120;
        }

        .nav-logo span em {
            color: #1a6cf6;
            font-style: normal;
        }

        .btn-nav-login {
            border: 1.5px solid #1a6cf6;
            color: #1a6cf6;
            padding: 7px 21px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
        }

        .btn-nav-register {
            background: #1a6cf6;
            color: #fff;
            padding: 7px 21px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
        }

        .hero {
            min-height: 100vh;
            padding-top: 0px;
            background: linear-gradient(135deg, #e8f0fe 0%, #f0f9ff 50%, #e0f7fa 100%);
            display: flex;
            align-items: center;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(26,108,246,0.1);
            color: #1a6cf6;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 19px;
        }

        .hero h1 {
            font-size: 45px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 19px;
        }

        .hero h1 em {
            font-style: normal;
            color: #1a6cf6;
        }

        .hero p.lead {
            font-size: 18px;
            color: #6b7280;
            max-width: 480px;
            margin-bottom: 32px;
            line-height: 1.7;
        }

        .btn-hero-main {
            background: #1a6cf6;
            color: #fff;
            padding: 13px 32px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
        }

        .hero-img {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.18);
        }

        .features {
            padding: 80px 0;
            background: #fff;
        }

        .features h2 {
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
        }

        .features .sub {
            text-align: center;
            color: #6b7280;
            margin-bottom: 48px;
        }

        .feature-card {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 29px;
            height: 100%;
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: #f0f4ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }

        .feature-card h5 {
            font-weight: 700;
            margin-bottom: 8px;
        }

        .feature-card p {
            color: #6b7280;
            font-size: 15px;
            line-height: 1.6;
            margin: 0;
        }

        .schedule {
            padding: 80px 0;
            background: #f0f4ff;
        }

        .schedule h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .schedule .sub {
            color: #6b7280;
        }

        .schedule-table {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }

        .schedule-table table {
            margin: 0;
        }

        .schedule-table thead th {
            background: #111827;
            color: #fff;
            font-weight: 500;
            padding: 16px 19px;
            border: none;
        }

        .schedule-table tbody td {
            padding: 14px 19px;
            vertical-align: middle;
            border-color: #f3f4f6;
        }

        .level-badge {
            padding: 4px 11px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .level-prin {
             background: #dcfce7; color: #166534; 
        }
        .level-inter { 
            background: #dbeafe; color: #1e40af; 
        }
        .level-avanz { 
            background: #fef9c3; color: #854d0e; 
        }
        .level-comp { 
            background: #f2ad9f; color: #d23c1a; 
        }

        .ver-todo {
            color: #1a6cf6;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .cta {
            background: #111827;
            color: #fff;
            padding: 80px 0;
            text-align: center;
        }

        .cta h2 {
            font-size: 29px;
            font-weight: 800;
            margin-bottom: 13px;
        }

        .cta p {
            color: #9ca3af;
            margin-bottom: 32px;
            font-size: 17px;
            max-width: 560px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-cta {
            background: #1a6cf6;
            color: #fff;
            padding: 14px 40px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 17px;
            text-decoration: none;
        }

        .footer-landing {
            background: #0b1120;
            color: #6b7280;
            padding: 32px 0;
            font-size: 14px;
        }
    </style>

<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge">Plataforma oficial</div>
                <h1>Tu pasión por el agua, <em>gestionada con excelencia</em></h1>
                <p class="lead">Descubrí la escuela de natación que combina técnica profesional, instalaciones de primera clase y una plataforma intuitiva para gestionar tu progreso acuático.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="?url=register" class="btn-hero-main">Empezar ahora →</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img class="hero-img" src="<?= Env::get('ASSET_URL') ?>img/imagenLanding.jpg" alt="Pileta profesional">
            </div>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2>Todo lo que necesitás para nadar mejor</h2>
        <p class="sub">Nuestra metodología integra entrenamiento humano excepcional con tecnología de punta.</p>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">🏊</div>
                    <h5>Clases para todos los niveles</h5>
                    <p>Desde el primer contacto con el agua hasta nadadores avanzados. Principiante, Intermedio y Competitivo.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">🏅</div>
                    <h5>Entrenadores certificados</h5>
                    <p>Profesionales enfocados en la biomecánica y desarrollo atlético. Cada clase es una oportunidad para mejorar.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h5>Gestión online simple</h5>
                    <p>Reservá clases, consultá el historial de asistencia y gestioná cuotas desde cualquier dispositivo.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="schedule">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <h2>Próximas Clases</h2>
                <p class="sub mb-0">Horarios estructurados para mantener el ritmo.</p>
            </div>
            <a href="?url=login" class="ver-todo">Ver horario completo →</a>
        </div>
        <div class="schedule-table">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nivel</th>
                        <th>Día</th>
                        <th>Horario</th>
                        <th>Entrenador</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="level-badge level-prin">Principiante</span></td>
                        <td>Lunes y Miércoles</td>
                        <td>16:00 a 17:00</td>
                        <td>Prof. Cami</td>
                    </tr>
                    <tr>
                        <td><span class="level-badge level-inter">Intermedio</span></td>
                        <td>Martes y Jueves</td>
                        <td>17:00 a 18:00</td>
                        <td>Prof. Martu</td>
                    </tr>
                    <tr>
                        <td><span class="level-badge level-avanz">Avanzado</span></td>
                        <td>Lunes a Viernes</td>
                        <td>19:30 a 21:00</td>
                        <td>Prof. Matu</td>
                    </tr>
                    <tr>
                        <td><span class="level-badge level-comp">Competitivo</span></td>
                        <td>Sabado</td>
                        <td>9:00 a 10:00</td>
                        <td>Prof. Juan</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="cta">
    <div class="container text-center">
        <h2>¿Listo para sumergirte?</h2>
        <p>Unite a nuestro club hoy mismo. Asegurá tu lugar en las próximas clases y comenzá a gestionar tu entrenamiento.</p>
        <a href="?url=register" class="btn-cta">Anotate al club</a>
    </div>
</section>
<?php include __DIR__ . '/auth/layout/footer.php'; ?>